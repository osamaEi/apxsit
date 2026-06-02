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
                <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                    <i class="fas fa-check"></i>
                </div>
                <div class="flex-grow-1 mx-2" style="height: 2px; background-color: #28a745;"></div>
                <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                    <i class="fas fa-check"></i>
                </div>
                <div class="flex-grow-1 mx-2" style="height: 2px; background-color: #28a745;"></div>
                <div class="rounded-circle bg-warning text-white d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                    <span>5</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Upload Student's Documents</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.students.store.step5') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <!-- Photo -->
                                <div class="form-group">
                                    <label for="photo">Photo <span class="text-danger">*</span></label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input @error('photo') is-invalid @enderror" 
                                               id="photo" 
                                               name="photo"
                                               accept="image/*"
                                               required>
                                        <label class="custom-file-label" for="photo">Choose file</label>
                                    </div>
                                    <small class="form-text text-muted">Accepted formats: jpeg, png, jpg, gif. Max size: 2MB</small>
                                    @error('photo')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <!-- Passport -->
                                <div class="form-group">
                                    <label for="passport">Passport <span class="text-danger">*</span></label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input @error('passport') is-invalid @enderror" 
                                               id="passport" 
                                               name="passport"
                                               accept="image/*,.pdf"
                                               required>
                                        <label class="custom-file-label" for="passport">Choose file</label>
                                    </div>
                                    <small class="form-text text-muted">Accepted formats: jpeg, png, jpg, gif, pdf. Max size: 5MB</small>
                                    @error('passport')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <!-- High School Transcript -->
                                <div class="form-group">
                                    <label for="transcript">High School Transcript <span class="text-danger">*</span></label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input @error('transcript') is-invalid @enderror" 
                                               id="transcript" 
                                               name="transcript"
                                               accept="image/*,.pdf"
                                               required>
                                        <label class="custom-file-label" for="transcript">Choose file</label>
                                    </div>
                                    <small class="form-text text-muted">Accepted formats: jpeg, png, jpg, gif, pdf. Max size: 5MB</small>
                                    @error('transcript')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <!-- High School Diploma -->
                                <div class="form-group">
                                    <label for="diploma">High School Diploma</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input @error('diploma') is-invalid @enderror" 
                                               id="diploma" 
                                               name="diploma"
                                               accept="image/*,.pdf">
                                        <label class="custom-file-label" for="diploma">Choose file</label>
                                    </div>
                                    <small class="form-text text-muted">Accepted formats: jpeg, png, jpg, gif, pdf. Max size: 5MB</small>
                                    @error('diploma')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4">
                                <!-- Denklik -->
                                <div class="form-group">
                                    <label for="denklik">Denklik</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input @error('denklik') is-invalid @enderror" 
                                               id="denklik" 
                                               name="denklik"
                                               accept="image/*,.pdf">
                                        <label class="custom-file-label" for="denklik">Choose file</label>
                                    </div>
                                    <small class="form-text text-muted">Accepted formats: jpeg, png, jpg, gif, pdf. Max size: 5MB</small>
                                    @error('denklik')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <!-- Certificates -->
                                <div class="form-group">
                                    <label for="certificate">( Sat - Act - GCE ) Certificates</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input @error('certificate') is-invalid @enderror" 
                                               id="certificate" 
                                               name="certificate"
                                               accept="image/*,.pdf">
                                        <label class="custom-file-label" for="certificate">Choose file</label>
                                    </div>
                                    <small class="form-text text-muted">Accepted formats: jpeg, png, jpg, gif, pdf. Max size: 5MB</small>
                                    @error('certificate')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <!-- Other Documents -->
                                <div class="form-group">
                                    <label for="other_documents">Faculty Diploma</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input @error('other_documents') is-invalid @enderror" 
                                               id="other_documents" 
                                               name="other_documents"
                                               accept="image/*,.pdf">
                                        <label class="custom-file-label" for="other_documents">Choose file</label>
                                    </div>
                                    <small class="form-text text-muted">Accepted formats: jpeg, png, jpg, gif, pdf. Max size: 5MB</small>
                                    @error('other_documents')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mt-4">
                            <div class="col-md-12 text-right">
                                <a href="{{ route('admin.students.create.step4') }}" class="btn btn-secondary">Previous</a>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save"></i> Submit & Finish
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(function() {
    // File input display
    $(document).on('change', '.custom-file-input', function() {
        var fileName = $(this).val().split('\\').pop();
        $(this).siblings('.custom-file-label').addClass('selected').html(fileName || 'Choose file');
    });
});
</script>
@endsection