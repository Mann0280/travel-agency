<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Agency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends AgencyBaseController
{
    public function index(Request $request)
    {
        $agency = $this->getActiveAgency();
        
        // Build query
        $query = Booking::with(['user', 'package'])
            ->whereHas('package', function($q) use ($agency) {
                $q->where('agency_id', $agency->id);
            });

        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%{$search}%")
                              ->orWhere('email', 'like', "%{$search}%")
                              ->orWhere('phone', 'like', "%{$search}%");
                })
                ->orWhereHas('package', function($pkgQuery) use ($search) {
                    $pkgQuery->where('name', 'like', "%{$search}%");
                })
                ->orWhere('id', 'like', "%{$search}%");
            });
        }

        // Filters
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        if ($request->has('payment_status') && $request->payment_status) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->has('from_date') && $request->from_date) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->has('to_date') && $request->to_date) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $bookings = $query->latest()->paginate(10);

        // Calculate stats
        $allBookings = Booking::whereHas('package', function($q) use ($agency) {
            $q->where('agency_id', $agency->id);
        });

        $stats = [
            'total' => $allBookings->count(),
            'revenue' => $allBookings->sum('total_amount'),
            'confirmed' => $allBookings->where('status', 'confirmed')->count(),
            'pending' => $allBookings->where('status', 'pending')->count(),
        ];
            
        return view('agency.bookings.index', compact('bookings', 'stats'));
    }

    public function show(Booking $booking)
    {
        $agency = $this->getActiveAgency();
        if ($booking->package->agency_id !== $agency->id) {
            abort(403);
        }
        return view('agency.bookings.show', compact('booking'));
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        $agency = $this->getActiveAgency();
        if ($booking->package->agency_id !== $agency->id) {
            abort(403);
        }

        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled,completed'
        ]);

        $booking->update($validated);

        return redirect()->route('agency.bookings')->with('success', 'Booking status updated successfully.');
    }

    public function update(Request $request, Booking $booking)
    {
        $agency = $this->getActiveAgency();
        if ($booking->package->agency_id !== $agency->id) {
            abort(403);
        }

        $validated = $request->validate([
            'travel_date' => 'nullable|date',
            'travellers' => 'required|integer|min:1',
            'total_amount' => 'required|numeric|min:0',
            'payment_method' => 'nullable|string',
            'status' => 'required|in:pending,confirmed,cancelled,completed',
            'payment_status' => 'required|in:pending,paid,refunded',
        ]);

        $booking->update($validated);

        return redirect()->route('agency.bookings')->with('success', 'Booking updated successfully.');
    }
}
