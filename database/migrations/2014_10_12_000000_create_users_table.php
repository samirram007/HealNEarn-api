<?php

use App\Enums\GenderEnum;
use App\Enums\UserStatusEnum;
use App\Enums\UserTypeEnum;
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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->enum('status', array_keys(UserStatusEnum::labels()))->default(UserStatusEnum::INACTIVE);

            $table->unsignedBigInteger('manager_id')->nullable();
            $table->datetime('activation_date')->nullable();
            // $table->datetime('purchase_date')->nullable();
            // $table->unsignedBigInteger('product_id')->nullable();
            // $table->integer('product_amount')->nullable();
            // $table->string('product_no')->nullable();
            $table->string('name');
            $table->string('username')->unique();
            $table->enum('user_type', array_keys(UserTypeEnum::labels()))->default(UserTypeEnum::MANAGER);
            $table->string('contact_no')->nullable();
            $table->string('email')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('gender', array_keys(GenderEnum::labels()))->nullable();
            $table->date('doj')->nullable();
            $table->date('dob')->nullable();

            $table->string('aadhaar_no', 20)->nullable();
            $table->string('pan_no', 20)->nullable();
            $table->string('passport_no', 50)->nullable();
            $table->string('bank_name', 100)->nullable();
            $table->string('account_holder_name', 100)->nullable();
            $table->string('bank_account_no', 20)->nullable();
            $table->string('upi', 50)->nullable();
            $table->string('bank_ifsc', 20)->nullable();
            $table->string('bank_branch', 100)->nullable();
            $table->rememberToken();
            $table->timestamps();

            // $table->foreign('parent_id')->references('id')->on('users')->onDelete('cascade');

            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();

            // If you want foreign key constraints
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('updated_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
