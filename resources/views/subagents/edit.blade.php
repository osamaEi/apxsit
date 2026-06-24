@extends('admin.index')

@section('content')
<div class="container-fluid">

    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show mb-3">
        <i class="fas fa-exclamation-circle mr-2"></i>Please fix the errors below.
        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
    </div>
    @endif

    {{-- Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-user-edit mr-2 text-primary"></i>Edit Subagent</h1>
            <small class="text-muted">
                <a href="{{ route('subagents.index') }}" class="text-muted">Subagents</a>
                <i class="fas fa-chevron-right mx-1" style="font-size:10px"></i>
                {{ $subagent->name }}
                <i class="fas fa-chevron-right mx-1" style="font-size:10px"></i> Edit
            </small>
        </div>
        <a href="{{ route('subagents.index') }}" class="btn btn-light btn-sm shadow-sm">
            <i class="fas fa-arrow-left mr-1"></i> Back
        </a>
    </div>

    <form method="POST" action="{{ route('subagents.update', $subagent->id) }}" enctype="multipart/form-data">
    @csrf @method('PUT')

    <div class="row">

        {{-- Left: preview --}}
        <div class="col-xl-3 col-lg-4 mb-4">
            <div class="sa-side-card shadow">
                <div style="height:5px;background:linear-gradient(135deg,#1a6bff,#0a3d99);border-radius:14px 14px 0 0"></div>
                <div class="sa-side-body">
                    @php $typeClass = $subagent->type === 'company' ? 'sa-pill-com' : 'sa-pill-ind'; @endphp
                    <div class="sa-preview-avatar" id="previewAvatar"
                         @if($subagent->photo) style="background-image:url('{{ asset('storage/'.$subagent->photo) }}');background-size:cover;" @endif>
                        @if(!$subagent->photo){{ strtoupper(substr($subagent->name,0,1)) }}@endif
                    </div>
                    <div class="sa-preview-name" id="previewName">{{ $subagent->name }}</div>
                    <div class="sa-preview-email" id="previewEmail">{{ $subagent->email }}</div>
                    <span class="sa-type-pill {{ $typeClass }}" id="previewType">
                        <i class="fas {{ $subagent->type === 'company' ? 'fa-building' : 'fa-user' }} mr-1" id="previewTypeIco"></i>
                        <span id="previewTypeText">{{ ucfirst($subagent->type) }}</span>
                    </span>
                    <div class="sa-preview-meta mt-3 w-100">
                        <div class="sa-meta-row">
                            <span class="sa-meta-lbl"><i class="fas fa-hashtag"></i> ID</span>
                            <span class="sa-meta-val">#{{ $subagent->id }}</span>
                        </div>
                        <div class="sa-meta-row">
                            <span class="sa-meta-lbl"><i class="fas fa-phone"></i> Phone</span>
                            <span class="sa-meta-val" id="previewPhone">{{ $subagent->phone ?: '—' }}</span>
                        </div>
                        <div class="sa-meta-row">
                            <span class="sa-meta-lbl"><i class="fas fa-globe"></i> Country</span>
                            <span class="sa-meta-val" id="previewCountry">{{ $subagent->country->name ?? '—' }}</span>
                        </div>
                        <div class="sa-meta-row">
                            <span class="sa-meta-lbl"><i class="fas fa-calendar-alt"></i> Joined</span>
                            <span class="sa-meta-val">{{ $subagent->created_at->format('d M Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right: form cards --}}
        <div class="col-xl-9 col-lg-8">

            {{-- Basic Info --}}
            <div class="sa-card shadow mb-4">
                <div class="sa-card-hd"><i class="fas fa-user mr-2"></i>Basic Information</div>
                <div class="sa-card-bd">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="sa-label">Name <span class="text-danger">*</span></label>
                            <div class="sa-input-wrap">
                                <span class="sa-ico"><i class="fas fa-user"></i></span>
                                <input type="text" name="name" id="fieldName"
                                       class="sa-input @error('name') is-invalid @enderror"
                                       value="{{ old('name',$subagent->name) }}" required>
                            </div>
                            @error('name')<div class="sa-err">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="sa-label">Email <span class="text-danger">*</span></label>
                            <div class="sa-input-wrap">
                                <span class="sa-ico"><i class="fas fa-envelope"></i></span>
                                <input type="email" name="email" id="fieldEmail"
                                       class="sa-input @error('email') is-invalid @enderror"
                                       value="{{ old('email',$subagent->email) }}" required>
                            </div>
                            @error('email')<div class="sa-err">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="sa-label">Phone <span class="text-danger">*</span></label>
                            <div class="sa-input-wrap">
                                <span class="sa-ico"><i class="fas fa-phone"></i></span>
                                <input type="text" name="phone" id="fieldPhone"
                                       class="sa-input @error('phone') is-invalid @enderror"
                                       value="{{ old('phone',$subagent->phone) }}" required>
                            </div>
                            @error('phone')<div class="sa-err">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="sa-label">Country <span class="text-danger">*</span></label>
                            <div class="sa-input-wrap">
                                <span class="sa-ico"><i class="fas fa-globe"></i></span>
                                <select name="country_id" id="fieldCountry"
                                        class="sa-input sa-select @error('country_id') is-invalid @enderror" required>
                                    <option value="">Select country</option>
                                    @foreach($countries as $c)
                                        <option value="{{ $c->id }}" {{ old('country_id',$subagent->country_id)==$c->id?'selected':'' }}>{{ $c->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('country_id')<div class="sa-err">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="sa-label">Type <span class="text-danger">*</span></label>
                            <div class="sa-input-wrap">
                                <span class="sa-ico"><i class="fas fa-tag"></i></span>
                                <select name="type" id="fieldType"
                                        class="sa-input sa-select @error('type') is-invalid @enderror" required>
                                    <option value="individual" {{ old('type',$subagent->type)=='individual'?'selected':'' }}>Individual</option>
                                    <option value="company"    {{ old('type',$subagent->type)=='company'?'selected':'' }}>Company</option>
                                </select>
                            </div>
                            @error('type')<div class="sa-err">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="sa-label">Profile Photo</label>
                            @if($subagent->photo)
                            <div class="mb-2">
                                <img src="{{ asset('storage/'.$subagent->photo) }}"
                                     style="height:48px;border-radius:8px;border:1.5px solid #e2e8f0">
                            </div>
                            @endif
                            <div class="sa-file-pick-wrap">
                                <label class="sa-file-btn" for="fieldPhoto">
                                    <i class="fas fa-camera mr-1"></i>
                                    <span id="photoName">{{ $subagent->photo ? 'Change photo…' : 'Choose photo…' }}</span>
                                </label>
                                <input type="file" name="photo" id="fieldPhoto" accept="image/*" style="display:none"
                                       onchange="previewPhoto(this)">
                            </div>
                            @error('photo')<div class="sa-err">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- Password --}}
            <div class="sa-card shadow mb-4">
                <div class="sa-card-hd d-flex align-items-center justify-content-between">
                    <span><i class="fas fa-key mr-2"></i>Change Password</span>
                    <label class="sa-toggle-label">
                        <input type="checkbox" id="pwToggle" class="sa-toggle-input">
                        <span class="sa-toggle-track"></span>
                        <span style="font-size:12px;color:#94a3b8" id="pwToggleTxt">Disabled</span>
                    </label>
                </div>
                <div class="sa-card-bd" id="pwFields" style="display:none">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="sa-label">New Password</label>
                            <div class="sa-input-wrap">
                                <span class="sa-ico"><i class="fas fa-lock"></i></span>
                                <input type="password" name="password" id="pw1"
                                       class="sa-input @error('password') is-invalid @enderror"
                                       placeholder="Leave blank to keep current">
                                <button type="button" class="sa-pw-eye" onclick="togglePw('pw1',this)"><i class="fas fa-eye"></i></button>
                            </div>
                            @error('password')<div class="sa-err">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="sa-label">Confirm Password</label>
                            <div class="sa-input-wrap">
                                <span class="sa-ico"><i class="fas fa-lock"></i></span>
                                <input type="password" name="password_confirmation" id="pw2"
                                       class="sa-input" placeholder="Confirm new password">
                                <button type="button" class="sa-pw-eye" onclick="togglePw('pw2',this)"><i class="fas fa-eye"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Commissions --}}
            <div class="sa-card shadow mb-4">
                <div class="sa-card-hd"><i class="fas fa-percentage mr-2"></i>Contracted Countries & Commissions</div>
                <div class="sa-card-bd">
                    <div class="sa-comm-table">
                        <div class="sa-comm-head">
                            <span>Country</span><span>Commission %</span><span></span>
                        </div>
                        <div id="commRows">
                            @if($countryCommissions->count())
                                @foreach($countryCommissions as $comm)
                                <div class="sa-comm-row">
                                    <select name="contracted_countries[]" class="sa-input sa-select">
                                        <option value="">Select country</option>
                                        @foreach($countries as $c)
                                            <option value="{{ $c->id }}" {{ $comm->country_id==$c->id?'selected':'' }}>{{ $c->name }}</option>
                                        @endforeach
                                    </select>
                                    <input type="number" name="commission_rates[]" class="sa-input"
                                           min="0" max="100" step="0.01" value="{{ $comm->commission_rate }}" placeholder="e.g. 5.5">
                                    <button type="button" class="sa-btn-rm" onclick="removeCommRow(this)">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                @endforeach
                            @else
                                <div class="sa-comm-row">
                                    <select name="contracted_countries[]" class="sa-input sa-select">
                                        <option value="">Select country</option>
                                        @foreach($countries as $c)
                                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                                        @endforeach
                                    </select>
                                    <input type="number" name="commission_rates[]" class="sa-input"
                                           min="0" max="100" step="0.01" placeholder="e.g. 5.5">
                                    <button type="button" class="sa-btn-rm" onclick="removeCommRow(this)" disabled>
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            @endif
                        </div>
                        <button type="button" class="sa-add-row-btn mt-2" onclick="addCommRow()">
                            <i class="fas fa-plus mr-1"></i> Add Country
                        </button>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="d-flex align-items-center justify-content-between">
                <a href="{{ route('subagents.index') }}" class="sa-btn-cancel">
                    <i class="fas fa-times mr-1"></i> Cancel
                </a>
                <button type="submit" class="sa-btn-save">
                    <i class="fas fa-save mr-1"></i> Save Changes
                </button>
            </div>
        </div>
    </div>
    </form>
</div>

@include('subagents._styles')

<style>
.sa-toggle-label { display:flex; align-items:center; gap:8px; cursor:pointer; margin:0; }
.sa-toggle-input { display:none; }
.sa-toggle-track { width:36px; height:20px; border-radius:10px; background:#e2e8f0; position:relative; transition:background .2s; flex-shrink:0; }
.sa-toggle-track::after { content:''; position:absolute; top:3px; left:3px; width:14px; height:14px; border-radius:50%; background:#fff; transition:left .2s; box-shadow:0 1px 3px rgba(0,0,0,.2); }
.sa-toggle-input:checked + .sa-toggle-track { background:#1a6bff; }
.sa-toggle-input:checked + .sa-toggle-track::after { left:19px; }
</style>

<script>
const COUNTRIES_MAP = {
    @foreach($countries as $c)
    "{{ $c->id }}": "{{ $c->name }}",
    @endforeach
};

document.getElementById('fieldName').addEventListener('input', function() {
    const v = this.value.trim();
    const av = document.getElementById('previewAvatar');
    if (!av.style.backgroundImage) av.textContent = v ? v[0].toUpperCase() : '?';
    document.getElementById('previewName').textContent = v || 'Subagent Name';
});
document.getElementById('fieldEmail').addEventListener('input', function() {
    document.getElementById('previewEmail').textContent = this.value || '—';
});
document.getElementById('fieldPhone').addEventListener('input', function() {
    document.getElementById('previewPhone').textContent = this.value || '—';
});
document.getElementById('fieldCountry').addEventListener('change', function() {
    document.getElementById('previewCountry').textContent = COUNTRIES_MAP[this.value] || '—';
});
document.getElementById('fieldType').addEventListener('change', function() {
    const pill = document.getElementById('previewType');
    const ico  = document.getElementById('previewTypeIco');
    const txt  = document.getElementById('previewTypeText');
    if (this.value === 'company') {
        pill.className = 'sa-type-pill sa-pill-com';
        ico.className  = 'fas fa-building mr-1';
        txt.textContent = 'Company';
    } else {
        pill.className = 'sa-type-pill sa-pill-ind';
        ico.className  = 'fas fa-user mr-1';
        txt.textContent = 'Individual';
    }
});

function previewPhoto(input) {
    document.getElementById('photoName').textContent = input.files[0]?.name || 'Choose photo…';
    if (input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            const av = document.getElementById('previewAvatar');
            av.style.backgroundImage = 'url(' + e.target.result + ')';
            av.style.backgroundSize  = 'cover';
            av.textContent = '';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// Password toggle
document.getElementById('pwToggle').addEventListener('change', function() {
    const fields = document.getElementById('pwFields');
    const txt    = document.getElementById('pwToggleTxt');
    fields.style.display = this.checked ? '' : 'none';
    txt.textContent = this.checked ? 'Enabled' : 'Disabled';
    if (!this.checked) { document.getElementById('pw1').value = ''; document.getElementById('pw2').value = ''; }
});

function togglePw(id, btn) {
    const inp = document.getElementById(id);
    inp.type = inp.type === 'password' ? 'text' : 'password';
    btn.querySelector('i').classList.toggle('fa-eye');
    btn.querySelector('i').classList.toggle('fa-eye-slash');
}

const countryOptions = `@foreach($countries as $c)<option value="{{ $c->id }}">{{ $c->name }}</option>@endforeach`;

function addCommRow() {
    const row = document.createElement('div');
    row.className = 'sa-comm-row';
    row.innerHTML = `
        <select name="contracted_countries[]" class="sa-input sa-select">
            <option value="">Select country</option>${countryOptions}
        </select>
        <input type="number" name="commission_rates[]" class="sa-input" min="0" max="100" step="0.01" placeholder="e.g. 5.5">
        <button type="button" class="sa-btn-rm" onclick="removeCommRow(this)">
            <i class="fas fa-times"></i>
        </button>`;
    document.getElementById('commRows').appendChild(row);
    updateRemoveBtns();
}

function removeCommRow(btn) {
    btn.closest('.sa-comm-row').remove();
    updateRemoveBtns();
}

function updateRemoveBtns() {
    const rows = document.querySelectorAll('#commRows .sa-comm-row');
    rows.forEach(r => { r.querySelector('.sa-btn-rm').disabled = rows.length === 1; });
}

updateRemoveBtns();
</script>
@endsection
