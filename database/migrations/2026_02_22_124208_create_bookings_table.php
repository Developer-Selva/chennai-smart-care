<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_number', 20)->unique(); // CSC-2024-00001
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('technician_id')->nullable()->constrained()->nullOnDelete();

            // Guest booking fields (when no user account)
            $table->string('guest_name')->nullable();
            $table->string('guest_phone', 15)->nullable();
            $table->string('guest_email')->nullable();

            // Service address
            $table->text('address');
            $table->string('area')->nullable();  // locality/area name
            $table->string('city')->default('Chennai');
            $table->string('pincode', 10)->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();

            // Scheduling
            $table->date('booking_date');
            $table->time('booking_time');
            $table->string('time_slot'); // "09:00-11:00"

            // Status tracking
            $table->enum('status', [
                'pending',
                'confirmed',
                'assigned',
                'in_progress',
                'completed',
                'cancelled',
                'rescheduled',
                'no_show'
            ])->default('pending');

            $table->decimal('total_amount', 8, 2)->nullable();
            $table->decimal('discount_amount', 8, 2)->default(0);
            $table->decimal('final_amount', 8, 2)->nullable();

            $table->text('customer_notes')->nullable();
            $table->text('admin_notes')->nullable();
            $table->text('technician_notes')->nullable();
            $table->string('cancellation_reason')->nullable();

            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();

            $table->string('source')->default('website'); // website, phone, whatsapp, admin
            $table->boolean('is_free_consultation')->default(false);

            $table->timestamps();
            $table->softDeletes();

            $table->index(['booking_date', 'status']);
            $table->index(['guest_phone']);
            $table->index(['booking_number']);
        });
    }
    public function down(): void { Schema::dropIfExists('bookings'); }
};