<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NotificationSetting;
use Illuminate\Http\Request;

class NotificationSettingsController extends Controller
{
    public function index()
    {
        // Ensure all default events exist in DB
        foreach (NotificationSetting::defaults() as $default) {
            NotificationSetting::firstOrCreate(['event_key' => $default['event_key']], $default);
        }

        $settings = NotificationSetting::orderBy('id')->get();
        $allRoles = ['Admin', 'Register', 'Sales', 'Employee'];

        return view('admin.notification_settings.index', compact('settings', 'allRoles'));
    }

    public function update(Request $request)
    {
        $settings = NotificationSetting::all();

        foreach ($settings as $setting) {
            $key = $setting->event_key;

            $setting->enabled = $request->has("enabled_{$key}");
            $setting->roles   = $request->input("roles_{$key}", []);
            $setting->save();
        }

        return redirect()->route('admin.notification-settings.index')
            ->with('success', 'Notification settings saved successfully.');
    }
}
