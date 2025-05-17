<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Division;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DivisionController extends Controller
{
    // GET /api/v1/divisions
    public function index()
    {
        $divisions = Division::all();
        return response()->json($divisions);
    }

    // POST /api/v1/divisions
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'division_nom'         => 'required|max:255',
            'division_responsable' => 'required|max:255|unique:division,division_responsable',
            'password'             => 'required|max:255',
        ]);

        // Hash the password before storing
        $validatedData['password'] = Hash::make($validatedData['password']);

        $division = Division::create($validatedData);
        return response()->json([
            'message' => 'Division created successfully',
            'data'    => $division
        ], 201);
    }

    // GET /api/v1/divisions/{division}
    public function show(Division $division)
    {
        return response()->json($division);
    }

    // PUT/PATCH /api/v1/divisions/{division}
    public function update(Request $request, Division $division)
    {
        $validatedData = $request->validate([
            'division_nom'         => 'required|max:255',
            'division_responsable' => 'required|max:255|unique:division,division_responsable,' . $division->division_id . ',division_id',
            'password'             => 'sometimes|required|max:255',
        ]);

        // Hash the password if it's being updated
        if (isset($validatedData['password'])) {
            $validatedData['password'] = Hash::make($validatedData['password']);
        }

        $division->update($validatedData);
        return response()->json([
            'message' => 'Division updated successfully',
            'data'    => $division
        ]);
    }

    // DELETE /api/v1/divisions/{division}
    public function destroy(Division $division)
    {
        $division->delete();
        return response()->json(['message' => 'Division deleted successfully']);
    }
}