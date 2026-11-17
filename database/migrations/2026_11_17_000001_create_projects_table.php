<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('summary', 220);
            $table->text('description')->nullable();

            // Where the code lives. provider is detected from repo_url.
            $table->string('repo_url')->nullable();
            $table->string('provider')->nullable();
            $table->boolean('show_repo_link')->default(true);
            $table->string('live_url')->nullable();

            $table->string('language', 40)->nullable();
            $table->json('topics')->nullable();
            $table->string('cover')->nullable();

            $table->boolean('is_featured')->default(false);
            $table->boolean('is_published')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);

            $table->timestamps();

            $table->index(['is_published', 'is_featured', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
