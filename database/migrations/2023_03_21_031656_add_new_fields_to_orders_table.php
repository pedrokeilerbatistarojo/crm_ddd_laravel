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
            $table->integer('counter_sale_seq')->nullable()->after('total_price');
            $table->integer('telephone_sale_seq')->nullable()->after('total_price');
            $table->string('type')->nullable()->after('total_price');
            $table->string('ticket_number')->nullable()->after('total_price');
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
            $table->dropColumn([
                'ticket_number',
                'type',
                'telephone_sale_seq',
                'counter_sale_seq'
            ]);
        });
    }
};
