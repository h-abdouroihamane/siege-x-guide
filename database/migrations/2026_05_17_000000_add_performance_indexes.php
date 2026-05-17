<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Hot filter columns queried on every gadget/operator page.
        Schema::table('operators', function (Blueprint $table) {
            $table->index('side');
        });

        Schema::table('secondary_gadgets', function (Blueprint $table) {
            $table->index('side');
        });

        // operator_squad.rank is range-queried during squad reordering.
        Schema::table('operator_squad', function (Blueprint $table) {
            $table->index('rank');
        });

        // Operator::rework() is hasOne — one rework row per operator.
        Schema::table('operator_rework', function (Blueprint $table) {
            $table->unique('operator_id');
        });
    }

    public function down(): void
    {
        Schema::table('operators', function (Blueprint $table) {
            $table->dropIndex(['side']);
        });

        Schema::table('secondary_gadgets', function (Blueprint $table) {
            $table->dropIndex(['side']);
        });

        Schema::table('operator_squad', function (Blueprint $table) {
            $table->dropIndex(['rank']);
        });

        Schema::table('operator_rework', function (Blueprint $table) {
            $table->dropUnique(['operator_id']);
        });
    }
};
