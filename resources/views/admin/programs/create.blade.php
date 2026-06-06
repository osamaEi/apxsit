@extends('admin.index')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Add Program</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.programs.index') }}" class="btn btn-default">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.programs.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
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
                                
                                <!-- Language -->
                                <div class="form-group">
                                    <label for="language">Language <span class="text-danger">*</span></label>
                                    <select class="form-control @error('language') is-invalid @enderror" id="language" name="language" required disabled>
                                        <option value="">Select University first</option>
                                    </select>
                                    @error('language')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                            <!-- Department -->
                            <div class="form-group">
                                <label for="department">Department <span class="text-danger">*</span></label>
                                <select class="form-control @error('department') is-invalid @enderror" id="department" name="department" required disabled>
                                    <option value="">Select Language first</option>
                                @error('department')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                                </select>
                            </div>

                                <!-- Degree -->
                                <div class="form-group">
                                    <label for="degree">Degree <span class="text-danger">*</span></label>
                                    <select class="form-control @error('degree') is-invalid @enderror" id="degree" name="degree" required>
                                        <option value="">Select Degree</option>

                                        @foreach($degrees as $key )
                                            <option value="{{ $key->name }}" >
                                                {{ $key->name }}
                                            </option>
                                        @endforeach

                                    </select>

                                    @error('degree')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <!-- Shift Type -->
                                <div class="form-group">
                                    <label for="shift_type">Shift Type <span class="text-danger">*</span></label>
                                    <select class="form-control @error('shift_type') is-invalid @enderror" id="shift_type" name="shift_type" required>
                                        <option value="">Select Shift Type</option>
                                        @foreach($shiftTypes as $key => $value)
                                            <option value="{{ $key }}" {{ old('shift_type', 'Day') == $key ? 'selected' : '' }}>
                                                {{ $value }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('shift_type')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <!-- Price Before Discount -->
                                <div class="form-group">
                                    <label for="before_discount">Price Before Discount <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">$</span>
                                        </div>
                                        <input type="number" step="0.01" min="0" class="form-control @error('before_discount') is-invalid @enderror" id="before_discount" name="before_discount" value="{{ old('before_discount') }}" required>
                                    </div>
                                    @error('before_discount')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <!-- Price After Discount -->
                                <div class="form-group">
                                    <label for="after_discount">Price After Discount <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">$</span>
                                        </div>
                                        <input type="number" step="0.01" min="0" class="form-control @error('after_discount') is-invalid @enderror" id="after_discount" name="after_discount" value="{{ old('after_discount') }}" required>
                                    </div>
                                    <small class="form-text text-muted">Must be less than or equal to the price before discount.</small>
                                    @error('after_discount')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <!-- Cash Discount -->
                                <div class="form-group">
                                    <label for="cash_discount">Cash Discount</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">$</span>
                                        </div>
                                        <input type="number" step="0.01" min="0" class="form-control @error('cash_discount') is-invalid @enderror" id="cash_discount" name="cash_discount" value="{{ old('cash_discount', 0) }}">
                                    </div>
                                    <small class="form-text text-muted">Additional discount for cash payments.</small>
                                    @error('cash_discount')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <!-- Deposit Payment -->
                                <div class="form-group">
                                    <label for="deposit_payment">Deposit Payment</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">$</span>
                                        </div>
                                        <input type="number" step="0.01" min="0" class="form-control @error('deposit_payment') is-invalid @enderror" id="deposit_payment" name="deposit_payment" value="{{ old('deposit_payment', 0) }}">
                                    </div>
                                    <small class="form-text text-muted">Initial deposit required.</small>
                                    @error('deposit_payment')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <!-- Siblings Discount -->
                                <div class="form-group">
                                    <label for="siblings_discount">Siblings Discount</label>
                                    <div class="input-group">
                                        <input type="number" step="0.01" min="0" max="100" class="form-control @error('siblings_discount') is-invalid @enderror" id="siblings_discount" name="siblings_discount" value="{{ old('siblings_discount', 0) }}">
                                        <div class="input-group-append">
                                            <span class="input-group-text">%</span>
                                        </div>
                                    </div>
                                    <small class="form-text text-muted">Discount percentage for siblings.</small>
                                    @error('siblings_discount')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <!-- Status -->
                                <div class="form-group">
                                    <label for="status">Status <span class="text-danger">*</span></label>
                                    <select class="form-control @error('status') is-invalid @enderror" id="status" name="status" required>
                                        <option value="">Select Status</option>
                                        @foreach($statuses as $key => $value)
                                            <option value="{{ $key }}" {{ old('status', 'Active') == $key ? 'selected' : '' }}>
                                                {{ $value }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('status')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Save Program
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
@endsection

@section('scripts')
<!-- Select2 -->
<script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
<script>
$(function() {
    // Initialize Select2 Elements
    $('.select2bs4').select2({
        theme: 'bootstrap4'
    });

    // University → load languages (Select2 fires 'select2:select' not 'change')
    $('#university_id').on('select2:select select2:unselect change', function() {
        var universityId = $(this).val();
        var $language = $('#language');
        var $department = $('#department');

        $language.empty().append('<option value="">Select Language</option>').prop('disabled', true);
        $department.empty().append('<option value="">Select Language first</option>').prop('disabled', true);

        if (!universityId) return;

        $.get('{{ url("admin/programs/by-university") }}/' + universityId)
            .done(function(data) {
                if (data.languages && data.languages.length) {
                    $.each(data.languages, function(i, lang) {
                        $language.append('<option value="' + lang + '">' + lang + '</option>');
                    });
                    $language.prop('disabled', false);
                } else {
                    $language.append('<option value="" disabled>No languages found for this university</option>');
                }
            });
    });

    // Language → load departments
    $('#language').on('change', function() {
        var universityId = $('#university_id').val();
        var language = $(this).val();
        var $department = $('#department');

        $department.empty().append('<option value="">Select Department</option>').prop('disabled', true);

        if (!universityId || !language) return;

        $.get('{{ url("admin/programs/departments-by-language") }}/' + universityId + '/' + encodeURIComponent(language))
            .done(function(data) {
                if (data.departments && data.departments.length) {
                    $.each(data.departments, function(i, dept) {
                        $department.append('<option value="' + dept + '">' + dept + '</option>');
                    });
                }
                $department.prop('disabled', false);
            });
    });

    // Re-enable disabled selects before submit so their values are posted
    $('form').on('submit', function() {
        $('#language, #department').prop('disabled', false);
    });

    // Form validation and calculations
    $('#before_discount, #after_discount').on('input', function() {
        validateDiscounts();
    });

    function validateDiscounts() {
        var beforeDiscount = parseFloat($('#before_discount').val()) || 0;
        var afterDiscount = parseFloat($('#after_discount').val()) || 0;

        if (afterDiscount > beforeDiscount) {
            $('#after_discount').addClass('is-invalid');
            $('#after_discount').after('<div class="invalid-feedback">After discount amount cannot be greater than before discount.</div>');
        } else {
            $('#after_discount').removeClass('is-invalid');
            $('.invalid-feedback').remove();

            var discount = beforeDiscount - afterDiscount;
            var percentage = (discount / beforeDiscount) * 100;
            percentage = percentage.toFixed(2);

            if (beforeDiscount > 0) {
                $('#after_discount').parent().siblings('small').html('Discount: ' + percentage + '%');
            }
        }
    }
});
</script>
@endsection