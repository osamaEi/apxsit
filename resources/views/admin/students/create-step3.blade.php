@extends('admin.index')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 mb-4">
            <h4>Please Follow The Steps To Add New Student</h4>
        </div>
    </div>
    
    <div class="row mb-4">
        <div class="col-12 text-center">
            <div class="d-flex align-items-center justify-content-center">
                <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                    <i class="fas fa-check"></i>
                </div>
                <div class="flex-grow-1 mx-2" style="height: 2px; background-color: #28a745;"></div>
                <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                    <i class="fas fa-check"></i>
                </div>
                <div class="flex-grow-1 mx-2" style="height: 2px; background-color: #28a745;"></div>
                <div class="rounded-circle bg-warning text-white d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                    <span>3</span>
                </div>
                <div class="flex-grow-1 mx-2" style="height: 2px; background-color: #ddd;"></div>
                <div class="rounded-circle bg-light text-dark d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; border: 1px solid #ddd;">
                    <span>4</span>
                </div>
                <div class="flex-grow-1 mx-2" style="height: 2px; background-color: #ddd;"></div>
                <div class="rounded-circle bg-light text-dark d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; border: 1px solid #ddd;">
                    <span>5</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Personal information</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.students.store.step3') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <!-- First Name -->
                                <div class="form-group">
                                    <label for="first_name">First name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('first_name') is-invalid @enderror" 
                                           id="first_name" 
                                           name="first_name" 
                                           value="{{ old('first_name', session('student_data.personal.first_name')) }}"
                                           placeholder="Student first name as shown in Passport"
                                           required>
                                    @error('first_name')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <!-- Last Name -->
                                <div class="form-group">
                                    <label for="last_name">Last name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('last_name') is-invalid @enderror" 
                                           id="last_name" 
                                           name="last_name" 
                                           value="{{ old('last_name', session('student_data.personal.last_name')) }}"
                                           placeholder="Student Last name as shown in Passport"
                                           required>
                                    @error('last_name')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <!-- Email -->
                                <div class="form-group">
                                    <label for="email">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" 
                                           name="email" 
                                           value="{{ old('email', session('student_data.personal.email')) }}"
                                           placeholder="Your valid email..."
                                           required>
                                    @error('email')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div> 
                            </div>
                            
                            <div class="col-md-6">
                                <!-- Phone Number -->
                                <div class="form-group">
                                    <label for="phone_number">Phone Number <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <select class="form-control select2-country" name="country_code" style="width: 220px;">
                                                @foreach($countries as $country)
                                                    <option 
                                                        value="{{ $country->phone_code }}"
                                                        data-iso="{{ strtolower($country->iso2) }}"
                                                        {{ old('country_code', session('student_data.personal.country_code', '+967')) == $country->phone_code ? 'selected' : '' }}
                                                    >
                                                        {{ $country->name }} ({{ $country->phone_code }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <input 
                                            type="text" 
                                            class="form-control @error('phone_number') is-invalid @enderror"
                                            id="phone_number"
                                            name="phone_number"
                                            value="{{ old('phone_number', session('student_data.personal.phone_number')) }}"
                                            placeholder="Enter mobile number"
                                            required
                                        >
                                    </div>
                                    @error('phone_number')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <!-- Load Select2 for better dropdown UI -->
                                <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
                                <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
                                
                                <script>
                                $(document).ready(function() {
                                    $('.select2-country').select2({
                                        templateResult: formatCountry,
                                        templateSelection: formatCountry,
                                    });
                                
                                    function formatCountry(country) {
                                        if (!country.id) return country.text;
                                        
                                        const isoCode = $(country.element).data('iso');
                                        const $country = $(
                                            `<span>
                                                <img src="https://flagcdn.com/16x12/${isoCode}.png" width="16" height="12" style="margin-right: 5px;">
                                                ${country.text}
                                            </span>`
                                        );
                                        return $country;
                                    }
                                });
                                </script>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <!-- Password -->
                                <div class="form-group">
                                    <label for="password">Password <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                           id="password" 
                                           name="password" 
                                           placeholder="Enter password"
                                           required>
                                    @error('password')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <!-- Password Confirmation -->
                                <div class="form-group">
                                    <label for="password_confirmation">Confirm Password <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" 
                                           id="password_confirmation" 
                                           name="password_confirmation" 
                                           placeholder="Confirm password"
                                           required>
                                    @error('password_confirmation')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <!-- Country of Residence -->
                                <div class="form-group">
                                    <label for="country_of_residence_id">Country of Residence <span class="text-danger">*</span></label>
                                    <select class="form-control select2 @error('country_of_residence_id') is-invalid @enderror" 
                                            id="country_of_residence_id" 
                                            name="country_of_residence_id"
                                            required>
                                        <option value="">Please Select</option>
                                        @foreach($countries as $country)
                                            <option value="{{ $country->id }}" {{ old('country_of_residence_id', session('student_data.personal.country_of_residence_id')) == $country->id ? 'selected' : '' }}>
                                                {{ $country->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('country_of_residence_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <!-- Nationality -->
                                <div class="form-group">
                                    <label for="nationality_id">Nationality <span class="text-danger">*</span></label>
                                    <select class="form-control select2 @error('nationality_id') is-invalid @enderror" 
                                            id="nationality_id" 
                                            name="nationality_id"
                                            required>
                                        <option value="">Please Select</option>
                                        @foreach($countries as $country)
                                            <option value="{{ $country->id }}" {{ old('nationality_id', session('student_data.personal.nationality_id')) == $country->id ? 'selected' : '' }}>
                                                {{ $country->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('nationality_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <!-- Gender -->
                                <div class="form-group">
                                    <label for="gender">Gender <span class="text-danger">*</span></label>
                                    <select class="form-control @error('gender') is-invalid @enderror" 
                                            id="gender" 
                                            name="gender"
                                            required>
                                        @foreach($genders as $key => $value)
                                            <option value="{{ $key }}" {{ old('gender', session('student_data.personal.gender')) == $key ? 'selected' : '' }}>
                                                {{ $value }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('gender')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <!-- Marital Status -->
                                <div class="form-group">
                                    <label for="marital_status">Marital Status <span class="text-danger">*</span></label>
                                    <select class="form-control @error('marital_status') is-invalid @enderror" 
                                            id="marital_status" 
                                            name="marital_status"
                                            required>
                                        @foreach($maritalStatuses as $key => $value)
                                            <option value="{{ $key }}" {{ old('marital_status', session('student_data.personal.marital_status')) == $key ? 'selected' : '' }}>
                                                {{ $value }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('marital_status')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <hr class="my-4">
                        <h5 class="mb-4">Family information</h5>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <!-- Father Name -->
                                <div class="form-group">
                                    <label for="father_name">Father name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('father_name') is-invalid @enderror" 
                                           id="father_name" 
                                           name="father_name" 
                                           value="{{ old('father_name', session('student_data.personal.father_name')) }}"
                                           placeholder="Student's father name"
                                           required>
                                    @error('father_name')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <!-- Mother Name -->
                                <div class="form-group">
                                    <label for="mother_name">Mother name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('mother_name') is-invalid @enderror" 
                                           id="mother_name" 
                                           name="mother_name" 
                                           value="{{ old('mother_name', session('student_data.personal.mother_name')) }}"
                                           placeholder="Student's mother name"
                                           required>
                                    @error('mother_name')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                  
                        
                        <div class="row mt-4">
                            <div class="col-md-12 text-right">
                                <a href="{{ route('admin.students.create.step2') }}" class="btn btn-secondary">Previous</a>
                                <button type="submit" class="btn btn-primary">Next</button>
                            </div>
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
@endsection

@section('scripts')
<!-- Select2 -->
<script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
<script>
$(function() {
    // Initialize Select2 Elements
    $('.select2').select2({
        theme: 'bootstrap4',
        width: '100%'
    });
});
</script>
@endsection