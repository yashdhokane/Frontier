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
        Schema::create('location_service_area', function (Blueprint $table) {
            $table->id();
            $table->string('area_id');
            $table->string('area_name');
            $table->text('area_description')->nullable();
            $table->string('area_type');
            $table->integer('area_radius');
            $table->decimal('area_latitude');
            $table->decimal('area_longitude');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};