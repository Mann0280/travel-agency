@extends('admin.layouts.app')

@section('title', 'Add New Agency')

@section('content')
<div class="space-y-6 max-w-5xl mx-auto">
    <!-- Header with Breadcrumb -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <div class="flex items-center text-sm text-gray-400 mb-2 font-medium">
                <a href="{{ route('admin.agencies.index') }}" class="hover:text-forest transition-colors">Agencies</a>
                <i class="fas fa-chevron-right mx-2 text-[10px]"></i>
                <span class="text-gray-600">Add New Agency</span>
            </div>
            <h1 class="text-2xl font-bold text-forest font-poppins">Add New Agency</h1>
            <p class="text-gray-500 text-sm">Register a new travel agency partner</p>
        </div>
        <a href="{{ route('admin.agencies.index') }}" class="flex items-center gap-2 px-6 py-2 border border-gray-200 text-gray-600 font-bold rounded-xl hover:bg-gray-50 transition-all duration-300">
            <i class="fas fa-arrow-left"></i> Back to List
        </a>
    </div>

    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-600 px-6 py-4 rounded-2xl">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="agencyForm" action="{{ route('admin.agencies.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6 pb-20">
        @csrf
        
        <!-- Agency Information -->
        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-8 py-6 bg-gray-50 border-b border-gray-100 italic">
                <h3 class="text-lg font-bold text-forest">Agency Information</h3>
            </div>
            <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="text-xs font-black text-gray-500 uppercase tracking-wider ml-1">Agency Name *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required placeholder="e.g., ABC Travels" class="w-full px-5 py-3 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-forest focus:border-forest transition-all">
                </div>
                
                <div class="space-y-2">
                    <label class="text-xs font-black text-gray-500 uppercase tracking-wider ml-1">Agency Email * (Login Username)</label>
                    <input type="email" name="email" value="{{ old('email') }}" required placeholder="agency@example.com" class="w-full px-5 py-3 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-forest focus:border-forest transition-all">
                </div>

                <div class="space-y-2">
                    <label class="text-xs font-black text-gray-500 uppercase tracking-wider ml-1">Login Password *</label>
                    <div class="relative">
                        <input type="password" name="password" required placeholder="Minimum 8 characters" class="w-full px-5 py-3 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-forest focus:border-forest transition-all">
                        <i class="fas fa-lock absolute right-4 top-1/2 -translate-y-1/2 text-gray-300"></i>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-xs font-black text-gray-500 uppercase tracking-wider ml-1">Agency Logo</label>
                    <div class="flex items-center gap-4">
                        <div class="flex-1 border-2 border-dashed border-gray-200 rounded-2xl p-4 text-center cursor-pointer hover:border-forest transition-colors relative">
                            <input type="file" name="logo_upload" onchange="previewImage(this)" class="absolute inset-0 opacity-0 cursor-pointer">
                            <div id="uploadPlaceholder" class="flex flex-col items-center">
                                <i class="fas fa-cloud-upload-alt text-gray-300 text-2xl mb-1"></i>
                                <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Click to upload</span>
                            </div>
                            <div id="imagePreviewContainer" class="hidden">
                                <img id="imagePreview" src="#" class="h-16 w-16 object-cover mx-auto rounded-xl">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="md:col-span-2 space-y-2">
                    <label class="text-xs font-black text-gray-500 uppercase tracking-wider ml-1">Description *</label>
                    <textarea name="description" rows="4" required placeholder="Describe the agency's specialization and services..." class="w-full px-5 py-3 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-forest focus:border-forest transition-all resize-none">{{ old('description') }}</textarea>
                </div>
            </div>
        </div>

        <!-- Contact & Location -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Contact Box -->
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-8 py-5 bg-gray-50 border-b border-gray-100 italic">
                    <h3 class="text-base font-bold text-forest">Contact Details</h3>
                </div>
                <div class="p-8 space-y-4">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Contact Person</label>
                        <input type="text" name="contact_person" value="{{ old('contact_person') }}" placeholder="Full Name" class="w-full px-5 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-forest transition-all">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Phone Number *</label>
                            <input type="tel" id="agencyPhone" name="phone" value="{{ old('phone') }}" required placeholder="+91 ..." class="w-full px-5 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-forest transition-all">
                            <div class="flex items-center gap-2 mt-1 ml-1">
                                <input type="checkbox" id="syncWhatsApp" class="w-3 h-3 rounded text-forest focus:ring-forest border-gray-200 cursor-pointer">
                                <label for="syncWhatsApp" class="text-[10px] text-gray-500 font-bold uppercase tracking-wider cursor-pointer select-none italic">Same as Phone</label>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">WhatsApp Number *</label>
                            <input type="tel" id="agencyWhatsApp" name="alternate_phone" value="{{ old('alternate_phone') }}" required placeholder="+91 ..." class="w-full px-5 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-forest transition-all">
                        </div>
                    </div>

                </div>
            </div>

            <!-- Location Box -->
            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-8 py-5 bg-gray-50 border-b border-gray-100 italic">
                    <h3 class="text-base font-bold text-forest">Office Location</h3>
                </div>
                <div class="p-8 space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">City *</label>
                            <input type="text" name="city" value="{{ old('city') }}" required class="w-full px-5 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-forest transition-all">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">State</label>
                            <input type="text" name="state" value="{{ old('state') }}" class="w-full px-5 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-forest transition-all">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Country</label>
                            <input type="text" name="country" value="{{ old('country', 'India') }}" class="w-full px-5 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-forest transition-all">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Pincode</label>
                            <input type="text" name="pincode" value="{{ old('pincode') }}" class="w-full px-5 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-forest transition-all">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Professional & Financial -->
        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-8 py-6 bg-gray-50 border-b border-gray-100 italic">
                <h3 class="text-lg font-bold text-forest">Internal Settings & Financials</h3>
            </div>
            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Experience (Years)</label>
                        <input type="number" name="experience_years" value="{{ old('experience_years', 0) }}" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-forest">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Rating (1-5)</label>
                        <input type="number" name="rating" step="0.1" value="{{ old('rating', 5.0) }}" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-forest">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Commission (%)</label>
                        <input type="number" name="commission_percentage" step="0.1" value="{{ old('commission_percentage', 10.0) }}" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-forest">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Status</label>
                        <select name="status" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-forest bg-white">
                            <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 pt-6 border-t border-gray-100">
                    <div class="space-y-4">
                        <h4 class="text-xs font-black text-forest uppercase tracking-widest border-l-4 border-gold pl-3">Bank Details</h4>
                        <div class="grid grid-cols-1 gap-3">
                            <input type="text" name="bank_name" placeholder="Bank Name" class="w-full px-4 py-2 border border-gray-100 rounded-xl text-sm">
                            <input type="text" name="account_holder" placeholder="Account Holder" class="w-full px-4 py-2 border border-gray-100 rounded-xl text-sm">
                            <div class="grid grid-cols-2 gap-3">
                                <input type="text" name="account_number" placeholder="Account Number" class="w-full px-4 py-2 border border-gray-100 rounded-xl text-sm">
                                <input type="text" name="ifsc_code" placeholder="IFSC Code" class="w-full px-4 py-2 border border-gray-100 rounded-xl text-sm">
                            </div>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <h4 class="text-xs font-black text-forest uppercase tracking-widest border-l-4 border-gold pl-3">Statutory Info</h4>
                        <div class="grid grid-cols-1 gap-3">
                            <input type="text" name="gst_number" placeholder="GST Number" class="w-full px-4 py-2 border border-gray-100 rounded-xl text-sm">
                            <div class="grid grid-cols-2 gap-3">
                                <input type="text" name="pan_number" placeholder="PAN Number" class="w-full px-4 py-2 border border-gray-100 rounded-xl text-sm">
                                <input type="text" name="license_number" placeholder="Trade License" class="w-full px-4 py-2 border border-gray-100 rounded-xl text-sm">
                            </div>
                            <select name="payment_terms" class="w-full px-4 py-2 border border-gray-100 rounded-xl text-sm bg-white">
                                <option value="">Select Payment Terms</option>
                                <option value="net_15">Net 15 Days</option>
                                <option value="net_30">Net 30 Days</option>
                                <option value="immediate">Immediate</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sticky Form Footer -->
        <div class="fixed bottom-0 left-0 right-0 bg-white/80 backdrop-blur-xl border-t border-gray-100 p-4 z-40">
            <div class="max-w-5xl mx-auto flex justify-end gap-3 px-4">
                <button type="button" onclick="window.history.back()" class="px-8 py-3 rounded-2xl font-bold text-gray-500 hover:bg-gray-100 transition-all">Cancel</button>
                <button type="submit" class="px-12 py-3 bg-forest text-white font-black rounded-2xl shadow-luxury hover:scale-[1.02] active:scale-[0.98] transition-all">
                    Register Agency
                </button>
            </div>
        </div>
    </form>
</div>


<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('imagePreview').src = e.target.result;
                document.getElementById('uploadPlaceholder').classList.add('hidden');
                document.getElementById('imagePreviewContainer').classList.remove('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    // WhatsApp Sync Logic
    document.addEventListener('DOMContentLoaded', function() {
        const phoneInput = document.getElementById('agencyPhone');
        const whatsappInput = document.getElementById('agencyWhatsApp');
        const syncCheckbox = document.getElementById('syncWhatsApp');

        if (phoneInput && whatsappInput && syncCheckbox) {
            function performSync() {
                if (syncCheckbox.checked) {
                    whatsappInput.value = phoneInput.value;
                }
            }

            phoneInput.addEventListener('input', performSync);
            
            syncCheckbox.addEventListener('change', function() {
                performSync();
                if (this.checked) {
                    whatsappInput.setAttribute('readonly', true);
                    whatsappInput.classList.add('bg-gray-50/50', 'text-gray-500');
                } else {
                    whatsappInput.removeAttribute('readonly');
                    whatsappInput.classList.remove('bg-gray-50/50', 'text-gray-500');
                }
            });

            // Initial sync if checked
            if (syncCheckbox.checked) {
                performSync();
                whatsappInput.setAttribute('readonly', true);
                whatsappInput.classList.add('bg-gray-50/50', 'text-gray-500');
            }
        }
    });
</script>



@endsection
