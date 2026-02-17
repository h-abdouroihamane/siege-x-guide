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
        Schema::create('operator_queer_identity', function (Blueprint $table) {
            $table->ulid('operator_id');
            $table->foreign('operator_id')->references('id')->on('operators');

            $table->ulid('queer_identity_id');
            $table
                ->foreign('queer_identity_id')
                ->references('id')
                ->on('queer_identities');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('operator_queer_identity');
    }
};
