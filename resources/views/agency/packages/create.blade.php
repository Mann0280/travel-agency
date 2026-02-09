@extends('agency.layouts.app')

@section('title', 'Add Package')

@section('content')
<div class="space-y-6 p-4 md:p-6">
    <!-- Header with Breadcrumb -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <div class="flex items-center text-sm text-gray-600 mb-2">
                <a href="{{ route('agency.packages.index') }}" class="text-secondary hover:underline">Packages</a>
                <i class="fas fa-chevron-right mx-2"></i>
                <span>Add New Package</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-800">Add New Package</h1>
            <p class="text-gray-600">Create a new travel package for your agency</p>
        </div>
        <div>
            <a href="{{ route('agency.packages.index') }}" class="btn btn-outline">
                <i class="fas fa-arrow-left mr-2"></i> Back to List
            </a>
        </div>
    </div>

    <!-- Package Form -->
    <div class="card">
        <form action="{{ route('agency.packages.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            <input type="hidden" name="agency_id" value="{{ auth('agency')->id() }}">
            
            <!-- Basic Information -->
            <div>
                <h3 class="text-lg font-bold text-gray-800 mb-4">Basic Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Package Name *</label>
                        <input type="text" name="name" class="form-input" required placeholder="e.g., Spiti Valley Adventure" value="{{ old('name') }}">
                    </div>
                    <div>
                        <label class="form-label">Location *</label>
                        <input type="text" name="location" class="form-input" required placeholder="e.g., Spiti Valley, Himachal Pradesh" value="{{ old('location') }}">
                    </div>
                    <div class="md:col-span-2">
                        <label class="form-label">Description *</label>
                        <textarea name="description" rows="3" class="form-input" required placeholder="Describe the package...">{{ old('description') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Pricing & Duration -->
            <div>
                <h3 class="text-lg font-bold text-gray-800 mb-4">Pricing & Duration</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="form-label">Price (₹) *</label>
                        <input type="number" name="price" class="form-input" required min="0" step="1" placeholder="e.g., 16000" value="{{ old('price') }}">
                    </div>
                    <div>
                        <label class="form-label">Duration *</label>
                        <input type="text" name="duration" class="form-input" required placeholder="e.g., 7 days/6 nights" value="{{ old('duration') }}">
                    </div>
                    <div>
                        <label class="form-label">Duration (Days) *</label>
                        <input type="number" name="duration_days" class="form-input" required min="1" placeholder="e.g., 7" value="{{ old('duration_days') }}">
                    </div>
                </div>
            </div>

            <!-- Dates & Destination -->
            <div>
                <h3 class="text-lg font-bold text-gray-800 mb-4">Dates & Destination</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="form-label">Select Destination *</label>
                        <select name="destination_id" class="form-select w-full" required>
                            <option value="">Select Destination</option>
                            @foreach($destinations as $destination)
                                <option value="{{ $destination->id }}" {{ old('destination_id') == $destination->id ? 'selected' : '' }}>{{ $destination->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Start Date *</label>
                        <input type="date" name="start_date" class="form-input" required value="{{ old('start_date') }}">
                    </div>
                    <div>
                        <label class="form-label">End Date *</label>
                        <input type="date" name="end_date" class="form-input" required value="{{ old('end_date') }}">
                    </div>
                </div>
            </div>

            <!-- Image & Category -->
            <div>
                <h3 class="text-lg font-bold text-gray-800 mb-4">Media & Category</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Upload Image *</label>
                        <div class="space-y-3">
                            <input type="file" name="image" class="form-input p-2 w-full" accept="image/*" required>
                        </div>
                    </div>
                    <div>
                        <label class="form-label">Category *</label>
                        <select name="category" class="form-input" required>
                            <option value="">Select Category</option>
                            @foreach(['adventure', 'hill-station', 'cultural', 'beach', 'desert', 'trekking', 'nature', 'heritage', 'religious'] as $cat)
                                <option value="{{ $cat }}" {{ old('category') == $cat ? 'selected' : '' }}>{{ ucfirst($cat) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Status -->
            <div>
                <h3 class="text-lg font-bold text-gray-800 mb-4">Status</h3>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="form-label">Status</label>
                        <select name="status" class="form-input">
                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Departure Cities -->
            <div>
                <h3 class="text-lg font-bold text-gray-800 mb-4">Departure Cities</h3>
                <div id="departureCitiesContainer" class="space-y-2">
                    <div class="flex items-center space-x-2">
                        <input type="text" name="departure_cities[]" class="form-input" placeholder="e.g., Ahmedabad">
                        <button type="button" onclick="addDepartureCity()" class="btn btn-outline">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Available Months -->
            <div>
                <h3 class="text-lg font-bold text-gray-800 mb-4">Available Months</h3>
                <div class="grid grid-cols-2 md:grid-cols-6 gap-3">
                    @foreach(['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'] as $month)
                        <label class="flex items-center">
                            <input type="checkbox" name="available_months[]" value="{{ $month }}" class="rounded border-gray-300 text-secondary focus:ring-secondary mr-2">
                            <span class="text-gray-800">{{ substr($month, 0, 3) }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

             <!-- Itinerary -->
             <div>
                <h3 class="text-lg font-bold text-gray-800 mb-4">Itinerary</h3>
                <div id="itineraryContainer" class="space-y-4">
                    <div class="border border-gray-300 rounded-lg p-4 itinerary-day">
                        <div class="flex justify-between items-center mb-3">
                            <h4 class="font-semibold">Day 1</h4>
                            <button type="button" onclick="this.closest('.itinerary-day').remove()" class="btn btn-danger btn-sm">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Day Title *</label>
                            <input type="text" name="itinerary[0][day]" class="form-input" required placeholder="e.g., DAY 1: AHMEDABAD - UDAIPUR">
                        </div>
                        <div class="space-y-2 activities-container">
                            <div class="flex items-center space-x-2">
                                <input type="text" name="itinerary[0][activities][]" class="form-input" placeholder="e.g., Arrival At Ahmedabad">
                                <button type="button" onclick="addActivity(this)" class="btn btn-outline">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <button type="button" onclick="addItineraryDay()" class="btn btn-outline mt-3">
                    <i class="fas fa-plus mr-2"></i> Add Day
                </button>
            </div>

            <!-- Dynamic Lists (Inclusions, Exclusions, etc.) -->
            @foreach(['inclusions' => 'Inclusions', 'exclusions' => 'Exclusions', 'things_to_carry' => 'Things to Carry', 'terms_conditions' => 'Terms & Conditions'] as $field => $label)
                <div>
                    <h3 class="text-lg font-bold text-gray-800 mb-4">{{ $label }}</h3>
                    <div id="{{ $field }}Container" class="space-y-2">
                        <div class="flex items-center space-x-2">
                            <input type="text" name="{{ $field }}[]" class="form-input">
                            <button type="button" onclick="addItem('{{ $field }}')" class="btn btn-outline">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach

            <!-- Branches -->
            <div>
                <h3 class="text-lg font-bold text-gray-800 mb-4">Branches</h3>
                <div id="branchesContainer" class="space-y-4">
                    <div class="border border-gray-300 rounded-lg p-4 branch-item">
                        <div class="flex justify-between items-center mb-3">
                            <h4 class="font-semibold">Branch 1</h4>
                            <button type="button" onclick="this.closest('.branch-item').remove()" class="btn btn-danger btn-sm">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="form-label">City *</label>
                                <input type="text" name="branches[0][city]" class="form-input" required placeholder="e.g., Mumbai">
                            </div>
                            <div>
                                <label class="form-label">Phone Numbers *</label>
                                <input type="text" name="branches[0][phone]" class="form-input" required placeholder="e.g., +91 9876543210">
                            </div>
                            <div class="md:col-span-3">
                                <label class="form-label">Address *</label>
                                <textarea name="branches[0][address]" rows="2" class="form-input" required placeholder="Full Address"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <button type="button" onclick="addBranch()" class="btn btn-outline mt-3">
                    <i class="fas fa-plus mr-2"></i> Add Branch
                </button>
            </div>

            <!-- Contact Information -->
            <div>
                <h3 class="text-lg font-bold text-gray-800 mb-4">Contact Information</h3>
                <div class="space-y-4">
                    <div>
                        <label class="form-label">Website</label>
                        <input type="url" name="contact_info[website]" class="form-input" placeholder="http://example.com" value="{{ old('contact_info.website') }}">
                    </div>
                    <div>
                        <label class="form-label">Email</label>
                        <input type="email" name="contact_info[email]" class="form-input" placeholder="support@example.com" value="{{ old('contact_info.email') }}">
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end space-x-3 pt-6 border-t">
                <a href="{{ route('agency.packages.index') }}" class="btn btn-outline">Cancel</a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save mr-2"></i> Save Package
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function addDepartureCity() {
        const container = document.getElementById('departureCitiesContainer');
        const div = document.createElement('div');
        div.className = 'flex items-center space-x-2';
        div.innerHTML = `
            <input type="text" name="departure_cities[]" class="form-input" placeholder="e.g., Delhi">
            <button type="button" onclick="this.parentElement.remove()" class="btn btn-danger">
                <i class="fas fa-minus"></i>
            </button>
        `;
        container.appendChild(div);
    }

    function addItem(field) {
        const container = document.getElementById(field + 'Container');
        const div = document.createElement('div');
        div.className = 'flex items-center space-x-2';
        div.innerHTML = `
            <input type="text" name="${field}[]" class="form-input">
            <button type="button" onclick="this.parentElement.remove()" class="btn btn-danger">
                <i class="fas fa-minus"></i>
            </button>
        `;
        container.appendChild(div);
    }

    let dayCount = 1;
    function addItineraryDay() {
        const container = document.getElementById('itineraryContainer');
        const div = document.createElement('div');
        div.className = 'border border-gray-300 rounded-lg p-4 itinerary-day mt-4';
        div.innerHTML = `
            <div class="flex justify-between items-center mb-3">
                <h4 class="font-semibold">Day ${dayCount + 1}</h4>
                <button type="button" onclick="this.closest('.itinerary-day').remove()" class="btn btn-danger btn-sm">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
            <div class="mb-3">
                <label class="form-label">Day Title *</label>
                <input type="text" name="itinerary[${dayCount}][day]" class="form-input" required placeholder="e.g., DAY ${dayCount + 1}: Title">
            </div>
            <div class="space-y-2 activities-container">
                <div class="flex items-center space-x-2">
                    <input type="text" name="itinerary[${dayCount}][activities][]" class="form-input" placeholder="Activity">
                    <button type="button" onclick="addActivity(this)" class="btn btn-outline">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
            </div>
        `;
        container.appendChild(div);
        dayCount++;
    }

    function addActivity(btn) {
        const container = btn.closest('.activities-container');
        const dayIndex = container.closest('.itinerary-day').querySelector('input[name*="[day]"]').name.match(/\[(\d+)\]/)[1];
        const div = document.createElement('div');
        div.className = 'flex items-center space-x-2';
        div.innerHTML = `
            <input type="text" name="itinerary[${dayIndex}][activities][]" class="form-input" placeholder="Activity">
            <button type="button" onclick="this.parentElement.remove()" class="btn btn-danger">
                <i class="fas fa-minus"></i>
            </button>
        `;
        container.appendChild(div);
    }

    let branchCount = 1;
    function addBranch() {
        const container = document.getElementById('branchesContainer');
        const div = document.createElement('div');
        div.className = 'border border-gray-300 rounded-lg p-4 branch-item mt-4';
        div.innerHTML = `
            <div class="flex justify-between items-center mb-3">
                <h4 class="font-semibold">Branch ${branchCount + 1}</h4>
                <button type="button" onclick="this.closest('.branch-item').remove()" class="btn btn-danger btn-sm">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="form-label">City *</label>
                    <input type="text" name="branches[${branchCount}][city]" class="form-input" required placeholder="e.g., Mumbai">
                </div>
                <div>
                    <label class="form-label">Phone Numbers *</label>
                    <input type="text" name="branches[${branchCount}][phone]" class="form-input" required placeholder="e.g., +91 9876543210">
                </div>
                <div class="md:col-span-3">
                    <label class="form-label">Address *</label>
                    <textarea name="branches[${branchCount}][address]" rows="2" class="form-input" required placeholder="Full Address"></textarea>
                </div>
            </div>
        `;
        container.appendChild(div);
        branchCount++;
    }
</script>
@endpush
@endsection
