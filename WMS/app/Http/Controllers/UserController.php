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

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return response()->json($users);
    }

    public function indexView()
    {
        $users = User::all();
        return view('admin.user_management.index', compact('users'));
    }

    public function store(UserStoreRequest $request)
    {
        try {
            // Log the request for debugging
            Log::info('User creation request received', $request->all());
            
            // Get validated data
            $validated = $request->validated();
            Log::info('Validation passed', $validated);

            // Save the plain password for notification
            $plainPassword = $validated['password'];

            // Hash the password
            $validated['password'] = Hash::make($validated['password']);

            // Create the user
            $user = User::create($validated);
            Log::info('User created successfully', ['user_id' => $user->id]);

            // Send account creation notification with credentials
            $user->notify(new UserCreated($user, $plainPassword));
            Log::info('Account creation notification sent', ['user_id' => $user->id]);

            if ($request->expectsJson()) {
                return response()->json($user, 201);
            }

            return redirect()->route('admin.user_management.index')->with('success', 'User created successfully and notification sent');
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
            
            // Store original user data to detect changes
            $originalData = $user->getOriginal();
            
            // Get validated data
            $validated = $request->validated();
            
            // Check for password update separately
            $passwordChanged = false;
            $plainPassword = null;
            
            if (isset($validated['password']) && !empty($validated['password'])) {
                $passwordChanged = true;
                $plainPassword = $validated['password'];
                $validated['password'] = Hash::make($validated['password']);
            }
            
            // Update the user
            $user->update($validated);
            
            // Determine which fields were changed
            $changedFields = [];
            foreach ($validated as $field => $value) {
                // Skip password as we handle it separately
                if ($field === 'password') {
                    continue;
                }
                
                // If field was changed, add it to changed fields
                if (isset($originalData[$field]) && $originalData[$field] !== $value) {
                    $changedFields[$field] = $value;
                }
            }
            
            // Add password if it was changed
            if ($passwordChanged) {
                $changedFields['password'] = $plainPassword;
            }
            
            // Only send notification if something actually changed
            if (!empty($changedFields)) {
                $user->notify(new UserUpdated($user, $changedFields));
                Log::info('User update notification sent', ['user_id' => $user->id, 'fields_changed' => array_keys($changedFields)]);
            }
            
            if ($request->expectsJson()) {
                return response()->json($user);
            }
            
            return redirect()->route('admin.user_management.index')->with('success', 'User updated successfully' . (!empty($changedFields) ? ' and notification sent' : ''));
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
            
            // Send deletion notification before deleting the user
            // We need to create a temporary notification instance
            // since the user will be deleted before it's processed
            $tempUser = clone $user; // Create a copy of the user object
            
            // Send the user deleted notification
            // We use the Notification facade directly since the user object may be deleted
            // by the time the queue processes the notification
            \Illuminate\Support\Facades\Notification::route('mail', [
                $user->email => $user->first_name . ' ' . $user->last_name
            ])->notify(new UserDeleted($tempUser));
            
            Log::info('User deletion notification queued', ['user_id' => $user->id, 'email' => $user->email]);
            
            // Now delete the user
            $user->delete();
            
            if (request()->expectsJson()) {
                return response()->json(null, 204);
            }
            
            return redirect()->route('admin.user_management.index')->with('success', 'User deleted successfully and notification sent');
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
        $user = User::findOrFail($id);

        // Get validated data
        $validated = $request->validated();

        // Hash the new password
        $user->password = Hash::make($validated['password']);
        $user->save();

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Password reset successfully']);
        }

        return redirect()->route('admin.user_management.index')->with('success', 'Password reset successfully');
    }
}