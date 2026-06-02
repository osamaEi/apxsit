<?php
// app/Http/Controllers/Admin/WorldDataController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Nnjeim\World\Models\Country;
use Nnjeim\World\Models\State;
use Nnjeim\World\Models\City;
use Nnjeim\World\World;

class WorldDataController extends Controller
{
    /**
     * Display countries list with search and pagination
     */
    public function countries(Request $request)
    {
        $search = $request->input('search', '');
        
        // Get countries using Eloquent instead of the package's action
        $query = Country::query();
        
        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }
        
        $countries = $query->get();
        
        return view('admin.world.countries', compact('countries', 'search'));
    }

    /**
     * Display states/regions for a specific country
     */
    public function states(Request $request, $countryId)
    {
        $country = Country::find($countryId);
        
        if (!$country) {
            return redirect()->route('admin.countries.index')
                ->with('error', 'Country not found');
        }

        $search = $request->input('search', '');
        $query = State::where('country_id', $countryId);
        
        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }
        
        $states = $query->get();

        return view('admin.world.states', compact('states', 'country', 'search'));
    }

    /**
     * Display cities for a specific state
     */
    public function cities(Request $request, $countryId, $stateId)
    {
        $country = Country::find($countryId);
        $state = State::find($stateId);
        
        if (!$country || !$state) {
            return redirect()->route('admin.countries.index')
                ->with('error', 'Country or state not found');
        }

        $search = $request->input('search', '');
        $query = City::where('state_id', $stateId);
        
        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }
        
        $cities = $query->get();

        return view('admin.world.cities', compact('cities', 'country', 'state', 'search'));
    }

    /**
     * Display dashboard overview of world data
     */
    public function dashboard()
    {
        $countriesCount = Country::count();
        
        // Get top 5 countries with most states
        $countriesWithMostStates = Country::select('id', 'name')
            ->withCount('states')
            ->orderByDesc('states_count')
            ->take(5)
            ->get();

        return view('admin.world.dashboard', compact('countriesCount', 'countriesWithMostStates'));
    }
}