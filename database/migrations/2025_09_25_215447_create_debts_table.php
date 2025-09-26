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
        Schema::create('debts', function (Blueprint $table) {
            $table->id();
            $table->float('quantity');
            $table->string('description');
            $table->date('date_start');
            $table->enum('status', ['pendiente', 'pagada'])->default('pendiente');
            $table->timestamps();
            $table->foreignId('contact_id')->constrained()->onDelete('cascade')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('debts');
    }
};
