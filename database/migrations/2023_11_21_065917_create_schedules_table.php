<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->string('task_name');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('technician_id'); // Technician ID or foreign key
            $table->dateTime('deadline');
            // Add other columns as needed
            $table->date('date');
            $table->time('time');
            // Other columns as needed

            // Foreign key constraint
            $table->foreign('technician_id')->references('id')->on('technicians')->onDelete('cascade');

            $table->timestamps(); // Created at & Updated at timestamps
        });
    }

    public function down() {
        Schema::dropIfExists('schedules');
    }
};
