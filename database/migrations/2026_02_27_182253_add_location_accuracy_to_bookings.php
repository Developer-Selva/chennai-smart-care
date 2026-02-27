<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->decimal('location_accuracy', 8, 2)->nullable()->after('longitude')
                  ->comment('GPS accuracy in metres');
            $table->string('location_source', 20)->nullable()->after('location_accuracy')
                  ->comment('gps | network | manual');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['location_accuracy', 'location_source']);
        });
    }
};