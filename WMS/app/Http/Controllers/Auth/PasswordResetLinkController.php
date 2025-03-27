<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;
use App\Models\User;
use App\Notifications\PasswordResetRequest;
use App\Models\Admin;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        try {
            // Log that we're starting the password reset process
            Log::info('Password reset requested for email: ' . $request->email);
            
            // Find the user who requested the password reset
            $user = User::where('email', $request->email)->first();
            
            if (!$user) {
                Log::warning('No user found with email: ' . $request->email);
                return back()->withInput($request->only('email'))
                    ->withErrors(['email' => 'We could not find a user with that email address.']);
            }
            
            Log::info('User found for password reset', [
                'user_id' => $user->id,
                'email' => $user->email
            ]);
            
            // Get admin users to notify
            $admins = User::where('role', 'admin')->get();
            
            if ($admins->isEmpty()) {
                Log::error('No admin users found to send password reset notification');
                return back()->withInput($request->only('email'))
                    ->withErrors(['email' => 'Unable to send reset request. Please contact support.']);
            }
            
            Log::info('Found ' . $admins->count() . ' admin users to notify about password reset');
            $successfulSends = 0;
            
            // Send notification to all admins using Mail directly
            foreach ($admins as $admin) {
                try {
                    Log::info('Sending password reset notification to admin', [
                        'admin_id' => $admin->id,
                        'admin_email' => $admin->email
                    ]);
                    
                    // Create the full reset URL with absolute path
                    $adminDashboardUrl = url(route('admin.user_management.index', ['reset_user' => $user->id], true));
                    
                    // Create the notification
                    $notification = new PasswordResetRequest($user);
                    
                    // Send the notification
                    $admin->notify($notification);
                    
                    // Also try sending a direct mail as a backup
                    Mail::send('emails.password-reset-request', [
                        'user' => $user, 
                        'resetUrl' => $adminDashboardUrl,
                        'notifiable' => $admin
                    ], function ($message) use ($admin) {
                        $message->to($admin->email)
                                ->subject('YHROCU - Password Reset Request');
                    });
                    
                    $successfulSends++;
                    
                    Log::info('Password reset notification sent to admin', [
                        'admin_id' => $admin->id,
                        'admin_email' => $admin->email
                    ]);
                } catch (\Exception $e) {
                    Log::error('Error sending password reset notification to admin: ' . $e->getMessage(), [
                        'admin_id' => $admin->id,
                        'admin_email' => $admin->email,
                        'exception' => $e
                    ]);
                }
            }
            
            // If at least one email was sent successfully
            if ($successfulSends > 0) {
                Log::info('Password reset request processing completed for user', ['user_id' => $user->id]);
                
                return back()->with('status', 'We have sent your password reset request to the admin team. You will receive an email with your new password soon.');
            } else {
                Log::error('Failed to send any password reset notifications');
                
                return back()->withInput($request->only('email'))
                    ->withErrors(['email' => 'Failed to send reset request. Please try again later or contact support.']);
            }
        } catch (\Exception $e) {
            Log::error('Error sending password reset request: ' . $e->getMessage(), [
                'exception' => $e, 
                'request_data' => $request->only('email')
            ]);
            
            return back()->withInput($request->only('email'))
                ->withErrors(['email' => 'An error occurred when processing your request. Please try again later.']);
        }
    }
}
