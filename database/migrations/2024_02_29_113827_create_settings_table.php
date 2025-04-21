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
        Schema::create('settings', function (Blueprint $table) {
            $table->string('invoice_no');
            $table->text('deal_desc')->nullable();
            $table->string('deal_date_until')->nullable();
            $table->string('number')->nullable();
            $table->string('quote_image')->nullable();
            $table->string('quote_title')->nullable();
            $table->string('quote_body')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
