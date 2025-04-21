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
        Schema::create('breedings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('buck_id')
                ->nullable()
                ->constrained('rabbits')
                ->onDelete('cascade');
            $table->foreignId('doe_id')
                ->nullable()
                ->constrained('rabbits')
                ->onDelete('cascade');
            $table->string('tested')->nullable();
            $table->string('bred')->nullable();
            $table->string('breed_duration')->nullable();
            $table->string('out_count')->nullable();
            $table->string('kindled')->nullable();
            $table->string('weaning')->nullable();
            $table->string('alive')->nullable();
            $table->string('dead')->nullable();
            $table->string('expected_kindle')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('breedings');
    }
};
