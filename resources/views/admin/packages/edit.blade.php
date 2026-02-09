@extends('admin.layouts.app')

@section('title', 'Edit Package')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Edit Package</h1>
            <p class="text-gray-600">Update package information</p>
        </div>
        <a href="{{ route('admin.packages.index') }}" class="btn btn-outline">
            <i class="fas fa-arrow-left mr-2"></i> Back to List
        </a>
    </div>

    <div class="card">
        <form action="{{ route('admin.packages.update', $package) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')
            
            <!-- Basic Information -->
            <div>
                <h3 class="text-lg font-bold text-gray-800 mb-4">Basic Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Package Name *</label>
                        <input type="text" name="name" class="form-input" required value="{{ old('name', $package->name) }}">
                    </div>
                    <div>
                        <label class="form-label">Location *</label>
                        <input type="text" name="location" class="form-input" required value="{{ old('location', $package->location) }}">
                    </div>
                    <div class="md:col-span-2">
                        <label class="form-label">Description *</label>
                        <textarea name="description" rows="3" class="form-input" required>{{ old('description', $package->description) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Pricing & Duration -->
            <div>
                <h3 class="text-lg font-bold text-gray-800 mb-4">Pricing & Duration</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="form-label">Price (₹) *</label>
                        <input type="number" name="price" class="form-input" required min="0" step="1" value="{{ old('price', $package->price) }}">
                    </div>
                    <div>
                        <label class="form-label">Duration *</label>
                        <input type="text" name="duration" class="form-input" required value="{{ old('duration', $package->duration) }}">
                    </div>
                    <div>
                        <label class="form-label">Duration (Days) *</label>
                        <input type="number" name="duration_days" class="form-input" required min="1" value="{{ old('duration_days', $package->duration_days) }}">
                    </div>
                </div>
            </div>

            <!-- Agency & Dates -->
            <div>
                <h3 class="text-lg font-bold text-gray-800 mb-4">Agency & Dates</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="form-label">Select Agency *</label>
                        <select name="agency_id" class="form-select w-full" required>
                            @foreach($agencies as $agency)
                                <option value="{{ $agency->id }}" {{ old('agency_id', $package->agency_id) == $agency->id ? 'selected' : '' }}>{{ $agency->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Select Destination *</label>
                        <select name="destination_id" class="form-select w-full" required>
                            @foreach($destinations as $destination)
                                <option value="{{ $destination->id }}" {{ old('destination_id', $package->destination_id) == $destination->id ? 'selected' : '' }}>{{ $destination->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Start Date *</label>
                        <input type="date" name="start_date" class="form-input" required value="{{ old('start_date', $package->start_date ? $package->start_date->format('Y-m-d') : '') }}">
                    </div>
                    <div>
                        <label class="form-label">End Date *</label>
                        <input type="date" name="end_date" class="form-input" required value="{{ old('end_date', $package->end_date ? $package->end_date->format('Y-m-d') : '') }}">
                    </div>
                </div>
            </div>

            <!-- Image & Category -->
            <div>
                <h3 class="text-lg font-bold text-gray-800 mb-4">Media & Category</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Update Image</label>
                        <div class="mt-2">
                             @if($package->image)
                                <img src="{{ $package->image }}" alt="Current Image" class="h-20 w-20 object-cover rounded mb-2">
                            @endif
                            <input type="file" name="image_upload" class="form-input p-2 w-full" accept="image/*">
                            <p class="text-xs text-gray-500 mt-1">Leave blank to keep current image</p>
                        </div>
                    </div>
                    <div>
                        <label class="form-label">Category *</label>
                        <select name="category" class="form-input" required>
                            @foreach(['adventure', 'hill-station', 'cultural', 'beach', 'desert', 'trekking', 'nature', 'heritage', 'religious'] as $cat)
                                <option value="{{ $cat }}" {{ old('category', $package->category) == $cat ? 'selected' : '' }}>{{ ucfirst($cat) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

             <!-- Ratings & Features -->
             <div>
                <h3 class="text-lg font-bold text-gray-800 mb-4">Ratings & Features</h3>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="form-label">Rating (1-5)</label>
                        <input type="number" name="rating" class="form-input" min="0" max="5" step="0.1" value="{{ old('rating', $package->rating) }}">
                    </div>
                    <div>
                        <label class="form-label">Rating Count</label>
                        <input type="number" name="reviews_count" class="form-input" min="0" value="{{ old('reviews_count', $package->reviews_count) }}">
                    </div>
                    <div>
                        <label class="form-label">Status</label>
                        <select name="status" class="form-input">
                            <option value="active" {{ old('status', $package->status) == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $package->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                     <div class="flex items-center">
                        <label class="flex items-center">
                            <input type="checkbox" name="featured" value="1" class="rounded border-gray-300 text-secondary focus:ring-secondary mr-2" {{ old('featured', $package->is_featured) ? 'checked' : '' }}>
                            <span class="text-gray-800">Featured Package</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Departure Cities -->
            <div>
                <h3 class="text-lg font-bold text-gray-800 mb-4">Departure Cities</h3>
                <div id="departureCitiesContainer" class="space-y-2">
                    @forelse($package->departure_cities ?? [] as $city)
                        <div class="flex items-center space-x-2">
                            <input type="text" name="departure_cities[]" class="form-input" value="{{ $city }}">
                            <button type="button" onclick="this.parentElement.remove()" class="btn btn-danger">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    @empty
                         <div class="flex items-center space-x-2">
                            <input type="text" name="departure_cities[]" class="form-input" placeholder="e.g., Ahmedabad">
                            <button type="button" onclick="addDepartureCity()" class="btn btn-outline"><i class="fas fa-plus"></i></button>
                        </div>
                    @endforelse
                    <button type="button" onclick="addDepartureCity()" class="btn btn-outline mt-2"><i class="fas fa-plus"></i> Add City</button>
                </div>
            </div>

             <!-- Available Months -->
             <div>
                <h3 class="text-lg font-bold text-gray-800 mb-4">Available Months</h3>
                <div class="grid grid-cols-2 md:grid-cols-6 gap-3">
                    @foreach(['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'] as $month)
                        <label class="flex items-center">
                            <input type="checkbox" name="available_months[]" value="{{ $month }}" 
                                class="rounded border-gray-300 text-secondary focus:ring-secondary mr-2"
                                {{ in_array($month, $package->available_months ?? []) ? 'checked' : '' }}>
                            <span class="text-gray-800">{{ substr($month, 0, 3) }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Itinerary -->
            <div>
                <h3 class="text-lg font-bold text-gray-800 mb-4">Itinerary</h3>
                <div id="itineraryContainer" class="space-y-4">
                    @foreach($package->itinerary ?? [] as $index => $day)
                        <div class="border border-gray-300 rounded-lg p-4 itinerary-day">
                            <div class="flex justify-between items-center mb-3">
                                <h4 class="font-semibold">Day {{ $index + 1 }}</h4>
                                <button type="button" onclick="this.closest('.itinerary-day').remove()" class="btn btn-danger btn-sm">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Day Title *</label>
                                <input type="text" name="itinerary[{{ $index }}][day]" class="form-input" required value="{{ $day['day'] ?? '' }}">
                            </div>
                            <div class="space-y-2 activities-container">
                                @foreach($day['activities'] ?? [] as $activity)
                                    <div class="flex items-center space-x-2">
                                        <input type="text" name="itinerary[{{ $index }}][activities][]" class="form-input" value="{{ $activity }}">
                                        <button type="button" onclick="this.parentElement.remove()" class="btn btn-danger">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                    </div>
                                @endforeach
                                <button type="button" onclick="addActivity(this)" class="btn btn-outline mt-2">
                                    <i class="fas fa-plus"></i> Add Activity
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
                <button type="button" onclick="addItineraryDay()" class="btn btn-outline mt-3">
                    <i class="fas fa-plus mr-2"></i> Add Day
                </button>
            </div>

            <!-- Dynamic Lists -->
            @foreach(['inclusions' => 'Inclusions', 'exclusions' => 'Exclusions', 'things_to_carry' => 'Things to Carry', 'terms_conditions' => 'Terms & Conditions'] as $field => $label)
                <div>
                    <h3 class="text-lg font-bold text-gray-800 mb-4">{{ $label }}</h3>
                    <div id="{{ $field }}Container" class="space-y-2">
                        @foreach($package->$field ?? [] as $item)
                             <div class="flex items-center space-x-2">
                                <input type="text" name="{{ $field }}[]" class="form-input" value="{{ $item }}">
                                <button type="button" onclick="this.parentElement.remove()" class="btn btn-danger">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        @endforeach
                         <button type="button" onclick="addItem('{{ $field }}')" class="btn btn-outline mt-2">
                            <i class="fas fa-plus"></i> Add Item
                        </button>
                    </div>
                </div>
            @endforeach

            <!-- Branches -->
            <div>
                <h3 class="text-lg font-bold text-gray-800 mb-4">Branches</h3>
                <div id="branchesContainer" class="space-y-4">
                    @foreach($package->branches ?? [] as $index => $branch)
                        <div class="border border-gray-300 rounded-lg p-4 branch-item">
                            <div class="flex justify-between items-center mb-3">
                                <h4 class="font-semibold">Branch {{ $index + 1 }}</h4>
                                <button type="button" onclick="this.closest('.branch-item').remove()" class="btn btn-danger btn-sm">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="form-label">City *</label>
                                    <input type="text" name="branches[{{ $index }}][city]" class="form-input" required value="{{ $branch['city'] ?? '' }}">
                                </div>
                                <div>
                                    <label class="form-label">Phone Numbers *</label>
                                    <input type="text" name="branches[{{ $index }}][phone]" class="form-input" required value="{{ $branch['phone'] ?? '' }}">
                                </div>
                                <div class="md:col-span-3">
                                    <label class="form-label">Address *</label>
                                    <textarea name="branches[{{ $index }}][address]" rows="2" class="form-input" required>{{ $branch['address'] ?? '' }}</textarea>
                                </div>
                            </div>
                        </div>
                    @endforeach
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
                        <input type="url" name="contact_info[website]" class="form-input" value="{{ old('contact_info.website', $package->contact_info['website'] ?? '') }}">
                    </div>
                    <div>
                        <label class="form-label">Email</label>
                        <input type="email" name="contact_info[email]" class="form-input" value="{{ old('contact_info.email', $package->contact_info['email'] ?? '') }}">
                    </div>
                </div>
            </div>

            <div class="flex justify-end space-x-3 pt-6 border-t">
                 <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save mr-2"></i> Update Package
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Copy the same scripts from create.blade.php here, but adapt for dayCount
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
        container.insertBefore(div, container.lastElementChild);
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
         container.insertBefore(div, container.lastElementChild);
    }

    let dayCount = {{ count($package->itinerary ?? []) }};
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
                 <button type="button" onclick="addActivity(this)" class="btn btn-outline">
                    <i class="fas fa-plus"></i> Add Activity
                </button>
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
        // Insert before the button
        container.insertBefore(div, btn);
    }

    let branchCount = {{ count($package->branches ?? []) }};
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
