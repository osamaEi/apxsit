<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\University;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class StudentDashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth.student');
    }

    /**
     * Display the student dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $student = Auth::guard('student')->user();
        
        // Load all necessary relationships
        $student->load([
            'studyCountry',
            'responsibleEmployee',
            'countryOfResidence',
            'nationality',
            'highSchoolCountry',
            'applyingDegree',
            'applications.university'
        ]);
        
        $universities = University::all();
        $departments = ['Computer Science', 'Engineering', 'Business', 'Medicine', 'Law', 'Arts']; // Replace with your actual departments
        $degrees = ['Bachelor', 'Master', 'PhD'];
        $languages = ['English', 'German', 'French', 'Spanish'];
        $semesters = ['Fall', 'Spring', 'Summer'];
        
        return view('students.dashboard', compact(
            'student',
            'universities',
            'departments',
            'degrees',
            'languages',
            'semesters'
        ));
    }
    
    /**
     * Display the student's profile page
     *
     * @return \Illuminate\Http\Response
     */
    public function profile()
    {
        $student = Auth::guard('student')->user();
        $student->load([
            'studyCountry',
            'countryOfResidence',
            'nationality',
            'highSchoolCountry',
            'applyingDegree'
        ]);
        
        return view('students.profile', compact('student'));
    }
    
    /**
     * Display student's documents
     *
     * @return \Illuminate\Http\Response
     */
    public function documents()
    {
        $student = Auth::guard('student')->user();
        return view('students.documents', compact('student'));
    }
    
    /**
     * Display student's applications
     *
     * @return \Illuminate\Http\Response
     */
    public function applications()
    {
        $student = Auth::guard('student')->user();
        $student->load('applications.university');
        
        $universities = University::all();
        $departments = ['Computer Science', 'Engineering', 'Business', 'Medicine', 'Law', 'Arts'];
        $degrees = ['Bachelor', 'Master', 'PhD'];
        $languages = ['English', 'German', 'French', 'Spanish'];
        $semesters = ['Fall', 'Spring', 'Summer'];
        
        return view('students.applications', compact(
            'student',
            'universities',
            'departments',
            'degrees',
            'languages',
            'semesters'
        ));
    }
    
    /**
     * Display timeline of student's application process
     * 
     * @return \Illuminate\Http\Response
     */
    public function timeline()
    {
        $student = Auth::guard('student')->user();
        return view('students.timeline', compact('student'));
    }

    /**
     * Update student profile information
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateProfile(Request $request)
    {
        $student = Auth::guard('student')->user();
        
        $validated = $request->validate([
            'phone_number' => 'required|string|max:20',
            'email' => 'required|email|unique:students,email,' . $student->id,
            'emergency_email' => 'nullable|email',
            'emergency_phone' => 'nullable|string|max:20',
        ]);
        
        $student->update($validated);
        
        return redirect()->back()->with('success', 'Profile updated successfully');
    }
    
    /**
     * Upload student documents
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function uploadDocuments(Request $request)
    {
        $student = Auth::guard('student')->user();
        
        $request->validate([
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'passport' => 'nullable|mimes:jpeg,png,jpg,pdf|max:2048',
            'transcript' => 'nullable|mimes:jpeg,png,jpg,pdf|max:2048',
            'diploma' => 'nullable|mimes:jpeg,png,jpg,pdf|max:2048',
            'denklik' => 'nullable|mimes:jpeg,png,jpg,pdf|max:2048',
            'certificate' => 'nullable|mimes:jpeg,png,jpg,pdf|max:2048',
            'other_documents' => 'nullable|mimes:jpeg,png,jpg,pdf,zip|max:5120',
        ]);
        
        // Handle file uploads
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('students/photos', 'public');
            $student->photo_path = $path;
        }
        
        if ($request->hasFile('passport')) {
            $path = $request->file('passport')->store('students/passports', 'public');
            $student->passport_path = $path;
        }
        
        if ($request->hasFile('transcript')) {
            $path = $request->file('transcript')->store('students/transcripts', 'public');
            $student->transcript_path = $path;
        }
        
        if ($request->hasFile('diploma')) {
            $path = $request->file('diploma')->store('students/diplomas', 'public');
            $student->diploma_path = $path;
        }
        
        if ($request->hasFile('denklik')) {
            $path = $request->file('denklik')->store('students/denkliks', 'public');
            $student->denklik_path = $path;
        }
        
        if ($request->hasFile('certificate')) {
            $path = $request->file('certificate')->store('students/certificates', 'public');
            $student->certificate_path = $path;
        }
        
        if ($request->hasFile('other_documents')) {
            $path = $request->file('other_documents')->store('students/other_documents', 'public');
            $student->other_documents_path = $path;
        }
        
        $student->save();
        
        return redirect()->back()->with('success', 'Documents uploaded successfully');
    }
    
    /**
     * Store a new application for the student
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeApplication(Request $request)
    {
        $student = Auth::guard('student')->user();
        
        $validated = $request->validate([
            'university_id' => 'required|exists:universities,id',
            'department' => 'required|string',
            'degree' => 'required|string',
            'semester' => 'required|string',
            'language' => 'required|string',
            'notes' => 'nullable|string',
        ]);
        
        $application = new Application();
        $application->student_id = $student->id;
        $application->university_id = $validated['university_id'];
        $application->department = $validated['department'];
        $application->degree = $validated['degree'];
        $application->semester = $validated['semester'];
        $application->language = $validated['language'];
        $application->notes = $validated['notes'];
        $application->status = 'Pending Review';
        $application->save();
        
        return redirect()->back()->with('success', 'Application submitted successfully');
    }
    
    /**
     * Show a specific application
     * 
     * @param  \App\Models\Application  $application
     * @return \Illuminate\Http\Response
     */
    public function showApplication(Application $application)
    {
        $student = Auth::guard('student')->user();
        
        // Ensure the application belongs to the authenticated student
        if ($application->student_id !== $student->id) {
            abort(403, 'Unauthorized action.');
        }
        
        $application->load('university');
        
        return view('students.application-detail', compact('application'));
    }
    
    /**
     * Display student notifications
     * 
     * @return \Illuminate\Http\Response
     */
    public function notifications()
    {
        $student = Auth::guard('student')->user();
        $notifications = $student->notifications;
        
        return view('students.notifications', compact('notifications'));
    }
    
    /**
     * Mark notifications as read
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function markNotificationsAsRead(Request $request)
    {
        $student = Auth::guard('student')->user();
        $student->unreadNotifications->markAsRead();
        
        return redirect()->back()->with('success', 'Notifications marked as read');
    }
}