<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

        /*
    |--------------------------------------------------------------------------
    | WhatsApp Business Cloud API (Meta)
    |--------------------------------------------------------------------------
    |
    | Free to use — no monthly fee. Charges apply per conversation
    | after the first 1,000 free conversations per month.
    |
    | Setup guide:
    |   1. Create a Meta Developer App: https://developers.facebook.com
    |   2. Add the WhatsApp product to your app
    |   3. Get your Phone Number ID from the WhatsApp dashboard
    |   4. Generate a permanent System User Token
    |   5. Add the number you want to receive admin alerts
    |
    */
    'whatsapp' => [
        'token'       => env('WHATSAPP_TOKEN'),       // Permanent system user access token
        'phone_id'    => env('WHATSAPP_PHONE_ID'),    // Numeric phone number ID from Meta dashboard
        'admin_phone' => env('WHATSAPP_ADMIN_PHONE', '919444900470'), // Admin's WhatsApp number (with country code, no +)
        'templates_approved' => env('WHATSAPP_TEMPLATES_APPROVED', false),
    ],

];
