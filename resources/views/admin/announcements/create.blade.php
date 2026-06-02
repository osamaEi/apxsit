<!-- resources/views/admin/announcements/create.blade.php -->
@extends('admin.index')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Create Announcement</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.announcements.index') }}" class="btn btn-default">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.announcements.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-8">
                                <!-- Title -->
                                <div class="form-group">
                                    <label for="title">Announcement Title <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" required>
                                    @error('title')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <!-- Description with Rich Text Editor -->
                                <div class="form-group">
                                    <label for="description">Description <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="8" required>{{ old('description') }}</textarea>
                                    @error('description')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <!-- University -->
                                <div class="form-group">
                                    <label for="university_id">University <span class="text-danger">*</span></label>
                                    <select class="form-control select2bs4 @error('university_id') is-invalid @enderror" id="university_id" name="university_id" required>
                                        <option value="">Select University</option>
                                        @foreach($universities as $university)
                                            <option value="{{ $university->id }}" {{ old('university_id') == $university->id ? 'selected' : '' }}>
                                                {{ $university->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('university_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                
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
                                    <select class="form-control select2bs4 @error('city_id') is-invalid @enderror" id="city_id" name="city_id" required>
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
                                
                                <!-- Image -->
                                <div class="form-group">
                                    <label for="image">Image</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input @error('image') is-invalid @enderror" id="image" name="image">
                                            <label class="custom-file-label" for="image">Choose file</label>
                                        </div>
                                    </div>
                                    <small class="form-text text-muted">Accepted formats: jpeg, png, jpg, gif. Max size: 2MB</small>
                                    @error('image')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <!-- Publication Settings -->
                                <div class="card mt-4">
                                    <div class="card-header bg-light">
                                        <h5 class="mb-0">Publication Settings</h5>
                                    </div>
                                    <div class="card-body">
                                        <!-- Status -->
                                        <div class="form-group">
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" {{ old('is_active', 1) ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="is_active">Active</label>
                                            </div>
                                            <small class="form-text text-muted">Inactive announcements won't be displayed even if published.</small>
                                        </div>
                                        
                                        <!-- Publication Options -->
                                        <div class="form-group mt-3">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="publish_now" name="publish_option" value="now" class="custom-control-input" {{ old('publish_option', 'now') == 'now' ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="publish_now">Publish immediately</label>
                                                <input type="hidden" name="publish_now" value="0" id="publish_now_value">
                                            </div>
                                            <div class="custom-control custom-radio mt-2">
                                                <input type="radio" id="publish_later" name="publish_option" value="later" class="custom-control-input" {{ old('publish_option') == 'later' ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="publish_later">Schedule publication</label>
                                            </div>
                                            <div class="mt-3 {{ old('publish_option') == 'later' ? '' : 'd-none' }}" id="schedule_datetime_container">
                                                <div class="input-group date" id="published_at_picker" data-target-input="nearest">
                                                    <input type="text" class="form-control datetimepicker-input @error('published_at') is-invalid @enderror" data-target="#published_at_picker" name="published_at" id="published_at" value="{{ old('published_at') }}"/>
                                                    <div class="input-group-append" data-target="#published_at_picker" data-toggle="datetimepicker">
                                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                    </div>
                                                </div>
                                                @error('published_at')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="custom-control custom-radio mt-2">
                                                <input type="radio" id="publish_draft" name="publish_option" value="draft" class="custom-control-input" {{ old('publish_option') == 'draft' ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="publish_draft">Save as draft</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Save Announcement
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add this to your <head> section -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<!-- Tempusdominus Bootstrap 4 (datetime picker) -->
<link rel="stylesheet" href="{{ asset('plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
<!-- Summernote (Rich Text Editor) -->
<link rel="stylesheet" href="{{ asset('plugins/summernote/summernote-bs4.min.css') }}">
<style>
    .select2-container--bootstrap4 .select2-selection--single {
        height: calc(2.25rem + 2px) !important;
    }
    .note-editor.note-frame {
        border-radius: 0.5rem;
        border-color: #e2e8f0;
    }
    .note-editor.note-frame.note-frame-focused {
        border-color: #4f46e5;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.2);
    }
</style>

<!-- jQuery (make sure only one jQuery is loaded) -->
<!-- Moment.js (for date/time picker) -->
<script src="{{ asset('plugins/moment/moment.min.js') }}"></script>
<!-- Tempusdominus Bootstrap 4 (datetime picker) -->
<script src="{{ asset('plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
<!-- Select2 -->

<!-- Summernote (Rich Text Editor) -->
<script src="{{ asset('plugins/summernote/summernote-bs4.min.js') }}"></script>

<script>
// Wait for DOM to be fully loaded
document.addEventListener('DOMContentLoaded', function() {
    // Check if jQuery is loaded
    if (typeof jQuery === 'undefined') {
        console.error('jQuery is not loaded! Adding jQuery from CDN...');
        const jqueryScript = document.createElement('script');
        jqueryScript.src = "https://code.jquery.com/jquery-3.6.0.min.js";
        jqueryScript.onload = function() {
            console.log('jQuery loaded from CDN');
            initializeAllComponents();
        };
        document.head.appendChild(jqueryScript);
    } else {
        console.log('jQuery is loaded, initializing components');
        initializeAllComponents();
    }
});

// Main function to initialize all components
function initializeAllComponents() {
    // Initialize Summernote editor
    if (typeof $.fn.summernote !== 'undefined') {
        $('#description').summernote({
            height: 300,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ],
            placeholder: 'Write announcement description here...',
            callbacks: {
                onImageUpload: function(files) {
                    alert('Image upload is not available. Please use external image links.');
                }
            }
        });
    } else {
        console.error('Summernote plugin is not available');
    }
    
    // Initialize Select2 Elements
    if (typeof $.fn.select2 !== 'undefined') {
        $('.select2bs4').select2({
            theme: 'bootstrap4',
            width: '100%'
        });
    } else {
        console.error('Select2 plugin is not available');
    }
    
    // Initialize datetime picker
    if (typeof $.fn.datetimepicker !== 'undefined') {
        $('#published_at_picker').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            icons: {
                time: 'far fa-clock'
            },
            minDate: moment()
        });
    } else {
        console.error('DateTimePicker plugin is not available');
    }
    
    // Setup publication options
    setupPublicationOptions();
    
    // Setup cities dropdown with regular AJAX
    setupCitiesDropdown();
    
    // Setup file input display
    setupFileInputs();
}

// Handle publication options
function setupPublicationOptions() {
    const publishOptions = document.querySelectorAll('input[name="publish_option"]');
    const scheduleContainer = document.getElementById('schedule_datetime_container');
    const publishNowValue = document.getElementById('publish_now_value');
    
    if (!publishOptions.length || !scheduleContainer || !publishNowValue) {
        console.warn('Publication option elements not found');
        return;
    }
    
    publishOptions.forEach(option => {
        option.addEventListener('change', function() {
            const selectedOption = document.querySelector('input[name="publish_option"]:checked').value;
            
            if (selectedOption === 'later') {
                scheduleContainer.classList.remove('d-none');
                publishNowValue.value = '0';
            } else {
                scheduleContainer.classList.add('d-none');
                
                if (selectedOption === 'now') {
                    publishNowValue.value = '1';
                } else {
                    publishNowValue.value = '0';
                }
            }
        });
    });
    
    // Trigger change to set initial state
    const checkedOption = document.querySelector('input[name="publish_option"]:checked');
    if (checkedOption) {
        const event = new Event('change');
        checkedOption.dispatchEvent(event);
    }
}

// Setup cities dropdown with regular XMLHttpRequest
function setupCitiesDropdown() {
    const countrySelect = document.getElementById('country_id');
    const citySelect = document.getElementById('city_id');
    const cityLoading = document.getElementById('city-loading');
    
    if (!countrySelect || !citySelect) {
        console.warn('Country or city select elements not found');
        return;
    }
    
    // Store old city ID for reselection
    let oldCityId = '';
    try {
        // Get from Laravel blade template if available
        oldCityId = '{{ old("city_id") }}';
        citySelect.setAttribute('data-old-value', oldCityId);
    } catch (e) {
        console.log('No old city ID available');
    }
    
    // Add event listener for country change
    countrySelect.addEventListener('change', function() {
        const countryId = this.value;
        console.log('Country changed to ID:', countryId);
        
        // Reset city dropdown
        citySelect.innerHTML = '<option value="">Loading...</option>';
        citySelect.disabled = true;
        
        // Show loading indicator
        if (cityLoading) cityLoading.classList.remove('d-none');
        
        if (!countryId) {
            // No country selected
            citySelect.innerHTML = '<option value="">Select Country First</option>';
            citySelect.disabled = false;
            if (cityLoading) cityLoading.classList.add('d-none');
            
            // Update Select2 if available
            if (typeof $ !== 'undefined' && typeof $.fn.select2 !== 'undefined') {
                $(citySelect).trigger('change');
            }
            return;
        }
        
        // Create AJAX request
        const xhr = new XMLHttpRequest();
        xhr.open('GET', '/admin/announcements/get-cities/' + countryId, true);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.setRequestHeader('Accept', 'application/json');
        
        // Get CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (csrfToken) {
            xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken.getAttribute('content'));
        }
        
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
                if (xhr.status === 200) {
                    try {
                        const cities = JSON.parse(xhr.responseText);
                        console.log('Cities loaded successfully:', cities);
                        populateCities(cities);
                    } catch (error) {
                        console.error('Error parsing city data:', error);
                        citySelect.innerHTML = '<option value="">Error loading cities</option>';
                        tryAlternativeUrl(countryId);
                    }
                } else {
                    console.error('Error loading cities. Status:', xhr.status);
                    citySelect.innerHTML = '<option value="">Error loading cities</option>';
                    tryAlternativeUrl(countryId);
                }
                
                // Enable city dropdown and hide loading
                citySelect.disabled = false;
                if (cityLoading) cityLoading.classList.add('d-none');
                
                // Update Select2 if available
                if (typeof $ !== 'undefined' && typeof $.fn.select2 !== 'undefined') {
                    $(citySelect).trigger('change');
                }
            }
        };
        
        xhr.send();
    });
    
    // Try alternative URL if the first one fails
    function tryAlternativeUrl(countryId) {
        console.log('Trying alternative URL for cities');
        const altXhr = new XMLHttpRequest();
        altXhr.open('GET', '/admin/cities/by-country/' + countryId, true);
        altXhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        altXhr.setRequestHeader('Accept', 'application/json');
        
        // Get CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (csrfToken) {
            altXhr.setRequestHeader('X-CSRF-TOKEN', csrfToken.getAttribute('content'));
        }
        
        altXhr.onreadystatechange = function() {
            if (altXhr.readyState === 4) {
                if (altXhr.status === 200) {
                    try {
                        const cities = JSON.parse(altXhr.responseText);
                        console.log('Cities loaded from alternative URL:', cities);
                        populateCities(cities);
                    } catch (error) {
                        console.error('Error parsing city data from alternative URL:', error);
                        citySelect.innerHTML = '<option value="">Could not load cities</option>';
                    }
                } else {
                    console.error('Alternative URL also failed. Status:', altXhr.status);
                    citySelect.innerHTML = '<option value="">Could not load cities</option>';
                }
                
                // Update Select2 if available
                if (typeof $ !== 'undefined' && typeof $.fn.select2 !== 'undefined') {
                    $(citySelect).trigger('change');
                }
            }
        };
        
        altXhr.send();
    }
    
    // Helper function to populate the cities dropdown
    function populateCities(cities) {
        // Clear dropdown
        citySelect.innerHTML = '<option value="">Select City</option>';
        
        // Add cities to dropdown
        if (cities && cities.length > 0) {
            cities.forEach(function(city) {
                const option = document.createElement('option');
                option.value = city.id;
                option.textContent = city.name;
                citySelect.appendChild(option);
            });
            
            // Set previously selected city if any
            const oldCityId = citySelect.getAttribute('data-old-value');
            if (oldCityId) {
                citySelect.value = oldCityId;
            }
        } else {
            const option = document.createElement('option');
            option.value = '';
            option.textContent = 'No cities found';
            citySelect.appendChild(option);
        }
    }
    
    // Trigger country change event if country is already selected (on page load)
    if (countrySelect.value) {
        console.log('Country already selected, triggering change event');
        const event = new Event('change');
        countrySelect.dispatchEvent(event);
    }
}

// Setup file input display functionality
function setupFileInputs() {
    const fileInputs = document.querySelectorAll('.custom-file-input');
    
    fileInputs.forEach(input => {
        input.addEventListener('change', function() {
            const fileName = this.value.split('\\').pop();
            const label = this.nextElementSibling;
            if (label) {
                label.classList.add('selected');
                label.textContent = fileName;
            }
        });
    });
}
</script>
@endsection