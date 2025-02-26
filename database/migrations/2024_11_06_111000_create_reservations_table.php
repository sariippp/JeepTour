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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id');
            $table->foreign('session_id')
                ->references('id')
                ->on('sessions')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->string('name');
            $table->string('email');
            $table->string('telp');
            $table->string('city');
            $table->integer('count');
            $table->double('price');
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'cancelled'])->default('pending'); //tambahan ini untuk pembayaran midtrans
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
