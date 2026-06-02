@extends('admin.index')

@section('content')
<div class="container-fluid">
    <div class="row">
   
        
        <!-- Main content -->
        <main class="col-md-12 ms-sm-auto col-lg-12 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">World Data Dashboard</h1>
            </div>
            
            <div class="row">
                <div class="col-md-4">
                    <div class="card mb-4">
                        <div class="card-body text-center">
                            <h5 class="card-title">Total Countries</h5>
                            <h2 class="display-4">{{ $countriesCount }}</h2>
                            <a href="{{ route('admin.countries.index') }}" class="btn btn-primary mt-3">View Countries</a>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-8">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Countries with Most States/Regions</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>Country</th>
                                            <th>Number of States/Regions</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($countriesWithMostStates as $country)
                                        <tr>
                                            <td>{{ $country['name'] }}</td>
                                            <td>{{ $country['states_count'] }}</td>
                                            <td>
                                                <a href="{{ route('admin.states.index', $country['id']) }}" class="btn btn-sm btn-outline-primary">View States</a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Quick Navigation</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="card mb-3">
                                        <div class="card-body text-center">
                                            <i class="fas fa-globe fa-3x mb-3 text-primary"></i>
                                            <h5 class="card-title">Countries</h5>
                                            <p class="card-text">Browse all countries and their details</p>
                                            <a href="{{ route('admin.countries.index') }}" class="btn btn-outline-primary">Countries List</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card mb-3">
                                        <div class="card-body text-center">
                                            <i class="fas fa-map fa-3x mb-3 text-success"></i>
                                            <h5 class="card-title">States & Regions</h5>
                                            <p class="card-text">Browse states for each country</p>
                                            <a href="{{ route('admin.countries.index') }}" class="btn btn-outline-success">Select Country</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card mb-3">
                                        <div class="card-body text-center">
                                            <i class="fas fa-city fa-3x mb-3 text-info"></i>
                                            <h5 class="card-title">Cities</h5>
                                            <p class="card-text">Browse cities within states/regions</p>
                                            <a href="{{ route('admin.countries.index') }}" class="btn btn-outline-info">Select Country</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection