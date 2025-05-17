<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // GET /api/admins
    public function index()
    {
        $admins = Admin::all();
        return response()->json($admins);
    }

    // POST /api/admins
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'username' => 'required|max:255',
            'password' => 'required|max:255',
        ]);

        $admin = Admin::create($validatedData);
        return response()->json([
            'message' => 'Admin created successfully',
            'data'    => $admin
        ], 201);
    }

    // GET /api/admins/{admin}
    public function show(Admin $admin)
    {
        return response()->json($admin);
    }

    // PUT/PATCH /api/admins/{admin}
    public function update(Request $request, Admin $admin)
    {
        $validatedData = $request->validate([
            'username' => 'required|max:255',
            'password' => 'required|max:255',
        ]);

        $admin->update($validatedData);
        return response()->json([
            'message' => 'Admin updated successfully',
            'data'    => $admin
        ]);
    }

    // DELETE /api/admins/{admin}
    public function destroy(Admin $admin)
    {
        $admin->delete();
        return response()->json(['message' => 'Admin deleted successfully']);
    }
}
