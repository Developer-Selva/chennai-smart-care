<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'address'   => 'required|string|max:500',
            'area'      => 'nullable|string|max:100',
            'pincode'   => 'nullable|string|regex:/^\d{6}$/',
            'latitude'  => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',

            'services'            => 'required|array|min:1',
            'services.*.id'       => 'required|exists:services,id',
            'services.*.quantity' => 'required|integer|min:1|max:10',

            'booking_date' => [
                'required',
                'date',
                'after_or_equal:today',
                'before_or_equal:' . now()->addDays(30)->toDateString(),
            ],

            'time_slot'      => 'required|string|in:09:00-11:00,11:00-13:00,13:00-15:00,15:00-17:00,17:00-19:00,19:00-21:00',
            'customer_notes' => 'nullable|string|max:500',
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