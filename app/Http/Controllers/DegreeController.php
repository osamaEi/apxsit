<?php

namespace App\Http\Controllers;

use App\Models\Degree;
use Illuminate\Http\Request;

class DegreeController extends Controller
{
    /**
     * Display a listing of departments with create form.
     */
    public function index()
    {
        $degrees = Degree::latest()->paginate(10);
        return view('admin.degrees.index', compact('degrees'));
    }

    /**
     * Store a newly created department.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:degrees,name',
        ]);

        Degree::create([
            'name' => $request->name,
        ]);

        return redirect()->route('admin.degrees.index')
            ->with('success', 'Department created successfully.');
    }

    /**
     * Delete the specified department.
     */
    public function destroy(Degree $degree)
    {
        $degree->delete();

        return redirect()->route('admin.degrees.index')
            ->with('success', 'Department deleted successfully.');
    }
}