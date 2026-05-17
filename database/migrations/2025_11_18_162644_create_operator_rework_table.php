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
        Schema::create('operator_rework', function (Blueprint $table) {
            // operators.id is a ULID, so operator_id matches as ulid.
            $table->ulid('operator_id');
            $table->foreign('operator_id')->references('id')->on('operators');

            // operations.id is a string key (e.g. "Y10S3"), NOT a ULID —
            // operation_id must be string to match the FK (was ulid/char(26),
            // which silently mismatched on real databases). See report H4.
            $table->string('operation_id');
            $table->foreign('operation_id')->references('id')->on('operations');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('operator_rework');
    }
};
