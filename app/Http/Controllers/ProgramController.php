<?php

namespace App\Http\Controllers;


use App\Models\Degree;
use App\Models\Program;
use App\Models\Department;
use App\Models\University;
use Illuminate\Http\Request;
use App\Exports\ProgramsExport;
use App\Imports\ProgramsImport;
use Barryvdh\DomPDF\Facade\PDF; 
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class ProgramController extends Controller
{
    /**
     * Display a listing of the programs.
     */
    public function index(Request $request)
    {
        // Get all filter parameters
        $search = $request->input('search', '');
        $universityFilter = $request->input('university_id', []);
        $departmentFilter = $request->input('department', []);
        $degreeFilter = $request->input('degree', []);
        $statusFilter = $request->input('status');
        $languageFilter = $request->input('language');
        $shiftTypeFilter = $request->input('shift_type');
        $minPrice = $request->input('min_price');
        $maxPrice = $request->input('max_price');
        $minDiscount = $request->input('min_discount');
        $maxDiscount = $request->input('max_discount');
        
        $query = Program::with('university');
        
        // Apply search filter
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('department', 'like', "%{$search}%")
                  ->orWhereHas('university', function ($uniQuery) use ($search) {
                      $uniQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }
        
        // Apply university filter (now handles multiple selections)
        if (!empty($universityFilter)) {
            $query->whereIn('university_id', $universityFilter);
        }
        
        // Apply department filter (now handles multiple selections)
        if (!empty($departmentFilter)) {
            $query->whereIn('department', $departmentFilter);
        }
        
        // Apply degree filter (now handles multiple selections)
        if (!empty($degreeFilter)) {
            $query->whereIn('degree', $degreeFilter);
        }
        
        // Apply status filter
        if ($statusFilter) {
            $query->where('status', $statusFilter);
        }
        
        // Apply language filter
        if ($languageFilter) {
            $query->where('language', $languageFilter);
        }
        
        // Apply shift type filter
        if ($shiftTypeFilter) {
            $query->where('shift_type', $shiftTypeFilter);
        }
        
        // Apply price range filters
        if ($minPrice !== null) {
            $query->where('after_discount', '>=', $minPrice);
        }
        
        if ($maxPrice !== null) {
            $query->where('after_discount', '<=', $maxPrice);
        }
        
        // Calculate and filter by discount percentage
        if ($minDiscount !== null || $maxDiscount !== null) {
            // We need to use a raw query to filter by the calculated discount percentage
            $query->addSelect(DB::raw('*, ROUND(((before_discount - after_discount) / before_discount * 100), 2) as discount_percentage'));
            
            if ($minDiscount !== null) {
                $query->having('discount_percentage', '>=', $minDiscount);
            }
            
            if ($maxDiscount !== null) {
                $query->having('discount_percentage', '<=', $maxDiscount);
            }
        } else {
            // Always add the discount_percentage calculation
            $query->addSelect(DB::raw('*, ROUND(((before_discount - after_discount) / before_discount * 100), 2) as discount_percentage'));
        }
        
        // Get paginated results
        $programs = $query->latest()->paginate(10)->withQueryString();
        
        // Get data for filter dropdowns
        $universities = University::where('is_active', true)->orderBy('name')->get();
        $departments = Program::select('department')->distinct()->orderBy('department')->pluck('department');
        $degrees = Degree::all();
        $statuses = Program::getStatuses();
        
        // Calculate statistics for dashboard
        $totalPrograms = Program::count();
        $activePrograms = Program::where('status', 'Active')->count();
        $totalUniversities = University::where('is_active', true)->count();
        $totalDepartments = Program::select('department')->distinct()->count();
        
        return view('admin.programs.index', compact(
            'programs',
            'universities',
            'departments',
            'degrees',
            'statuses',
            'search',
            'universityFilter',
            'departmentFilter',
            'degreeFilter',
            'statusFilter',
            'languageFilter',
            'shiftTypeFilter',
            'minPrice',
            'maxPrice',
            'minDiscount',
            'maxDiscount',
            'totalPrograms',
            'activePrograms',
            'totalUniversities',
            'totalDepartments'
        ));
    }

    /**
     * Show the form for creating a new program.
     */
    public function create()
    {
        $universities = University::where('is_active', true)->orderBy('name')->get();
        $degrees = Degree::all();
        $languages = Program::getLanguages();
        $shiftTypes = Program::getShiftTypes();
        $statuses = Program::getStatuses();
        $departments = Department::all();

        return view('admin.programs.create', compact('universities', 'departments','degrees', 'languages', 'shiftTypes', 'statuses'));
    }

    /**
     * Store a newly created program in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'university_id' => 'required|exists:universities,id',
            'department' => 'required|string|max:255',
            'degree' => 'required|string|max:255',
            'language' => 'required|string|max:255',
            'before_discount' => 'required|numeric|min:0',
            'after_discount' => 'required|numeric|min:0|lte:before_discount',
            'cash_discount' => 'nullable|numeric|min:0',
            'deposit_payment' => 'nullable|numeric|min:0',
            'shift_type' => 'required|string|max:255',
            'siblings_discount' => 'nullable|numeric|min:0|max:100',
            'status' => 'required|string|max:255',
        ]);

        // Set default values for nullable fields
        $validated['cash_discount'] = $validated['cash_discount'] ?? 0;
        $validated['deposit_payment'] = $validated['deposit_payment'] ?? 0;
        $validated['siblings_discount'] = $validated['siblings_discount'] ?? 0;

        Program::create($validated);

        return redirect()->route('admin.programs.index')
            ->with('success', 'Program created successfully.');
    }
    /**
     * Export programs data to Excel
     */
    public function exportExcel(Request $request)
    {
        $programs = $this->buildFilteredQuery($request)->get();

        $export = new ProgramsExport($programs);
        $filename = 'programs_' . date('Y-m-d_H-i-s') . '.xlsx';

        return Excel::download($export, $filename);
    }

    /**
     * Build a filtered query shared by exportExcel and exportPdf.
     */
    private function buildFilteredQuery(Request $request)
    {
        $search = $request->input('search');
        $universityFilter = $request->input('university_id', []);
        $departmentFilter = $request->input('department', []);
        $degreeFilter = $request->input('degree', []);
        $statusFilter = $request->input('status');
        $languageFilter = $request->input('language');
        $shiftTypeFilter = $request->input('shift_type');
        $minPrice = $request->input('min_price');
        $maxPrice = $request->input('max_price');
        $minDiscount = $request->input('min_discount');
        $maxDiscount = $request->input('max_discount');

        // Normalise: filters may arrive as a single value or an array
        $universityFilter = is_array($universityFilter) ? array_filter($universityFilter) : array_filter([$universityFilter]);
        $departmentFilter = is_array($departmentFilter) ? array_filter($departmentFilter) : array_filter([$departmentFilter]);
        $degreeFilter     = is_array($degreeFilter)     ? array_filter($degreeFilter)     : array_filter([$degreeFilter]);

        $query = Program::with('university')
            ->select([
                'programs.*',
                DB::raw('ROUND(((before_discount - after_discount) / NULLIF(before_discount, 0) * 100), 2) as discount_percentage'),
            ]);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('department', 'like', "%{$search}%")
                  ->orWhereHas('university', fn ($u) => $u->where('name', 'like', "%{$search}%"));
            });
        }

        if (!empty($universityFilter)) {
            $query->whereIn('university_id', $universityFilter);
        }

        if (!empty($departmentFilter)) {
            $query->whereIn('department', $departmentFilter);
        }

        if (!empty($degreeFilter)) {
            $query->whereIn('degree', $degreeFilter);
        }

        if ($statusFilter) {
            $query->where('status', $statusFilter);
        }

        if ($languageFilter) {
            $query->where('language', $languageFilter);
        }

        if ($shiftTypeFilter) {
            $query->where('shift_type', $shiftTypeFilter);
        }

        if ($minPrice !== null && $minPrice !== '') {
            $query->where('after_discount', '>=', $minPrice);
        }

        if ($maxPrice !== null && $maxPrice !== '') {
            $query->where('after_discount', '<=', $maxPrice);
        }

        if ($minDiscount !== null && $minDiscount !== '') {
            $query->having('discount_percentage', '>=', $minDiscount);
        }

        if ($maxDiscount !== null && $maxDiscount !== '') {
            $query->having('discount_percentage', '<=', $maxDiscount);
        }

        return $query;
    }

   /**
 * Export programs data to PDF
 *
 * @param Request $request
 * @return \Illuminate\Http\Response
 */
public function exportPdf(Request $request)
{
    $universityFilter = $request->input('university_id', []);
    $universityFilter = is_array($universityFilter) ? array_filter($universityFilter) : array_filter([$universityFilter]);

    $departmentFilter = $request->input('department', []);
    $departmentFilter = is_array($departmentFilter) ? array_filter($departmentFilter) : array_filter([$departmentFilter]);

    $degreeFilter = $request->input('degree', []);
    $degreeFilter = is_array($degreeFilter) ? array_filter($degreeFilter) : array_filter([$degreeFilter]);

    $statusFilter    = $request->input('status');
    $languageFilter  = $request->input('language');
    $shiftTypeFilter = $request->input('shift_type');
    $minDiscount     = $request->input('min_discount');
    $maxDiscount     = $request->input('max_discount');

    $programs = $this->buildFilteredQuery($request)->limit(500)->get();

    foreach ($programs as $program) {
        if (empty($program->discount_percentage) && $program->before_discount > 0) {
            $program->discount_percentage = round(
                (($program->before_discount - $program->after_discount) / $program->before_discount) * 100
            );
        }
    }

    $totalCount = $this->buildFilteredQuery($request)->count();
    $isTruncated = $totalCount > 500;
   
    // Build human-readable filter labels for the PDF header
    $allUniversities = University::all();
    $universityName = !empty($universityFilter)
        ? $allUniversities->whereIn('id', $universityFilter)->pluck('name')->implode(', ')
        : 'All';

    $departmentName = !empty($departmentFilter) ? implode(', ', $departmentFilter) : 'All';
    $degreeName     = !empty($degreeFilter)     ? implode(', ', $degreeFilter)     : 'All';
    $languageName   = $languageFilter  ?: 'All';
    $shiftTypeName  = $shiftTypeFilter ?: 'All';
    $statusName     = $statusFilter    ?: 'All';
    $search         = $request->input('search');

    // Get the company logo
    $companyLogoPath = public_path('logo.jpg');
    $companyLogo = '';
    if (file_exists($companyLogoPath)) {
        $type = pathinfo($companyLogoPath, PATHINFO_EXTENSION);
        $data = file_get_contents($companyLogoPath);
        $companyLogo = 'data:image/' . $type . ';base64,' . base64_encode($data);
    }

    // Create PDF
    ini_set('memory_limit', '-1');
    $pdf = app('dompdf.wrapper');
    $pdf->getDomPDF()->set_option('isHtml5ParserEnabled', true);
    $pdf->getDomPDF()->set_option('isRemoteEnabled', false);
    $pdf->getDomPDF()->set_option('enable_php', false);

    $pdf->loadView('admin.programs.pdf', [
        'programs'       => $programs,
        'search'         => $search,
        'universityName' => $universityName,
        'departmentName' => $departmentName,
        'degreeName'     => $degreeName,
        'statusName'     => $statusName,
        'languageName'   => $languageName,
        'shiftTypeName'  => $shiftTypeName,
        'date'           => now()->format('F d, Y, h:i A'),
        'isTruncated'    => $isTruncated,
        'totalCount'     => $totalCount,
    ]);
   
    // Use landscape orientation for better fit 
    $pdf->setPaper('a4', 'landscape');
   
    return $pdf->download('programs_report_' . date('Y-m-d_H-i-s') . '.pdf');
}
    public function show(Program $program)
    {
        $program->load('university');
        
        return view('admin.programs.show', compact('program'));
    }

    /**
     * Show the form for editing the specified program.
     */
    public function edit(Program $program)
    {
        $universities = University::where('is_active', true)->orderBy('name')->get();
        $languages = Program::getLanguages();
        $shiftTypes = Program::getShiftTypes();
        $statuses = Program::getStatuses();
        $departments = Department::all();
        $degrees = Degree::all();

        return view('admin.programs.edit', compact('departments','program', 'universities', 'degrees', 'languages', 'shiftTypes', 'statuses'));
    }

    /**
     * Update the specified program in storage.
     */
    public function update(Request $request, Program $program)
    {
        $validated = $request->validate([
            'university_id' => 'required|exists:universities,id',
            'department' => 'required|string|max:255',
            'degree' => 'required|string|max:255',
            'language' => 'required|string|max:255',
            'before_discount' => 'required|numeric|min:0',
            'after_discount' => 'required|numeric|min:0|lte:before_discount',
            'cash_discount' => 'nullable|numeric|min:0',
            'deposit_payment' => 'nullable|numeric|min:0',
            'shift_type' => 'required|string|max:255',
            'siblings_discount' => 'nullable|numeric|min:0|max:100',
            'status' => 'required|string|max:255',
        ]);

        // Set default values for nullable fields
        $validated['cash_discount'] = $validated['cash_discount'] ?? 0;
        $validated['deposit_payment'] = $validated['deposit_payment'] ?? 0;
        $validated['siblings_discount'] = $validated['siblings_discount'] ?? 0;

        $program->update($validated);

        return redirect()->route('admin.programs.index')
            ->with('success', 'Program updated successfully.');
    }

    /**
     * Remove the specified program from storage.
     */
    public function destroy(Program $program)
    {
        $program->delete();

        return redirect()->route('admin.programs.index')
            ->with('success', 'Program deleted successfully.');
    }

    public function allFilteredIds(Request $request)
    {
        $query = Program::query();
        if ($request->filled('search')) {
            $s = $request->input('search');
            $query->where(function ($q) use ($s) {
                $q->where('department', 'like', "%{$s}%")
                  ->orWhereHas('university', fn($u) => $u->where('name', 'like', "%{$s}%"));
            });
        }
        if ($request->filled('university_id')) $query->whereIn('university_id', $request->input('university_id'));
        if ($request->filled('department'))    $query->whereIn('department',    $request->input('department'));
        if ($request->filled('degree'))        $query->whereIn('degree',        $request->input('degree'));
        if ($request->filled('status'))        $query->where('status',          $request->input('status'));
        if ($request->filled('language'))      $query->where('language',        $request->input('language'));
        if ($request->filled('shift_type'))    $query->where('shift_type',      $request->input('shift_type'));
        if ($request->filled('min_price'))     $query->where('after_discount',  '>=', $request->input('min_price'));
        if ($request->filled('max_price'))     $query->where('after_discount',  '<=', $request->input('max_price'));
        return response()->json(['count' => $query->count()]);
    }

    public function destroyAll()
    {
        $count = Program::count();
        Program::query()->delete();

        return redirect()->route('admin.programs.index')
            ->with('success', "{$count} programs deleted successfully.");
    }

    public function destroySelected(Request $request)
    {
        // Select-all mode: delete everything matching current filters
        if ($request->boolean('select_all')) {
            $query = Program::query();
            if ($request->filled('search')) {
                $s = $request->input('search');
                $query->where(function ($q) use ($s) {
                    $q->where('department', 'like', "%{$s}%")
                      ->orWhereHas('university', fn($u) => $u->where('name', 'like', "%{$s}%"));
                });
            }
            if ($request->filled('university_id')) $query->whereIn('university_id', $request->input('university_id'));
            if ($request->filled('department'))    $query->whereIn('department',    $request->input('department'));
            if ($request->filled('degree'))        $query->whereIn('degree',        $request->input('degree'));
            if ($request->filled('status'))        $query->where('status',          $request->input('status'));
            if ($request->filled('language'))      $query->where('language',        $request->input('language'));
            if ($request->filled('shift_type'))    $query->where('shift_type',      $request->input('shift_type'));
            if ($request->filled('min_price'))     $query->where('after_discount',  '>=', $request->input('min_price'));
            if ($request->filled('max_price'))     $query->where('after_discount',  '<=', $request->input('max_price'));
            $count = $query->delete();
        } else {
            $ids = $request->input('ids', []);
            if (empty($ids)) {
                return redirect()->route('admin.programs.index')
                    ->with('warning', 'No programs selected.');
            }
            $count = Program::whereIn('id', $ids)->delete();
        }

        return redirect()->route('admin.programs.index')
            ->with('success', "{$count} program(s) deleted successfully.");
    }

    /**
     * Import programs from an uploaded Excel file.
     */
    public function importExcel(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ]);

        $import = new ProgramsImport();
        Excel::import($import, $request->file('file'));

        $failures = $import->failures();
        $errors   = $import->errors();

        $problemMessages = [];

        foreach ($failures as $failure) {
            $problemMessages[] = 'Row ' . $failure->row() . ': ' . implode(', ', $failure->errors());
        }
        foreach ($errors as $error) {
            $problemMessages[] = $error->getMessage();
        }

        $count = $import->importedCount;

        if (!empty($problemMessages)) {
            $preview = implode(' | ', array_slice($problemMessages, 0, 5));
            $more = count($problemMessages) > 5 ? ' ... and ' . (count($problemMessages) - 5) . ' more.' : '';
            return redirect()->route('admin.programs.index')
                ->with('warning', "Imported {$count} program(s) with issues — {$preview}{$more}");
        }

        return redirect()->route('admin.programs.index')
            ->with('success', "Successfully imported {$count} program(s).");
    }

    /**
     * Download a blank Excel template for importing programs.
     */
    public function downloadTemplate()
    {
        $headers = [
            'University', 'Department', 'Degree', 'Language',
            'Price Before Discount', 'Price After Discount',
            'Cash Discount', 'Deposit Payment', 'Siblings Discount',
            'Shift Type', 'Status',
        ];

        $example = [
            'Example University', 'Computer Science', 'Bachelor', 'English',
            '10000', '8000', '500', '2000', '5', 'Day', 'Active',
        ];

        $export = new class($headers, $example) implements \Maatwebsite\Excel\Concerns\FromArray, \Maatwebsite\Excel\Concerns\WithStyles {
            public function __construct(private array $headers, private array $example) {}
            public function array(): array { return [$this->headers, $this->example]; }
            public function styles(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet): array {
                return [1 => ['font' => ['bold' => true]]];
            }
        };

        return Excel::download($export, 'programs_import_template.xlsx');
    }

    /**
     * Toggle program status between Active and Inactive.
     */
    public function toggleStatus(Program $program)
    {
        $program->status = ($program->status === 'Active') ? 'Inactive' : 'Active';
        $program->save();
        
        return redirect()->back()
            ->with('success', 'Program status updated successfully.');
    }
}