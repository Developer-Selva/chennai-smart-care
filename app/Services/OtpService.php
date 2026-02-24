<?php

namespace App\Services;

use App\Models\Otp;

class OtpService
{
    private const OTP_EXPIRY_MINUTES = 10;
    private const MAX_ATTEMPTS      = 5;
    private const COOLDOWN_MINUTES  = 1;

    public function generate(string $phone, string $purpose = 'login'): string
    {
        // Rate limiting: prevent spamming OTPs
        $recentOtps = Otp::where('phone', $phone)
            ->where('created_at', '>=', now()->subMinutes(self::COOLDOWN_MINUTES))
            ->count();

        if ($recentOtps >= self::MAX_ATTEMPTS) {
            throw new \Exception('Too many OTP requests. Please wait a moment.');
        }

        // Invalidate previous OTPs for this phone+purpose
        Otp::where('phone', $phone)
            ->where('purpose', $purpose)
            ->where('is_used', false)
            ->update(['is_used' => true]);

        $code = str_pad(random_int(100000, 999999), 6, '0', STR_PAD_LEFT);

        Otp::create([
            'phone'      => $phone,
            'code'       => $code,
            'purpose'    => $purpose,
            'expires_at' => now()->addMinutes(self::OTP_EXPIRY_MINUTES),
        ]);

        return $code;
    }

    public function verify(string $phone, string $code, string $purpose = 'login'): bool
    {
        $otp = Otp::where('phone', $phone)
            ->where('code', $code)
            ->where('purpose', $purpose)
            ->where('is_used', false)
            ->latest()
            ->first();

        if (! $otp || ! $otp->isValid()) {
            return false;
        }

        $otp->update(['is_used' => true]);
        return true;
    }
}