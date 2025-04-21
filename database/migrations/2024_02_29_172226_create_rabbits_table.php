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
        Schema::create('rabbits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')
                ->constrained('products')
                ->onDelete('cascade');
            $table->string('images')->nullable();
            $table->string('name')->nullable();
            $table->string('gender')->nullable();
            $table->string('birth')->nullable();
            $table->text('note')->nullable();
            $table->string('illness')->nullable();
            $table->string('status')->nullable();
            $table->boolean('sold')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rabbits');
    }
};
