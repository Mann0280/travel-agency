@extends('admin.layouts.app')

@section('title', 'Edit Package')

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
                <span class="text-gray-800 font-medium">Edit</span>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 tracking-tight">{{ $package->name }}</h1>
            <p class="text-gray-500 mt-1">Manage package details, itinerary, and pricing</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.packages.index') }}" class="btn btn-outline bg-white hover:bg-gray-50 border-gray-300 text-gray-700">
                <i class="fas fa-arrow-left mr-2"></i> Cancel
            </a>
            <button type="submit" form="packageForm" class="btn btn-primary shadow-lg shadow-gold/20 hover:shadow-gold/40 transition-all transform hover:-translate-y-0.5">
                <i class="fas fa-save mr-2"></i> Save Changes
            </button>
        </div>
    </div>

    <form id="packageForm" action="{{ route('admin.packages.update', $package) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
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
                                <input type="text" name="name" class="form-input pl-11 text-lg font-medium" required value="{{ old('name', $package->name) }}" placeholder="e.g., Majestic Manali Tour">
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="form-label text-gray-700">From City <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"><i class="fas fa-plane-departure"></i></span>
                                    <input type="text" name="from_city" class="form-input pl-11" required value="{{ old('from_city', $package->from_city) }}" placeholder="e.g., Delhi">
                                </div>
                            </div>
                            <div>
                                <label class="form-label text-gray-700">To (Location) <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"><i class="fas fa-map-marker-alt"></i></span>
                                    <input type="text" name="location" class="form-input pl-11" required value="{{ old('location', $package->location) }}" placeholder="e.g., Manali, Himachal Pradesh">
                                </div>
                            </div>
                            <div>
                                <label class="form-label text-gray-700">Duration Text <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"><i class="fas fa-clock"></i></span>
                                    <input type="text" name="duration" class="form-input pl-11" required value="{{ old('duration', $package->duration) }}" placeholder="e.g., 5 Nights / 6 Days">
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="form-label text-gray-700">Description <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <textarea name="description" rows="5" class="form-textarea w-full" required>{{ old('description', $package->description) }}</textarea>
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
                        <div class="absolute left-[2.25rem] top-6 bottom-6 w-0.5 bg-gray-200" id="timelineProp" style="{{ empty($package->itinerary) ? 'display:none;' : '' }}"></div>

                        @foreach($package->itinerary ?? [] as $index => $day)
                            <div class="itinerary-day relative pl-12 pb-8 group last:pb-0">
                                <!-- Day Dot -->
                                <div class="absolute left-5 top-0 w-8 h-8 -ml-4 bg-white border-4 border-forest rounded-full z-10 flex items-center justify-center shadow-sm text-xs font-bold text-forest">
                                    {{ $index + 1 }}
                                </div>
                                
                                <div class="bg-gray-50 rounded-xl p-5 border border-gray-200 transition-all hover:border-forest/30 hover:shadow-md relative">
                                     <div class="absolute top-4 right-4 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <button type="button" onclick="removeDay(this)" class="text-red-400 hover:text-red-600 p-1.5 hover:bg-red-50 rounded-md transition-colors">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>

                                    <div class="grid grid-cols-1 gap-3 mb-4">
                                        <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Day {{ $index + 1 }} Title</label>
                                        <input type="text" name="itinerary[{{ $index }}][day]" class="form-input font-semibold text-gray-800 bg-white" required value="{{ $day['day'] ?? '' }}" placeholder="e.g., Arrival in Manali & Local Sightseeing">
                                    </div>
                                    
                                    <div class="space-y-3 activities-container">
                                        @foreach($day['activities'] ?? [] as $activity)
                                            <div class="flex items-center gap-3 group/activity">
                                                <div class="w-1.5 h-1.5 rounded-full bg-gold shrink-0"></div>
                                                <input type="text" name="itinerary[{{ $index }}][activities][]" class="form-input text-sm py-2 bg-white border-transparent hover:border-gray-300 focus:border-gold transition-colors" value="{{ $activity }}">
                                                <button type="button" onclick="this.parentElement.remove()" class="text-gray-300 hover:text-red-500 opacity-0 group-hover/activity:opacity-100 transition-opacity">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        @endforeach
                                        <button type="button" onclick="addActivity(this)" class="text-sm text-gold hover:text-yellow-700 font-medium mt-2 inline-flex items-center px-2 py-1 rounded hover:bg-gold/10 transition-colors">
                                            <i class="fas fa-plus-circle mr-1.5"></i> Add Activity
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    @if(empty($package->itinerary))
                        <div id="noItineraryMsg" class="text-center py-12">
                            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-300">
                                <i class="fas fa-map-marked-alt text-2xl"></i>
                            </div>
                            <h4 class="text-gray-900 font-medium mb-1">No Itinerary Added</h4>
                            <p class="text-gray-500 text-sm mb-4">Start building the day-wise schedule for this package.</p>
                            <button type="button" onclick="addItineraryDay()" class="btn btn-primary btn-sm">Start Adding Days</button>
                        </div>
                    @endif
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
                                        @foreach($package->$field ?? [] as $item)
                                             <div class="flex items-center gap-2 group/item">
                                                <div class="w-1.5 h-1.5 rounded-full bg-gray-300 shrink-0"></div>
                                                <input type="text" name="{{ $field }}[]" class="form-input text-sm py-1.5 bg-white border-transparent focus:bg-white hover:border-gray-300 focus:border-gold transition-colors" value="{{ $item }}">
                                                <button type="button" onclick="this.parentElement.remove()" class="text-gray-300 hover:text-red-500 opacity-0 group-hover/item:opacity-100 transition-opacity">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        @endforeach
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
                                <label class="text-xs font-bold text-gray-500 uppercase tracking-wide mb-2 block">Current Status</label>
                                <select name="status" class="form-select font-medium text-gray-800 w-full" data-status-color>
                                    <option value="active" {{ old('status', $package->status) == 'active' ? 'selected' : '' }}>Active (Published)</option>
                                    <option value="inactive" {{ old('status', $package->status) == 'inactive' ? 'selected' : '' }}>Inactive (Draft)</option>
                                </select>
                            </div>
                            <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                                <span class="text-gray-700 font-medium text-sm flex items-center gap-2">
                                    <i class="fas fa-star text-gold"></i> Featured
                                </span>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="featured" value="1" class="sr-only peer" {{ old('featured', $package->is_featured) ? 'checked' : '' }}>
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-gold hover:bg-gray-300 peer-checked:hover:bg-yellow-600 transition-colors"></div>
                                </label>
                            </div>
                             <div class="pt-2">
                                <button type="submit" class="w-full btn btn-primary py-2.5 shadow-md shadow-gold/20">
                                    Update Package
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
                                @if($package->image)
                                    <div class="relative rounded-xl overflow-hidden aspect-video w-full group shadow-md">
                                        <img src="{{ $package->image_url }}" alt="Package Image" class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-500">
                                        <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity backdrop-blur-sm">
                                            <span class="text-white text-xs font-medium border border-white/50 px-3 py-1 rounded-full">Current Cover</span>
                                        </div>
                                    </div>
                                @endif
                                
                                <label class="block">
                                    <span class="sr-only">Choose profile photo</span>
                                    <input type="file" name="image_upload" class="block w-full text-sm text-gray-500
                                      file:mr-4 file:py-2 file:px-4
                                      file:rounded-full file:border-0
                                      file:text-xs file:font-semibold
                                      file:bg-forest/10 file:text-forest
                                      hover:file:bg-forest/20
                                      transition-colors cursor-pointer
                                    " accept="image/*"/>
                                </label>
                                <p class="text-xs text-gray-400">Recommended: 1200x800px. Max: 5MB.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Pricing & Dates -->
                    <div class="card border-0 shadow-sm ring-1 ring-gray-100">
                        <div class="p-5 border-b border-gray-100">
                             <h3 class="text-md font-bold text-gray-900">Pricing & Availability</h3>
                        </div>
                        <div class="p-5 space-y-5">
                             <div>
                                <label class="text-xs font-bold text-gray-500 uppercase tracking-wide block mb-1">Base Price</label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-bold">₹</span>
                                    <input type="number" name="price" class="form-input pl-8 text-xl font-bold text-forest" required min="0" value="{{ old('price', $package->price) }}">
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="form-label text-xs">Days (Count)</label>
                                    <input type="number" name="duration_days" class="form-input" required min="1" value="{{ old('duration_days', $package->duration_days) }}">
                                </div>
                                <div>
                                    <label class="form-label text-xs">Rating</label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gold text-xs"><i class="fas fa-star"></i></span>
                                        <input type="number" name="rating" class="form-input pl-8" min="0" max="5" step="0.1" value="{{ old('rating', $package->rating) }}">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="space-y-3 pt-2">
                                 <div>
                                    <label class="form-label text-xs">Valid From - To</label>
                                    <div class="flex items-center gap-2">
                                        <input type="date" name="start_date" class="form-input text-xs" required value="{{ old('start_date', $package->start_date ? $package->start_date->format('Y-m-d') : '') }}">
                                        <span class="text-gray-400">-</span>
                                        <input type="date" name="end_date" class="form-input text-xs" required value="{{ old('end_date', $package->end_date ? $package->end_date->format('Y-m-d') : '') }}">
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
                                                class="peer sr-only"
                                                {{ in_array($fullMonth, $package->available_months ?? []) ? 'checked' : '' }}>
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
                                    @forelse($package->departure_cities ?? [] as $city)
                                        <div class="flex items-center gap-2 group/city">
                                            <div class="relative w-full">
                                                <span class="absolute left-2.5 top-1/2 -translate-y-1/2 text-gray-400 text-xs"><i class="fas fa-plane-departure"></i></span>
                                                <input type="text" name="departure_cities[]" class="form-input text-xs py-1.5 pl-8" value="{{ $city }}">
                                            </div>
                                            <button type="button" onclick="this.parentElement.remove()" class="text-gray-300 hover:text-red-500 opacity-0 group-hover/city:opacity-100 transition-opacity">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    @empty
                                    @endforelse
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
                                <label class="form-label text-xs">Category</label>
                                <select name="category" class="form-select w-full" required>
                                    <option value="">Select Category</option>
                                    @php
                                        $categories = explode(',', \App\Models\Setting::get('package_categories', 'adventure, hill-station, cultural, beach, desert, trekking, nature, heritage, religious'));
                                    @endphp
                                    @foreach($categories as $cat)
                                        @php $cat = trim($cat); @endphp
                                        <option value="{{ strtolower($cat) }}" {{ old('category', $package->category) == strtolower($cat) ? 'selected' : '' }}>{{ ucfirst($cat) }}</option>
                                    @endforeach
                                </select>
                            </div>
                             <div>
                                <label class="form-label text-xs">Destination</label>
                                <select name="destination_id" class="form-select w-full" required>
                                    @foreach($destinations as $destination)
                                        <option value="{{ $destination->id }}" {{ old('destination_id', $package->destination_id) == $destination->id ? 'selected' : '' }}>{{ $destination->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="form-label text-xs">Agency</label>
                                <select name="agency_id" class="form-select w-full" required>
                                    @foreach($agencies as $agency)
                                        <option value="{{ $agency->id }}" {{ old('agency_id', $package->agency_id) == $agency->id ? 'selected' : '' }}>{{ $agency->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                     <!-- Branches & Contact -->
                    <div class="card border-0 shadow-sm ring-1 ring-gray-100">
                         <div class="p-5 border-b border-gray-100 flex justify-between items-center">
                             <h3 class="text-md font-bold text-gray-900">Branches</h3>
                             <button type="button" onclick="addBranch()" class="text-xs btn btn-xs btn-outline bg-white border-dashed">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                        <div class="p-5 space-y-4">
                            <!-- Contact Info -->
                            <div class="grid grid-cols-1 gap-2 border-b border-gray-100 pb-4">
                                <div>
                                    <label class="form-label text-[10px] uppercase text-gray-400">Contact Email <span class="text-red-500">*</span></label>
                                    <input type="email" name="contact_info[email]" class="form-input text-xs py-1" value="{{ old('contact_info.email', $package->contact_info['email'] ?? '') }}" required placeholder="e.g., contact@agency.com">
                                </div>
                                 <div>
                                    <label class="form-label text-[10px] uppercase text-gray-400">Website <span class="text-red-500">*</span></label>
                                    <input type="url" name="contact_info[website]" class="form-input text-xs py-1" value="{{ old('contact_info.website', $package->contact_info['website'] ?? '') }}" required placeholder="e.g., https://agency.com">
                                </div>
                            </div>

                            <div id="branchesContainer" class="space-y-3">
                                @foreach($package->branches ?? [] as $index => $branch)
                                    <div class="branch-item border border-gray-200 rounded-lg p-3 bg-gray-50/50 text-sm relative group">
                                        <button type="button" onclick="this.closest('.branch-item').remove()" class="absolute top-2 right-2 text-gray-300 hover:text-red-500 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <i class="fas fa-times"></i>
                                        </button>
                                        <div class="space-y-2">
                                            <div class="flex items-center gap-2">
                                                <i class="fas fa-building text-gray-400 text-xs"></i>
                                                <input type="text" name="branches[{{ $index }}][city]" class="form-input text-xs py-1 bg-white" required value="{{ $branch['city'] ?? '' }}" placeholder="City Name">
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <i class="fas fa-phone text-gray-400 text-xs"></i>
                                                <input type="text" name="branches[{{ $index }}][phone]" class="form-input text-xs py-1 bg-white" required value="{{ $branch['phone'] ?? '' }}" placeholder="Phone Number">
                                            </div>
                                            <textarea name="branches[{{ $index }}][address]" rows="2" class="form-textarea text-xs py-1 bg-white w-full" required placeholder="Full Address">{{ $branch['address'] ?? '' }}</textarea>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                             @if(empty($package->branches) && empty($package->contact_info))
                                <p class="text-xs text-center text-gray-400 italic">No contact info added.</p>
                            @endif
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
        div.className = 'flex items-center gap-2 group/city';
        div.innerHTML = `
            <div class="relative w-full">
                <span class="absolute left-2.5 top-1/2 -translate-y-1/2 text-gray-400 text-xs"><i class="fas fa-plane-departure"></i></span>
                <input type="text" name="departure_cities[]" class="form-input text-xs py-1.5 pl-8" placeholder="e.g., Mumbai">
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-gray-300 hover:text-red-500 transition-opacity">
                <i class="fas fa-times"></i>
            </button>
        `;
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

    let dayCount = {{ count($package->itinerary ?? []) }};
    
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
                    <input type="text" name="itinerary[${dayCount}][day]" class="form-input font-semibold text-gray-800 bg-white" required placeholder="e.g., Exploration Day">
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
        // Ideally re-number days here, but for simplicity we keep as is or user saves
        // Re-numbering logic could be added
        updateTimeline();
    }

    function addActivity(btn) {
        const container = btn.closest('.activities-container');
        const dayInput = container.closest('.itinerary-day').querySelector('input[name*="[day]"]');
        const dayIndexMatch = dayInput.name.match(/itinerary\[(\d+)\]/);
        const dayIndex = dayIndexMatch ? dayIndexMatch[1] : dayCount; // fallback
        
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

    let branchCount = {{ count($package->branches ?? []) }};
    function addBranch() {
        const container = document.getElementById('branchesContainer');
        const div = document.createElement('div');
        div.className = 'branch-item border border-gray-200 rounded-lg p-3 bg-gray-50/50 text-sm relative group';
        div.innerHTML = `
            <button type="button" onclick="this.closest('.branch-item').remove()" class="absolute top-2 right-2 text-gray-300 hover:text-red-500 opacity-100 transition-opacity">
                <i class="fas fa-times"></i>
            </button>
            <div class="space-y-2">
                <div class="flex items-center gap-2">
                    <i class="fas fa-building text-gray-400 text-xs"></i>
                    <input type="text" name="branches[${branchCount}][city]" class="form-input text-xs py-1 bg-white" required placeholder="City Name">
                </div>
                <div class="flex items-center gap-2">
                    <i class="fas fa-phone text-gray-400 text-xs"></i>
                    <input type="text" name="branches[${branchCount}][phone]" class="form-input text-xs py-1 bg-white" required placeholder="Phone Number">
                </div>
                <textarea name="branches[${branchCount}][address]" rows="2" class="form-textarea text-xs py-1 bg-white w-full" required placeholder="Full Address"></textarea>
            </div>
        `;
        container.appendChild(div);
        branchCount++;
    }
</script>
@endpush
@endsection
