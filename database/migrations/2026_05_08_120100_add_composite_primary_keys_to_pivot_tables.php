<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('operator_role', function (Blueprint $table) {
            $table->primary(['operator_id', 'role_id']);
        });

        Schema::table('operator_squad', function (Blueprint $table) {
            $table->primary(['operator_id', 'squad_id']);
        });

        Schema::table('operator_queer_identity', function (Blueprint $table) {
            $table->primary(['operator_id', 'queer_identity_id']);
        });

        Schema::table('operator_secondary_gadget', function (Blueprint $table) {
            $table->primary(['operator_id', 'secondary_gadget_id']);
        });

        Schema::table('operator_rework', function (Blueprint $table) {
            $table->primary(['operator_id', 'operation_id']);
        });
    }

    public function down(): void
    {
        Schema::table('operator_role', function (Blueprint $table) {
            $table->dropPrimary(['operator_id', 'role_id']);
        });

        Schema::table('operator_squad', function (Blueprint $table) {
            $table->dropPrimary(['operator_id', 'squad_id']);
        });

        Schema::table('operator_queer_identity', function (Blueprint $table) {
            $table->dropPrimary(['operator_id', 'queer_identity_id']);
        });

        Schema::table('operator_secondary_gadget', function (Blueprint $table) {
            $table->dropPrimary(['operator_id', 'secondary_gadget_id']);
        });

        Schema::table('operator_rework', function (Blueprint $table) {
            $table->dropPrimary(['operator_id', 'operation_id']);
        });
    }
};
