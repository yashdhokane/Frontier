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
        Schema::create('estimate_templates_product_items', function (Blueprint $table) {
            $table->id('item_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('estimate_id');
            $table->string('product_name');
            $table->decimal('price_product', 10, 2);
            $table->integer('quantity_product');
            $table->decimal('tax', 10, 2)->nullable();
            $table->decimal('discount', 10, 2)->nullable();
            $table->decimal('cost_product', 10, 2);

            $table->string('description_product');
            $table->timestamps();

            // Foreign key relationships
            $table->foreign('product_id')->references('product_id')->on('products');
            $table->foreign('estimate_id')->references('id')->on('estimates');
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