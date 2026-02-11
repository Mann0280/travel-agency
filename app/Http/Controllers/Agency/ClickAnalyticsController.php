<?php

namespace App\Http\Controllers\Agency;

use App\Models\Booking;
use Illuminate\Http\Request;

class ClickAnalyticsController extends AgencyBaseController
{
    public function index()
    {
        $agency = $this->getActiveAgency();

        // Calculate stats specific to this agency
        $stats = [
            'total_clicks' => Booking::whereHas('package', function($query) use ($agency) {
                $query->where('agency_id', $agency->id);
            })->sum('button_clicks'),
            
            'whatsapp_clicks' => Booking::whereHas('package', function($query) use ($agency) {
                $query->where('agency_id', $agency->id);
            })->sum('whatsapp_clicks'),
            
            'call_clicks' => Booking::whereHas('package', function($query) use ($agency) {
                $query->where('agency_id', $agency->id);
            })->sum('call_clicks'),
        ];

        // Get click data with relationships, filtered by agency
        $clickData = Booking::with(['package', 'user'])
            ->whereHas('package', function($query) use ($agency) {
                $query->where('agency_id', $agency->id);
            })
            ->where('button_clicks', '>', 0)
            ->latest()
            ->paginate(20);

        return view('agency.analytics.index', compact('agency', 'stats', 'clickData'));
    }
}
