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
        Schema::create('illnesses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rabbit_id')
                ->nullable()
                ->constrained('rabbits')
                ->onDelete('cascade');
            $table->string('illness');
            $table->string('date');
            $table->string('status')->default('Under Treatment');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('illnesses');
    }
};
