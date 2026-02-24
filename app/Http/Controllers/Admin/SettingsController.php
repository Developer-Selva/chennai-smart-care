<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Artisan;
use Inertia\Inertia;
use Inertia\Response;

class SettingsController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Settings/Index', [
            'settings' => [
                'app_name'         => config('app.name'),
                'support_phone'    => config('app.support_phone'),
                'whatsapp_number'  => config('app.whatsapp_number'),
                'booking_radius'   => config('booking.radius_km'),
                'slot_duration'    => config('booking.slot_duration'),
                'max_per_slot'     => config('booking.max_per_slot'),
                'advance_days'     => config('booking.advance_days'),
                'gtm_id'           => config('analytics.gtm_id'),
                'ga4_id'           => config('analytics.ga4_measurement_id'),
            ],
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'support_phone'   => 'required|string|max:20',
            'whatsapp_number' => 'required|string|max:20',
            'booking_radius'  => 'required|integer|min:1|max:50',
            'max_per_slot'    => 'required|integer|min:1|max:20',
            'advance_days'    => 'required|integer|min:1|max:90',
            'gtm_id'          => 'nullable|string|max:20',
            'ga4_id'          => 'nullable|string|max:30',
        ]);

        // Persist to .env via artisan config:set (or your preferred method)
        // For simplicity, writing to .env directly:
        $this->setEnvValue('APP_SUPPORT_PHONE',   $request->support_phone);
        $this->setEnvValue('WHATSAPP_NUMBER',      $request->whatsapp_number);
        $this->setEnvValue('BOOKING_RADIUS_KM',    $request->booking_radius);
        $this->setEnvValue('BOOKING_MAX_PER_SLOT', $request->max_per_slot);
        $this->setEnvValue('BOOKING_ADVANCE_DAYS', $request->advance_days);
        $this->setEnvValue('GTM_ID',               $request->gtm_id ?? '');
        $this->setEnvValue('GA4_MEASUREMENT_ID',   $request->ga4_id ?? '');

        Artisan::call('config:clear');

        return back()->with('success', 'Settings saved.');
    }

    private function setEnvValue(string $key, mixed $value): void
    {
        $path    = base_path('.env');
        $content = file_get_contents($path);
        $value   = (string) $value;

        if (str_contains($content, "{$key}=")) {
            $content = preg_replace("/^{$key}=.*/m", "{$key}={$value}", $content);
        } else {
            $content .= "\n{$key}={$value}";
        }

        file_put_contents($path, $content);
    }
}