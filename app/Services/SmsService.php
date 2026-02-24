<?php

namespace App\Services;

// -------------------------------------------------------
// app/Services/SmsService.php
// -------------------------------------------------------
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsService
{
    public function sendOtp(string $phone, string $code): bool
    {
        try {
            return $this->sendMsg91([
                'mobiles'     => $phone,
                'otp'         => $code,
                'template_id' => config('services.msg91.otp_template_id'),
            ]);
        } catch (\Exception $e) {
            Log::error('OTP SMS failed', ['phone' => $phone, 'error' => $e->getMessage()]);
            return false;
        }
    }

    public function sendBookingConfirmation(string $phone, array $booking): bool
    {
        $message = "Dear {$booking['customer_name']}, your service booking #{$booking['booking_number']} "
            . "is confirmed for {$booking['date']} ({$booking['slot']}). "
            . "Chennai Smart Care | " . config('app.url');

        return $this->sendSms($phone, $message);
    }

    public function sendBookingCancellation(string $phone, array $booking): bool
    {
        $message = "Your booking #{$booking['booking_number']} has been cancelled. "
            . "Contact us: " . config('app.phone') . ". Chennai Smart Care.";

        return $this->sendSms($phone, $message);
    }

    public function sendRescheduled(string $phone, array $booking): bool
    {
        $message = "Your booking #{$booking['booking_number']} is rescheduled to "
            . "{$booking['date']} ({$booking['slot']}). Chennai Smart Care.";

        return $this->sendSms($phone, $message);
    }

    private function sendSms(string $phone, string $message): bool
    {
        // Normalize Indian phone number
        $phone = preg_replace('/[^0-9]/', '', $phone);
        if (strlen($phone) === 10) $phone = '91' . $phone;

        try {
            $response = Http::withHeaders([
                'authkey' => config('services.msg91.auth_key'),
            ])->post('https://api.msg91.com/api/sendhttp.php', [
                'mobiles'  => $phone,
                'authkey'  => config('services.msg91.auth_key'),
                'sender'   => config('services.msg91.sender_id', 'SMTCRE'),
                'route'    => '4',
                'country'  => '91',
                'message'  => $message,
            ]);

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('SMS send failed', ['error' => $e->getMessage()]);
            return false;
        }
    }

    private function sendMsg91(array $params): bool
    {
        $response = Http::withHeaders([
            'authkey'      => config('services.msg91.auth_key'),
            'Content-Type' => 'application/json',
        ])->post('https://control.msg91.com/api/v5/otp', $params);

        return $response->successful();
    }
}