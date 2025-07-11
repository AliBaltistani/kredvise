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
        if (!Schema::hasTable('user_currency_permissions')) {
            Schema::create('user_currency_permissions', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('user_id');
                $table->unsignedBigInteger('gateway_currency_id');
                $table->boolean('status')->default(0)->comment('0: disabled, 1: enabled');
                $table->timestamps();
                
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('gateway_currency_id')->references('id')->on('gateway_currencies')->onDelete('cascade');
                
                // Ensure a user can have only one permission record per currency
                $table->unique(['user_id', 'gateway_currency_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_currency_permissions');
    }
};