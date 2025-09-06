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
        Schema::create('operator_secondary_gadget', function (Blueprint $table) {
            $table->ulid('operator_id');
            $table->foreign('operator_id')->references('id')->on('operators');

            $table->ulid('secondary_gadget_id');
            $table->foreign('secondary_gadget_id')->references('id')->on('secondary_gadgets');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('operator_secondary_gadget');
    }
};
