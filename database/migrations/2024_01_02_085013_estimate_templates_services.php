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
        Schema::create('estimate_templates_services', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('connection_id');
            $table->unsignedBigInteger('template_id');
            $table->unsignedBigInteger('service_id');
            $table->string('description_service');
            $table->decimal('quantity_service');
            $table->decimal('cost_service');
            $table->decimal('price_service');
            $table->decimal('tax_service');

            	
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('connection_id')->references('id')->on('connections')->onDelete('cascade');
            $table->foreign('template_id')->references('id')->on('estimate_templates')->onDelete('cascade');
            $table->foreign('service_id')->references('id')->on('services')->onDelete('cascade');
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