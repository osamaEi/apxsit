@extends('admin.index')

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Add University</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.universities.index') }}" class="btn btn-default">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.universities.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <!-- Name -->
                                <div class="form-group">
                                    <label for="name">University Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <!-- Logo -->
                                <div class="form-group">
                                    <label for="logo">Logo</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input @error('logo') is-invalid @enderror" id="logo" name="logo">
                                            <label class="custom-file-label" for="logo">Choose file</label>
                                        </div>
                                    </div>
                                    <small class="form-text text-muted">Accepted formats: jpeg, png, jpg, gif. Max size: 2MB</small>
                                    @error('logo')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <!-- Type -->
                                <div class="form-group">
                                    <label for="type">University Type</label>
                                    <select class="form-control @error('type') is-invalid @enderror" id="type" name="type">
                                        <option value="">Select Type</option>
                                        @foreach($types as $key => $value)
                                            <option value="{{ $key }}" {{ old('type') == $key ? 'selected' : '' }}>
                                                {{ $value }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('type')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <!-- Status -->
                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" {{ old('is_active', 1) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="is_active">Active</label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <!-- Country -->
                                <div class="form-group">
                                    <label for="country_id">Country <span class="text-danger">*</span></label>
                                    <select class="form-control select2bs4 @error('country_id') is-invalid @enderror" id="country_id" name="country_id" required>
                                        <option value="">Select Country</option>
                                        @foreach($countries as $country)
                                            <option value="{{ $country->id }}" {{ old('country_id') == $country->id ? 'selected' : '' }}>
                                                {{ $country->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('country_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <!-- City -->
                                <div class="form-group">
                                    <label for="city_id">City <span class="text-danger">*</span></label>
                                    <select class="form-control select2bs4 @error('city_id') is-invalid @enderror" id="city_id" name="city_id"  data-placeholder="Select Country First">
                                        <option value="">Select Country First</option>
                                        @if(old('city_id') && old('country_id'))
                                            <option value="{{ old('city_id') }}" selected>Loading...</option>
                                        @endif
                                    </select>
                                    <div id="city-loading" class="mt-2 d-none">
                                        <div class="spinner-border spinner-border-sm text-primary" role="status">
                                            <span class="sr-only">Loading...</span>
                                        </div>
                                        <span class="ml-1">Loading cities...</span>
                                    </div>
                                    @error('city_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <!-- Address -->
                                <div class="form-group">
                                    <label for="address">Address</label>
                                    <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3">{{ old('address') }}</textarea>
                                    @error('address')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <!-- Description -->
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="5">{{ old('description') }}</textarea>
                                    @error('description')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Save University
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<style>
    .select2-container--bootstrap4 .select2-selection--single {
        height: calc(2.25rem + 2px) !important;
    }
</style>
<!-- First, make sure jQuery is loaded only once, preferably a recent version -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

<!-- Then add your custom script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Wait for the country dropdown to change
    document.getElementById('country_id').addEventListener('change', function() {
        var countryId = this.value;
        var citySelect = document.getElementById('city_id');
        
        // Clear and disable city dropdown
        citySelect.innerHTML = '<option value="">Loading...</option>';
        citySelect.disabled = true;
        
        // Show loading indicator if it exists
        var loadingIndicator = document.getElementById('city-loading');
        if (loadingIndicator) loadingIndicator.classList.remove('d-none');
        
        if (countryId) {
            // Create and send AJAX request using native JavaScript
            var xhr = new XMLHttpRequest();
            xhr.open('GET', '/admin/universities/get-cities?country_id=' + countryId, true);
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                        var cities = JSON.parse(xhr.responseText);
                        
                        // Clear dropdown
                        citySelect.innerHTML = '<option value="">Select City</option>';
                        
                        // Add cities to dropdown
                        if (cities && cities.length > 0) {
                            cities.forEach(function(city) {
                                var option = document.createElement('option');
                                option.value = city.id;
                                option.textContent = city.name;
                                citySelect.appendChild(option);
                            });
                        } else {
                            var option = document.createElement('option');
                            option.value = "";
                            option.textContent = "No cities found";
                            citySelect.appendChild(option);
                        }
                    } else {
                        citySelect.innerHTML = '<option value="">Error loading cities</option>';
                        console.error('AJAX Error:', xhr.statusText);
                    }
                    
                    // Enable city dropdown
                    citySelect.disabled = false;
                    
                    // Hide loading indicator
                    if (loadingIndicator) loadingIndicator.classList.add('d-none');
                }
            };
            xhr.send();
        } else {
            citySelect.innerHTML = '<option value="">Select Country First</option>';
            citySelect.disabled = false;
            if (loadingIndicator) loadingIndicator.classList.add('d-none');
        }
    });
});
</script>


@endsection
