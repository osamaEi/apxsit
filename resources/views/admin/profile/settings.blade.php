@extends('admin.index')


@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Profile Settings</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Profile Settings</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <div class="row">
            <!-- Profile Photo -->
            <div class="col-md-4">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">Profile Photo</h3>
                    </div>
                    <div class="card-body box-profile">
                        <div class="text-center mb-3">
                            @if(auth()->user()->photo)
                                <img class="profile-user-img img-fluid img-circle" 
                                     src="{{ asset('storage/' . auth()->user()->photo) }}" 
                                     alt="User profile picture">
                            @else
                                <img class="profile-user-img img-fluid img-circle" 
                                     src="{{ asset('admin/img/default-profile.png') }}" 
                                     alt="Default profile picture">
                            @endif
                        </div>
                        <form action="{{ route('admin.profile.updatePhoto') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            
                            <div class="form-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input @error('photo') is-invalid @enderror" 
                                           id="profile_photo" name="photo" accept="image/*" required>
                                    <label class="custom-file-label" for="profile_photo">Choose new photo</label>
                                    @error('photo')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <small class="form-text text-muted">
                                    Recommended size: 200x200 pixels. Maximum file size: 2MB.
                                </small>
                            </div>
                            
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fas fa-upload mr-1"></i> Upload New Photo
                            </button>
                        </form>
                        
                        @if(auth()->user()->profile_photo_path)
                            <form action="{{ route('admin.profile.removePhoto') }}" method="POST" class="mt-2">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-block">
                                    <i class="fas fa-trash mr-1"></i> Remove Photo
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Email & Password -->
            <div class="col-md-8">
                <!-- Update Email -->
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">Update Email</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.profile.updateEmail') }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div class="form-group">
                                <label for="current_email">Current Email</label>
                                <input type="email" class="form-control" id="current_email" 
                                       value="{{ auth()->user()->email }}" disabled>
                            </div>
                            
                            <div class="form-group">
                                <label for="email">New Email Address</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" required>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="email_confirmation">Confirm New Email Address</label>
                                <input type="email" class="form-control" 
                                       id="email_confirmation" name="email_confirmation" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="current_password_email">Current Password</label>
                                <input type="password" class="form-control @error('current_password_email') is-invalid @enderror" 
                                       id="current_password_email" name="current_password_email" required>
                                @error('current_password_email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                <small class="form-text text-muted">
                                    Enter your current password to confirm this change.
                                </small>
                            </div>
                            
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-envelope mr-1"></i> Update Email
                            </button>
                        </form>
                    </div>
                </div>
                
                <!-- Update Password -->
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">Update Password</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.profile.updatePassword') }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div class="form-group">
                                <label for="current_password">Current Password</label>
                                <input type="password" class="form-control @error('current_password') is-invalid @enderror" 
                                       id="current_password" name="current_password" required>
                                @error('current_password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="password">New Password</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       id="password" name="password" required>
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                <small class="form-text text-muted">
                                    Password must be at least 8 characters and include uppercase, lowercase, numbers, and special characters.
                                </small>
                            </div>
                            
                            <div class="form-group">
                                <label for="password_confirmation">Confirm New Password</label>
                                <input type="password" class="form-control" 
                                       id="password_confirmation" name="password_confirmation" required>
                            </div>
                            
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-lock mr-1"></i> Update Password
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        // Show selected filename in the custom file input
        $('.custom-file-input').on('change', function() {
            var fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').addClass("selected").html(fileName);
            
            // Preview the image
            if (this.files && this.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('.profile-user-img').attr('src', e.target.result);
                }
                reader.readAsDataURL(this.files[0]);
            }
        });
    });
</script>
@endpush
@endsection