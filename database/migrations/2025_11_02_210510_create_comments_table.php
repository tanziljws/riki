<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('gallery_id')->constrained('galleries')->onDelete('cascade');
            $table->text('text');
            $table->timestamps();
            $table->softDeletes();
            $table->index(['gallery_id','created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
