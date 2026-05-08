<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Cascade on operator deletion across every pivot — orphan rows would
        // be meaningless. The "other side" uses restrict for reference data
        // that should not silently disappear (roles, squads, gadgets, ops),
        // and cascade for queer_identities which is pure tag data.
        Schema::table('operator_role', function (Blueprint $table) {
            $table->dropForeign(['operator_id']);
            $table->dropForeign(['role_id']);
            $table
                ->foreign('operator_id')
                ->references('id')
                ->on('operators')
                ->onDelete('cascade');
            $table
                ->foreign('role_id')
                ->references('id')
                ->on('roles')
                ->onDelete('restrict');
        });

        Schema::table('operator_squad', function (Blueprint $table) {
            $table->dropForeign(['operator_id']);
            $table->dropForeign(['squad_id']);
            $table
                ->foreign('operator_id')
                ->references('id')
                ->on('operators')
                ->onDelete('cascade');
            $table
                ->foreign('squad_id')
                ->references('id')
                ->on('squads')
                ->onDelete('restrict');
        });

        Schema::table('operator_queer_identity', function (Blueprint $table) {
            $table->dropForeign(['operator_id']);
            $table->dropForeign(['queer_identity_id']);
            $table
                ->foreign('operator_id')
                ->references('id')
                ->on('operators')
                ->onDelete('cascade');
            $table
                ->foreign('queer_identity_id')
                ->references('id')
                ->on('queer_identities')
                ->onDelete('cascade');
        });

        Schema::table('operator_secondary_gadget', function (Blueprint $table) {
            $table->dropForeign(['operator_id']);
            $table->dropForeign(['secondary_gadget_id']);
            $table
                ->foreign('operator_id')
                ->references('id')
                ->on('operators')
                ->onDelete('cascade');
            $table
                ->foreign('secondary_gadget_id')
                ->references('id')
                ->on('secondary_gadgets')
                ->onDelete('restrict');
        });

        Schema::table('operator_rework', function (Blueprint $table) {
            $table->dropForeign(['operator_id']);
            $table->dropForeign(['operation_id']);
            $table
                ->foreign('operator_id')
                ->references('id')
                ->on('operators')
                ->onDelete('cascade');
            $table
                ->foreign('operation_id')
                ->references('id')
                ->on('operations')
                ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        foreach (
            [
                'operator_role' => ['role_id' => 'roles'],
                'operator_squad' => ['squad_id' => 'squads'],
                'operator_queer_identity' => [
                    'queer_identity_id' => 'queer_identities',
                ],
                'operator_secondary_gadget' => [
                    'secondary_gadget_id' => 'secondary_gadgets',
                ],
                'operator_rework' => ['operation_id' => 'operations'],
            ]
            as $pivot => $other
        ) {
            [$otherCol, $otherTable] = [
                array_key_first($other),
                $other[array_key_first($other)],
            ];

            Schema::table($pivot, function (Blueprint $table) use (
                $otherCol,
                $otherTable,
            ) {
                $table->dropForeign(['operator_id']);
                $table->dropForeign([$otherCol]);
                $table
                    ->foreign('operator_id')
                    ->references('id')
                    ->on('operators');
                $table->foreign($otherCol)->references('id')->on($otherTable);
            });
        }
    }
};
