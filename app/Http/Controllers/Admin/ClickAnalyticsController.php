<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class ClickAnalyticsController extends Controller
{
    public function index()
    {
        // Calculate stats
        $stats = [
            'total_clicks' => Booking::sum('button_clicks'),
            'whatsapp_clicks' => Booking::sum('whatsapp_clicks'),
            'call_clicks' => Booking::sum('call_clicks'),
        ];

        // Get click data with relationships
        $clickData = Booking::with(['package', 'user'])
            ->where('button_clicks', '>', 0)
            ->latest()
            ->paginate(20);

        return view('admin.analytics.clicks', compact('stats', 'clickData'));
    }
}
