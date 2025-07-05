<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            'profile_image' => $user->profile_image,
        ]);
    }
}
