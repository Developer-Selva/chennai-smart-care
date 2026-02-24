<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuickBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Guest info
            'guest_name'  => 'required|string|max:100',
            'guest_phone' => 'required|string|regex:/^[6-9]\d{9}$/',
            'guest_email' => 'nullable|email|max:150',

            // Address
            'address'   => 'required|string|max:500',
            'area'      => 'nullable|string|max:100',
            'pincode'   => 'nullable|string|regex:/^\d{6}$/',
            'latitude'  => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',

            // Services
            'services'            => 'required|array|min:1',
            'services.*.id'       => 'required|exists:services,id',
            'services.*.quantity' => 'required|integer|min:1|max:10',

            // Schedule
            'booking_date' => [
                'required',
                'date',
                'after_or_equal:today',
                'before_or_equal:' . now()->addDays(30)->toDateString(),
            ],
            'time_slot' => [
                'required',
                'string',
                'in:09:00-11:00,11:00-13:00,13:00-15:00,15:00-17:00,17:00-19:00,19:00-21:00',
            ],

            'customer_notes' => 'nullable|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'guest_phone.regex'           => 'Please enter a valid 10-digit Indian mobile number.',
            'pincode.regex'               => 'Please enter a valid 6-digit pincode.',
            'booking_date.after_or_equal' => 'Booking date cannot be in the past.',
            'booking_date.before_or_equal'=> 'Bookings can only be made up to 30 days in advance.',
            'time_slot.in'                => 'Please select a valid time slot.',
            'services.min'                => 'Please select at least one service.',
        ];
    }

    protected function passedValidation(): void
    {
        [$time] = explode('-', $this->time_slot);
        $this->merge([
            'booking_time' => $time . ':00',
        ]);
    }
}