<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('technicians', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone', 15)->unique();
            $table->string('email')->unique()->nullable();
            $table->json('skills')->nullable(); // ['ac', 'refrigerator', 'washing_machine']
            $table->decimal('rating', 3, 2)->default(5.00);
            $table->integer('total_jobs')->default(0);
            $table->string('avatar')->nullable();
            $table->boolean('is_available')->default(true);
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
    public function down(): void { Schema::dropIfExists('technicians'); }
};
