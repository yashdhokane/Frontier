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
        Schema::create('location_states', function (Blueprint $table) {
            $table->id('state_id');
            $table->string('state_name');
            $table->integer('state_tax');
            $table->string('state_code');
            $table->timestamps(); // You can remove this if you don't need timestamps
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