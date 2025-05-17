<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\Status;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    // GET /api/v1/tasks
    public function index()
    {
        // Loads related relationships
        $tasks = Task::with(['division', 'statuses', 'historiques', 'documentpaths'])->get();
        return response()->json($tasks);
    }
    
    // POST /api/v1/tasks
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'task_name'   => 'required|max:255',
            'description' => 'nullable|max:255',
            'due_date'    => 'required|date',
            'fin_date'    => 'nullable|date',
            'division_id' => 'required|integer|exists:division,division_id',
        ]);

        $task = Task::create($validatedData);
        
        // Create initial status if provided
        if ($request->has('statut')) {
            Status::create([
                'statut' => $request->statut,
                'date_changed' => now()->toDateString(),
                'task_id' => $task->task_id
            ]);
        }
        
        return response()->json([
            'message' => 'Task created successfully',
            'data'    => $task
        ], 201);
    }

    // GET /api/v1/tasks/{task}
    public function show(Task $task)
    {
        $task->load(['division', 'statuses', 'historiques', 'documentpaths']);
        return response()->json($task);
    }

    // PUT/PATCH /api/v1/tasks/{task}
    public function update(Request $request, Task $task)
    {
        $validatedData = $request->validate([
            'task_name'   => 'sometimes|required|max:255',
            'description' => 'nullable|max:255',
            'due_date'    => 'sometimes|required|date',
            'fin_date'    => 'nullable|date',
            'division_id' => 'sometimes|required|integer|exists:division,division_id',
        ]);

        $task->update($validatedData);
        
        // Create new status if provided
        if ($request->has('statut')) {
            Status::create([
                'statut' => $request->statut,
                'date_changed' => now()->toDateString(),
                'task_id' => $task->task_id
            ]);
        }
        
        return response()->json([
            'message' => 'Task updated successfully',
            'data'    => $task
        ]);
    }

    // DELETE /api/v1/tasks/{task}
    public function destroy(Task $task)
    {
        // First delete related records to avoid foreign key constraints
        $task->statuses()->delete();
        $task->historiques()->delete();
        $task->documentpaths()->delete();
        
        $task->delete();
        return response()->json(['message' => 'Task deleted successfully']);
    }
}