<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Status;
use Illuminate\Http\Request;

class StatusController extends Controller
{
    // GET /api/v1/statuses
    public function index(Request $request)
    {
        // Get all statuses or filter by task_id if provided
        if ($request->has('task_id')) {
            $statuses = Status::where('task_id', $request->task_id)
                ->orderBy('date_changed', 'desc')
                ->get();
        } else {
            $statuses = Status::all();
        }
        
        return response()->json($statuses);
    }

    // POST /api/v1/statuses
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'statut'       => 'required|max:255',
            'date_changed' => 'required|date',
            'task_id'      => 'required|integer|exists:task,task_id',
        ]);

        $status = Status::create($validatedData);
        return response()->json([
            'message' => 'Status created successfully',
            'data'    => $status
        ], 201);
    }

    // GET /api/v1/statuses/{status}
    public function show(Status $status)
    {
        return response()->json($status);
    }

    // PUT/PATCH /api/v1/statuses/{status}
    public function update(Request $request, Status $status)
    {
        $validatedData = $request->validate([
            'statut'       => 'required|max:255',
            'date_changed' => 'required|date',
            'task_id'      => 'required|integer|exists:task,task_id',
        ]);

        $status->update($validatedData);
        return response()->json([
            'message' => 'Status updated successfully',
            'data'    => $status
        ]);
    }

    // DELETE /api/v1/statuses/{status}
    public function destroy(Status $status)
    {
        $status->delete();
        return response()->json(['message' => 'Status deleted successfully']);
    }
}