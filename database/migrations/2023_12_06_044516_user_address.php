<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('user_address', function (Blueprint $table) {
            $table->id('address_id');
            $table->unsignedBigInteger('user_id');
            $table->enum('address_type', ['billing', 'work', 'ticket', 'other']);
            $table->enum('address_primary', ['yes', 'no'])->default('no');
            $table->string('address_line1', 255);
            $table->string('address_line2', 255)->nullable();
            $table->string('city', 255);
            $table->string('zipcode', 10);
            $table->string('state_name');
            $table->string('state_id');
            $table->unsignedSmallInteger('country_id');
            $table->text('address_notes')->nullable();
            $table->string('latitude', 100)->nullable();
            $table->string('longitude', 100)->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            // Add foreign keys for state_id and country_id if needed
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        //
    }
};
