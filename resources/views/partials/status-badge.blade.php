@php
    // Define status colors
    $statusColors = [
        'Pending Review' => 'badge-warning',
        'Awaiting App Fees Payment' => 'badge-info',
        'Awaiting Conditional Acceptance' => 'badge-info',
        'Conditional Acceptance' => 'badge-success',
        'Awaiting Payment' => 'badge-warning',
        'Paid' => 'badge-success',
        'Awaiting Final Acceptance' => 'badge-info',
        'Final Acceptance' => 'badge-success',
        'Awaiting Student Card' => 'badge-info',
        'Completed' => 'badge-success',
        'Invalid' => 'badge-danger',
        'Already Registered' => 'badge-dark',
        'Paid Another' => 'badge-dark',
        'Refused' => 'badge-danger',
        'Awaiting Student' => 'badge-warning',
        'Refund Request (Visa Rejected)' => 'badge-warning',
        'Refund Request (Other)' => 'badge-warning',
        'Refunded' => 'badge-dark',
        'Freeze' => 'badge-dark',
        'Free Scholarship' => 'badge-success',
        '100% Scholarship' => 'badge-success',
        'Cancelled' => 'badge-danger',
        'Quota Full' => 'badge-danger',
        'Student Duplicated' => 'badge-danger'
    ];
@endphp

<span class="badge {{ $statusColors[$status] ?? 'badge-secondary' }} badge-pill">
    {{ $status }}
</span>