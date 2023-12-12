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
    public function up()
    {
        Schema::table('gym_fee_types', function (Blueprint $table) {
            $table->unsignedInteger('duration_number_of_days')->after('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('gym_fee_types', function (Blueprint $table) {
            $table->dropColumn('duration_number_of_days');
        });
    }
};
