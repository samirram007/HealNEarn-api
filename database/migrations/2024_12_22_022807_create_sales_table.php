<?php

use Carbon\Traits\Timestamp;
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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->string('sale_no')->default(1);
            $table->date('sale_date')->default(now());
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('product_id')->default(1);
            $table->integer('quantity')->default(1);
            $table->integer('rate')->default(300);
            $table->integer('amount')->default(300);
            $table->boolean('is_confirm')->default(false);
            $table->unsignedBigInteger('confirmed_by_id')->default(1);
            $table->datetime('confirmation_date')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
