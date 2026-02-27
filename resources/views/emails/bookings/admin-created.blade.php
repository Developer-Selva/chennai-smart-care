{{-- resources/views/emails/bookings/admin-created.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Booking Alert</title>
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            color: #1e293b;
            background-color: #f8fafc;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #2563eb, #1e40af);
            color: white;
            padding: 30px 24px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
        }
        .header p {
            margin: 8px 0 0;
            opacity: 0.9;
            font-size: 16px;
        }
        .badge {
            display: inline-block;
            background: rgba(255,255,255,0.2);
            padding: 6px 16px;
            border-radius: 40px;
            font-size: 14px;
            font-weight: 500;
            margin-top: 12px;
        }
        .content {
            padding: 32px 24px;
        }
        .section-title {
            font-size: 18px;
            font-weight: 600;
            margin: 24px 0 16px;
            color: #0f172a;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 8px;
        }
        .section-title:first-of-type {
            margin-top: 0;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin-bottom: 16px;
        }
        .info-item {
            background: #f8fafc;
            padding: 16px;
            border-radius: 12px;
        }
        .info-label {
            font-size: 12px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }
        .info-value {
            font-size: 18px;
            font-weight: 600;
            color: #0f172a;
        }
        .highlight {
            background: #dbeafe;
            border-left: 4px solid #2563eb;
            padding: 16px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .services-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .services-table th {
            background: #f1f5f9;
            text-align: left;
            padding: 12px;
            font-size: 14px;
            font-weight: 600;
            color: #334155;
        }
        .services-table td {
            padding: 12px;
            border-bottom: 1px solid #e2e8f0;
        }
        .action-buttons {
            display: flex;
            gap: 12px;
            margin: 30px 0 20px;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 500;
            text-decoration: none;
            text-align: center;
            flex: 1;
        }
        .btn-primary {
            background: #2563eb;
            color: white;
        }
        .btn-primary:hover {
            background: #1e40af;
        }
        .btn-secondary {
            background: #f1f5f9;
            color: #334155;
            border: 1px solid #cbd5e1;
        }
        .btn-secondary:hover {
            background: #e2e8f0;
        }
        .footer {
            padding: 24px;
            background: #f8fafc;
            text-align: center;
            font-size: 14px;
            color: #64748b;
            border-top: 1px solid #e2e8f0;
        }
        .urgent-tag {
            background: #ef4444;
            color: white;
            font-size: 12px;
            padding: 4px 8px;
            border-radius: 4px;
            margin-left: 8px;
        }
        @media (max-width: 600px) {
            .info-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔔 New Booking Alert</h1>
            <p>A new booking has been received</p>
            <span class="badge">#{{ $booking->booking_number }}</span>
        </div>

        <div class="content">
            <!-- Urgency indicators -->
            <div class="highlight">
                <strong>⏰ Booking Time:</strong> {{ $booking->created_at->format('l, d M Y h:i A') }}<br>
                @if($booking->created_at->diffInMinutes(now()) < 30)
                    <span style="color: #16a34a;">✓ New booking (within last 30 minutes)</span>
                @endif
            </div>

            <h2 class="section-title">📋 Customer Details</h2>
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Name</div>
                    <div class="info-value">{{ $booking->customer_name }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Phone</div>
                    <div class="info-value">
                        <a href="tel:{{ $booking->guest_phone }}" style="color: #2563eb; text-decoration: none;">
                            {{ $booking->guest_phone }}
                        </a>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-label">Email</div>
                    <div class="info-value">{{ $booking->guest_email ?? 'Not provided' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Customer Type</div>
                    <div class="info-value">
                        @if($booking->user_id)
                            <span style="color: #16a34a;">✓ Registered User</span>
                        @else
                            Guest
                        @endif
                    </div>
                </div>
            </div>

            <h2 class="section-title">📅 Booking Details</h2>
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Date</div>
                    <div class="info-value">{{ $date }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Time Slot</div>
                    <div class="info-value">{{ $slot }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Booking ID</div>
                    <div class="info-value">#{{ $booking->booking_number }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Source</div>
                    <div class="info-value">{{ ucfirst($booking->source) }}</div>
                </div>
            </div>

            <h2 class="section-title">🛠️ Services Requested</h2>
            <table class="services-table">
                <thead>
                    <tr>
                        <th>Service</th>
                        <th>Qty</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($booking->items as $item)
                    <tr>
                        <td>{{ $item->service_name }}</td>
                        <td>{{ $item->quantity ?? 1 }}</td>
                        <td>₹{{ number_format($item->unit_price ?? 0) }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2" style="text-align: right; font-weight: 600;">Total:</td>
                        <td style="font-weight: 600;">₹{{ number_format($booking->total_amount) }}</td>
                    </tr>
                </tfoot>
            </table>

            <h2 class="section-title">📍 Service Address</h2>
            <div style="background: #f8fafc; padding: 16px; border-radius: 12px; margin-bottom: 20px;">
                {{ $booking->address }}<br>
                @if($booking->landmark)
                    <strong>Landmark:</strong> {{ $booking->landmark }}<br>
                @endif
                <strong>Pincode:</strong> {{ $booking->pincode }}
            </div>

            @if($booking->notes)
            <h2 class="section-title">📝 Customer Notes</h2>
            <div style="background: #fff7ed; padding: 16px; border-radius: 12px; color: #9a3412;">
                {{ $booking->notes }}
            </div>
            @endif

            <!-- Action Buttons -->
            <div class="action-buttons">
                <a href="{{ $adminUrl ?? '#' }}" class="btn btn-primary">View in Admin Panel</a>
                <a href="https://wa.me/{{ $booking->guest_phone }}" class="btn btn-secondary">Contact Customer</a>
            </div>

            <!-- Quick Actions -->
            <div style="margin-top: 20px; font-size: 14px; color: #64748b;">
                <strong>Quick Actions:</strong><br>
                • <a href="{{ $adminUrl ?? '#' }}" style="color: #2563eb;">View Booking</a><br>
            </div>
        </div>

        <div class="footer">
            <p>📱 Chennai Smart Care • 24/7 Support: {{ $phone }}</p>
            <p style="font-size: 12px;">© {{ date('Y') }} Chennai Smart Care. All rights reserved.</p>
        </div>
    </div>
</body>
</html>