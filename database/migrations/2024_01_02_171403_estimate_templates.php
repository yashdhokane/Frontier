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
        Schema::create('estimate_templates', function (Blueprint $table) {
            $table->id();
            $table->string('template_name');
            $table->text('template_description')->nullable();
            $table->unsignedBigInteger('template_category_id')->nullable();
            $table->string('template_status')->default('active');
            $table->decimal('estimate_subtotal', 10, 2)->nullable();
            $table->decimal('estimate_tax', 10, 2)->nullable();
            $table->decimal('estimate_discount', 10, 2)->nullable();
            $table->decimal('estimate_total', 10, 2)->nullable();
            $table->unsignedBigInteger('added_by')->nullable();
            $table->unsignedBigInteger('last_updated_by')->nullable();
            $table->timestamps();

            // Foreign key constraint for template_category_id
            $table->foreign('template_category_id')->references('id')->on('template_categories')->onDelete('set null');

            // Foreign key constraint for added_by and last_updated_by
            $table->foreign('added_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('last_updated_by')->references('id')->on('users')->onDelete('set null');
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