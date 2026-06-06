<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationSetting extends Model
{
    protected $fillable = ['event_key', 'event_label', 'description', 'enabled', 'roles'];

    protected $casts = [
        'enabled' => 'boolean',
        'roles'   => 'array',
    ];

    // All known notification events with defaults
    public static function defaults(): array
    {
        return [
            [
                'event_key'   => 'new_application',
                'event_label' => 'New Application Created',
                'description' => 'Sent when a new application is submitted',
                'enabled'     => true,
                'roles'       => ['Register'],
            ],
            [
                'event_key'   => 'application_status_changed',
                'event_label' => 'Application Status Changed',
                'description' => 'Sent when an application status is updated',
                'enabled'     => true,
                'roles'       => ['Admin', 'Register'],
            ],
            [
                'event_key'   => 'file_uploaded',
                'event_label' => 'Acceptance Letter Uploaded',
                'description' => 'Sent when a first or final acceptance letter is uploaded',
                'enabled'     => true,
                'roles'       => ['Admin', 'Register', 'Sales', 'Employee'],
            ],
            [
                'event_key'   => 'payment_uploaded',
                'event_label' => 'Payment Confirmation Uploaded',
                'description' => 'Sent when a payment confirmation file is uploaded',
                'enabled'     => true,
                'roles'       => ['Register'],
            ],
        ];
    }

    public static function forEvent(string $eventKey): ?self
    {
        return static::where('event_key', $eventKey)->first();
    }

    public static function isEnabled(string $eventKey): bool
    {
        $setting = static::forEvent($eventKey);
        return $setting ? $setting->enabled : true;
    }

    public static function rolesFor(string $eventKey): array
    {
        $setting = static::forEvent($eventKey);
        return $setting ? ($setting->roles ?? []) : [];
    }
}
