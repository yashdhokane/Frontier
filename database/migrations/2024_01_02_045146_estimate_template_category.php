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
        Schema::create('estimate_template_category', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('category_name');
            $table->string('category_image')->nullable();
            $table->unsignedBigInteger('added_by');
            $table->unsignedBigInteger('last_updated_by');
            $table->timestamps();

            // Foreign key constraint for parent_id
            $table->foreign('parent_id')->references('id')->on('estimate_template_category')->onDelete('set null');

            // Foreign key constraint for added_by
            $table->foreign('added_by')->references('id')->on('users')->onDelete('cascade');

            // Foreign key constraint for last_updated_by
            $table->foreign('last_updated_by')->references('id')->on('users')->onDelete('cascade');
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