<template>
  <LegalLayout
    title="Refund & Cancellation Policy"
    slug="/refund-policy"
    icon="💰"
    meta-desc="Chennai Smart Care Refund and Cancellation Policy — how cancellations, visiting charges, and refunds are handled."
    last-updated="1 March 2025"
    :sections="sections"
  >
    <p>
      At Chennai Smart Care, we strive to make every service experience satisfying. This policy
      outlines our approach to cancellations and refunds so you know exactly what to expect.
    </p>
    <p>
      We keep this simple: <strong>if we don't fix it, you don't fully pay for it.</strong>
    </p>

    <h2 id="visiting-charge">1. Visiting Charge</h2>
    <p>
      A <strong>non-refundable visiting charge of ₹199</strong> is applicable for all service calls.
      This covers the technician's travel, time, and initial diagnosis.
    </p>
    <ul>
      <li>The visiting charge is collected <strong>after</strong> the technician completes the diagnosis, not in advance.</li>
      <li>
        If you proceed with the repair, the ₹199 visiting charge is <strong>fully adjusted</strong> against
        the total repair bill — so you pay only the net repair amount.
      </li>
      <li>
        If you decline the repair after receiving the diagnosis and quote, only the ₹199
        visiting charge is payable.
      </li>
      <li>
        The visiting charge is <strong>waived entirely</strong> if our technician is unable to diagnose
        the fault or if no fault is found.
      </li>
    </ul>

    <h2 id="cancellation-policy">2. Cancellation Policy</h2>
    <h3>Cancellation Before Technician Dispatch</h3>
    <ul>
      <li>
        <strong>Free cancellation</strong> is available if you cancel at least
        <strong>2 hours before your scheduled appointment</strong>.
      </li>
      <li>No charges apply for cancellations made before technician dispatch.</li>
      <li>To cancel, call <a href="tel:+919444900470">+91 94449 00470</a> or use your customer dashboard.</li>
    </ul>

    <h3>Cancellation After Technician Dispatch</h3>
    <ul>
      <li>
        If you cancel after the technician has been dispatched (is en route), the
        <strong>₹199 visiting charge applies</strong> to cover travel costs.
      </li>
      <li>If the technician has already arrived at your location, the visiting charge is fully payable.</li>
    </ul>

    <h3>Rescheduling</h3>
    <ul>
      <li>You may reschedule your appointment free of charge up to <strong>1 hour before</strong> the scheduled time.</li>
      <li>Rescheduling is subject to technician availability.</li>
      <li>
        We allow a maximum of <strong>2 reschedules</strong> per booking. After that, the booking will be
        cancelled and you will need to create a new booking.
      </li>
    </ul>

    <h2 id="refund-scenarios">3. Refund Scenarios</h2>

    <h3>Prepaid Bookings</h3>
    <p>
      We do not currently require prepayment for standard bookings. Payment is collected after the
      service is complete. If you have made an advance payment:
    </p>
    <ul>
      <li>Full refund if cancelled before technician dispatch</li>
      <li>₹199 deducted if cancelled after dispatch</li>
      <li>Full refund if we are unable to attend the appointment</li>
    </ul>

    <h3>AMC (Annual Maintenance Contract) Refunds</h3>
    <ul>
      <li>
        <strong>Within 7 days of purchase</strong> with no service calls used: Full refund, no questions asked.
      </li>
      <li>
        <strong>After 7 days or after using any service call:</strong> Pro-rated refund based on
        unused months remaining, minus the cost of services utilised.
      </li>
      <li>AMC refund requests must be submitted in writing to <a href="mailto:support@chennaismartcare.com">support@chennaismartcare.com</a>.</li>
    </ul>

    <h3>Warranty Recall Service</h3>
    <p>
      If a repair fails within the 6-month warranty period, we will re-attend free of charge.
      See our <a href="/service-warranty-policy">Service Warranty Policy</a> for full details.
      No refund is issued for warranty recall situations — we fix it.
    </p>

    <h3>Service Not Completed</h3>
    <p>
      If our technician is unable to complete a repair due to unavailability of parts or technical
      complexity beyond our scope, you will not be charged the repair amount. The visiting charge
      (₹199) may still apply for the diagnosis visit.
    </p>

    <h2 id="no-fix-no-fee">4. Our No Fix, No Fee Commitment</h2>
    <p>
      If our technician attempts a repair and the same problem reappears within <strong>7 days</strong>,
      we will return and fix it at <strong>no additional charge</strong> — this applies even outside
      the formal warranty policy. Just call us within 7 days.
    </p>

    <h2 id="refund-process">5. Refund Processing</h2>
    <ul>
      <li>Approved refunds are processed within <strong>5–7 business days</strong>.</li>
      <li>Refunds are issued via the original payment method (UPI / bank transfer).</li>
      <li>Cash payments are refunded via UPI or bank transfer to your registered account.</li>
      <li>Refund requests must be raised within <strong>30 days</strong> of the service date.</li>
    </ul>

    <h2 id="how-to-request">6. How to Request a Refund or Cancel</h2>
    <ul>
      <li><strong>Phone / WhatsApp:</strong> <a href="tel:+919444900470">+91 94449 00470</a> (9 AM – 9 PM)</li>
      <li><strong>Email:</strong> <a href="mailto:support@chennaismartcare.com">support@chennaismartcare.com</a></li>
      <li><strong>Customer Dashboard:</strong> Login → My Bookings → Cancel / Request Refund</li>
    </ul>
    <p>Please have your booking reference number (e.g. CSC-2025-XXXXX) ready when contacting us.</p>

    <h2 id="contact">7. Contact</h2>
    <p>For any refund or cancellation queries:</p>
    <ul>
      <li><strong>Email:</strong> <a href="mailto:support@chennaismartcare.com">support@chennaismartcare.com</a></li>
      <li><strong>Phone:</strong> <a href="tel:+919444900470">+91 94449 00470</a></li>
      <li><strong>Hours:</strong> 9:00 AM – 9:00 PM, Monday to Sunday</li>
    </ul>
  </LegalLayout>
</template>

<script setup>
import LegalLayout from '../Legal/Layout.vue'

const sections = [
  { id: 'visiting-charge',    title: '1. Visiting Charge' },
  { id: 'cancellation-policy', title: '2. Cancellation Policy' },
  { id: 'refund-scenarios',   title: '3. Refund Scenarios' },
  { id: 'no-fix-no-fee',      title: '4. No Fix, No Fee' },
  { id: 'refund-process',     title: '5. Refund Processing' },
  { id: 'how-to-request',     title: '6. How to Request' },
  { id: 'contact',            title: '7. Contact' },
]
</script>