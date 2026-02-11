<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice - ZB{{ str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.6;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: #17320B;
            color: white;
            padding: 20px;
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            font-size: 28px;
            margin-bottom: 5px;
        }
        .header p {
            font-size: 14px;
            color: #dbb363;
        }
        .invoice-info {
            display: table;
            width: 100%;
            margin-bottom: 30px;
        }
        .invoice-info-left, .invoice-info-right {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }
        .invoice-info-right {
            text-align: right;
        }
        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #17320B;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 2px solid #dbb363;
        }
        .info-row {
            margin-bottom: 5px;
        }
        .info-label {
            font-weight: bold;
            display: inline-block;
            width: 120px;
        }
        .booking-details {
            background: #f9f9f9;
            padding: 15px;
            margin: 20px 0;
            border-left: 4px solid #dbb363;
        }
        .booking-details h3 {
            color: #17320B;
            margin-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table th {
            background: #17320B;
            color: white;
            padding: 10px;
            text-align: left;
            font-weight: bold;
        }
        table td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        table tr:last-child td {
            border-bottom: none;
        }
        .total-section {
            margin-top: 20px;
            text-align: right;
        }
        .total-row {
            margin: 5px 0;
        }
        .total-label {
            display: inline-block;
            width: 150px;
            font-weight: bold;
        }
        .total-amount {
            display: inline-block;
            width: 150px;
            text-align: right;
        }
        .grand-total {
            font-size: 18px;
            color: #17320B;
            font-weight: bold;
            margin-top: 10px;
            padding-top: 10px;
            border-top: 2px solid #dbb363;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
        }
        .status-confirmed {
            background: #10b981;
            color: white;
        }
        .status-pending {
            background: #f59e0b;
            color: white;
        }
        .status-cancelled {
            background: #ef4444;
            color: white;
        }
    </style>
</head>
<body>
    <div class=\"container\">
        <!-- Header -->
        <div class=\"header\">
            <h1>{{ \App\Models\Setting::get('site_name', 'ZUBEEE') }}</h1>
            <p>Travel & Tourism</p>
        </div>

        <!-- Invoice Info -->
        <div class=\"invoice-info\">
            <div class=\"invoice-info-left\">
                <div class=\"section-title\">Invoice To:</div>
                <div class=\"info-row\"><span class=\"info-label\">Name:</span> {{ $booking->user->name ?? auth()->user()->name }}</div>
                <div class=\"info-row\"><span class=\"info-label\">Email:</span> {{ $booking->user->email ?? auth()->user()->email }}</div>
                <div class=\"info-row\"><span class=\"info-label\">Phone:</span> {{ $booking->phone ?? 'N/A' }}</div>
            </div>
            <div class=\"invoice-info-right\">
                <div class=\"section-title\">Invoice Details:</div>
                <div class=\"info-row\"><span class=\"info-label\">Invoice #:</span> INV-ZB{{ str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}</div>
                <div class=\"info-row\"><span class=\"info-label\">Booking ID:</span> ZB{{ str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}</div>
                <div class=\"info-row\"><span class=\"info-label\">Date:</span> {{ $booking->created_at->format('M d, Y') }}</div>
                <div class=\"info-row\">
                    <span class=\"info-label\">Status:</span> 
                    <span class=\"status-badge status-{{ $booking->status }}\">{{ ucfirst($booking->status) }}</span>
                </div>
            </div>
        </div>

        <!-- Booking Details -->
        <div class=\"booking-details\">
            <h3>Package Details</h3>
            <div class=\"info-row\"><span class=\"info-label\">Package Name:</span> {{ $booking->package->name ?? 'N/A' }}</div>
            <div class=\"info-row\"><span class=\"info-label\">Duration:</span> {{ $booking->package->duration ?? 'N/A' }}</div>
            <div class=\"info-row\"><span class=\"info-label\">Location:</span> {{ $booking->package->location ?? 'N/A' }}</div>
            <div class=\"info-row\"><span class=\"info-label\">Travel Date:</span> {{ $booking->travel_date ? $booking->travel_date->format('M d, Y') : 'Flexible' }}</div>
            <div class=\"info-row\"><span class=\"info-label\">Travelers:</span> {{ $booking->travellers }} Adults</div>
        </div>

        <!-- Price Breakdown Table -->
        <table>
            <thead>
                <tr>
                    <th>Description</th>
                    <th style=\"text-align: right;\">Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $booking->package->name ?? 'Package' }} ({{ $booking->travellers }} Adults)</td>
                    <td style=\"text-align: right;\">₹{{ number_format($booking->total_amount, 2) }}</td>
                </tr>
            </tbody>
        </table>

        <!-- Total Section -->
        <div class=\"total-section\">
            <div class=\"total-row\">
                <span class=\"total-label\">Subtotal:</span>
                <span class=\"total-amount\">₹{{ number_format($booking->total_amount, 2) }}</span>
            </div>
            <div class=\"total-row grand-total\">
                <span class=\"total-label\">Grand Total:</span>
                <span class=\"total-amount\">₹{{ number_format($booking->total_amount, 2) }}</span>
            </div>
        </div>

        @if($booking->special_requests)
        <div class=\"booking-details\" style=\"margin-top: 30px;\">
            <h3>Special Requests</h3>
            <p>{{ $booking->special_requests }}</p>
        </div>
        @endif

        <!-- Footer -->
        <div class=\"footer\">
            <p><strong>{{ \App\Models\Setting::get('site_name', 'ZUBEEE') }}</strong></p>
            <p>{{ \App\Models\Setting::get('address', '') }}</p>
            <p>Phone: {{ \App\Models\Setting::get('phone', '') }} | Email: {{ \App\Models\Setting::get('contact_email', '') }}</p>
            <p style=\"margin-top: 10px;\">Thank you for choosing {{ \App\Models\Setting::get('site_name', 'ZUBEEE') }}!</p>
        </div>
    </div>
</body>
</html>
