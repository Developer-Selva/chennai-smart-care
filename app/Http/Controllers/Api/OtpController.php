<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class OtpController extends Controller
{
    public function send(Request $request): JsonResponse
    {
        $request->validate([
            'phone' => 'required|string|regex:/^[6-9]\d{9}$/',
        ]);

        $otp = rand(100000, 999999);

        // Store OTP in cache for 10 minutes
        Cache::put('otp_' . $request->phone, $otp, now()->addMinutes(10));

        // TODO: Integrate SMS provider (Twilio / MSG91 / Fast2SMS)
        // SmsService::send($request->phone, "Your Chennai Smart Care OTP is: {$otp}");

        // In development, return the OTP directly
        $response = ['message' => 'OTP sent successfully.'];
        if (app()->isLocal()) {
            $response['otp'] = $otp;
        }

        return response()->json($response);
    }

    public function verify(Request $request): JsonResponse
    {
        $request->validate([
            'phone' => 'required|string',
            'otp'   => 'required|string|size:6',
        ]);

        $cached = Cache::get('otp_' . $request->phone);

        if (! $cached || (string) $cached !== (string) $request->otp) {
            return response()->json(['message' => 'Invalid or expired OTP.'], 422);
        }

        Cache::forget('otp_' . $request->phone);

        // Find or create user
        $user = User::firstOrCreate(
            ['phone' => $request->phone],
            ['name'  => 'User ' . substr($request->phone, -4)]
        );

        $token = $user->createToken('mobile')->plainTextToken;

        return response()->json([
            'message' => 'OTP verified.',
            'token'   => $token,
            'user'    => $user,
        ]);
    }
}