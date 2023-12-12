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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('counter_sale_seq', 255)->nullable()->after('total_price')->change();
            $table->boolean('used_purchase')->default(false)->after('counter_sale_seq');
            $table->string('note', 255)->nullable()->after('used_purchase');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->integer('counter_sale_seq')->nullable()->after('total_price')->change();
            $table->dropColumn('used_purchase');
            $table->dropColumn('note');
        });
    }
};
