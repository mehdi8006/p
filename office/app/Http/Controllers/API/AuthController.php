<?php

namespace App\Http\Controllers\API;

use App\Models\Admin;
use App\Models\Division;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // Validate the input
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string'
        ]);
    
        // Check Admin table first
        $admin = Admin::where('username', $credentials['username'])->first();
        if ($admin) {
            // Check if password is already hashed
            if (Hash::needsRehash($admin->password)) {
                // Compare plaintext password for now, but note it should be hashed in production
                if ($credentials['password'] === $admin->password) {
                    return response()->json([
                        'message' => 'Admin login successful',
                        'user' => [
                            'username' => $admin->username,
                            'role' => 'admin'
                        ]
                    ], 200);
                }
            } else {
                // Use proper password verification if it's already hashed
                if (Hash::check($credentials['password'], $admin->password)) {
                    return response()->json([
                        'message' => 'Admin login successful',
                        'user' => [
                            'username' => $admin->username,
                            'role' => 'admin'
                        ]
                    ], 200);
                }
            }
        }
    
        // Check Division table if admin not found
        $division = Division::where('division_responsable', $credentials['username'])->first();
        if ($division) {
            // Check if password is already hashed
            if (Hash::needsRehash($division->password)) {
                // Compare plaintext password for now
                if ($credentials['password'] === $division->password) {
                    return response()->json([
                        'message' => 'Division login successful',
                        'user' => [
                            'username' => $division->division_responsable,
                            'role' => 'division_responsable'
                        ]
                    ], 200);
                }
            } else {
                // Use proper password verification if it's already hashed
                if (Hash::check($credentials['password'], $division->password)) {
                    return response()->json([
                        'message' => 'Division login successful',
                        'user' => [
                            'username' => $division->division_responsable,
                            'role' => 'division_responsable'
                        ]
                    ], 200);
                }
            }
        }
    
        // Return an error if credentials do not match
        return response()->json([
            'message' => 'Invalid credentials'
        ], 401);
    }
    
    // Method to securely rehash passwords
    public function rehashPasswords()
    {
        // Rehash Admin passwords if necessary
        $admins = Admin::all();
        foreach ($admins as $admin) {
            // Check if the password is plain-text and rehash it
            if (Hash::needsRehash($admin->password)) {
                $admin->password = Hash::make($admin->password); // Hash the password
                $admin->save();
            }
        }

        // Rehash Division passwords if necessary
        $divisions = Division::all();
        foreach ($divisions as $division) {
            // Check if the password is plain-text and rehash it
            if (Hash::needsRehash($division->password)) {
                $division->password = Hash::make($division->password); // Hash the password
                $division->save();
            }
        }

        return response()->json([
            'message' => 'Passwords have been rehashed where needed.'
        ], 200);
    }
}