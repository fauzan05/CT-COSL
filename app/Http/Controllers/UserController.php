<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function getUsers(Request $request)
    {
        // Default pagination parameters
        $perPage = $request->input('per_page', 10);
        $page = $request->input('page', 1);
        $search = $request->input('search', '');
        $sortBy = $request->input('sort_by', 'id');
        $sortDirection = $request->input('is_desc', 'desc') === 'true' ? 'desc' : 'asc';

        $query = User::query();
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('fullname', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%");
            });
        }
        $query->where('is_admin', false);
        $query->orderBy($sortBy, $sortDirection);
        $users = $query->paginate($perPage, ['*'], 'page', $page);
        $users->getCollection()->transform(function ($user) {
            return [
                'id' => $user->id,
                'fullname' => $user->fullname,
                'username' => $user->username,
                'email' => $user->email,
                'download_access' => $user->download_access,
                'profile_image' => $user->getProfileImageUrl(),
                'created_at' => $user->created_at->toDateTimeString(),
                'created_by_name' => $user->createdBy ? $user->createdBy->fullname : null,
                'updated_at' => $user->updated_at->toDateTimeString(),
                'updated_by_name' => $user->updatedBy ? $user->updatedBy->fullname : null,
            ];
        });

        return response()->json($users);
    }

    public function checkUsername(Request $request)
    {
        $username = $request->input('username');
        if (!$username) {
            return response()->json(['message' => 'Username is required'], 400);
        }

        $exists = User::where('username', $username)->exists();

        // Check against reserved usernames (optional)
        $reservedUsernames = ['admin', 'root', 'system', 'api', 'www', 'mail', 'test'];
        $isReserved = in_array(strtolower($username), $reservedUsernames);

        $available = !$exists && !$isReserved;

        return response()->json([
            'available' => $available,
            'message' => $available ? 'Username is available' : 'Username is not available'
        ]);
    }

    public function checkEmail(Request $request)
    {
        $email = $request->input('email');
        $currentEmailUser = $request->input('selected_current_user_email', null);

        if (!$email) {
            return response()->json(['message' => 'Email is required'], 400);
        }

        $exists = User::where('email', $email)->when($currentEmailUser, function ($query) use ($currentEmailUser) {
            return $query->where('email', '!=', $currentEmailUser);
        })
            ->exists();

        return response()->json([
            'available' => !$exists,
            'message' => $exists ? 'Email is already in use' : 'Email is available'
        ]);
    }

    public function storeUser(Request $request)
    {
        $response = DB::transaction(function () use ($request) {
            // Validate the request
            $validated = $request->validate([
                'username' => 'required|string|unique:users,username',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:8',
                'fullname' => 'required|string|max:255',
                'download_access' => 'boolean',
            ]);

            // Create a new user
            $user = new User();
            $user->username = $validated['username'];
            $user->email = $validated['email'];
            $user->password = bcrypt($validated['password']);
            $user->fullname = $validated['fullname'];
            $user->is_admin = false;
            $user->download_access = $validated['download_access'] ?? false;
            $user->profile_image = null;
            $user->created_at = now();
            $user->created_by = $request->user()->id; // Assuming the user is authenticated
            $user->updated_at = now();
            $user->updated_by = $request->user()->id; // Assuming the user is authenticated
            $user->save();

            // Send email
            try {
                $user->sendEmailCreateUserNotification(
                    $validated['password'],
                    [],
                    'emails.user_created',
                    'Account Created – Here Are Your Login Details'
                );
            } catch (\Throwable $e) {
                Log::error('Email failed: ' . $e->getMessage());
            }

            // return dari closure
            return response()->json(['message' => 'User created successfully and email sent'], 201);
        });

        // return ke frontend
        return $response;
    }

    public function updateDownloadPermission(Request $request, $id)
    {
        // Find the user by ID
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // Check if the authenticated user is allowed to update this user's download access
        if (!$request->user()->is_admin) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Validate the request
        $validated = $request->validate([
            'download_access' => 'required|boolean',
        ]);

        // Update the user's download access
        $user->download_access = $validated['download_access'];
        $user->updated_at = now();
        $user->updated_by = $request->user()->id; // Assuming the user is authenticated
        $user->save();

        return response()->json(['message' => 'Download access updated successfully']);
    }

    public function updateUser(Request $request, $id)
    {
        $response = DB::transaction(function () use ($request, $id) {
            // Find the user by ID
            $user = User::find($id);
            if (!$user) {
                return response()->json(['message' => 'User not found'], 404);
            }

            $is_update_password = $request->is_update_password;

            // Validate the request
            $validated = $request->validate([
                'email' => 'required|email|unique:users,email,' . $user->id,
                'fullname' => 'required|string|max:255',
                'download_access' => 'boolean',
            ]);
            
            $old_email = $user->email;
            $new_email = '';
            $old_fullname = $user->fullname;
            $new_fullname = '';

            // Update the user
            $user->email = $validated['email'];
            if ($user->email != $validated['email']) {
                $new_email = $validated['email'];
            }
            $user->fullname = $validated['fullname'];
            if ($user->fullname != $validated['fullname']) {
                $new_fullname = $validated['fullname'];
            }
            if ($is_update_password) {
                $request->validate(['password' => 'required|string|min:8']);
                $user->password = bcrypt($request->input('password'));
            }
            $user->download_access = $validated['download_access'] ?? false;
            $user->updated_at = now();
            $user->updated_by = $request->user()->id; // Assuming the user is authenticated
            $user->save();

            // send email
            try {
                $user->sendEmailUpdateUserNotification(
                    $is_update_password ? $request->input('password') : null,
                    [],
                    $old_email,
                    $new_email,
                    $old_fullname,
                    $new_fullname,
                    'emails.user_updated',
                    'Account Updated – Here Are Your Account Details'
                );
            } catch (\Throwable $e) {
                Log::error('Email failed: ' . $e->getMessage());
            }

            return response()->json(['message' => 'User updated successfully']);
        });

        return $response;
    }
}
