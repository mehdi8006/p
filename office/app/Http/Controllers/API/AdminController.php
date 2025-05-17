<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    // GET /api/v1/admins
    public function index()
    {
        $admins = Admin::all();
        return response()->json($admins);
    }

    // POST /api/v1/admins
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'username' => 'required|max:255|unique:admin,username',
            'password' => 'required|max:255',
        ]);

        // Hash the password before storing
        $validatedData['password'] = Hash::make($validatedData['password']);

        $admin = Admin::create($validatedData);
        return response()->json([
            'message' => 'Admin created successfully',
            'data'    => $admin
        ], 201);
    }

    // GET /api/v1/admins/{admin}
    public function show(Admin $admin)
    {
        return response()->json($admin);
    }

    // PUT/PATCH /api/v1/admins/{admin}
    public function update(Request $request, Admin $admin)
    {
        $validatedData = $request->validate([
            'username' => 'required|max:255|unique:admin,username,' . $admin->Admin_id . ',Admin_id',
            'password' => 'sometimes|required|max:255',
        ]);

        // Hash the password if it's being updated
        if (isset($validatedData['password'])) {
            $validatedData['password'] = Hash::make($validatedData['password']);
        }

        $admin->update($validatedData);
        return response()->json([
            'message' => 'Admin updated successfully',
            'data'    => $admin
        ]);
    }

    // DELETE /api/v1/admins/{admin}
    public function destroy(Admin $admin)
    {
        $admin->delete();
        return response()->json(['message' => 'Admin deleted successfully']);
    }
}