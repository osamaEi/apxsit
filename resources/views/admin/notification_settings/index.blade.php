@extends('admin.index')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-bell mr-1"></i> Notification Settings
                    </h3>
                </div>

                <form action="{{ route('admin.notification-settings.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="card-body">

                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show">
                                <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
                                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                            </div>
                        @endif

                        <p class="text-muted mb-4">
                            Control which events trigger notifications and which roles receive them.
                        </p>

                        @foreach($settings as $setting)
                        <div class="card card-secondary card-outline mb-3">
                            <div class="card-header d-flex align-items-center justify-content-between">
                                <div>
                                    <h5 class="mb-0">
                                        <i class="fas fa-bell-slash mr-1 text-muted"></i>
                                        {{ $setting->event_label }}
                                    </h5>
                                    @if($setting->description)
                                        <small class="text-muted">{{ $setting->description }}</small>
                                    @endif
                                </div>

                                <!-- Enable / Disable toggle -->
                                <div class="custom-control custom-switch ml-3">
                                    <input type="checkbox"
                                           class="custom-control-input event-toggle"
                                           id="enabled_{{ $setting->event_key }}"
                                           name="enabled_{{ $setting->event_key }}"
                                           data-target="roles_block_{{ $setting->event_key }}"
                                           {{ $setting->enabled ? 'checked' : '' }}>
                                    <label class="custom-control-label font-weight-bold"
                                           for="enabled_{{ $setting->event_key }}">
                                        {{ $setting->enabled ? 'Enabled' : 'Disabled' }}
                                    </label>
                                </div>
                            </div>

                            <div class="card-body {{ $setting->enabled ? '' : 'd-none' }}"
                                 id="roles_block_{{ $setting->event_key }}">
                                <label class="font-weight-bold mb-2">
                                    <i class="fas fa-users mr-1"></i> Notify these roles:
                                </label>
                                <div class="row">
                                    @foreach($allRoles as $role)
                                    <div class="col-md-3 col-6">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox"
                                                   class="custom-control-input"
                                                   id="{{ $setting->event_key }}_role_{{ $role }}"
                                                   name="roles_{{ $setting->event_key }}[]"
                                                   value="{{ $role }}"
                                                   {{ in_array($role, $setting->roles ?? []) ? 'checked' : '' }}>
                                            <label class="custom-control-label"
                                                   for="{{ $setting->event_key }}_role_{{ $role }}">
                                                {{ $role }}
                                            </label>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endforeach

                    </div><!-- /.card-body -->

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-1"></i> Save Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(function () {
    // Toggle roles block visibility when enable switch changes
    $('.event-toggle').on('change', function () {
        var targetId = $(this).data('target');
        var $label   = $(this).siblings('label');

        if ($(this).is(':checked')) {
            $('#' + targetId).removeClass('d-none');
            $label.text('Enabled');
        } else {
            $('#' + targetId).addClass('d-none');
            $label.text('Disabled');
        }
    });
});
</script>
@endsection
