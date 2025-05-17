<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Division;
use Illuminate\Http\Request;

class DivisionController extends Controller
{
    // GET /api/divisions
    public function index()
    {
        $divisions = Division::all();
        return response()->json($divisions);
    }

    // POST /api/divisions
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'division_nom'         => 'required|max:255',
            'division_responsable' => 'required|max:255',
            'password'             => 'required|max:255',
        ]);

        $division = Division::create($validatedData);
        return response()->json([
            'message' => 'Division created successfully',
            'data'    => $division
        ], 201);
    }

    // GET /api/divisions/{division}
    public function show(Division $division)
    {
        return response()->json($division);
    }

    // PUT/PATCH /api/divisions/{division}
    public function update(Request $request, Division $division)
    {
        $validatedData = $request->validate([
            'division_nom'         => 'required|max:255',
            'division_responsable' => 'required|max:255',
            'password'             => 'required|max:255',
        ]);

        $division->update($validatedData);
        return response()->json([
            'message' => 'Division updated successfully',
            'data'    => $division
        ]);
    }

    // DELETE /api/divisions/{division}
    public function destroy(Division $division)
    {
        $division->delete();
        return response()->json(['message' => 'Division deleted successfully']);
    }
}
