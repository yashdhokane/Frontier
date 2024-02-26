<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('location_cities', function (Blueprint $table) {
            $table->increments('city_id');
            $table->string('city', 50);
            $table->char('state_code', 2);
            $table->unsignedSmallInteger('zip')->nullable();
            $table->double('latitude');
            $table->double('longitude');
            $table->string('county', 50);
            $table->unsignedTinyInteger('state_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('location_cities');
    }
};