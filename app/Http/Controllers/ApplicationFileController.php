<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Illuminate\Http\Request;
use App\Models\ApplicationFile;
use App\Models\ApplicationFiles;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ApplicationFileController extends Controller
{
    /**
     * Display a listing of files for a specific application.
     *
     * @param  \App\Models\Application  $application
     * @return \Illuminate\Http\Response
     */
    public function index(Application $application)
    {
        $documents = $application->documents()->get();
        $certificates = $application->certificates()->get();
        
        return view('application-files.index', compact('application', 'documents', 'certificates'));
    }

    /**
     * Show the form for uploading a new file.
     *
     * @param  \App\Models\Application  $application
     * @return \Illuminate\Http\Response
     */
    public function create(Application $application)
    {
        return view('application-files.create', compact('application'));
    }

    /**
     * Store a newly created file in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Application  $application
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Application $application)
    {
        $validated = $request->validate([
            'file_type' => 'required|in:document,certificate',
            'file' => 'required|file|max:10240', // 10MB max size
            'description' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);
        
        $file = $request->file('file');
        $originalFilename = $file->getClientOriginalName();
        $mimeType = $file->getMimeType();
        $fileSize = $file->getSize() / 1024; // Convert to KB
        
        // Generate a unique filename
        $filename = 'app_' . $application->id . '_' . time() . '_' . $file->getClientOriginalName();
        
        // Store the file in an application-specific directory
        $path = $file->storeAs(
            'application-files/' . $application->id, 
            $filename, 
            'public'
        );
        
        try {
            // Create the file record
            $applicationFile = ApplicationFiles::create([
                'application_id' => $application->id,
                'file_type' => $validated['file_type'],
                'file_path' => $path,
                'original_filename' => $originalFilename,
                'mime_type' => $mimeType,
                'file_size' => $fileSize,
                'description' => $validated['description'] ?? null,
                'uploaded_by' => Auth::id(),
                'notes' => $validated['notes'] ?? null,
            ]);
            
            return redirect()
                ->route('applications.files.index', $application)
                ->with('success', 'File uploaded successfully.');
        } catch (\Exception $e) {
            // Delete the file if record creation fails
            Storage::disk('public')->delete($path);
            
            return back()
                ->withInput()
                ->with('error', 'Failed to upload file. ' . $e->getMessage());
        }
    }

    /**
     * Display the specified file.
     *
     * @param  \App\Models\Application  $application
     * @param  \App\Models\ApplicationFile  $file
     * @return \Illuminate\Http\Response
     */
    public function show(Application $application, ApplicationFile $file)
    {
        if ($file->application_id !== $application->id) {
            abort(404);
        }
        
        return view('application-files.show', compact('application', 'file'));
    }

    /**
     * Download the specified file.
     *
     * @param  \App\Models\Application  $application
     * @param  \App\Models\ApplicationFile  $file
     * @return \Illuminate\Http\Response
     */
    public function download(Application $application, ApplicationFile $file)
    {
        if ($file->application_id !== $application->id) {
            abort(404);
        }
        
        return Storage::disk('public')->download($file->file_path, $file->original_filename);
    }

    /**
     * Show the form for editing file details.
     *
     * @param  \App\Models\Application  $application
     * @param  \App\Models\ApplicationFile  $file
     * @return \Illuminate\Http\Response
     */
    public function edit(Application $application, ApplicationFile $file)
    {
        if ($file->application_id !== $application->id) {
            abort(404);
        }
        
        return view('application-files.edit', compact('application', 'file'));
    }

    /**
     * Update the specified file in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Application  $application
     * @param  \App\Models\ApplicationFile  $file
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Application $application, ApplicationFile $file)
    {
        if ($file->application_id !== $application->id) {
            abort(404);
        }
        
        $validated = $request->validate([
            'file_type' => 'required|in:document,certificate',
            'description' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);
        
        // Check if a new file is being uploaded
        if ($request->hasFile('file')) {
            $request->validate([
                'file' => 'file|max:10240', // 10MB max size
            ]);
            
            $newFile = $request->file('file');
            $originalFilename = $newFile->getClientOriginalName();
            $mimeType = $newFile->getMimeType();
            $fileSize = $newFile->getSize() / 1024; // Convert to KB
            
            // Generate a unique filename
            $filename = 'app_' . $application->id . '_' . time() . '_' . $newFile->getClientOriginalName();
            
            // Store the new file
            $path = $newFile->storeAs(
                'application-files/' . $application->id, 
                $filename, 
                'public'
            );
            
            // Delete the old file
            Storage::disk('public')->delete($file->file_path);
            
            // Update file information
            $validated['file_path'] = $path;
            $validated['original_filename'] = $originalFilename;
            $validated['mime_type'] = $mimeType;
            $validated['file_size'] = $fileSize;
            
            // Reset verification status if file is changed
            $validated['is_verified'] = false;
            $validated['verified_by'] = null;
            $validated['verified_at'] = null;
        }
        
        $file->update($validated);
        
        return redirect()
            ->route('applications.files.show', [$application, $file])
            ->with('success', 'File details updated successfully.');
    }

    /**
     * Verify the specified file.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Application  $application
     * @param  \App\Models\ApplicationFile  $file
     * @return \Illuminate\Http\Response
     */
    public function verify(Request $request, Application $application, ApplicationFile $file)
    {
        if ($file->application_id !== $application->id) {
            abort(404);
        }
        
        $validated = $request->validate([
            'notes' => 'nullable|string',
        ]);
        
        $file->verify(Auth::id(), $validated['notes'] ?? null);
        
        return redirect()
            ->route('applications.files.show', [$application, $file])
            ->with('success', 'File verified successfully.');
    }

    /**
     * Remove the specified file from storage.
     *
     * @param  \App\Models\Application  $application
     * @param  \App\Models\ApplicationFile  $file
     * @return \Illuminate\Http\Response
     */
    public function destroy(Application $application, ApplicationFiles $file)
    {
        if ($file->application_id !== $application->id) {
            abort(404);
        }
        
        try {
            // Delete the physical file
            Storage::disk('public')->delete($file->file_path);
            
            // Delete the record
            $file->delete();
            
            return redirect()
                ->route('applications.files.index', $application)
                ->with('success', 'File deleted successfully.');
        } catch (\Exception $e) {
            return back()
                ->with('error', 'Failed to delete file. ' . $e->getMessage());
        }
    }
    
    /**
     * Batch upload multiple files for an application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Application  $application
     * @return \Illuminate\Http\Response
     */
    public function batchUpload(Request $request, Application $application)
    {
        $request->validate([
            'files' => 'required|array',
            'files.*' => 'file|max:10240', // 10MB max size per file
            'file_type' => 'required|in:document,certificate',
            'description' => 'nullable|string',
        ]);
        
        $successCount = 0;
        $failCount = 0;
        
        foreach ($request->file('files') as $uploadedFile) {
            $originalFilename = $uploadedFile->getClientOriginalName();
            $mimeType = $uploadedFile->getMimeType();
            $fileSize = $uploadedFile->getSize() / 1024; // Convert to KB
            
            // Generate a unique filename
            $filename = 'app_' . $application->id . '_' . time() . '_' . $uploadedFile->getClientOriginalName();
            
            try {
                // Store the file
                $path = $uploadedFile->storeAs(
                    'application-files/' . $application->id, 
                    $filename, 
                    'public'
                );
                
                // Create the file record
                ApplicationFile::create([
                    'application_id' => $application->id,
                    'file_type' => $request->input('file_type'),
                    'file_path' => $path,
                    'original_filename' => $originalFilename,
                    'mime_type' => $mimeType,
                    'file_size' => $fileSize,
                    'description' => $request->input('description'),
                    'uploaded_by' => Auth::id(),
                ]);
                
                $successCount++;
            } catch (\Exception $e) {
                // Delete the file if it was uploaded
                if (isset($path)) {
                    Storage::disk('public')->delete($path);
                }
                $failCount++;
            }
        }
        
        $message = "Successfully uploaded $successCount files.";
        if ($failCount > 0) {
            $message .= " Failed to upload $failCount files.";
        }
        
        return redirect()
            ->route('applications.files.index', $application)
            ->with('success', $message);
    }
    
    /**
     * Show the form for batch uploading files.
     *
     * @param  \App\Models\Application  $application
     * @return \Illuminate\Http\Response
     */
    public function createBatch(Application $application)
    {
        return view('application-files.batch-upload', compact('application'));
    }
}