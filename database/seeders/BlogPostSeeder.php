<?php
// ============================================================
// DATABASE SEEDER — database/seeders/BlogPostSeeder.php
// SEO-optimised blog posts targeting Chennai localities
// ============================================================
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use App\Models\BlogCategory;
use App\Models\BlogPost;

class BlogPostSeeder extends Seeder
{
    public function run(): void
    {
        $admin = Admin::first();

        // ── Blog categories (get or create) ──────────────────
        $catAC      = BlogCategory::firstOrCreate(['slug' => 'ac-repair-tips'],           ['name' => 'AC Repair Tips']);
        $catFridge  = BlogCategory::firstOrCreate(['slug' => 'refrigerator-care'],         ['name' => 'Refrigerator Care']);
        $catWM      = BlogCategory::firstOrCreate(['slug' => 'washing-machine-guide'],     ['name' => 'Washing Machine Guide']);
        $catMicro   = BlogCategory::firstOrCreate(['slug' => 'microwave-oven-repair-chennai'], ['name' => 'Microwave Oven Service']);

        // ═══════════════════════════════════════════════════════
        //  AC REPAIR — POST 1
        //  Target: Anna Nagar, T. Nagar, Nungambakkam, Kilpauk,
        //          Aminjikarai, Choolaimedu, Shenoy Nagar
        // ═══════════════════════════════════════════════════════
        BlogPost::create([
            'blog_category_id' => $catAC->id,
            'author_id'        => $admin->id,
            'title'            => 'AC Repair in Anna Nagar, T. Nagar & Central Chennai – Same-Day Service',
            'slug'             => 'ac-repair-anna-nagar-t-nagar-central-chennai',
            'excerpt'          => 'Looking for AC repair in Anna Nagar, T. Nagar, Nungambakkam or Kilpauk? Get certified AC technicians at your doorstep within 60 minutes. All brands, transparent pricing.',
            'content'          => '
<h1>AC Repair in Anna Nagar, T. Nagar & Central Chennai</h1>

<p>Is your air conditioner not cooling on a hot Chennai day? Whether you are in <strong>Anna Nagar</strong>, <strong>T. Nagar</strong>, <strong>Nungambakkam</strong>, <strong>Kilpauk</strong>, <strong>Aminjikarai</strong>, <strong>Shenoy Nagar</strong>, or <strong>Choolaimedu</strong>, Chennai Smart Care provides <strong>same-day AC repair</strong> at your doorstep.</p>

<p>Our certified technicians reach you within 60 minutes in most central Chennai areas, diagnose the problem transparently, and fix it with genuine spare parts — all backed by a <strong>6-month service warranty</strong>.</p>

<hr>

<h2>Common AC Problems We Fix in Central Chennai</h2>

<h3>1. AC Not Cooling in Anna Nagar</h3>
<p>Anna Nagar apartments often have ACs running 12–16 hours a day during summer. This leads to refrigerant depletion, dirty coils, and compressor wear. If your split AC is blowing warm air, the most likely causes are:</p>
<ul>
<li>Low refrigerant gas (R22, R32 or R410A)</li>
<li>Dirty or clogged air filter</li>
<li>Frozen evaporator coil</li>
<li>Faulty thermostat</li>
<li>Compressor failure</li>
</ul>
<p><strong>Our fix:</strong> We perform a full pressure test, refill gas if needed, clean coils, and test the unit before leaving.</p>

<h3>2. AC Water Leakage in T. Nagar</h3>
<p>Older buildings in T. Nagar and Mambalam often have drainage alignment issues that cause indoor AC units to drip water on walls and floors. Common reasons:</p>
<ul>
<li>Blocked drain pipe or tray</li>
<li>Improper installation angle</li>
<li>Frozen coil thawing</li>
</ul>
<p><strong>Our fix:</strong> We flush the drain pipe, clean the drain tray, and re-align the indoor unit to the correct angle.</p>

<h3>3. AC PCB / Motherboard Failure in Nungambakkam</h3>
<p>Power fluctuations in Nungambakkam and Kilpauk can damage AC circuit boards. Symptoms include the unit not turning on, error codes on the display, or random shutoffs.</p>
<p><strong>Our fix:</strong> We diagnose the PCB, repair faulty components, or replace the board with an OEM-compatible unit.</p>

<h3>4. AC Gas Refilling in Shenoy Nagar</h3>
<p>If your AC was not serviced in the last 2 years, the refrigerant level may have dropped. Signs include the AC running but room not reaching the set temperature, ice forming on the copper pipe, or high electricity bills.</p>
<p><strong>Cost:</strong> AC gas refilling in Chennai starts from ₹1,200 for window ACs and ₹1,500 for split ACs.</p>

<h3>5. Noisy AC in Aminjikarai & Choolaimedu</h3>
<p>Rattling, grinding, or squealing noises from an AC unit usually indicate fan blade damage, loose motor mounts, or a failing compressor bearing.</p>
<p><strong>Our fix:</strong> We identify the noise source, tighten loose parts, replace worn fan blades, or service the compressor.</p>

<hr>

<h2>AC Brands We Service in Anna Nagar & T. Nagar</h2>
<p>Our technicians are trained to service all major AC brands used across central Chennai:</p>
<ul>
<li>Samsung, LG, Voltas, Daikin, Hitachi</li>
<li>Blue Star, Carrier, Mitsubishi, O General</li>
<li>Whirlpool, Godrej, Lloyd, Panasonic</li>
</ul>

<hr>

<h2>AC Service Charges in Chennai (2025)</h2>
<table border="1" cellpadding="8" cellspacing="0">
<thead>
<tr><th>Service</th><th>Starting Price</th></tr>
</thead>
<tbody>
<tr><td>AC General Service & Cleaning</td><td>₹499</td></tr>
<tr><td>AC Gas Refilling (per unit)</td><td>₹1,200</td></tr>
<tr><td>AC PCB Repair</td><td>₹1,500</td></tr>
<tr><td>AC Compressor Replacement</td><td>₹5,000</td></tr>
<tr><td>AC Installation</td><td>₹1,200</td></tr>
</tbody>
</table>
<p><em>Final price provided after diagnosis. No hidden charges.</em></p>

<hr>

<h2>How to Book AC Repair in Anna Nagar or T. Nagar</h2>
<ol>
<li>Call us at <a href="tel:+919444900470">+91 94449 00470</a> or book online</li>
<li>Tell us your location (Anna Nagar, T. Nagar, Nungambakkam, etc.) and AC brand</li>
<li>Technician arrives within 60 minutes</li>
<li>Diagnosis is done and estimate provided</li>
<li>Repair completed with genuine spare parts</li>
<li>6-month warranty provided on all work</li>
</ol>

<hr>

<h2>Areas Covered in Central Chennai</h2>
<ul>
<li>Anna Nagar, Anna Nagar West</li>
<li>T. Nagar, Mambalam, Nandanam</li>
<li>Nungambakkam, Kilpauk, Shenoy Nagar</li>
<li>Aminjikarai, Choolaimedu, Arumbakkam</li>
<li>Kodambakkam, Ashok Nagar, K. K. Nagar</li>
<li>Egmore, Royapettah, Teynampet</li>
<li>Alwarpet, Gopalapuram, Chetpet</li>
</ul>

<hr>

<h2>Frequently Asked Questions – AC Repair Central Chennai</h2>

<h3>How quickly can a technician arrive in Anna Nagar?</h3>
<p>Our technicians are stationed across Chennai. In Anna Nagar, T. Nagar, and Nungambakkam, we typically arrive within 30–60 minutes of booking.</p>

<h3>Is AC gas refilling needed every year?</h3>
<p>Not necessarily. A well-maintained AC with no leaks holds gas for 3–5 years. If your AC suddenly stops cooling, we first check for leaks before refilling gas.</p>

<h3>Do you service inverter ACs in T. Nagar?</h3>
<p>Yes. We service both inverter and non-inverter AC models from all brands.</p>

<h3>What is included in an AC general service?</h3>
<p>Filter cleaning, coil wash, drain flush, electrical connection check, gas pressure verification, and performance test — all included in our ₹499 service package.</p>

<p><strong>Call <a href="tel:+919444900470">+91 94449 00470</a> now for AC repair in Anna Nagar, T. Nagar, Nungambakkam and all central Chennai areas.</strong></p>
',
            'status'           => 'published',
            'published_at'     => now()->subDays(1),
            'meta_title'       => 'AC Repair in Anna Nagar, T. Nagar & Central Chennai | Same-Day Service',
            'meta_description' => 'Expert AC repair in Anna Nagar, T. Nagar, Nungambakkam, Kilpauk & central Chennai. Certified technicians, 6-month warranty, transparent pricing. Call +91 94449 00470.',
            'focus_keyword'    => 'AC repair Anna Nagar Chennai',
            'tags'             => ['AC repair Anna Nagar', 'AC repair T. Nagar', 'AC service Chennai', 'AC not cooling Chennai', 'AC gas refilling Chennai'],
            'read_time_minutes'        => 6,
        ]);

        // ═══════════════════════════════════════════════════════
        //  AC REPAIR — POST 2
        //  Target: Adyar, Mylapore, Velachery, Sholinganallur,
        //          Perungudi, Pallikaranai, Guindy, Nanganallur
        // ═══════════════════════════════════════════════════════
        BlogPost::create([
            'blog_category_id' => $catAC->id,
            'author_id'        => $admin->id,
            'title'            => 'AC Repair in Adyar, Mylapore & South Chennai – Expert Doorstep Service',
            'slug'             => 'ac-repair-adyar-mylapore-south-chennai',
            'excerpt'          => 'Need AC repair in Adyar, Mylapore, Velachery, Sholinganallur or Guindy? Chennai Smart Care provides same-day AC service across all South Chennai neighbourhoods.',
            'content'          => '
<h1>AC Repair in Adyar, Mylapore & South Chennai</h1>

<p>South Chennai residents rely heavily on air conditioning year-round. If your AC stops working in <strong>Adyar</strong>, <strong>Mylapore</strong>, <strong>Velachery</strong>, <strong>Guindy</strong>, <strong>Sholinganallur</strong>, <strong>Perungudi</strong>, <strong>Pallikaranai</strong> or <strong>Nanganallur</strong>, our expert AC repair team reaches you the same day.</p>

<p>We service all AC brands, provide a written estimate before starting work, and back every repair with a <strong>6-month service warranty</strong>.</p>

<hr>

<h2>AC Problems Common in South Chennai Homes</h2>

<h3>AC Not Cooling in Adyar & Mylapore</h3>
<p>Sea breeze from Adyar and Besant Nagar introduces salt and moisture that corrode AC coils and copper pipes faster than inland areas. If you live near the coast, annual AC servicing is essential. Signs that your AC needs attention:</p>
<ul>
<li>Room temperature not dropping below 26–27°C despite AC running</li>
<li>AC running but humidity remains high</li>
<li>Unusual smell from the indoor unit (mould or damp smell)</li>
</ul>

<h3>Frequent AC Tripping in Velachery & Pallikaranai</h3>
<p>Areas like Velachery and Pallikaranai sometimes experience voltage fluctuations during peak summer. This causes AC circuit breakers to trip. Our technicians check the compressor load, capacitor health, and electrical connections to prevent recurrence.</p>

<h3>AC Gas Leakage in Sholinganallur & Perungudi</h3>
<p>IT corridor apartments in Sholinganallur and Perungudi often have shared wall installations where vibration from adjacent units stresses copper pipes, causing slow gas leaks. We use electronic leak detectors to locate even the smallest leaks before refilling refrigerant.</p>

<h3>AC Not Turning On in Guindy & Nanganallur</h3>
<p>This can be caused by a blown capacitor, PCB fault, or simply a tripped breaker. Our technicians diagnose the exact cause — we never guess or replace parts unnecessarily.</p>

<hr>

<h2>AC Annual Maintenance Contract (AMC) – South Chennai</h2>
<p>For homes in Adyar, Mylapore, Besant Nagar and coastal areas, we recommend an <strong>AC Annual Maintenance Contract (AMC)</strong>. This includes:</p>
<ul>
<li>2 full service visits per year</li>
<li>Filter cleaning every visit</li>
<li>Gas pressure check</li>
<li>Priority emergency response</li>
<li>10% discount on spare parts</li>
</ul>

<hr>

<h2>Split AC vs Window AC Repair Cost in South Chennai</h2>
<table border="1" cellpadding="8" cellspacing="0">
<thead>
<tr><th>Issue</th><th>Split AC</th><th>Window AC</th></tr>
</thead>
<tbody>
<tr><td>General Service</td><td>₹499</td><td>₹399</td></tr>
<tr><td>Gas Refilling</td><td>₹1,500</td><td>₹1,200</td></tr>
<tr><td>PCB Repair</td><td>₹1,500</td><td>₹1,200</td></tr>
<tr><td>Fan Motor Replacement</td><td>₹1,800</td><td>₹1,500</td></tr>
</tbody>
</table>

<hr>

<h2>South Chennai Areas We Cover</h2>
<ul>
<li>Adyar, Besant Nagar, Thiruvanmiyur</li>
<li>Mylapore, Mandavelli, Raja Annamalaipuram</li>
<li>Velachery, Pallikaranai, Medavakkam</li>
<li>Sholinganallur, Perungudi, Tharamani</li>
<li>Guindy, Ekkatuthangal, St. Thomas Mount</li>
<li>Nanganallur, Palavanthangal, Madipakkam</li>
<li>Saidapet, Kotturpuram, Kasturba Nagar</li>
</ul>

<hr>

<h2>FAQs – AC Repair South Chennai</h2>

<h3>Does sea air affect my AC in Adyar?</h3>
<p>Yes. Salt in the sea air corrodes the condenser fins and copper pipes faster. We recommend yearly servicing and applying anti-corrosion coating on the outdoor unit if you live within 3 km of the coast.</p>

<h3>My AC in Velachery is making a rattling noise at night. What is the issue?</h3>
<p>Rattling noise usually comes from loose fan blades, a worn-out blower wheel, or debris stuck in the outdoor unit. Our technician will identify and fix the noise source on the spot.</p>

<h3>How often should I service my AC in Sholinganallur?</h3>
<p>Once a year is recommended. If the AC runs for more than 8 hours daily (common in home offices), service every 6 months for best efficiency.</p>

<p><strong>Book AC repair in Adyar, Mylapore, Velachery or Sholinganallur — call <a href="tel:+919444900470">+91 94449 00470</a>.</strong></p>
',
            'status'           => 'published',
            'published_at'     => now()->subDays(2),
            'meta_title'       => 'AC Repair in Adyar, Mylapore, Velachery & South Chennai | Same-Day',
            'meta_description' => 'Professional AC repair in Adyar, Mylapore, Velachery, Sholinganallur, Guindy & South Chennai. Same-day doorstep service. Call +91 94449 00470.',
            'focus_keyword'    => 'AC repair Adyar Chennai',
            'tags'             => ['AC repair Adyar', 'AC repair Mylapore', 'AC repair Velachery', 'AC service South Chennai', 'AC repair Guindy'],
            'read_time_minutes'        => 6,
        ]);

        // ═══════════════════════════════════════════════════════
        //  AC REPAIR — POST 3
        //  Target: Porur, Maduravoyal, Ambattur, Mogappair,
        //          Valasaravakkam, Koyambedu, Gerugambakkam
        // ═══════════════════════════════════════════════════════
        BlogPost::create([
            'blog_category_id' => $catAC->id,
            'author_id'        => $admin->id,
            'title'            => 'AC Repair in Porur, Maduravoyal, Ambattur & West Chennai – Fast Doorstep Service',
            'slug'             => 'ac-repair-porur-maduravoyal-ambattur-west-chennai',
            'excerpt'          => 'AC not working in Porur, Maduravoyal, Ambattur, Mogappair or Valasaravakkam? Our certified AC repair team covers all West Chennai locations with same-day service.',
            'content'          => <<<HTML
<h1>AC Repair in Porur, Maduravoyal, Ambattur & West Chennai</h1>

<p>West Chennai is one of the fastest-growing residential zones in the city. From new apartments in <strong>Mogappair</strong> to independent houses in <strong>Valasaravakkam</strong> and gated communities in <strong>Porur</strong> and <strong>Gerugambakkam</strong>, Chennai Smart Care provides <strong>fast, reliable AC repair across all West Chennai areas</strong>.</p>

<p>Our technicians are based in West Chennai, so response times in <strong>Maduravoyal</strong>, <strong>Ambattur</strong>, <strong>Koyambedu</strong>, <strong>Nolambur</strong>, and <strong>Manapakkam</strong> are among the fastest in the city — typically under 45 minutes.</p>

<hr>

<h2>Why ACs Break Down More Often in West Chennai</h2>

<h3>Construction Dust in Porur & Gerugambakkam</h3>
<p>Ongoing construction in Porur, Gerugambakkam, Moulivakkam, and Manapakkam fills the air with fine dust particles. This clogs AC filters and coils every 2–3 months instead of the usual 6 months, reducing cooling efficiency and increasing electricity bills by up to 25%.</p>
<p><strong>Recommendation:</strong> Clean or replace AC filters every 2 months if you live near active construction sites.</p>

<h3>Hard Water Stains on Outdoor Units in Maduravoyal</h3>
<p>Water from Chennai's supply can be hard in certain West Chennai pockets, causing mineral deposits on outdoor unit fins that reduce heat exchange efficiency. We clean these with descaling agents during our service visits.</p>

<h3>Voltage Issues in Ambattur Industrial Area</h3>
<p>Proximity to Ambattur Industrial Estate occasionally causes voltage irregularities in residential areas nearby. We always check the capacitor and compressor start relay when servicing ACs in Ambattur and Padi.</p>

<hr>

<h2>Our AC Services in West Chennai</h2>

<h3>AC Gas Filling – Porur & Maduravoyal</h3>
<p>We use genuine R22, R32, and R410A refrigerants. Every gas refill job includes a leak test, vacuum process, and gas charge to manufacturer-specified pressure. Starting from ₹1,200.</p>

<h3>Split AC Deep Cleaning – Mogappair & Koyambedu</h3>
<p>Our jet wash service cleans the indoor unit's evaporator coil, drain tray, and blower wheel using a high-pressure foaming agent. This restores cooling by 15–20% and eliminates mould and bacteria. Price: ₹499.</p>

<h3>AC Installation – Valasaravakkam & Nolambur</h3>
<p>Moving to a new apartment? We install split ACs with proper piping, electrical connections, and outdoor unit bracket mounting. Starting from ₹1,200 per unit.</p>

<h3>Inverter AC PCB Repair – Manapakkam & Mugalivakkam</h3>
<p>Inverter ACs have more complex PCBs. We repair Daikin, Mitsubishi, and LG inverter controller boards rather than recommending replacement, saving you ₹3,000–₹8,000.</p>

<hr>

<h2>West Chennai Areas We Serve</h2>
<ul>
<li>Porur, Manapakkam, Mugalivakkam</li>
<li>Maduravoyal, Moulivakkam, Alapakkam</li>
<li>Valasaravakkam, Alwarthirunagar, Nerkundram</li>
<li>Mogappair, Nolambur, Ambattur</li>
<li>Koyambedu, Arumbakkam, Saligramam</li>
<li>Gerugambakkam, Ramapuram, Mangadu</li>
<li>Ambattur, Padi, Thirumullaivoyal, Korattur</li>
</ul>

<hr>

<h2>FAQs – AC Repair West Chennai</h2>

<h3>How often should ACs be serviced in Porur due to construction dust?</h3>
<p>Every 2–3 months for filter cleaning, and a full deep service every 6 months. This keeps your AC running at peak efficiency despite the dusty environment.</p>

<h3>My new apartment AC in Mogappair is not cooling even though it is new. Why?</h3>
<p>New ACs can have issues from poor installation — incorrect pipe length, insufficient gas charge during installation, or a faulty PCB from the factory. We can inspect and fix this without voiding your warranty.</p>

<h3>Do you offer AC repair on Sundays in Ambattur?</h3>
<p>Yes. We work 7 days a week, 9 AM to 9 PM, including Sundays and public holidays across all West Chennai areas.</p>

<p><strong>Call <a href="tel:+919444900470">+91 94449 00470</a> for AC repair in Porur, Maduravoyal, Ambattur, Koyambedu and all West Chennai areas.</strong></p>
HTML,
            'status'           => 'published',
            'published_at'     => now()->subDays(3),
            'meta_title'       => 'AC Repair in Porur, Maduravoyal, Ambattur & West Chennai | Fast Service',
            'meta_description' => 'AC repair in Porur, Maduravoyal, Ambattur, Mogappair, Valasaravakkam & Koyambedu. Same-day service, 6-month warranty. Call +91 94449 00470.',
            'focus_keyword'    => 'AC repair Porur Chennai',
            'tags'             => ['AC repair Porur', 'AC repair Maduravoyal', 'AC repair Ambattur', 'AC repair Mogappair', 'AC service West Chennai'],
            'read_time_minutes'        => 6,
        ]);

        // ═══════════════════════════════════════════════════════
        //  REFRIGERATOR — POST 1
        //  Target: Anna Nagar, Kilpauk, Perambur, Kolathur,
        //          Madhavaram, Tondiarpet, Kodungaiyur
        // ═══════════════════════════════════════════════════════
        BlogPost::create([
            'blog_category_id' => $catFridge->id,
            'author_id'        => $admin->id,
            'title'            => 'Refrigerator Repair in Anna Nagar, Perambur & North Chennai – All Brands',
            'slug'             => 'refrigerator-repair-anna-nagar-perambur-north-chennai',
            'excerpt'          => 'Fridge stopped cooling in Anna Nagar, Kilpauk, Perambur, Kolathur or Madhavaram? Get expert refrigerator repair at your doorstep in North Chennai. All brands, same day.',
            'content'          => '
<h1>Refrigerator Repair in Anna Nagar, Perambur & North Chennai</h1>

<p>A refrigerator breakdown is a kitchen emergency. Spoiled food, melted ice cream, and warm medicines — it all adds up fast. If your fridge has stopped cooling in <strong>Anna Nagar</strong>, <strong>Kilpauk</strong>, <strong>Perambur</strong>, <strong>Kolathur</strong>, <strong>Madhavaram</strong>, <strong>Tondiarpet</strong>, or <strong>Kodungaiyur</strong>, call Chennai Smart Care for <strong>same-day refrigerator repair</strong>.</p>

<hr>

<h2>Common Refrigerator Problems in North Chennai</h2>

<h3>Fridge Not Cooling – Anna Nagar & Kilpauk</h3>
<p>The most common fridge complaint. Possible causes:</p>
<ul>
<li><strong>Compressor failure</strong> — the heart of the fridge stops pumping refrigerant</li>
<li><strong>Gas leakage</strong> — slow leaks over years deplete the refrigerant</li>
<li><strong>Dirty condenser coils</strong> — dust buildup prevents heat release</li>
<li><strong>Thermostat fault</strong> — temperature sensor sends wrong signals</li>
<li><strong>Evaporator fan not working</strong> — cold air not circulated</li>
</ul>
<p>We diagnose the actual cause before quoting any price. No parts are replaced without your approval.</p>

<h3>Refrigerator Ice Maker Not Working – Perambur</h3>
<p>Double-door fridges with ice makers in Perambur and Sembium often have water inlet valve failures. We replace the valve and test the ice maker cycle before closing the job.</p>

<h3>Fridge Water Leakage – Kolathur & Madhavaram</h3>
<p>Water pooling inside or under the fridge is usually caused by a blocked defrost drain tube. We flush the drain, clean the drain pan, and check the defrost heater to prevent recurrence.</p>

<h3>Fridge Making Loud Noise – Tondiarpet & Perambur</h3>
<p>Buzzing, clicking, or rattling sounds from a refrigerator usually indicate a failing compressor, dirty condenser fan motor, or vibrating evaporator fan. We identify the source and fix it without guesswork.</p>

<h3>Fridge Freezing Everything – Kodungaiyur</h3>
<p>If your fridge is turning vegetables into ice, the thermostat or temperature control board has failed. We replace the faulty component and calibrate the temperature settings correctly.</p>

<hr>

<h2>Refrigerator Brands We Repair in North Chennai</h2>
<ul>
<li>Samsung, LG, Whirlpool, Godrej</li>
<li>Haier, Bosch, Siemens, IFB</li>
<li>Videocon, Onida, Kelvinator, Voltas</li>
</ul>

<hr>

<h2>Refrigerator Repair Cost in Chennai (2025)</h2>
<table border="1" cellpadding="8" cellspacing="0">
<thead>
<tr><th>Repair Type</th><th>Starting Price</th></tr>
</thead>
<tbody>
<tr><td>Fridge Not Cooling (diagnosis + repair)</td><td>₹800</td></tr>
<tr><td>Refrigerator Gas Charging (R600A/R134A)</td><td>₹1,200</td></tr>
<tr><td>Thermostat / PCB Replacement</td><td>₹1,200</td></tr>
<tr><td>Compressor Replacement</td><td>₹4,500</td></tr>
<tr><td>Ice Maker Repair</td><td>₹900</td></tr>
</tbody>
</table>

<hr>

<h2>North Chennai Areas Covered</h2>
<ul>
<li>Anna Nagar, Anna Nagar West, Nolambur</li>
<li>Kilpauk, Shenoy Nagar, Aminjikarai</li>
<li>Perambur, Sembium, Kolathur</li>
<li>Madhavaram, Madhavaram Milk Colony</li>
<li>Tondiarpet, Moolakadai, Kodungaiyur</li>
<li>Ayanavaram, Purasawalkam, Otteri</li>
<li>Periamet, Basin Bridge, Royapuram</li>
</ul>

<hr>

<h2>FAQs – Refrigerator Repair North Chennai</h2>

<h3>How long does refrigerator repair take in Anna Nagar?</h3>
<p>Most repairs are completed within 1–3 hours on the same visit. Gas charging takes 2 hours. Compressor replacement may require 3–4 hours.</p>

<h3>Is it worth repairing an old fridge in Perambur?</h3>
<p>If the fridge is under 8 years old, repair is usually cost-effective. For fridges over 10 years old, we give an honest recommendation based on the repair cost vs. replacement cost.</p>

<h3>Do you carry spare parts for Samsung and LG fridges in Chennai?</h3>
<p>Yes. Our technicians carry commonly needed parts in their service kits — thermostats, capacitors, start relays, and gas — for same-visit repairs in most cases.</p>

<p><strong>Call <a href="tel:+919444900470">+91 94449 00470</a> for refrigerator repair in Anna Nagar, Perambur, Kolathur and all North Chennai areas.</strong></p>
',
            'status'           => 'published',
            'published_at'     => now()->subDays(4),
            'meta_title'       => 'Refrigerator Repair in Anna Nagar, Perambur & North Chennai | All Brands',
            'meta_description' => 'Expert fridge repair in Anna Nagar, Kilpauk, Perambur, Kolathur, Madhavaram & North Chennai. All brands, same-day service. Call +91 94449 00470.',
            'focus_keyword'    => 'refrigerator repair Anna Nagar Chennai',
            'tags'             => ['refrigerator repair Anna Nagar', 'fridge repair Perambur', 'refrigerator repair North Chennai', 'fridge not cooling Chennai'],
            'read_time_minutes'        => 6,
        ]);

        // ═══════════════════════════════════════════════════════
        //  REFRIGERATOR — POST 2
        //  Target: Adyar, Velachery, T. Nagar, Mylapore,
        //          Tambaram, Pallikaranai, Chrompet
        // ═══════════════════════════════════════════════════════
        BlogPost::create([
            'blog_category_id' => $catFridge->id,
            'author_id'        => $admin->id,
            'title'            => 'Refrigerator Repair in Adyar, Velachery, T. Nagar & South Chennai',
            'slug'             => 'refrigerator-repair-adyar-velachery-t-nagar-south-chennai',
            'excerpt'          => 'Fridge not cooling in Adyar, T. Nagar, Velachery, Mylapore or Pallikaranai? Chennai Smart Care provides same-day refrigerator repair across South Chennai with a 6-month warranty.',
            'content'          => '
<h1>Refrigerator Repair in Adyar, Velachery, T. Nagar & South Chennai</h1>

<p>From the busy lanes of <strong>T. Nagar</strong> to the beach-side apartments of <strong>Adyar</strong> and the IT residential hubs of <strong>Velachery</strong>, Chennai Smart Care provides <strong>fast, professional refrigerator repair across South Chennai</strong>. We carry spare parts in our service vehicles, so most repairs are completed on the first visit.</p>

<hr>

<h2>Refrigerator Issues We Resolve in South Chennai</h2>

<h3>Double Door Fridge Repair – T. Nagar & Mylapore</h3>
<p>Double-door and French-door refrigerators in T. Nagar and Mylapore homes have more components — ice dispensers, water filters, and digital controls — that can fail. Our technicians are trained on all double-door models from Samsung, LG, and Haier.</p>

<h3>Side-by-Side Fridge Repair – Adyar & Besant Nagar</h3>
<p>Premium homes in Adyar and Besant Nagar often have American-style side-by-side refrigerators. We service these complex models including water dispenser repairs, ice maker module replacement, and sealed system (gas) repairs.</p>

<h3>Fridge Compressor Replacement – Velachery & Pallikaranai</h3>
<p>A clicking sound from the fridge followed by it stopping is a classic sign of compressor failure. We replace compressors with genuine OEM units backed by a 6-month warranty. Starting from ₹4,500 for single-door and ₹5,500 for double-door models.</p>

<h3>Fridge Not Defrosting – Nanganallur & Palavanthangal</h3>
<p>If ice builds up on the back wall of your fridge, the auto-defrost system has failed. We replace the defrost heater, thermal fuse, or defrost timer as required.</p>

<h3>Refrigerator Door Seal Replacement – Sholinganallur & Perungudi</h3>
<p>A worn-out door gasket wastes electricity and lets warm air in, reducing cooling. We replace door seals for all fridge models, restoring airtight closure.</p>

<hr>

<h2>Energy Efficiency — Is Your Old Fridge Costing You Money?</h2>
<p>A well-maintained refrigerator uses 30–40% less electricity than a neglected one. Issues that increase power consumption:</p>
<ul>
<li>Dirty condenser coils — add ₹200–400/month to electricity bill</li>
<li>Worn door seals — compressor runs continuously</li>
<li>Low refrigerant — compressor works harder</li>
</ul>
<p>Our energy efficiency check identifies these issues and fixes them during the same visit.</p>

<hr>

<h2>South Chennai Areas We Cover</h2>
<ul>
<li>T. Nagar, Mambalam, Nandanam</li>
<li>Mylapore, Mandavelli, Royapettah</li>
<li>Adyar, Besant Nagar, Thiruvanmiyur</li>
<li>Velachery, Pallikaranai, Medavakkam</li>
<li>Sholinganallur, Perungudi, Karapakkam</li>
<li>Nanganallur, Palavanthangal, Madipakkam</li>
<li>Guindy, Ekkatuthangal, Saidapet</li>
</ul>

<hr>

<h2>FAQs – Refrigerator Repair South Chennai</h2>

<h3>Why does my fridge in Adyar smell bad?</h3>
<p>Mould growth in the drain pan, a cracked drain tube, or food debris in the fridge can cause bad odours. We clean and sanitise the entire unit during our service visit.</p>

<h3>My Samsung smart fridge in Velachery shows an error code. Can you fix it?</h3>
<p>Yes. We work with Samsung smart refrigerators and can diagnose error codes using diagnostic tools. Common codes like 1E, 5E, 8E are related to sensors and fan motors which we resolve on-site.</p>

<h3>How much does fridge gas charging cost in South Chennai?</h3>
<p>Gas charging for single-door fridges starts from ₹1,200. Double-door fridges start from ₹1,500. Price includes refrigerant, labour, and leak testing.</p>

<p><strong>Book refrigerator repair in Adyar, T. Nagar, Velachery or anywhere in South Chennai — call <a href="tel:+919444900470">+91 94449 00470</a>.</strong></p>
',
            'status'           => 'published',
            'published_at'     => now()->subDays(5),
            'meta_title'       => 'Refrigerator Repair in Adyar, Velachery, T. Nagar & South Chennai',
            'meta_description' => 'Fridge repair in Adyar, T. Nagar, Mylapore, Velachery, Sholinganallur & South Chennai. All brands, same-day service, 6-month warranty. Call +91 94449 00470.',
            'focus_keyword'    => 'refrigerator repair Adyar Chennai',
            'tags'             => ['refrigerator repair Adyar', 'fridge repair Velachery', 'fridge repair T. Nagar', 'refrigerator repair South Chennai'],
            'read_time_minutes'        => 5,
        ]);

        // ═══════════════════════════════════════════════════════
        //  REFRIGERATOR — POST 3
        //  Target: Porur, Maduravoyal, Ambattur, Mogappair,
        //          Valasaravakkam, Koyambedu
        // ═══════════════════════════════════════════════════════
        BlogPost::create([
            'blog_category_id' => $catFridge->id,
            'author_id'        => $admin->id,
            'title'            => 'Refrigerator Repair in Porur, Maduravoyal, Koyambedu & West Chennai',
            'slug'             => 'refrigerator-repair-porur-maduravoyal-koyambedu-west-chennai',
            'excerpt'          => 'Get expert refrigerator repair in Porur, Maduravoyal, Koyambedu, Mogappair and Valasaravakkam. Same-day fridge service with 6-month warranty across West Chennai.',
            'content'          => '
<h1>Refrigerator Repair in Porur, Maduravoyal, Koyambedu & West Chennai</h1>

<p>West Chennai is home to thousands of families who depend on their refrigerators daily. If your fridge is not cooling in <strong>Porur</strong>, <strong>Maduravoyal</strong>, <strong>Koyambedu</strong>, <strong>Mogappair</strong>, <strong>Valasaravakkam</strong>, <strong>Ambattur</strong>, or <strong>Alwarthirunagar</strong>, our experienced repair team is ready to help — the same day you call.</p>

<hr>

<h2>Why Choose Chennai Smart Care for Fridge Repair in West Chennai?</h2>
<ul>
<li><strong>Fastest Response:</strong> Our West Chennai base means we reach Porur and Maduravoyal in under 45 minutes</li>
<li><strong>No Fix, No Fee:</strong> We charge only if we successfully resolve the issue</li>
<li><strong>6-Month Warranty:</strong> All parts and labour covered</li>
<li><strong>Transparent Pricing:</strong> Written estimate provided before any work begins</li>
</ul>

<hr>

<h2>Top Refrigerator Issues in West Chennai Homes</h2>

<h3>Fridge Gas Leakage – Porur & Manapakkam</h3>
<p>New apartments in Porur and Manapakkam often have refrigerators relocated during renovation, which can stress the copper refrigerant pipes and cause micro-leaks. If your fridge is cooling less over time (not suddenly), a slow gas leak is likely.</p>
<p>We use electronic leak detectors to find the exact leak point, repair it, and then recharge with the correct refrigerant quantity.</p>

<h3>Refrigerator Not Cooling After Power Cut – Koyambedu & Ambattur</h3>
<p>Power cuts followed by sudden restoration can damage the compressor start capacitor. The fridge attempts to restart but fails, causing a clicking sound and no cooling. We replace the capacitor — a ₹300–500 part — and test thoroughly.</p>

<h3>Whirlpool Fridge Repair – Mogappair & Nolambur</h3>
<p>Whirlpool is extremely popular in Mogappair and Nolambur households. Common Whirlpool issues we fix include the adaptive defrost system failure, ice dispenser motor problems, and the sealed system gas charging for Whirlpool-specific refrigerants.</p>

<h3>Godrej Fridge Not Cooling – Valasaravakkam & Alwarthirunagar</h3>
<p>Godrej refrigerators are known for durability but older models (10+ years) often need thermostat replacement. We stock Godrej-compatible thermostats and relay kits for immediate repair.</p>

<hr>

<h2>West Chennai Areas We Cover for Fridge Repair</h2>
<ul>
<li>Porur, Manapakkam, Mugalivakkam</li>
<li>Maduravoyal, Moulivakkam, Alapakkam</li>
<li>Valasaravakkam, Alwarthirunagar, Nerkundram</li>
<li>Koyambedu, Saligramam, Arumbakkam</li>
<li>Mogappair, Nolambur, Ambattur</li>
<li>Ramapuram, Mangadu, Gerugambakkam</li>
</ul>

<hr>

<h2>FAQs – Refrigerator Repair West Chennai</h2>

<h3>My LG double door fridge in Porur shows error code CH. What does it mean?</h3>
<p>CH or CH1 error on LG fridges indicates a sensor fault in the freezer compartment. We replace the sensor and reset the board — typically a 1-hour job.</p>

<h3>Can you repair a deep freezer in Maduravoyal?</h3>
<p>Yes. We repair both domestic deep freezers and commercial cold storage units in Maduravoyal, Koyambedu market area, and surrounding West Chennai zones.</p>

<h3>How much does fridge repair cost in Koyambedu?</h3>
<p>Basic repairs start from ₹800. Gas charging is ₹1,200 onwards. We provide a written estimate on-site before starting work — no surprise bills.</p>

<p><strong>Call <a href="tel:+919444900470">+91 94449 00470</a> for refrigerator repair in Porur, Maduravoyal, Koyambedu and all West Chennai localities.</strong></p>
',
            'status'           => 'published',
            'published_at'     => now()->subDays(6),
            'meta_title'       => 'Refrigerator Repair in Porur, Maduravoyal, Koyambedu & West Chennai',
            'meta_description' => 'Fridge repair in Porur, Maduravoyal, Koyambedu, Mogappair & West Chennai. Same-day service, all brands, 6-month warranty. Call +91 94449 00470.',
            'focus_keyword'    => 'refrigerator repair Porur Chennai',
            'tags'             => ['refrigerator repair Porur', 'fridge repair Maduravoyal', 'fridge repair Koyambedu', 'refrigerator repair West Chennai'],
            'read_time_minutes'        => 5,
        ]);

        // ═══════════════════════════════════════════════════════
        //  WASHING MACHINE — POST 1
        //  Target: Anna Nagar, Kilpauk, Perambur, Kolathur,
        //          Mogappair, Ambattur, Padi
        // ═══════════════════════════════════════════════════════
        BlogPost::create([
            'blog_category_id' => $catWM->id,
            'author_id'        => $admin->id,
            'title'            => 'Washing Machine Repair in Anna Nagar, Kilpauk & North-West Chennai',
            'slug'             => 'washing-machine-repair-anna-nagar-kilpauk-north-west-chennai',
            'excerpt'          => 'Washing machine not spinning, draining or starting in Anna Nagar, Kilpauk, Mogappair or Ambattur? Get doorstep washing machine repair across North and West Chennai today.',
            'content'          => '
<h1>Washing Machine Repair in Anna Nagar, Kilpauk & North-West Chennai</h1>

<p>A broken washing machine disrupts the entire household routine. Whether you have a front-load, top-load, or semi-automatic machine in <strong>Anna Nagar</strong>, <strong>Kilpauk</strong>, <strong>Mogappair</strong>, <strong>Ambattur</strong>, <strong>Padi</strong>, or <strong>Kolathur</strong>, Chennai Smart Care provides <strong>certified washing machine repair at your doorstep</strong> — the same day you call.</p>

<hr>

<h2>Common Washing Machine Problems We Fix</h2>

<h3>Washing Machine Not Spinning – Anna Nagar</h3>
<p>This is the most common complaint across all Chennai localities. Causes vary by machine type:</p>
<ul>
<li><strong>Front-load:</strong> Drum bearing worn out, drive belt broken, motor carbon brushes worn</li>
<li><strong>Top-load:</strong> Lid switch failure, motor coupling broken, spin brake pad worn</li>
<li><strong>Semi-automatic:</strong> Spin motor capacitor failed</li>
</ul>
<p>We carry drum bearings, belts, and motor parts for all popular brands — most spinning issues are fixed on the first visit.</p>

<h3>Washing Machine Not Draining – Kilpauk & Choolaimedu</h3>
<p>Water remaining in the drum after a wash cycle is usually caused by a blocked drain pump filter, clogged drain hose, or a failing pump motor. We clear blockages and replace the pump if needed — typically a 1-hour job.</p>

<h3>Front-Load Washing Machine Door Locked – Mogappair</h3>
<p>If your front-loader door will not open after a wash, the door interlock (latch mechanism) has failed. We replace the door latch and safety interlock, which is a 45-minute repair.</p>

<h3>Washing Machine Making Noise – Ambattur & Padi</h3>
<p>Loud banging, grinding, or squealing during a wash cycle usually indicates drum bearing failure, a foreign object stuck in the drum, or a worn shock absorber. We diagnose and repair without unnecessary part replacements.</p>

<h3>Washing Machine Not Starting – Kolathur & Madhavaram</h3>
<p>PCB (control board) failure, a tripped thermal fuse, or a faulty door switch can prevent the machine from starting. We diagnose the exact cause using electronic testers.</p>

<hr>

<h2>Brands We Service in North-West Chennai</h2>
<ul>
<li>Samsung, LG, Whirlpool, IFB</li>
<li>Bosch, Siemens, Haier, Godrej</li>
<li>Videocon, Onida, Panasonic, Voltas</li>
</ul>

<hr>

<h2>Washing Machine Repair Cost in Chennai (2025)</h2>
<table border="1" cellpadding="8" cellspacing="0">
<thead>
<tr><th>Issue</th><th>Starting Price</th></tr>
</thead>
<tbody>
<tr><td>Not Spinning Repair</td><td>₹700</td></tr>
<tr><td>Drum Bearing Replacement</td><td>₹1,200</td></tr>
<tr><td>Water Leakage Fix</td><td>₹600</td></tr>
<tr><td>PCB / Control Board Repair</td><td>₹1,500</td></tr>
<tr><td>Inlet Valve Replacement</td><td>₹800</td></tr>
</tbody>
</table>

<hr>

<h2>Areas Covered in North and West Chennai</h2>
<ul>
<li>Anna Nagar, Anna Nagar West</li>
<li>Kilpauk, Shenoy Nagar, Chetpet</li>
<li>Mogappair, Nolambur, Ambattur</li>
<li>Padi, Korattur, Kolathur</li>
<li>Perambur, Sembium, Ayanavaram</li>
<li>Madhavaram, ICF Colony, Villivakkam</li>
</ul>

<hr>

<h2>FAQs – Washing Machine Repair North-West Chennai</h2>

<h3>My IFB front-load in Anna Nagar is showing an error E1. What does it mean?</h3>
<p>IFB E1 error indicates a water inlet issue — the machine is not filling with water. This is usually a clogged inlet filter, a faulty inlet valve, or low water pressure. We fix this in under an hour.</p>

<h3>Is it worth repairing an old top-load washing machine in Kilpauk?</h3>
<p>Top-load machines under 8 years old are almost always worth repairing. For older models, we give an honest cost-benefit assessment.</p>

<h3>Do you repair semi-automatic washing machines in Ambattur?</h3>
<p>Yes. Semi-automatic machines are common in Ambattur and Padi. We repair spin motor, timer, and water pump issues for all semi-automatic brands.</p>

<p><strong>Call <a href="tel:+919444900470">+91 94449 00470</a> for washing machine repair in Anna Nagar, Kilpauk, Mogappair, Ambattur and all North-West Chennai areas.</strong></p>
',
            'status'           => 'published',
            'published_at'     => now()->subDays(7),
            'meta_title'       => 'Washing Machine Repair in Anna Nagar, Kilpauk & North-West Chennai',
            'meta_description' => 'Expert washing machine repair in Anna Nagar, Kilpauk, Mogappair, Ambattur & North-West Chennai. Front-load, top-load, semi-auto. Call +91 94449 00470.',
            'focus_keyword'    => 'washing machine repair Anna Nagar Chennai',
            'tags'             => ['washing machine repair Anna Nagar', 'washing machine repair Kilpauk', 'washing machine repair Mogappair', 'washing machine service Chennai'],
            'read_time_minutes'        => 6,
        ]);

        // ═══════════════════════════════════════════════════════
        //  WASHING MACHINE — POST 2
        //  Target: Adyar, Velachery, T. Nagar, Mylapore,
        //          Porur, Maduravoyal, Koyambedu
        // ═══════════════════════════════════════════════════════
        BlogPost::create([
            'blog_category_id' => $catWM->id,
            'author_id'        => $admin->id,
            'title'            => 'Washing Machine Repair in Adyar, Velachery, Porur & South-West Chennai',
            'slug'             => 'washing-machine-repair-adyar-velachery-porur-south-west-chennai',
            'excerpt'          => 'Washing machine repair in Adyar, Velachery, T. Nagar, Porur, Maduravoyal and Valasaravakkam. Certified technicians fix front-load and top-load machines same day.',
            'content'          => '
<h1>Washing Machine Repair in Adyar, Velachery, Porur & South-West Chennai</h1>

<p>From high-rise apartments in <strong>Velachery</strong> to independent houses in <strong>Adyar</strong>, and the growing residential neighbourhoods of <strong>Porur</strong>, <strong>Maduravoyal</strong>, and <strong>Valasaravakkam</strong>, Chennai Smart Care handles all types of washing machine repairs — front-load, top-load, and semi-automatic — with a <strong>6-month service warranty</strong>.</p>

<hr>

<h2>Front-Load Washing Machine Repair – South Chennai</h2>

<h3>Bosch & Siemens Front-Load Repair – Adyar & Besant Nagar</h3>
<p>Premium homes in Adyar and Besant Nagar frequently use Bosch and Siemens front-loaders. We specialise in Bosch washing machine repairs including:</p>
<ul>
<li>E18 error — blocked pump filter (most common Bosch issue)</li>
<li>F21 error — door not locking properly</li>
<li>Drum not spinning — carbon brush or motor module failure</li>
<li>Water not draining — pump motor replacement</li>
</ul>

<h3>Samsung Front-Load Repair – Velachery & Pallikaranai</h3>
<p>Samsung front-loaders are popular in Velachery apartments. Common Samsung issues we fix:</p>
<ul>
<li>Sc / 5C error — drain pump blocked or faulty</li>
<li>4E / 4C error — water supply issue</li>
<li>UB / UE error — load imbalance (drum bearing may be worn)</li>
<li>Drum seal leakage — door gasket replacement</li>
</ul>

<h3>LG Front-Load Repair – T. Nagar & Nandanam</h3>
<p>LG Direct Drive washers in T. Nagar are known for longevity, but the LE error (motor locked) and OE error (drain issue) are the most frequent complaints. We fix both without recommending unnecessary motor replacement in most cases.</p>

<hr>

<h2>Washing Machine Drum Bearing Replacement – Porur & Maduravoyal</h2>
<p>If your washing machine makes a loud rumbling noise while spinning, the drum bearing has worn out. This is one of the most common repairs in 5–8-year-old front-loaders. Our West Chennai technicians carry bearing kits for Samsung, LG, Whirlpool, and IFB machines.</p>
<p>The job requires partial disassembly of the machine. We complete it in 2–3 hours and test with a full wash cycle before leaving.</p>

<hr>

<h2>Water Leakage Repair – Koyambedu & Valasaravakkam</h2>
<p>Water leaking from under a washing machine in Koyambedu and Valasaravakkam homes is usually caused by:</p>
<ul>
<li>Torn door gasket (front-load) — seals the drum door</li>
<li>Cracked soap dispenser drawer — overflow during wash</li>
<li>Loose drain hose connection</li>
<li>Pump housing crack</li>
</ul>
<p>We replace door gaskets for all front-load brands on the same visit.</p>

<hr>

<h2>South and West Chennai Areas Covered</h2>
<ul>
<li>Adyar, Besant Nagar, Thiruvanmiyur</li>
<li>Velachery, Pallikaranai, Madipakkam</li>
<li>T. Nagar, Mylapore, Nandanam</li>
<li>Porur, Manapakkam, Ramapuram</li>
<li>Maduravoyal, Valasaravakkam, Alwarthirunagar</li>
<li>Koyambedu, Saligramam, Nerkundram</li>
<li>Guindy, Ekkatuthangal, Saidapet</li>
</ul>

<hr>

<h2>FAQs – Washing Machine Repair South-West Chennai</h2>

<h3>My Bosch washing machine in Adyar shows E18. Is it a serious issue?</h3>
<p>E18 means the machine cannot drain water. In most cases, the pump filter is clogged with lint or coins. We clean it out in 30 minutes. If the pump itself has failed, we replace it — parts are available from stock.</p>

<h3>Does drum bearing replacement require the machine to go to a workshop?</h3>
<p>No. We do all repairs at your home in Porur, Velachery, or wherever you are. No need to transport your heavy machine.</p>

<h3>My top-load washing machine in Koyambedu is vibrating excessively. What is wrong?</h3>
<p>Excessive vibration in top-loaders is usually caused by worn shock absorbers or damaged suspension springs. We replace them and re-test for vibration before finishing the job.</p>

<p><strong>Book washing machine repair in Adyar, Velachery, Porur or anywhere in South-West Chennai — call <a href="tel:+919444900470">+91 94449 00470</a>.</strong></p>
',
            'status'           => 'published',
            'published_at'     => now()->subDays(8),
            'meta_title'       => 'Washing Machine Repair in Adyar, Velachery, Porur & South-West Chennai',
            'meta_description' => 'Washing machine repair in Adyar, Velachery, T. Nagar, Porur & South-West Chennai. Front-load, top-load experts. 6-month warranty. Call +91 94449 00470.',
            'focus_keyword'    => 'washing machine repair Velachery Chennai',
            'tags'             => ['washing machine repair Adyar', 'washing machine repair Velachery', 'washing machine repair Porur', 'washing machine repair South Chennai'],
            'read_time_minutes'        => 6,
        ]);

        // ═══════════════════════════════════════════════════════
        //  WASHING MACHINE — POST 3
        //  Target: Complete guide — all common issues, all areas
        // ═══════════════════════════════════════════════════════
        BlogPost::create([
            'blog_category_id' => $catWM->id,
            'author_id'        => $admin->id,
            'title'            => 'Complete Guide to Washing Machine Repair in Chennai – Front Load, Top Load & Semi-Auto',
            'slug'             => 'complete-guide-washing-machine-repair-chennai',
            'excerpt'          => 'Everything you need to know about washing machine repair in Chennai. Error codes, repair costs, common problems for front-load, top-load & semi-automatic machines — all explained.',
            'content'          => <<<HTML
<h1>Complete Guide to Washing Machine Repair in Chennai</h1>

<p>This guide covers everything Chennai homeowners need to know about washing machine repairs — common problems, error codes, repair costs, when to repair vs. replace, and how to find a reliable technician in your area.</p>

<hr>

<h2>Front-Load Washing Machine Error Codes & Fixes – Chennai</h2>

<h3>Samsung Front-Load Error Codes</h3>
<table border="1" cellpadding="8" cellspacing="0">
<thead><tr><th>Error Code</th><th>Meaning</th><th>Common Fix</th></tr></thead>
<tbody>
<tr><td>4E / 4C</td><td>Water supply issue</td><td>Check inlet valve and water pressure</td></tr>
<tr><td>5E / SC</td><td>Drainage problem</td><td>Clean pump filter, check drain hose</td></tr>
<tr><td>UE / UB</td><td>Unbalanced load</td><td>Redistribute clothes; check drum bearing</td></tr>
<tr><td>LE / LC</td><td>Water leakage detected</td><td>Check door gasket and hose connections</td></tr>
<tr><td>HE</td><td>Heater issue</td><td>Replace heating element</td></tr>
</tbody>
</table>

<h3>LG Front-Load Error Codes</h3>
<table border="1" cellpadding="8" cellspacing="0">
<thead><tr><th>Error Code</th><th>Meaning</th><th>Common Fix</th></tr></thead>
<tbody>
<tr><td>OE</td><td>Drain error</td><td>Clean pump filter; replace pump</td></tr>
<tr><td>IE</td><td>Inlet error</td><td>Check water supply and inlet valve</td></tr>
<tr><td>LE</td><td>Motor locked</td><td>Check motor and rotor bolt</td></tr>
<tr><td>DE</td><td>Door error</td><td>Replace door interlock</td></tr>
<tr><td>tE</td><td>Thermistor error</td><td>Replace temperature sensor</td></tr>
</tbody>
</table>

<h3>Bosch Front-Load Error Codes</h3>
<table border="1" cellpadding="8" cellspacing="0">
<thead><tr><th>Error Code</th><th>Meaning</th><th>Common Fix</th></tr></thead>
<tbody>
<tr><td>E18</td><td>Pump blocked</td><td>Clean pump filter (most common)</td></tr>
<tr><td>E17</td><td>Inlet valve fault</td><td>Replace inlet solenoid valve</td></tr>
<tr><td>E23</td><td>Leak detected</td><td>Check hose connections and gasket</td></tr>
<tr><td>F21</td><td>Door lock fault</td><td>Replace door lock mechanism</td></tr>
</tbody>
</table>

<hr>

<h2>Washing Machine Repair vs. Replacement – When to Call a Technician</h2>

<h3>Repair if:</h3>
<ul>
<li>Machine is under 8 years old</li>
<li>Repair cost is less than 50% of a new machine</li>
<li>It is a reliable brand (Samsung, LG, Bosch, IFB)</li>
<li>Problem is mechanical (bearing, belt, pump) — these are durable repairs</li>
</ul>

<h3>Consider replacement if:</h3>
<ul>
<li>Machine is over 12 years old</li>
<li>Compressor and drum both need replacement</li>
<li>Multiple recurring failures in 12 months</li>
</ul>

<hr>

<h2>Preventive Tips to Extend Washing Machine Life in Chennai</h2>
<ul>
<li><strong>Clean the pump filter every 3 months</strong> — Chennai's hard water deposits debris faster</li>
<li><strong>Use the right detergent quantity</strong> — excess detergent damages the drum seal</li>
<li><strong>Do not overload the drum</strong> — overloading accelerates bearing wear</li>
<li><strong>Leave the door open after each wash</strong> — prevents mould growth in the drum seal</li>
<li><strong>Use a voltage stabiliser</strong> — Chennai's power fluctuations damage PCBs</li>
</ul>

<hr>

<h2>Washing Machine Service in All Chennai Areas</h2>
<p>We provide doorstep washing machine repair across all major Chennai locations including:</p>
<ul>
<li><strong>Central:</strong> Anna Nagar, T. Nagar, Nungambakkam, Kodambakkam, Ashok Nagar</li>
<li><strong>North:</strong> Perambur, Kolathur, Madhavaram, Tondiarpet, Villivakkam</li>
<li><strong>South:</strong> Adyar, Mylapore, Velachery, Sholinganallur, Nanganallur</li>
<li><strong>West:</strong> Porur, Maduravoyal, Koyambedu, Mogappair, Valasaravakkam</li>
<li><strong>IT Corridor:</strong> Sholinganallur, Perungudi, Thoraipakkam, Karapakkam</li>
</ul>

<hr>

<h2>FAQs – Washing Machine Repair Chennai</h2>

<h3>What is the average cost of washing machine repair in Chennai?</h3>
<p>Basic repairs (water leakage, filter cleaning, belt replacement) start from ₹600. Drum bearing replacement is ₹1,200. PCB repair is ₹1,500. Pump motor replacement starts from ₹1,200.</p>

<h3>How long does a washing machine repair take in Chennai?</h3>
<p>Most repairs take 1–2 hours on-site. Drum bearing replacement takes 2–3 hours. We complete all work at your home — no need to send the machine anywhere.</p>

<h3>Do you carry spare parts for all brands in Chennai?</h3>
<p>Yes. Our technicians carry belts, bearings, inlet valves, drain pumps, door seals, and PCBs for Samsung, LG, Whirlpool, Bosch, IFB, and Godrej — the most common brands in Chennai.</p>

<p><strong>Need washing machine repair in Chennai? Call <a href="tel:+919444900470">+91 94449 00470</a> — we reach you the same day.</strong></p>
HTML,
            'status'           => 'published',
            'published_at'     => now()->subDays(9),
            'meta_title'       => 'Washing Machine Repair in Chennai – Complete Guide to Error Codes & Costs',
            'meta_description' => 'Complete guide to washing machine repair in Chennai. Error codes for Samsung, LG, Bosch. Repair costs, front-load & top-load service. Call +91 94449 00470.',
            'focus_keyword'    => 'washing machine repair Chennai',
            'tags'             => ['washing machine repair Chennai', 'front load washing machine repair', 'Samsung washing machine error codes', 'LG washing machine repair Chennai'],
            'read_time_minutes'        => 8,
        ]);

        // ═══════════════════════════════════════════════════════
        //  MICROWAVE — POST 1
        //  Target: Anna Nagar, T. Nagar, Adyar, Mylapore,
        //          Velachery, Nungambakkam, Kilpauk
        // ═══════════════════════════════════════════════════════
        BlogPost::create([
            'blog_category_id' => $catMicro->id,
            'author_id'        => $admin->id,
            'title'            => 'Microwave Oven Repair in Chennai – All Areas, All Brands',
            'slug'             => 'microwave-oven-repair-chennai-all-areas-all-brands',
            'excerpt'          => 'Microwave not heating, sparking or rotating in Chennai? Expert microwave oven repair in Anna Nagar, T. Nagar, Adyar, Velachery, Porur and all Chennai areas. Same-day service.',
            'content'          => '
<h1>Microwave Oven Repair in Chennai – All Areas, All Brands</h1>

<p>Microwave ovens are now found in most Chennai kitchens. When they break down — not heating, sparking inside, turntable not rotating, or buttons not working — Chennai Smart Care provides <strong>professional microwave oven repair across all Chennai areas</strong> including Anna Nagar, T. Nagar, Adyar, Mylapore, Velachery, Porur, Maduravoyal, and Koyambedu.</p>

<hr>

<h2>Types of Microwave Ovens We Repair</h2>

<h3>Solo Microwave Oven Repair</h3>
<p>Solo microwaves are the most basic type — used for reheating and defrosting. Common failures:</p>
<ul>
<li><strong>Not heating:</strong> Magnetron, high-voltage diode, or capacitor failure</li>
<li><strong>Turntable not rotating:</strong> Coupling, roller ring, or turntable motor failure</li>
<li><strong>Display not working:</strong> Control board or membrane switch failure</li>
</ul>

<h3>Grill Microwave Oven Repair</h3>
<p>Grill microwaves have an additional heating element for grilling. Common issues:</p>
<ul>
<li>Grill not working (microwave function works): Grill element failure</li>
<li>Sparking from grill area: Burnt grill element or splatter guard damage</li>
</ul>

<h3>Convection Microwave Oven Repair</h3>
<p>Convection models are the most complex. Popular in Anna Nagar, T. Nagar, and Adyar homes, these can develop:</p>
<ul>
<li>Convection fan not working (fan motor failure)</li>
<li>Temperature inaccuracy (thermistor failure)</li>
<li>Error codes on display (control board issues)</li>
</ul>

<hr>

<h2>Common Microwave Problems Explained</h2>

<h3>Microwave Not Heating</h3>
<p>The microwave runs (light on, turntable rotating) but food remains cold. This is almost always caused by a failed magnetron — the core component that generates microwave energy. A high-voltage diode or capacitor failure can also cause this.</p>
<p><strong>Cost:</strong> Magnetron replacement starts from ₹1,500. Diode/capacitor replacement is ₹500–800.</p>
<p><strong>Warning:</strong> Microwave capacitors retain lethal charge even when unplugged. Never attempt DIY repair on a microwave oven.</p>

<h3>Microwave Sparking Inside</h3>
<p>Sparks inside the microwave are caused by:</p>
<ul>
<li>Damaged waveguide cover (the tan-coloured panel on the ceiling of the microwave interior)</li>
<li>Food residue burnt onto the cavity walls</li>
<li>Metal accidentally left inside</li>
</ul>
<p>We replace the waveguide cover and clean the cavity thoroughly. Cost: ₹300–500.</p>

<h3>Microwave Turntable Not Rotating</h3>
<p>The glass turntable rotates to ensure even heating. If it stops:</p>
<ul>
<li>Check if roller ring or coupling are broken (visual check possible)</li>
<li>If ring/coupling looks fine, the turntable motor has failed</li>
</ul>
<p>Turntable motor replacement: ₹600.</p>

<h3>Microwave Display Not Working</h3>
<p>Dead display but microwave works means the display board has failed. If the entire control panel is unresponsive, the main control PCB or membrane switch keypad has failed.</p>
<p>PCB repair: ₹1,200 onwards.</p>

<hr>

<h2>Microwave Brands We Service in Chennai</h2>
<ul>
<li>Samsung, LG, IFB, Panasonic</li>
<li>Godrej, Whirlpool, Bajaj, Onida</li>
<li>Morphy Richards, Philips, Kenstar</li>
</ul>

<hr>

<h2>Microwave Repair Cost in Chennai (2025)</h2>
<table border="1" cellpadding="8" cellspacing="0">
<thead><tr><th>Issue</th><th>Starting Price</th></tr></thead>
<tbody>
<tr><td>Not Heating (Magnetron/Diode)</td><td>₹800</td></tr>
<tr><td>PCB / Control Board Repair</td><td>₹1,200</td></tr>
<tr><td>Turntable Motor Replacement</td><td>₹600</td></tr>
<tr><td>Door Switch Repair</td><td>₹700</td></tr>
<tr><td>Waveguide Cover Replacement</td><td>₹400</td></tr>
</tbody>
</table>

<hr>

<h2>Chennai Areas We Cover for Microwave Repair</h2>
<ul>
<li>Anna Nagar, T. Nagar, Nungambakkam, Kilpauk</li>
<li>Adyar, Mylapore, Velachery, Sholinganallur</li>
<li>Porur, Maduravoyal, Koyambedu, Mogappair</li>
<li>Perambur, Kolathur, Ambattur, Valasaravakkam</li>
<li>Guindy, Nanganallur, Pallikaranai, Saidapet</li>
</ul>

<hr>

<h2>FAQs – Microwave Oven Repair Chennai</h2>

<h3>Is it worth repairing a microwave in Chennai or should I buy a new one?</h3>
<p>For microwaves under 6 years old, repair is almost always worth it. Repair costs for most issues are ₹600–1,500 vs. ₹3,500–8,000 for a replacement. We give an honest recommendation after diagnosis.</p>

<h3>My Samsung microwave in Anna Nagar shows SE error. What does it mean?</h3>
<p>SE (or 5E) on Samsung microwaves is a keypad shorted error — usually caused by steam condensation. In many cases, drying the membrane switch resolves it without replacement.</p>

<h3>Do you repair IFB convection microwaves in Adyar?</h3>
<p>Yes. IFB convection microwave repairs including control board replacement, magnetron issues, and convection fan motor repairs are handled by our technicians in Adyar and all South Chennai areas.</p>

<h3>How soon can a technician arrive for microwave repair in T. Nagar?</h3>
<p>Same day, typically within 1–2 hours of booking for T. Nagar, Anna Nagar, Nungambakkam, and other central Chennai areas.</p>

<p><strong>Book microwave oven repair in Chennai — call <a href="tel:+919444900470">+91 94449 00470</a>. We cover all Chennai areas with same-day service.</strong></p>
',
            'status'           => 'published',
            'published_at'     => now()->subDays(10),
            'meta_title'       => 'Microwave Oven Repair in Chennai – All Areas, All Brands | Same-Day',
            'meta_description' => 'Expert microwave oven repair in Chennai. Not heating, sparking, turntable issues fixed. All brands across Anna Nagar, Adyar, Velachery, Porur. Call +91 94449 00470.',
            'focus_keyword'    => 'microwave oven repair Chennai',
            'tags'             => ['microwave repair Chennai', 'microwave oven repair Anna Nagar', 'microwave not heating Chennai', 'IFB microwave repair', 'Samsung microwave repair Chennai'],
            'read_time_minutes'        => 7,
        ]);

        // ═══════════════════════════════════════════════════════
        //  MICROWAVE — POST 2
        //  Target: Porur, Maduravoyal, Valasaravakkam,
        //          Mogappair, Koyambedu, West Chennai
        // ═══════════════════════════════════════════════════════
        BlogPost::create([
            'blog_category_id' => $catMicro->id,
            'author_id'        => $admin->id,
            'title'            => 'Microwave Oven Repair in Porur, Mogappair, Koyambedu & West Chennai',
            'slug'             => 'microwave-oven-repair-porur-mogappair-koyambedu-west-chennai',
            'excerpt'          => 'Microwave not working in Porur, Mogappair, Koyambedu, Valasaravakkam or Maduravoyal? Get same-day microwave oven repair service with 6-month warranty across West Chennai.',
            'content'          => '
<h1>Microwave Oven Repair in Porur, Mogappair, Koyambedu & West Chennai</h1>

<p>West Chennai neighbourhoods like <strong>Porur</strong>, <strong>Mogappair</strong>, <strong>Koyambedu</strong>, <strong>Maduravoyal</strong>, <strong>Valasaravakkam</strong>, <strong>Alwarthirunagar</strong>, and <strong>Ambattur</strong> have a high density of young families with modern kitchens — and microwave ovens are central to their daily cooking. When your microwave stops working, our West Chennai-based technicians are among the quickest to respond.</p>

<hr>

<h2>Most Common Microwave Issues in West Chennai Homes</h2>

<h3>Microwave Runs But Does Not Heat – Porur & Manapakkam</h3>
<p>This is the number one microwave complaint across West Chennai. The magnetron has failed. Magnetrons have a finite lifespan — heavy daily use (4–6 times a day) can shorten it to 5–7 years. We replace the magnetron with a compatible unit and test heating performance before completing the job.</p>
<p><strong>Cost:</strong> From ₹1,500 including magnetron, labour, and 6-month warranty on the part.</p>

<h3>IFB Microwave Door Latch Issue – Mogappair & Nolambur</h3>
<p>IFB microwaves in Mogappair homes frequently develop door switch problems. If the microwave does not start when the door is closed, or stops mid-cycle, a door switch has failed. IFB microwaves have 3 door switches — we test all 3 and replace only the faulty one.</p>

<h3>LG Microwave Control Panel Not Responding – Koyambedu</h3>
<p>LG smart inverter microwaves are popular in Koyambedu. If buttons stop responding or the display is partially dead, it is a membrane switch or control PCB issue. We repair PCBs and replace membrane keypads for LG models.</p>

<h3>Godrej Microwave Turntable Stops – Valasaravakkam & Alwarthirunagar</h3>
<p>Godrej solo and grill microwaves are common in Valasaravakkam homes. The turntable motor in these models is cost-effective to replace — usually ₹400–600 including labour and 6-month warranty.</p>

<h3>Samsung Convection Microwave Error – Maduravoyal</h3>
<p>Samsung convection microwaves showing -SE-, F-2, or C-00 errors in Maduravoyal homes are usually experiencing keypad or board issues. We diagnose and resolve these without recommending a new microwave unless absolutely necessary.</p>

<hr>

<h2>Safety Warning — Do Not DIY Microwave Repair</h2>
<p>Unlike most other appliances, microwave ovens contain a <strong>high-voltage capacitor that stores up to 2,100 volts</strong> — enough to cause serious injury or death even when the microwave is unplugged. Always call a trained technician for internal microwave repairs.</p>

<hr>

<h2>West Chennai Microwave Repair Areas</h2>
<ul>
<li>Porur, Manapakkam, Mugalivakkam</li>
<li>Mogappair, Nolambur, Ambattur</li>
<li>Koyambedu, Arumbakkam, Saligramam</li>
<li>Maduravoyal, Valasaravakkam, Alwarthirunagar</li>
<li>Ramapuram, Mangadu, Gerugambakkam</li>
<li>Nerkundram, Moulivakkam, Alapakkam</li>
</ul>

<hr>

<h2>FAQs – Microwave Repair West Chennai</h2>

<h3>How much does microwave magnetron replacement cost in Porur?</h3>
<p>Magnetron replacement starts from ₹1,500 in Porur and all West Chennai areas. Price varies slightly by brand and model. We confirm the price before starting work.</p>

<h3>Can my 8-year-old IFB microwave in Mogappair be repaired?</h3>
<p>In most cases, yes. IFB builds durable microwaves. If the magnetron has not failed (and we test this first), most other repairs — door switches, PCB, turntable motor — are cost-effective on an 8-year-old unit.</p>

<h3>Do you offer microwave repair on weekends in Koyambedu?</h3>
<p>Yes. We work 7 days a week including Sundays and public holidays from 9 AM to 9 PM across Koyambedu and all West Chennai areas.</p>

<p><strong>Book microwave oven repair in Porur, Mogappair, Koyambedu or anywhere in West Chennai — call <a href="tel:+919444900470">+91 94449 00470</a>.</strong></p>
',
            'status'           => 'published',
            'published_at'     => now()->subDays(11),
            'meta_title'       => 'Microwave Oven Repair in Porur, Mogappair, Koyambedu & West Chennai',
            'meta_description' => 'Microwave repair in Porur, Mogappair, Koyambedu, Valasaravakkam & West Chennai. Not heating, door switch, PCB fixed same day. Call +91 94449 00470.',
            'focus_keyword'    => 'microwave repair Porur Chennai',
            'tags'             => ['microwave repair Porur', 'microwave repair Mogappair', 'microwave repair Koyambedu', 'microwave oven service West Chennai'],
            'read_time_minutes'        => 5,
        ]);
    }
}