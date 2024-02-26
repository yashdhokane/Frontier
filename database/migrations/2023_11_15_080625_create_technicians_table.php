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
        Schema::create('technicians', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('phone')->nullable();
            $table->string('designation')->nullable();
            $table->string('age')->nullable();
            $table->string('doj')->nullable();
            $table->decimal('salary', 8, 2);
            $table->string('image')->nullable();
            $table->string('task_assign');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->enum('role', ['Electrician', 'technician', 'Plumber', 'Mechanick'])->default('technician');
            $table->enum('status', ['available', 'unavailable'])->default('available');
            $table->string('password');
            $table->string('lat');
            $table->string('lng');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('technicians');
    }
};
