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
        Schema::create('user_activities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->integer('immediate_count')->default(0);
            $table->integer('immediate_business')->default(0);
            $table->integer('team_count')->default(0);
            $table->integer('team_business')->default(0);
            $table->integer('total_count')->default(0);
            $table->integer('total_business')->default(0);
            $table->integer('joining_benefit')->default(0);
            $table->integer('pool_income')->default(0);
            $table->integer('self_earning')->default(0);
            $table->integer('self_paid')->default(0);
            $table->integer('team_earning')->default(0);
            $table->integer('team_paid')->default(0);
            $table->integer('total_earning')->default(0);
            $table->integer('total_paid')->default(0);
            $table->integer('self_balance')->default(0);
            $table->integer('team_balance')->default(0);
            $table->integer('total_balance')->default(0);
            $table->date('last_payment_date')->default(now());
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_activities');
    }
};
