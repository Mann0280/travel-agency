<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\User;
use App\Models\Package;
use App\Models\Agency;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_bookings' => Booking::count(),
            'total_users' => User::count(),
            'total_packages' => Package::count(),
            'total_agencies' => Agency::count(),
            'total_feedback' => \App\Models\Feedback::count(),
            'recent_bookings' => Booking::with('user', 'package')
                ->latest()
                ->limit(5)
                ->get(),
            'recent_feedbacks' => \App\Models\Feedback::with('user')
                ->latest()
                ->limit(5)
                ->get(),
        ];

        return view('admin.dashboard.index', compact('stats'));
    }
}