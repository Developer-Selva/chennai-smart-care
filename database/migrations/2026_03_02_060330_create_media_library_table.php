<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('media_library', function (Blueprint $table) {
            $table->id();

            // Who uploaded it
            $table->foreignId('uploaded_by')
                  ->constrained('admins')
                  ->cascadeOnDelete();

            // File metadata
            $table->string('filename');              // original filename (sanitised)
            $table->string('disk')->default('public'); // Laravel disk name
            $table->string('path');                  // storage path: blog/2026/03/uuid.webp
            $table->string('url');                   // public URL: /storage/blog/2026/03/uuid.webp
            $table->string('mime_type', 100);
            $table->unsignedBigInteger('file_size'); // bytes

            // Image dimensions (null for non-images)
            $table->unsignedSmallInteger('width')->nullable();
            $table->unsignedSmallInteger('height')->nullable();

            // SEO / accessibility
            $table->string('alt_text', 250)->nullable();
            $table->string('caption', 500)->nullable();
            $table->string('title', 250)->nullable();

            // Thumbnail path (WebP, max 400px wide) — generated on upload
            $table->string('thumbnail_path')->nullable();
            $table->string('thumbnail_url')->nullable();

            // Folder/collection grouping for future organisation
            $table->string('collection', 80)->default('blog')
                  ->index()
                  ->comment('Logical folder: blog, team, services …');

            // Full-text search helper
            $table->string('search_text', 600)->nullable()
                  ->comment('Concatenated filename + alt + caption for LIKE search');

            $table->softDeletes();
            $table->timestamps();

            $table->index(['collection', 'created_at']);
            $table->index('uploaded_by');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media_library');
    }
};