<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Degree;
use App\Models\Student;
use App\Models\Department;
use App\Models\University;
use App\Models\Application;
use App\Models\Notification;
use App\Models\NotificationSetting;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\ApplicationFile;
use App\Models\ApplicationFiles;
use Barryvdh\DomPDF\Facade\PDF; 
use Illuminate\Support\Facades\DB;
use App\Exports\ApplicationsExport;
use Illuminate\Support\Facades\Log;
use App\Exports\AnnouncementsExport;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class ApplicationController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        if ($user->role == 'Admin' || $user->role == 'Register') {
            // Admin sees all applications
            $query = Application::with(['student.nationality', 'creator', 'university']);
            $students = Student::orderBy('first_name')->get();

        } else {
            // Get IDs of all users who have this user as their parent
            $childUserIds = User::where('parent_id', $user->id)->pluck('id')->toArray();
            
            // Add the current user's ID to the array of IDs to include
            $userIds = array_merge([$user->id], $childUserIds);
            
            // Get all applications created by the current user OR any of their child users
            $query = Application::whereIn('created_by', $userIds)
                               ->with(['student', 'creator', 'university']);

                $students = Student::where('processed_by',auth()->user()->id)->orderBy('first_name')->get();

        }
        
        // Apply filters
        if ($request->filled('student_id')) {
            $query->where('student_id', $request->student_id);
        }
        
        if ($request->filled('university_id')) {
            $universityIds = is_array($request->university_id) ? $request->university_id : [$request->university_id];
            // Remove empty values
            $universityIds = array_filter($universityIds);
            if (!empty($universityIds)) {
                $query->whereIn('university_id', $universityIds);
            }
        }
        
        if ($request->filled('department')) {
            $departments = is_array($request->department) ? $request->department : [$request->department];
            // Remove empty values
            $departments = array_filter($departments);
            if (!empty($departments)) {
                $query->whereIn('department', $departments);
            }
        }
        
        if ($request->filled('degree')) {
            $degrees = is_array($request->degree) ? $request->degree : [$request->degree];
            // Remove empty values
            $degrees = array_filter($degrees);
            if (!empty($degrees)) {
                $query->whereIn('degree', $degrees);
            }
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $applications = $query->latest()->paginate(10);
        
        // Get filter data for dropdowns
        $universities = University::where('is_active', true)->orderBy('name')->get();
        $departments = Department::orderBy('name')->get();
        $degrees = Degree::orderBy('name')->get();
        
        return view('admin.applications.index', compact(
            'applications',
            'students',
            'universities',
            'departments',
            'degrees'
        ));
    }
    /**
     * Export applications data to Excel
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportExcel(Request $request)
    {
        $user = Auth::user();
        
        // Get filter parameters directly from the request
        $studentId = $request->input('student_id');
        $universityId = $request->input('university_id');
        $department = $request->input('department');
        $degree = $request->input('degree');
        $status = $request->input('status');
        
        // Start with the base query and apply user role restrictions
        if ($user->role == 'Admin' || $user->role == 'Register') {
            // Admin sees all applications
            $query = Application::with(['student.nationality', 'university', 'creator']);
        } else {
            // Get IDs of all users who have this user as their parent
            $childUserIds = User::where('parent_id', $user->id)->pluck('id')->toArray();
            
            // Add the current user's ID to the array of IDs to include
            $userIds = array_merge([$user->id], $childUserIds);
            
            // Get all applications created by the current user OR any of their child users
            $query = Application::whereIn('created_by', $userIds)
                               ->with(['student', 'university', 'creator']);
        }
        
        // Apply filters
        if ($studentId) {
            $query->where('student_id', $studentId);
        }
        
        if ($universityId) {
            $universityIds = is_array($universityId) ? $universityId : [$universityId];
            // Remove empty values
            $universityIds = array_filter($universityIds);
            if (!empty($universityIds)) {
                $query->whereIn('university_id', $universityIds);
            }
        }
        
        if ($department) {
            $departments = is_array($department) ? $department : [$department];
            // Remove empty values
            $departments = array_filter($departments);
            if (!empty($departments)) {
                $query->whereIn('department', $departments);
            }
        }
        
        if ($degree) {
            $degrees = is_array($degree) ? $degree : [$degree];
            // Remove empty values
            $degrees = array_filter($degrees);
            if (!empty($degrees)) {
                $query->whereIn('degree', $degrees);
            }
        }
        
        if ($status) {
            $query->where('status', $status);
        }
        
        // Get applications based on current filters
        $applications = $query->latest()->get();
        
        // Create Excel file
        $filename = 'applications_' . date('Y-m-d_H-i-s') . '.xlsx';
        
        return Excel::download(new ApplicationsExport($applications), $filename);
    }

    /**
     * Export applications data to PDF
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function exportPdf(Request $request)
{
    $user = Auth::user();
    
    // Get filter parameters directly from the request
    $studentId = $request->input('student_id');
    $universityId = $request->input('university_id');
    $department = $request->input('department');
    $degree = $request->input('degree');
    $status = $request->input('status');
    
    // Start with the base query and apply user role restrictions
    if ($user->role == 'Admin' || $user->role == 'Register') {
        // Admin sees all applications
        $query = Application::with(['student.nationality', 'university', 'creator']);
    } else {
        // Get IDs of all users who have this user as their parent
        $childUserIds = User::where('parent_id', $user->id)->pluck('id')->toArray();
        
        // Add the current user's ID to the array of IDs to include
        $userIds = array_merge([$user->id], $childUserIds);
        
        // Get all applications created by the current user OR any of their child users
        $query = Application::whereIn('created_by', $userIds)
                           ->with(['student', 'university', 'creator']);
    }
    
    // Apply filters
    if (!empty($studentId)) {
        $query->where('student_id', $studentId);
    }
    
    if ($universityId) {
        $universityIds = is_array($universityId) ? $universityId : [$universityId];
        // Remove empty values
        $universityIds = array_filter($universityIds);
        if (!empty($universityIds)) {
            $query->whereIn('university_id', $universityIds);
        }
    }
    
    if ($department) {
        $departments = is_array($department) ? $department : [$department];
        // Remove empty values
        $departments = array_filter($departments);
        if (!empty($departments)) {
            $query->whereIn('department', $departments);
        }
    }
    
    if ($degree) {
        $degrees = is_array($degree) ? $degree : [$degree];
        // Remove empty values
        $degrees = array_filter($degrees);
        if (!empty($degrees)) {
            $query->whereIn('degree', $degrees);
        }
    }
    
    if ($status) {
        $query->where('status', $status);
    }
    
    // Get applications based on current filters
    $applications = $query->latest()->get();
    
    // Process student photos and university logos to base64 for reliable PDF rendering
    foreach ($applications as $application) {
        // Process student photos
        if (isset($application->student) && isset($application->student->photo_path)) {
            $photoPath = str_replace('storage/', '', $application->student->photo_path);
            $path = storage_path('app/public/' . $photoPath);
            
            if (file_exists($path)) {
                $type = pathinfo($path, PATHINFO_EXTENSION);
                $data = file_get_contents($path);
                $application->student->photo_base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
            } else {
                $application->student->photo_base64 = null;
            }
        }
        
        // Process university logos
        if (isset($application->university) && isset($application->university->logo)) {
            $logoPath = str_replace('storage/', '', $application->university->logo);
            $path = storage_path('app/public/' . $logoPath);
            
            if (file_exists($path)) {
                $type = pathinfo($path, PATHINFO_EXTENSION);
                $data = file_get_contents($path);
                $application->university->logo_base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
            } else {
                $application->university->logo_base64 = null;
            }
        }
    }
    
    // Get filter information for the PDF header
    $studentName = 'All Students';
    if ($studentId) {
        $student = Student::find($studentId);
        if ($student) {
            $studentName = $student->first_name . ' ' . $student->last_name;
        }
    }
    
    $universityName = 'All Universities';
    if ($universityId) {
        $universityIds = is_array($universityId) ? array_filter($universityId) : [$universityId];
        if (!empty($universityIds)) {
            $universities = University::whereIn('id', $universityIds)->pluck('name')->toArray();
            $universityName = implode(', ', $universities);
        }
    }
    
    $departmentName = 'All Departments';
    if ($department) {
        $departments = is_array($department) ? array_filter($department) : [$department];
        if (!empty($departments)) {
            $departmentName = implode(', ', $departments);
        }
    }
    
    $degreeName = 'All Degrees';
    if ($degree) {
        $degrees = is_array($degree) ? array_filter($degree) : [$degree];
        if (!empty($degrees)) {
            $degreeName = implode(', ', $degrees);
        }
    }
    
    // Get status name
    $statusName = 'All Statuses';
    if ($status) {
        $statuses = Application::STATUSES;
        $statusName = $statuses[$status] ?? $status;
    }
    
    // Get the company logo
    $companyLogoPath = public_path('logo.jpg');
    $companyLogo = '';
    if (file_exists($companyLogoPath)) {
        $type = pathinfo($companyLogoPath, PATHINFO_EXTENSION);
        $data = file_get_contents($companyLogoPath);
        $companyLogo = 'data:image/' . $type . ';base64,' . base64_encode($data);
    }
    
    // Create PDF with image options enabled
    $pdf = app('dompdf.wrapper');
    $pdf->getDomPDF()->set_option('isHtml5ParserEnabled', true);
    $pdf->getDomPDF()->set_option('isRemoteEnabled', true);
    
    $pdf->loadView('admin.applications.pdf', [
        'applications' => $applications,
        'studentName' => $studentName,
        'universityName' => $universityName,
        'departmentName' => $departmentName,
        'degreeName' => $degreeName,
        'statusName' => $statusName,
        'companyLogo' => $companyLogo,
        'date' => now()->format('F d, Y'),
        'user' => $user,
        'filterInfo' => [
            'student' => $studentName,
            'university' => $universityName,
            'department' => $departmentName,
            'degree' => $degreeName,
            'status' => $statusName
        ]
    ]);
    
    // Use landscape orientation for better fit
    $pdf->setPaper('a4', 'landscape');
    
    return $pdf->download('applications_' . date('Y-m-d_H-i-s') . '.pdf');
}
    
    public function create()
    {
        $students = Student::all();
        $universities = University::orderBy('name')->get();
        
        // Predefined array of departments
        $departments = Department::all();
        
        $degrees = Degree::all();
        $languages = ['English', 'French', 'German', 'Spanish', 'Other'];
        $semesters = ['Fall', 'Spring', 'Summer', 'Winter'];
        
        return view('admin.applications.create', compact(
            'students',
            'universities',
            'departments',
            'degrees',
            'languages',
            'semesters'
        ));
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'university_id' => 'required|exists:universities,id',
            'department' => 'required|string|max:255',
            'code' => 'nullable|string|max:255',
            'degree' => 'required|string|max:255',
            'language' => 'required|string|max:255',
            'semester' => 'required|string|max:255',
            'notes' => 'nullable|string',
            'first_acceptance' => 'nullable|file|max:10240',
            'payment_file' => 'nullable|file|max:10240',
            'final_acceptance' => 'nullable|file|max:10240',
            'awaiting_student_card' => 'nullable|file|max:10240',
        ]);
        
        // Start database transaction
        DB::beginTransaction();
        
        try {
            // Create the application - filter out file fields from the validated data
            $applicationData = array_filter($validated, function($key) {
                return !in_array($key, ['first_acceptance', 'payment_file', 'final_acceptance', 'awaiting_student_card']);
            }, ARRAY_FILTER_USE_KEY);
            
            $applicationData['created_by'] = auth()->id();
            $applicationData['status'] = 'Application Submitted';
            
            $application = Application::create($applicationData);
            
            // Define the files we want to handle
            $specificFiles = [
                'first_acceptance' => 'First Acceptance Letter',
                'payment_file' => 'Payment Confirmation',
                'final_acceptance' => 'Final Acceptance Letter',
                'awaiting_student_card' => 'Awaiting Student Card'
            ];
            
            $uploadErrors = [];
            
            // Process files individually, continuing even if one fails
            foreach ($specificFiles as $fileKey => $fileDescription) {
                if ($request->hasFile($fileKey)) {
                    try {
                        $this->storeApplicationFile(
                            $application, 
                            $request->file($fileKey), 
                            'official_document', 
                            $fileKey
                        );
                        
                        Log::info("Successfully uploaded {$fileDescription} for application ID: {$application->id}");
                    } catch (\Exception $e) {
                        // Log the error but continue with other files
                        Log::error("Error uploading {$fileDescription}: " . $e->getMessage());
                        $uploadErrors[] = "Could not upload {$fileDescription}: {$e->getMessage()}";
                    }
                }
            }
            
            $creatorId  = auth()->id();
            $student    = $application->student;
            $appRef     = "Application #{$application->id} — {$student->first_name} {$student->last_name}";
            $notifData  = fn($recipientId) => [
                'type'            => 'New Application',
                'notifiable_type' => 'App\Models\User',
                'notifiable_id'   => $recipientId,
                'data'            => [
                    'message'        => "New application submitted: {$appRef}",
                    'application_id' => $application->id,
                    'created_by'     => $creatorId,
                ],
            ];

            // 1. Notify every Register user
            User::where('role', 'Register')->each(function ($register) use ($notifData, $creatorId) {
                if ($register->id !== $creatorId) {
                    Notification::create($notifData($register->id));
                }
            });

            // 2. Notify the creator themselves (confirmation) — only if they are not Register
            //    so Register creating their own app doesn't get double-notified
            if (auth()->user()->role !== 'Register') {
                Notification::create(array_merge($notifData($creatorId), [
                    'data' => [
                        'message'        => "✅ Your application was submitted successfully: {$appRef}",
                        'application_id' => $application->id,
                        'created_by'     => $creatorId,
                    ],
                ]));
            }
            
       
            DB::commit();

            // Return with success message, but include warnings about failed uploads if any
            if (empty($uploadErrors)) {
                return redirect()->route('admin.applications.index')
                    ->with('success', 'Application created successfully with all uploaded files.');
            } else {
                $warningMessage = 'Application created, but some files could not be uploaded. You can add them later.';
                return redirect()->route('admin.applications.index')
                    ->with('success', 'Application created successfully.')
                    ->with('warning', $warningMessage);
            }
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            // Log the error
            Log::error("Error creating application: " . $e->getMessage());
            
            return redirect()->back()->withInput()
                ->with('error', 'Error creating application: ' . $e->getMessage());
        }
    }

    private function storeApplicationFile($application, $file, $fileType, $description)
    {
        try {
            $originalFilename = $file->getClientOriginalName();
            $mimeType = $file->getMimeType();
            $fileSize = $file->getSize() / 1024; // Convert to KB
            
            // Generate a unique filename
            $filename = 'app_' . $application->id . '_' . $fileType . '_' . $description . '_' . time() . '.' . $file->getClientOriginalExtension();
            
            // Log before storing
            Log::info("Attempting to store file: {$filename} of type {$fileType}");
            
            // Store the file in an application-specific directory
            $path = $file->storeAs(
                'application-files/' . $application->id,
                $filename,
                'public'
            );
            
            // Check if file was actually stored
            if (!Storage::disk('public')->exists($path)) {
                throw new \Exception("File could not be stored at path: {$path}");
            }
            
            Log::info("File stored at path: {$path}");
            
            // Create the file record
            $record = ApplicationFiles::create([
                'application_id' => $application->id,
                'file_type' => $fileType,
                'file_path' => $path,
                'original_filename' => $originalFilename,
                'mime_type' => $mimeType,
                'file_size' => $fileSize,
                'description' => $description,
                'uploaded_by' => auth()->id(),
            ]);
            
            Log::info("File record created with ID: {$record->id}");
            
            return $record;
        } catch (\Exception $e) {
            Log::error("Error storing file: " . $e->getMessage());
            throw $e;
        }
    }
    

    


    public function show(Application $application)
    {
        // Load application with related data
        $application->load([
            'student',
            'university',
            'university.city',
            'university.country',
            'creator',
            'files' => function($query) {
                $query->orderBy('file_type')->orderBy('created_at');
            },
            'files.uploader' // Eagerly load the uploader relationship
        ]);
       
        $documents = $application->files->where('file_type', 'document');
        $certificates = $application->files->where('file_type', 'certificate');
       
        $officialFiles = $application->files->where('file_type', 'official_document');
       
        $firstAcceptance = $officialFiles->where('description', 'first_acceptance')->first();
        $paymentFile = $officialFiles->where('description', 'payment_file')->first();
        $finalAcceptance = $officialFiles->where('description', 'final_acceptance')->first();
        $awaitingStudentCard = $officialFiles->where('description', 'awaiting_student_card')->first();
       
        // Define which required files are missing
        $missingFiles = [];
        if (!$firstAcceptance) $missingFiles[] = 'first_acceptance';
        if (!$paymentFile) $missingFiles[] = 'payment_file';
        if (!$finalAcceptance) $missingFiles[] = 'final_acceptance';
        if (!$awaitingStudentCard) $missingFiles[] = 'awaiting_student_card';
       
        return view('admin.applications.show', compact(
            'application',
            'documents',
            'certificates',
            'firstAcceptance',
            'paymentFile',
            'finalAcceptance',
            'awaitingStudentCard',
            'missingFiles'
        ));
    }


    public function edit(Application $application)
    {
        $students = Student::all();
        $universities = University::orderBy('name')->get();
        
        // Predefined array of departments
        $departments = Department::all();
        
        $degrees = Degree::all();
        $languages = ['English', 'French', 'German', 'Spanish', 'Other'];
        $semesters = ['Fall', 'Spring', 'Summer', 'Winter'];
        
        // Load application with related files
        $application->load([
            'files' => function($query) {
                $query->orderBy('file_type')->orderBy('created_at');
            }
        ]);
        
        // Separate files by type
        $documents = $application->files->where('file_type', 'document');
        $certificates = $application->files->where('file_type', 'certificate');
        
        return view('admin.applications.edit', compact(
            'application', 
            'students',
            'universities',
            'departments',
            'degrees',
            'languages',
            'semesters',
            'documents',
            'certificates'
        ));
    }

    public function update(Request $request, Application $application)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'university_id' => 'required|exists:universities,id',
            'department' => 'required|string|max:255',
            'degree' => 'required|string|max:255',
            'code' => 'nullable|string|max:255',
            'language' => 'required|string|max:255',
            'semester' => 'required|string|max:255',
            'notes' => 'nullable|string',
            'status' => 'required|in:' . implode(',', array_keys(Application::STATUSES)),
            'documents' => 'nullable|array',
            'documents.*' => 'nullable|file|max:10240',
            'certificates' => 'nullable|array',
            'certificates.*' => 'nullable|file|max:10240',
            'additional_files' => 'nullable|array',
            'additional_files.*' => 'nullable|file|max:10240',
        ]);

        // Start database transaction
        DB::beginTransaction();
        
        try {
            // Update the application
            $application->update($validated);
            
            // Process documents
            if ($request->hasFile('documents')) {
                foreach ($request->file('documents') as $type => $file) {
                    if ($file) {
                        $this->storeApplicationFile($application, $file, 'document', $type);
                    }
                }
            }
            
            // Process certificates
            if ($request->hasFile('certificates')) {
                foreach ($request->file('certificates') as $type => $file) {
                    if ($file) {
                        $this->storeApplicationFile($application, $file, 'certificate', $type);
                    }
                }
            }
            
            // Process additional files
            if ($request->hasFile('additional_files')) {
                foreach ($request->file('additional_files') as $index => $file) {
                    $this->storeApplicationFile($application, $file, 'document', 'additional_' . ($index + 1));
                }
            }
            
            DB::commit();

            return redirect()->route('admin.applications.index')
                ->with('success', 'Application updated successfully with uploaded files.');
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()->withInput()
                ->with('error', 'Error updating application: ' . $e->getMessage());
        }
    }

    public function destroy(Application $application)
    {
            
            $application->delete();
            
            return redirect()->route('admin.applications.index')
                ->with('success', 'Application and associated files deleted successfully.');
        
    }

   /**
 * Update the application status
 */
public function updateStatus(Request $request, Application $application)
{
    Log::info('updateStatus called', [
        'application_id' => $application->id,
        'status'         => $request->input('status'),
        'method'         => $request->method(),
        'ajax'           => $request->ajax(),
    ]);

    $request->validate([
        'status' => ['required', Rule::in(array_keys(Application::STATUSES))],
        'notes'  => 'nullable|string',
    ]);

    $oldStatusName = $application->status;
    $newStatusName = $request->status; // The dropdown sends the status name directly

    // Update application status with the selected name
    $application->update(['status' => $newStatusName]);

    // Record the change in application_stage when a matching admission stage exists.
    // Not every manual status maps to an admission_stage row, so this is optional.
    $selectedStage = \App\Models\AdmissionStage::where('name', $newStatusName)->first();

    if ($selectedStage) {
        $existingStage = DB::table('application_stage')
            ->where('application_id', $application->id)
            ->where('admission_stage_id', $selectedStage->id)
            ->first();

        if (!$existingStage) {
            DB::table('application_stage')->insert([
                'application_id' => $application->id,
                'admission_stage_id' => $selectedStage->id,
                'created_by' => auth()->id(),
                'is_completed' => 1,
                'notes' => $request->notes ?? "Status changed from {$oldStatusName} to {$newStatusName}",
                'created_at' => now(),
                'updated_at' => now()
            ]);
        } else {
            DB::table('application_stage')
                ->where('id', $existingStage->id)
                ->update([
                    'is_completed' => 1,
                    'completed_at' => now(),
                    'completed_by' => auth()->id(),
                    'notes' => $request->notes ?? $existingStage->notes,
                    'updated_at' => now()
                ]);
        }
    }
    
    // Get the student information from the application
    $student = $application->student;
    
    if (NotificationSetting::isEnabled('application_status_changed')) {
        $notifyRoles = NotificationSetting::rolesFor('application_status_changed');
        $users = \App\Models\User::whereIn('role', $notifyRoles)->get();
        foreach ($users as $user) {
            Notification::create([
                'type' => 'application_status_changed',
                'notifiable_type' => 'App\Models\User',
                'notifiable_id' => $user->id,
                'data' => [
                    'message' => "Application status updated to {$newStatusName}: {$student->first_name} {$student->last_name}",
                    'student_id' => $student->id,
                    'student_name' => $student->first_name . ' ' . $student->last_name,
                    'application_id' => $application->id,
                    'time' => now()->diffForHumans()
                ],
            ]);
        }
    }
    
    // Log the status change
    // if (method_exists($application, 'statusLogs')) {
    //     $application->statusLogs()->create([
    //         'status' => $newStatusName,
    //         'user_id' => auth()->id(),
    //         'notes' => $request->notes ?? "Status changed from {$oldStatusName} to {$newStatusName}"
    //     ]);
    // }
   
    // Respond with JSON for AJAX (show page + inline list update the badge in place)
    if ($request->ajax() || $request->wantsJson()) {
        $newClass = $this->getStatusClass($newStatusName);
        $oldClass = $this->getStatusClass($oldStatusName);

        // Header-style badge used on the show page
        $statusBadge = '<span class="badge badge-' . $newClass . ' px-3 py-2">'
            . '<i class="fas fa-certificate mr-1"></i> ' . e($newStatusName) . '</span>';

        // Pill-style badge used in the applications list (shared partial → stays consistent with reloads)
        $listBadge = view('partials.status-badge', ['status' => $newStatusName])->render();

        return response()->json([
            'success'          => true,
            'message'          => 'Application status updated successfully.',
            'new_status'       => $newStatusName,
            'new_status_class' => $newClass,
            'old_status_class' => $oldClass,
            'status_badge'     => $statusBadge,
            'list_badge'       => $listBadge,
        ]);
    }

    // Flash a success message
    session()->flash('success', 'Application status updated successfully.');

    // Redirect back
    return redirect()->back();
}

/**
 * Update the application's external reference code.
 */
public function updateCode(Request $request, Application $application)
{
    $request->validate([
        'code' => 'nullable|string|max:100',
    ]);

    $application->update(['code' => $request->code]);

    if ($request->ajax() || $request->wantsJson()) {
        return response()->json([
            'success' => true,
            'message' => 'Application code updated successfully.',
            'code'    => $application->code,
        ]);
    }

    session()->flash('success', 'Application code updated successfully.');
    return redirect()->back();
}

/**
 * Get the admission_stage_id based on the application status
 * Using the actual data from the admission_stages table
 */
private function getAdmissionStageIdByStatus($status)
{
    // Status to stage name mapping based on your admission_stages table data
    $statusToStageMap = [
        'Pending Review' => 'Pending Review',
        'Awaiting App Fees Payment' => 'Awaiting App Fees Payment',
        'Awaiting Conditional Acceptance' => 'Awaiting Conditional Acceptance',
        'Conditional Acceptance' => 'Conditional Acceptance',
        'Awaiting Payment' => 'Awaiting Payment',
        'Paid' => 'Paid',
        'Awaiting Final Acceptance' => 'Awaiting Final Acceptance',
        'Final Acceptance' => 'Final Acceptance', // This might be in your table but wasn't shown
        'Awaiting Student Card' => 'Awaiting Student Card', // This might be in your table but wasn't shown
        'Completed' => 'Completed', // This might be in your table but wasn't shown
        'Free Scholarship' => 'Completed', // Mapping to Completed stage
        '100% Scholarship' => 'Completed', // Mapping to Completed stage
    ];
    
    // Get the stage name for this status
    $stageName = $statusToStageMap[$status] ?? null;
    
    if (!$stageName) {
        return null;
    }
    
    // Query the database to get the stage ID by name
    $stage = DB::table('admission_stages')
        ->where('name', $stageName)
        ->first();
    
    return $stage ? $stage->id : null;
}

/**
 * Get CSS class for a status (copied from your view helper)
 */
private function getStatusClass($status) 
{
    $statusMap = [
        'Completed' => 'success',
        'Paid' => 'success',
        'Final Acceptance' => 'success',
        'Free Scholarship' => 'success',
        '100% Scholarship' => 'success',
        'Pending Review' => 'warning',
        'Awaiting App Fees Payment' => 'warning',
        'Awaiting Student' => 'warning',
        'Awaiting Payment' => 'warning',
        'Refused' => 'danger',
        'Cancelled' => 'danger',
        'Quota Full' => 'danger',
        'Refund Request (Visa Rejected)' => 'danger',
        'Student Duplicated' => 'danger',
        'Awaiting Conditional Acceptance' => 'info',
        'Conditional Acceptance' => 'primary',
        'Awaiting Final Acceptance' => 'info',
        'Awaiting Student Card' => 'info'
    ];
    
    return $statusMap[$status] ?? 'secondary';
}

    /**
     * Download a specific application file
     *
     * @param Application $application
     * @param ApplicationFile $file
     * @return \Illuminate\Http\Response
     */
    public function downloadFile(Application $application, ApplicationFiles $file)
    {
        // Security check - ensure the file belongs to this application
        if ($file->application_id !== $application->id) {
            abort(403, 'Unauthorized action');
        }
        
        // Check if the file exists
        if (!Storage::disk('public')->exists($file->file_path)) {
            return back()->with('error', 'File does not exist.');
        }
        
        return Storage::disk('public')->download($file->file_path, $file->original_filename);
    }

    /**
     * Delete a specific application file
     *
     * @param Application $application
     * @param ApplicationFile $file
     * @return \Illuminate\Http\Response
     */
    public function deleteFile(Application $application, ApplicationFiles $file)
    {
        // Check if the file belongs to this application
        if ($file->application_id !== $application->id) {
            return redirect()->back()->with('error', 'File not found for this application.');
        }
    
        // Delete the file from storage
        if (Storage::exists($file->file_path)) {
            Storage::delete($file->file_path);
        }
    
        // Delete the record from database
        $file->delete();
    
        return redirect()->back()->with('success', 'File deleted successfully.');
    }
    /**
     * Export applications data to Excel
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */



/**
 * Controller method to handle uploading missing files for an application
 * Add this to your ApplicationController
 */
public function uploadMissingFiles(Request $request, Application $application)
{
    $request->validate([
        'first_acceptance' => 'nullable|file|max:10240',
        'payment_file' => 'nullable|file|max:10240',
        'final_acceptance' => 'nullable|file|max:10240',
        'awaiting_student_card' => 'nullable|file|max:10240',
    ]);
   
    // Check immediately if student card is being uploaded and log it
    $updateStatus = $request->hasFile('awaiting_student_card');
    Log::info("Student card file detected: " . ($updateStatus ? 'YES' : 'NO'));
    
    // Also log all request files to debug
    Log::info("All uploaded files: " . json_encode($request->allFiles()));
   
    // Start database transaction
    DB::beginTransaction();
   
    try {
        $uploadedFiles = [];
        $failedFiles = [];
       
        // Process the four specific files
        $specificFiles = [
            'first_acceptance' => 'First Acceptance Letter',
            'payment_file' => 'Payment Confirmation',
            'final_acceptance' => 'Final Acceptance Letter',
            'awaiting_student_card' => 'Awaiting Student Card'
        ];
       
        foreach ($specificFiles as $fileKey => $fileDescription) {
            if ($request->hasFile($fileKey)) {
                try {
                    // Check if file already exists
                    $existingFile = $application->files()
                        ->where('file_type', 'official_document')
                        ->where('description', $fileKey)
                        ->first();
                   
                    if ($existingFile) {
                        // Skip this file since it already exists
                        continue;
                    }
                   
                    // Store the file
                    $this->storeApplicationFile(
                        $application,
                        $request->file($fileKey),
                        'official_document',
                        $fileKey
                    );
                    
                    $uploadedFiles[] = $fileDescription;
                    
                    // Log when we're processing the student card file
                    if ($fileKey === 'awaiting_student_card') {
                        Log::info("Processing student card file in loop for application ID: {$application->id}");
                    }
                    
                } catch (\Exception $e) {
                    // Log the error but continue with other files
                    Log::error("Error uploading {$fileDescription}: " . $e->getMessage());
                    $failedFiles[] = $fileDescription;
                }
            }
        }
        
        // Update application status if student card was uploaded
        if ($updateStatus) {
            // Log before status update
            Log::info("Before status update - Current application status: " . $application->status);
            
            // Approach 1: Direct model update
            $application->status = 'Completed';
            $application->save();
            
            // Log after first update attempt
            Log::info("After model save - Application status should be: " . $application->status);
            
            // Approach 2: Query Builder update as backup
            DB::table('applications')
                ->where('id', $application->id)
                ->update(['status' => 'Completed']);
            
            // Reload the model to see if status was updated
            $application = $application->fresh();
            Log::info("After DB update and refresh - Application status is now: " . $application->status);
        }
       
        // Commit the transaction
        DB::commit();
       
        // Prepare success/error messages
        if (count($uploadedFiles) > 0) {
            $successMessage = 'Successfully uploaded: ' . implode(', ', $uploadedFiles) . '.';
           
            if (count($failedFiles) > 0) {
                $errorMessage = 'Failed to upload: ' . implode(', ', $failedFiles) . '. Please try again.';
                return redirect()->route('admin.applications.show', $application)
                    ->with('success', $successMessage)
                    ->with('error', $errorMessage);
            }
           
            return redirect()->route('admin.applications.show', $application)
                ->with('success', $successMessage);
        } elseif (count($failedFiles) > 0) {
            $errorMessage = 'Failed to upload: ' . implode(', ', $failedFiles) . '. Please try again.';
            return redirect()->route('admin.applications.show', $application)
                ->with('error', $errorMessage);
        } else {
            return redirect()->route('admin.applications.show', $application)
                ->with('info', 'No files were selected for upload.');
        }
       
    } catch (\Exception $e) {
        DB::rollBack();
       
        // Log the error
        Log::error("Error uploading files: " . $e->getMessage());
       
        return redirect()->route('admin.applications.show', $application)
            ->with('error', 'Error uploading files: ' . $e->getMessage());
    }
}
/**
 * Controller method to handle uploading a single file for an application
 * Add this to your ApplicationController
 */
public function uploadFile(Request $request, Application $application)
{
    $request->validate([
        'file'      => 'required|file|max:10240|mimes:pdf,jpg,jpeg,png,webp',
        'file_type' => 'required|string|in:first_acceptance,payment_file,final_acceptance,awaiting_student_card',
    ], [
        'file.mimes' => 'The document must be a PDF or an image (JPG, PNG, WEBP).',
        'file.max'   => 'The document may not be larger than 10 MB.',
    ]);

    $fileLabels = [
        'first_acceptance'     => 'First Acceptance Letter',
        'payment_file'         => 'Payment Confirmation',
        'final_acceptance'     => 'Final Acceptance Letter',
        'awaiting_student_card'=> 'Student Card',
    ];

    $fileType    = $request->file_type;
    $fileLabel   = $fileLabels[$fileType];
    $uploaderId  = auth()->id();

    // ── Permission gate ──────────────────────────────────────────
    // First Acceptance & Final Acceptance → Register only
    // Payment Confirmation & Student Card → Sales (creator) only
    $uploaderRole = auth()->user()->role;
    if (in_array($fileType, ['first_acceptance', 'final_acceptance']) && $uploaderRole !== 'Register') {
        return back()->with('error', 'Only Register can upload this document.');
    }
    if (in_array($fileType, ['payment_file', 'awaiting_student_card']) && $uploaderRole !== 'Sales' && $uploaderRole !== 'Admin') {
        return back()->with('error', 'Only the Sales agent can upload this document.');
    }

    DB::beginTransaction();
    try {
        // Replace if already exists
        $existing = $application->files()
            ->where('file_type', 'official_document')
            ->where('description', $fileType)
            ->first();
        if ($existing) {
            Storage::disk('public')->delete($existing->file_path);
            $existing->delete();
        }

        // Store file
        $this->storeApplicationFile($application, $request->file('file'), 'official_document', $fileType);

        // ── Status advance ───────────────────────────────────────
        $newStatus = match($fileType) {
            'first_acceptance'      => 'First Acceptance',
            'payment_file'          => 'Payment Confirmed',
            'final_acceptance'      => 'Final Acceptance',
            'awaiting_student_card' => 'Completed',
        };
        $application->status = $newStatus;
        $application->save();

        // ── Targeted notifications ───────────────────────────────
        // Reload files after the new one was stored
        $application->load('files');
        $officialFiles = $application->files->where('file_type', 'official_document');

        // Specific people involved in this application's document chain
        $salesCreator   = $application->creator; // Sales who created the application

        $firstAccFile   = $officialFiles->where('description', 'first_acceptance')->first();
        $registerWhoUploadedFirst = $firstAccFile ? User::find($firstAccFile->uploaded_by) : null;

        $finalAccFile   = $officialFiles->where('description', 'final_acceptance')->first();
        $registerWhoUploadedFinal = $finalAccFile ? User::find($finalAccFile->uploaded_by) : null;

        // Helper: send notification to one specific user (never to yourself)
        $notify = function(?int $recipientId, string $type, string $message) use ($application, $uploaderId, $fileType) {
            if (!$recipientId || $recipientId === $uploaderId) return;
            Notification::create([
                'type'            => $type,
                'notifiable_type' => 'App\Models\User',
                'notifiable_id'   => $recipientId,
                'data'            => [
                    'message'        => $message,
                    'application_id' => $application->id,
                    'uploaded_by'    => $uploaderId,
                    'file_type'      => $fileType,
                ],
            ]);
        };

        $appRef = "Application #{$application->id} — {$application->student->first_name} {$application->student->last_name}";

        switch ($fileType) {

            // STEP 1: Register uploaded First Acceptance
            // → notify the Sales agent who created the application
            case 'first_acceptance':
                $notify(
                    $salesCreator?->id,
                    'file_uploaded',
                    "✅ First Acceptance Letter uploaded for {$appRef}. Please now upload the Payment Confirmation."
                );
                break;

            // STEP 2: Sales uploaded Payment Confirmation
            // → notify the exact Register who uploaded the First Acceptance
            case 'payment_file':
                $notify(
                    $registerWhoUploadedFirst?->id,
                    'payment_uploaded',
                    "💰 Payment Confirmed for {$appRef}. Please now upload the Final Acceptance Letter."
                );
                break;

            // STEP 3: Register uploaded Final Acceptance
            // → notify the Sales agent who created the application
            case 'final_acceptance':
                $notify(
                    $salesCreator?->id,
                    'file_uploaded',
                    "✅ Final Acceptance Letter uploaded for {$appRef}. Please now upload the Student Card."
                );
                break;

            // STEP 4: Sales uploaded Student Card → application is Completed
            // → notify the Register who uploaded the Final Acceptance (the one who did the last step)
            case 'awaiting_student_card':
                $notify(
                    $registerWhoUploadedFinal?->id,
                    'file_uploaded',
                    "🎓 Student Card uploaded for {$appRef}. Application is now Completed."
                );
                break;
        }

        DB::commit();

        return redirect()->route('admin.applications.show', $application)
            ->with('success', "{$fileLabel} uploaded successfully. Status updated to: {$newStatus}.");

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error("uploadFile error: " . $e->getMessage());
        return redirect()->route('admin.applications.show', $application)
            ->with('error', 'Error uploading file: ' . $e->getMessage());
    }
}

}