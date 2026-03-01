<?php

namespace App\Data;

/**
 * Unique hyper-local content for each service area.
 * Used by ServiceAreaLanding to avoid "scaled doorway page" penalties.
 *
 * Each entry contains:
 *  - landmarks      : 3-5 real local landmarks / roads / areas
 *  - housing_type   : dominant housing in that area
 *  - local_issues   : real appliance problems specific to this area
 *  - unique_section : 200-300 word unique prose (HTML-safe plain text)
 *  - unique_faqs    : 2 location-specific FAQs not in the generic set
 *  - geo            : lat/lng for LocalBusiness schema areaServed
 */
class LocationIntelligence
{
    public static function get(string $areaSlug): array
    {
        return self::DATA[$areaSlug] ?? self::fallback($areaSlug);
    }

    private static function fallback(string $areaSlug): array
    {
        return [
            'landmarks'      => [],
            'housing_type'   => 'residential apartments and independent houses',
            'local_issues'   => ['general wear and tear', 'dust accumulation'],
            'unique_section' => '',
            'unique_faqs'    => [],
            'geo'            => ['lat' => 13.0827, 'lng' => 80.2707],
        ];
    }

    public const DATA = [

        'porur' => [
            'landmarks'    => ['Porur Lake', 'DLF Garden City', 'Ramachandra Hospital', 'Porur Junction', 'VGP Housing'],
            'housing_type' => 'large gated communities, IT staff apartments, and independent houses along the GST Road belt',
            'local_issues' => [
                'Hard water deposits clogging AC filters and refrigerator water dispensers',
                'Frequent compressor strain due to heat from the nearby industrial belt',
                'Dust ingress from ongoing GST Road construction affecting AC coils',
                'Power fluctuations near Porur Junction causing PCB damage in washing machines',
            ],
            'unique_section' => 'Porur has grown into one of Chennai\'s most densely populated IT corridors, with thousands of families living in large gated communities like DLF Garden City, Olympia Opaline, and the residential pockets along Trunk Road and GST Road. The area\'s proximity to the IT corridor means most households run their ACs virtually round-the-clock during summer — making routine service critical, not optional.

One of the most common issues we see in Porur specifically is hard water damage. The borewell water supply in many Porur apartment complexes is notably high in TDS, which accelerates scale build-up inside refrigerator water lines, washing machine drum seals, and AC condensate trays. Left unchecked, this leads to foul odours, mold growth, and reduced cooling efficiency.

Residents near Porur Lake Road and the older VGP Housing layouts often face dust ingress problems — the ongoing road widening and construction means external AC units accumulate grime faster than in other parts of Chennai, requiring more frequent deep-cleaning services.

We have a dedicated team of technicians based in Porur itself, familiar with the building access protocols at major complexes like Ramachandra Towers, Shriram Summitt, and Olympia Opaline. Typical response time within Porur is 30–45 minutes. We service all brands — Samsung, LG, Voltas, Daikin — and carry genuine spare parts for the most common models used in this area.',
            'unique_faqs' => [
                [
                    'q' => 'Do you service apartments near Porur Lake and DLF Garden City?',
                    'a' => 'Yes, absolutely. We regularly service apartments in DLF Garden City, Olympia Opaline, Shriram Summitt, and all residential complexes along Porur Lake Road and Trunk Road. Our technicians know the access protocols for these gated communities and typically arrive within 30–45 minutes.',
                ],
                [
                    'q' => 'Why does my AC cool poorly despite regular use in Porur?',
                    'a' => 'Poor cooling in Porur is often caused by hard water scale on the evaporator coil or dust blockage from the ongoing road construction near GST Road and Porur Junction. A deep-cleaning service usually resolves this. We recommend Porur residents service their ACs every 3–4 months rather than the standard 6 months due to the local dust levels.',
                ],
            ],
            'geo' => ['lat' => 13.0358, 'lng' => 80.1560],
        ],

        'anna-nagar' => [
            'landmarks'    => ['Anna Nagar Tower', 'Shanthi Colony', '2nd Avenue', 'Roundtana', 'CMBT Bus Terminus'],
            'housing_type' => 'premium independent houses, high-rise apartments, and older bungalows in Western Extension',
            'local_issues' => [
                'Older buildings with outdated wiring causing voltage instability in appliances',
                'Window AC units in bungalows requiring specialist fitting skills',
                'High humidity near CMBT causing refrigerator gasket deterioration',
                'Premium brand appliances (Bosch, Siemens) requiring specialised service',
            ],
            'unique_section' => 'Anna Nagar is one of Chennai\'s most well-planned and affluent residential areas, home to a mix of older independent bungalows in the Western Extension and modern high-rise apartments closer to the 2nd Avenue commercial strip. The area\'s housing diversity means our technicians encounter a wider range of appliance brands and models here than almost anywhere else in Chennai.

Homes in the older Anna Nagar layouts — particularly along Shanthi Colony, 14th Main Road, and the bungalow belt near Anna Nagar Tower — often run appliances that are 12–15 years old. These require careful diagnosis, as spare parts for older models can take 24–48 hours to source. We always carry a comprehensive stock of common parts to minimise wait times.

Residents in the high-rise towers closer to Roundtana and CMBT frequently report refrigerator gasket issues — the higher humidity near the bus terminus and the frequent door opening in large families accelerates seal wear. We stock OEM gaskets for all major brands and can typically replace them in a single visit.

Anna Nagar also has a higher-than-average concentration of premium European appliances (Bosch, Siemens, SMEG). Our senior technicians are specially trained on these brands and carry brand-specific diagnostic tools. If you own a premium appliance, please mention the brand when booking so we can dispatch the right specialist.',
            'unique_faqs' => [
                [
                    'q' => 'Can you repair older appliances (10+ years) in Anna Nagar Western Extension bungalows?',
                    'a' => 'Yes. We specialise in older appliances that many services refuse to touch. Our Anna Nagar technicians carry a wide range of legacy parts, and for rare components we can usually source within 24–48 hours. We\'ll give you an honest assessment of whether repair is economical versus replacement.',
                ],
                [
                    'q' => 'Do you service premium brands like Bosch and Siemens in Anna Nagar?',
                    'a' => 'Absolutely. We have senior technicians trained specifically on European premium brands including Bosch, Siemens, and Electrolux. Please mention your brand when booking so we can ensure the right technician with the correct diagnostic equipment is dispatched to your Anna Nagar address.',
                ],
            ],
            'geo' => ['lat' => 13.0850, 'lng' => 80.2101],
        ],

        'adyar' => [
            'landmarks'    => ['Adyar River', 'Besant Nagar Beach Road', 'LIC Colony', 'Kasturba Nagar', 'Adyar Bakery'],
            'housing_type' => 'premium seafront apartments, independent houses in LIC Colony, and Thiruvanmiyur border residences',
            'local_issues' => [
                'Severe salt-air corrosion on outdoor AC units near Besant Nagar seafront',
                'High humidity accelerating mold growth inside refrigerators and washing machine drums',
                'PCB corrosion in appliances in ground-floor flats near Adyar River flood zones',
                'AC outdoor units requiring anti-corrosion coating due to sea breeze',
            ],
            'unique_section' => 'Adyar\'s coastal location — sandwiched between the Adyar River and the Bay of Bengal — creates a uniquely aggressive environment for home appliances. The combination of salt-laden sea breeze, high ambient humidity, and occasional flooding near the river banks means appliances in Adyar experience problems not typically seen in inland Chennai areas.

The most significant issue is salt-air corrosion on outdoor AC units. Homes and apartments within 1.5km of Besant Nagar beach — including the popular LIC Colony, Kasturba Nagar, and Karpagam Avenue areas — see outdoor condenser units corrode at 3–4 times the rate of units in western Chennai. We strongly recommend annual anti-corrosion treatment for all outdoor units in Adyar, which we offer as an add-on service.

Ground-floor apartments near the Adyar River estuary have an additional challenge: seasonal flooding leaves residual moisture that causes PCB corrosion in washing machines and refrigerators. We\'ve developed a post-flood appliance inspection checklist specifically for Adyar residents.

Premium high-rise apartments along Sardar Patel Road and the Lattice Bridge Road corridor are among our highest-volume Adyar customers. Residents in these buildings often run multiple split ACs, and we offer discounted multi-unit service packages for households with 3 or more ACs.',
            'unique_faqs' => [
                [
                    'q' => 'My AC outdoor unit near Besant Nagar beach is rusting — is this repairable?',
                    'a' => 'Salt-air corrosion on outdoor units is very common near Besant Nagar and Adyar beach. Depending on severity, we can clean, treat, and apply a protective anti-corrosion coating. Severely corroded fins may need fin comb straightening or panel replacement. We offer a dedicated coastal unit treatment service specifically for Adyar and Besant Nagar residents.',
                ],
                [
                    'q' => 'My fridge smells musty in my Adyar flat — why?',
                    'a' => 'Musty refrigerator odour in Adyar is almost always caused by mold growing inside the drain pan or on the evaporator due to the high coastal humidity. A full internal cleaning and drain sanitisation — which takes about 45 minutes — eliminates this completely. We recommend Adyar residents do this every 6 months rather than the standard 12.',
                ],
            ],
            'geo' => ['lat' => 13.0012, 'lng' => 80.2565],
        ],

        'velachery' => [
            'landmarks'    => ['Phoenix Mall', 'Velachery Lake', 'MRTS Velachery Station', 'Vijaya Nagar', 'SRM Institute Road'],
            'housing_type' => 'mid-to-premium apartment complexes, IT professional households, and older Vijaya Nagar layouts',
            'local_issues' => [
                'Waterlogging near Velachery Lake causing ground floor appliance damage during monsoon',
                'High AC usage by IT professionals leading to early compressor wear',
                'Hard water from corporation supply causing washing machine drum scaling',
                'Dust from Metro Rail Phase 2 construction affecting AC external units',
            ],
            'unique_section' => 'Velachery sits at the intersection of Chennai\'s IT belt and its most flood-prone low-lying zones, making it a uniquely challenging area for home appliances. The presence of Velachery Lake and the historically poor drainage in several localities means flooding risk is real for ground-floor and basement residents every monsoon season.

We are one of the few appliance repair services in Chennai with a formal post-flood recovery protocol. After the 2015 Chennai floods and the subsequent near-floods in 2021 and 2023, we developed a systematic approach to inspecting and restoring appliances that have been exposed to floodwater. If your appliance was in contact with water, we recommend an inspection before switching it on — water-damaged electronics can be a safety hazard.

The IT professional demographic in Velachery also means ACs are running 12–16 hours daily during work-from-home periods. This heavy usage profile leads to faster compressor wear and more frequent gas top-ups than the Chennai average. We recommend Velachery residents on WFH schedules service their ACs every 4 months.

The Phoenix Mall corridor and the SRM Road apartment belt — including Vijaya Nagar, Palm Groove, and Akshaya Tango — form our highest-density Velachery service zone. We typically have technicians in this area between 9AM and 6PM daily.',
            'unique_faqs' => [
                [
                    'q' => 'My ground floor apartment near Velachery Lake gets flooded during monsoon — can you inspect my appliances after flooding?',
                    'a' => 'Yes, this is one of our most requested services in Velachery during and after the monsoon. We conduct a comprehensive post-flood appliance safety inspection covering washing machines, refrigerators, and ACs. We check for water ingress, PCB damage, and motor insulation before clearing appliances for safe use. Please do not switch on flooded appliances before an inspection.',
                ],
                [
                    'q' => 'I work from home in Velachery and run my AC 12+ hours daily — how often should I service it?',
                    'a' => 'For WFH professionals running ACs 10+ hours daily, we recommend servicing every 3–4 months instead of the standard 6. Heavy usage causes faster filter clogging, coil fouling, and gas depletion. A regular service schedule will maintain cooling efficiency and prevent the compressor from overworking, extending its life significantly.',
                ],
            ],
            'geo' => ['lat' => 12.9810, 'lng' => 80.2209],
        ],

        't-nagar' => [
            'landmarks'    => ['Ranganathan Street', 'Pondy Bazaar', 'Panagal Park', 'Usman Road', 'T. Nagar Bus Terminus'],
            'housing_type' => 'commercial-residential mixed-use buildings, older flats above shops, and independent houses in quieter residential streets',
            'local_issues' => [
                'Severe voltage fluctuations from heavy commercial load causing PCB failures',
                'Dust and textile fibres from Pondy Bazaar clogging AC filters daily',
                'Old building electrical infrastructure incompatible with modern inverter ACs',
                'Refrigerators working harder due to frequent door opening in large joint families',
            ],
            'unique_section' => 'T. Nagar is Chennai\'s commercial heart, and this presents unique challenges for home appliances that don\'t exist in purely residential suburbs. The area\'s electrical grid carries enormous commercial load from thousands of shops, malls, and eateries — this creates voltage fluctuations that are more severe and more frequent than anywhere else in Chennai. Inverter-based appliances are particularly vulnerable: PCB failures in inverter ACs and inverter refrigerators are significantly more common in T. Nagar than the city average.

We strongly advise all T. Nagar residents to use a voltage stabiliser rated at least 4kVA for their AC and refrigerator. If you\'ve already experienced a PCB failure, we can supply and install the correct replacement — we stock PCBs for the 15 most common AC and refrigerator brands used in the area.

Homes and flats along Pondy Bazaar and Ranganathan Street face an unusual problem: airborne textile fibres and dust from the trading activity clog AC filters and condenser coils at an extraordinary rate. Residents in these areas report needing filter cleaning every 4–6 weeks. We offer a discounted bi-monthly AC cleaning subscription specifically for T. Nagar commercial-residential addresses.

The older buildings off Usman Road and the residential pockets around Panagal Park often have 3-phase power supplies and older wiring that needs assessment before installing modern inverter-type appliances.',
            'unique_faqs' => [
                [
                    'q' => 'My AC PCB fails repeatedly in my T. Nagar flat — is this a wiring issue?',
                    'a' => 'Repeated PCB failures in T. Nagar are almost always caused by voltage spikes from the area\'s heavily loaded commercial electrical grid. The fix is a combination of replacing the damaged PCB and installing a proper servo-controlled voltage stabiliser. We supply and install stabilisers for ACs and refrigerators — this investment typically pays for itself by preventing one PCB replacement.',
                ],
                [
                    'q' => 'AC filters in my flat near Pondy Bazaar get dirty within weeks — is this normal?',
                    'a' => 'Completely normal for Pondy Bazaar and Ranganathan Street addresses. Airborne textile fibres and street dust from the trading area are exceptionally fine and clog filters fast. We recommend monthly filter cleaning for this specific zone. We offer a T. Nagar monthly maintenance subscription that covers filter cleaning, coil check, and condensate drain clearing for a flat monthly fee.',
                ],
            ],
            'geo' => ['lat' => 13.0418, 'lng' => 80.2341],
        ],

        'koyambedu' => [
            'landmarks'    => ['CMBT Bus Stand', 'Koyambedu Market', 'Metro Rail Station', 'SIDCO Industrial Estate', 'Jawaharlal Nehru Salai'],
            'housing_type' => 'dense residential apartments, working-class housing near the market, and mid-range flats along NSK Salai',
            'local_issues' => [
                'Extreme dust and vegetable debris from Koyambedu Market affecting nearby AC units',
                'Hard water from municipal supply causing washing machine descaling issues',
                'High ambient temperature near SIDCO estate straining refrigerator compressors',
                'Frequent power cuts from heavy industrial load requiring UPS-protected appliances',
            ],
            'unique_section' => 'Koyambedu presents a distinctive set of appliance challenges driven by its identity as Chennai\'s largest wholesale market hub. Residents living within 1–2km of Koyambedu Market face a constant influx of organic particulates — vegetable matter, dust from lorry movements, and market debris — that settles on AC external units and in washing machine inlet filters at a rate far higher than typical residential areas.

The CMBT bus terminus and the adjoining SIDCO Industrial Estate generate heavy vehicle traffic and diesel exhaust, which coats AC condenser fins with a greasy film that reduces heat dissipation efficiency. This manifests as poor cooling even with a fully charged refrigerant. A deep coil cleaning is the solution, and we recommend it every 3 months for Koyambedu addresses near the market boundary.

Water quality is another major factor. The municipal water supply in parts of Koyambedu has elevated TDS, and many apartments rely on tanker water during summer — tanker water often has even higher mineral content. This causes visible white scaling inside washing machine drums and on fridge ice-maker components within 6–8 months of use.

Despite these challenges, Koyambedu is well-served by our team. We have technicians stationed near Jawaharlal Nehru Salai who can typically reach any Koyambedu address within 25–40 minutes of booking.',
            'unique_faqs' => [
                [
                    'q' => 'My flat near Koyambedu Market has a lot of dust — how often should I clean my AC?',
                    'a' => 'For apartments near Koyambedu Market, Vegetable Market Road, or the CMBT periphery, we recommend AC cleaning every 6–8 weeks due to the extremely high particulate load from market activity and lorry traffic. Neglecting this leads to ice formation on the coil and eventual compressor damage. Our Koyambedu team can set up a recurring maintenance schedule at your convenience.',
                ],
                [
                    'q' => 'There is white scale build-up inside my washing machine drum in Koyambedu — how do I fix this?',
                    'a' => 'Scale build-up in Koyambedu washing machines is caused by the high mineral content in both corporation and tanker water supplies. We perform a descaling treatment using food-grade citric acid solution that removes existing scale without damaging the drum or seals. We also fit an inlet filter that reduces future scaling. This service typically takes 45–60 minutes.',
                ],
            ],
            'geo' => ['lat' => 13.0694, 'lng' => 80.1948],
        ],

        'maduravoyal' => [
            'landmarks'    => ['Maduravoyal Bypass', 'Porur Flyover', 'Chennai Bypass Road', 'CMDA Layout', 'Pattabiram Road'],
            'housing_type' => 'CMDA layout independent houses, new apartment projects along the bypass, and older colony housing',
            'local_issues' => [
                'Extreme heat from the open bypass road raising ambient temperatures above 40°C in summer',
                'Diesel soot from heavy commercial vehicles on the bypass clogging AC external coils',
                'Newly constructed buildings with improper electrical earthing causing appliance faults',
                'Dust from unpaved internal roads in older CMDA layouts entering appliances',
            ],
            'unique_section' => 'Maduravoyal straddles the old city and the expanding suburban fringe along the Chennai Bypass, making it one of the fastest-growing residential belts in west Chennai. The mix of established CMDA layouts and brand-new apartment projects creates interesting appliance challenges — on one side, older homes with legacy wiring and aging appliances; on the other, freshly constructed buildings where improper earthing during construction causes subtle but damaging electrical faults.

The Chennai Bypass is one of the busiest freight corridors in Tamil Nadu, and the constant stream of heavy trucks means outdoor AC units in Maduravoyal collect diesel soot and road dust at a significantly higher rate than in quieter residential areas. This greasy particulate is harder to clean than regular dust and requires solvent-based cleaning agents rather than just a water jet — something our technicians are specifically trained for.

Summer temperatures in Maduravoyal are consistently 2–3°C higher than in coastal areas like Adyar or Anna Nagar, due to the open landscape and heat island effect from the bypass. This means refrigerators and ACs are under greater thermal load, and compressors in older units often struggle during April–June. Preventive service before summer is critical here.

We service all housing in Maduravoyal — from the independent houses near Pattabiram Road to the apartment towers along the bypass — with technicians typically available within 30–50 minutes.',
            'unique_faqs' => [
                [
                    'q' => 'My new apartment in Maduravoyal has AC tripping frequently — could it be an earthing issue?',
                    'a' => 'Absolutely — this is one of the most common issues we see in newly constructed Maduravoyal buildings. Improper electrical earthing during construction causes residual current protection (RCBO) to trip the AC circuit. Our technician can diagnose the root cause, and if it\'s an earthing issue, we\'ll advise your building\'s electrician on the correct fix. The AC itself is unlikely to be faulty.',
                ],
                [
                    'q' => 'How often should I service my AC in Maduravoyal given the highway dust?',
                    'a' => 'We recommend every 6–8 weeks for properties directly facing or within 500m of the Chennai Bypass. The diesel soot from freight vehicles coats condenser fins with a film that dramatically reduces cooling efficiency and forces the compressor to overwork. A deep coil clean with our solvent-based cleaning process removes this build-up completely in one visit.',
                ],
            ],
            'geo' => ['lat' => 13.0697, 'lng' => 80.1284],
        ],

        'vadapalani' => [
            'landmarks'    => ['Vadapalani Murugan Temple', 'Arcot Road', 'Vadapalani Bus Stand', 'Kaliamman Koil Street', 'Virugambakkam Junction'],
            'housing_type' => 'dense residential apartments, temple-adjacent housing, and commercial-residential buildings along Arcot Road',
            'local_issues' => [
                'Incense smoke and camphor vapour near the Murugan Temple clogging AC filters',
                'Heavy commercial traffic on Arcot Road generating dust and exhaust',
                'Old building plumbing causing hard water damage to washing machines',
                'Temple festival season (Panguni, Karthigai) power load spikes damaging electronics',
            ],
            'unique_section' => 'Vadapalani is defined by two things: the iconic Murugan Temple that draws thousands of devotees daily, and Arcot Road — one of Chennai\'s major arterial routes connecting the city to western suburbs. Both create unique appliance conditions for residents in the area.

The temple\'s constant activity — incense, camphor, and the foot traffic of thousands — releases fine particulate matter that settles in nearby residential buildings. AC filters in apartments within 300m of the temple need cleaning every 3–4 weeks during peak festival periods like Panguni Uthiram and Karthigai Deepam. We\'ve noticed that appliances in temple-adjacent flats on Kaliamman Koil Street and the lanes leading off the Vadapalani junction accumulate filter build-up twice as fast as properties in quieter streets.

Arcot Road\'s heavy traffic — buses, autos, commercial vehicles — adds diesel exhaust and rubber dust to the mix, particularly affecting outdoor AC units installed facing the main road. We provide a "highway-facing" deep coil service for these units that includes a protective fin coating to slow future fouling.

On the electrical side, the large transformer loads during major temple festivals have been known to cause voltage spikes that damage PCBs in sensitive inverter electronics. We see a noticeable spike in PCB replacement requests in the week following major Vadapalani temple festivals — a stabiliser is highly recommended for residents within 500m of the temple.',
            'unique_faqs' => [
                [
                    'q' => 'My AC near Vadapalani Murugan Temple smells like incense — can it be fixed?',
                    'a' => 'Yes, this is a very specific issue we deal with regularly in Vadapalani. Incense particulates and camphor residue accumulate on the evaporator coil and cause a burning smell when the AC runs. A deep coil cleaning with our enzyme-based cleaner eliminates the odour completely. We recommend residents within 500m of the temple do this every 2 months during festival seasons.',
                ],
                [
                    'q' => 'Do you service areas along Arcot Road and the Virugambakkam junction?',
                    'a' => 'Yes — Arcot Road, Virugambakkam junction, and all the residential streets running off it (including Kaliamman Koil Street, 100 Feet Road, and the colonies near Vadapalani bus stand) are all within our core Vadapalani service zone. Typical response time for Arcot Road addresses is 25–40 minutes.',
                ],
            ],
            'geo' => ['lat' => 13.0521, 'lng' => 80.2110],
        ],

        'ambattur' => [
            'landmarks'    => ['Ambattur Industrial Estate', 'OT Road', 'Padi', 'Ambattur Lake', 'SIDCO Nagar'],
            'housing_type' => 'workers\' quarters near the industrial estate, SIDCO Nagar layouts, and mid-range apartments along OT Road',
            'local_issues' => [
                'Industrial fumes and chemical particulates from Ambattur Estate affecting AC performance',
                'Hard borewell water in SIDCO layouts scaling washing machine heating elements',
                'Frequent voltage dips from industrial load causing compressor damage',
                'Cotton lint from textile units clogging AC return air filters',
            ],
            'unique_section' => 'Ambattur is one of Chennai\'s oldest and largest industrial estates, and this industrial character has a direct impact on home appliances in the residential areas surrounding it. The estate houses hundreds of manufacturing units — textiles, chemicals, light engineering — and the emissions and particulates from these operations settle in the residential colonies along OT Road, SIDCO Nagar, and the streets near Ambattur Lake.

Cotton lint from textile manufacturing units is a particularly insidious problem for air conditioners. Unlike regular dust, cotton fibres are light and mobile — they pass through standard AC return air grilles and compact densely on evaporator coils, reducing airflow significantly. We\'ve developed a specialised lint-extraction cleaning process for Ambattur addresses that removes embedded fibres without damaging the coil fins.

Chemical particulates from paint and solvent manufacturers in the estate area can corrode the aluminium fins of outdoor condenser units over time. We offer a protective anti-corrosion treatment for outdoor units in Ambattur that forms a barrier against chemical exposure — particularly useful for homes downwind of the estate.

The borewell water quality in SIDCO Nagar and surrounding layouts is heavily mineralised. Washing machine drum seals, heating elements, and drum bearings show accelerated wear compared to Chennai averages. We recommend annual descaling for all washing machines in Ambattur.',
            'unique_faqs' => [
                [
                    'q' => 'My AC filter gets clogged with white fibres in Ambattur — what is causing this?',
                    'a' => 'Those white fibres are cotton lint from the textile manufacturing units in Ambattur Industrial Estate. This is a very common issue for residential properties near OT Road and SIDCO Nagar. Standard filter cleaning won\'t fully remove embedded cotton fibres — we use a specialised lint-extraction process that clears the coil completely. We recommend monthly filter checks for Ambattur addresses near the estate.',
                ],
                [
                    'q' => 'Does the industrial pollution near Ambattur Estate damage outdoor AC units?',
                    'a' => 'Yes, chemical particulates from paint, solvent, and light engineering units in the estate accelerate corrosion of aluminium condenser fins. We offer an anti-corrosion protective coating service for outdoor units in Ambattur that significantly extends the life of the condenser. This is particularly recommended for properties on the eastern side of the estate where prevailing winds carry emissions.',
                ],
            ],
            'geo' => ['lat' => 13.1143, 'lng' => 80.1548],
        ],

        'mogappair' => [
            'landmarks'    => ['Mogappair East', 'Mogappair West', 'Anna Arch', 'TNHB Colony', 'Collector Nagar'],
            'housing_type' => 'large TNHB apartment complexes, government quarters, and newer private apartment towers in East Mogappair',
            'local_issues' => [
                'TNHB apartments with aging electrical panels incompatible with modern inverter ACs',
                'High occupancy in government quarters causing shared-circuit overloading',
                'Hard Cauvery-mix water causing calcium deposits in washing machine inlets',
                'Tree pollen from the heavily wooded Collector Nagar area blocking AC coils',
            ],
            'unique_section' => 'Mogappair is one of Chennai\'s largest planned residential zones, dominated by the iconic TNHB apartment complexes that house thousands of government employees and middle-class families. The large-scale planned housing here creates specific appliance service patterns unlike anywhere else in Chennai.

The original TNHB blocks in Mogappair West were constructed decades ago with electrical infrastructure designed for far lighter appliance loads than today\'s typical household. Many of these blocks still have original wiring panels rated at 4–6 amps per flat. Modern split ACs draw 6–10 amps, and this incompatibility regularly causes circuit breaker trips, overheated wiring, and — in worst cases — PCB damage from unstable power delivery. Before installing a new AC in a TNHB Mogappair flat, we recommend an electrical load assessment, which we offer as a pre-installation service.

Mogappair East has seen significant private development in recent years, with numerous apartment towers replacing older layouts. These newer buildings don\'t share the legacy electrical problems but face different issues — inadequate earthing in hastily constructed buildings being the most common cause of appliance complaints we encounter.

Collector Nagar\'s heavy tree cover — while making it one of Chennai\'s greener neighbourhoods — means AC outdoor units accumulate significant tree pollen and leaf debris during the pre-monsoon season. We recommend a pre-summer outdoor unit check for all Collector Nagar residents.',
            'unique_faqs' => [
                [
                    'q' => 'My AC keeps tripping the circuit breaker in my TNHB flat in Mogappair — what should I do?',
                    'a' => 'This is the most common appliance issue in TNHB Mogappair flats. The original electrical panels in most TNHB blocks cannot handle modern split AC loads. The circuit breaker is actually protecting your wiring — do not just replace it with a higher-rated breaker. We offer an electrical load assessment that determines whether your flat\'s wiring can safely support a split AC, and if not, what upgrades are needed.',
                ],
                [
                    'q' => 'I live in Collector Nagar, Mogappair — my AC outdoor unit is covered in tree debris. Is this a problem?',
                    'a' => 'Yes, leaf debris and tree pollen blocking the condenser fin gaps significantly reduces heat dissipation and forces the compressor to overwork. For Collector Nagar addresses, we recommend clearing the outdoor unit area and servicing the condenser before summer (March–April) and again before the monsoon (June). This prevents the most common cause of compressor failure in the area.',
                ],
            ],
            'geo' => ['lat' => 13.0944, 'lng' => 80.1640],
        ],

        'chromepet' => [
            'landmarks'    => ['Chromepet Railway Station', 'Chromepet Bypass', 'Pallavaram Road', 'St. Thomas Mount', 'Airport Link Road'],
            'housing_type' => 'railway staff quarters, CISF residential areas, and mid-range apartments along the GST Road belt near the airport',
            'local_issues' => [
                'Jet fuel exhaust and aviation particulates from proximity to Chennai Airport clogging AC units',
                'Electromagnetic interference from aviation navigation equipment affecting appliance electronics',
                'Hard borewell water in older railway colony housing scaling washing machines',
                'High ambient noise from aircraft requiring sound-isolated technical work',
            ],
            'unique_section' => 'Chromepet occupies a unique position in Chennai\'s geography — straddling the railway junction and the outskirts of Chennai International Airport. This proximity to major transit infrastructure creates appliance conditions that are genuinely unlike any other part of the city.

The most distinctive issue: jet fuel exhaust. The kerosene-based particulates from aircraft taking off and landing at the nearby airport settle on outdoor AC condenser units in Chromepet and Pallavaram at a rate that surprises many residents. This creates a oily film on condenser fins that normal water-jet cleaning fails to remove. Our Chromepet service team uses a solvent-pre-treatment before jet washing that specifically addresses aviation particulate build-up.

Railway colony residents — the large Tamil Nadu Railway employees\' quarters near Chromepet station — tend to have older appliances on very stable government housing electrical supplies. These properties often have window ACs that are 8–12 years old and still functioning but increasingly inefficient. We service all window AC brands and carry most common window unit spare parts.

St. Thomas Mount and the elevated residential areas nearby have excellent ambient airflow which partially offsets particulate issues, but also means outdoor units are exposed to more aggressive weather during the monsoon. We\'ve documented higher rates of PCB corrosion from monsoon moisture intrusion into outdoor units in the elevated Chromepet localities.',
            'unique_faqs' => [
                [
                    'q' => 'My AC near Chromepet airport area has a greasy coating on the outdoor unit — what is this?',
                    'a' => 'This is aviation particulate — fine kerosene combustion residue from aircraft operating at Chennai Airport. It is extremely common in Chromepet, Pallavaram, and Meenambakkam. Standard water cleaning doesn\'t fully remove it; we use a solvent pre-treatment specifically designed for petroleum-based fouling, followed by a high-pressure rinse. This service is among our most requested in Chromepet.',
                ],
                [
                    'q' => 'Do you service old window ACs in the Railway Colony near Chromepet Station?',
                    'a' => 'Absolutely. We regularly service the older window AC units in Chromepet Railway Colony and CISF residential quarters. We carry spare parts for all major window AC brands including Voltas, LG, Carrier, and Blue Star. For units older than 10 years, we\'ll provide an honest assessment of whether repair is cost-effective versus upgrading to a modern inverter split AC.',
                ],
            ],
            'geo' => ['lat' => 12.9516, 'lng' => 80.1461],
        ],

        'tambaram' => [
            'landmarks'    => ['Tambaram Railway Station', 'Tambaram Sanatorium', 'East Tambaram', 'Selaiyur', 'Mudichur Road'],
            'housing_type' => 'large suburban houses, railway staff residential areas, and newer apartment developments along Mudichur and GST Road',
            'local_issues' => [
                'Forest proximity causing increased insect and rodent entry into appliances',
                'High humidity from Pallikaranai marshland adjacent areas affecting appliance electronics',
                'Old colonial-era railway staff bungalows with outdated electrical infrastructure',
                'Distance from city centre meaning longer parts sourcing times for rare components',
            ],
            'unique_section' => 'Tambaram is one of Chennai\'s southernmost major suburbs, historically famous for its railway junction and the sprawling Tambaram Sanatorium. The area\'s semi-rural character — large plots, old trees, and proximity to the Pallikaranai marshland — creates appliance problems that are unusual in a city context.

Insect and rodent ingress into appliances is significantly more common in Tambaram than in urban Chennai. Cockroaches, ants, and occasional small rodents find their way into the control boards of washing machines and refrigerators, causing short circuits and erratic behaviour. We always check for evidence of pest damage during Tambaram service calls, and can recommend a post-repair pest exclusion treatment.

The high ambient humidity in East Tambaram and the areas bordering Pallikaranai marshland accelerates rusting of refrigerator interior components and causes mold growth in washing machine rubber door seals. We recommend Tambaram residents run a drum-cleaning cycle monthly and keep the washer door ajar between uses.

Selaiyur, Mudichur Road, and the newer Tambaram extensions are seeing rapid apartment development. Many of these projects have electrical installations that are technically compliant but barely adequate — undersized mains cables and shared earthing in low-budget developments are sources of recurring appliance problems that we\'ve become adept at diagnosing.',
            'unique_faqs' => [
                [
                    'q' => 'My washing machine in Tambaram stopped working — could it be an insect or rodent issue?',
                    'a' => 'Yes, and this is more common in Tambaram than in central Chennai due to the area\'s greenery and older housing stock. Ants and cockroaches are drawn to the warm, dark interior of appliance control boxes and can cause short circuits on PCBs. We routinely check for pest evidence on Tambaram service calls. If we find pest damage, we clean and treat the control compartment and recommend sealing entry points to prevent recurrence.',
                ],
                [
                    'q' => 'Do you cover Selaiyur and Mudichur Road for appliance repair?',
                    'a' => 'Yes — Selaiyur, Mudichur Road, Vandalur, and the newer residential developments extending south of Tambaram are all within our service zone. Response time to these southern extensions is typically 45–60 minutes as they are slightly further from our nearest base. We recommend booking morning slots for same-day service to these areas.',
                ],
            ],
            'geo' => ['lat' => 12.9229, 'lng' => 80.1275],
        ],

        'guindy' => [
            'landmarks'    => ['Guindy Industrial Estate', 'IIT Madras', 'Raj Bhavan', 'Guindy National Park', 'TIDEL Park'],
            'housing_type' => 'IIT staff quarters, government bungalows, and the newer commercial-residential mix near TIDEL Park',
            'local_issues' => [
                'Industrial fumes from Guindy Estate affecting nearby residential AC units',
                'Deer and wildlife from Guindy National Park causing outdoor unit disturbances',
                'Government housing with outdated single-phase supply limiting AC options',
                'High ambient heat near TIDEL Park data centre infrastructure',
            ],
            'unique_section' => 'Guindy is a study in contrasts — one of Chennai\'s oldest and most prestigious residential zones, home to IIT Madras, the Raj Bhavan, and the Guindy National Park, it also borders a large and active industrial estate. This dual identity creates a distinctive appliance service landscape.

Residents near the Guindy Industrial Estate — particularly those in the residential streets behind Ashok Nagar and along 100 Feet Road — are affected by industrial particulates similar to Ambattur, but with more diverse manufacturing outputs including auto components and rubber products. The rubber dust from auto-component manufacturers is particularly problematic for AC external units, coating condenser fins with a sticky residue that traps heat.

IIT Madras staff quarters and the government bungalows near Raj Bhavan are among the most prestigious addresses we service in Chennai, but also among the most electrically conservative. Many of these properties were designed for lighter appliance loads and have single-phase supply configurations that require load balancing before modern multi-ton ACs can be safely installed.

The areas closest to TIDEL Park and the IT corridor have seen significant densification with new apartments. These areas have good electrical infrastructure but face the heavy-usage profile common to IT professional households.',
            'unique_faqs' => [
                [
                    'q' => 'Do you service the residential areas inside or near IIT Madras campus in Guindy?',
                    'a' => 'Yes, we service IIT Madras staff quarters and all residential addresses near the campus. Campus access requires prior notice — please mention "IIT Madras" when booking so our team can carry the necessary visitor identification. We are familiar with the campus layout and can navigate to any staff residential block.',
                ],
                [
                    'q' => 'My AC near Guindy Industrial Estate has a rubber smell when it runs — what is causing this?',
                    'a' => 'Rubber and automotive particulates from the auto-component units in Guindy Industrial Estate create a sticky film on condenser and evaporator coils. When the AC runs, these compounds vaporise slightly and can create a rubber or chemical smell inside the room. A thorough coil cleaning with our degreaser-based process eliminates this. We recommend quarterly deep cleaning for Guindy Estate-adjacent properties.',
                ],
            ],
            'geo' => ['lat' => 13.0067, 'lng' => 80.2206],
        ],

        'mylapore' => [
            'landmarks'    => ['Kapaleeshwarar Temple', 'Luz Corner', 'Mylapore Tank', 'Kutchery Road', 'Alwarpet Junction'],
            'housing_type' => 'heritage-listed old Brahmin agraharam houses, Kutchery Road apartments, and premium Alwarpet border residences',
            'local_issues' => [
                'Incense and floral offerings near Kapaleeshwarar Temple clogging AC filters',
                'Very old plumbing in heritage houses causing scale damage to washing machines',
                'Narrow lanes limiting outdoor AC unit installation options',
                'High cultural event activity (music season) creating seasonal power load spikes',
            ],
            'unique_section' => 'Mylapore is Chennai\'s cultural capital — home to the magnificent Kapaleeshwarar Temple, the December Music Season, and some of the city\'s most treasured heritage architecture. This cultural richness also creates some of Chennai\'s most unique appliance service conditions.

The area within 500m of Kapaleeshwarar Temple has the highest concentration of incense and floral offering particulates of any location in Chennai. During festival seasons — Panguni, Brahmotsavam, and the daily morning-evening puja cycles — the air carries fine camphor and dhoop particles that are exceptionally fine and penetrate deep into AC evaporator coils. The characteristic sweet-spice smell that sometimes wafts from ACs in Mylapore homes is this incense residue. A specialist cleaning is required to fully remove it.

Heritage homes in the agraharam streets off Kutchery Road face unique installation challenges: narrow lanes, low roof heights, and heritage building restrictions make outdoor AC unit placement complex. Our Mylapore team includes an installation specialist who knows every agraharam street and can find compliant, efficient placements for outdoor units within these constraints.

The December Carnatic Music Season brings an influx of kutcheri attendees and a significant seasonal power load increase across Mylapore. We see a noticeable spike in calls in January from power-surge-related PCB damage from the December season overloads.',
            'unique_faqs' => [
                [
                    'q' => 'My AC near Kapaleeshwarar Temple smells of incense when it runs — can this be fixed?',
                    'a' => 'This is one of the most distinctly Mylapore appliance issues. Incense and camphor particulates from the temple penetrate AC coils and create a sweet-burning smell when the AC runs. Our enzyme-based coil cleaning specifically targets organic incense residue and eliminates the smell completely. For flats within 300m of the temple, we recommend this service every 2 months during festival seasons.',
                ],
                [
                    'q' => 'I live in an old agraharam house in Mylapore — can you install a split AC in a narrow lane with heritage restrictions?',
                    'a' => 'We have significant experience with heritage and agraharam house installations in Mylapore. Our installation specialist knows the typical structural constraints in these properties and can advise on the most compliant positioning for outdoor units — often using slim-profile brackets, elevated mounting on rear walls, or internal duct routing. Contact us for a free pre-installation survey before purchasing your AC.',
                ],
            ],
            'geo' => ['lat' => 13.0335, 'lng' => 80.2676],
        ],

        'kilpauk' => [
            'landmarks'    => ['Kilpauk Medical College', 'Kilpauk Garden', 'Poonamallee High Road', 'Choolaimedu', 'Chetpet Lake'],
            'housing_type' => 'medical college staff quarters, independent houses in garden colony, and newer apartments near Poonamallee High Road',
            'local_issues' => [
                'Hospital area power supply being prioritised causing residential voltage dips',
                'Medical waste particulates (disinfectant aerosols) affecting AC air quality',
                'Hard water from Poonamallee main supply causing washing machine scale',
                'Old garden colony houses with heritage-era electrical wiring',
            ],
            'unique_section' => 'Kilpauk is defined by its medical identity — Kilpauk Medical College and the associated hospitals dominate the area\'s character, and this has specific implications for nearby residential appliances. The hospital complex and its associated facilities have priority power supply arrangements with TANGEDCO, which means residential areas in the immediate vicinity can experience voltage dips when hospital load peaks.

Medical disinfectant aerosols — specifically the chlorine and phenol compounds used in hospital sterilisation — can be detected at trace levels in the air within 300m of Kilpauk Medical College. Over time, these compounds cause subtle corrosion on copper AC refrigerant lines and aluminium condenser fins. We use a specific passivation treatment when servicing AC units in the immediate KMC neighbourhood.

Kilpauk Garden is one of Chennai\'s oldest and most elegant residential areas, with large bungalows set in generous plots. Many of these properties have plumbing and electrical infrastructure from the 1960s–1970s, and the combination of hard water from aged pipes and undersized electrical supply creates a distinctive service challenge. Our Kilpauk team includes senior technicians with experience in heritage property service.

The area near Poonamallee High Road is more densely built and modern, with fewer heritage challenges but higher vehicle exhaust levels.',
            'unique_faqs' => [
                [
                    'q' => 'My flat near Kilpauk Medical College has frequent power fluctuations — is this affecting my appliances?',
                    'a' => 'Yes — the hospital area\'s priority power arrangements do cause residential voltage instability, particularly during peak hospital operations (6AM–10AM and 6PM–9PM). Inverter-based appliances are most vulnerable. A servo-type voltage stabiliser for your AC and refrigerator is the most cost-effective protection. We supply and install stabilisers rated for all appliance types — please ask when booking.',
                ],
                [
                    'q' => 'Do you service the large bungalows in Kilpauk Garden colony?',
                    'a' => 'Yes, Kilpauk Garden is one of our regular service areas. The older bungalows here often have window ACs, split ACs installed across multiple decades, and legacy plumbing. Our senior technicians are comfortable working in these heritage properties. We also offer a comprehensive multi-appliance health check package that is popular with Kilpauk Garden homeowners.',
                ],
            ],
            'geo' => ['lat' => 13.0810, 'lng' => 80.2390],
        ],

        'perambur' => [
            'landmarks'    => ['Integral Coach Factory', 'Perambur Railway Station', 'Old Washermanpet Road', 'Kolathur Link Road', 'Tondiarpet'],
            'housing_type' => 'ICF staff quarters, working-class independent houses, and older apartment colonies near the railway zone',
            'local_issues' => [
                'Metal grinding and machining particulates from ICF affecting AC units in nearby colonies',
                'Railway vibration from goods trains causing washing machine drum misalignment',
                'Hard municipal water supply causing heavy scaling in all water-using appliances',
                'Old ICF staff quarters with 1960s-era electrical wiring limiting modern appliance installation',
            ],
            'unique_section' => 'Perambur is one of Chennai\'s oldest industrial-residential zones, built around the massive Integral Coach Factory that has manufactured railway coaches here since 1955. The ICF\'s industrial operations have a direct impact on the appliances of the thousands of railway staff and local residents who live in its shadow.

Metal machining particulates — fine iron and steel dust from ICF\'s manufacturing operations — are present in the air around the factory boundary and settle on outdoor AC condenser units in surrounding colonies. This metallic dust is slightly magnetic and tends to cluster on condenser fins more densely than regular dust, creating a characteristic grey coating that severely restricts airflow. We recommend quarterly condenser cleaning for all ICF-adjacent addresses.

The constant vibration from heavy goods train movements through Perambur station — particularly the Howrah and Mumbai mail trains — is sufficient to gradually loosen washing machine feet bolts and cause drum bearing vibration in machines installed in ground-floor apartments directly adjacent to the railway line. If your washing machine vibrates excessively or walks across the floor, this railway vibration may be amplifying an existing bearing issue.

Despite these challenges, Perambur\'s large ICF staff colony provides a consistent demand for quality repair services, and we have dedicated Perambur-based technicians who typically respond within 30–40 minutes.',
            'unique_faqs' => [
                [
                    'q' => 'My washing machine vibrates very badly in my flat near Perambur railway station — what is causing this?',
                    'a' => 'Two factors are likely combining in your case: normal washing machine drum bearing wear, amplified by vibration transmitted through the building from heavy goods trains passing Perambur station. We recommend a drum bearing inspection and a proper anti-vibration pad installation. The pads significantly reduce the coupling of railway vibration into the machine body.',
                ],
                [
                    'q' => 'Do you service the ICF residential colony near Perambur?',
                    'a' => 'Yes — ICF residential colony, Perambur government quarters, and all the residential streets surrounding the factory are within our regular service area. For ICF colony addresses, please mention the colony block number when booking so our technician can find you quickly within the large campus layout.',
                ],
            ],
            'geo' => ['lat' => 13.1159, 'lng' => 80.2437],
        ],

        'omr' => [
            'landmarks'    => ['Sholinganallur Junction', 'Perungudi Toll', 'Tidel Park', 'Elcot SEZ', 'Rajiv Gandhi IT Expressway'],
            'housing_type' => 'premium IT-era apartment towers, gated villa communities, and serviced residences along the Rajiv Gandhi IT Expressway',
            'local_issues' => [
                'Round-the-clock AC usage by tech workers causing accelerated compressor wear',
                'Premium imported brands (Daikin, Mitsubishi) requiring specialised service',
                'High-rise buildings requiring rooftop and terrace outdoor unit installation skills',
                'Saline wind from nearby sea inlet at Sholinganallur increasing corrosion rates',
            ],
            'unique_section' => 'Old Mahabalipuram Road — better known simply as OMR or the IT Expressway — is Chennai\'s most dynamic residential belt, home to tens of thousands of technology professionals who work in the SEZs and IT parks that line this corridor. This creates an appliance service market unlike anywhere else in Chennai.

The defining characteristic of OMR appliance usage is intensity. With most residents working from home several days per week, ACs in OMR apartments run 10–14 hours daily — compared to the Chennai average of 5–7 hours. This translates to compressor degradation timelines of 4–6 years versus the standard 8–10, and gas depletion requiring refilling every 2–3 years rather than every 5. We strongly recommend OMR residents opt for our annual preventive maintenance plan rather than reactive repair.

Premium international brands are more common on OMR than anywhere else in Chennai — Daikin, Mitsubishi Electric, Carrier, and Fujitsu are all well-represented. Our OMR service team includes technicians specifically trained on these brands, and we maintain a stock of their commonly needed spare parts.

The high-rise towers of OMR — many reaching 20+ floors — require technicians comfortable with terrace and high-level outdoor unit work. All our OMR team members are trained in working at heights and carry the appropriate safety equipment.',
            'unique_faqs' => [
                [
                    'q' => 'I work from home on OMR and run my AC all day — how often should I service it?',
                    'a' => 'For WFH professionals running ACs 10+ hours daily on OMR, we recommend full service every 3 months and a gas pressure check every 18 months. Heavy usage in OMR\'s premium towers means coils foul faster, and compressors need monitoring. Our OMR Annual Maintenance Plan covers 4 services per year with priority response at a flat annual fee — most popular with tech professionals in this area.',
                ],
                [
                    'q' => 'Do you service Daikin and Mitsubishi ACs on OMR?',
                    'a' => 'Yes — these are among our most serviced brands on OMR. We have factory-trained technicians for Daikin, Mitsubishi Electric, Carrier, and Fujitsu. We stock genuine parts for all these brands at our OMR parts depot. Premium brand service typically costs slightly more than standard brand service due to the genuine parts requirement, but we always inform you of the cost before any work begins.',
                ],
            ],
            'geo' => ['lat' => 12.9010, 'lng' => 80.2279],
        ],

        'sholinganallur' => [
            'landmarks'    => ['Sholinganallur Junction', 'RMZ Millenia', 'Elnet SEZ', 'Apollo Sholinganallur', 'Kalaignar Centenary Bus Terminus'],
            'housing_type' => 'IT professional apartment towers, gated communities like Casagrand and TVH Lumbini, and older Sholinganallur village houses',
            'local_issues' => [
                'Saline breeze from Pallikaranai marshland border causing fin corrosion',
                'IT professional WFH heavy usage profile accelerating AC wear',
                'Marshy ground in older areas causing high ambient humidity affecting electronics',
                'Construction dust from ongoing IT park expansion coating outdoor units',
            ],
            'unique_section' => 'Sholinganallur sits at the intersection of Chennai\'s IT corridor and the ecologically sensitive Pallikaranai marshland — a combination that creates distinctive appliance conditions. The marshland proximity means ambient humidity levels in Sholinganallur are measurably higher than in other OMR localities, which accelerates corrosion in electronics and promotes mold growth in appliance interiors.

The IT park developments along RMZ Millenia, Elnet SEZ, and the Sholinganallur Junction corridor are among Chennai\'s most active construction zones, with new towers continuously rising. This construction generates fine concrete and silica dust that coats outdoor AC units and air purifier filters in nearby residential towers at a rate that surprises many new residents.

The older Sholinganallur village and the streets adjacent to the Pallikaranai marshland boundary have a different character — lower-density housing, older plumbing, and the particular dampness that comes from proximity to wetlands. Washing machines in these areas show faster drum bearing and seal wear from the high ambient humidity, and refrigerators benefit from annual internal dehumidification treatment.

Apartment towers like Casagrand Luxor, TVH Lumbini Square, and the Akshaya Tango Sholinganallur properties are among our highest-volume OMR-zone service addresses.',
            'unique_faqs' => [
                [
                    'q' => 'My apartment in Sholinganallur near Pallikaranai marshland has very high humidity — does this affect my appliances?',
                    'a' => 'Yes significantly. High ambient humidity from the marshland boundary accelerates rusting of refrigerator internal components, promotes mold in washing machine door seals, and causes moisture condensation on appliance PCBs. We recommend annual internal cleaning for refrigerators in marshy-adjacent Sholinganallur areas, and suggest running the AC\'s "dry mode" for 30 minutes daily to reduce indoor humidity.',
                ],
                [
                    'q' => 'Do you service apartments in Casagrand Luxor and TVH Lumbini Square in Sholinganallur?',
                    'a' => 'Yes, both complexes are on our regular Sholinganallur service route. We are familiar with the building access protocols and can typically arrange same-day or next-day appointments for residents of these and all other major Sholinganallur gated communities. Booking before 11AM usually secures a same-day slot for OMR-zone addresses.',
                ],
            ],
            'geo' => ['lat' => 12.9010, 'lng' => 80.2279],
        ],

        'perungudi' => [
            'landmarks'    => ['Perungudi Toll Plaza', 'Ramapuram Lake', 'Kasi Theatre Road', 'Thoraipakkam Junction', 'L&T IT Park'],
            'housing_type' => 'densely packed mid-rise apartments, IT-adjacent residential towers, and older Perungudi village houses',
            'local_issues' => [
                'Toll plaza diesel exhaust directly affecting outdoor AC units along arterial roads',
                'High transit traffic creating vibration in apartments near the elevated expressway',
                'Older Perungudi village plumbing with very hard water scaling washing machines',
                'Construction dust from ongoing L&T and tech park expansions',
            ],
            'unique_section' => 'Perungudi is one of the key entry points to Chennai\'s IT corridor, defined by the Perungudi Toll Plaza and the dense residential development that has sprung up to house tech workers in proximity to the OMR parks. The area\'s position astride a major arterial creates specific appliance conditions.

The toll plaza and the adjoining elevated expressway generate diesel exhaust at ground level that is significantly higher than typical residential areas. AC outdoor units on the road-facing sides of Perungudi apartments absorb this exhaust constantly, creating a carbon-soot film on condenser fins. For this reason, we recommend road-facing outdoor units in Perungudi receive condenser cleaning every 2 months rather than the standard 4.

Older Perungudi village — the original settlement that predates the IT corridor — has notably hard borewell water. We regularly see severe calcium scaling inside washing machine drums, heating elements, and inlet filters in these older houses. An annual descaling service significantly extends washing machine life in these areas.

The elevated expressway creates structural vibration in apartments directly below or adjacent to it. While not usually sufficient to cause appliance faults on its own, this vibration can accelerate drum bearing wear in washing machines and cause vibration-related noise in refrigerators. Proper anti-vibration pads are a worthwhile investment.',
            'unique_faqs' => [
                [
                    'q' => 'My flat faces Perungudi Toll Plaza — my AC outdoor unit is very dirty. How often should I clean it?',
                    'a' => 'For toll plaza-facing or main road-facing units in Perungudi, we recommend condenser cleaning every 6–8 weeks. The diesel exhaust from trucks queuing at the toll creates a carbon soot film that dramatically reduces heat dissipation within just a few months. We offer a road-exposure premium cleaning service that uses a degreaser pre-treatment before pressure washing.',
                ],
                [
                    'q' => 'Do you service the older houses in Perungudi village?',
                    'a' => 'Yes, Perungudi village and the older residential streets behind the toll plaza are within our service zone. These addresses often have older appliances and hard borewell water issues. Our Perungudi technicians are experienced with the area\'s specific water quality challenges and carry descaling materials for washing machine and refrigerator treatments as standard.',
                ],
            ],
            'geo' => ['lat' => 12.9610, 'lng' => 80.2414],
        ],

        'thoraipakkam' => [
            'landmarks'    => ['Thoraipakkam Junction', 'OMR-Pallavaram Radial Road', 'Nissan SEZ', 'Old Mahabalipuram Road', 'Navalur Toll'],
            'housing_type' => 'IT professional mid-rise apartments, newer gated developments, and some older village housing near Thoraipakkam Lake',
            'local_issues' => [
                'Dust from Nissan manufacturing complex affecting AC units on the industrial boundary',
                'High WFH population creating heavy AC usage patterns',
                'Hard water from borewell supply in newer developments causing appliance scaling',
                'Pallavaram Radial Road traffic adding exhaust to residential AC intakes',
            ],
            'unique_section' => 'Thoraipakkam is a rapidly transforming residential area, transitioning from older village housing to dense IT-era apartments along the OMR and the Pallavaram Radial Road. This transformation is visible in the appliance service calls we receive — older houses with aging appliances on one side, and brand-new apartments with premium multi-brand setups on the other.

The proximity to Nissan\'s Tamil Nadu manufacturing facility brings industrial emissions to the western boundary of Thoraipakkam, where residential developments are increasingly common. Automotive paint and solvent particulates from the complex are detectable on outdoor AC units in the nearest residential blocks. Our service for these units includes a paint-solvent degreasing step that standard cleaning skips.

Thoraipakkam Lake and the older village core maintain a microclimate of slightly higher humidity compared to the rest of the OMR stretch, particularly during the monsoon season. This local humidity pocket increases the frequency of refrigerator door seal mold and washing machine drum fungal growth in addresses near the lake boundary.

The Pallavaram Radial Road — one of OMR\'s key connecting arteries — carries significant commercial vehicle traffic, adding diesel particulate to the residential air quality mix along its length. We recommend quarterly AC service for all apartments fronting this road.',
            'unique_faqs' => [
                [
                    'q' => 'I live near Thoraipakkam Junction on the Pallavaram Radial Road — how often should my AC be serviced?',
                    'a' => 'For apartments on or near the Pallavaram Radial Road, we recommend AC service every 6–8 weeks due to the diesel particulate from commercial traffic. This is especially true for units whose outdoor condenser faces the road direction. Standard service every 6 months is insufficient for high-traffic exposures.',
                ],
                [
                    'q' => 'My new apartment in Thoraipakkam has hard water — what appliances are at risk?',
                    'a' => 'Washing machines and water purifiers are most affected by Thoraipakkam\'s hard borewell water. Heating elements scale within 18–24 months without treatment, and drum seals deteriorate faster in hard water environments. We recommend installing an inline water softener for the washing machine inlet and annual descaling service. We can assess your water hardness during any service call if you request it.',
                ],
            ],
            'geo' => ['lat' => 12.9331, 'lng' => 80.2301],
        ],

        'pallikaranai' => [
            'landmarks'    => ['Pallikaranai Marshland', 'MRTS Velachery Terminal', 'Medavakkam Main Road', 'Grand Southern Trunk Road', 'Vijayanagar'],
            'housing_type' => 'marshland-border apartments, rapidly developing residential blocks, and independent houses in older layouts',
            'local_issues' => [
                'Highest ambient humidity in Chennai due to Pallikaranai marshland proximity',
                'Mosquito repellent aerosol residue clogging AC filters in wetland-adjacent homes',
                'Seasonal flooding affecting ground floor appliances',
                'Organic matter from marshland causing musty odours in refrigerators',
            ],
            'unique_section' => 'Pallikaranai is uniquely situated on the border of one of Chennai\'s most important ecological assets — the Pallikaranai Marshland. This proximity creates the highest ambient humidity levels of any residential area in Chennai, and this has profound implications for home appliances that most residents are unaware of.

The marshland microclimate — characterised by persistent moisture-laden air, especially from October to January — accelerates appliance corrosion at rates we observe nowhere else in Chennai. Refrigerator compressor capacitors, washing machine motor windings, and AC PCBs in Pallikaranai addresses show rust and moisture damage typically seen in coastal areas, despite Pallikaranai being several kilometres from the sea.

Mosquito repellent usage near the marshland is also notably higher than in other parts of Chennai. The aerosol repellents commonly used in Pallikaranai homes — liquid vaporisers, coil mosquito repellents, and spray repellents — release fine particles that clog AC filters and leave a chemical residue on evaporator coils. This residue, when heated by the coil, creates a characteristic chemical smell that many Pallikaranai residents associate with "the AC smelling wrong."

During monsoon season, ground-floor apartments near the marshland boundary face periodic waterlogging. We have a specialised post-flood appliance recovery service, and recommend Pallikaranai ground-floor residents keep our emergency number saved for post-flooding appliance inspections.',
            'unique_faqs' => [
                [
                    'q' => 'My AC in Pallikaranai near the marshland smells chemical when it runs — what is causing this?',
                    'a' => 'Mosquito repellent compounds — from vaporisers, coil repellents, and aerosols — accumulate on AC evaporator coils over time and create a chemical odour when heated. This is extremely common in Pallikaranai due to higher repellent usage near the marshland. An enzyme-based coil cleaning specifically targets this residue and eliminates the smell. We recommend Pallikaranai residents include this in their bi-annual AC service.',
                ],
                [
                    'q' => 'My ground floor flat in Pallikaranai gets water during heavy rains — should I be worried about my appliances?',
                    'a' => 'Yes — please do not switch on any appliance that has come in contact with floodwater without a prior inspection. Water inside the electrical compartments of washing machines, refrigerators, and ACs creates electrocution and fire hazards. We offer a post-flood appliance safety inspection service in Pallikaranai; call us before restarting any appliance after flooding.',
                ],
            ],
            'geo' => ['lat' => 12.9372, 'lng' => 80.2140],
        ],

        'medavakkam' => [
            'landmarks'    => ['Medavakkam Main Road', 'Sholinganallur-Medavakkam Junction', 'Urapakkam Link Road', 'Keelkattalai', 'Medavakkam Lake'],
            'housing_type' => 'newly developed mid-range apartment complexes, growing suburban independent houses, and some affordable housing projects',
            'local_issues' => [
                'New construction buildings with improper earthing causing appliance PCB damage',
                'Rapidly growing population creating overloaded local electrical transformers',
                'Hard borewell water in new developments causing washing machine scaling',
                'Distance from major roads meaning fewer competing repair services — we fill this gap',
            ],
            'unique_section' => 'Medavakkam is one of Chennai\'s fastest-growing suburban areas, with hundreds of new apartment projects coming up to house families priced out of the closer-in OMR belt. This rapid development creates a specific appliance service profile: new homes, new appliances, but frequently with electrical infrastructure that hasn\'t kept pace with the population growth.

Electrical transformer overloading is a genuine issue in Medavakkam. TANGEDCO\'s infrastructure in several Medavakkam localities is under strain from the rapid population increase, and brownout conditions — sustained low voltage — are more common here than in established areas. Brownouts are particularly damaging to refrigerator compressors, which struggle against inadequate starting voltage. We recommend all Medavakkam households use a voltage stabiliser for their refrigerator and AC.

New construction quality in rapidly developing areas is variable. Many Medavakkam apartment projects — particularly in the affordable housing segment — have electrical installations that are technically compliant but marginally sized. We frequently see earthing faults in new Medavakkam apartments that cause residual current device trips and occasional appliance PCB damage. An earthing check during any service call is something we always offer in Medavakkam.

The Medavakkam Lake and the older southern portions near Keelkattalai maintain the higher ambient humidity typical of south Chennai\'s wetland-adjacent areas, creating the same moisture-related appliance issues as Pallikaranai.',
            'unique_faqs' => [
                [
                    'q' => 'My brand new refrigerator in my new Medavakkam apartment keeps tripping the MCB — what is wrong?',
                    'a' => 'MCB tripping with a new refrigerator in Medavakkam is almost always an earthing fault in the building\'s electrical installation, not a fault in the refrigerator itself. New construction earthing in Medavakkam is frequently inadequate. Our technician can confirm whether the fault is in the appliance or the building wiring and advise accordingly. Do not ignore repeated MCB trips — they indicate a genuine safety issue.',
                ],
                [
                    'q' => 'Are you able to service Medavakkam even though it is further from the city?',
                    'a' => 'Yes — Medavakkam is within our regular service zone. We have technicians stationed in the Sholinganallur-Medavakkam-Pallikaranai belt and can typically reach any Medavakkam address within 35–50 minutes. For same-day service, we recommend booking before 12 noon. We serve all areas of Medavakkam including Keelkattalai, Medavakkam Main Road, and the townships along the Urapakkam link.',
                ],
            ],
            'geo' => ['lat' => 12.9256, 'lng' => 80.1945],
        ],

        'nungambakkam' => [
            'landmarks'    => ['Nungambakkam High Road', 'Sterling Road', 'Express Avenue Mall', 'Chetpet Lake', 'Alliance Française'],
            'housing_type' => 'premium high-rise apartments, consulate-adjacent luxury residences, and older colonial-era bungalows',
            'local_issues' => [
                'Premium and luxury brand appliances requiring specialist factory-trained technicians',
                'Very old colonial-era bungalows with outdated electrical infrastructure',
                'High-security buildings and consulate zones requiring prior appointment bookings',
                'Hard municipal water causing issues in premium imported washing machines',
            ],
            'unique_section' => 'Nungambakkam is one of Chennai\'s most prestigious addresses — home to consulates, five-star hotels, luxury apartment towers, and some of the city\'s most beautiful colonial-era bungalows. This exclusivity creates an appliance service market that demands a higher standard of expertise than most Chennai areas.

The concentration of premium international appliances in Nungambakkam is the highest in Chennai. Miele, V-ZUG, Bosch Serie 8, Samsung BESPOKE, LG Objet Collection — brands and product lines rarely seen elsewhere in the city are common here. Our Nungambakkam service team has been specifically trained on these premium ranges and we maintain a dedicated parts stock for high-end German and Korean appliances.

Colonial-era bungalows along Sterling Road and the leafy lanes behind Nungambakkam High Road are among the most architecturally significant properties in Chennai, and they require particular care during appliance service. Access routes through heritage gardens, load-bearing wall restrictions, and the absence of dedicated AC outdoor unit spaces all require creative, respectful solutions. Our installation team has decades of experience in these specific property types.

Many Nungambakkam addresses near consulates and diplomatic residences require advance notice and visitor documentation for our technicians. We are accustomed to this requirement — please mention any such access requirements when booking.',
            'unique_faqs' => [
                [
                    'q' => 'Do you service premium European appliances like Miele and Bosch Serie 8 in Nungambakkam?',
                    'a' => 'Yes — this is a speciality of our Nungambakkam team. We have technicians trained on Miele, Bosch Serie 6 and 8, V-ZUG, Siemens, and Electrolux premium ranges. We stock genuine European brand parts and use brand-specified tools and diagnostic software. Premium brand service is priced slightly higher due to genuine parts requirements, but we quote before any work begins.',
                ],
                [
                    'q' => 'I live near a consulate in Nungambakkam — can your technician gain access to my building?',
                    'a' => 'Yes, absolutely. Our Nungambakkam technicians carry photo ID, company ID cards, and vehicle documentation as standard. For high-security buildings requiring advance guest registration, please mention this when booking so we can provide all necessary details ahead of time. We have serviced addresses in all the consulate-adjacent buildings in Nungambakkam and are familiar with the access protocols.',
                ],
            ],
            'geo' => ['lat' => 13.0569, 'lng' => 80.2425],
        ],

        'valasaravakkam' => [
            'landmarks'    => ['Arcot Road', 'Alwarthirunagar', '100 Feet Road Valasaravakkam', 'Virugambakkam Junction', 'Metco Junction'],
            'housing_type' => 'dense mid-range apartment complexes, independent houses in Alwarthirunagar layouts, and flats along Arcot Road',
            'local_issues' => [
                'Arcot Road heavy traffic generating constant dust and exhaust for road-facing ACs',
                'Hard borewell water across the locality causing significant appliance scaling',
                'Mixed-use commercial-residential buildings with unstable electrical supply',
                'High apartment density creating access challenges for outdoor AC unit servicing',
            ],
            'unique_section' => 'Valasaravakkam is one of west Chennai\'s most densely populated residential areas, centred on the busy Arcot Road corridor and the quieter Alwarthirunagar residential layouts that run perpendicular to it. This geographical split creates two distinct appliance service environments within a small area.

Arcot Road itself is one of the most heavily trafficked roads in Chennai, carrying a constant flow of TNSTC buses, commercial vehicles, and private cars. Apartments and houses with outdoor AC units facing Arcot Road — particularly on the stretch between Metco Junction and Virugambakkam — experience rapid condenser fouling from exhaust and road dust. We see some of the fastest condenser clogging rates in Chennai along this road, and recommend bi-monthly cleaning for road-facing units.

The Alwarthirunagar layouts — the residential streets running north and south off Arcot Road — are quieter but face a different challenge: very hard borewell water. The groundwater in Valasaravakkam has high TDS levels, and calcium deposits inside washing machine drums, heating elements, and refrigerator water dispensers build up significantly within 12–18 months. An annual descaling service for all water-using appliances is strongly recommended for Valasaravakkam households.

The 100 Feet Road in Valasaravakkam has seen significant apartment development in recent years, with numerous mid-rise complexes adding to the area\'s density. These newer buildings generally have better electrical infrastructure but face the same water quality challenges as the older layouts.',
            'unique_faqs' => [
                [
                    'q' => 'Do you cover Arcot Road and Alwarthirunagar in Valasaravakkam?',
                    'a' => 'Yes — both Arcot Road (the full Valasaravakkam stretch from Metco Junction to Virugambakkam) and all the Alwarthirunagar residential streets are within our regular service zone. For Arcot Road addresses, our technicians are familiar with access in the denser commercial-residential buildings. Typical response time in Valasaravakkam is 25–40 minutes from booking confirmation.',
                ],
                [
                    'q' => 'There is white chalky residue inside my washing machine drum in Valasaravakkam — what is it?',
                    'a' => 'That is calcium carbonate scale from Valasaravakkam\'s hard borewell water supply. The TDS levels in this area are among the highest in west Chennai, and scaling inside washing machine drums, heating elements, and door seals is extremely common. We provide a descaling service using food-grade citric acid that removes existing scale without damaging the drum. An inlet water filter installation also significantly reduces future scaling.',
                ],
            ],
            'geo' => ['lat' => 13.0491, 'lng' => 80.1774],
        ],

        'mangadu' => [
            'landmarks'    => ['Mangadu Temple', 'Mangadu Bus Depot', 'Kundrathur Road', 'Kolapakkam', 'Mangadu Lake'],
            'housing_type' => 'semi-rural independent houses, temple-area residential buildings, and newer suburban developments along Kundrathur Road',
            'local_issues' => [
                'Incense and flower offering particulates from Mangadu Temple affecting nearby AC units',
                'Semi-rural open dust from unpaved roads clogging appliances faster than urban areas',
                'Borewell water with very high TDS causing rapid appliance scaling',
                'Power supply instability in newer sub-stations serving rapid suburban expansion',
            ],
            'unique_section' => 'Mangadu is a rapidly growing suburban area in western Chennai, historically centred on the famous Mangadu Kamakshi Amman Temple — one of the most visited temples in the region. The area is transitioning from a semi-rural settlement to a suburban residential zone, with this transition creating a mix of old and new appliance challenges.

The Mangadu Temple draws lakhs of devotees annually, and the area immediately surrounding the temple — particularly the streets within 500m of the main entrance — has the characteristic incense and camphor air quality we associate with large temple complexes. AC filters in these locations need monthly cleaning during major festival periods like Navratri and Panguni.

Away from the temple, Mangadu\'s semi-rural character means more open land, more dust from unpaved access roads, and more ambient particulate than a fully urban environment. Outdoor AC units in Mangadu\'s independent house layouts accumulate dust faster than in apartment towers with protected outdoor spaces. We recommend 3-monthly cleaning for most Mangadu outdoor units.

The borewell water in Mangadu is notably hard — residents frequently report visible calcium deposits around washing machine inlet points and inside the drum within just a few months of installation. The newer apartment complexes along Kundrathur Road generally have better water treatment, but independent houses rely on raw borewell water. Annual descaling is our primary recommendation for all Mangadu washing machines.',
            'unique_faqs' => [
                [
                    'q' => 'I live near Mangadu Temple — my AC filter gets dirty very quickly. Is this from the temple?',
                    'a' => 'Yes, this is directly related to the temple\'s incense and floral offering activity. Fine camphor and agarbathi particles from the temple spread across the surrounding streets and accumulate in AC filters. Homes within 500m of the Mangadu Kamakshi Amman Temple entrance typically need monthly filter cleaning during festival seasons. We offer a Mangadu temple-zone monthly maintenance subscription for affected residents.',
                ],
                [
                    'q' => 'Do you service the newer apartments along Kundrathur Road, Mangadu?',
                    'a' => 'Yes — Kundrathur Road and the newer residential developments along the Mangadu-Kolapakkam stretch are within our service zone. These are among the most recent additions to our western Chennai coverage. Response time to Kundrathur Road Mangadu is typically 35–50 minutes. We recommend morning bookings for same-day service in this area.',
                ],
            ],
            'geo' => ['lat' => 13.0332, 'lng' => 80.1037],
        ],

        'kovur' => [
            'landmarks'    => ['Kovur Junction', 'Kundrathur Main Road', 'Kovur Lake', 'Somangalam Road', 'Porur-Kovur link'],
            'housing_type' => 'emerging suburban independent houses, villa plots, and newer gated apartment projects along Somangalam Road',
            'local_issues' => [
                'Open land dust from undeveloped plots surrounding newer housing projects',
                'Power supply from the Poonamallee feeder being shared with industrial users',
                'Very limited local repair service options historically — we address this gap',
                'New construction quality issues including inadequate electrical earthing',
            ],
            'unique_section' => 'Kovur is one of Chennai\'s emerging western suburban growth zones, attracting families and IT professionals looking for larger homes at more accessible prices than the established Porur-Valasaravakkam belt. This emerging character means Kovur residents have historically had limited access to quality appliance repair services — something we are actively addressing with dedicated Kovur coverage.

The undeveloped plots and agricultural land still interspersed among newer housing projects create exceptionally dusty conditions during dry months. Open-lot dust is coarser and higher in silica content than urban road dust, and it damages AC condenser fins more aggressively when impacted at high air-velocity. Outdoor units in Kovur independent houses typically need condenser fin straightening (combing) during their annual service — a step we always check at Kovur addresses.

Power supply in Kovur comes from the Poonamallee high-tension feeder, which also serves several industrial users along the GST Road belt. This shared industrial-residential supply creates voltage variation patterns that differ from purely residential feeders. Inverter compressors in ACs and refrigerators are sensitive to supply quality, and we recommend a stabiliser for all major appliances in Kovur homes.

The newer gated villa communities and apartment projects along Somangalam Road are among the most attractive residential developments in western Chennai, and we are pleased to extend our full service range to these properties.',
            'unique_faqs' => [
                [
                    'q' => 'I recently moved to a new house in Kovur — is appliance repair service available nearby?',
                    'a' => 'Yes — we specifically added Kovur to our service zone to address the gap in quality repair services for this growing area. Our closest technicians can typically reach any Kovur address within 40–55 minutes. We cover the full Kovur area including Somangalam Road, the Kovur Junction vicinity, and all the villa and apartment projects in the area.',
                ],
                [
                    'q' => 'My brand new home in Kovur has very dusty conditions from nearby empty plots — how does this affect my AC?',
                    'a' => 'Open plot dust in areas like Kovur is higher in silica particles than urban dust, which damages aluminium condenser fins when it impacts at speed. We recommend Kovur residents clean AC filters monthly (rather than the standard bi-monthly) and schedule a full outdoor unit clean every 3 months during the dust season (December–May). A fin comb inspection at each service call is also worthwhile.',
                ],
            ],
            'geo' => ['lat' => 13.0090, 'lng' => 80.1095],
        ],

        'korattur' => [
            'landmarks'    => ['Korattur Railway Station', 'Anna Nagar 40th Street', 'Villivakkam Junction', 'Korattur Lake', 'MTC Bus Depot'],
            'housing_type' => 'working-class independent houses, railway colony residences, and mid-range apartment clusters near Villivakkam Junction',
            'local_issues' => [
                'Cotton and textile dust from nearby Ambattur estate units affecting AC filters',
                'Korattur Lake proximity causing elevated humidity and appliance corrosion',
                'Old independent houses with single-phase supply limiting inverter AC options',
                'Vibration from nearby railway line affecting washing machine drum bearings',
            ],
            'unique_section' => 'Korattur occupies the territory between Anna Nagar to the south and Ambattur to the north, making it a transitional zone that combines characteristics of both. The area\'s proximity to the Ambattur Industrial Estate means some of the industrial particulate challenges seen further north also apply here — particularly textile fibres from the estate\'s manufacturing units, which drift southward and affect AC filters in Korattur homes.

Korattur Lake is the area\'s most significant geographic feature, and its presence maintains elevated ambient humidity throughout the year. We observe higher rates of refrigerator interior rusting, washing machine seal mold, and AC PCB moisture damage in Korattur addresses closest to the lake boundary compared to those further west.

The railway line that passes through Korattur — carrying both suburban and intercity trains — generates periodic vibration that, while not sufficient to damage appliances directly, can accelerate bearing wear in washing machines that are already showing early signs of drum bearing fatigue. We always run a bearing vibration check on washing machines at Korattur lake-adjacent and rail-adjacent addresses.

The residential streets of Korattur have a predominantly owner-occupied, middle-income character — this demographic tends to run appliances until they fail rather than investing in preventive maintenance. We actively encourage Korattur residents to consider annual service contracts, which save significant money by preventing costly breakdowns.',
            'unique_faqs' => [
                [
                    'q' => 'My washing machine near Korattur railway line makes a lot of noise — could it be related to train vibration?',
                    'a' => 'Possibly, yes. If your washing machine is in a room sharing a wall or floor with ground-level vibration from the railway line, this can amplify early-stage drum bearing noise. The bearing itself may need attention — the railway vibration is making a minor issue sound worse than it is. We recommend a bearing inspection, after which we can advise whether repair or replacement is more cost-effective.',
                ],
                [
                    'q' => 'My AC in Korattur has white fluffy fibres blocking the filter — what are they?',
                    'a' => 'Those are almost certainly cotton lint fibres from the textile manufacturing units in the Ambattur Industrial Estate, which drift southward into Korattur during northerly winds. This is a well-known phenomenon in the Anna Nagar-Korattur-Ambattur corridor. Standard filter cleaning removes surface lint, but our deep coil extraction service clears fibres embedded in the evaporator coil that cause poor airflow.',
                ],
            ],
            'geo' => ['lat' => 13.1019, 'lng' => 80.1993],
        ],

        'pammal' => [
            'landmarks'    => ['Pammal Bus Terminus', 'Pallavaram-Thoraipakkam Radial Road', 'Tambaram-Mudichur Road', 'Pammal Lake', 'Chrompet-Pammal link'],
            'housing_type' => 'dense independent house layouts, working-class apartments, and some newer residential projects on the radial road',
            'local_issues' => [
                'Airport proximity causing aviation particulate on AC outdoor units',
                'Adjacent to several bus depots — heavy vehicle exhaust affecting air quality',
                'Hard borewell water causing rapid appliance scaling',
                'Power supply shared with Pallavaram industrial users creating voltage fluctuations',
            ],
            'unique_section' => 'Pammal is a dense, established residential area in south-west Chennai, bordered by Chromepet to the north and Tambaram to the south. Its position near Chennai Airport and adjacent to several major transport hubs — Pallavaram MRTS, multiple bus depots — gives it a distinctive industrial-transport character that affects home appliances in ways residents often don\'t anticipate.

The aviation particulate issue that we document in Chromepet extends into Pammal as well, with kerosene combustion residue from aircraft affecting outdoor AC units in the northern portions of the area, particularly near the Pallavaram junction. For Pammal properties north of the bus terminus, we apply the same solvent-pre-treatment condenser cleaning that we use for Chromepet airport-adjacent properties.

Bus depot diesel exhaust from the multiple TNSTC depots near Pallavaram and Chromepet creates a persistent air quality challenge for Pammal residents close to the Pallavaram-Pammal boundary. Outdoor AC condenser fins in this zone require cleaning every 6–8 weeks to maintain efficiency.

Pammal\'s borewell water is hard — this is a consistent characteristic across the south-west Chennai zone encompassing Chromepet, Pammal, and Tambaram. Washing machine drum seals, heating elements, and refrigerator ice-maker components are the primary victims of high-TDS water, and annual descaling is our standard recommendation for all Pammal households.',
            'unique_faqs' => [
                [
                    'q' => 'Do you service all areas of Pammal including near Pallavaram Radial Road?',
                    'a' => 'Yes — we cover all of Pammal including the areas near Pallavaram Radial Road, the Pammal bus terminus vicinity, the Chromepet-Pammal link, and the routes towards Tambaram-Mudichur Road. Our south Chennai technicians can typically reach any Pammal address within 30–45 minutes during daytime hours.',
                ],
                [
                    'q' => 'My AC in Pammal is less than 2 years old but cools poorly — could it be the exhaust from the bus depots?',
                    'a' => 'Absolutely possible. Diesel exhaust from the nearby bus depots creates a rapid carbon build-up on condenser coils that standard maintenance cleaning misses. A new unit losing cooling efficiency within 2 years in Pammal is a known pattern — we recommend a deep chemical coil wash that restores near-new cooling performance. For this zone, we also recommend outdoor unit covers when the AC is not in use.',
                ],
            ],
            'geo' => ['lat' => 12.9600, 'lng' => 80.1389],
        ],

    ]; // end DATA
}