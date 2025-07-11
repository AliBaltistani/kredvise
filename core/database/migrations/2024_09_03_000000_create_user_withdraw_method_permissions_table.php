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
        if (!Schema::hasTable('user_withdraw_method_permissions')) {
            Schema::create('user_withdraw_method_permissions', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('user_id');
                $table->unsignedBigInteger('withdraw_method_id');
                $table->boolean('status')->default(0)->comment('0: disabled, 1: enabled');
                $table->timestamps();
                
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('withdraw_method_id')->references('id')->on('withdraw_methods')->onDelete('cascade');
                
                // Ensure a user can have only one permission record per withdrawal method
                $table->unique(['user_id', 'withdraw_method_id'], 'user_withdraw_method_unique');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_withdraw_method_permissions');
    }
};