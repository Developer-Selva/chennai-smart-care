<template>
  <LegalLayout
    title="Disclaimer"
    slug="/disclaimer"
    icon="⚠️"
    meta-desc="Chennai Smart Care Disclaimer — important notices regarding our website content, service advice, and limitation of liability."
    last-updated="1 March 2025"
    :sections="sections"
  >
    <p>
      The information provided on this website (<a href="https://chennaismartcare.com">chennaismartcare.com</a>)
      and through our services is for general informational and service purposes only. Please read
      this disclaimer carefully before relying on any content or information provided here.
    </p>

    <h2 id="website-content">1. Website Content</h2>
    <p>
      While we strive to keep all information on this website accurate, complete, and up to date,
      <strong>Chennai Smart Care makes no warranties or representations</strong>, express or implied,
      about the completeness, accuracy, reliability, suitability, or availability of any information,
      services, or related graphics on the website.
    </p>
    <ul>
      <li>Pricing shown on the website is indicative and subject to change based on diagnosis.</li>
      <li>Service availability by area is subject to technician availability at the time of booking.</li>
      <li>Repair timeframes are estimates and may vary based on spare part availability and fault complexity.</li>
      <li>Brand logos, model names, and trademarks mentioned on our site belong to their respective owners.</li>
    </ul>

    <h2 id="repair-advice">2. DIY Repair & Technical Advice</h2>
    <p>
      Our blog articles, guides, and FAQs are provided for <strong>general informational purposes only</strong>.
      Any DIY repair tips or troubleshooting guides on our website:
    </p>
    <ul>
      <li>Should not be treated as professional technical advice for your specific appliance</li>
      <li>Are followed at your own risk — Chennai Smart Care accepts no liability for damage caused by self-repair attempts</li>
      <li>Do not constitute a warranty or guarantee of any outcome</li>
    </ul>
    <p>
      We strongly recommend having appliances — particularly air conditioners, refrigerators, and
      washing machines — serviced only by trained and certified technicians. Incorrect repairs can
      cause safety hazards, void manufacturer warranties, and worsen the original fault.
    </p>

    <h2 id="third-party-brands">3. Third-Party Brands & Manufacturers</h2>
    <p>
      Chennai Smart Care is an <strong>independent repair service provider</strong> and is not affiliated
      with, endorsed by, or an authorised service centre of any appliance brand (Samsung, LG, Voltas,
      Daikin, Whirlpool, etc.) unless explicitly stated.
    </p>
    <ul>
      <li>Repairs by Chennai Smart Care may affect the manufacturer's original warranty on new appliances.</li>
      <li>We recommend checking your appliance's manufacturer warranty status before booking our service.</li>
      <li>All brand names mentioned are trademarks of their respective companies.</li>
    </ul>

    <h2 id="liability">4. Limitation of Liability</h2>
    <p>
      To the fullest extent permitted by applicable law, Chennai Smart Care, its directors,
      employees, and technicians shall not be liable for any:
    </p>
    <ul>
      <li>Indirect, incidental, or consequential damages arising from use of our services</li>
      <li>Loss of data, revenue, or business resulting from appliance downtime</li>
      <li>Damage to property not directly caused by our technician's negligence</li>
      <li>Losses arising from force majeure events (floods, power outages, civil unrest)</li>
    </ul>
    <p>
      Our total liability in any circumstance is limited to the amount paid by the customer
      for the specific service in question.
    </p>

    <h2 id="external-links">5. External Links</h2>
    <p>
      Our website may contain links to third-party websites. These links are provided for your
      convenience. Chennai Smart Care has no control over the content or practices of external sites
      and accepts no responsibility for them. Accessing external links is at your own discretion and risk.
    </p>

    <h2 id="safety">6. Safety Warning</h2>
    <p>
      Home appliances involve electricity, refrigerants, and high-pressure systems.
      <strong>Attempting repairs without proper training can result in electric shock, gas leaks,
      fire, or serious injury.</strong> Always use qualified technicians. If you smell gas or burning
      from an appliance, switch off the power at the mains and call a professional immediately.
    </p>

    <h2 id="changes">7. Changes to This Disclaimer</h2>
    <p>
      We reserve the right to update this disclaimer at any time. The "Last updated" date at
      the top of this page will reflect the most recent revision. Continued use of our website
      constitutes acceptance of the current version.
    </p>

    <h2 id="contact">8. Contact</h2>
    <p>If you have questions about this disclaimer:</p>
    <ul>
      <li><strong>Email:</strong> <a href="mailto:support@chennaismartcare.com">support@chennaismartcare.com</a></li>
      <li><strong>Phone:</strong> <a href="tel:+919444900470">+91 94449 00470</a></li>
    </ul>
  </LegalLayout>
</template>

<script setup>
import LegalLayout from '../Legal/Layout.vue'

const sections = [
  { id: 'website-content',    title: '1. Website Content' },
  { id: 'repair-advice',      title: '2. DIY Repair Advice' },
  { id: 'third-party-brands', title: '3. Third-Party Brands' },
  { id: 'liability',          title: '4. Limitation of Liability' },
  { id: 'external-links',     title: '5. External Links' },
  { id: 'safety',             title: '6. Safety Warning' },
  { id: 'changes',            title: '7. Changes' },
  { id: 'contact',            title: '8. Contact' },
]
</script>