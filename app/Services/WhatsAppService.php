<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Consultation;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * WhatsApp Business Cloud API Service
 *
 * Required .env keys:
 *   WHATSAPP_TOKEN                = permanent system user token
 *   WHATSAPP_PHONE_ID             = phone number ID from Meta dashboard
 *   WHATSAPP_ADMIN_PHONE          = admin WhatsApp e.g. 919444900470
 *   WHATSAPP_TEMPLATES_APPROVED   = true  (set after Meta approves templates)
 *
 * ── HOW IT WORKS ─────────────────────────────────────────────
 * While WHATSAPP_TEMPLATES_APPROVED=false (default):
 *   → Sends free-form text (works only within 24h after customer
 *     messages your number, OR to numbers on your sandbox allow-list)
 *
 * After WHATSAPP_TEMPLATES_APPROVED=true:
 *   → Sends approved templates (works to ANY number, any time)
 *
 * ── TEMPLATES TO CREATE IN META ─────────────────────────────
 * business.facebook.com → WhatsApp Manager → Message Templates
 * Category: UTILITY, Language: English
 *
 * booking_received:
 *   "Hi {{1}}, your booking #{{2}} with Chennai Smart Care has been received.
 *    Services: {{3}} | Date: {{4}} | Slot: {{5}}
 *    We will confirm shortly. Queries? Call +91 94449 00470"
 *
 * booking_confirmed:
 *   "Hi {{1}}, your booking #{{2}} is confirmed!
 *    Date: {{3}} | Slot: {{4}}
 *    Our technician will arrive on time. Keep the appliance accessible."
 *
 * technician_assigned:
 *   "Hi {{1}}, a technician has been assigned to booking #{{2}}.
 *    Technician: {{3}} | Phone: {{4}}
 *    Visit: {{5}} | Slot: {{6}}
 *    The technician will call before arriving."
 *
 * booking_completed:
 *   "Hi {{1}}, your service #{{2}} is completed!
 *    Amount: {{3}} | Warranty: 6 months on all repairs
 *    Please rate us: https://g.page/r/chennaismartcare
 *    Thank you for choosing Chennai Smart Care!"
 *
 * booking_rescheduled:
 *   "Hi {{1}}, your booking #{{2}} has been rescheduled.
 *    New Date: {{3}} | New Slot: {{4}}
 *    Didn't request this? Call +91 94449 00470"
 *
 * booking_cancelled:
 *   "Hi {{1}}, your booking #{{2}} has been cancelled.
 *    Reason: {{3}}
 *    To rebook visit chennaismartcare.com or call +91 94449 00470"
 *
 * consultation_received:
 *   "Hi {{1}}, we received your free consultation request for {{2}}.
 *    Our expert will call you within 2 hours (Mon-Sun 9AM-9PM).
 *    Urgent? Call +91 94449 00470"
 *
 * admin_booking_alert:
 *   "🔔 *New Booking Alert!*
 *    Booking #: {{1}}
 *    Customer: {{2}}
 *    Phone: {{3}}
 *    Services: {{4}}
 *    Date: {{5}} | Slot: {{6}}
 *    Amount: ₹{{7}}
 *    View: {{8}}"
 * ─────────────────────────────────────────────────────────────
 */
class WhatsAppService
{
    private string $apiUrl;
    private string $token;
    private string $phoneId;
    private string $adminPhone;
    private bool   $templatesApproved;

    public function __construct()
    {
        $this->token             = config('services.whatsapp.token', '');
        $this->phoneId           = config('services.whatsapp.phone_id', '');
        $this->adminPhone        = config('services.whatsapp.admin_phone', '919444900470');
        $this->templatesApproved = (bool) config('services.whatsapp.templates_approved', false);
        $this->apiUrl            = "https://graph.facebook.com/v19.0/{$this->phoneId}/messages";
    }

    // ═══════════════════════════════════════════════════════════
    //  BOOKING NOTIFICATIONS
    // ═══════════════════════════════════════════════════════════

    public function sendBookingCreated(Booking $booking): void
    {
        $booking->loadMissing(['items.service', 'user']);
        $customerPhone = $this->normalizePhone($booking->guest_phone ?? $booking->user?->phone ?? '');
        $serviceList   = $booking->items->map(fn ($i) => $i->service_name)->implode(', ');
        $date          = \Carbon\Carbon::parse($booking->booking_date)->format('D, d M Y');

        if ($customerPhone) {
            if ($this->templatesApproved) {
                $this->sendTemplate($customerPhone, 'booking_received', [
                    $booking->customer_name, $booking->booking_number,
                    $serviceList, $date, $booking->time_slot,
                ]);
            } else {
                $this->sendText($customerPhone,
                    "✅ *Booking Received!*\n\n" .
                    "Hi {$booking->customer_name}, your booking with *Chennai Smart Care* has been received.\n\n" .
                    "📋 *Booking #:* {$booking->booking_number}\n" .
                    "🔧 *Services:* {$serviceList}\n" .
                    "📅 *Date:* {$date}\n" .
                    "⏰ *Slot:* {$booking->time_slot}\n" .
                    "📍 *Address:* {$booking->address}\n\n" .
                    "Our team will confirm shortly.\n" .
                    "Queries? Call: *+91 94449 00470*\n\n" .
                    "_Chennai Smart Care — Expert Appliance Repair_"
                );
            }
        }

        // Send admin notification
        $this->sendAdminBookingAlert($booking);
    }

    /**
     * Send high-priority admin WhatsApp alert
     */
    private function sendAdminBookingAlert(Booking $booking): void
    {
        $serviceList = $booking->items->map(fn ($i) => $i->service_name)->implode(', ');
        $date = \Carbon\Carbon::parse($booking->booking_date)->format('D, d M Y');
        $amount = $booking->total_amount ?? '—';
        $adminUrl = url("/admin/bookings/{$booking->id}");
        
        // Determine priority
        $priorityEmoji = $this->getBookingPriority($booking);
        $priorityTag = $this->getPriorityTag($booking);

        if ($this->templatesApproved && $this->hasTemplate('admin_booking_alert')) {
            $this->sendTemplate($this->adminPhone, 'admin_booking_alert', [
                $booking->booking_number,
                $booking->customer_name,
                $booking->guest_phone ?? $booking->user?->phone ?? 'N/A',
                $serviceList,
                $date,
                $booking->time_slot,
                $amount,
                $adminUrl,
            ]);
        } else {
            // Enhanced admin message with priority indicators
            $message = $this->buildAdminAlertMessage($booking, $priorityEmoji, $priorityTag);
            $this->sendText($this->adminPhone, $message);
        }

        // For very high priority bookings, send a second alert with quick actions
        if ($this->isHighPriority($booking)) {
            $this->sendHighPriorityFollowUp($booking);
        }
    }

    /**
     * Build admin alert message
     */
    private function buildAdminAlertMessage(Booking $booking, string $priorityEmoji, string $priorityTag): string
    {
        $serviceList = $booking->items->map(fn ($i) => $i->service_name)->implode(', ');
        $date = \Carbon\Carbon::parse($booking->booking_date)->format('D, d M Y');
        $amount = $booking->total_amount ?? '—';
        $adminUrl = url("/admin/bookings/{$booking->id}");
        
        // Check if urgent (same-day booking)
        $urgentTag = $booking->booking_date->isToday() ? " 🔴 URGENT - SAME DAY" : "";
        
        // Check if customer is registered
        $customerType = $booking->user_id ? "👤 Registered" : "🆕 Guest";

        return "{$priorityEmoji} *NEW BOOKING ALERT!* {$priorityTag}{$urgentTag}\n\n" .
               "📋 *#:* {$booking->booking_number}\n" .
               "👤 *Customer:* {$booking->customer_name}\n" .
               "📞 *Phone:* `{$booking->guest_phone}`\n" .
               "🔧 *Services:* {$serviceList}\n" .
               "📅 *Date:* {$date}\n" .
               "⏰ *Slot:* {$booking->time_slot}\n" .
               "📍 *Area:* {$booking->area}\n" .
               "💰 *Amount:* ₹{$amount}\n" .
               "🏷️ *Type:* {$customerType}\n\n" .
               "⚡ *Quick Actions:*\n" .
               "• 👨‍🔧 Assign: {$adminUrl}/assign\n" .
               "• 📞 Call: `{$booking->guest_phone}`\n" .
               "• 💬 WhatsApp: https://wa.me/{$booking->guest_phone}\n\n" .
               "🔗 *View Full Details:*\n" .
               "{$adminUrl}\n\n" .
               "⏱️ *Booked:* {$booking->created_at->format('h:i A')}";
    }

    /**
     * Send follow-up for high-priority bookings
     */
    private function sendHighPriorityFollowUp(Booking $booking): void
    {
        $message = "⚠️ *HIGH PRIORITY FOLLOW-UP*\n\n" .
                   "Booking *#{$booking->booking_number}* requires immediate attention.\n\n" .
                   "*Suggested Actions:*\n" .
                   "1️⃣ Check technician availability\n" .
                   "2️⃣ Contact customer immediately\n" .
                   "3️⃣ Prepare required parts\n\n" .
                   "Click to assign technician:\n" .
                   url("/admin/bookings/{$booking->id}/assign");

        $this->sendText($this->adminPhone, $message);
    }

    /**
     * Get booking priority emoji
     */
    private function getBookingPriority(Booking $booking): string
    {
        if ($booking->booking_date->isToday()) {
            return "🔴"; // Urgent - same day
        }
        
        if ($booking->total_amount > 5000) {
            return "💰"; // High value
        }
        
        if ($booking->items->contains(fn($item) => str_contains($item->service_name, 'AC'))) {
            return "❄️"; // AC repair (often urgent in Chennai)
        }
        
        return "🟢"; // Normal
    }

    /**
     * Get priority tag text
     */
    private function getPriorityTag(Booking $booking): string
    {
        $tags = [];
        
        if ($booking->booking_date->isToday()) {
            $tags[] = "SAME DAY";
        }
        
        if ($booking->total_amount > 5000) {
            $tags[] = "HIGH VALUE";
        }
        
        if ($booking->user_id && $booking->user->bookings()->count() > 5) {
            $tags[] = "VIP";
        }
        
        if ($booking->items->contains(fn($item) => str_contains($item->service_name, 'AC'))) {
            $tags[] = "AC";
        }
        
        return empty($tags) ? "NEW" : implode(" • ", $tags);
    }

    /**
     * Check if booking is high priority
     */
    private function isHighPriority(Booking $booking): bool
    {
        // Same-day booking
        if ($booking->booking_date->isToday()) {
            return true;
        }

        // High-value booking
        if ($booking->total_amount > 5000) {
            return true;
        }

        // VIP customer (repeat customer)
        if ($booking->user_id && $booking->user->bookings()->count() > 5) {
            return true;
        }

        // AC repair in Chennai (urgent due to heat)
        if ($booking->items->contains(fn($item) => str_contains($item->service_name, 'AC'))) {
            return true;
        }

        // Urgent note indicators
        $urgentKeywords = ['urgent', 'emergency', 'leak', 'not working', 'broken', 'hot'];
        if ($booking->notes && collect($urgentKeywords)->contains(fn($keyword) => 
            stripos($booking->notes, $keyword) !== false
        )) {
            return true;
        }

        return false;
    }

    /**
     * Check if a specific template exists (you'd need to implement this based on your template management)
     */
    private function hasTemplate(string $templateName): bool
    {
        // This should check against your approved templates list
        // For now, return false to use text fallback
        return false;
    }

    public function sendBookingConfirmed(Booking $booking): void
    {
        $customerPhone = $this->normalizePhone($booking->guest_phone ?? $booking->user?->phone ?? '');
        $date          = \Carbon\Carbon::parse($booking->booking_date)->format('D, d M Y');

        if ($customerPhone) {
            if ($this->templatesApproved) {
                $this->sendTemplate($customerPhone, 'booking_confirmed', [
                    $booking->customer_name, $booking->booking_number, $date, $booking->time_slot,
                ]);
            } else {
                $this->sendText($customerPhone,
                    "✅ *Booking Confirmed!*\n\n" .
                    "Hi {$booking->customer_name}, booking *#{$booking->booking_number}* is *confirmed*.\n\n" .
                    "📅 *Date:* {$date}\n" .
                    "⏰ *Slot:* {$booking->time_slot}\n\n" .
                    "Our technician will arrive on time. Keep the appliance accessible.\n" .
                    "Questions? Call *+91 94449 00470*"
                );
            }
        }

        // Brief admin update
        $this->sendText($this->adminPhone,
            "✅ *Booking Confirmed*\n" .
            "#{$booking->booking_number} | {$booking->customer_name}\n" .
            "{$date} | {$booking->time_slot}"
        );
    }

    public function sendTechnicianAssigned(Booking $booking): void
    {
        $booking->loadMissing('technician');
        $customerPhone = $this->normalizePhone($booking->guest_phone ?? $booking->user?->phone ?? '');
        $tech          = $booking->technician;
        $date          = \Carbon\Carbon::parse($booking->booking_date)->format('D, d M Y');

        if ($customerPhone && $tech) {
            if ($this->templatesApproved) {
                $this->sendTemplate($customerPhone, 'technician_assigned', [
                    $booking->customer_name, $booking->booking_number,
                    $tech->name, $tech->phone, $date, $booking->time_slot,
                ]);
            } else {
                $this->sendText($customerPhone,
                    "👨‍🔧 *Technician Assigned!*\n\n" .
                    "Hi {$booking->customer_name}, a technician is assigned to *#{$booking->booking_number}*.\n\n" .
                    "👤 *Technician:* {$tech->name}\n" .
                    "📞 *Contact:* {$tech->phone}\n" .
                    "📅 *Date:* {$date} | ⏰ {$booking->time_slot}\n\n" .
                    "The technician will call before arriving.\n" .
                    "Support: *+91 94449 00470*"
                );
            }
        }

        if ($tech) {
            $this->sendText($this->adminPhone,
                "👨‍🔧 *Technician Assigned*\n" .
                "Booking *#{$booking->booking_number}* → {$tech->name}\n" .
                "Customer: {$booking->customer_name} | {$booking->customer_phone}\n" .
                "Date: {$date} | {$booking->time_slot}"
            );
        }
    }

    public function sendBookingCompleted(Booking $booking): void
    {
        $customerPhone = $this->normalizePhone($booking->guest_phone ?? $booking->user?->phone ?? '');
        $amount        = $booking->final_amount ?? $booking->total_amount ?? '—';

        if ($customerPhone) {
            if ($this->templatesApproved) {
                $this->sendTemplate($customerPhone, 'booking_completed', [
                    $booking->customer_name, $booking->booking_number, "₹{$amount}",
                ]);
            } else {
                $this->sendText($customerPhone,
                    "🎉 *Service Completed!*\n\n" .
                    "Hi {$booking->customer_name}, service *#{$booking->booking_number}* is done!\n\n" .
                    "💰 *Amount:* ₹{$amount}\n" .
                    "🛡️ *Warranty:* 6 months on all repairs\n\n" .
                    "Please rate us on Google:\n" .
                    "⭐ https://g.page/r/chennaismartcare\n\n" .
                    "_Thank you for choosing Chennai Smart Care!_"
                );
            }
        }

        $this->sendText($this->adminPhone,
            "🎉 *Booking Completed*\n" .
            "#{$booking->booking_number} | {$booking->customer_name}\n" .
            "Amount: ₹{$amount}"
        );
    }

    public function sendBookingRescheduled(Booking $booking): void
    {
        $customerPhone = $this->normalizePhone($booking->guest_phone ?? $booking->user?->phone ?? '');
        $date          = \Carbon\Carbon::parse($booking->booking_date)->format('D, d M Y');

        if ($customerPhone) {
            if ($this->templatesApproved) {
                $this->sendTemplate($customerPhone, 'booking_rescheduled', [
                    $booking->customer_name, $booking->booking_number, $date, $booking->time_slot,
                ]);
            } else {
                $this->sendText($customerPhone,
                    "📅 *Booking Rescheduled*\n\n" .
                    "Hi {$booking->customer_name}, booking *#{$booking->booking_number}* rescheduled.\n\n" .
                    "📅 *New Date:* {$date}\n" .
                    "⏰ *New Slot:* {$booking->time_slot}\n\n" .
                    "Didn't request this? Call: *+91 94449 00470*"
                );
            }
        }

        $this->sendText($this->adminPhone,
            "📅 *Booking Rescheduled*\n" .
            "Booking *#{$booking->booking_number}*\n" .
            "Customer: {$booking->customer_name}\n" .
            "New: {$date} | {$booking->time_slot}"
        );
    }

    public function sendBookingCancelled(Booking $booking, string $reason = ''): void
    {
        $customerPhone = $this->normalizePhone($booking->guest_phone ?? $booking->user?->phone ?? '');
        $reason        = $reason ?: 'No reason provided';

        if ($customerPhone) {
            if ($this->templatesApproved) {
                $this->sendTemplate($customerPhone, 'booking_cancelled', [
                    $booking->customer_name, $booking->booking_number, $reason,
                ]);
            } else {
                $this->sendText($customerPhone,
                    "❌ *Booking Cancelled*\n\n" .
                    "Hi {$booking->customer_name}, booking *#{$booking->booking_number}* cancelled.\n\n" .
                    "📝 *Reason:* {$reason}\n\n" .
                    "To rebook: chennaismartcare.com or call *+91 94449 00470*"
                );
            }
        }

        $this->sendText($this->adminPhone,
            "❌ *Booking Cancelled*\n" .
            "#{$booking->booking_number} | {$booking->customer_name}\n" .
            "Reason: {$reason}"
        );
    }

    // ═══════════════════════════════════════════════════════════
    //  CONSULTATION NOTIFICATIONS
    // ═══════════════════════════════════════════════════════════

    public function sendConsultationCreated(Consultation $consultation): void
    {
        $customerPhone = $this->normalizePhone($consultation->phone ?? '');
        $appliance     = $consultation->appliance_type ?? $consultation->service_interest ?? 'your appliance';
        $issue         = $consultation->issue ?? $consultation->message ?? '—';

        if ($customerPhone) {
            if ($this->templatesApproved) {
                $this->sendTemplate($customerPhone, 'consultation_received', [
                    $consultation->name, $appliance,
                ]);
            } else {
                $this->sendText($customerPhone,
                    "📞 *Consultation Request Received!*\n\n" .
                    "Hi {$consultation->name}, we received your free consultation request.\n\n" .
                    "🔧 *Appliance:* {$appliance}\n" .
                    "📋 *Issue:* {$issue}\n\n" .
                    "Our expert will call within *2 hours* (Mon–Sun, 9AM–9PM).\n\n" .
                    "Urgent? Call: *+91 94449 00470*\n\n" .
                    "_Chennai Smart Care — Expert Appliance Repair_"
                );
            }
        }

        $this->sendText($this->adminPhone,
            "📞 *New Consultation!*\n\n" .
            "👤 *Name:* {$consultation->name}\n" .
            "📞 *Phone:* {$consultation->phone}\n" .
            "🔧 *Appliance:* {$appliance}\n" .
            "📋 *Issue:* {$issue}\n" .
            "📍 *Area:* " . ($consultation->area ?? 'Not specified') . "\n\n" .
            "👉 " . url("/admin/consultations/{$consultation->id}")
        );
    }

    // ═══════════════════════════════════════════════════════════
    //  ADDITIONAL NOTIFICATIONS
    // ═══════════════════════════════════════════════════════════

    public function sendInvoice($invoice): void
    {
        $invoice->loadMissing('items');
        $phone = $this->normalizePhone($invoice->customer_phone ?? '');
        if (! $phone) return;

        $itemLines = $invoice->items->map(
            fn ($i) => "  • {$i->description} × {$i->quantity} = ₹{$i->total}"
        )->implode("\n");

        $discountLine = $invoice->discount_amount > 0
            ? "\n💸 *Discount:* -₹{$invoice->discount_amount}" : '';

        $taxLine = $invoice->tax_amount > 0
            ? "\n🧾 *GST ({$invoice->tax_percent}%):* ₹{$invoice->tax_amount}" : '';

        $paymentLine = $invoice->payment_method !== 'pending'
            ? "\n💳 *Payment:* " . ucfirst($invoice->payment_method) .
              ($invoice->payment_reference ? " ({$invoice->payment_reference})" : '')
            : "\n💳 *Payment:* Pending";

        $statusBadge = $invoice->status === 'paid' ? '✅ PAID' : '📄 Invoice';

        $this->sendText($phone,
            "{$statusBadge} *{$invoice->invoice_number}*\n\n" .
            "Hi {$invoice->customer_name}, here is your invoice from *Chennai Smart Care*.\n\n" .
            "*Services:*\n{$itemLines}\n" .
            "──────────────────\n" .
            "💰 *Subtotal:* ₹{$invoice->subtotal}" .
            $discountLine . $taxLine . "\n" .
            "🏷️ *Total: ₹{$invoice->total}*" .
            $paymentLine . "\n\n" .
            "📲 View & download your invoice:\n" .
            url("/user/invoices/{$invoice->id}") . "\n\n" .
            "🛡️ All repairs carry a *6-month warranty*.\n" .
            "Queries? Call *+91 94449 00470*\n\n" .
            "_Chennai Smart Care — Expert Appliance Repair_"
        );

        // Admin copy
        $this->sendText($this->adminPhone,
            "📄 *Invoice Sent*\n" .
            "{$invoice->invoice_number} → {$invoice->customer_name}\n" .
            "Total: ₹{$invoice->total} | Status: {$invoice->status}"
        );
    }

    public function sendAmcUnlocked($user, $amc): void
    {
        $phone = $this->normalizePhone($user->phone ?? '');
        if (! $phone) return;

        $this->sendText($phone,
            "🎉 *Congratulations! Free AMC Unlocked!*\n\n" .
            "Hi {$user->name}, you've unlocked a *FREE Annual Maintenance Contract* " .
            "by spending ₹5,000+ with Chennai Smart Care this year!\n\n" .
            "✅ *AMC #:* {$amc->amc_number}\n" .
            "📅 *Valid till:* " . \Carbon\Carbon::parse($amc->expires_at)->format('d M Y') . "\n\n" .
            "*Your AMC Benefits:*\n" .
            "🔧 2 free service calls/year\n" .
            "💸 15% off on all repairs\n" .
            "⚡ Priority booking — skip the queue\n\n" .
            "View your AMC card in your dashboard:\n" .
            url('/user/dashboard') . "\n\n" .
            "_Thank you for being a valued customer!_"
        );

        $this->sendText($this->adminPhone,
            "🎁 *AMC Unlocked*\n" .
            "User: {$user->name} | {$user->phone}\n" .
            "AMC #: {$amc->amc_number}\n" .
            "Expires: {$amc->expires_at->format('d M Y')}"
        );
    }

    // ═══════════════════════════════════════════════════════════
    //  CORE — FREE-FORM TEXT
    // ═══════════════════════════════════════════════════════════

    public function sendText(string $phone, string $message): bool
    {
        if (! $this->token || ! $this->phoneId) {
            Log::warning('WhatsApp: WHATSAPP_TOKEN or WHATSAPP_PHONE_ID not set in .env');
            return false;
        }

        $phone = $this->normalizePhone($phone);
        if (! $phone) {
            Log::warning('WhatsApp: invalid phone', compact('phone'));
            return false;
        }

        try {
            $response = Http::withToken($this->token)
                ->timeout(10)
                ->post($this->apiUrl, [
                    'messaging_product' => 'whatsapp',
                    'recipient_type'    => 'individual',
                    'to'                => $phone,
                    'type'              => 'text',
                    'text'              => ['preview_url' => false, 'body' => $message],
                ]);

            if ($response->failed()) {
                Log::error('WhatsApp send failed', [
                    'phone'  => $phone,
                    'status' => $response->status(),
                    'body'   => $response->json(),
                ]);
                return false;
            }

            Log::info('WhatsApp sent', ['phone' => $phone, 'id' => $response->json('messages.0.id')]);
            return true;

        } catch (\Throwable $e) {
            Log::error('WhatsApp exception', ['phone' => $phone, 'error' => $e->getMessage()]);
            return false;
        }
    }

    // ═══════════════════════════════════════════════════════════
    //  CORE — APPROVED TEMPLATE
    // ═══════════════════════════════════════════════════════════

    public function sendTemplate(string $phone, string $templateName, array $params = [], string $language = 'en'): bool
    {
        if (! $this->token || ! $this->phoneId) {
            Log::warning('WhatsApp: WHATSAPP_TOKEN or WHATSAPP_PHONE_ID not set in .env');
            return false;
        }

        $phone = $this->normalizePhone($phone);
        if (! $phone) {
            Log::warning('WhatsApp: invalid phone for template', compact('phone', 'templateName'));
            return false;
        }

        $components = [];
        if (! empty($params)) {
            $components[] = [
                'type'       => 'body',
                'parameters' => array_map(
                    fn ($v) => ['type' => 'text', 'text' => (string) $v],
                    $params
                ),
            ];
        }

        try {
            $response = Http::withToken($this->token)
                ->timeout(10)
                ->post($this->apiUrl, [
                    'messaging_product' => 'whatsapp',
                    'recipient_type'    => 'individual',
                    'to'                => $phone,
                    'type'              => 'template',
                    'template'          => [
                        'name'       => $templateName,
                        'language'   => ['code' => $language],
                        'components' => $components,
                    ],
                ]);

            if ($response->failed()) {
                Log::error('WhatsApp template failed', [
                    'phone'    => $phone,
                    'template' => $templateName,
                    'status'   => $response->status(),
                    'body'     => $response->json(),
                ]);
                return false;
            }

            Log::info('WhatsApp template sent', [
                'phone'    => $phone,
                'template' => $templateName,
                'id'       => $response->json('messages.0.id'),
            ]);
            return true;

        } catch (\Throwable $e) {
            Log::error('WhatsApp template exception', [
                'phone'    => $phone,
                'template' => $templateName,
                'error'    => $e->getMessage(),
            ]);
            return false;
        }
    }

    // ═══════════════════════════════════════════════════════════
    //  HELPERS
    // ═══════════════════════════════════════════════════════════

    private function normalizePhone(string $phone): string
    {
        $digits = preg_replace('/\D/', '', $phone);
        if (! $digits) return '';
        if (strlen($digits) === 12 && str_starts_with($digits, '91')) return $digits;
        if (strlen($digits) === 10) return '91' . $digits;
        if (strlen($digits) === 11 && str_starts_with($digits, '0')) return '91' . substr($digits, 1);
        return $digits;
    }
}