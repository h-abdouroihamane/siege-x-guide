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
        Schema::create('operator_squad', function (Blueprint $table) {
            $table->ulid('operator_id');
            $table->foreign('operator_id')->references('id')->on('operators');

            $table->ulid('squad_id');
            $table->foreign('squad_id')->references('id')->on('squads');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('operator_squad');
    }
};
