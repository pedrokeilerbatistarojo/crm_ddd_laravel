<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('treatment_reservations_order_details', static function (Blueprint $table) {
            $table->unsignedBigInteger('id');
            $table->unsignedBigInteger('order_detail_id');
            $table->timestamps();

            $table->foreign('id', 'treatment_reservations_order_details_id_foreign')
                ->references('id')
                ->on('treatment_reservations')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreign('order_detail_id', 'treatment_reservations_order_details_order_detail_id_foreign')
                ->references('id')
                ->on('order_details')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->unique(['id', 'order_detail_id'], 'treatment_reservation_order_detail_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('treatment_reservations_order_details');
    }
};
