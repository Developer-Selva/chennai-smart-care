<?php
// -------------------------------------------------------
// app/Repositories/Contracts/BookingRepositoryInterface.php
// -------------------------------------------------------
namespace App\Repositories\Contracts;

use App\Models\Booking;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface BookingRepositoryInterface
{
    public function findByNumber(string $number): ?Booking;
    public function findById(int $id): ?Booking;
    public function createBooking(array $data): Booking;
    public function updateStatus(Booking $booking, string $status, ?string $note, $actor): Booking;
    public function reschedule(Booking $booking, string $date, string $timeSlot, $actor): Booking;
    public function cancelBooking(Booking $booking, string $reason, $actor): Booking;
    public function paginateForAdmin(array $filters, int $perPage = 20): LengthAwarePaginator;
    public function getForUser(int $userId): Collection;
    public function getTodayCount(): int;
    public function getPendingCount(): int;
    public function getRevenueThisMonth(): float;
    public function getSlotAvailability(string $date): array;
}