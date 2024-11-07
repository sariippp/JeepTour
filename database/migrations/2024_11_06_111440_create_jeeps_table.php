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
        Schema::create('jeeps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id');
            $table->foreign('owner_id')
                ->references('id')
                ->on('owners')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->string('number_plate');
            $table->integer('total_passenger');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jeeps');
    }
};
