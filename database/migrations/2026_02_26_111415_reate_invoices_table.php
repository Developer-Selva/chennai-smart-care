<?php
// FILE: database/migrations/2024_01_01_000021_create_invoices_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // ── Invoices ──────────────────────────────────────────────
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number', 30)->unique(); // INV-2024-00001
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('admin_id')->nullable()->constrained('admins')->nullOnDelete();

            // Type: system = auto from booking items, custom = admin built
            $table->enum('type', ['system', 'custom'])->default('system');
            $table->enum('status', ['draft', 'sent', 'paid', 'void'])->default('draft');

            // Customer snapshot (in case user data changes)
            $table->string('customer_name');
            $table->string('customer_phone', 20);
            $table->string('customer_email')->nullable();
            $table->string('customer_address', 500);

            // Business details snapshot
            $table->string('business_name')->default('Chennai Smart Care');
            $table->string('business_phone')->default('+91 94449 00470');
            $table->string('business_email')->default('support@chennaismartcare.com');
            $table->string('business_address', 300)->default('Chennai, Tamil Nadu');
            $table->string('business_gstin')->nullable();

            // Amounts
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('discount_percent', 5, 2)->default(0);
            $table->decimal('tax_amount', 10, 2)->default(0);
            $table->decimal('tax_percent', 5, 2)->default(0);   // GST % e.g. 18
            $table->decimal('total', 10, 2)->default(0);

            // Payment
            $table->enum('payment_method', ['cash', 'upi', 'card', 'bank_transfer', 'pending'])
                  ->default('pending');
            $table->string('payment_reference')->nullable(); // UPI transaction ID etc.
            $table->timestamp('paid_at')->nullable();

            // Meta
            $table->text('notes')->nullable();          // shown on invoice
            $table->text('terms')->nullable();           // T&C on invoice
            $table->timestamp('sent_at')->nullable();    // when WhatsApp was sent
            $table->date('due_date')->nullable();

            $table->timestamps();
            $table->index(['booking_id', 'status']);
            $table->index('invoice_number');
        });

        // ── Invoice Line Items ────────────────────────────────────
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained()->cascadeOnDelete();
            $table->string('description');              // service/part/labour name
            $table->string('category')->nullable();     // 'service' | 'labour' | 'parts' | 'extra'
            $table->decimal('unit_price', 10, 2);
            $table->decimal('quantity', 8, 2)->default(1);
            $table->decimal('total', 10, 2);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoice_items');
        Schema::dropIfExists('invoices');
    }
};