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
        Schema::create('products', function (Blueprint $table) {
            $table->id('product_id');
            $table->integer('product_category_id');
            $table->varchar('product_code', 20);
            $table->varchar('assigned_to');
            $table->varchar('product_name', 255);
            $table->text('product_short');
            $table->text('product_description');
            $table->varchar('product_image', 255);
            $table->varchar('status', 30);
            $table->integer('created_by');
            $table->integer('updated_by');
            $table->timestamp('created_at');
            $table->timestamp('updated_at');
            $table->float('base_price');
            $table->float('discount');
            $table->float('tax');
            $table->float('total');
            $table->integer('stock');
            $table->primary('product_id');
            $table->index('product_category_id');
            // Add any other indexes or foreign keys as needed
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};