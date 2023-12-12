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
        Schema::table('gym_subscription_quotas', function (Blueprint $table) {
            $table->foreignId('gym_subscription_id')->after('id')->constrained()->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('gym_subscription_quotas', function (Blueprint $table) {
            $table->dropColumn('gym_subscription_id');
        });
    }
};
