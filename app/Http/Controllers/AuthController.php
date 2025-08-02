<?php

namespace App\Http\Controllers;

use App\Models\PasswordResetTokenModel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    public function postLogin(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = [
            'username' => $validated['username'],
            'password' => $validated['password'],
        ];

        // Attempt to log the user in
        if (Auth::attempt($credentials)) {
            // Regenerate session to prevent session fixation attacks
            $request->session()->regenerate();

            // Return a successful response
            return response()->json(['message' => 'Login successful'], 200);
        }

        // If authentication fails, return an error response
        return response()->json(['message' => 'Incorrect username or password, please try again.'], 401);
    }

    public function currentUser(Request $request)
    {
        // Get the currently authenticated user
        $user = Auth::user();

        // If no user is authenticated, return an error response
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Return the authenticated user's data
        return response()->json([
            'id' => $user->id,
            'fullname' => $user->fullname,
            'username' => $user->username,
            'email' => $user->email,
            'is_admin' => $user->is_admin,
            'download_access' => $user->download_access,
            'modification_job_tracker_master_access' => $user->modification_job_tracker_master_access,
            'profile_image' => $user->profile_image ? Storage::url('assets/images/profile_images/' . $user->profile_image) : '',
        ]);
    }

    public function logout(Request $request)
    {
        // Log the user out
        Auth::logout();

        // Invalidate the session
        $request->session()->invalidate();

        // Regenerate the CSRF token
        $request->session()->regenerateToken();

        // Return a successful response
        return response()->json(['message' => 'Logout successful'], 200);
    }

    public function forgotPassword(Request $request)
    {
        $response = DB::transaction(function () use ($request) {

            // Validate the request
            $validated = $request->validate([
                'email' => 'required|email',
            ]);

            // Find the user by email
            $user = User::where('email', $validated['email'])->first();

            if (!$user) {
                return response()->json(['message' => 'Email not found'], 404);
            }

            // Generate a password reset token
            $token = app('auth.password.broker')->createToken($user);
            if (!$token) {
                return response()->json(['message' => 'Failed to create password reset token'], 500);
            }

            // create url reset password with token
            $reset_link = url("/password-reset?token=$token") . '&email=' . urlencode($user->email);
            // dd($resetUrl);
            // Send the password reset link via email
            try {
                $user->sendResetPasswordNotification($reset_link);
            } catch (\Exception $e) {
                // Log the error for debugging purposes
                Log::error('Failed to send password reset email: ' . $e->getMessage());
                return response()->json(['message' => 'Failed to send password reset link'], 500);
            }

            return response()->json(['message' => 'Password reset link sent to your email'], 200);
        });

        return $response;
    }

    public function validatePasswordResetToken(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'token' => 'required|string',
        ]);

        $tokenRecord = PasswordResetTokenModel::where('email', $validated['email'])->first();
        if (!$tokenRecord) {
            return false;
        }

        if (!Hash::check($validated['token'], $tokenRecord->token)) {
            return false;
        }

        if ($tokenRecord->created_at->diffInMinutes(now()) > 60) {
            return false;
        }

        return true;
    }

    public function resetPassword(Request $request)
    {
        $response = DB::transaction(function () use ($request) {
            // Validate the request
            $validated = $request->validate([
                'email' => 'required|email',
                'token' => 'required|string',
                'password' => 'required|string|min:8|confirmed',
            ]);

            // Validate the token
            if (!$this->validatePasswordResetToken($request)) {
                return response()->json(['message' => 'Invalid or expired password reset token'], 400);
            }

            // Find the user by email
            $user = User::where('email', $validated['email'])->first();
            if (!$user) {
                return response()->json(['message' => 'Email not found'], 404);
            }

            // Update the user's password
            $user->password = Hash::make($validated['password']);
            $user->save();

            // Delete the password reset token
            PasswordResetTokenModel::where('email', $validated['email'])->delete();

            return response()->json(['message' => 'Password has been reset successfully'], 200);
        });

        return $response;
    }
}
