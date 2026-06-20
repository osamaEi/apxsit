@extends('admin.index')

@section('content')
@php
$roleMap = [
    'Admin'    => ['bg'=>'#fee2e2','text'=>'#dc2626','icon'=>'fa-crown'],
    'Sales'    => ['bg'=>'#dcfce7','text'=>'#16a34a','icon'=>'fa-chart-line'],
    'Register' => ['bg'=>'#fef9c3','text'=>'#ca8a04','icon'=>'fa-clipboard-list'],
    'Employee' => ['bg'=>'#dbeafe','text'=>'#2563eb','icon'=>'fa-user-tie'],
];
$rc = $roleMap[$user->role] ?? $roleMap['Employee'];
@endphp

<div class="container-fluid">

    {{-- Flash errors --}}
    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show mb-3">
        <i class="fas fa-exclamation-circle mr-2"></i>
        Please fix the errors below.
        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
    </div>
    @endif

    {{-- Breadcrumb --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-user-edit mr-2 text-primary"></i>Edit User
            </h1>
            <small class="text-muted">
                <a href="{{ route('users.index') }}" class="text-muted">Users</a>
                <i class="fas fa-chevron-right mx-1" style="font-size:10px"></i>
                <a href="{{ route('users.show', $user->id) }}" class="text-muted">{{ $user->name }}</a>
                <i class="fas fa-chevron-right mx-1" style="font-size:10px"></i>
                Edit
            </small>
        </div>
        <div class="d-flex" style="gap:8px">
            <a href="{{ route('users.show', $user->id) }}" class="btn btn-light btn-sm shadow-sm">
                <i class="fas fa-eye mr-1"></i> View Profile
            </a>
            <a href="{{ route('users.index') }}" class="btn btn-light btn-sm shadow-sm">
                <i class="fas fa-arrow-left mr-1"></i> Back
            </a>
        </div>
    </div>

    <form method="POST" action="{{ route('users.update', $user->id) }}">
    @csrf @method('PUT')

    <div class="row">

        {{-- Left: avatar card --}}
        <div class="col-xl-3 col-lg-4 mb-4">
            <div class="ued-side-card shadow">
                <div style="height:5px; background:{{ $rc['text'] }}; border-radius:14px 14px 0 0"></div>
                <div class="ued-side-body">
                    <div class="ued-avatar" style="background:{{ $rc['bg'] }}; color:{{ $rc['text'] }}" id="avatarCircle">
                        {{ strtoupper(substr($user->name,0,1)) }}
                    </div>
                    <div class="ued-uname" id="avatarName">{{ $user->name }}</div>
                    <span class="ued-role-pill" id="avatarRole" style="background:{{ $rc['bg'] }}; color:{{ $rc['text'] }}">
                        <i class="fas {{ $rc['icon'] }}" id="avatarRoleIcon"></i>
                        <span id="avatarRoleText">{{ $user->role }}</span>
                    </span>
                    <div class="ued-id-badge">#{{ $user->id }}</div>
                    <div class="ued-meta-small">
                        <span><i class="fas fa-calendar-alt mr-1"></i>Joined {{ $user->created_at->format('d M Y') }}</span>
                    </div>
                    <div class="ued-meta-small">
                        <span><i class="fas fa-sync-alt mr-1"></i>Updated {{ $user->updated_at->diffForHumans() }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right: form --}}
        <div class="col-xl-9 col-lg-8">

            {{-- Basic info --}}
            <div class="ued-card shadow mb-4">
                <div class="ued-card-hd">
                    <i class="fas fa-user mr-2"></i> Basic Information
                </div>
                <div class="ued-card-bd">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="ued-label">Full Name <span class="text-danger">*</span></label>
                            <div class="ued-input-group">
                                <span class="ued-input-icon"><i class="fas fa-user"></i></span>
                                <input type="text" name="name" id="nameInput"
                                       class="ued-input @error('name') is-invalid @enderror"
                                       value="{{ old('name', $user->name) }}" required
                                       placeholder="Full name">
                            </div>
                            @error('name')<div class="ued-err">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="ued-label">Email Address <span class="text-danger">*</span></label>
                            <div class="ued-input-group">
                                <span class="ued-input-icon"><i class="fas fa-envelope"></i></span>
                                <input type="email" name="email"
                                       class="ued-input @error('email') is-invalid @enderror"
                                       value="{{ old('email', $user->email) }}" required
                                       placeholder="Email address">
                            </div>
                            @error('email')<div class="ued-err">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="ued-label">User Role <span class="text-danger">*</span></label>
                            <div class="ued-input-group">
                                <span class="ued-input-icon"><i class="fas fa-shield-alt"></i></span>
                                <select name="role" id="roleSelect"
                                        class="ued-input ued-select @error('role') is-invalid @enderror" required>
                                    @foreach($roles as $role)
                                        <option value="{{ $role }}" {{ old('role',$user->role)==$role?'selected':'' }}>
                                            {{ $role }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('role')<div class="ued-err">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="ued-label">Member Since</label>
                            <div class="ued-input-group">
                                <span class="ued-input-icon"><i class="fas fa-calendar-alt"></i></span>
                                <input type="text" class="ued-input"
                                       value="{{ $user->created_at->format('Y-m-d H:i') }}" disabled>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Password section --}}
            <div class="ued-card shadow mb-4">
                <div class="ued-card-hd d-flex align-items-center justify-content-between">
                    <span><i class="fas fa-key mr-2"></i> Change Password</span>
                    <label class="ued-toggle-label">
                        <input type="checkbox" id="pwToggle" class="ued-toggle-input">
                        <span class="ued-toggle-track"></span>
                        <span class="ued-toggle-text" id="pwToggleText">Disabled</span>
                    </label>
                </div>
                <div class="ued-card-bd" id="pwFields" style="display:none">
                    <div class="alert alert-warning py-2 mb-3" style="font-size:12px">
                        <i class="fas fa-exclamation-triangle mr-1"></i>
                        Changing the password will log the user out of all devices.
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="ued-label">New Password</label>
                            <div class="ued-input-group">
                                <span class="ued-input-icon"><i class="fas fa-lock"></i></span>
                                <input type="password" name="password" id="pwInput"
                                       class="ued-input @error('password') is-invalid @enderror"
                                       placeholder="New password">
                                <button type="button" class="ued-pw-toggle" onclick="togglePw('pwInput',this)">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            @error('password')<div class="ued-err">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="ued-label">Confirm New Password</label>
                            <div class="ued-input-group">
                                <span class="ued-input-icon"><i class="fas fa-lock"></i></span>
                                <input type="password" name="password_confirmation" id="pwConfirm"
                                       class="ued-input" placeholder="Confirm password">
                                <button type="button" class="ued-pw-toggle" onclick="togglePw('pwConfirm',this)">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Form actions --}}
            <div class="d-flex align-items-center justify-content-between">
                <a href="{{ route('users.index') }}" class="ued-btn-cancel">
                    <i class="fas fa-times mr-1"></i> Cancel
                </a>
                <div class="d-flex" style="gap:8px">
                    <button type="reset" class="ued-btn-reset">
                        <i class="fas fa-undo mr-1"></i> Reset
                    </button>
                    <button type="submit" class="ued-btn-save">
                        <i class="fas fa-save mr-1"></i> Save Changes
                    </button>
                </div>
            </div>

        </div>
    </div>
    </form>
</div>

<style>
/* ── side card ── */
.ued-side-card { background:#fff; border-radius:14px; overflow:hidden; }
.ued-side-body {
    padding: 24px 20px;
    display: flex; flex-direction: column; align-items: center; text-align: center;
}
.ued-avatar {
    width: 80px; height: 80px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 32px; font-weight: 700; margin-bottom: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,.1);
}
.ued-uname { font-size: 16px; font-weight: 700; color: #1e293b; margin-bottom: 8px; }
.ued-role-pill {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 4px 12px; border-radius: 20px;
    font-size: 12px; font-weight: 700; margin-bottom: 10px;
}
.ued-id-badge {
    font-size: 12px; color: #94a3b8; font-weight: 600;
    background: #f1f5f9; padding: 3px 10px; border-radius: 20px;
    margin-bottom: 12px;
}
.ued-meta-small {
    font-size: 12px; color: #94a3b8; margin-bottom: 4px;
}

/* ── form cards ── */
.ued-card { background: #fff; border-radius: 14px; overflow: hidden; }
.ued-card-hd {
    padding: 13px 20px;
    background: linear-gradient(135deg, #1a6bff, #0a3d99);
    color: #fff; font-size: 13px; font-weight: 700;
}
.ued-card-bd { padding: 20px; }

/* inputs */
.ued-label { font-size: 12px; font-weight: 600; color: #475569; text-transform: uppercase; letter-spacing: .4px; margin-bottom: 6px; display: block; }
.ued-input-group { position: relative; display: flex; align-items: center; }
.ued-input-icon {
    position: absolute; left: 12px; color: #94a3b8; font-size: 13px; z-index: 1;
}
.ued-input {
    width: 100%; padding: 9px 12px 9px 36px;
    border: 1.5px solid #e2e8f0; border-radius: 9px;
    font-size: 13px; color: #1e293b; outline: none;
    background: #f8fafc; transition: border-color .15s;
}
.ued-input:focus { border-color: #1a6bff; background: #fff; }
.ued-input:disabled { background: #f1f5f9; color: #94a3b8; cursor: not-allowed; }
.ued-select { appearance: none; cursor: pointer; }
.ued-err { font-size: 11px; color: #dc2626; margin-top: 4px; }

/* password toggle button */
.ued-pw-toggle {
    position: absolute; right: 10px; background: none; border: none;
    color: #94a3b8; cursor: pointer; font-size: 13px; z-index: 1;
}
.ued-pw-toggle:hover { color: #475569; }

/* password section toggle */
.ued-toggle-label { display: flex; align-items: center; gap: 8px; cursor: pointer; margin: 0; }
.ued-toggle-input { display: none; }
.ued-toggle-track {
    width: 36px; height: 20px; border-radius: 10px;
    background: #e2e8f0; position: relative; transition: background .2s; flex-shrink: 0;
}
.ued-toggle-track::after {
    content: ''; position: absolute; top: 3px; left: 3px;
    width: 14px; height: 14px; border-radius: 50%;
    background: #fff; transition: left .2s;
    box-shadow: 0 1px 3px rgba(0,0,0,.2);
}
.ued-toggle-input:checked + .ued-toggle-track { background: #1a6bff; }
.ued-toggle-input:checked + .ued-toggle-track::after { left: 19px; }
.ued-toggle-text { font-size: 12px; color: #94a3b8; }

/* action buttons */
.ued-btn-cancel {
    padding: 9px 18px; border-radius: 9px; font-size: 13px; font-weight: 600;
    border: 1.5px solid #e2e8f0; color: #64748b; text-decoration: none;
    display: inline-flex; align-items: center; gap: 5px;
    transition: background .15s;
}
.ued-btn-cancel:hover { background: #f1f5f9; color: #334155; text-decoration: none; }
.ued-btn-reset {
    padding: 9px 18px; border-radius: 9px; font-size: 13px; font-weight: 600;
    border: 1.5px solid #fbbf24; color: #ca8a04; background: #fffbeb;
    cursor: pointer; display: inline-flex; align-items: center; gap: 5px;
    transition: opacity .15s;
}
.ued-btn-reset:hover { opacity: .8; }
.ued-btn-save {
    padding: 9px 22px; border-radius: 9px; font-size: 13px; font-weight: 700;
    background: linear-gradient(135deg, #1a6bff, #0a3d99); color: #fff; border: none;
    cursor: pointer; display: inline-flex; align-items: center; gap: 5px;
    box-shadow: 0 4px 12px rgba(26,107,255,.3); transition: opacity .15s;
}
.ued-btn-save:hover { opacity: .9; }
</style>

<script>
@php
$roleData = [
    'Admin'    => ['bg'=>'#fee2e2','text'=>'#dc2626','icon'=>'fa-crown'],
    'Sales'    => ['bg'=>'#dcfce7','text'=>'#16a34a','icon'=>'fa-chart-line'],
    'Register' => ['bg'=>'#fef9c3','text'=>'#ca8a04','icon'=>'fa-clipboard-list'],
    'Employee' => ['bg'=>'#dbeafe','text'=>'#2563eb','icon'=>'fa-user-tie'],
];
@endphp
const ROLE_DATA = @json($roleData);

document.addEventListener('DOMContentLoaded', function () {

    // Live preview: name → avatar initial + name label
    const nameInput  = document.getElementById('nameInput');
    const avatarCircle = document.getElementById('avatarCircle');
    const avatarName   = document.getElementById('avatarName');
    nameInput.addEventListener('input', function () {
        const v = this.value.trim();
        avatarCircle.textContent = v ? v[0].toUpperCase() : '?';
        avatarName.textContent   = v || 'User Name';
    });

    // Live preview: role → pill color + icon
    const roleSelect   = document.getElementById('roleSelect');
    const avatarRole   = document.getElementById('avatarRole');
    const avatarRoleText = document.getElementById('avatarRoleText');
    const avatarRoleIcon = document.getElementById('avatarRoleIcon');
    roleSelect.addEventListener('change', function () {
        const r = ROLE_DATA[this.value] || ROLE_DATA['Employee'];
        avatarRole.style.background = r.bg;
        avatarRole.style.color      = r.text;
        avatarRoleIcon.className    = 'fas ' + r.icon;
        avatarRoleText.textContent  = this.value;
    });

    // Password section toggle
    const pwToggle = document.getElementById('pwToggle');
    const pwFields = document.getElementById('pwFields');
    const pwToggleText = document.getElementById('pwToggleText');
    pwToggle.addEventListener('change', function () {
        if (this.checked) {
            pwFields.style.display = '';
            pwToggleText.textContent = 'Enabled';
        } else {
            pwFields.style.display = 'none';
            pwToggleText.textContent = 'Disabled';
            document.getElementById('pwInput').value   = '';
            document.getElementById('pwConfirm').value = '';
        }
    });
});

function togglePw(inputId, btn) {
    const inp = document.getElementById(inputId);
    const ico = btn.querySelector('i');
    if (inp.type === 'password') {
        inp.type = 'text';
        ico.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        inp.type = 'password';
        ico.classList.replace('fa-eye-slash', 'fa-eye');
    }
}
</script>

@endsection
