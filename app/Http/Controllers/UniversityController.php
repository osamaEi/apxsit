<?php

namespace App\Http\Controllers;



use Barryvdh\DomPDF\Facade\PDF; // Note the "Facade" namespace

use App\Models\University;
use Illuminate\Http\Request;
use Nnjeim\World\Models\City;
use Nnjeim\World\Models\Country;
use App\Exports\UniversitiesExport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class UniversityController extends Controller
{
    /**
     * Display a listing of the universities.
     */
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $countryFilter = $request->input('country_id');
        $typeFilter = $request->input('type');
        $statusFilter = $request->input('is_active');

        $query = University::with(['country', 'city']);

        // Apply search filter
        if ($search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
        }

        // Apply country filter
        if ($countryFilter) {
            $query->where('country_id', $countryFilter);
        }

        // Apply type filter
        if ($typeFilter) {
            $query->where('type', $typeFilter);
        }

        // Apply status filter
        if ($statusFilter !== null) {
            $query->where('is_active', $statusFilter == '1');
        }

        $universities = $query->orderBy('name')->paginate(10);
        $countries = Country::orderBy('name')->get();
        $types = University::getTypes();

        return view('admin.universities.index', compact(
            'universities', 
            'countries', 
            'types', 
            'search', 
            'countryFilter', 
            'typeFilter', 
            'statusFilter'
        ));
    }

 /**
 * Export universities data to Excel
 *
 * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
 */

    public function exportExcel(Request $request)
    {
        // Pass the request to the export class
        $filename = 'universities_' . date('Y-m-d_H-i-s') . '.xlsx';
        
        // Get filter parameters - use the actual request, not a new one
        $search = $request->input('search');
        $countryFilter = $request->input('country_id');
        $typeFilter = $request->input('type');
        $statusFilter = $request->input('is_active');
        
        // Build query with filters
        $query = University::with(['country', 'city']);
        
        // Apply search filter
        if ($search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
        }
        
        // Apply country filter
        if ($countryFilter) {
            $query->where('country_id', $countryFilter);
        }
        
        // Apply type filter
        if ($typeFilter) {
            $query->where('type', $typeFilter);
        }
        
        // Apply status filter - fixed to check for null instead of empty string
        if ($statusFilter !== null && $statusFilter !== '') {
            $query->where('is_active', $statusFilter == '1');
        }
        
        // Get universities based on current filters
        $universities = $query->get();
        
        // Get type names mapping
        $types = University::getTypes();
        
        // Create an instance of the export class with filtered data
        return Excel::download(new UniversitiesExport($universities, $types), $filename);
    }

    /**
     * Export universities data to PDF
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function exportPdf(Request $request)
    {
        // Get filter parameters - use the actual request, not a new one
        $search = $request->input('search');
        $countryFilter = $request->input('country_id');
        $typeFilter = $request->input('type');
        $statusFilter = $request->input('is_active');
        
        // Build query with filters
        $query = University::with(['country', 'city']);
        
        // Apply search filter
        if ($search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
        }
        
        // Apply country filter
        if ($countryFilter) {
            $query->where('country_id', $countryFilter);
        }
        
        // Apply type filter
        if ($typeFilter) {
            $query->where('type', $typeFilter);
        }
        
        // Apply status filter - fixed to check for null instead of empty string
        if ($statusFilter !== null && $statusFilter !== '') {
            $query->where('is_active', $statusFilter == '1');
        }
        
        // Get universities based on current filters
        $universities = $query->get();
        
        // Process logo images to base64 for reliable PDF rendering
        foreach ($universities as $university) {
            if ($university->logo) {
                // Get the physical path to the file
                // Remove "storage/" prefix if it exists
                $logoPath = str_replace('storage/', '', $university->logo);
                
                // Build the full path to the storage file
                $path = storage_path('app/public/' . $logoPath);
                
                if (file_exists($path)) {
                    $type = pathinfo($path, PATHINFO_EXTENSION);
                    $data = file_get_contents($path);
                    $university->logo_base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                } else {
                    $university->logo_base64 = null;
                }
            } else {
                $university->logo_base64 = null;
            }
        }
        
        // Get type names mapping
        $types = [
            'public' => 'Public',
            'private' => 'Private',
            'community' => 'Community',
            // Add other types as needed
        ];
        
        // Get current filter information for header
        $countries = Country::all();
        $countryName = $countryFilter ? $countries->where('id', $countryFilter)->first()->name ?? 'All' : 'All';
        $typeName = $typeFilter ? $types[$typeFilter] ?? 'All' : 'All';
        $statusName = $statusFilter !== null ? ($statusFilter == '1' ? 'Active' : 'Inactive') : 'All';
        
        // Create PDF with image options enabled
        $pdf = app('dompdf.wrapper');
        $pdf->getDomPDF()->set_option('isHtml5ParserEnabled', true);
        $pdf->getDomPDF()->set_option('isRemoteEnabled', true);

        $logoPath = public_path('logo2');
        $logoData = null;

        if (file_exists($logoPath)) {
            $type = pathinfo($logoPath, PATHINFO_EXTENSION);
            $data = file_get_contents($logoPath);
            $logoData = 'data:image/' . $type . ';base64,' . base64_encode($data);
        }
        
        $pdf->loadView('admin.universities.pdf', [
            'universities' => $universities,
            'logoData' => $logoData,
            'types' => $types,
            'search' => $search,
            'countryName' => $countryName,
            'typeName' => $typeName,
            'statusName' => $statusName,
            'date' => now()->format('F d, Y')
        ]);
        
        return $pdf->download('universities_' . date('Y-m-d_H-i-s') . '.pdf');
    }

    public function create()
    {
        $countries = Country::orderBy('name')->get();
        $types = University::getTypes();
        $cities = collect([]);  // Empty collection initially

        return view('admin.universities.create', compact('countries', 'types', 'cities'));
    }

    /**
     * Store a newly created university in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'country_id' => 'required|exists:countries,id',
            'city_id' => 'required|exists:cities,id',
            'address' => 'nullable|string',
            'description' => 'nullable|string',
            'type' => 'nullable|string',
            'is_active' => 'boolean',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('universities', 'public');
            $validated['logo'] = $logoPath;
        }

        University::create($validated);

        return redirect()->route('admin.universities.index')
            ->with('success', 'University created successfully.');
    }

    /**
     * Display the specified university.
     */
    public function show(University $university)
    {
        $university->load(['country', 'city']);
        
        return view('admin.universities.show', compact('university'));
    }

    /**
     * Show the form for editing the specified university.
     */
    public function edit(University $university)
    {
        $countries = Country::orderBy('name')->get();
        $types = University::getTypes();
        
        // Get cities for the selected country
        $cities = City::where('country_id', $university->country_id)
                      ->orderBy('name')
                      ->get();

        return view('admin.universities.edit', compact('university', 'countries', 'types', 'cities'));
    }

    /**
     * Update the specified university in storage.
     */
    public function update(Request $request, University $university)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'country_id' => 'required|exists:countries,id',
            'city_id' => 'required|exists:cities,id',
            'address' => 'nullable|string',
            'description' => 'nullable|string',
            'type' => 'nullable|string',
            'is_active' => 'boolean',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($university->logo) {
                Storage::disk('public')->delete($university->logo);
            }
            
            $logoPath = $request->file('logo')->store('universities', 'public');
            $validated['logo'] = $logoPath;
        }

        $university->update($validated);

        return redirect()->route('admin.universities.index')
            ->with('success', 'University updated successfully.');
    }

    /**
     * Remove the specified university from storage.
     */
    public function destroy(University $university)
    {
        // Delete logo if exists
        if ($university->logo) {
            Storage::disk('public')->delete($university->logo);
        }
        
        $university->delete();

        return redirect()->route('admin.universities.index')
            ->with('success', 'University deleted successfully.');
    }

    /**
     * Get cities for a specific country (AJAX request).
     */
    public function getCities(Request $request)
    {
        $countryId = $request->input('country_id');
        
        // Log the request for debugging
        \Log::info('City request received for country ID: ' . $countryId);
        
        if (!$countryId) {
            \Log::warning('No country ID provided');
            return response()->json([]);
        }
        
        try {
            // Get cities from the database
            $cities = \Nnjeim\World\Models\City::where('country_id', $countryId)
                          ->orderBy('name')
                          ->get(['id', 'name']);
            
            // Log the result count for debugging
            \Log::info('Found ' . $cities->count() . ' cities for country ID: ' . $countryId);
            
            return response()->json($cities);
        } catch (\Exception $e) {
            \Log::error('Error fetching cities: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    /**
     * Toggle university active status.
     */
    public function toggleStatus(University $university)
    {
        $university->is_active = !$university->is_active;
        $university->save();
        
        return redirect()->back()
            ->with('success', 'University status updated successfully.');
    }
}