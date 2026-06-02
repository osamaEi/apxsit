@extends('admin.index')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->

        
        <!-- Main content -->
        <main class="col-md-12 ms-sm-auto col-lg-12 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Countries</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <a href="{{ route('admin.world.dashboard') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Dashboard
                    </a>
                </div>
            </div>
            
            <!-- Search Form -->
            <div class="card mb-4">
                <div class="card-body">
                    <form action="{{ route('admin.countries.index') }}" method="GET" class="row g-3">
                        <div class="col-md-10">
                            <input type="text" name="search" class="form-control" placeholder="Search countries..." value="{{ $search }}">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100">Search</button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Countries Table -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Countries List</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Country Name</th>
                                    <th>ISO2</th>
                                    <th>ISO3</th>
                                    <th>Phone Code</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($countries as $country)
                                <tr>
                                    <td>{{ $country['name'] }}</td>
                                    <td>{{ $country['iso2'] }}</td>
                                    <td>{{ $country['iso3'] }}</td>
                                    <td>{{ $country['phone_code'] }}</td>
                                    <td>
                                        <a href="{{ route('admin.states.index', $country['id']) }}" class="btn btn-sm btn-primary">
                                            View States/Regions
                                        </a>
                                    </td>
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