@extends('admin.layouts.app')

@section('title', 'Add Package')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 border-b border-gray-200 pb-6">
        <div>
             <div class="flex items-center gap-2 text-sm text-gray-500 mb-1">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-forest transition-colors">Dashboard</a>
                <i class="fas fa-chevron-right text-xs"></i>
                <a href="{{ route('admin.packages.index') }}" class="hover:text-forest transition-colors">Packages</a>
                <i class="fas fa-chevron-right text-xs"></i>
                <span class="text-gray-800 font-medium">Add New</span>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Add New Package</h1>
            <p class="text-gray-500 mt-1">Create a new travel package for your portal</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.packages.index') }}" class="btn btn-outline bg-white hover:bg-gray-50 border-gray-300 text-gray-700">
                <i class="fas fa-arrow-left mr-2"></i> Cancel
            </a>
            <button type="submit" form="packageForm" class="btn btn-primary shadow-lg shadow-gold/20 hover:shadow-gold/40 transition-all transform hover:-translate-y-0.5">
                <i class="fas fa-save mr-2"></i> Save Package
            </button>
        </div>
    </div>

    <form id="packageForm" action="{{ route('admin.packages.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <!-- Validation Errors -->
        @if ($errors->any())
            <div class="mb-8 p-6 bg-red-50 border-l-4 border-red-500 rounded-r-xl shadow-sm">
                <div class="flex items-center mb-4">
                    <i class="fas fa-exclamation-triangle text-red-500 mr-2 text-xl"></i>
                    <h3 class="text-lg font-bold text-red-800">Please correct the following errors:</h3>
                </div>
                <ul class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-2">
                    @foreach ($errors->all() as $error)
                        <li class="flex items-start text-red-700 text-sm">
                            <i class="fas fa-circle text-[6px] mt-1.5 mr-2 shrink-0"></i>
                            {{ $error }}
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
        
         <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Left Column: Main Content (8/12) -->
            <div class="lg:col-span-8 space-y-8">
                
                <!-- Basic Information -->
                <div class="card border-0 shadow-sm ring-1 ring-gray-100">
                    <div class="border-b border-gray-100 p-6">
                        <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                             <span class="w-8 h-8 rounded-full bg-forest/10 flex items-center justify-center text-forest text-sm">
                                <i class="fas fa-info"></i>
                            </span>
                            Basic Information
                        </h3>
                    </div>
                    <div class="p-6 space-y-6">
                        <div>
                            <label class="form-label text-gray-700">Package Name <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"><i class="fas fa-heading"></i></span>
                                <input type="text" name="name" class="form-input pl-11 text-lg font-medium" required value="{{ old('name') }}" placeholder="e.g., Majestic Manali Tour">
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="form-label text-gray-700">From City <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"><i class="fas fa-plane-departure"></i></span>
                                    <input type="text" name="from_city" class="form-input pl-11" required value="{{ old('from_city') }}" placeholder="e.g., Delhi">
                                </div>
                            </div>
                            <div>
                                <label class="form-label text-gray-700">To (Location) <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"><i class="fas fa-map-marker-alt"></i></span>
                                    <input type="text" name="location" class="form-input pl-11 @error('location') border-red-500 @enderror" required value="{{ old('location') }}" placeholder="e.g., Manali, Himachal Pradesh">
                                </div>
                                @error('location') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="form-label text-gray-700">Duration Text <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"><i class="fas fa-clock"></i></span>
                                    <input type="text" name="duration" class="form-input pl-11" required value="{{ old('duration') }}" placeholder="e.g., 5 Nights / 6 Days">
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="form-label text-gray-700">Description <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <textarea name="description" rows="5" class="form-textarea w-full" required placeholder="Describe the package details, highlights, and experience...">{{ old('description') }}</textarea>
                                <div class="absolute bottom-2 right-2 text-xs text-gray-400 bg-white px-2 py-1 rounded">Markdown supported</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Itinerary Builder (Timeline Style) -->
                 <div class="card border-0 shadow-sm ring-1 ring-gray-100 overflow-visible">
                    <div class="border-b border-gray-100 p-6 flex justify-between items-center bg-gray-50/50 rounded-t-xl">
                        <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                             <span class="w-8 h-8 rounded-full bg-forest/10 flex items-center justify-center text-forest text-sm">
                                <i class="fas fa-route"></i>
                            </span>
                            Itinerary
                        </h3>
                        <button type="button" onclick="addItineraryDay()" class="btn btn-sm bg-white border border-gray-200 text-forest hover:border-forest hover:bg-forest hover:text-white transition-all shadow-sm">
                            <i class="fas fa-plus mr-1"></i> Add Day
                        </button>
                    </div>
                    
                    <div id="itineraryContainer" class="p-6 space-y-0 relative">
                        <!-- Vertical Thread Line -->
                        <div class="absolute left-[2.25rem] top-6 bottom-6 w-0.5 bg-gray-200" id="timelineProp" style="display:none;"></div>
                        
                        <!-- Initial empty state message is handled by JS logic or absence of items -->
                         <div id="noItineraryMsg" class="text-center py-12">
                            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-300">
                                <i class="fas fa-map-marked-alt text-2xl"></i>
                            </div>
                            <h4 class="text-gray-900 font-medium mb-1">No Itinerary Added</h4>
                            <p class="text-gray-500 text-sm mb-4">Start building the day-wise schedule for this package.</p>
                            <button type="button" onclick="addItineraryDay()" class="btn btn-primary btn-sm">Start Adding Days</button>
                        </div>
                    </div>
                </div>

                <!-- Package Details (Tabs Style) -->
                 <div class="card border-0 shadow-sm ring-1 ring-gray-100">
                     <div class="border-b border-gray-100 p-6">
                        <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                            <span class="w-8 h-8 rounded-full bg-forest/10 flex items-center justify-center text-forest text-sm">
                                <i class="fas fa-list-ul"></i>
                            </span>
                            Package Inclusions & Details
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            @foreach(['inclusions' => 'Inclusions', 'exclusions' => 'Exclusions', 'things_to_carry' => 'Things to Carry', 'terms_conditions' => 'Terms & Conditions'] as $field => $label)
                                <div class="bg-gray-50/50 p-5 rounded-xl border border-gray-100 hover:border-gray-200 transition-colors">
                                    <div class="flex justify-between items-center mb-3">
                                        <label class="font-bold text-gray-800 text-sm flex items-center gap-2">
                                            @if($field == 'inclusions') <i class="fas fa-check text-green-500"></i>
                                            @elseif($field == 'exclusions') <i class="fas fa-times text-red-500"></i>
                                            @else <i class="fas fa-info-circle text-blue-500"></i>
                                            @endif
                                            {{ $label }}
                                        </label>
                                        <button type="button" onclick="addItem('{{ $field }}')" class="text-xs btn btn-xs btn-outline bg-white border-gray-200 shadow-sm">
                                            <i class="fas fa-plus"></i> Add
                                        </button>
                                    </div>
                                    <div id="{{ $field }}Container" class="space-y-2 max-h-60 overflow-y-auto custom-scrollbar pr-1">
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Sidebar (4/12) -->
            <div class="lg:col-span-4 space-y-6">
                
                <!-- Sticky Wrapper -->
                 <div class="sticky top-6 space-y-6">
                    
                    <!-- Publishing Status -->
                     <div class="card border-0 shadow-md ring-1 ring-gray-100 bg-white">
                        <div class="p-5 border-b border-gray-100 bg-gradient-to-r from-forest/5 to-transparent">
                            <h3 class="text-md font-bold text-forest mb-0">Publishing Status</h3>
                        </div>
                        <div class="p-5 space-y-4">
                            <div>
                                <label class="text-xs font-bold text-gray-500 uppercase tracking-wide mb-2 block">Initial Status</label>
                                <select name="status" class="form-select font-medium text-gray-800 w-full" data-status-color>
                                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active (Published)</option>
                                    <option value="inactive" {{ old('status', 'inactive') == 'inactive' ? 'selected' : '' }}>Inactive (Draft)</option>
                                </select>
                            </div>
                            <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                                <div class="flex flex-col">
                                    <span class="text-gray-700 font-medium text-sm flex items-center gap-2">
                                        <i class="fas fa-star text-gold"></i> Featured
                                    </span>
                                    <span class="text-[10px] text-gray-400">Mark as featured package</span>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="is_featured" value="1" class="sr-only peer" {{ old('is_featured') ? 'checked' : '' }}>
                                    <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-forest/20 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-forest"></div>
                                </label>
                            </div>
                             <div class="pt-2">
                                <button type="submit" class="w-full btn btn-primary py-2.5 shadow-md shadow-gold/20">
                                    Create Package
                                </button>
                             </div>
                        </div>
                    </div>

                    <!-- Media -->
                     <div class="card border-0 shadow-sm ring-1 ring-gray-100">
                        <div class="p-5 border-b border-gray-100">
                            <h3 class="text-md font-bold text-gray-900">Cover Image</h3>
                        </div>
                        <div class="p-5">
                            <div class="space-y-4">
                                <label class="block">
                                    <span class="sr-only">Choose profile photo</span>
                                    <input type="file" name="image" required class="block w-full text-sm text-gray-500
                                      file:mr-4 file:py-2 file:px-4
                                      file:rounded-full file:border-0
                                      file:text-xs file:font-semibold
                                      file:bg-forest/10 file:text-forest
                                      hover:file:bg-forest/20
                                      transition-colors cursor-pointer
                                    " accept="image/*"/>
                                </label>
                                <p class="text-xs text-gray-400">Required. Recommended: 1200x800px. Max: 5MB.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Pricing & Dates -->
                    <div class="card border-0 shadow-sm ring-1 ring-gray-100">
                        <div class="p-5 border-b border-gray-100">
                             <h3 class="text-md font-bold text-gray-900">Pricing & Availability</h3>
                        </div>
                        <div class="p-5 space-y-5">
                            {{-- Base Price Removed as per requirements --}}
    <input type="hidden" name="price" value="0">

                            
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="form-label text-xs">Days (Count)</label>
                                    <input type="number" name="duration_days" class="form-input" required min="1" value="{{ old('duration_days') }}">
                                </div>
                                <div>
                                    <label class="form-label text-xs">Initial Rating</label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gold text-xs"><i class="fas fa-star"></i></span>
                                        <input type="number" name="rating" class="form-input pl-8" min="0" max="5" step="0.1" value="{{ old('rating', '5.0') }}">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="space-y-3 pt-2">
                                 <div>
                                    <label class="form-label text-xs">Valid From - To</label>
                                    <div class="flex items-center gap-2">
                                        <input type="date" name="start_date" class="form-input text-xs" required value="{{ old('start_date') }}">
                                        <span class="text-gray-400">-</span>
                                        <input type="date" name="end_date" class="form-input text-xs" required value="{{ old('end_date') }}">
                                    </div>
                                </div>
                            </div>
                            
                             <div class="pt-2 border-t border-gray-100">
                                 <label class="form-label text-xs mb-2 block">Best Months</label>
                                 <div class="flex flex-wrap gap-1.5">
                                    @foreach(['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'] as $month)
                                        @php $fullMonth = date('F', strtotime($month)); @endphp
                                        <label class="cursor-pointer select-none">
                                            <input type="checkbox" name="available_months[]" value="{{ $fullMonth }}" 
                                                class="peer sr-only" {{ in_array($fullMonth, old('available_months', [])) ? 'checked' : '' }}>
                                            <span class="px-2 py-1 text-[10px] uppercase font-bold rounded-md bg-gray-50 text-gray-400 border border-gray-200 peer-checked:bg-forest peer-checked:text-white peer-checked:border-forest transition-all hover:border-gray-300">
                                                {{ $month }}
                                            </span>
                                        </label>
                                    @endforeach
                                 </div>
                            </div>

                             <div class="pt-2 border-t border-gray-100">
                                <label class="form-label text-xs block mb-2">Departure Cities</label>
                                <div id="departureCitiesContainer" class="space-y-2 mb-2">
                                </div>
                                <button type="button" onclick="addDepartureCity()" class="text-xs text-forest hover:text-forest-light font-bold uppercase tracking-wider flex items-center gap-1">
                                    <i class="fas fa-plus"></i> Add City
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Organization -->
                    <div class="card border-0 shadow-sm ring-1 ring-gray-100">
                         <div class="p-5 border-b border-gray-100">
                             <h3 class="text-md font-bold text-gray-900">Organization</h3>
                        </div>
                        <div class="p-5 space-y-4">
                            <div>
                                <label class="form-label text-xs">Agency</label>
                                <select name="agency_id" class="form-select w-full" required>
                                    <option value="">Select Agency</option>
                                    @foreach($agencies as $agency)
                                        <option value="{{ $agency->id }}" {{ old('agency_id') == $agency->id ? 'selected' : '' }}>{{ $agency->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="form-label text-xs">Category</label>
                                <select name="category" class="form-select w-full" required>
                                    <option value="">Select Category</option>
                                    @foreach(['adventure' => 'Adventure', 'hill-station' => 'Hill Station', 'cultural' => 'Cultural', 'beach' => 'Beach', 'desert' => 'Desert', 'trekking' => 'Trekking', 'nature' => 'Nature', 'heritage' => 'Heritage', 'religious' => 'Religious'] as $val => $label)
                                        <option value="{{ $val }}" {{ old('category') == $val ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                             <div>
                                <label class="form-label text-xs">Destination</label>
                                <select name="destination_id" class="form-select w-full" required>
                                    <option value="">Select Destination</option>
                                    @foreach($destinations as $destination)
                                        <option value="{{ $destination->id }}" {{ old('destination_id') == $destination->id ? 'selected' : '' }}>{{ $destination->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    


                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
    function addDepartureCity() {
        const container = document.getElementById('departureCitiesContainer');
        const div = document.createElement('div');
        div.className = 'flex flex-col gap-2 p-3 bg-gray-50 rounded-lg border border-gray-100 group/city relative';
        div.innerHTML = `
            <button type="button" onclick="this.parentElement.remove()" class="absolute -top-2 -right-2 w-6 h-6 bg-white border border-gray-200 rounded-full flex items-center justify-center text-gray-400 hover:text-red-500 hover:border-red-200 transition-all shadow-sm z-10">
                <i class="fas fa-times text-[10px]"></i>
            </button>
            <div class="grid grid-cols-2 gap-2">
                <div class="relative">
                    <span class="absolute left-2.5 top-1/2 -translate-y-1/2 text-gray-400 text-[10px]"><i class="fas fa-plane-departure"></i></span>
                    <input type="text" name="departure_cities[][city]" class="form-input text-[10px] py-1.5 pl-7" placeholder="City (e.g. Delhi)" required>
                </div>
                <div class="relative">
                    <span class="absolute left-2.5 top-1/2 -translate-y-1/2 text-gray-400 text-[10px]">₹</span>
                    <input type="number" name="departure_cities[][price]" class="form-input text-[10px] py-1.5 pl-6" placeholder="Price" required min="0">
                </div>
            </div>
        `;
        
        // Fix for array naming to group city and price
        const index = container.querySelectorAll('.group\\/city').length;
        div.querySelector('input[name="departure_cities[][city]"]').name = `departure_cities[${index}][city]`;
        div.querySelector('input[name="departure_cities[][price]"]').name = `departure_cities[${index}][price]`;
        
        container.appendChild(div);
    }


    function addItem(field) {
        const container = document.getElementById(field + 'Container');
        const div = document.createElement('div');
        div.className = 'flex items-center gap-2 group/item';
        div.innerHTML = `
            <div class="w-1.5 h-1.5 rounded-full bg-gray-300 shrink-0"></div>
            <input type="text" name="${field}[]" class="form-input text-sm py-1.5 bg-white border-transparent focus:bg-white hover:border-gray-300 focus:border-gold transition-colors" placeholder="Add item...">
            <button type="button" onclick="this.parentElement.remove()" class="text-gray-300 hover:text-red-500 opacity-100 transition-opacity">
                <i class="fas fa-times"></i>
            </button>
        `;
        container.appendChild(div);
    }

    let dayCount = 0;
    
    function updateTimeline() {
        const container = document.getElementById('itineraryContainer');
        const prop = document.getElementById('timelineProp');
        if(container.querySelectorAll('.itinerary-day').length > 0) {
            prop.style.display = 'block';
            document.getElementById('noItineraryMsg')?.remove();
        } else {
            prop.style.display = 'none';
        }
    }

    function addItineraryDay() {
        const container = document.getElementById('itineraryContainer');
        const div = document.createElement('div');
        div.className = 'itinerary-day relative pl-12 pb-8 group last:pb-0';
        div.innerHTML = `
             <div class="absolute left-5 top-0 w-8 h-8 -ml-4 bg-white border-4 border-forest rounded-full z-10 flex items-center justify-center shadow-sm text-xs font-bold text-forest">
                ${dayCount + 1}
            </div>
            
            <div class="bg-gray-50 rounded-xl p-5 border border-gray-200 transition-all hover:border-forest/30 hover:shadow-md relative">
                 <div class="absolute top-4 right-4 opacity-0 group-hover:opacity-100 transition-opacity">
                    <button type="button" onclick="removeDay(this)" class="text-red-400 hover:text-red-600 p-1.5 hover:bg-red-50 rounded-md transition-colors">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </div>

                <div class="grid grid-cols-1 gap-3 mb-4">
                    <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Day ${dayCount + 1} Title</label>
                    <input type="text" name="itinerary[${dayCount}][day]" class="form-input font-semibold text-gray-800 bg-white" required placeholder="e.g., Arrival in Manali & Local Sightseeing">
                </div>
                
                <div class="space-y-3 activities-container">
                     <button type="button" onclick="addActivity(this)" class="text-sm text-gold hover:text-yellow-700 font-medium mt-2 inline-flex items-center px-2 py-1 rounded hover:bg-gold/10 transition-colors">
                        <i class="fas fa-plus-circle mr-1.5"></i> Add Activity
                    </button>
                </div>
            </div>
        `;
        container.appendChild(div);
        dayCount++;
        updateTimeline();
    }
    
    function removeDay(btn) {
        btn.closest('.itinerary-day').remove();
        updateTimeline();
    }

    function addActivity(btn) {
        const container = btn.closest('.activities-container');
        const dayInput = container.closest('.itinerary-day').querySelector('input[name*="[day]"]');
        const dayIndexMatch = dayInput.name.match(/itinerary\[(\d+)\]/);
        const dayIndex = dayIndexMatch ? dayIndexMatch[1] : dayCount;
        
        const div = document.createElement('div');
        div.className = 'flex items-center gap-3 group/activity';
        div.innerHTML = `
             <div class="w-1.5 h-1.5 rounded-full bg-gold shrink-0"></div>
            <input type="text" name="itinerary[${dayIndex}][activities][]" class="form-input text-sm py-2 bg-white border-transparent hover:border-gray-300 focus:border-gold transition-colors" placeholder="Activity detail">
            <button type="button" onclick="this.parentElement.remove()" class="text-gray-300 hover:text-red-500 opacity-100 transition-opacity">
                <i class="fas fa-times"></i>
            </button>
        `;
        container.insertBefore(div, btn);
    }

</script>
@endpush
@endsection
