<?php
// ================================================================
// FILE: database/migrations/2024_01_01_000020_create_warranties_table.php
// ================================================================
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // ── Warranties ────────────────────────────────────────────
        Schema::create('warranties', function (Blueprint $table) {
            $table->id();
            $table->string('warranty_number')->unique(); // WRN-2024-00001
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('technician_id')->nullable()->constrained()->nullOnDelete();

            // What is covered
            $table->text('services_covered');   // JSON-encoded list of service names
            $table->text('appliances_covered');  // e.g. "Samsung AC Split 1.5T"

            // Period
            $table->date('starts_at');           // = booking completed_at date
            $table->date('expires_at');          // = starts_at + 6 months
            $table->enum('status', ['active', 'expired', 'claimed', 'void'])->default('active');

            // Claim tracking
            $table->timestamp('claimed_at')->nullable();
            $table->text('claim_notes')->nullable();

            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index('expires_at');
        });

        // ── AMC Subscriptions ─────────────────────────────────────
        Schema::create('amc_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->string('amc_number')->unique();    // AMC-2024-00001
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->enum('type', ['free', 'paid'])->default('paid');
            $table->decimal('price', 8, 2)->default(0);  // 0 for free tier

            // Period
            $table->date('starts_at');
            $table->date('expires_at');   // starts_at + 1 year
            $table->enum('status', ['active', 'expired', 'cancelled'])->default('active');

            // What triggered free AMC
            $table->decimal('qualifying_spend', 8, 2)->nullable(); // spend that unlocked free AMC

            // Benefits included
            $table->integer('free_services_total')->default(2);  // 2 free service calls/year
            $table->integer('free_services_used')->default(0);
            $table->decimal('discount_percent', 5, 2)->default(15.00); // 15% off all repairs
            $table->boolean('priority_booking')->default(true);

            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index('expires_at');
        });

        // ── Annual spend tracker (for AMC eligibility) ────────────
        // Tracks rolling 12-month spend per user to auto-unlock free AMC
        Schema::create('user_annual_spends', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->integer('year');                     // calendar year e.g. 2024
            $table->decimal('total_spend', 10, 2)->default(0);
            $table->boolean('amc_unlocked')->default(false); // true once ≥ ₹5000
            $table->timestamp('amc_unlocked_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'year']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_annual_spends');
        Schema::dropIfExists('amc_subscriptions');
        Schema::dropIfExists('warranties');
    }
};