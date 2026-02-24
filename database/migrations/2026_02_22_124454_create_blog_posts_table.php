<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('blog_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('blog_category_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('author_id')->nullable()->constrained('admins')->nullOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt')->nullable();
            $table->longText('content');
            $table->string('featured_image')->nullable();
            $table->string('alt_text')->nullable();
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->timestamp('published_at')->nullable();

            // SEO fields
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('focus_keyword')->nullable();
            $table->json('schema_markup')->nullable();
            $table->string('canonical_url')->nullable();
            $table->boolean('is_indexed')->default(true);

            // Engagement
            $table->integer('view_count')->default(0);
            $table->integer('read_time_minutes')->default(0);

            // Google Ads & Analytics
            $table->string('gtm_category')->nullable(); // for GA event tracking
            $table->json('tags')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'published_at']);
            $table->fullText(['title', 'content', 'excerpt']);
        });
    }
    public function down(): void { Schema::dropIfExists('blog_posts'); }
};
