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
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('business_name');
            $table->string('address');
            $table->string('phone');
            $table->string('email');
            $table->text('website');
            $table->string('legal_name');
            $table->string('hvac');
            $table->text('description_short');
            $table->text('description_long');
            $table->text('message_on_docs');
            $table->text('terms_condition');
            $table->string('logo')->nullable();
            $table->string('date_format', 20)->default('d-m-Y');
            $table->string('time_format', 20)->default('h:i a');
            $table->string('timezone', 191)->default('Asia/Kolkata');
            $table->string('favicon')->nullable();
            $table->text('allowed_file_types')->nullable();
            $table->integer('allowed_file_size')->default(10);
            $table->timestamps(0);
            $table->string('google_map_key')->nullable();
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
