<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Historique;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HistoriqueController extends Controller
{
    // GET /api/v1/historiques
    public function index()
    {
        $historiques = Historique::all();
        return response()->json($historiques);
    }

    // POST /api/v1/historiques
    public function store(Request $request) {
        // Validate the request
        $validated = $request->validate([
            'description' => 'required|string|max:500',
            'task_id' => 'required|integer|exists:task,task_id',
            'change_date' => 'required|date',
            'dochistorique_path' => 'required|file|mimes:pdf,doc,docx,txt',
        ]);
    
        // Store the file and get the path
        $filePath = $request->file('dochistorique_path')->store('historiques', 'public');
    
        // Save to database
        $historique = Historique::create([
            'description' => $validated['description'],
            'task_id' => $validated['task_id'],
            'change_date' => $validated['change_date'],
            'dochistorique_path' => $filePath, // e.g., "historiques/filename.pdf"
        ]);
        
        return response()->json([
            'message' => 'Historique created successfully',
            'data' => $historique
        ], 201);
    }

    // GET /api/v1/historiques/{historique}
    public function show(Historique $historique)
    {
        return response()->json($historique);
    }

    // PUT/PATCH /api/v1/historiques/{historique}
    public function update(Request $request, Historique $historique)
    {
        $validatedData = $request->validate([
            'description' => 'required|max:500',
            'change_date' => 'required|date',
            'dochistorique_path' => 'nullable|file|mimes:pdf,doc,docx,txt',
            'task_id'     => 'required|integer',
        ]);

        // If a new file is uploaded, replace the old one
        if ($request->hasFile('dochistorique_path')) {
            // Delete old file if it exists
            if ($historique->dochistorique_path) {
                Storage::disk('public')->delete($historique->dochistorique_path);
            }
            
            $filePath = $request->file('dochistorique_path')->store('historiques', 'public');
            $validatedData['dochistorique_path'] = $filePath;
        }

        $historique->update($validatedData);
        return response()->json([
            'message' => 'Historique updated successfully',
            'data'    => $historique
        ]);
    }

    // DELETE /api/v1/historiques/{historique}
    public function destroy(Historique $historique)
    {
        // Delete the file from storage
        if ($historique->dochistorique_path) {
            Storage::disk('public')->delete($historique->dochistorique_path);
        }
        
        $historique->delete();
        return response()->json(['message' => 'Historique deleted successfully']);
    }
}