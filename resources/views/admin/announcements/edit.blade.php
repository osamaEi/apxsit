{{-- resources/views/admin/announcements/edit.blade.php --}}
@extends('admin.index')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Announcement</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.announcements.index') }}" class="btn btn-default">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.announcements.update', $announcement) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-8">
                                <!-- Title -->
                                <div class="form-group">
                                    <label for="title">Announcement Title <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $announcement->title) }}" required>
                                    @error('title')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <!-- Description -->
                                <div class="form-group">
                                    <label for="description">Description <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="8" required>{{ old('description', $announcement->description) }}</textarea>
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
                                            <option value="{{ $university->id }}" {{ old('university_id', $announcement->university_id) == $university->id ? 'selected' : '' }}>
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
                                            <option value="{{ $country->id }}" {{ old('country_id', $announcement->country_id) == $country->id ? 'selected' : '' }}>
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
                                    <select class="form-control select2bs4 @error('city_id') is-invalid @enderror" id="city_id" name="city_id" required data-placeholder="Select Country First">
                                        <option value="">Select City</option>
                                        @foreach($cities as $city)
                                            <option value="{{ $city->id }}" {{ old('city_id', $announcement->city_id) == $city->id ? 'selected' : '' }}>
                                                {{ $city->name }}
                                            </option>
                                        @endforeach
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
                                    @if($announcement->image)
                                        <div class="mb-2">
                                            <img src="{{ Storage::url($announcement->image) }}" alt="{{ $announcement->title }}" class="img-thumbnail" style="max-height: 150px;">
                                        </div>
                                    @endif
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input @error('image') is-invalid @enderror" id="image" name="image">
                                            <label class="custom-file-label" for="image">Choose new file</label>
                                        </div>
                                    </div>
                                    <small class="form-text text-muted">Leave empty to keep current image. Accepted formats: jpeg, png, jpg, gif. Max size: 2MB</small>
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
                                                <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" {{ old('is_active', $announcement->is_active) ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="is_active">Active</label>
                                            </div>
                                            <small class="form-text text-muted">Inactive announcements won't be displayed even if published.</small>
                                        </div>
                                        
                                        <!-- Publication Options -->
                                        <div class="form-group mt-3">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="publish_now" name="publish_option" value="now" class="custom-control-input" {{ old('publish_option') == 'now' ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="publish_now">Publish immediately</label>
                                                <input type="hidden" name="publish_now" value="0" id="publish_now_value">
                                            </div>
                                            <div class="custom-control custom-radio mt-2">
                                                <input type="radio" id="publish_later" name="publish_option" value="later" class="custom-control-input" 
                                                    {{ (old('publish_option') == 'later' || ($announcement->published_at && $announcement->published_at->gt(now()))) ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="publish_later">Schedule publication</label>
                                            </div>
                                            <div class="mt-3 {{ (old('publish_option') == 'later' || ($announcement->published_at && $announcement->published_at->gt(now()))) ? '' : 'd-none' }}" id="schedule_datetime_container">
                                                <div class="input-group date" id="published_at_picker" data-target-input="nearest">
                                                    <input type="text" class="form-control datetimepicker-input @error('published_at') is-invalid @enderror" 
                                                           data-target="#published_at_picker" name="published_at" id="published_at" 
                                                           value="{{ old('published_at', $announcement->published_at ? $announcement->published_at->format('Y-m-d H:i:s') : '') }}"/>
                                                    <div class="input-group-append" data-target="#published_at_picker" data-toggle="datetimepicker">
                                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                    </div>
                                                </div>
                                                @error('published_at')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="custom-control custom-radio mt-2">
                                                <input type="radio" id="publish_draft" name="publish_option" value="draft" class="custom-control-input" 
                                                    {{ (old('publish_option') == 'draft' || !$announcement->published_at) ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="publish_draft">Save as draft</label>
                                            </div>
                                            <div class="custom-control custom-radio mt-2">
                                                <input type="radio" id="keep_current" name="publish_option" value="keep" class="custom-control-input" 
                                                    {{ (old('publish_option') == 'keep' || ($announcement->published_at && $announcement->published_at->lte(now()))) ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="keep_current">Keep current publication status</label>
                                            </div>
                                        </div>
                                        
                                        <!-- Current Status -->
                                        <div class="mt-3 p-2 bg-light rounded">
                                            <small>
                                                <strong>Current Status:</strong> 
                                                <span class="badge badge-{{ $announcement->status_color }}">{{ $announcement->status_label }}</span>
                                                @if($announcement->published_at)
                                                    <br>
                                                    <strong>Published At:</strong> {{ $announcement->published_at->format('M d, Y H:i') }}
                                                @endif
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Announcement
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<!-- Tempusdominus Bootstrap 4 (datetime picker) -->
<link rel="stylesheet" href="{{ asset('plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
<style>
    .select2-container--bootstrap4 .select2-selection--single {
        height: calc(2.25rem + 2px) !important;
    }
</style>
@endsection

@section('scripts')
<!-- Select2 -->
<script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
<!-- Moment.js -->
<script src="{{ asset('plugins/moment/moment.min.js') }}"></script>
<!-- Tempusdominus Bootstrap 4 (datetime picker) -->
<script src="{{ asset('plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
<script>
$(function() {
    // Initialize Select2 Elements
    $('.select2bs4').select2({
        theme: 'bootstrap4',
        width: '100%'
    });
    
    // Initialize datetime picker
    $('#published_at_picker').datetimepicker({
        format: 'YYYY-MM-DD HH:mm:ss',
        icons: {
            time: 'far fa-clock'
        },
        minDate: moment()
    });
    
    // Handle publication option changes
    $('input[name="publish_option"]').on('change', function() {
        var selectedOption = $('input[name="publish_option"]:checked').val();
        
        if (selectedOption === 'later') {
            $('#schedule_datetime_container').removeClass('d-none');
            $('#publish_now_value').val(0);
        } else {
            $('#schedule_datetime_container').addClass('d-none');
            
            if (selectedOption === 'now') {
                $('#publish_now_value').val(1);
            } else {
                $('#publish_now_value').val(0);
            }
        }
    });
    
    // Trigger change to set initial state
    $('input[name="publish_option"]:checked').trigger('change');
    
    // Handle city dropdown based on country selection
    $('#country_id').on('change', function() {
        var countryId = $(this).val();
        var citySelect = $('#city_id');
        var currentCityId = "{{ $announcement->city_id }}";
        
        // Reset city dropdown
        citySelect.empty().prop('disabled', true);
        
        // Show loading indicator
        $('#city-loading').removeClass('d-none');
        
        if (countryId) {
            // Create and send AJAX request using native JavaScript
            var xhr = new XMLHttpRequest();
            xhr.open('GET', '/admin/announcements/get-cities?country_id=' + countryId, true);
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                        var cities = JSON.parse(xhr.responseText);
                        
                        // Clear dropdown
                        citySelect.empty();
                        citySelect.append('<option value="">Select City</option>');
                        
                        // Add cities to dropdown
                        if (cities && cities.length > 0) {
                            cities.forEach(function(city) {
                                var option = document.createElement('option');
                                option.value = city.id;
                                option.textContent = city.name;
                                citySelect.append(option);
                            });
                            
                            // If there was a previously selected city
                            if (currentCityId && countryId == "{{ $announcement->country_id }}") {
                                citySelect.val(currentCityId);
                            }
                        } else {
                            citySelect.append('<option value="">No cities found</option>');
                        }
                    } else {
                        citySelect.empty();
                        citySelect.append('<option value="">Error loading cities</option>');
                    }
                    
                 // Enable city dropdown
                 citySelect.prop('disabled', false);
                    
                    // Hide loading indicator
                    $('#city-loading').addClass('d-none');
                    
                    // Refresh Select2
                    citySelect.trigger('change');
                }
            };
            xhr.send();
        } else {
            citySelect.empty();
            citySelect.append('<option value="">Select Country First</option>');
            citySelect.prop('disabled', false);
            $('#city-loading').addClass('d-none');
            
            // Refresh Select2
            citySelect.trigger('change');
        }
    });

    // File input display
    $(document).on('change', '.custom-file-input', function() {
        var fileName = $(this).val().split('\\').pop();
        $(this).siblings('.custom-file-label').addClass('selected').html(fileName);
    });
});
</script>
@endsection