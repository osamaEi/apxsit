<?php

namespace App\Http\Controllers;



use App\Models\User;
use App\Models\Degree;
use App\Models\Program;
use App\Models\Student;
use App\Models\Department;
use App\Models\University;
use Illuminate\Http\Request;
use App\Exports\StudentsExport;
use Illuminate\Validation\Rule;
use Barryvdh\DomPDF\Facade\PDF; 
use Nnjeim\World\Models\Country;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class StudentController extends Controller
{
    /**
     * Display a listing of the students.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $search = $request->input('search', '');
        $statusFilter = $request->input('status');
        $countryFilter = $request->input('country_id');
        $employeeFilter = $request->input('employee_id');
        $degreeFilter = $request->input('degree_id');
        
        if ($user->role == 'Admin' || $user->role == 'Register') {
            // Admin sees all students
            $query = Student::with(['studyCountry', 'responsibleEmployee', 'applyingDegree', 'nationality']);
        } else {
            // Get IDs of all users who have this user as their parent
            $childUserIds = User::where('parent_id', $user->id)->pluck('id')->toArray();
           
            // Add the current user's ID to the array of IDs to include
            $userIds = array_merge([$user->id], $childUserIds);
           
            // Get all students processed by the current user OR any of their child users
            $query = Student::whereIn('processed_by', $userIds)
                            ->with(['studyCountry', 'responsibleEmployee', 'applyingDegree', 'nationality']);
        }
        
        // Apply search filter
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('passport_id', 'like', "%{$search}%")
                  ->orWhere('phone_number', 'like', "%{$search}%");
            });
        }
        
        // Apply status filter
        if ($statusFilter) {
            $query->where('status', $statusFilter);
        }
        
        // Apply country filter
        if ($countryFilter) {
            $query->where('study_country_id', $countryFilter);
        }
        
        // Apply employee filter
        if ($employeeFilter) {
            $query->where('employee_id', $employeeFilter);
        }
        
        // Apply degree filter
        if ($degreeFilter) {
            $query->where('applying_degree_id', $degreeFilter);
        }
        
        $students = $query->latest()->paginate(10);
       
        $countries = Country::orderBy('name')->get();
        $employees = User::where('role', '!=', 'Admin')->orderBy('name')->get();
        $degrees = Program::orderBy('department')->get();
        $statuses = Student::getStatuses();
        
        return view('admin.students.index', compact(
            'students',
            'countries',
            'employees',
            'degrees',
            'statuses',
            'search',
            'statusFilter',
            'countryFilter',
            'employeeFilter',
            'degreeFilter'
        ));
    }
    
    /**
     * Export students data to Excel
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportExcel(Request $request)
    {
        $user = Auth::user();
        
        // Get filter parameters directly from the request
        $search = $request->input('search', '');
        $statusFilter = $request->input('status');
        $countryFilter = $request->input('country_id');
        $employeeFilter = $request->input('employee_id');
        $degreeFilter = $request->input('degree_id');
        
        // Start with the base query and apply user role restrictions
        if ($user->role == 'Admin' || $user->role == 'Register') {
            // Admin sees all students
            $query = Student::with(['studyCountry', 'responsibleEmployee', 'applyingDegree', 'nationality', 'processedBy']);
        } else {
            // Get IDs of all users who have this user as their parent
            $childUserIds = User::where('parent_id', $user->id)->pluck('id')->toArray();
            
            // Add the current user's ID to the array of IDs to include
            $userIds = array_merge([$user->id], $childUserIds);
            
            // Get all students processed by the current user OR any of their child users
            $query = Student::whereIn('processed_by', $userIds)
                           ->with(['studyCountry', 'responsibleEmployee', 'applyingDegree', 'nationality', 'processedBy']);
        }
        
        // Apply filters - exactly the same as in the index method
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('passport_id', 'like', "%{$search}%")
                  ->orWhere('phone_number', 'like', "%{$search}%");
            });
        }
        
        if (!empty($statusFilter)) {
            $query->where('status', $statusFilter);
        }
        
        if (!empty($countryFilter)) {
            $query->where('study_country_id', $countryFilter);
        }
        
        if (!empty($employeeFilter)) {
            $query->where('employee_id', $employeeFilter);
        }
        
        if (!empty($degreeFilter)) {
            $query->where('applying_degree_id', $degreeFilter);
        }
        
        // Get students based on current filters
        $students = $query->latest()->get();
        
        // Create Excel file
        $filename = 'students_' . date('Y-m-d_H-i-s') . '.xlsx';
        
        return Excel::download(new StudentsExport($students), $filename);
    }

    /**
     * Export students data to PDF
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function exportPdf(Request $request)
    {
        $user = Auth::user();
        
        // Get filter parameters
        $search = $request->input('search', '');
        $statusFilter = $request->input('status');
        $countryFilter = $request->input('country_id');
        $employeeFilter = $request->input('employee_id');
        $degreeFilter = $request->input('degree_id');
        
        // Start with the base query and apply user role restrictions
        if ($user->role == 'Admin' || $user->role == 'Register') {
            // Admin sees all students
            $query = Student::with(['studyCountry', 'responsibleEmployee', 'applyingDegree', 'nationality', 'processedBy']);
        } else {
            // Get IDs of all users who have this user as their parent
            $childUserIds = User::where('parent_id', $user->id)->pluck('id')->toArray();
            
            // Add the current user's ID to the array of IDs to include
            $userIds = array_merge([$user->id], $childUserIds);
            
            // Get all students processed by the current user OR any of their child users
            $query = Student::whereIn('processed_by', $userIds)
                           ->with(['studyCountry', 'responsibleEmployee', 'applyingDegree', 'nationality', 'processedBy']);
        }
        
        // Apply filters - exactly the same as in the index method
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('passport_id', 'like', "%{$search}%")
                  ->orWhere('phone_number', 'like', "%{$search}%");
            });
        }
        
        if ($statusFilter) {
            $query->where('status', $statusFilter);
        }
        
        if ($countryFilter) {
            $query->where('study_country_id', $countryFilter);
        }
        
        if ($employeeFilter) {
            $query->where('employee_id', $employeeFilter);
        }
        
        if ($degreeFilter) {
            $query->where('applying_degree_id', $degreeFilter);
        }
        
        // Get students based on current filters
        $students = $query->latest()->get();
        
        // Process student photos to base64 for reliable PDF rendering
        foreach ($students as $student) {
            if ($student->photo_path) {
                // Get the physical path to the file
                // Remove "storage/" prefix if it exists
                $photoPath = str_replace('storage/', '', $student->photo_path);
                
                // Build the full path to the storage file
                $path = storage_path('app/public/' . $photoPath);
                
                if (file_exists($path)) {
                    $type = pathinfo($path, PATHINFO_EXTENSION);
                    $data = file_get_contents($path);
                    $student->photo_base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                } else {
                    $student->photo_base64 = null;
                }
            } else {
                $student->photo_base64 = null;
            }
        }
        
        // Get information for header/filters
        $countries = Country::all();
        $countryName = $countryFilter ? ($countries->where('id', $countryFilter)->first()->name ?? 'All') : 'All';
        
        $employees = User::all();
        $employeeName = $employeeFilter ? ($employees->where('id', $employeeFilter)->first()->name ?? 'All') : 'All';
        
        $degrees = Program::all();
        $degreeName = $degreeFilter ? ($degrees->where('id', $degreeFilter)->first()->department ?? 'All') : 'All';
        
        $statuses = Student::getStatuses();
        $statusName = $statusFilter ? ($statuses[$statusFilter] ?? $statusFilter) : 'All';
        
        // Calculate totals for summary stats
        $totalStudents = $students->count();
        $acceptedCount = $students->where('status', 'Accepted')->count();
        $pendingCount = $students->where('status', 'Pending Documents')->count();
        $countriesCount = $students->pluck('study_country_id')->unique()->count();
        
        // Create PDF with image options enabled
        $pdf = app('dompdf.wrapper');
        $pdf->getDomPDF()->set_option('isHtml5ParserEnabled', true);
        $pdf->getDomPDF()->set_option('isRemoteEnabled', true);
        
        $pdf->loadView('admin.students.pdf', [
            'students' => $students,
            'search' => $search,
            'statusName' => $statusName,
            'countryName' => $countryName,
            'employeeName' => $employeeName,
            'degreeName' => $degreeName,
            'totalStudents' => $totalStudents,
            'acceptedCount' => $acceptedCount,
            'pendingCount' => $pendingCount,
            'countriesCount' => $countriesCount,
            'date' => now()->format('F d, Y')
        ]);
        
        // Use landscape orientation for better fit
        $pdf->setPaper('a4', 'landscape');
        
        return $pdf->download('students_' . date('Y-m-d_H-i-s') . '.pdf');
    }

    /**
     * Show the form for creating a new student - Step 1: Basic Information.
     */
    public function createStep1()
    {
        $countries = Country::orderBy('name')->get();
        $studycountries = Country::whereIn('name',['Turkey','Cyprus'])->get();

        $employees = User::where('role', '!=', 'Admin')->orderBy('name')->get();
        
        return view('admin.students.create-step1', compact('countries', 'employees','studycountries'));
    }

    /**
     * Store the basic information and proceed to step 2.
     */
    public function storeStep1(Request $request)
    {
        $validated = $request->validate([
            'academic_year' => 'required|string',
            'study_country_id' => 'required|exists:countries,id',
            'is_transfer' => 'boolean',
        ]);

        // Store in session
        $request->session()->put('student_data.basic', $validated);
        
        return redirect()->route('admin.students.create.step2');
    }

    /**
     * Show the form for creating a new student - Step 2: Passport Information.
     */
    public function createStep2(Request $request)
    {
        // Check if step 1 is completed
        if (!$request->session()->has('student_data.basic')) {
            return redirect()->route('admin.students.create.step1');
        }
        
        return view('admin.students.create-step2');
    }

    /**
     * Store the passport information and proceed to step 3.
     */
    public function storeStep2(Request $request)
    {
        $validated = $request->validate([
            'date_of_birth' => 'required|date',
            'passport_id' => 'required|string|max:255',
            'passport_issue_date' => 'required|date',
            'passport_expiry_date' => 'required|date|after:passport_issue_date',
            'needs_visa_support' => 'boolean',
        ]);

        // Store in session
        $request->session()->put('student_data.passport', $validated);
        
        return redirect()->route('admin.students.create.step3');
    }

    /**
     * Show the form for creating a new student - Step 3: Personal Information.
     */
    public function createStep3(Request $request)
    {
        // Check if previous steps are completed
        if (!$request->session()->has('student_data.basic') || 
            !$request->session()->has('student_data.passport')) {
            return redirect()->route('admin.students.create.step1');
        }
        
        $countries = Country::orderBy('name')->get();
        $genders = Student::getGenders();
        $maritalStatuses = Student::getMaritalStatuses();
        
        return view('admin.students.create-step3', compact('countries', 'genders', 'maritalStatuses'));
    }

    /**
     * Store the personal information and proceed to step 4.
     */
    public function storeStep3(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:students',
            'phone_number' => 'required|string|max:255',
            'country_of_residence_id' => 'required|exists:countries,id',
            'nationality_id' => 'required|exists:countries,id',
            'gender' => 'required|string|in:' . implode(',', array_keys(Student::getGenders())),
            'marital_status' => 'required|string|in:' . implode(',', array_keys(Student::getMaritalStatuses())),
            'father_name' => 'required|string|max:255',
            'mother_name' => 'required|string|max:255',
            'emergency_email' => 'nullable|string|email|max:255',
            'emergency_phone' => 'nullable|string|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);
        
        // Remove password confirmation as it's not needed in the session
        $personalData = $validated;
        
        // Hash the password before storing in session
        $personalData['password'] = Hash::make($validated['password']);
        
        // Store in session
        $request->session()->put('student_data.personal', $personalData);
        
        return redirect()->route('admin.students.create.step4');
    }

    /**
     * Show the form for creating a new student - Step 4: Education Information.
     */
    public function createStep4(Request $request)
    {
        // Check if previous steps are completed
        if (!$request->session()->has('student_data.basic') || 
            !$request->session()->has('student_data.passport') ||
            !$request->session()->has('student_data.personal')) {
            return redirect()->route('admin.students.create.step1');
        }
        
        $countries = Country::orderBy('name')->get();
        $degrees = Degree::orderBy('name')->get();

        return view('admin.students.create-step4', compact('countries', 'degrees'));
    }

    /**
     * Store the education information and proceed to step 5.
     */
    public function storeStep4(Request $request)
    {
        $validated = $request->validate([
            'applying_degree_id' => 'required|exists:degrees,id',
            'high_school_name' => 'required|string|max:255',
            'high_school_country_id' => 'required|exists:countries,id',
            'gpa' => 'required|string|max:255',
        ]);

        // Store in session
        $request->session()->put('student_data.education', $validated);

        return redirect()->route('admin.students.create.step5');
    }

    /**
     * Show the form for creating a new student - Step 5: Documents Upload.
     */
    public function createStep5(Request $request)
    {
        // Check if previous steps are completed
        if (!$request->session()->has('student_data.basic') || 
            !$request->session()->has('student_data.passport') ||
            !$request->session()->has('student_data.personal') ||
            !$request->session()->has('student_data.education')) {
            return redirect()->route('admin.students.create.step1');
        }
        
        return view('admin.students.create-step5');
    }

    /**
     * Store the documents and create the student.
     */
    public function storeStep5(Request $request)
    {
        $validated = $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'passport' => 'required|file|mimes:jpeg,png,jpg,gif,pdf|max:5120',
            'transcript' => 'required|file|mimes:jpeg,png,jpg,gif,pdf|max:5120',
            'diploma' => 'nullable|file|mimes:jpeg,png,jpg,gif,pdf|max:5120',
            'denklik' => 'nullable|file|mimes:jpeg,png,jpg,gif,pdf|max:5120',
            'certificate' => 'nullable|file|mimes:jpeg,png,jpg,gif,pdf|max:5120',
            'other_documents' => 'nullable|file|mimes:jpeg,png,jpg,gif,pdf|max:5120',
        ]);
    
        // Handle file uploads
        $filePaths = [];
        
        foreach (['photo', 'passport', 'transcript', 'diploma', 'denklik', 'certificate', 'other_documents'] as $document) {
            if ($request->hasFile($document)) {
                $filePaths[$document . '_path'] = $request->file($document)->store('students/' . strtolower($document) . 's', 'public');
            }
        }
    
        // Combine all data
        $studentData = array_merge(
            $request->session()->get('student_data.basic'),
            $request->session()->get('student_data.passport'),
            $request->session()->get('student_data.personal'),
            $request->session()->get('student_data.education'),
            $filePaths,
            [
                'status' => 'New',
                'application_date' => now(),
                'processed_by' => Auth::id(),
            ]
        );
    
        // Create the student
        $student = Student::create($studentData);
    
        // Create notification
        Auth::user()->notifications()->create([
            'type' => 'new_application',
            'data' => [
                'message' => 'New application submitted: ' . $student->first_name . ' ' . $student->last_name,
                'student_id' => $student->id,
                'student_name' => $student->first_name . ' ' . $student->last_name,
                'time' => now()->diffForHumans()
            ],
        ]);
    
        // Clear session data
        $request->session()->forget('student_data');
    
        return redirect()->route('admin.students.show', $student)
            ->with('success', 'Student created successfully.');
    }

    /**
     * Display the specified student.
     */
    public function show(Student $student)
    {
        $student->load([
            'studyCountry', 
            'responsibleEmployee', 
            'countryOfResidence', 
            'nationality', 
            'highSchoolCountry', 
            'applyingDegree',
            'applications'
        
        ]);
        

        $universities = University::all();
        $departments = Department::all();
        $degrees = Degree::all();
        $languages = ['English', 'German', 'French', 'Spanish'];
        $semesters = ['Fall', 'Spring', 'Summer'];
        
        return view('admin.students.show', compact(
            'student',
            'universities',
            'departments',
            'degrees',
            'languages',
            'semesters'
        ));
    
    }
/**
 * Export students data to Excel
 *
 * @param Request $request
 * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
 */


/**
 * Export students data to PDF
 *
 * @param Request $request
 * @return \Illuminate\Http\Response
 */

    /**
     * Show the form for editing the specified student.
     */
    public function edit(Student $student)
    {
        $student->load([
            'studyCountry', 
            'responsibleEmployee', 
            'countryOfResidence', 
            'nationality', 
            'highSchoolCountry', 
            'applyingDegree'
        ]);
        
        $countries = Country::orderBy('name')->get();
        $studycountries = Country::whereIn('name',['Turkey','Cyprus'])->get();

        $employees = User::where('role', '!=', 'Admin')->orderBy('name')->get();
        $degrees = Degree::all();
        $genders = Student::getGenders();
        $maritalStatuses = Student::getMaritalStatuses();
        $statuses = Student::getStatuses();
        
        return view('admin.students.edit', compact(
            'student', 
            'countries', 
            'employees', 
            'studycountries',
            'degrees', 
            'genders', 
            'maritalStatuses',
            'statuses'
        ));
    }

    /**
     * Update the specified student in storage.
     */
    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            // Basic Information
            'academic_year' => 'required|string',
            'study_country_id' => 'required|exists:countries,id',
            'employee_id' => 'required|exists:users,id',
            'is_transfer' => 'boolean',
           
            // Passport Information
            'date_of_birth' => 'required|date',
            'passport_id' => 'required|string|max:255',
            'passport_issue_date' => 'required|date',
            'passport_expiry_date' => 'required|date|after:passport_issue_date',
            'needs_visa_support' => 'boolean',
           
            // Personal Information
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('students')->ignore($student)],
            'phone_number' => 'required|string|max:255',
            'country_of_residence_id' => 'required|exists:countries,id',
            'nationality_id' => 'required|exists:countries,id',
            'gender' => 'required|string|in:' . implode(',', array_keys(Student::getGenders())),
            'marital_status' => 'required|string|in:' . implode(',', array_keys(Student::getMaritalStatuses())),
           
            // Family Information
            'father_name' => 'required|string|max:255',
            'mother_name' => 'required|string|max:255',
            'emergency_email' => 'nullable|string|email|max:255',
            'emergency_phone' => 'nullable|string|max:255',
           
            // Education Information
            'applying_degree_id' => 'required|exists:degrees,id',
            'high_school_name' => 'required|string|max:255',
            'high_school_country_id' => 'required|exists:countries,id',
            'gpa' => 'required|string|max:255',

            // Documents
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'passport' => 'nullable|file|mimes:jpeg,png,jpg,gif,pdf|max:5120',
            'transcript' => 'nullable|file|mimes:jpeg,png,jpg,gif,pdf|max:5120',
            'diploma' => 'nullable|file|mimes:jpeg,png,jpg,gif,pdf|max:5120',
            'denklik' => 'nullable|file|mimes:jpeg,png,jpg,gif,pdf|max:5120',
            'certificate' => 'nullable|file|mimes:jpeg,png,jpg,gif,pdf|max:5120',
            'other_documents' => 'nullable|file|mimes:jpeg,png,jpg,gif,pdf|max:5120',
           
            // Status and notes
            'status' => 'required|string|in:' . implode(',', array_keys(Student::getStatuses())),
            'notes' => 'nullable|string',
        ]);
    
        $validated['is_transfer'] = $request->input('is_transfer', 0);
        $validated['needs_visa_support'] = $request->input('needs_visa_support', 0);
        
        // Handle file uploads
        foreach (['photo', 'passport', 'transcript', 'diploma', 'denklik', 'certificate', 'other_documents'] as $document) {
            if ($request->hasFile($document)) {
                // Delete old file if exists
                if ($student->{$document . '_path'}) {
                    Storage::disk('public')->delete($student->{$document . '_path'});
                }
               
                // Store new file
                $validated[$document . '_path'] = $request->file($document)->store('students/' . strtolower($document) . 's', 'public');
            }
        }
        
        // Additional meta information
        $validated['processed_by'] = Auth::id();
        
        // Format date fields to ensure they match DB expectations
        $validated['date_of_birth'] = date('Y-m-d', strtotime($validated['date_of_birth']));
        $validated['passport_issue_date'] = date('Y-m-d', strtotime($validated['passport_issue_date']));
        $validated['passport_expiry_date'] = date('Y-m-d', strtotime($validated['passport_expiry_date']));
        
        $student->update($validated);
        
        return redirect()->route('admin.students.show', $student)
            ->with('success', 'Student updated successfully.');
    }

    /**
     * Remove the specified student from storage.
     */
    public function destroy(Student $student)
    {
        // Delete all associated files
        foreach (['photo_path', 'passport_path', 'transcript_path', 'diploma_path', 'denklik_path', 'certificate_path', 'other_documents_path'] as $path) {
            if ($student->{$path}) {
                Storage::disk('public')->delete($student->{$path});
            }
        }
        
        $student->delete();

        return redirect()->route('admin.students.index')
            ->with('success', 'Student deleted successfully.');
    }

    /**
     * Change the status of a student.
     */
    public function changeStatus(Request $request, Student $student)
    {
        $validated = $request->validate([
            'status' => 'required|string|in:' . implode(',', array_keys(Student::getStatuses())),
            'notes' => 'nullable|string',
        ]);
        
        $student->update($validated);

        return redirect()->back()
            ->with('success', 'Student status updated successfully.');
    }

    /**
     * Display pending documents students.
     */
    public function pendingDocuments()
    {
        $students = Student::withoutCompleteDocuments()
            ->with(['studyCountry', 'responsibleEmployee', 'applyingDegree'])
            ->latest()
            ->paginate(10);
            
        return view('admin.students.pending-documents', compact('students'));
    }


public function uploadDocument(Request $request, Student $student)
{
    $request->validate([
        'document_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120', // 5MB max
        'document_type' => 'required|string|in:photo_path,passport_path,transcript_path,diploma_path,denklik_path,certificate_path,other_documents_path',
    ]);

    try {
        $documentType = $request->document_type;
        $file = $request->file('document_file');
        $filename = $student->id . '_' . Str::slug(explode('_', $documentType)[0]) . '_' . time() . '.' . $file->getClientOriginalExtension();
        
        // Delete old file if exists
        if ($student->$documentType) {
            $oldPath = str_replace('storage/', '', $student->$documentType);
            if (Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->delete($oldPath);
            }
        }
        
        // Upload new file
        $path = $file->storeAs('students/' . $student->id . '/documents', $filename, 'public');
        
        // Update student record
        $student->update([
            $documentType => 'storage/' . $path
        ]);
        
        return redirect()->route('admin.students.show', $student)
            ->with('success', 'Document uploaded successfully.');
    } catch (\Exception $e) {
        return redirect()->back()
            ->with('error', 'Error uploading document: ' . $e->getMessage());
    }
}

public function deleteDocument(Request $request, Student $student)
{
    $request->validate([
        'document_type' => 'required|string|in:photo_path,passport_path,transcript_path,diploma_path,denklik_path,certificate_path,other_documents_path',
    ]);

    try {
        $documentType = $request->document_type;
        
        // Check if document exists
        if ($student->$documentType) {
            // Delete file from storage
            $path = str_replace('storage/', '', $student->$documentType);
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
            
            // Update student record
            $student->update([
                $documentType => null
            ]);
            
            return redirect()->route('admin.students.show', $student)
                ->with('success', 'Document deleted successfully.');
        }
        
        return redirect()->route('admin.students.show', $student)
            ->with('error', 'Document not found.');
    } catch (\Exception $e) {
        return redirect()->back()
            ->with('error', 'Error deleting document: ' . $e->getMessage());
    }
}

}
