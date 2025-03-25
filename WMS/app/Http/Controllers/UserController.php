<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Requests\PasswordResetRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Notifications\UserCreated;
use App\Notifications\UserUpdated;
use App\Notifications\UserDeleted;
use App\Notifications\PasswordChanged;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return response()->json($users);
    }

    // Updated indexView method for search, filtering, and sorting
    public function indexView(Request $request)
    {
        $query = User::query();

        // Search filter: checks first_name, last_name, username, and email fields
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'LIKE', '%' . $search . '%')
                  ->orWhere('last_name', 'LIKE', '%' . $search . '%')
                  ->orWhere('username', 'LIKE', '%' . $search . '%')
                  ->orWhere('email', 'LIKE', '%' . $search . '%');
            });
        }

        // Role filter: only filter if a role is provided
        if ($request->filled('role')) {
            $query->where('role', $request->input('role'));
        }

        // Sorting: default is by name (first then last), but can also sort by role or created_at date.
        $sort = $request->input('sort', 'name');
        if ($sort === 'name') {
            $query->orderBy('first_name')->orderBy('last_name');
        } elseif ($sort === 'role') {
            $query->orderBy('role');
        } elseif ($sort === 'created') {
            $query->orderBy('created_at', 'desc');
        }

        // Paginate results; adjust the per-page limit as needed
        $users = $query->paginate(10);

        // Check if a reset_user parameter is present to open the reset modal
        $resetUser = null;
        if ($request->has('reset_user')) {
            $resetUser = User::find($request->input('reset_user'));
        }

        // Append current query parameters to the pagination links if needed:
        // $users->appends($request->query());

        return view('admin.user_management.index', compact('users', 'resetUser'));
    }

    public function store(UserStoreRequest $request)
    {
        try {
            Log::info('User creation request received', $request->all());
            $validated = $request->validated();
            Log::info('Validation passed', $validated);

            $plainPassword = $validated['password'];
            $validated['password'] = Hash::make($validated['password']);

            $user = User::create($validated);
            Log::info('User created successfully', ['user_id' => $user->id]);

            $user->notify(new UserCreated($user, $plainPassword));
            Log::info('Account creation notification sent', ['user_id' => $user->id]);

            if ($request->expectsJson()) {
                return response()->json($user, 201);
            }

            return redirect()->route('admin.user_management.index')
                             ->with('success', 'User created successfully and notification sent');
        } catch (\Exception $e) {
            Log::error('Error creating user: ' . $e->getMessage(), [
                'exception' => $e,
                'request_data' => $request->all()
            ]);
            
            return redirect()->route('admin.user_management.index')
                ->withInput()
                ->withErrors(['general' => 'An error occurred while creating the user: ' . $e->getMessage()]);
        }
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user);
    }

    public function update(UserUpdateRequest $request, $id)
    {
        try {
            $user = User::findOrFail($id);
            $originalData = $user->getOriginal();
            $validated = $request->validated();
            
            $passwordChanged = false;
            $plainPassword = null;
            
            if (isset($validated['password']) && !empty($validated['password'])) {
                $passwordChanged = true;
                $plainPassword = $validated['password'];
                $validated['password'] = Hash::make($validated['password']);
            }
            
            $user->update($validated);
            
            $changedFields = [];
            foreach ($validated as $field => $value) {
                if ($field === 'password') {
                    continue;
                }
                if (isset($originalData[$field]) && $originalData[$field] !== $value) {
                    $changedFields[$field] = $value;
                }
            }
            
            if ($passwordChanged) {
                $changedFields['password'] = $plainPassword;
            }
            
            // Prevent duplicate notifications by using a cache key with a short TTL
            $cacheKey = 'user_updated_' . $user->id;
            
            if (!empty($changedFields) && !Cache::has($cacheKey)) {
                // Set a cache key that expires after 5 seconds to prevent duplicate notifications
                Cache::put($cacheKey, true, now()->addSeconds(5));
                
                $user->notify(new UserUpdated($user, $changedFields));
                Log::info('User update notification sent', ['user_id' => $user->id, 'fields_changed' => array_keys($changedFields)]);
            } else if (Cache::has($cacheKey)) {
                Log::info('Duplicate user update notification prevented', ['user_id' => $user->id]);
            }
            
            if ($request->expectsJson()) {
                return response()->json($user);
            }
            
            return redirect()->route('admin.user_management.index')
                             ->with('success', 'User updated successfully' . (!empty($changedFields) ? ' and notification sent' : ''));
        } catch (\Exception $e) {
            Log::error('Error updating user: ' . $e->getMessage(), [
                'exception' => $e,
                'request_data' => $request->all()
            ]);
            
            return redirect()->route('admin.user_management.index')
                ->withInput()
                ->withErrors(['general' => 'An error occurred while updating the user: ' . $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            
            // Create a direct notification instance with user data before deleting
            Notification::route('mail', $user->email)
                ->notify(new UserDeleted($user));
            
            Log::info('User deletion notification queued', ['user_id' => $user->id, 'email' => $user->email]);
            
            $user->delete();
            
            if (request()->expectsJson()) {
                return response()->json(null, 204);
            }
            
            return redirect()->route('admin.user_management.index')
                             ->with('success', 'User deleted successfully and notification sent');
        } catch (\Exception $e) {
            Log::error('Error deleting user: ' . $e->getMessage(), [
                'exception' => $e,
                'user_id' => $id
            ]);
            
            return redirect()->route('admin.user_management.index')
                ->withErrors(['general' => 'An error occurred while deleting the user: ' . $e->getMessage()]);
        }
    }

    public function resetPassword(PasswordResetRequest $request, $id)
    {
        try {
            Log::info('Password reset attempt', [
                'user_id' => $id,
                'request_type' => $request->expectsJson() || $request->ajax() ? 'AJAX' : 'Regular'
            ]);
            
            $user = User::findOrFail($id);
            $validated = $request->validated();
            $plainPassword = $validated['password'];
            
            // Update the user's password
            $user->password = Hash::make($plainPassword);
            $user->save();
            
            // Send notification to the user with their new password
            try {
                Log::info('Sending password changed notification to user', [
                    'user_id' => $user->id, 
                    'email' => $user->email
                ]);
                
                // Prevent duplicate notifications by using a cache key with a short TTL
                $cacheKey = 'password_changed_' . $user->id;
                
                if (!Cache::has($cacheKey)) {
                    // Set a cache key that expires after 5 seconds to prevent duplicate notifications
                    Cache::put($cacheKey, true, now()->addSeconds(5));
                    
                    // Send notification to the user
                    $user->notify(new PasswordChanged($user, $plainPassword));
                    
                    Log::info('Password changed notification sent successfully');
                } else {
                    Log::info('Duplicate password changed notification prevented', ['user_id' => $user->id]);
                }
            } catch (\Exception $e) {
                Log::error('Error sending password changed notification: ' . $e->getMessage(), [
                    'exception' => $e
                ]);
                // Continue with success even if notification fails
            }
            
            Log::info('Password reset successfully for user', ['user_id' => $user->id]);
            
            if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Password reset successfully. An email with the new password has been sent to the user.'
                ]);
            }
            
            return redirect()->route('admin.user_management.index')
                ->with('success', 'Password reset successfully. An email with the new password has been sent to the user.');
        } catch (\Exception $e) {
            Log::error('Error resetting password: ' . $e->getMessage(), [
                'exception' => $e,
                'user_id' => $id
            ]);
            
            if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred while resetting the password',
                    'error' => $e->getMessage()
                ], 500);
            }
            
            return redirect()->route('admin.user_management.index')
                ->withErrors(['general' => 'An error occurred while resetting the password: ' . $e->getMessage()]);
        }
    }
    
    public function resetPasswordForm(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);
            $password = Str::random(12);
            
            $user->password = Hash::make($password);
            $user->save();
            
            // Send notification to the user with their new password
            try {
                Log::info('Sending password changed notification to user', [
                    'user_id' => $user->id, 
                    'email' => $user->email
                ]);
                
                // Prevent duplicate notifications by using a cache key with a short TTL
                $cacheKey = 'password_reset_' . $user->id;
                
                if (!Cache::has($cacheKey)) {
                    // Set a cache key that expires after 5 seconds to prevent duplicate notifications
                    Cache::put($cacheKey, true, now()->addSeconds(5));
                
                    // Send notification to the user
                    $user->notify(new PasswordChanged($user, $password));
                    
                    // Remove direct mail method to prevent duplicate emails
                    
                    Log::info('Password changed notification sent successfully');
                } else {
                    Log::info('Duplicate password changed notification prevented', ['user_id' => $user->id]);
                }
            } catch (\Exception $e) {
                Log::error('Error sending password changed notification: ' . $e->getMessage(), [
                    'exception' => $e
                ]);
            }
            
            Log::info('Password reset successfully for user', ['user_id' => $user->id]);
            
            return redirect()->route('admin.user_management.index')
                ->with('success', 'Password reset successfully. An email with the new password has been sent to the user.');
        } catch (\Exception $e) {
            Log::error('Error resetting password: ' . $e->getMessage(), [
                'exception' => $e,
                'user_id' => $id
            ]);
            
            return redirect()->route('admin.user_management.index')
                ->withErrors(['general' => 'An error occurred while resetting the password: ' . $e->getMessage()]);
        }
    }
}
