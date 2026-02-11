<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use App\Models\Agency;
use App\Models\Package;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;

class DashboardController extends AgencyBaseController
{
    public function redirect()
    {
        return redirect()->route('agency.dashboard');
    }

    public function index()
    {
        $agency = $this->getActiveAgency();
        
        $stats = [
            'total_packages' => Package::where('agency_id', $agency->id)->count(),
            'total_revenue' => Booking::where('status', 'confirmed')
                ->whereHas('package', function($query) use ($agency) {
                    $query->where('agency_id', $agency->id);
                })->sum('total_amount'),
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

        // Calculate monthly revenue for the current year
        // Calculate monthly revenue for the current year
        $monthlyRevenue = Booking::whereHas('package', function($query) use ($agency) {
                $query->where('agency_id', $agency->id);
            })
            ->where('status', 'confirmed')
            ->whereYear('created_at', date('Y'))
            ->get()
            ->groupBy(function($booking) {
                return $booking->created_at->format('n'); // 1-12
            })
            ->map(function($bookings) {
                return $bookings->sum('total_amount');
            })
            ->toArray();

        // Fill in missing months with 0
        $chartData = [];
        for ($i = 1; $i <= 12; $i++) {
            $chartData[] = $monthlyRevenue[$i] ?? 0;
        }

        return view('agency.dashboard', compact('agency', 'stats', 'chartData'));
    }
}
