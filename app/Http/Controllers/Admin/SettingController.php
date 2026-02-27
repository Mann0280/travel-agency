<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all();
        $destinations = \App\Models\Destination::all();
        return view('admin.settings.index', compact('settings', 'destinations'));
    }

    public function update(Request $request)
    {
        // Handle Site Logo Upload
        if ($request->hasFile('site_logo')) {
            $file = $request->file('site_logo');
            $filename = 'logo_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('uploads/settings', $filename, 'public');
            Setting::set('site_logo', 'storage/' . $path);
        }

        // List of all expected keys (to handle checkboxes)
        $keys = [
            'site_name', 'site_url', 'contact_email', 'phone', 'address',
            'currency_code', 'currency_position',
            'primary_color', 'secondary_color',
            'notif_new_booking', 'notif_user_reg', 'notif_sys_update',
            'api_url', 'api_key', 'webhook_url',
            'meta_title', 'meta_description', 'meta_keywords',
            'mail_host', 'mail_port', 'mail_username', 'mail_password',
            'stripe_key', 'stripe_secret',
            'facebook_url', 'instagram_url', 'twitter_url',
            'package_categories'
        ];

        foreach ($keys as $key) {
            if ($request->has($key)) {
                $value = $request->input($key);
                // Handle checkbox value normalization
                if (str_starts_with($key, 'notif_')) {
                    $value = '1';
                }
                Setting::set($key, $value);
            } elseif (str_starts_with($key, 'notif_')) {
                // Handle unchecked checkboxes
                Setting::set($key, '0');
            }
        }

        return redirect()->route('admin.settings.index')->with('success', 'Settings synchronized successfully.');
    }
}