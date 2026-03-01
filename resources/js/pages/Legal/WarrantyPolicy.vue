<template>
  <LegalLayout
    title="Service Warranty Policy"
    slug="/service-warranty-policy"
    icon="🛡️"
    meta-desc="Chennai Smart Care's 6-Month Service Warranty Policy — what's covered, how to claim, and what's excluded."
    last-updated="1 March 2025"
    :sections="sections"
  >
    <p>
      At Chennai Smart Care, we stand behind every repair we do. Every completed service comes
      with a <strong>6-month service warranty</strong> — not because we have to, but because we're
      confident in the quality of our work.
    </p>
    <p>
      This policy explains exactly what our warranty covers, how to claim it, and what falls outside its scope.
    </p>

    <!-- Highlight Box -->
    <div style="background: #EFF6FF; border-left: 4px solid #2563EB; padding: 1rem 1.25rem; border-radius: 0.75rem; margin: 1.5rem 0;">
      <p style="margin:0; font-weight: 600; color: #1e40af; font-size: 0.9rem;">
        🛡️ Our Warranty Promise: If the same fault recurs within 6 months of the repair date,
        we'll return and fix it completely — at zero additional cost to you.
      </p>
    </div>

    <h2 id="coverage">1. What the Warranty Covers</h2>
    <p>Our 6-month service warranty covers:</p>
    <ul>
      <li><strong>The specific defect repaired</strong> — if it returns within 6 months, we fix it free</li>
      <li><strong>Parts replaced during the service</strong> — any part we installed is covered for 6 months against defect</li>
      <li><strong>Labour charges</strong> — warranty recall visits are at zero labour cost</li>
      <li><strong>All appliance categories</strong> — AC, Refrigerator, Washing Machine, Microwave Oven</li>
    </ul>

    <h3>Warranty by Service Type</h3>
    <ul>
      <li><strong>AC repair & gas refilling:</strong> 6 months on the repaired component; 3 months on gas refill</li>
      <li><strong>Refrigerator repair:</strong> 6 months on repaired parts and components</li>
      <li><strong>Washing Machine repair:</strong> 6 months on motor, drum, PCB repairs; 3 months on belt, filter</li>
      <li><strong>Microwave Oven repair:</strong> 6 months on magnetron and PCB; 3 months on other components</li>
      <li><strong>Routine servicing (cleaning, filter wash):</strong> 30 days</li>
    </ul>

    <h2 id="exclusions">2. What the Warranty Does Not Cover</h2>
    <p>The following are explicitly excluded from our warranty:</p>
    <ul>
      <li>
        <strong>New or unrelated faults</strong> — a different fault appearing after the repair is not covered
        (e.g., if we fixed a compressor and later a PCB fails, the PCB is not under warranty)
      </li>
      <li>
        <strong>Physical damage:</strong> Damage caused by falls, power surges, flooding, fire, or accidents
        after the repair
      </li>
      <li>
        <strong>Third-party interference:</strong> The warranty is voided if the appliance is subsequently
        repaired, modified, or tampered with by another service provider or the customer
      </li>
      <li>
        <strong>Improper use:</strong> Damage resulting from incorrect operation, overloading, or use
        contrary to the manufacturer's guidelines
      </li>
      <li>
        <strong>Consumables:</strong> Items like AC filters, washing machine lint filters, door gaskets
        subject to normal wear and tear
      </li>
      <li>
        <strong>Cosmetic issues:</strong> Scratches, dents, or panel damage not related to the repair
      </li>
      <li>
        <strong>Power-related damage:</strong> Faults caused by unstable voltage, power cuts, or lack
        of voltage stabiliser (we recommend using a stabiliser for all appliances)
      </li>
      <li>
        <strong>Post-relocation issues:</strong> Problems arising from moving the appliance to a new
        location after the repair
      </li>
    </ul>

    <h2 id="warranty-card">3. Your Digital Warranty Card</h2>
    <p>
      After every completed repair, you will receive a <strong>digital warranty card</strong>
      automatically accessible in your customer dashboard at
      <a href="https://chennaismartcare.com/user/dashboard">chennaismartcare.com/user/dashboard</a>.
    </p>
    <p>Your warranty card includes:</p>
    <ul>
      <li>Unique warranty reference number (e.g. WRN-2025-00142)</li>
      <li>Service date and expiry date</li>
      <li>Appliance details and fault description</li>
      <li>Parts replaced (if any)</li>
      <li>Assigned technician name</li>
      <li>Claim instructions</li>
    </ul>
    <p>
      You can also request a PDF warranty certificate via
      <a href="mailto:support@chennaismartcare.com">support@chennaismartcare.com</a>
      or by calling us.
    </p>

    <h2 id="claiming">4. How to Claim Warranty Service</h2>
    <p>Claiming your warranty is simple:</p>
    <ol>
      <li>
        <strong>Contact us</strong> within the warranty period — call <a href="tel:+919444900470">+91 94449 00470</a>,
        WhatsApp, or email <a href="mailto:support@chennaismartcare.com">support@chennaismartcare.com</a>
      </li>
      <li>
        <strong>Provide your warranty reference number</strong> or the original booking number
        (e.g. CSC-2025-XXXXX)
      </li>
      <li>
        <strong>Describe the recurring fault</strong> — a brief description helps us dispatch
        the right technician with the right tools
      </li>
      <li>
        <strong>Schedule a visit</strong> — we will confirm a warranty recall appointment within 4 hours
        during business hours (9 AM – 9 PM)
      </li>
      <li>
        <strong>Technician visits</strong> and fixes the fault — at zero cost to you
      </li>
    </ol>

    <h2 id="response-time">5. Warranty Recall Response Time</h2>
    <ul>
      <li><strong>Warranty claims raised before 2 PM:</strong> Same-day visit wherever possible</li>
      <li><strong>All warranty claims:</strong> Technician dispatched within 24 hours</li>
      <li>Priority is given to warranty recalls during peak season (April–June for AC)</li>
    </ul>

    <h2 id="parts-warranty">6. Parts & Spare Parts Warranty</h2>
    <p>
      All spare parts installed by Chennai Smart Care are <strong>genuine or OEM-equivalent parts</strong>
      sourced from authorised distributors.
    </p>
    <ul>
      <li>Parts carry the same 6-month warranty as the service</li>
      <li>If a part fails within the warranty period, it is replaced at no charge</li>
      <li>We do not use low-quality imitation parts. If you suspect otherwise, report it immediately</li>
    </ul>

    <h2 id="multiple-repairs">7. Multiple Repairs</h2>
    <p>
      If multiple faults were repaired in a single visit, each repaired fault carries its own
      6-month warranty independently. A new fault unrelated to the original repairs is not covered
      but can be booked as a fresh service.
    </p>

    <h2 id="amc-warranty">8. AMC & Extended Warranty</h2>
    <p>
      Customers with an Annual Maintenance Contract (AMC) receive enhanced warranty terms:
    </p>
    <ul>
      <li>All repairs under AMC carry a <strong>12-month warranty</strong> (double the standard)</li>
      <li>Priority response for warranty recall — within 12 hours</li>
      <li>Free follow-up visits included as part of the AMC plan</li>
    </ul>
    <p>
      See our <a href="/terms">Terms & Conditions</a> for full AMC details.
    </p>

    <h2 id="contact">9. Contact for Warranty</h2>
    <ul>
      <li><strong>Phone / WhatsApp:</strong> <a href="tel:+919444900470">+91 94449 00470</a></li>
      <li><strong>Email:</strong> <a href="mailto:warranty@chennaismartcare.in">warranty@chennaismartcare.in</a></li>
      <li><strong>Hours:</strong> 9:00 AM – 9:00 PM, 7 days a week</li>
      <li><strong>Dashboard:</strong> <a href="/user/dashboard">My Account → Warranties</a></li>
    </ul>
  </LegalLayout>
</template>

<script setup>
import LegalLayout from '../Legal/Layout.vue'

const sections = [
  { id: 'coverage',       title: '1. What\'s Covered' },
  { id: 'exclusions',     title: '2. Exclusions' },
  { id: 'warranty-card',  title: '3. Digital Warranty Card' },
  { id: 'claiming',       title: '4. How to Claim' },
  { id: 'response-time',  title: '5. Response Time' },
  { id: 'parts-warranty', title: '6. Parts Warranty' },
  { id: 'multiple-repairs', title: '7. Multiple Repairs' },
  { id: 'amc-warranty',   title: '8. AMC & Extended Warranty' },
  { id: 'contact',        title: '9. Contact' },
]
</script>