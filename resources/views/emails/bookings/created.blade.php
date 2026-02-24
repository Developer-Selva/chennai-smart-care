{{-- resources/views/emails/bookings/created.blade.php --}}
@extends('emails.bookings.layout')

@section('title', "Booking Received — #{$booking->booking_number}")

@section('body')
<p class="greeting">Hi <strong>{{ $customerName }}</strong>,</p>

<p style="font-size:15px;color:#374151;line-height:1.6;margin-bottom:16px;">
  Thank you for booking with <strong>Chennai Smart Care</strong>!
  We've received your service request and our team will confirm your appointment shortly.
</p>

<span class="status-badge status-pending">⏳ Pending Confirmation</span>

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
  @if($booking->final_amount)
  <div class="info-row">
    <span class="info-label">Estimated Amount</span>
    <span class="info-value" style="color:#2563eb;">₹{{ number_format($booking->final_amount, 2) }}</span>
  </div>
  @endif
</div>

<div>
  <a href="{{ $trackUrl }}" class="btn">Track Your Booking</a>
</div>

<hr class="divider" />

<p class="help-text">
  📞 <strong>What happens next?</strong><br />
  Our team will call you within <span class="highlight">30–60 minutes</span> to confirm your appointment.
  You'll receive another email and SMS once confirmed.
</p>

<p class="help-text">
  Need to make changes? Call us at <a href="tel:{{ $phone }}" class="highlight">{{ $phone }}</a>
  and mention your booking ID <strong>{{ $booking->booking_number }}</strong>.
</p>
@endsection