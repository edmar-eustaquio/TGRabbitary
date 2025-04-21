<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('blog_id')
                ->nullable()
                ->constrained('blogs')
                ->onDelete('cascade');
            $table->foreignId('owner_id')
                ->constrained('users')
                ->onDelete('cascade');
            $table->foreignId('reply_to_id')
                ->nullable()
                ->constrained('comments')
                ->onDelete('cascade');
            $table->text('body');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
