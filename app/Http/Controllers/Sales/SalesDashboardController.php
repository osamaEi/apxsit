<?php

namespace App\Http\Controllers\Sales;

use App\Models\User;
use App\Models\Program;
use App\Models\Student;
use App\Models\University;
use App\Models\Application;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Nnjeim\World\Models\City;
use Nnjeim\World\Models\Country;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class SalesDashboardController extends Controller
{
    public function index()
    {
        // User statistics
        $userStats = [
            'totalUsers' => User::count(),
            'totalAdmins' => User::where('role', 'Admin')->count(),
            'totalSales' => User::where('role', 'Sales')->count(),
            'totalRegisters' => User::where('role', 'Register')->count(),
            'totalEmployees' => User::where('role', 'Employee')->count(),
        ];
        
        // University statistics
        $universityStats = [
            'totalUniversities' => University::count(),
            'activeUniversities' => University::where('is_active', true)->count(),
            'inactiveUniversities' => University::where('is_active', false)->count(),
        ];
        
        // Program statistics
        $programStats = [
            'totalPrograms' => Program::count(),
            'activePrograms' => Program::where('status', 'Active')->count(),
            'inactivePrograms' => Program::where('status', 'Inactive')->count(),
            'comingSoonPrograms' => Program::where('status', 'Coming Soon')->count(),
            'fullPrograms' => Program::where('status', 'Full')->count(),
            'bachelorPrograms' => Program::where('degree', 'Bachelor')->count(),
            'masterPrograms' => Program::where('degree', 'Master')->count(),
            'phdPrograms' => Program::where('degree', 'PhD')->count(),
        ];
        
        // Calculate average discount percentage
        $avgDiscountPercentage = Program::select(
            DB::raw('AVG((before_discount - after_discount) / before_discount * 100) as avg_discount')
        )->first()->avg_discount ?? 0;
        
        $programStats['averageDiscount'] = round($avgDiscountPercentage, 2);
        
        // Announcement statistics 
        $announcementStats = [
            'totalAnnouncements' => Announcement::count(),
            'activeAnnouncements' => Announcement::where('is_active', true)->count(),
            'inactiveAnnouncements' => Announcement::where('is_active', false)->count(),
            'publishedAnnouncements' => Announcement::where('is_active', true)
                ->whereNotNull('published_at')
                ->where('published_at', '<=', now())
                ->count(),
            'scheduledAnnouncements' => Announcement::where('is_active', true)
                ->whereNotNull('published_at')
                ->where('published_at', '>', now())
                ->count(),
            'draftAnnouncements' => Announcement::whereNull('published_at')->count(),
        ];
        
        // World data statistics
        $worldStats = [
            'totalCountries' => Country::count(),
            'totalCities' => City::count(),
        ];
        
        // Get top 5 universities with most programs
        $topUniversities = University::withCount('programs')
            ->orderByDesc('programs_count')
            ->take(5)
            ->get();
        
        // Get recent announcements
        $recentAnnouncements = Announcement::with(['university', 'creator'])
            ->latest()
            ->take(5)
            ->get();
        
        // Get latest registered users
        $recentUsers = User::latest()
            ->take(5)
            ->get();
            $students =Student::latest()->where('processed_by',auth()->user()->id)->get();

            $studentCount = Student::where('processed_by',auth()->user()->id)->count();
            $applicationCount = Application::where('created_by',auth()->user()->id)->count();
            $applicationCompleted = Application::where('created_by',auth()->user()->id)->where('status','Completed')->count();
            $studentStats = [
                'totalStudents' => $studentCount,
                'newApplications' => Application::where('created_by',auth()->user()->id)->where('status', 'New')->count(),
                'inReviewApplications' => Application::where('created_by',auth()->user()->id)->where('status', 'In Review')->count(),
                'pendingDocuments' => Application::where('created_by',auth()->user()->id)->where('status', 'Pending Documents')->count(),
                'acceptedApplications' => Application::where('created_by',auth()->user()->id)->where('status', 'Accepted')->count(),
                'rejectedApplications' => Application::where('created_by',auth()->user()->id)->where('status', 'Rejected')->count(),
            ];
        
            return view('admin.dashboard', compact(
                'userStats',
                'students',
                'universityStats',
                'programStats',
                'announcementStats',
                'worldStats',
                'topUniversities',
                'recentAnnouncements',
                'recentUsers',
                'studentCount',
                'applicationCompleted',
                'applicationCount',
                'studentStats' // Add this new variable
            ));
}
}
