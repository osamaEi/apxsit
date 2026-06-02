@extends('admin.index')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 mx-auto">
            <h2 class="mb-4">{{ __('Edit Subagent') }}</h2>

            <form method="POST" action="{{ route('subagents.update', $subagent->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row g-4">
                    <!-- Basic Information Section -->
                    <div class="col-md-6">
                        <div class="p-4 bg-light rounded shadow-sm">
                            <h4 class="mb-3 border-bottom pb-2">{{ __('Basic Information') }}</h4>

                            <div class="mb-3">
                                <label for="name" class="form-label">{{ __('Name') }} <span class="text-danger">*</span></label>
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $subagent->name) }}" required autocomplete="name" autofocus>
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">{{ __('Email Address') }} <span class="text-danger">*</span></label>
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', $subagent->email) }}" required autocomplete="email">
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="phone" class="form-label">{{ __('Phone Number') }} <span class="text-danger">*</span></label>
                                <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone', $subagent->phone) }}" required>
                                @error('phone')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="photo" class="form-label">{{ __('Profile Photo') }}</label>
                                @if($subagent->photo)
                                    <div class="mb-2">
                                        <img src="{{ asset('storage/' . $subagent->photo) }}" alt="{{ $subagent->name }}" class="img-thumbnail" style="max-height: 100px;">
                                    </div>
                                @endif
                                <input id="photo" type="file" class="form-control @error('photo') is-invalid @enderror" name="photo" accept="image/*">
                                <small class="form-text text-muted">{{ __('Upload a new profile photo to replace the existing one (JPG, PNG, GIF)') }}</small>
                                @error('photo')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="country_id" class="form-label">{{ __('Country') }} <span class="text-danger">*</span></label>
                                <select id="country_id" class="form-control @error('country_id') is-invalid @enderror" name="country_id" required>
                                    <option value="">Select a country</option>
                                    @foreach($countries as $country)
                                        <option value="{{ $country->id }}" {{ old('country_id', $subagent->country_id) == $country->id ? 'selected' : '' }}>
                                            {{ $country->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('country_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="type" class="form-label">{{ __('Type') }} <span class="text-danger">*</span></label>
                                <select id="type" class="form-control @error('type') is-invalid @enderror" name="type" required>
                                    <option value="individual" {{ old('type', $subagent->type) == 'individual' ? 'selected' : '' }}>Individual</option>
                                    <option value="company" {{ old('type', $subagent->type) == 'company' ? 'selected' : '' }}>Company</option>
                                </select>
                                @error('type')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Additional Information Section -->
                    <div class="col-md-6">
                        <div class="p-4 bg-light rounded shadow-sm">
                            <h4 class="mb-3 border-bottom pb-2">{{ __('Access & Commission') }}</h4>

                            <div class="mb-3">
                                <label for="password" class="form-label">{{ __('New Password') }}</label>
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" autocomplete="new-password">
                                <small class="form-text text-muted">{{ __('Leave blank to keep current password') }}</small>
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="password-confirm" class="form-label">{{ __('Confirm New Password') }}</label>
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" autocomplete="new-password">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">{{ __('Contracted Countries & Commissions') }}</label>
                                
                                <div class="contracted-countries-container border p-3 rounded bg-white">
                                    <div class="row mb-2">
                                        <div class="col-7">
                                            <label>{{ __('Country') }}</label>
                                        </div>
                                        <div class="col-4">
                                            <label>{{ __('Commission %') }}</label>
                                        </div>
                                        <div class="col-1"></div>
                                    </div>
                                    
                                    @if($countryCommissions->count() > 0)
                                        @foreach($countryCommissions as $commission)
                                            <div class="country-commission-row row mb-2">
                                                <div class="col-7">
                                                    <select name="contracted_countries[]" class="form-control">
                                                        <option value="">{{ __('Select Country') }}</option>
                                                        @foreach($countries as $country)
                                                            <option value="{{ $country->id }}" {{ $commission->country_id == $country->id ? 'selected' : '' }}>
                                                                {{ $country->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-4">
                                                    <input type="number" name="commission_rates[]" class="form-control" min="0" max="100" step="0.01" placeholder="e.g. 5.5" value="{{ $commission->commission_rate }}">
                                                </div>
                                                <div class="col-1">
                                                    <button type="button" class="btn btn-danger btn-sm remove-row">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="country-commission-row row mb-2">
                                            <div class="col-7">
                                                <select name="contracted_countries[]" class="form-control">
                                                    <option value="">{{ __('Select Country') }}</option>
                                                    @foreach($countries as $country)
                                                        <option value="{{ $country->id }}">{{ $country->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-4">
                                                <input type="number" name="commission_rates[]" class="form-control" min="0" max="100" step="0.01" placeholder="e.g. 5.5">
                                            </div>
                                            <div class="col-1">
                                                <button type="button" class="btn btn-danger btn-sm remove-row" disabled>
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                
                                <button type="button" class="btn btn-sm btn-success mt-2" id="add-country-btn">
                                    <i class="fas fa-plus"></i> {{ __('Add Country') }}
                                </button>
                                
                                @error('contracted_countries.*')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                
                                @error('commission_rates.*')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4 text-end">
                    <a href="{{ route('subagents.index') }}" class="btn btn-secondary me-2">
                        <i class="fas fa-arrow-left"></i> {{ __('Back') }}
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> {{ __('Update Subagent') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const addButton = document.getElementById('add-country-btn');
        const container = document.querySelector('.contracted-countries-container');
        
        if (addButton && container) {
            // Enable all remove buttons except when there's only one row
            updateRemoveButtons();
            
            // Add remove event to existing buttons
            container.querySelectorAll('.remove-row').forEach(btn => {
                btn.addEventListener('click', function() {
                    this.closest('.country-commission-row').remove();
                    updateRemoveButtons();
                });
            });
            
            // Add new country row
            addButton.addEventListener('click', function() {
                const template = container.querySelector('.country-commission-row').cloneNode(true);
                
                // Clear values
                template.querySelector('select[name="contracted_countries[]"]').value = '';
                template.querySelector('input[name="commission_rates[]"]').value = '';
                
                // Add remove event
                const removeBtn = template.querySelector('.remove-row');
                removeBtn.disabled = false;
                removeBtn.addEventListener('click', function() {
                    template.remove();
                    updateRemoveButtons();
                });
                
                // Append to container
                container.appendChild(template);
                updateRemoveButtons();
            });
            
            // Function to update remove buttons (disable if only one row exists)
            function updateRemoveButtons() {
                const rows = container.querySelectorAll('.country-commission-row');
                const removeButtons = container.querySelectorAll('.remove-row');
                
                if (rows.length === 1) {
                    removeButtons[0].disabled = true;
                } else {
                    removeButtons.forEach(btn => btn.disabled = false);
                }
            }
        }
    });
</script>
@endsection