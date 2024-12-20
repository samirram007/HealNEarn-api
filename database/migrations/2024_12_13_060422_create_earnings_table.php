<?php

use App\Enums\EarningTypeEnum;
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
        Schema::create('earnings', function (Blueprint $table) {
            $table->id();
            $table->date('generation_date')->default(now());
            $table->unsignedBigInteger('product_user_id');
            $table->unsignedBigInteger('user_id');
            $table->enum('earning_type', array_keys(EarningTypeEnum::labels()))->default(EarningTypeEnum::LEVEL);
            $table->string('note')->nullable();
            $table->string('product_amount')->default(300);
            $table->decimal('earning_amount', 10, 2)->default(0);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('product_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->boolean('is_deleted')->default(false);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('earnings');
    }
};
