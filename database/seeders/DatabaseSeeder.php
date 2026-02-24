<?php
// ============================================================
// DATABASE SEEDER — database/seeders/DatabaseSeeder.php
// ============================================================
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;
use App\Models\ServiceCategory;
use App\Models\Service;
use App\Models\BlogCategory;
use App\Models\BlogPost;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ---- Super Admin ----
        Admin::create([
            'name'     => 'Super Admin',
            'email'    => 'admin@chennaismarcare.in',
            'password' => Hash::make('Admin@123!'),
            'role'     => 'super_admin',
            'phone'    => '9444900470',
        ]);

        // ---- Service Categories ----
        $ac = ServiceCategory::create([
            'name'             => 'Air Conditioner',
            'slug'             => 'ac',
            'description'      => 'Complete AC repair, installation, and maintenance services',
            'icon'             => '❄️',
            'color'            => '#3B82F6',
            'sort_order'       => 1,
            'meta_title'       => 'AC Repair in Chennai | Fast & Reliable Air Conditioner Service',
            'meta_description' => 'Expert AC repair in Chennai. Split AC, window AC, cassette AC — all brands. Same-day service. Call now!',
        ]);

        $fridge = ServiceCategory::create([
            'name'             => 'Refrigerator',
            'slug'             => 'refrigerator',
            'description'      => 'Expert fridge and deep freezer repair for all brands',
            'icon'             => '🧊',
            'color'            => '#10B981',
            'sort_order'       => 2,
            'meta_title'       => 'Refrigerator Repair in Chennai | All Brands Serviced',
            'meta_description' => 'Professional refrigerator repair in Chennai. Samsung, LG, Whirlpool, Godrej & all brands. Fast doorstep service.',
        ]);

        $washing = ServiceCategory::create([
            'name'             => 'Washing Machine',
            'slug'             => 'washing-machine',
            'description'      => 'Front-load, top-load, and semi-automatic washing machine repair',
            'icon'             => '👕',
            'color'            => '#8B5CF6',
            'sort_order'       => 3,
            'meta_title'       => 'Washing Machine Repair in Chennai | Front Load & Top Load',
            'meta_description' => 'Best washing machine repair service in Chennai. Same-day service, genuine spare parts, 6-Month warranty.',
        ]);

        $microwave = ServiceCategory::create([
            'name'             => 'Microwave Oven',
            'slug'             => 'microwave-oven',
            'description'      => 'Microwave oven repair and maintenance services for all brands',
            'icon'             => '🔥',
            'color'            => '#F97316',
            'sort_order'       => 4,
            'meta_title'       => 'Microwave Oven Repair in Chennai | All Brands Service',
            'meta_description' => 'Professional microwave oven repair in Chennai. Solo, Grill & Convection models serviced. Same-day doorstep repair.',
        ]);

        // ---- AC Services ----
        $acServices = [
            ['name' => 'AC Gas Refilling', 'slug' => 'ac-gas-refilling', 'base_price' => 1200, 'duration_estimate' => '1-2 hours',
             'features' => ['R22/R32/R410A refrigerant', 'Pressure check', 'Leak detection'], 'sort_order' => 1],
            ['name' => 'AC Service & Cleaning', 'slug' => 'ac-service-cleaning', 'base_price' => 499, 'duration_estimate' => '1 hour',
             'features' => ['Deep filter clean', 'Coil wash', 'Performance check'], 'sort_order' => 2],
            ['name' => 'AC PCB Repair', 'slug' => 'ac-pcb-repair', 'base_price' => 1500, 'duration_estimate' => '2-3 hours',
             'features' => ['Motherboard diagnosis', 'Component replacement', 'Testing'], 'sort_order' => 3],
            ['name' => 'AC Installation', 'slug' => 'ac-installation', 'base_price' => 1200, 'duration_estimate' => '2-4 hours',
             'features' => ['Professional mounting', 'Pipe laying', 'Electrical connections'], 'sort_order' => 4],
            ['name' => 'AC Compressor Replacement', 'slug' => 'ac-compressor', 'base_price' => 5000, 'duration_estimate' => '3-4 hours',
             'features' => ['OEM compressor', '6-month warranty', 'Gas top-up included'], 'sort_order' => 5],
        ];

        foreach ($acServices as $s) {
            $ac->services()->create([...$s, 'is_featured' => in_array($s['slug'], ['ac-service-cleaning', 'ac-gas-refilling'])]);
        }

        // ---- Refrigerator Services ----
        $fridgeServices = [
            ['name' => 'Fridge Not Cooling Fix', 'slug' => 'fridge-not-cooling', 'base_price' => 800, 'duration_estimate' => '1-2 hours',
             'features' => ['Compressor test', 'Gas check', 'Thermostat check'], 'sort_order' => 1],
            ['name' => 'Refrigerator Gas Charging', 'slug' => 'fridge-gas-charging', 'base_price' => 1200, 'duration_estimate' => '2 hours',
             'features' => ['R600A/R134A refrigerant', 'Leak test', 'Vacuum process'], 'sort_order' => 2],
            ['name' => 'Fridge PCB / Thermostat Repair', 'slug' => 'fridge-pcb-repair', 'base_price' => 1200, 'duration_estimate' => '2 hours',
             'features' => ['Diagnosis', 'Component replacement', 'Full test'], 'sort_order' => 3],
            ['name' => 'Ice Maker Repair', 'slug' => 'ice-maker-repair', 'base_price' => 900, 'duration_estimate' => '1-2 hours',
             'features' => ['Ice maker diagnosis', 'Water valve check', 'Testing'], 'sort_order' => 4],
        ];

        foreach ($fridgeServices as $s) {
            $fridge->services()->create([...$s, 'is_featured' => $s['slug'] === 'fridge-not-cooling']);
        }

        // ---- Washing Machine Services ----
        $washingServices = [
            ['name' => 'WM Not Spinning Fix', 'slug' => 'wm-not-spinning', 'base_price' => 700, 'duration_estimate' => '1-2 hours',
             'features' => ['Motor check', 'Belt inspection', 'Control board test'], 'sort_order' => 1],
            ['name' => 'WM Drum Bearing Replacement', 'slug' => 'wm-drum-bearing', 'base_price' => 1200, 'duration_estimate' => '2-3 hours',
             'features' => ['OEM bearings', 'Seal replacement', 'Vibration test'], 'sort_order' => 2],
            ['name' => 'WM Water Leakage Fix', 'slug' => 'wm-water-leakage', 'base_price' => 600, 'duration_estimate' => '1 hour',
             'features' => ['Door seal check', 'Drain hose inspection', 'Pump test'], 'sort_order' => 3],
            ['name' => 'WM PCB / Control Panel Repair', 'slug' => 'wm-pcb-repair', 'base_price' => 1500, 'duration_estimate' => '2-3 hours',
             'features' => ['Full diagnosis', 'Board repair or replace', 'Testing'], 'sort_order' => 4],
        ];

        foreach ($washingServices as $s) {
            $washing->services()->create([...$s, 'is_featured' => $s['slug'] === 'wm-not-spinning']);
        }

        // ---- Microwave Services ----
        $microwaveServices = [
            [
                'name' => 'Microwave Not Heating Fix','slug' => 'microwave-not-heating','base_price' => 800,'duration_estimate' => '1-2 hours',
                'features' => ['Magnetron check','High-voltage diode test','Capacitor inspection'],
                'sort_order' => 1
            ],
            [
                'name' => 'Microwave PCB Repair','slug' => 'microwave-pcb-repair','base_price' => 1200,'duration_estimate' => '2 hours',
                'features' => ['Control board diagnosis','Component replacement','Full functionality test'],
                'sort_order' => 2
            ],
            [
                'name' => 'Microwave Turntable Repair','slug' => 'microwave-turntable-repair','base_price' => 600,'duration_estimate' => '1 hour',
                'features' => ['Motor inspection','Coupler check','Roller ring alignment'],
                'sort_order' => 3
            ],
            [
                'name' => 'Microwave Door Switch Repair','slug' => 'microwave-door-switch-repair','base_price' => 700,'duration_estimate' => '1 hour',
                'features' => ['Door latch inspection','Safety switch replacement','Testing'],
                'sort_order' => 4
            ],
        ];

        foreach ($microwaveServices as $s) {
            $microwave->services()->create([
                ...$s,
                'is_featured' => $s['slug'] === 'microwave-not-heating'
            ]);
        }

        // ---- Blog Categories ----
        $blogCategories = [
            BlogCategory::create(['name' => 'AC Repair Tips', 'slug' => 'ac-repair-tips']),
            BlogCategory::create(['name' => 'Refrigerator Care', 'slug' => 'refrigerator-care']),
            BlogCategory::create(['name' => 'Washing Machine Guide', 'slug' => 'washing-machine-guide']),
            BlogCategory::create(['name' => 'Microwave Oven Service', 'slug' => 'microwave-oven-repair-chennai']),
        ];

        // ---- Sample Blog Posts ----
        $admin = Admin::first();

        BlogPost::create([
            'blog_category_id' => $blogCategories[0]->id,
            'author_id'        => $admin->id,
            'title'            => 'Why Is My AC Not Cooling? 7 Common Reasons & Fixes in Chennai',
            'slug'             => 'why-ac-not-cooling-common-reasons-fixes-chennai',
            'excerpt'          => 'Is your AC running but not cooling your room? Here are 7 most common reasons your AC stops cooling in Chennai\'s hot climate and what you can do about it.',
            'content'          => '<h2>Common AC Cooling Problems</h2><p>Chennai\'s hot and humid climate means ACs run almost year-round, making them prone to issues...</p>',
            'status'           => 'published',
            'published_at'     => now()->subDays(5),
            'meta_title'       => 'AC Not Cooling in Chennai? 7 Causes + Expert Fix | Chennai Smart Care',
            'meta_description' => 'AC running but not cooling in Chennai? Our experts explain 7 common reasons — low refrigerant, dirty filters, PCB issues & more. Call for same-day AC repair.',
            'focus_keyword'    => 'AC not cooling Chennai',
            'tags'             => ['AC repair', 'AC not cooling', 'Chennai', 'air conditioner'],
            'gtm_category'     => 'ac_blog',
        ]);

        BlogPost::create([
            'blog_category_id' => $blogCategories[1]->id,
            'author_id'        => $admin->id,
            'title'            => 'Refrigerator Not Cooling? Step-by-Step Diagnosis for Chennai Homeowners',
            'slug'             => 'refrigerator-not-cooling-diagnosis-chennai',
            'excerpt'          => 'A fridge that stops cooling can spoil food and cost thousands. Learn how to quickly diagnose the issue before calling a repair technician.',
            'content'          => '<h2>Is Your Fridge Not Cooling?</h2><p>A non-cooling refrigerator is a kitchen emergency...</p>',
            'status'           => 'published',
            'published_at'     => now()->subDays(10),
            'meta_title'       => 'Refrigerator Not Cooling in Chennai? Expert Diagnosis & Repair Tips',
            'meta_description' => 'Fridge stopped cooling in Chennai? Check these common causes: gas leak, compressor failure, thermostat issue. Expert repair from Chennai Smart Care.',
            'focus_keyword'    => 'refrigerator not cooling Chennai',
            'tags'             => ['refrigerator repair', 'fridge not cooling', 'Chennai', 'appliance repair'],
            'gtm_category'     => 'refrigerator_blog',
        ]);

        BlogPost::create([
            'blog_category_id' => $blogCategories[0]->id,
            'author_id'        => $admin->id,
            'title'            => 'AC Repair in Chennai – Fast Service in Porur & Nearby Areas',
            'slug'             => 'ac-repair-chennai-porur-fast-service',
            'excerpt'          => 'Looking for AC repair in Chennai? Get same-day doorstep service in Porur, Maduravoyal, Vadapalani & Guindy.',
            'content'          => '
        <h1>AC Repair in Chennai – Same Day Service in Porur</h1>

        <p>Chennai’s extreme heat makes air conditioning essential. If your AC is not cooling, leaking water, or making noise, you need professional AC repair in Chennai immediately.</p>

        <h2>Common AC Problems in Chennai</h2>

        <h3>1. AC Not Cooling</h3>
        <p>This is usually caused by low refrigerant gas, clogged filters, dirty condenser coils, or compressor issues.</p>

        <h3>2. AC Gas Leakage</h3>
        <p>If cooling reduces gradually, there may be a refrigerant leak. Proper leak testing is required before gas refilling.</p>

        <h3>3. Water Leakage</h3>
        <p>Blocked drain pipes or frozen coils often cause indoor water leakage.</p>

        <h2>AC Gas Filling Cost in Chennai</h2>
        <ul>
        <li>Split AC: ₹1800 – ₹3500</li>
        <li>Window AC: ₹1500 – ₹2800</li>
        </ul>

        <h2>Areas We Serve</h2>
        <ul>
        <li>Porur</li>
        <li>Maduravoyal</li>
        <li>Vadapalani</li>
        <li>Guindy</li>
        <li>Koyambedu</li>
        </ul>

        <h2>Why Choose Our AC Service?</h2>
        <ul>
        <li>Same-day technician visit</li>
        <li>Experienced professionals</li>
        <li>Affordable pricing</li>
        <li>Warranty on service</li>
        </ul>

        <h2>FAQs – AC Repair Chennai</h2>

        <h3>Why is my AC running but not cooling?</h3>
        <p>Low refrigerant, dirty filters, or compressor issues are common reasons.</p>

        <h3>How often should I service my AC in Chennai?</h3>
        <p>Every 6 months due to heavy usage in hot climate.</p>

        <p><strong>Call now for fast AC repair in Porur & Chennai.</strong></p>

        <script type="application/ld+json">
        {
        "@context": "https://schema.org",
        "@type": "FAQPage",
        "mainEntity": [{
        "@type": "Question",
        "name": "Why is my AC not cooling?",
        "acceptedAnswer": {
            "@type": "Answer",
            "text": "Low refrigerant, dirty filters, or compressor issues are common causes."
        }
        }]
        }
        </script>
        ',
            'status'           => 'published',
            'published_at'     => now()->subDays(2),
            'meta_title'       => 'AC Repair in Chennai – Same Day Service in Porur',
            'meta_description' => 'Professional AC repair in Chennai. Fast doorstep service in Porur, Maduravoyal & nearby areas. Book same-day AC service.',
            'focus_keyword'    => 'AC repair Chennai',
            'tags'             => ['AC repair Chennai', 'AC service Porur'],
            'gtm_category'     => 'ac_blog',
        ]);

        BlogPost::create([
            'blog_category_id' => $blogCategories[1]->id,
            'author_id'        => $admin->id,
            'title'            => 'Refrigerator Repair in Chennai – Fridge Service in Porur',
            'slug'             => 'refrigerator-repair-chennai-porur',
            'excerpt'          => 'Fridge not cooling? Get same-day refrigerator repair in Chennai & Porur.',
            'content'          => '
        <h1>Refrigerator Repair in Chennai – Fast Fridge Service in Porur</h1>

        <p>A refrigerator breakdown can spoil food quickly. If your fridge is not cooling, leaking water, or making noise, expert repair is required.</p>

        <h2>Common Refrigerator Issues</h2>

        <h3>Fridge Not Cooling</h3>
        <p>Gas leakage, compressor problems, or thermostat failure are major causes.</p>

        <h3>Water Leakage</h3>
        <p>Blocked drain pipe or defrost issue can cause leakage.</p>

        <h3>Fridge Making Noise</h3>
        <p>Evaporator fan or condenser motor issues may create noise.</p>

        <h2>Fridge Gas Filling Cost in Chennai</h2>
        <ul>
        <li>Single Door: ₹1800 – ₹3000</li>
        <li>Double Door: ₹2500 – ₹4500</li>
        </ul>

        <h2>Service Areas</h2>
        <ul>
        <li>Porur</li>
        <li>Maduravoyal</li>
        <li>Vadapalani</li>
        <li>Guindy</li>
        </ul>

        <h2>FAQs</h2>

        <h3>Why is my fridge not cooling but light is on?</h3>
        <p>Likely compressor or gas issue.</p>

        <h3>How fast can a technician arrive?</h3>
        <p>Usually within 30–60 minutes in Porur.</p>

        <p><strong>Book refrigerator repair in Chennai today.</strong></p>
        ',
            'status'           => 'published',
            'published_at'     => now()->subDays(3),
            'meta_title'       => 'Refrigerator Repair in Chennai – Same Day Fridge Service',
            'meta_description' => 'Professional fridge repair in Chennai. Doorstep refrigerator service in Porur & nearby areas.',
            'focus_keyword'    => 'Refrigerator repair Chennai',
            'tags'             => ['Fridge repair Chennai', 'Refrigerator service Porur'],
            'gtm_category'     => 'refrigerator_blog',
        ]);

        BlogPost::create([
            'blog_category_id' => $blogCategories[2]->id,
            'author_id'        => $admin->id,
            'title'            => 'Washing Machine Repair in Chennai – Doorstep Service in Porur',
            'slug'             => 'washing-machine-repair-chennai-porur',
            'excerpt'          => 'Top-load & front-load washing machine repair in Chennai with same-day service.',
            'content'          => '
        <h1>Washing Machine Repair in Chennai</h1>

        <p>If your washing machine is not spinning, draining, or making noise, our expert technicians in Porur can help.</p>

        <h2>Common Issues</h2>

        <h3>Machine Not Spinning</h3>
        <p>Motor failure or belt damage are common reasons.</p>

        <h3>Not Draining</h3>
        <p>Drain pump blockage or hose clog can stop drainage.</p>

        <h3>Excessive Vibration</h3>
        <p>Improper leveling or drum damage may cause shaking.</p>

        <h2>Service Areas</h2>
        <ul>
        <li>Porur</li>
        <li>Guindy</li>
        <li>Maduravoyal</li>
        <li>Vadapalani</li>
        </ul>

        <p><strong>Call today for washing machine repair in Chennai.</strong></p>
        ',
            'status'           => 'published',
            'published_at'     => now()->subDays(4),
            'meta_title'       => 'Washing Machine Repair Chennai – Fast Service in Porur',
            'meta_description' => 'Affordable washing machine repair in Chennai. Same-day doorstep service in Porur & nearby areas.',
            'focus_keyword'    => 'Washing machine repair Chennai',
            'tags'             => ['Washing machine repair Chennai'],
            'gtm_category'     => 'washing_machine_blog',
        ]);

        BlogPost::create([
            'blog_category_id' => $blogCategories[3]->id,
            'author_id'        => $admin->id,
            'title'            => 'Microwave Oven Repair in Chennai – Fast Service in Porur',
            'slug'             => 'microwave-oven-repair-chennai-porur',
            'excerpt'          => 'Microwave not heating? Get expert microwave oven repair in Chennai.',
            'content'          => '
        <h1>Microwave Oven Repair in Chennai</h1>

        <p>If your microwave is not heating, sparking, or not rotating, professional repair is required.</p>

        <h2>Common Problems</h2>

        <h3>Not Heating</h3>
        <p>Magnetron or diode failure may stop heating.</p>

        <h3>Sparking Inside</h3>
        <p>Damaged waveguide cover or metal particles can cause sparks.</p>

        <h2>Areas Served</h2>
        <ul>
        <li>Porur</li>
        <li>Vadapalani</li>
        <li>Guindy</li>
        </ul>

        <p><strong>Book microwave repair in Chennai today.</strong></p>
        ',
            'status'           => 'published',
            'published_at'     => now()->subDays(5),
            'meta_title'       => 'Microwave Repair in Chennai – Same Day Service',
            'meta_description' => 'Professional microwave oven repair in Chennai & Porur. Fast doorstep service.',
            'focus_keyword'    => 'Microwave repair Chennai',
            'tags'             => ['Microwave repair Chennai'],
            'gtm_category'     => 'microwave_blog',
        ]);

        BlogPost::create([
            'blog_category_id' => $blogCategories[4]->id ?? $blogCategories[0]->id,
            'author_id'        => $admin->id,
            'title'            => 'Home Appliance Repair in Chennai – AC, Fridge, Washing Machine & Microwave Service in Porur',
            'slug'             => 'home-appliance-repair-chennai-porur',
            'excerpt'          => 'Professional home appliance repair in Chennai. AC, refrigerator, washing machine & microwave service in Porur, Maduravoyal, Vadapalani & Guindy.',
            'content'          => '

        <h1>Home Appliance Repair in Chennai – Trusted Service in Porur</h1>

        <p>Looking for reliable <strong>home appliance repair in Chennai</strong>? Whether your AC is not cooling, fridge stopped working, washing machine is not spinning, or microwave is not heating – our expert technicians provide fast, affordable doorstep service in Porur and nearby areas.</p>

        <p>We specialize in repairing all major home appliances with same-day service across Chennai.</p>

        <hr>

        <h2>Our Appliance Repair Services in Chennai</h2>

        <h3>1. AC Repair in Chennai</h3>
        <p>Chennai’s hot climate makes air conditioners essential. If your AC is not cooling, leaking water, or making noise, our professional AC technicians in Porur can fix it quickly.</p>

        <ul>
        <li>AC not cooling repair</li>
        <li>AC gas filling</li>
        <li>Water leakage fixing</li>
        <li>Compressor repair</li>
        <li>Split & Window AC service</li>
        </ul>

        <p><a href="/blog/ac-repair-chennai-porur-fast-service">Learn more about AC Repair in Chennai →</a></p>

        <hr>

        <h3>2. Refrigerator Repair in Chennai</h3>
        <p>A refrigerator breakdown can cause food spoilage. We provide expert fridge repair in Chennai including gas refilling, thermostat replacement, and compressor repair.</p>

        <ul>
        <li>Fridge not cooling</li>
        <li>Gas leakage repair</li>
        <li>Water leakage issues</li>
        <li>Double door fridge repair</li>
        </ul>

        <p><a href="/blog/refrigerator-repair-chennai-porur">Read our Refrigerator Repair Guide →</a></p>

        <hr>

        <h3>3. Washing Machine Repair in Chennai</h3>
        <p>Front-load and top-load washing machines often face drainage or spinning problems due to heavy usage.</p>

        <ul>
        <li>Machine not spinning</li>
        <li>Not draining water</li>
        <li>Drum noise repair</li>
        <li>PCB replacement</li>
        </ul>

        <p><a href="/blog/washing-machine-repair-chennai-porur">Explore Washing Machine Repair →</a></p>

        <hr>

        <h3>4. Microwave Oven Repair in Chennai</h3>
        <p>If your microwave is running but not heating, sparking inside, or turntable not rotating, we provide safe and reliable repair service.</p>

        <ul>
        <li>Magnetron replacement</li>
        <li>Heating issues</li>
        <li>Control panel repair</li>
        <li>Turntable motor fixing</li>
        </ul>

        <p><a href="/blog/microwave-oven-repair-chennai-porur">See Microwave Repair Details →</a></p>

        <hr>

        <h2>Why Choose Our Appliance Repair Service in Porur?</h2>

        <ul>
        <li>✔ Same-day doorstep service</li>
        <li>✔ Experienced & verified technicians</li>
        <li>✔ Affordable and transparent pricing</li>
        <li>✔ Genuine spare parts</li>
        <li>✔ Service warranty provided</li>
        </ul>

        <p>We cover Porur, Maduravoyal, Vadapalani, Guindy, Koyambedu, and nearby Chennai locations.</p>

        <hr>

        <h2>Common Appliance Problems We Fix</h2>

        <h3>AC Not Cooling in Chennai Summer</h3>
        <p>High temperature and dust accumulation cause frequent AC failures. Regular maintenance prevents breakdowns.</p>

        <h3>Refrigerator Not Cooling</h3>
        <p>Gas leakage or compressor issues are common in Chennai’s humid climate.</p>

        <h3>Washing Machine Not Draining</h3>
        <p>Clogged pumps and hose blockages cause water retention problems.</p>

        <h3>Microwave Not Heating</h3>
        <p>Magnetron failure or diode issues require expert attention.</p>

        <hr>

        <h2>Appliance Repair Cost in Chennai</h2>

        <p>Repair cost depends on appliance type and issue severity.</p>

        <ul>
        <li>Basic inspection: ₹400 – ₹600</li>
        <li>Minor repair: ₹600 – ₹1500</li>
        <li>Major part replacement: Depends on component</li>
        </ul>

        <p>We provide transparent estimates before starting repair.</p>

        <hr>

        <h2>Service Areas in Chennai</h2>

        <ul>
        <li>Porur</li>
        <li>Maduravoyal</li>
        <li>Vadapalani</li>
        <li>Guindy</li>
        <li>Koyambedu</li>
        <li>Valasaravakkam</li>
        </ul>

        <hr>

        <h2>Frequently Asked Questions</h2>

        <h3>Do you provide same-day appliance repair in Porur?</h3>
        <p>Yes, we offer same-day doorstep service in most Chennai locations.</p>

        <h3>Do you repair all brands?</h3>
        <p>Yes, we service all major appliance brands.</p>

        <h3>Is there a warranty on repairs?</h3>
        <p>Yes, warranty depends on the type of repair performed.</p>

        <p><strong>Call now for trusted home appliance repair in Chennai.</strong></p>

        <script type="application/ld+json">
        {
        "@context": "https://schema.org",
        "@type": "FAQPage",
        "mainEntity": [{
        "@type": "Question",
        "name": "Do you provide same-day appliance repair in Chennai?",
        "acceptedAnswer": {
            "@type": "Answer",
            "text": "Yes, we offer same-day doorstep service in Porur and nearby Chennai areas."
        }
        }]
        }
        </script>

        ',
            'status'           => 'published',
            'published_at'     => now()->subDay(),
            'meta_title'       => 'Home Appliance Repair in Chennai – AC, Fridge, Washing Machine Service',
            'meta_description' => 'Trusted home appliance repair in Chennai. AC, refrigerator, washing machine & microwave repair in Porur & nearby areas. Same-day service available.',
            'focus_keyword'    => 'Home appliance repair Chennai',
            'tags'             => ['Appliance repair Chennai', 'AC repair', 'Fridge repair', 'Washing machine repair', 'Microwave repair'],
            'gtm_category'     => 'pillar_page',
        ]);
    }
}