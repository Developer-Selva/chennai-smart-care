<?php

namespace App\Repositories;

use App\Models\Booking;
use App\Models\BookingHistory;
use App\Repositories\Contracts\BookingRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class BookingRepository implements BookingRepositoryInterface
{
    public function __construct(protected Booking $model) {}

    public function findByNumber(string $number): ?Booking
    {
        return $this->model->with(['items.service', 'user', 'technician'])
            ->where('booking_number', $number)
            ->first();
    }

    public function findById(int $id): ?Booking
    {
        return $this->model->with(['items.service', 'user', 'technician', 'history'])
            ->findOrFail($id);
    }

    public function createBooking(array $data): Booking
    {
        return DB::transaction(function () use ($data) {
            // Derive booking_time from time_slot start (e.g. "09:00-11:00" → "09:00:00")
            if (empty($data['booking_time']) && ! empty($data['time_slot'])) {
                $data['booking_time'] = explode('-', $data['time_slot'])[0] . ':00';
            }

            // Strip non-column keys before insert
            $insertData = array_diff_key($data, array_flip(['services', 'discount_amount']));

            $booking = $this->model->create([
                ...$insertData,
                'booking_number' => Booking::generateBookingNumber(),
            ]);

            if (! empty($data['services'])) {
                // Pre-load service names from DB to avoid relying on client-submitted names
                $serviceIds   = array_column($data['services'], 'id');
                $serviceNames = \App\Models\Service::whereIn('id', $serviceIds)
                    ->pluck('name', 'id');

                foreach ($data['services'] as $serviceData) {
                    $id       = $serviceData['id'];
                    $price    = $serviceData['price'] ?? 0;
                    $quantity = $serviceData['quantity'] ?? 1;

                    $booking->items()->create([
                        'service_id'   => $id,
                        'service_name' => $serviceData['name'] ?? $serviceNames[$id] ?? 'Service #' . $id,
                        'unit_price'   => $price,
                        'quantity'     => $quantity,
                        'total_price'  => $price * $quantity,
                    ]);
                }
            }

            $total = $booking->items()->sum('total_price');
            $booking->update([
                'total_amount' => $total,
                'final_amount' => $total - ($data['discount_amount'] ?? 0),
            ]);

            return $booking->fresh(['items.service']);
        });
    }

    public function updateStatus(Booking $booking, string $status, ?string $note, $actor): Booking
    {
        $oldStatus = $booking->status;

        $updates = ['status' => $status];
        if ($status === 'confirmed')   $updates['confirmed_at'] = now();
        if ($status === 'completed')   $updates['completed_at'] = now();
        if ($status === 'cancelled')   $updates['cancelled_at'] = now();

        $booking->update($updates);

        BookingHistory::create([
            'booking_id'  => $booking->id,
            'action'      => 'status_changed',
            'from_status' => $oldStatus,
            'to_status'   => $status,
            'note'        => $note,
            'actor_id'    => $actor->id,
            'actor_type'  => get_class($actor),
        ]);

        return $booking->fresh();
    }

    public function reschedule(Booking $booking, string $date, string $timeSlot, $actor): Booking
    {
        $old = [
            'date' => $booking->booking_date->format('Y-m-d'),
            'slot' => $booking->time_slot,
        ];

        $bookingTime = explode('-', $timeSlot)[0] . ':00';

        $booking->update([
            'booking_date' => $date,
            'booking_time' => $bookingTime,
            'time_slot'    => $timeSlot,
            'status'       => 'rescheduled',
        ]);

        BookingHistory::create([
            'booking_id' => $booking->id,
            'action'     => 'rescheduled',
            'note'       => "Rescheduled from {$old['date']} {$old['slot']} to {$date} {$timeSlot}",
            'actor_id'   => $actor->id,
            'actor_type' => get_class($actor),
        ]);

        return $booking->fresh();
    }

    public function cancelBooking(Booking $booking, string $reason, $actor): Booking
    {
        return $this->updateStatus($booking, 'cancelled', $reason, $actor);
    }

    public function paginateForAdmin(array $filters, int $perPage = 20): LengthAwarePaginator
    {
        $query = $this->model->with(['items', 'user', 'technician'])
            ->latest('booking_date');

        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        if (! empty($filters['date'])) {
            $query->whereDate('booking_date', $filters['date']);
        }
        if (! empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('booking_number', 'like', "%{$filters['search']}%")
                  ->orWhere('guest_name', 'like', "%{$filters['search']}%")
                  ->orWhere('guest_phone', 'like', "%{$filters['search']}%")
                  ->orWhereHas('user', fn ($u) =>
                      $u->where('name', 'like', "%{$filters['search']}%")
                        ->orWhere('phone', 'like', "%{$filters['search']}%")
                  );
            });
        }
        if (! empty($filters['technician_id'])) {
            $query->where('technician_id', $filters['technician_id']);
        }
        if (! empty($filters['from'])) {
            $query->whereDate('booking_date', '>=', $filters['from']);
        }
        if (! empty($filters['to'])) {
            $query->whereDate('booking_date', '<=', $filters['to']);
        }

        return $query->paginate($perPage)->withQueryString();
    }

    public function getForUser(int $userId): Collection
    {
        return $this->model->with(['items', 'technician', 'review'])
            ->where('user_id', $userId)
            ->latest('booking_date')
            ->get();
    }

    public function getTodayCount(): int
    {
        return $this->model->whereDate('booking_date', today())->count();
    }

    public function getPendingCount(): int
    {
        return $this->model->where('status', 'pending')->count();
    }

    public function getRevenueThisMonth(): float
    {
        return (float) $this->model
            ->where('status', 'completed')
            ->whereMonth('created_at', now()->month)
            ->sum('final_amount');
    }

    public function getSlotAvailability(string $date): array
    {
        $bookedSlots = $this->model
            ->whereDate('booking_date', $date)
            ->whereNotIn('status', ['cancelled'])
            ->pluck('time_slot')
            ->toArray();

        $slotDefs = [
            '09:00-11:00', '11:00-13:00', '13:00-15:00',
            '15:00-17:00', '17:00-19:00', '19:00-21:00',
        ];

        $maxPerSlot = config('booking.max_per_slot', 3);
        $counts     = array_count_values($bookedSlots);

        return array_map(function (string $slot) use ($counts, $maxPerSlot) {
            $booked = $counts[$slot] ?? 0;
            [$from, $to] = explode('-', $slot);
            return [
                'slot'      => $slot,
                'label'     => $this->to12h($from) . ' – ' . $this->to12h($to),
                'available' => $booked < $maxPerSlot,
                'count'     => $booked,
            ];
        }, $slotDefs);
    }

    private function to12h(string $time): string
    {
        return date('g:i A', strtotime($time));
    }
}