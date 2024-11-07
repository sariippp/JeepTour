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
        Schema::create('reserve_jeep', function (Blueprint $table) {
            $table->foreignId('reservation_id');
            $table->foreignId('jeep_id');
            $table->foreign('reservation_id')
                    ->references('id')
                    ->on('reservations')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
            $table->foreign('jeep_id')
                ->references('id')
                ->on('jeeps')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reserve_jeep');
    }
};
