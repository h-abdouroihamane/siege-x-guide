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
        Schema::create('operators', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('name');
            $table->string('description');
            $table->string('side');
            $table->unsignedInteger('year');
            $table->unsignedInteger('season');

            /*
             * Concatenating the Year and Season as YxxSxx
             * to circumvent Eloquent's ability to deal with
             * composite foreign keys
             */
            $table->string('operation_id')->default('Y1S0');
            $table
                ->foreign('operation_id')
                ->references('id')
                ->on('operations');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('operators');
    }
};
