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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('service_category_id');
            $table->string('service_code');
            $table->string('service_name');
            $table->text('service_description')->nullable();
            $table->string('service_image')->nullable();
            $table->timestamps();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->double('service_cost');
            $table->double('service_discount')->nullable();
            $table->double('service_tax')->nullable();
            $table->double('service_total')->nullable();
            $table->integer('service_time')->nullable();
            $table->boolean('service_online')->default(false);
            $table->string('service_for')->nullable();
            $table->text('troubleshooting_question1')->nullable();
            $table->text('troubleshooting_question2')->nullable();
            $table->float('service_cost')->default(0);
            $table->integer('service_quantity')->default(0);
            $table->float('service_discount')->default(0);
            $table->float('service_tax')->default(0);
            $table->float('service_total')->default(0);

            // Foreign key constraint for service_category_id
            $table->foreign('service_category_id')->references('id')->on('service_category')->onDelete('cascade');

            // Add more foreign key constraints if needed for created_by, updated_by, etc.
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
