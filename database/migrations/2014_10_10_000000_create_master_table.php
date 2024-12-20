<?php

use App\Enums\AddressTypeEnum;
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
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('name')->require();
            $table->string('number_code')->nullable();
            $table->string('code')->nullable();
            $table->timestamps();
        });
        Schema::create('states', function (Blueprint $table) {
            $table->id();
            $table->string('name')->require();
            $table->string('number_code')->nullable();
            $table->string('code')->nullable();
            $table->unsignedBigInteger('country_id')->nullable();
            // $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->enum('address_type', AddressTypeEnum::labels())->default(AddressTypeEnum::default());
            $table->string('address_line_1');
            $table->string('address_line_2')->nullable();
            $table->string('city')->nullable();
            $table->string('post_office')->nullable();
            $table->string('rail_station')->nullable();
            $table->string('police_station')->nullable();
            $table->string('district')->nullable();

            $table->string('postal_code')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->timestamps();
            $table->unsignedBigInteger('country_id')->nullable();
            $table->unsignedBigInteger('state_id')->nullable();

            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();

            // If you want foreign key constraints
            // $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade');
            // $table->foreign('state_id')->references('id')->on('states')->onDelete('cascade');
            // $table->foreign('created_by')->references('id')->on('users');
            // $table->foreign('updated_by')->references('id')->on('users');
        });
        Schema::create('designations', function (Blueprint $table) {
            $table->id();
            $table->string('name')->require();
            $table->timestamps();
        });
     

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('states');
        Schema::dropIfExists('countries');
        Schema::dropIfExists('addresses');
        Schema::dropIfExists('designations'); 

    }
};
