<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->smallInteger('duration_treatment_schedule')->unsigned()->nullable()->after('all_reserves_on_same_day');
            $table->smallInteger('duration_circuit_schedule')->unsigned()->nullable()->after('duration_treatment_schedule');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['duration_treatment_schedule', 'duration_circuit_schedule']);
        });
    }
};
