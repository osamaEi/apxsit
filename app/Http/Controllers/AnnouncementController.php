<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\University;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Nnjeim\World\Models\Country;
use Nnjeim\World\Models\City;
use App\Exports\AnnouncementsExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\PDF; 



class AnnouncementController extends Controller
{
    /**
     * Display a listing of announcements.
     */
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $universityFilter = $request->input('university_id');
        $countryFilter = $request->input('country_id');
        $statusFilter = $request->input('status');

        $query = Announcement::with(['university', 'country', 'city', 'creator']);

        // Apply search filter
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Apply university filter
        if ($universityFilter) {
            $query->where('university_id', $universityFilter);
        }

        // Apply country filter
        if ($countryFilter) {
            $query->where('country_id', $countryFilter);
        }

        // Apply status filter
        if ($statusFilter === 'active') {
            $query->where('is_active', true);
        } elseif ($statusFilter === 'inactive') {
            $query->where('is_active', false);
        } elseif ($statusFilter === 'published') {
            $query->where('is_active', true)
                  ->whereNotNull('published_at')
                  ->where('published_at', '<=', now());
        } elseif ($statusFilter === 'draft') {
            $query->whereNull('published_at');
        } elseif ($statusFilter === 'scheduled') {
            $query->where('is_active', true)
                  ->whereNotNull('published_at')
                  ->where('published_at', '>', now());
        }

        $announcements = $query->latest()->paginate(10);
        $universities = University::orderBy('name')->get();
        $countries = Country::orderBy('name')->get();

        return view('admin.announcements.index', compact(
            'announcements', 
            'universities', 
            'countries', 
            'search', 
            'universityFilter', 
            'countryFilter', 
            'statusFilter'
        ));
    }

    /**
     * Show the form for creating a new announcement.
     */
    public function create()
    {
        $universities = University::where('is_active', true)->orderBy('name')->get();
        $countries = Country::whereIn('name',['Turkey','Cyprus'])->get();
        $cities = collect([]);  

        return view('admin.announcements.create', compact('universities', 'countries', 'cities'));
    }

    /**
     * Store a newly created announcement in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'university_id' => 'required|exists:universities,id',
            'country_id' => 'required|exists:countries,id',
            'city_id' => 'required|exists:cities,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
            'publish_now' => 'boolean',
            'published_at' => 'nullable|date',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('announcements', 'public');
            $validated['image'] = $imagePath;
        }

        // Set the published_at date
        if ($request->input('publish_now', false)) {
            $validated['published_at'] = now();
        }

        // Remove publish_now from validated data
        unset($validated['publish_now']);

        // Add created_by
        $validated['created_by'] = Auth::id();

        Announcement::create($validated);

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Announcement created successfully.');
    }

    /**
     * Display the specified announcement.
     */
    public function show(Announcement $announcement)
    {
        $announcement->load(['university', 'country', 'city', 'creator']);
        
        return view('admin.announcements.show', compact('announcement'));
    }

    /**
     * Show the form for editing the specified announcement.
     */
    public function edit(Announcement $announcement)
    {
        $universities = University::where('is_active', true)->orderBy('name')->get();
        $countries = Country::orderBy('name')->get();
        
        // Get cities for the selected country
        $cities = City::where('country_id', $announcement->country_id)
                     ->orderBy('name')
                     ->get();

        return view('admin.announcements.edit', compact('announcement', 'universities', 'countries', 'cities'));
    }

    /**
     * Update the specified announcement in storage.
     */
    public function update(Request $request, Announcement $announcement)
    {
        $validated = $request->validate([
            'university_id' => 'required|exists:universities,id',
            'country_id' => 'required|exists:countries,id',
            'city_id' => 'required|exists:world_cities,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
            'publish_now' => 'boolean',
            'published_at' => 'nullable|date',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($announcement->image) {
                Storage::disk('public')->delete($announcement->image);
            }
            
            $imagePath = $request->file('image')->store('announcements', 'public');
            $validated['image'] = $imagePath;
        }

        // Set the published_at date
        if ($request->input('publish_now', false)) {
            $validated['published_at'] = now();
        }

        // Remove publish_now from validated data
        unset($validated['publish_now']);

        $announcement->update($validated);

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Announcement updated successfully.');
    }

    /**
     * Remove the specified announcement from storage.
     */
    public function destroy(Announcement $announcement)
    {
        // Delete image if exists
        if ($announcement->image) {
            Storage::disk('public')->delete($announcement->image);
        }
        
        $announcement->delete();

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Announcement deleted successfully.');
    }

    /**
     * Get cities for a specific country (AJAX request).
     */
    public function getCities($countryId)
    {
        $cities = City::where('country_id', $countryId)->orderBy('name')->get();
        return response()->json($cities);
    }
    /**
     * Toggle announcement active status.
     */
    public function toggleStatus(Announcement $announcement)
    {
        $announcement->is_active = !$announcement->is_active;
        $announcement->save();
        
        return redirect()->back()
            ->with('success', 'Announcement status updated successfully.');
    }

    /**
     * Publish the announcement immediately.
     */
    public function publish(Announcement $announcement)
    {
        $announcement->published_at = now();
        $announcement->is_active = true;
        $announcement->save();
        
        return redirect()->back()
            ->with('success', 'Announcement published successfully.');
    }

    /**
     * Unpublish the announcement (revert to draft).
     */
    public function unpublish(Announcement $announcement)
    {
        $announcement->published_at = null;
        $announcement->save();
        
        return redirect()->back()
            ->with('success', 'Announcement unpublished successfully.');
    }

    /**
 * Export announcements data to Excel
 *
 * @param Request $request
 * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
 */
public function exportExcel(Request $request)
{
    // Get filter parameters
    $search = $request->input('search');
    $universityFilter = $request->input('university_id');
    $countryFilter = $request->input('country_id');
    $statusFilter = $request->input('status');
    
    // Build the query with filters
    $query = Announcement::with(['university', 'country', 'city'])
        ->when($search, function ($q) use ($search) {
            return $q->where(function($query) use ($search) {
                $query->where('title', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
            });
        })
        ->when($universityFilter, function ($q) use ($universityFilter) {
            return $q->where('university_id', $universityFilter);
        })
        ->when($countryFilter, function ($q) use ($countryFilter) {
            return $q->where('country_id', $countryFilter);
        })
        ->when($statusFilter, function ($q) use ($statusFilter) {
            return $q->where('status', $statusFilter);
        });
    
    // Get announcements based on current filters
    $announcements = $query->get();
    
    // Create Excel file
    $filename = 'announcements_' . date('Y-m-d_H-i-s') . '.xlsx';
    
    return Excel::download(new AnnouncementsExport($announcements), $filename);
}

/**
 * Export announcements data to PDF
 *
 * @param Request $request
 * @return \Illuminate\Http\Response
 */
public function exportPdf(Request $request)
{
    // Get filter parameters
    $search = $request->input('search', '');
    $universityFilter = $request->input('university_id');
    $countryFilter = $request->input('country_id');
    $cityFilter = $request->input('city_id');
    $statusFilter = $request->input('status');
    $dateFrom = $request->input('date_from');
    $dateTo = $request->input('date_to');
    
    // Build the query with filters
    $query = Announcement::with(['university', 'country', 'city', 'creator'])
        ->when($search, function ($q) use ($search) {
            return $q->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        })
        ->when($universityFilter, function ($q) use ($universityFilter) {
            return $q->where('university_id', $universityFilter);
        })
        ->when($countryFilter, function ($q) use ($countryFilter) {
            return $q->where('country_id', $countryFilter);
        })
        ->when($cityFilter, function ($q) use ($cityFilter) {
            return $q->where('city_id', $cityFilter);
        })
        ->when($statusFilter, function ($q) use ($statusFilter) {
            if ($statusFilter === 'active') {
                return $q->where('is_active', true);
            } elseif ($statusFilter === 'inactive') {
                return $q->where('is_active', false);
            } elseif ($statusFilter === 'published') {
                return $q->where('is_active', true)
                      ->whereNotNull('published_at')
                      ->where('published_at', '<=', now());
            } elseif ($statusFilter === 'draft') {
                return $q->whereNull('published_at');
            } elseif ($statusFilter === 'scheduled') {
                return $q->where('is_active', true)
                      ->whereNotNull('published_at')
                      ->where('published_at', '>', now());
            }
        })
        ->when($dateFrom, function ($q) use ($dateFrom) {
            return $q->whereDate('published_at', '>=', $dateFrom);
        })
        ->when($dateTo, function ($q) use ($dateTo) {
            return $q->whereDate('published_at', '<=', $dateTo);
        });
    
    // Get announcements based on current filters
    $announcements = $query->latest()->get();
    
    // Process images to base64 for reliable PDF rendering
    foreach ($announcements as $announcement) {
        // Process announcement image
        if ($announcement->image) {
            $imagePath = str_replace('storage/', '', $announcement->image);
            $path = storage_path('app/public/' . $imagePath);
            
            if (file_exists($path)) {
                $type = pathinfo($path, PATHINFO_EXTENSION);
                $data = file_get_contents($path);
                $announcement->image_base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
            } else {
                $announcement->image_base64 = null;
            }
        } else {
            $announcement->image_base64 = null;
        }
        
        // Process university logo if exists
        if (isset($announcement->university) && $announcement->university->logo) {
            $logoPath = str_replace('storage/', '', $announcement->university->logo);
            $path = storage_path('app/public/' . $logoPath);
            
            if (file_exists($path)) {
                $type = pathinfo($path, PATHINFO_EXTENSION);
                $data = file_get_contents($path);
                $announcement->university->logo_base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
            } else {
                $announcement->university->logo_base64 = null;
            }
        } elseif (isset($announcement->university)) {
            $announcement->university->logo_base64 = null;
        }
    }
    
    // Get information for header/filters
    $universities = University::all();
    $universityName = $universityFilter ? $universities->where('id', $universityFilter)->first()->name : 'All';
    
    $countries = Country::all();
    $countryName = $countryFilter ? $countries->where('id', $countryFilter)->first()->name : 'All';
    
    // Determine status name
    $statusNames = [
        'active' => 'Active',
        'inactive' => 'Inactive',
        'published' => 'Published',
        'draft' => 'Draft',
        'scheduled' => 'Scheduled'
    ];
    $statusName = $statusFilter ? ($statusNames[$statusFilter] ?? $statusFilter) : 'All';
    
    // Get the company logo
    $companyLogoPath = public_path('logo.jpg');
    $companyLogo = '';
    if (file_exists($companyLogoPath)) {
        $type = pathinfo($companyLogoPath, PATHINFO_EXTENSION);
        $data = file_get_contents($companyLogoPath);
        $companyLogo = 'data:image/' . $type . ';base64,' . base64_encode($data);
    }
    
    // Count statistics for PDF header
    $publishedCount = $announcements->filter(function($item) {
        return $item->status_label === 'Published';
    })->count();
    
    $draftCount = $announcements->filter(function($item) {
        return $item->status_label === 'Draft';
    })->count();
    
    $inactiveCount = $announcements->filter(function($item) {
        return $item->is_active === false;
    })->count();
    
    // Create PDF
    $pdf = app('dompdf.wrapper');
    $pdf->getDomPDF()->set_option('isHtml5ParserEnabled', true);
    $pdf->getDomPDF()->set_option('isRemoteEnabled', true);
    $pdf->getDomPDF()->set_option('enable_php', true);
    
    $pdf->loadView('admin.announcements.pdf', [
        'announcements' => $announcements,
        'search' => $search,
        'universityName' => $universityName,
        'countryName' => $countryName,
        'statusName' => $statusName,
        'companyLogo' => $companyLogo,
        'publishedCount' => $publishedCount,
        'draftCount' => $draftCount,
        'inactiveCount' => $inactiveCount,
        'date' => now()->format('F d, Y, h:i A')
    ]);
    
    // Use portrait orientation for detailed view
    $pdf->setPaper('a4', 'portrait');
    
    return $pdf->download('announcements_report_' . date('Y-m-d_H-i-s') . '.pdf');
}




}