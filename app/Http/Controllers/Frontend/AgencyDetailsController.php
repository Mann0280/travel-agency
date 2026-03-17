<?php
// app/Http/Controllers/AgencyDetailsController.php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Package;
use App\Models\Agency;

class AgencyDetailsController extends Controller
{
    public function show(Request $request, $agency, $package)
    {
        // Decode URL parameters
        $agencyName = urldecode($agency);
        $packageName = urldecode($package);

        // Get search filters
        $fromFilter = $request->input('from', '');
        $dateFilter = $request->input('date', '');

        // Find the package and agency
        $selectedPackage = Package::with(['destination', 'agency', 'reviews'])
            ->where('name', $packageName)
            ->whereHas('agency', function($query) use ($agencyName) {
                $query->where('name', $agencyName);
            })
            ->first();

        // If not found, redirect to agency listing
        if (!$selectedPackage) {
            return redirect()->route('agency')->with('error', 'Agency or package not found');
        }

        $selectedAgency = $selectedPackage->agency;
        $agencyContacts = $this->getAgencyContacts();

        // Package metadata defaults matching the user's PHP snippet
        // Package metadata defaults matching the user's PHP snippet
        $defaults = [
            'itinerary' => [
                ['day' => 'DAY 1: AHMEDABAD - UDAIPUR (280KM/6HRS)', 'activities' => ['Arrival At Ahmedabad And Transfer To Udaipur', 'On Arrival At Udaipur Meet & Greet Then Check In At Hotel', 'Day At free time', 'By The Sunset We Take Boat Ride Over Lake Pichola Experience Of Which Is Beyond Any Comparison', 'Overnight At Udaipur']],
                ['day' => 'DAY 2: UDAIPUR (LOCAL SIGHTSEEING)', 'activities' => ['After Breakfast We Visit City Palace, Jagdish Temple, Saheliyon Ki Bari, Pratap Smarak, Fateh Sagar Lake, Fish Museum, Bhartiya Lok Kala Mandal, Karni Mata Temple & Ropeway', 'Today Evening We Also Witness Rich History Of Mewar By Sound & Light Show At City Palace', 'Overnight At Hotel']],
                ['day' => 'DAY 3: UDAIPUR - KUMBHALGARF (90KM/2HR)', 'activities' => ['After breakfast we proceed to Kumbhalgarh', 'Later we visit to Kumbhalgarh Fort & Badal mahal', 'Back to Ahmedabad']]
            ],
            'inclusions' => ['Travelling (3*2 Bus ac)', 'Food (2 BF, 1 LUNCH, 2 DINNER)', 'Trekking, garba, sightseeing', 'Resort with swimming pool (Sharing basis)', 'Udaipur local sightseeing', 'Volunteers/Instructors/Guide'],
            'exclusions' => ['Anything not mentioned in inclusion', 'Any paid activities (Paragliding, Boating or other)', 'Day 3 - Lunch & Day 3 - Dinner', 'Emergency medical cost after First Aid Support', 'Entry fee at Site Seeing Place (if any)'],
            'things_to_carry' => ['Rucksack & Backpack', '3 Pair of Clothes (Full Sleeves)', 'Raincoat/Poncho (Compulsory)', 'Sports Shoes (Compulsory)', 'Sun cap, Googles, Torch & Battery', 'Water bottles, Mask & Sanitiser', 'Sleeping bag / Blanket', 'Original Identity Proof', 'Soft copy of fee receipt', 'Snacks for travel', 'Mobile, Camera, Power bank', 'Plastic bag', 'Personal Medication', 'Warm Cloths (as per season)', 'Shocks & Sleeper'],
            'terms_conditions' => ['During Monsoon Pack your luggage in Plastic Bag', 'During camping in Accommodation we provide Tent & Mattress so please carry your bedding material like sleeping bag / Blanket, Bedsheet', 'Cancellation policy: 50% refund if cancelled 15 days before travel', 'Full payment required at time of booking', 'Valid ID proof mandatory for all travelers'],
            'contact_info' => [
                'website' => 'http://tripindiaholidays.in/',
                'email' => 'support@tripindiaholidays.in',
                'branches' => [
                    ['city' => 'Ahmedabad', 'address' => 'FF/63, HAREKRISHNA COMPLEX, NR. PUNTNAGAR CROSSING, GHODASAR, AHMEDABAD - 380050', 'phones' => ['+91 81601 31879', '+91 95581 31879']],
                    ['city' => 'Vadodara', 'address' => 'B/702, TULSI HIGHTS, NR. DASALAL BHAVAN WAGHODIYA ROAD, VADODARA - 390019', 'phones' => ['+91 70411 31879', '+91 63513 72339']]
                ]
            ]
        ];

        // Merge DB metadata if available
        $packageMetadata = [
            'itinerary' => $selectedPackage->itinerary ?? $defaults['itinerary'],
            'inclusions' => $selectedPackage->inclusions ?? $defaults['inclusions'],
            'exclusions' => $selectedPackage->exclusions ?? $defaults['exclusions'],
            'things_to_carry' => $selectedPackage->things_to_carry ?? $defaults['things_to_carry'],
            'terms_conditions' => $selectedPackage->terms_conditions ?? $defaults['terms_conditions'],
            'contact_info' => [
                'email' => $selectedPackage->contact_info['email'] ?? $defaults['contact_info']['email'],
                'website' => $selectedPackage->contact_info['website'] ?? $defaults['contact_info']['website'],
                'branches' => !empty($selectedPackage->branches) ? array_map(function($branch) {
                    if (!isset($branch['phones']) && isset($branch['phone'])) {
                        $branch['phones'] = [$branch['phone']];
                    }
                    return $branch;
                }, $selectedPackage->branches) : $defaults['contact_info']['branches'],
            ],
        ];

        return view('frontend.agency-details', compact('selectedPackage', 'selectedAgency', 'fromFilter', 'dateFilter', 'packageMetadata', 'agencyContacts'));
    }

    private function getAgencyContacts()
    {
        return config('agencies.contacts', []);
    }
}