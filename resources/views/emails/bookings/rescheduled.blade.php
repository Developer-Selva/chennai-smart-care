{{-- resources/views/emails/bookings/rescheduled.blade.php --}}
@extends('emails.bookings.layout')

@section('title', "Booking Rescheduled — #{$booking->booking_number}")

@section('body')
<p class="greeting">Hi <strong>{{ $customerName }}</strong>,</p>

<p style="font-size:15px;color:#374151;line-height:1.6;margin-bottom:16px;">
  Your booking <strong>{{ $booking->booking_number }}</strong> has been rescheduled.
  Here are your updated appointment details:
</p>

<span class="status-badge status-rescheduled">📅 Rescheduled</span>

<div class="info-box">
  <div class="info-row">
    <span class="info-label">Booking ID</span>
    <span class="info-value" style="font-family:monospace;">{{ $booking->booking_number }}</span>
  </div>
  <div class="info-row">
    <span class="info-label">Service(s)</span>
    <span class="info-value">{{ $services }}</span>
  </div>
  <div class="info-row" style="background:#eff6ff;border-radius:8px;padding:10px 8px;">
    <span class="info-label" style="color:#1d4ed8;">📅 New Date</span>
    <span class="info-value" style="color:#1d4ed8;">{{ $newDate }}</span>
  </div>
  <div class="info-row" style="background:#eff6ff;border-radius:8px;padding:10px 8px;">
    <span class="info-label" style="color:#1d4ed8;">🕐 New Time Slot</span>
    <span class="info-value" style="color:#1d4ed8;">{{ $newSlot }}</span>
  </div>
  <div class="info-row">
    <span class="info-label">Service Address</span>
    <span class="info-value" style="max-width:260px;">{{ $address }}</span>
  </div>
</div>

<div>
  <a href="{{ $trackUrl }}" class="btn">View Booking Details</a>
</div>

<hr class="divider" />

<p class="help-text">
  If this rescheduling was done in error or if the new time doesn't suit you,
  please call us immediately at <a href="tel:{{ $phone }}" class="highlight">{{ $phone }}</a>.
</p>

<p class="help-text">
  We apologise for any inconvenience and appreciate your patience.
  Our team will be there on the updated date and time.
</p>
@endsection