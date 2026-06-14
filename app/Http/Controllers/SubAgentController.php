<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Nnjeim\World\Models\Country;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\SubagentCountryCommission;
use Illuminate\Support\Facades\Validator;

class SubAgentController extends Controller
{
    /**
     * Display a listing of the subagents for the authenticated user.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Check if the authenticated user is an admin
        if (Auth::user()->role === 'Admin') {
            // Admin sees all subagents
            $subagents = User::whereNotNull('parent_id')
                             ->with(['country', 'countryCommissions.country'])
                             ->get();
        } else {
            // Regular users only see their own subagents
            $subagents = User::where('parent_id', Auth::id())
                             ->with(['country', 'countryCommissions.country'])
                             ->get();
        }
        
        return view('subagents.index', compact('subagents'));
    }

    /**
     * Show the form for creating a new subagent.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $countries = Country::whereIn('name',['Turkey','Cyprus'])->get();
        return view('subagents.create', compact('countries'));
    }

    /**
     * Store a newly created subagent in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'country_id' => 'required|exists:countries,id',
            'phone' => 'required|string|max:20',
            'type' => 'required|in:individual,company',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'contracted_countries' => 'nullable|array',
            'contracted_countries.*' => 'exists:countries,id',
            'commission_rates' => 'nullable|array',
            'commission_rates.*' => 'numeric|min:0|max:100',
        ]);
        
        if ($validator->fails()) {
            return redirect()->route('subagents.create')
                ->withErrors($validator)
                ->withInput();
        }
        
        // Upload and store photo if provided
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('profile-photos', 'public');
        }
       
        // Create the new subagent
        $subagent = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'country_id' => $request->country_id,
            'phone' => $request->phone,
            'type' => $request->type,
            'photo' => $photoPath,
            'parent_id' => Auth::id(), // Set the authenticated user as parent
        ]);
        
        // Save contracted countries and commission rates
        if ($request->has('contracted_countries') && is_array($request->contracted_countries)) {
            foreach ($request->contracted_countries as $index => $countryId) {
                // Skip empty country selections
                if (empty($countryId)) {
                    continue;
                }
                
                // Check if commission rate exists for this country
                if (isset($request->commission_rates[$index])) {
                    SubagentCountryCommission::create([
                        'user_id' => $subagent->id,
                        'country_id' => $countryId,
                        'commission_rate' => $request->commission_rates[$index],
                    ]);
                }
            }
        }
    
        return redirect()->route('subagents.index')
            ->with('success', 'Subagent created successfully.');
    }

    /**
     * Display the specified subagent.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $query = User::where('id', $id);
        if (Auth::user()->role !== 'Admin') {
            $query->where('parent_id', Auth::id());
        }
        $subagent = $query->firstOrFail();
            
        return view('subagents.show', compact('subagent'));
    }

    /**
     * Show the form for editing the specified subagent.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $query = User::where('id', $id);
        if (Auth::user()->role !== 'Admin') {
            $query->where('parent_id', Auth::id());
        }
        $subagent = $query->firstOrFail();

        $countries = Country::whereIn('name',['Turkey','Cyprus'])->get();
        $countryCommissions = $subagent->countryCommissions()->with('country')->get();

        return view('subagents.edit', compact('subagent', 'countries', 'countryCommissions'));
    }

    /**
     * Update the specified subagent in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $query = User::where('id', $id);
        if (Auth::user()->role !== 'Admin') {
            $query->where('parent_id', Auth::id());
        }
        $subagent = $query->firstOrFail();
            
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8|confirmed',
            'country_id' => 'required|exists:countries,id',
            'phone' => 'required|string|max:20',
            'type' => 'required|in:individual,company',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'contracted_countries' => 'nullable|array',
            'contracted_countries.*' => 'exists:countries,id',
            'commission_rates' => 'nullable|array',
            'commission_rates.*' => 'numeric|min:0|max:100',
        ]);

        if ($validator->fails()) {
            return redirect()->route('subagents.edit', $id)
                ->withErrors($validator)
                ->withInput();
        }

        // Update subagent details
        $subagent->name = $request->name;
        $subagent->email = $request->email;
        $subagent->country_id = $request->country_id;
        $subagent->phone = $request->phone;
        $subagent->type = $request->type;
        
        // Handle password update
        if ($request->filled('password')) {
            $subagent->password = Hash::make($request->password);
        }
        
        // Handle photo upload
        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($subagent->photo) {
                Storage::disk('public')->delete($subagent->photo);
            }
            
            // Store new photo
            $photoPath = $request->file('photo')->store('profile-photos', 'public');
            $subagent->photo = $photoPath;
        }
        
        $subagent->save();

        // Update contracted countries and commission rates
        // First, delete existing country commissions
        $subagent->countryCommissions()->delete();
        
        // Then add new ones
        if ($request->has('contracted_countries') && is_array($request->contracted_countries)) {
            foreach ($request->contracted_countries as $index => $countryId) {
                if (isset($request->commission_rates[$index])) {
                    SubagentCountryCommission::create([
                        'user_id' => $subagent->id,
                        'country_id' => $countryId,
                        'commission_rate' => $request->commission_rates[$index],
                    ]);
                }
            }
        }

        return redirect()->route('subagents.index')
            ->with('success', 'Subagent updated successfully.');
    }

    /**
     * Remove the specified subagent from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $query = User::where('id', $id);
        if (Auth::user()->role !== 'Admin') {
            $query->where('parent_id', Auth::id());
        }
        $subagent = $query->firstOrFail();
        
        // Delete the photo file if exists
        if ($subagent->photo) {
            Storage::disk('public')->delete($subagent->photo);
        }
            
        $subagent->delete();

        return redirect()->route('subagents.index')
            ->with('success', 'Subagent deleted successfully.');
    }
}