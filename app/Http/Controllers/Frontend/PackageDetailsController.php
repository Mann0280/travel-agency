<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;

use App\Models\Package;
use Illuminate\Http\Request;

class PackageDetailsController extends Controller
{
    public function show(Package $package)
    {
        // 1. Security check - ensure package is approved unless accessed by admin
        if (!$package->is_approved && !auth('admin')->check()) {
            abort(404);
        }

        // 2. Load relationships
        $package->load(['agency', 'reviews']);

        // 3. Robust Agency Phone Retrieval
        $agencyPhone = $package->agency->phone ?? $package->agency->alternate_phone ?? '9876543210';
        
        // 4. Package metadata defaults (only use if specific fields are empty)
        $defaults = [
            'itinerary' => [
                ['day' => 'DAY 1: ARRIVAL & TRANSFER', 'activities' => ['Arrival at the destination', 'Check-in at the hotel', 'Free time for leisure']],
                ['day' => 'DAY 2: SIGHTSEEING', 'activities' => ['Breakfast at hotel', 'Visit major local attractions', 'Evening market visit']],
                ['day' => 'DAY 3: DEPARTURE', 'activities' => ['Breakfast at hotel', 'Check-out', 'Transfer to airport/station']]
            ],
            'inclusions' => [
                'Accommodation at hotel',
                'Daily Breakfast',
                'All transfers and sightseeing by private vehicle',
                'Toll taxes, parking fees and driver expenses',
                'All applicable taxes'
            ],
            'exclusions' => [
                'Any airfare or train fare',
                'Any entry fees to monuments',
                'Any meals other than mentioned',
                'Personal expenses like tips, laundry, telephone calls etc.',
                'Travel Insurance'
            ],
            'things_to_carry' => [
                'Valid Photo ID Proof',
                'Personal Medicines',
                'Comfortable Walking Shoes',
                'Sunscreen & Sunglasses',
                'Power Bank',
                'Camera'
            ],
            'terms_conditions' => [
                'Standard check-in time 12:00 PM and check-out time 10:00 AM',
                'Early check-in and late check-out subject to availability',
                'Car AC will not work in hilly areas',
                'Package price valid for limited duration'
            ],
            'contact_info' => [
                'email' => $package->agency->email ?? 'support@zubee.com',
                'website' => config('app.url'),
                'branches' => []
            ]
        ];

        // 5. Build View Data
        $itineraryData = $package->itinerary ?? $defaults['itinerary'];
        
        // Normalize itinerary to ensure 'activities' key exists for the view
        $itinerary = array_map(function($day) {
            // Ensure 'day' is present
            if (!isset($day['day'])) {
                $day['day'] = 'Day';
            }
            
            // Ensure 'activities' is an array
            if (!isset($day['activities'])) {
                if (isset($day['description'])) {
                    $day['activities'] = [$day['description']];
                } elseif (isset($day['title'])) {
                    $day['activities'] = [$day['title']];
                } else {
                    $day['activities'] = [];
                }
            }
            
            return $day;
        }, $itineraryData);

        $inclusions = $package->inclusions ?? $defaults['inclusions'];
        $exclusions = $package->exclusions ?? $defaults['exclusions'];
        $thingsToCarry = $package->things_to_carry ?? $defaults['things_to_carry'];
        $termsConditions = $package->terms_conditions ?? $defaults['terms_conditions'];
        $contactInfo = $package->contact_info ?? $defaults['contact_info'];
        
        // Dynamic WhatsApp message with package and agency details
        $whatsappMessage = urlencode("Namaste! I am interested in the \"" . $package->name . "\" package offered by " . $package->agency->name . ". Kindly share more details.");

        return view('frontend.package.show', compact(
            'package', 
            'agencyPhone', 
            'itinerary', 
            'inclusions', 
            'exclusions', 
            'thingsToCarry', 
            'termsConditions', 
            'contactInfo',
            'whatsappMessage'
        ));
    }

    public function trackButtonClick(Request $request)
    {
        $validated = $request->validate([
            'package_id' => 'required|exists:packages,id',
            'button_type' => 'required|in:whatsapp,call'
        ]);

        // Get user ID or null for guests
        $userId = auth()->check() ? auth()->id() : null;

        // Find or create a booking record for this package and user/guest
        $booking = \App\Models\Booking::firstOrCreate(
            [
                'package_id' => $validated['package_id'],
                'user_id' => $userId,
                'booking_source' => 'button_click', // Special source for click tracking
            ],
            [
                'travel_date' => now()->addDays(7),
                'number_of_travelers' => 1,
                'total_amount' => 0,
                'status' => 'pending',
                'payment_status' => 'pending',
                'button_clicks' => 0,
                'whatsapp_clicks' => 0,
                'call_clicks' => 0,
            ]
        );

        // Increment the appropriate counter
        if ($validated['button_type'] === 'whatsapp') {
            $booking->increment('whatsapp_clicks');
        } else {
            $booking->increment('call_clicks');
        }
        
        // Also increment total button clicks
        $booking->increment('button_clicks');

        // Send notification if it was a new booking (first click)
        if ($booking->wasRecentlyCreated) {
            try {
                // 1. Notify Admin if enabled
                if (\App\Models\Setting::get('notif_new_booking')) {
                    $admins = \App\Models\User::where('role', 'admin')->get();
                    foreach ($admins as $admin) {
                        $admin->notify(new \App\Notifications\NewBookingNotification($booking));
                    }
                }

                // 2. Notify Agency (Owner of the package)
                // Agency model is Notifiable
                if ($booking->package && $booking->package->agency) {
                     $booking->package->agency->notify(new \App\Notifications\NewBookingNotification($booking));
                }
            } catch (\Exception $e) {
                \Log::error('Booking Notification Error: ' . $e->getMessage());
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Click tracked successfully',
            'data' => [
                'whatsapp_clicks' => $booking->whatsapp_clicks,
                'call_clicks' => $booking->call_clicks,
                'total_clicks' => $booking->button_clicks,
            ]
        ]);
    }
}
