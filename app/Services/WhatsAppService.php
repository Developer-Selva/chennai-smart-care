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

        $this->sendText($this->adminPhone,
            "🔔 *New Booking!*\n\n" .
            "📋 *#:* {$booking->booking_number}\n" .
            "👤 *Customer:* {$booking->customer_name}\n" .
            "📞 *Phone:* {$booking->customer_phone}\n" .
            "🔧 *Services:* {$serviceList}\n" .
            "📅 *Date:* {$date} | ⏰ {$booking->time_slot}\n" .
            "📍 *Address:* {$booking->address}\n" .
            "💰 *Amount:* ₹" . ($booking->total_amount ?? '—') . "\n\n" .
            "👉 " . url("/admin/bookings/{$booking->id}")
        );
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

    public function sendAmcUnlocked($user, $amc): void
    {
        $phone = $this->normalizePhone($user->phone ?? '');
        if (! $phone) return;

        $this->sendText($phone,
            "🎉 *Congratulations! Free AMC Unlocked!*

" .
            "Hi {$user->name}, you've unlocked a *FREE Annual Maintenance Contract* " .
            "by spending ₹5,000+ with Chennai Smart Care this year!

" .
            "✅ *AMC #:* {$amc->amc_number}
" .
            "📅 *Valid till:* " . \Carbon\Carbon::parse($amc->expires_at)->format('d M Y') . "

" .
            "*Your AMC Benefits:*
" .
            "🔧 2 free service calls/year
" .
            "💸 15% off on all repairs
" .
            "⚡ Priority booking — skip the queue

" .
            "View your AMC card in your dashboard:
" .
            url('/user/dashboard') . "

" .
            "_Thank you for being a valued customer!_"
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
        return $digits;
    }
}