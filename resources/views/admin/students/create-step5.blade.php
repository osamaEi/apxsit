@extends('admin.index')

@section('content')
<div class="container-fluid">

    {{-- Page title --}}
    <div class="row">
        <div class="col-12 mb-4">
            <h4>Please Follow The Steps To Add New Student</h4>
        </div>
    </div>

    {{-- Step indicator --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex align-items-center justify-content-center">
                <div class="s5-step done"><i class="fas fa-check"></i></div>
                <div class="s5-line done"></div>
                <div class="s5-step done"><i class="fas fa-check"></i></div>
                <div class="s5-line done"></div>
                <div class="s5-step done"><i class="fas fa-check"></i></div>
                <div class="s5-line done"></div>
                <div class="s5-step done"><i class="fas fa-check"></i></div>
                <div class="s5-line done"></div>
                <div class="s5-step active">5</div>
            </div>
        </div>
    </div>

    {{-- Main card --}}
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Upload Student's Documents</h3>
                </div>
                <div class="card-body">

                    <form action="{{ route('admin.students.store.step5') }}"
                          method="POST" enctype="multipart/form-data">
                        @csrf

                        @php
                        $docs = [
                            ['key'=>'photo',           'label'=>'Photo',                        'accept'=>'image/*',      'req'=>true,  'hint'=>'jpeg, png, jpg, gif · max 2MB'],
                            ['key'=>'passport',        'label'=>'Passport',                     'accept'=>'image/*,.pdf', 'req'=>true,  'hint'=>'image or pdf · max 5MB'],
                            ['key'=>'transcript',      'label'=>'High School Transcript',       'accept'=>'image/*,.pdf', 'req'=>true,  'hint'=>'image or pdf · max 5MB'],
                            ['key'=>'diploma',         'label'=>'High School Diploma',          'accept'=>'image/*,.pdf', 'req'=>false, 'hint'=>'image or pdf · max 5MB'],
                            ['key'=>'denklik',         'label'=>'Denklik',                      'accept'=>'image/*,.pdf', 'req'=>false, 'hint'=>'image or pdf · max 5MB'],
                            ['key'=>'certificate',     'label'=>'(SAT / ACT / GCE) Certificates','accept'=>'image/*,.pdf','req'=>false, 'hint'=>'image or pdf · max 5MB'],
                            ['key'=>'other_documents', 'label'=>'Faculty Diploma',              'accept'=>'image/*,.pdf', 'req'=>false, 'hint'=>'image or pdf · max 5MB'],
                        ];
                        @endphp

                        <div class="row">
                            @foreach($docs as $doc)
                            <div class="col-md-6 col-lg-4 mb-4">

                                {{-- Label --}}
                                <p class="s5-label">
                                    {{ $doc['label'] }}
                                    @if($doc['req'])<span class="text-danger">*</span>@endif
                                </p>

                                {{-- Drop zone --}}
                                <div class="s5-dz" id="dz-{{ $doc['key'] }}"
                                     onclick="document.getElementById('f-{{ $doc['key'] }}').click()">
                                    <i class="fas fa-cloud-upload-alt s5-dz-ico"></i>
                                    <span class="s5-dz-txt">Click to choose file</span>
                                    <span class="s5-dz-hint">{{ $doc['hint'] }}</span>
                                </div>

                                {{-- Preview (hidden until file chosen) --}}
                                <div class="s5-prev" id="prev-{{ $doc['key'] }}" style="display:none">
                                    <div class="s5-thumb" id="thumb-{{ $doc['key'] }}">
                                        <i class="fas fa-file-alt s5-file-ico"></i>
                                    </div>
                                    <div class="s5-meta">
                                        <div class="s5-fname" id="fname-{{ $doc['key'] }}">—</div>
                                        <div class="s5-fsize" id="fsize-{{ $doc['key'] }}">—</div>
                                        <div class="s5-actions">
                                            <button type="button" class="s5-btn-ch"
                                                    onclick="document.getElementById('f-{{ $doc['key'] }}').click()">
                                                <i class="fas fa-sync-alt"></i> Change
                                            </button>
                                            <button type="button" class="s5-btn-rm"
                                                    onclick="s5Clear('{{ $doc['key'] }}')">
                                                <i class="fas fa-trash"></i> Remove
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                {{-- Hidden file input --}}
                                <input type="file"
                                       id="f-{{ $doc['key'] }}"
                                       name="{{ $doc['key'] }}"
                                       accept="{{ $doc['accept'] }}"
                                       {{ $doc['req'] ? 'required' : '' }}
                                       style="display:none"
                                       onchange="s5Pick(this,'{{ $doc['key'] }}')">

                                @error($doc['key'])
                                    <div class="s5-err">{{ $message }}</div>
                                @enderror

                            </div>
                            @endforeach
                        </div>

                        {{-- Navigation --}}
                        <div class="d-flex justify-content-between mt-3 pt-3" style="border-top:1px solid #dee2e6">
                            <a href="{{ route('admin.students.create.step4') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left mr-1"></i> Previous
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save mr-1"></i> Submit &amp; Finish
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- ── Styles (inside content section) ── --}}
<style>
/* step indicator */
.s5-step {
    width:40px; height:40px; border-radius:50%; flex-shrink:0;
    display:flex; align-items:center; justify-content:center;
    font-weight:700; font-size:14px;
}
.s5-step.done   { background:#28a745; color:#fff; }
.s5-step.active { background:#ffc107; color:#fff; }
.s5-line {
    flex:1; height:2px; min-width:20px; margin:0 6px;
}
.s5-line.done { background:#28a745; }
.s5-line.pending { background:#dee2e6; }

/* label */
.s5-label { font-size:13px; font-weight:600; color:#495057; margin-bottom:6px; }

/* drop zone */
.s5-dz {
    border:2px dashed #ced4da; border-radius:10px;
    padding:30px 12px; text-align:center; cursor:pointer;
    background:#f8fafc; transition:border-color .15s, background .15s;
    display:flex; flex-direction:column; align-items:center; gap:6px;
}
.s5-dz:hover { border-color:#1a6bff; background:#eef4ff; }
.s5-dz-ico  { font-size:30px; color:#adb5bd; }
.s5-dz:hover .s5-dz-ico { color:#1a6bff; }
.s5-dz-txt  { font-size:12px; font-weight:600; color:#6c757d; }
.s5-dz-hint { font-size:11px; color:#adb5bd; }

/* preview card */
.s5-prev {
    border:1.5px solid #e2e8f0; border-radius:10px;
    overflow:hidden; background:#fff;
}
.s5-thumb {
    height:110px; display:flex; align-items:center; justify-content:center;
    background:#f1f5f9; border-bottom:1px solid #e2e8f0; overflow:hidden;
}
.s5-thumb img { width:100%; height:100%; object-fit:cover; }
.s5-file-ico { font-size:38px; color:#94a3b8; }
.s5-meta  { padding:10px 12px; }
.s5-fname { font-size:12px; font-weight:600; color:#1e293b; word-break:break-all; margin-bottom:2px; }
.s5-fsize { font-size:11px; color:#94a3b8; margin-bottom:8px; }
.s5-actions { display:flex; gap:6px; }
.s5-btn-ch, .s5-btn-rm {
    flex:1; padding:5px 0; border-radius:7px; font-size:11px;
    font-weight:600; cursor:pointer; border:1.5px solid; transition:background .15s;
}
.s5-btn-ch { background:#eff6ff; border-color:#dbeafe; color:#2563eb; }
.s5-btn-ch:hover { background:#dbeafe; }
.s5-btn-rm { background:#fff5f5; border-color:#fee2e2; color:#dc2626; }
.s5-btn-rm:hover { background:#fee2e2; }

/* error */
.s5-err { font-size:11px; color:#dc2626; margin-top:4px; }

/* ── dark mode ── */
body.dark-mode .s5-label  { color:#a8b8d0; }
body.dark-mode .s5-dz     { background:#0d1e38; border-color:rgba(255,255,255,.12); }
body.dark-mode .s5-dz:hover { background:#102040; border-color:#1a6bff; }
body.dark-mode .s5-dz-ico { color:#3a5070; }
body.dark-mode .s5-dz:hover .s5-dz-ico { color:#6ea8fe; }
body.dark-mode .s5-dz-txt  { color:#4a6080; }
body.dark-mode .s5-dz-hint { color:#2a4060; }
body.dark-mode .s5-prev    { background:#0f2040; border-color:rgba(255,255,255,.08); }
body.dark-mode .s5-thumb   { background:#0d1e38; border-color:rgba(255,255,255,.06); }
body.dark-mode .s5-file-ico { color:#3a5070; }
body.dark-mode .s5-fname   { color:#c8d2e6; }
body.dark-mode .s5-fsize   { color:#4a6080; }
body.dark-mode .s5-btn-ch  { background:#0d2a4a; border-color:#1a4070; color:#6ea8fe; }
body.dark-mode .s5-btn-ch:hover { background:#1a3a60; }
body.dark-mode .s5-btn-rm  { background:#2a0d0d; border-color:#4a1010; color:#f87171; }
body.dark-mode .s5-btn-rm:hover { background:#3a1010; }
body.dark-mode .card-body > form > div[style*="border-top"] { border-color:rgba(255,255,255,.08) !important; }
</style>

{{-- ── Scripts (inside content section) ── --}}
<script>
function s5Pick(input, key) {
    const file = input.files[0];
    if (!file) return;

    document.getElementById('fname-' + key).textContent = file.name;
    document.getElementById('fsize-' + key).textContent = s5Fmt(file.size);

    const thumb = document.getElementById('thumb-' + key);
    if (file.type.startsWith('image/')) {
        const r = new FileReader();
        r.onload = e => { thumb.innerHTML = '<img src="' + e.target.result + '" alt="">'; };
        r.readAsDataURL(file);
    } else {
        const ico = file.type === 'application/pdf' ? 'fa-file-pdf' : 'fa-file-alt';
        thumb.innerHTML = '<i class="fas ' + ico + ' s5-file-ico"></i>';
    }

    document.getElementById('dz-'   + key).style.display = 'none';
    document.getElementById('prev-' + key).style.display = '';
}

function s5Clear(key) {
    document.getElementById('f-' + key).value = '';
    document.getElementById('thumb-' + key).innerHTML = '<i class="fas fa-file-alt s5-file-ico"></i>';
    document.getElementById('prev-' + key).style.display = 'none';
    document.getElementById('dz-'   + key).style.display = '';
}

function s5Fmt(b) {
    if (b < 1024)     return b + ' B';
    if (b < 1048576)  return (b/1024).toFixed(1) + ' KB';
    return (b/1048576).toFixed(1) + ' MB';
}
</script>

@endsection
