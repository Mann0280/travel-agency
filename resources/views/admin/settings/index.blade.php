@extends('admin.layouts.app')

@section('content')
<style>
    .card { background: white; border-radius: 0.75rem; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); padding: 1.5rem; border: 1px solid rgba(0,0,0,0.05); }
    .form-input { width: 100%; border: 1px solid #e5e7eb; border-radius: 0.5rem; padding: 0.625rem 0.875rem; transition: all 0.3s; font-size: 0.875rem; }
    .form-input:focus { outline: none; border-color: #a8894d; ring: 2px; ring-color: #a8894d/20; }
    .form-label { display: block; font-size: 0.875rem; font-weight: 500; text-gray-700; margin-bottom: 0.5rem; }
    .btn { display: inline-flex; align-items: center; justify-content: center; padding: 0.625rem 1.25rem; border-radius: 0.5rem; font-weight: 600; transition: all 0.3s; cursor: pointer; border: 1px solid transparent; font-size: 0.875rem; }
    .btn-primary { background-color: #a8894d; color: white; }
    .btn-primary:hover { background-color: #9d7c4f; transform: translateY(-1px); }
    .btn-outline { border-color: #e5e7eb; color: #374151; background: white; }
    .btn-outline:hover { background: #f9fafb; border-color: #d1d5db; }
    .sidebar-link.active { background-color: #a8894d; color: white; }
    .sidebar-link:not(.active):hover { background-color: #f9fafb; }
</style>

<div class="space-y-4 md:space-y-6">
    <!-- Header -->
    <header class="bg-white rounded-xl shadow-sm border border-gray-100 px-6 py-4">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-xl md:text-2xl font-bold text-[#17320b]">Settings</h1>
                <p class="text-gray-600 text-sm md:text-base">Manage system settings and configurations</p>
            </div>
        </div>
    </header>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Sidebar Menu -->
        <div class="lg:col-span-1">
            <div class="card p-3 sticky top-6">
                <nav class="space-y-1" id="settings-nav">
                    <button data-tab="general" class="sidebar-link active w-full flex items-center p-3 rounded-lg text-sm font-medium transition">
                        <i class="fas fa-cog w-5 mr-3"></i> General Settings
                    </button>
                    <button data-tab="seo" class="sidebar-link w-full flex items-center p-3 rounded-lg text-sm font-medium text-gray-600 transition">
                        <i class="fas fa-search w-5 mr-3"></i> SEO Settings
                    </button>
                    <button data-tab="appearance" class="sidebar-link w-full flex items-center p-3 rounded-lg text-sm font-medium text-gray-600 transition">
                        <i class="fas fa-palette w-5 mr-3"></i> Appearance
                    </button>
                    <button data-tab="notifications" class="sidebar-link w-full flex items-center p-3 rounded-lg text-sm font-medium text-gray-600 transition">
                        <i class="fas fa-bell w-5 mr-3"></i> Notifications
                    </button>
                    {{-- 
                    <button data-tab="api" class="sidebar-link w-full flex items-center p-3 rounded-lg text-sm font-medium text-gray-600 transition">
                        <i class="fas fa-key w-5 mr-3"></i> API Settings
                    </button>
                    --}}
                    <button data-tab="email" class="sidebar-link w-full flex items-center p-3 rounded-lg text-sm font-medium text-gray-600 transition">
                        <i class="fas fa-envelope w-5 mr-3"></i> Email Settings
                    </button>
                    {{-- 
                    <button data-tab="payment" class="sidebar-link w-full flex items-center p-3 rounded-lg text-sm font-medium text-gray-600 transition">
                        <i class="fas fa-credit-card w-5 mr-3"></i> Payment Settings
                    </button>
                    --}}
                    <button data-tab="social" class="sidebar-link w-full flex items-center p-3 rounded-lg text-sm font-medium text-gray-600 transition">
                        <i class="fas fa-share-alt w-5 mr-3"></i> Social Media
                    </button>
                </nav>
            </div>
        </div>

        <!-- Settings Content -->
        <div class="lg:col-span-3">
            <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div id="settingsContent">
                    <!-- General Settings -->
                    <div id="general-tab" class="tab-pane space-y-6">
                        <div class="card">
                            <h2 class="text-xl font-bold text-[#17320b] mb-6 border-b pb-4">General Settings</h2>
                            <div class="space-y-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="form-label">Site Name</label>
                                        <input type="text" name="site_name" class="form-input" value="{{ \App\Models\Setting::get('site_name', 'ZUBEEE Tours & Travels') }}">
                                    </div>
                                    <div>
                                        <label class="form-label">Site URL</label>
                                        <input type="url" name="site_url" class="form-input" value="{{ \App\Models\Setting::get('site_url', 'https://zubeee-travels.com') }}">
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="form-label">Contact Email</label>
                                        <input type="email" name="contact_email" class="form-input" value="{{ \App\Models\Setting::get('contact_email', 'info@zubeee.com') }}">
                                    </div>
                                    <div>
                                        <label class="form-label">Contact Phone</label>
                                        <input type="tel" name="phone" class="form-input" value="{{ \App\Models\Setting::get('phone', '+91 123 456 7890') }}">
                                    </div>
                                </div>

                                <div>
                                    <label class="form-label">Address</label>
                                    <textarea name="address" class="form-input" rows="3">{{ \App\Models\Setting::get('address', '123 Travel Street, City, Country') }}</textarea>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-4">
                                    <div>
                                        <label class="form-label">Default Currency</label>
                                        <select name="currency_code" class="form-input">
                                            <option value="INR" {{ \App\Models\Setting::get('currency_code') == 'INR' ? 'selected' : '' }}>Indian Rupee (₹)</option>
                                            <option value="USD" {{ \App\Models\Setting::get('currency_code') == 'USD' ? 'selected' : '' }}>US Dollar ($)</option>
                                            <option value="EUR" {{ \App\Models\Setting::get('currency_code') == 'EUR' ? 'selected' : '' }}>Euro (€)</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="form-label">Currency Position</label>
                                        <select name="currency_position" class="form-input">
                                            <option value="left" {{ \App\Models\Setting::get('currency_position') == 'left' ? 'selected' : '' }}>Left (₹1000)</option>
                                            <option value="right" {{ \App\Models\Setting::get('currency_position') == 'right' ? 'selected' : '' }}>Right (1000₹)</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SEO Settings -->
                    <div id="seo-tab" class="tab-pane space-y-6 hidden">
                        <div class="card">
                            <h2 class="text-xl font-bold text-[#17320b] mb-6 border-b pb-4">SEO Settings</h2>
                            <div class="space-y-4">
                                <div>
                                    <label class="form-label">Meta Title</label>
                                    <input type="text" name="meta_title" class="form-input" value="{{ \App\Models\Setting::get('meta_title') }}">
                                </div>
                                <div>
                                    <label class="form-label">Meta Description</label>
                                    <textarea name="meta_description" class="form-input" rows="4">{{ \App\Models\Setting::get('meta_description') }}</textarea>
                                </div>
                                <div>
                                    <label class="form-label">Meta Keywords</label>
                                    <input type="text" name="meta_keywords" class="form-input" value="{{ \App\Models\Setting::get('meta_keywords') }}" placeholder="travel, tours, india">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Appearance Settings -->
                    <div id="appearance-tab" class="tab-pane space-y-6 hidden">
                        <div class="card">
                            <div class="flex justify-between items-center mb-6 border-b pb-4">
                                <h2 class="text-xl font-bold text-[#17320b]">Appearance Settings</h2>
                                <button type="button" onclick="setBrandingDefaults()" class="btn btn-outline text-xs py-2">
                                    <i class="fas fa-undo mr-2"></i> Set Branding Defaults
                                </button>
                            </div>
                            <div class="space-y-6">
                                <div>
                                    <label class="form-label">Site Logo</label>
                                    <div class="flex items-center space-x-6">
                                        <div class="w-24 h-24 bg-gray-50 rounded-xl border border-dashed border-gray-300 flex items-center justify-center p-2">
                                            @if(\App\Models\Setting::get('site_logo'))
                                                <img src="{{ asset(\App\Models\Setting::get('site_logo')) }}" class="max-w-full max-h-full object-contain">
                                            @else
                                                <span class="text-2xl font-bold text-gray-300">Z</span>
                                            @endif
                                        </div>
                                        <div class="flex-1">
                                            <input type="file" name="site_logo" accept="image/*" class="form-input">
                                            <p class="text-xs text-gray-500 mt-2">Recommended: 200x200px, PNG format</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4">
                                    <div>
                                        <label class="form-label">Primary Color</label>
                                        <div class="flex items-center space-x-3">
                                            <input type="color" name="primary_color" class="h-10 w-20 cursor-pointer rounded border" value="{{ \App\Models\Setting::get('primary_color', '#17320b') }}">
                                            <span class="text-sm font-mono text-gray-600">{{ \App\Models\Setting::get('primary_color', '#17320B') }}</span>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="form-label">Secondary Color</label>
                                        <div class="flex items-center space-x-3">
                                            <input type="color" name="secondary_color" class="h-10 w-20 cursor-pointer rounded border" value="{{ \App\Models\Setting::get('secondary_color', '#a8894d') }}">
                                            <span class="text-sm font-mono text-gray-600">{{ \App\Models\Setting::get('secondary_color', '#A8894D') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Notifications Settings -->
                    <div id="notifications-tab" class="tab-pane space-y-6 hidden">
                        <div class="card">
                            <h2 class="text-xl font-bold text-[#17320b] mb-6 border-b pb-4">Notification Settings</h2>
                            <div class="space-y-4">
                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                                    <div>
                                        <h4 class="font-bold text-gray-800">New Booking Alerts</h4>
                                        <p class="text-sm text-gray-600">Receive email notification for every new booking</p>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" name="notif_new_booking" class="sr-only peer" {{ \App\Models\Setting::get('notif_new_booking') ? 'checked' : '' }}>
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#a8894d]"></div>
                                    </label>
                                </div>
                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                                    <div>
                                        <h4 class="font-bold text-gray-800">User Registration</h4>
                                        <p class="text-sm text-gray-600">Notify admin when a new user signs up</p>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" name="notif_user_reg" class="sr-only peer" {{ \App\Models\Setting::get('notif_user_reg') ? 'checked' : '' }}>
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#a8894d]"></div>
                                    </label>
                                </div>
                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                                    <div>
                                        <h4 class="font-bold text-gray-800">System Updates</h4>
                                        <p class="text-sm text-gray-600">Notifications about maintenance and updates</p>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" name="notif_sys_update" class="sr-only peer" {{ \App\Models\Setting::get('notif_sys_update') ? 'checked' : '' }}>
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#a8894d]"></div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- API Settings -->
                    <!-- API Settings (Hidden) -->
                    {{-- 
                    <div id="api-tab" class="tab-pane space-y-6 hidden">
                        <div class="card">
                            <h2 class="text-xl font-bold text-[#17320b] mb-6 border-b pb-4">API Configuration</h2>
                            <div class="space-y-4">
                                <div>
                                    <label class="form-label">API Base URL</label>
                                    <input type="url" name="api_url" class="form-input" value="{{ \App\Models\Setting::get('api_url', 'https://api.zubeee-travels.com/v1') }}">
                                </div>
                                <div>
                                    <label class="form-label">API Key</label>
                                    <div class="flex space-x-2">
                                        <input type="password" name="api_key" id="api_key_input" class="form-input" value="{{ \App\Models\Setting::get('api_key') }}">
                                        <button type="button" class="btn btn-outline" onclick="copyValue('api_key_input')"><i class="fas fa-copy"></i></button>
                                        <button type="button" class="btn btn-outline" onclick="generateKey('api_key_input')"><i class="fas fa-redo"></i></button>
                                    </div>
                                </div>
                                <div>
                                    <label class="form-label">Webhook Secret</label>
                                    <input type="text" name="webhook_url" class="form-input" value="{{ \App\Models\Setting::get('webhook_url') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    --}}

                    <!-- Placeholder sections for Email, Payment, Social -->
                    <div id="email-tab" class="tab-pane space-y-6 hidden">
                        <div class="card">
                            <h2 class="text-xl font-bold text-[#17320b] mb-6 border-b pb-4">Email Settings</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="form-label">SMTP Host</label>
                                    <input type="text" name="mail_host" class="form-input" value="{{ \App\Models\Setting::get('mail_host') }}">
                                </div>
                                <div>
                                    <label class="form-label">SMTP Port</label>
                                    <input type="text" name="mail_port" class="form-input" value="{{ \App\Models\Setting::get('mail_port') }}">
                                </div>
                                <div>
                                    <label class="form-label">Username</label>
                                    <input type="text" name="mail_username" class="form-input" value="{{ \App\Models\Setting::get('mail_username') }}">
                                </div>
                                <div>
                                    <label class="form-label">Password</label>
                                    <input type="password" name="mail_password" class="form-input" value="{{ \App\Models\Setting::get('mail_password') }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 
                    <div id="payment-tab" class="tab-pane space-y-6 hidden">
                        <div class="card">
                            <h2 class="text-xl font-bold text-[#17320b] mb-6 border-b pb-4">Payment Settings</h2>
                            <div class="space-y-4">
                                <h4 class="font-bold text-gray-700">Stripe Configuration</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="form-label">Public Key</label>
                                        <input type="text" name="stripe_key" class="form-input" value="{{ \App\Models\Setting::get('stripe_key') }}">
                                    </div>
                                    <div>
                                        <label class="form-label">Secret Key</label>
                                        <input type="password" name="stripe_secret" class="form-input" value="{{ \App\Models\Setting::get('stripe_secret') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    --}}

                    <div id="social-tab" class="tab-pane space-y-6 hidden">
                        <div class="card">
                            <h2 class="text-xl font-bold text-[#17320b] mb-6 border-b pb-4">Social Media</h2>
                            <div class="space-y-4">
                                <div>
                                    <label class="form-label">Facebook URL</label>
                                    <input type="url" name="facebook_url" class="form-input" value="{{ \App\Models\Setting::get('facebook_url') }}">
                                </div>
                                <div>
                                    <label class="form-label">Instagram URL</label>
                                    <input type="url" name="instagram_url" class="form-input" value="{{ \App\Models\Setting::get('instagram_url') }}">
                                </div>
                                <div>
                                    <label class="form-label">Twitter URL</label>
                                    <input type="url" name="twitter_url" class="form-input" value="{{ \App\Models\Setting::get('twitter_url') }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Global Save Button -->
                    <div class="pt-6 border-t flex justify-end">
                        <button type="submit" class="btn btn-primary px-10 py-4 shadow-lg flex items-center">
                            <i class="fas fa-save mr-3"></i> Synchronize All Settings
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Tab functionality
    document.querySelectorAll('.sidebar-link').forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();
            const tabId = this.getAttribute('data-tab');

            // Update active link
            document.querySelectorAll('.sidebar-link').forEach(btn => {
                btn.classList.remove('active');
                btn.classList.add('text-gray-600');
            });

            this.classList.add('active');
            this.classList.remove('text-gray-600');

            // Show selected tab content
            document.querySelectorAll('.tab-pane').forEach(content => {
                content.classList.add('hidden');
            });

            document.getElementById(tabId + '-tab').classList.remove('hidden');
        });
    });

    // Real-time color picker hex sync
    document.querySelectorAll('input[type="color"]').forEach(input => {
        input.addEventListener('input', function () {
            const hexSpan = this.nextElementSibling;
            if (hexSpan) {
                hexSpan.textContent = this.value.toUpperCase();
            }
        });
    });

    function copyValue(id) {
        const input = document.getElementById(id);
        const originalType = input.type;
        input.type = 'text';
        input.select();
        document.execCommand('copy');
        input.type = originalType;
        alert('Copied to clipboard!');
    }

    function generateKey(id) {
        const length = 32;
        const charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        let retVal = "sk_";
        for (let i = 0; i < length; ++i) {
            retVal += charset.charAt(Math.floor(Math.random() * charset.length));
        }
        document.getElementById(id).value = retVal;
    }

    function setBrandingDefaults() {
        if (confirm('Are you sure you want to reset branding colors to default (#17320b and #a8894d)?')) {
            const primaryInput = document.querySelector('input[name="primary_color"]');
            const secondaryInput = document.querySelector('input[name="secondary_color"]');
            
            if (primaryInput) {
                primaryInput.value = '#17320b';
                primaryInput.dispatchEvent(new Event('input'));
            }
            
            if (secondaryInput) {
                secondaryInput.value = '#a8894d';
                secondaryInput.dispatchEvent(new Event('input'));
            }
        }
    }
</script>
@endsection
