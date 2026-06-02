@extends('admin.index')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
   
        
        <!-- Main content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Cities: {{ $state['name'] }}, {{ $country['name'] }}</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <a href="{{ route('admin.states.index', $country['id']) }}" class="btn btn-sm btn-outline-secondary me-2">
                        <i class="fas fa-arrow-left"></i> Back to States
                    </a>
                    <a href="{{ route('admin.world.dashboard') }}" class="btn btn-sm btn-outline-secondary">
                        Dashboard
                    </a>
                </div>
            </div>
            
            <!-- Search Form -->
            <div class="card mb-4">
                <div class="card-body">
                    <form action="{{ route('admin.cities.index', [$country['id'], $state['id']]) }}" method="GET" class="row g-3">
                        <div class="col-md-10">
                            <input type="text" name="search" class="form-control" placeholder="Search cities..." value="{{ $search }}">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100">Search</button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.countries.index') }}">Countries</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.states.index', $country['id']) }}">{{ $country['name'] }}</a></li>
                    <li class="breadcrumb-item active">{{ $state['name'] }}</li>
                </ol>
            </nav>
            
            <!-- Cities Table -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Cities in {{ $state['name'] }}</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>City Name</th>
                                    <th>Latitude</th>
                                    <th>Longitude</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cities as $city)
                                <tr>
                                    <td>{{ $city['name'] }}</td>
                                    <td>{{ $city['latitude'] ?? 'N/A' }}</td>
                                    <td>{{ $city['longitude'] ?? 'N/A' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection