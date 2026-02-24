<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth('admin')->check();
    }

    public function rules(): array
    {
        return [
            'guest_name'    => 'required_without:user_id|string|max:100',
            'guest_phone'   => 'required_without:user_id|string|regex:/^[6-9]\d{9}$/',
            'guest_email'   => 'nullable|email',

            'user_id'       => 'nullable|exists:users,id',
            'technician_id' => 'nullable|exists:technicians,id',

            'address'   => 'required|string|max:500',
            'area'      => 'nullable|string|max:100',
            'pincode'   => 'nullable|string|regex:/^\d{6}$/',
            'latitude'  => 'nullable|numeric',
            'longitude' => 'nullable|numeric',

            'services'            => 'required|array|min:1',
            'services.*.id'       => 'required|exists:services,id',
            'services.*.quantity' => 'required|integer|min:1',

            'booking_date'   => 'required|date',
            'time_slot'      => 'required|string|in:09:00-11:00,11:00-13:00,13:00-15:00,15:00-17:00,17:00-19:00,19:00-21:00',

            'customer_notes' => 'nullable|string|max:1000',
            'admin_notes'    => 'nullable|string|max:1000',
            'source'         => 'in:website,phone,whatsapp,admin',
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