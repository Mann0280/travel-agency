@extends('admin.layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header with Breadcrumb -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <div class="flex items-center text-sm text-gray-600 mb-2">
                <a href="{{ route('admin.users.index') }}" class="text-[#2d5f3f] hover:underline">Users</a>
                <i class="fas fa-chevron-right mx-2"></i>
                <span>Edit User</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-900">Edit User</h1>
            <p class="text-gray-600">Update user information and settings</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline">
                <i class="fas fa-arrow-left mr-2"></i> Back to List
            </a>
        </div>
    </div>
    
    <!-- User Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-2xl font-bold text-gray-900">{{ $bookingsCount }}</p>
            <p class="text-gray-600">Total Bookings</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-2xl font-bold text-gray-900">₹{{ number_format($totalSpent) }}</p>
            <p class="text-gray-600">Total Spent</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-2xl font-bold text-gray-900">{{ $user->created_at->format('d M Y') }}</p>
            <p class="text-gray-600">Member Since</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-2xl font-bold text-gray-900">{{ $wishlistCount }}</p>
            <p class="text-gray-600">Wishlist Items</p>
        </div>
    </div>
    
    <!-- User Form -->
    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('admin.users.update', $user) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            
            <!-- Basic Information -->
            <div>
                <h3 class="text-lg font-bold text-gray-900 mb-4">Basic Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                        <input type="text" name="name" class="form-input" required value="{{ old('name', $user->name) }}">
                        @error('name')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                        <input type="email" name="email" class="form-input" required value="{{ old('email', $user->email) }}">
                        @error('email')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                        <input type="tel" name="phone" class="form-input" value="{{ old('phone', $user->phone) }}">
                        @error('phone')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Date of Birth</label>
                        <input type="date" name="date_of_birth" class="form-input" value="{{ old('date_of_birth', $user->date_of_birth?->format('Y-m-d')) }}">
                        @error('date_of_birth')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>
            
            <!-- Address Information -->
            <div>
                <h3 class="text-lg font-bold text-gray-900 mb-4">Address Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Address Line 1</label>
                        <input type="text" name="address_line1" class="form-input" value="{{ old('address_line1', $user->address_line1) }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Address Line 2</label>
                        <input type="text" name="address_line2" class="form-input" value="{{ old('address_line2', $user->address_line2) }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">City</label>
                        <input type="text" name="city" class="form-input" value="{{ old('city', $user->city) }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">State</label>
                        <input type="text" name="state" class="form-input" value="{{ old('state', $user->state) }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Country</label>
                        <input type="text" name="country" class="form-input" value="{{ old('country', $user->country) }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">PIN Code</label>
                        <input type="text" name="pincode" class="form-input" value="{{ old('pincode', $user->pincode) }}">
                    </div>
                </div>
            </div>
            
            <!-- Account Settings -->
            <div>
                <h3 class="text-lg font-bold text-gray-900 mb-4">Account Settings</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Change Password</label>
                        <input type="password" name="password" class="form-input" placeholder="Leave blank to keep current">
                        @error('password')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-input" placeholder="Confirm new password">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">User Role *</label>
                        <select name="role" class="form-input" required>
                            <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>Regular User</option>
                            <option value="agent" {{ old('role', $user->role) == 'agent' ? 'selected' : '' }}>Travel Agent</option>
                            <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Administrator</option>
                        </select>
                        @error('role')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Account Status *</label>
                        <select name="status" class="form-input" required>
                            <option value="active" {{ old('status', $user->status) == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $user->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="pending" {{ old('status', $user->status) == 'pending' ? 'selected' : '' }}>Pending Verification</option>
                        </select>
                        @error('status')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>
            
            <!-- Preferences -->
            <div>
                <h3 class="text-lg font-bold text-gray-900 mb-4">Travel Preferences</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Preferred Travel Types</label>
                        <div class="flex flex-wrap gap-3">
                            @php
                                $userPreferences = old('preferences', $user->preferences ?? []);
                            @endphp
                            @foreach(['adventure' => 'Adventure', 'beach' => 'Beach', 'cultural' => 'Cultural', 'hill-station' => 'Hill Station', 'trekking' => 'Trekking'] as $value => $label)
                                <label class="flex items-center">
                                    <input type="checkbox" name="preferences[]" value="{{ $value }}" class="rounded border-gray-300 text-[#2d5f3f] focus:ring-[#2d5f3f] mr-2" {{ in_array($value, $userPreferences) ? 'checked' : '' }}>
                                    <span>{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Preferred Budget Range</label>
                            <select name="budget_range" class="form-input">
                                <option value="">Select budget range</option>
                                <option value="0-10000" {{ old('budget_range', $user->budget_range) == '0-10000' ? 'selected' : '' }}>Under ₹10,000</option>
                                <option value="10000-20000" {{ old('budget_range', $user->budget_range) == '10000-20000' ? 'selected' : '' }}>₹10,000 - ₹20,000</option>
                                <option value="20000-50000" {{ old('budget_range', $user->budget_range) == '20000-50000' ? 'selected' : '' }}>₹20,000 - ₹50,000</option>
                                <option value="50000+" {{ old('budget_range', $user->budget_range) == '50000+' ? 'selected' : '' }}>Above ₹50,000</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Preferred Duration</label>
                            <select name="preferred_duration" class="form-input">
                                <option value="">Select duration</option>
                                <option value="1-3" {{ old('preferred_duration', $user->preferred_duration) == '1-3' ? 'selected' : '' }}>1-3 Days</option>
                                <option value="4-7" {{ old('preferred_duration', $user->preferred_duration) == '4-7' ? 'selected' : '' }}>4-7 Days</option>
                                <option value="8-14" {{ old('preferred_duration', $user->preferred_duration) == '8-14' ? 'selected' : '' }}>8-14 Days</option>
                                <option value="15+" {{ old('preferred_duration', $user->preferred_duration) == '15+' ? 'selected' : '' }}>15+ Days</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Emergency Contact -->
            <div>
                <h3 class="text-lg font-bold text-gray-900 mb-4">Emergency Contact</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Emergency Contact Name</label>
                        <input type="text" name="emergency_name" class="form-input" value="{{ old('emergency_name', $user->emergency_name) }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Emergency Contact Phone</label>
                        <input type="tel" name="emergency_phone" class="form-input" value="{{ old('emergency_phone', $user->emergency_phone) }}">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Relationship</label>
                        <input type="text" name="emergency_relationship" class="form-input" value="{{ old('emergency_relationship', $user->emergency_relationship) }}">
                    </div>
                </div>
            </div>
            
            <!-- Form Actions -->
            <div class="flex justify-end space-x-3 pt-6 border-t">
                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this user?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash mr-2"></i> Delete User
                    </button>
                </form>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save mr-2"></i> Update User
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
