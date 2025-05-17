<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Documentpath;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentpathController extends Controller
{
    // GET /api/v1/documentpaths
    public function index()
    {
        $documentpaths = Documentpath::all();
        return response()->json($documentpaths);
    }

    // POST /api/v1/documentpaths
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'document_path' => 'required|file|mimes:pdf,doc,docx,txt|max:2048',
            'task_id'       => 'required|integer',
            'hist_id'       => 'nullable|integer',
        ]);
        
        if ($request->hasFile('document_path')) {
            $file = $request->file('document_path');
            
            // Save to the 'public' disk under 'uploads' directory
            $path = $file->store('uploads', 'public');
    
            // Save to database
            $document = Documentpath::create([
                'task_id' => $request->task_id,
                'document_path' => $path, // Path: 'uploads/filename.pdf'
                'hist_id' => $request->hist_id
            ]);
    
            return response()->json([
                'success' => true,
                'message' => 'File uploaded successfully!',
                'data' => $document,
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'File upload failed.',
        ], 400);
    }

    // GET /api/v1/documentpaths/{documentpath}
    public function show(Documentpath $documentpath)
    {
        return response()->json($documentpath);
    }

    // PUT/PATCH /api/v1/documentpaths/{documentpath}
    public function update(Request $request, Documentpath $documentpath)
    {
        $validatedData = $request->validate([
            'document_path' => 'nullable|file|mimes:pdf,doc,docx,txt|max:2048',
            'task_id'       => 'required|integer',
            'hist_id'       => 'nullable|integer',
        ]);

        // If a new file is uploaded, replace the old one
        if ($request->hasFile('document_path')) {
            // Delete old file if it exists
            if ($documentpath->document_path) {
                Storage::disk('public')->delete($documentpath->document_path);
            }
            
            $file = $request->file('document_path');
            $path = $file->store('uploads', 'public');
            $documentpath->document_path = $path;
        }
        
        $documentpath->task_id = $request->task_id;
        if ($request->has('hist_id')) {
            $documentpath->hist_id = $request->hist_id;
        }
        
        $documentpath->save();
        
        return response()->json([
            'message' => 'Document updated successfully',
            'data'    => $documentpath
        ]);
    }

    // DELETE /api/v1/documentpaths/{documentpath}
    public function destroy(Documentpath $documentpath)
    {
        // Delete the file from storage
        if ($documentpath->document_path) {
            Storage::disk('public')->delete($documentpath->document_path);
        }
        
        $documentpath->delete();
        return response()->json(['message' => 'Document deleted successfully']);
    }
}