<?php

namespace App\Http\Controllers\Auth;

use App\Models\Degree;
use App\Models\Program;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Nnjeim\World\Models\Country;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class StudentAuthController extends Controller

{
    /**
     * Display the student registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Get all countries for the dropdowns
        $countries = Country::orderBy('name', 'asc')->get();
        
        // Get all available programs
        $programs = Program::all();
        $degrees = Degree::all();
        
        return view('students.register', compact('countries', 'programs','degrees'));
    }
/**
 * Log the student out of the application.
 *
 * @param  \Illuminate\Http\Request  $request
 * @return \Illuminate\Http\RedirectResponse
 */
public function logout(Request $request)
{
    Auth::guard('student')->logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('student.login');
}
    /**
     * Register a new student.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
  /**
 * Register a new student through the public registration portal.
 *
 * @param Request $request
 * @return \Illuminate\Http\RedirectResponse
 */
public function register(Request $request)
{
    // Validate all form steps at once
    $validated = $request->validate([
        // Step 1: Basic Information
        'academic_year' => 'required|string',
        'study_country_id' => 'required|exists:countries,id',
        'is_transfer' => 'boolean',
        
        // Step 2: Passport Information
        'date_of_birth' => 'required|date',
        'passport_id' => 'required|string|max:255',
        'passport_issue_date' => 'required|date',
        'passport_expiry_date' => 'required|date|after:passport_issue_date',
        'needs_visa_support' => 'boolean',
        
        // Step 3: Personal Information
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:students',
        'phone_number' => 'required|string|max:255',
        'country_of_residence_id' => 'required|exists:countries,id',
        'nationality_id' => 'required|exists:countries,id',
        'gender' => 'required|string|in:Male,Female,Other',
        'marital_status' => 'required|string|in:Single,Married,Divorced,Widowed',
        'father_name' => 'required|string|max:255',
        'mother_name' => 'required|string|max:255',
        'emergency_email' => 'nullable|string|email|max:255',
        'emergency_phone' => 'nullable|string|max:255',
        'password' => 'required|string|min:8|confirmed',
        
        // Step 4: Education Information
        'high_school_name' => 'required|string|max:255',
        'high_school_country_id' => 'required|exists:countries,id',
        'gpa' => 'required|string|max:255',
        'applying_degree_id' => 'required|exists:programs,id',
        
        // Step 5: Documents
        'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        'passport' => 'required|file|mimes:jpeg,png,jpg,gif,pdf|max:5120',
        'transcript' => 'required|file|mimes:jpeg,png,jpg,gif,pdf|max:5120',
        'diploma' => 'nullable|file|mimes:jpeg,png,jpg,gif,pdf|max:5120',
        'denklik' => 'nullable|file|mimes:jpeg,png,jpg,gif,pdf|max:5120',
        'certificate' => 'nullable|file|mimes:jpeg,png,jpg,gif,pdf|max:5120',
        'other_documents' => 'nullable|file|mimes:jpeg,png,jpg,gif,pdf|max:5120',
        
        // Terms acceptance
        'terms' => 'required|accepted',
    ]);

    // Handle file uploads
    $filePaths = [];
    
    foreach (['photo', 'passport', 'transcript', 'diploma', 'denklik', 'certificate', 'other_documents'] as $document) {
        if ($request->hasFile($document)) {
            $filePaths[$document . '_path'] = $request->file($document)->store('students/' . strtolower($document) . 's', 'public');
        }
    }

    // Create the student record with all collected data
    $student = Student::create(array_merge(
        $validated,
        $filePaths,
        [
            'password' => Hash::make($validated['password']),
            'status' => 'New',
            'application_date' => now(),
        ]
    ));

    // Create admin notification for new student application
    // Notification::create([
    //     'type' => 'new_application',
    //     'user_id' => 1, // Admin user ID or null
    //     'data' => [
    //         'message' => 'New application submitted: ' . $student->first_name . ' ' . $student->last_name,
    //         'student_id' => $student->id,
    //         'student_name' => $student->first_name . ' ' . $student->last_name,
    //         'time' => now()->diffForHumans()
    //     ],
    // ]);

    // // Send email notification to admissions team
    // try {
    //     Mail::to(config('app.admissions_email'))->send(new NewStudentApplication($student));
    // } catch (\Exception $e) {
    //     // Log email sending error but don't interrupt the process
    //     Log::error('Failed to send admissions email: ' . $e->getMessage());
    // }

    // // Send confirmation email to student
    // try {
    //     Mail::to($student->email)->send(new ApplicationConfirmation($student));
    // } catch (\Exception $e) {
    //     // Log email sending error but don't interrupt the process
    //     Log::error('Failed to send confirmation email: ' . $e->getMessage());
    // }

    // Attempt to login the student immediately after registration
    Auth::guard('student')->login($student);

    // Store the registered email in session for the success page
    session(['registered_email' => $validated['email']]);

    // Redirect to success page with a success message
    return redirect()
        ->route('student.login.view')
        ->with('success', 'Your application has been submitted successfully! We will contact you soon.');
}
    
    /**
     * Display the registration success page.
     *
     * @return \Illuminate\Http\Response
     */
    public function registrationSuccess()
    {
        return view('students.registration-success');
    }
}