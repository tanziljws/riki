<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('actor_user_id')->constrained('users')->onDelete('cascade');
            $table->enum('type', ['like','comment']);
            $table->foreignId('gallery_id')->constrained('galleries')->onDelete('cascade');
            $table->foreignId('comment_id')->nullable()->constrained('comments')->nullOnDelete();
            $table->json('meta')->nullable();
            $table->timestamps();
            $table->index(['type','created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
