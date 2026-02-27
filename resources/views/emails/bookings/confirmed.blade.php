{{-- resources/views/emails/bookings/confirmed.blade.php --}}
@extends('emails.bookings.layout')

@section('title', "Booking Confirmed — #{$booking->booking_number}")

@section('body')
<p class="greeting">Hi <strong>{{ $customerName }}</strong>,</p>

<p style="font-size:15px;color:#374151;line-height:1.6;margin-bottom:16px;">
  Great news! Your service booking has been <strong style="color:#059669;">confirmed</strong>.
  Our technician will be at your location at the scheduled time.
</p>

<span class="status-badge status-confirmed">✅ Confirmed</span>

<div class="info-box">
  <div class="info-row">
    <span class="info-label">Booking ID</span>
    <span class="info-value" style="font-family:monospace;">{{ $booking->booking_number }}</span>
  </div>
  <div class="info-row">
    <span class="info-label">Service(s)</span>
    <span class="info-value">{{ $services }}</span>
  </div>
  <div class="info-row">
    <span class="info-label">Date</span>
    <span class="info-value">{{ $date }}</span>
  </div>
  <div class="info-row">
    <span class="info-label">Time Slot</span>
    <span class="info-value">{{ $slot }}</span>
  </div>
  <div class="info-row">
    <span class="info-label">Service Address</span>
    <span class="info-value" style="max-width:260px;">{{ $address }}</span>
  </div>
  @if($technician)
  <div class="info-row">
    <span class="info-label">Technician</span>
    <span class="info-value">{{ $technician->name }} &nbsp;📞 {{ $technician->phone }}</span>
  </div>
  @endif
  @if($booking->final_amount)
  <div class="info-row">
    <span class="info-label">Estimated Amount</span>
    <span class="info-value" style="color:#2563eb;">₹{{ number_format($booking->final_amount, 2) }}</span>
  </div>
  @endif
</div>

<div>
  <a href="{{ $trackUrl }}" class="btn">Track Booking</a>
  <a href="https://wa.me/{{ preg_replace('/\D/', '', $phone) }}?text=Hi%2C%20my%20booking%20is%20{{ $booking->booking_number }}" class="btn-outline">Chat on WhatsApp</a>
</div>

<hr class="divider" />

<p class="help-text">
  💡 <strong>Tips for a smooth service visit:</strong><br />
  • Ensure the appliance is accessible for the technician<br />
  • Keep your booking ID handy: <strong>{{ $booking->booking_number }}</strong><br />
  • Payment is collected after the service is complete (cash/UPI accepted)
</p>

<p class="help-text">
  Need to reschedule or cancel? Call us at <a href="tel:{{ $phone }}" class="highlight">{{ $phone }}</a>
  at least 2 hours before the appointment.
</p>
@endsection