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
        Schema::create('gym_subscriptions', static function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->restrictOnDelete();
            $table->foreignId('gym_fee_type_id')->nullable()->constrained()->nullOnDelete();
            $table->string('gym_fee_type_name');
            $table->decimal('price');
            $table->date('activation_date');
            $table->date('start_date');
            $table->date('expiration_date');
            $table->integer('payment_day');
            $table->integer('biweekly_payment_day')->nullable();
            $table->string('payment_type');
            $table->foreignId('created_by')->nullable()->constrained('users')->restrictOnDelete();
            $table->foreignId('last_modified_by')->nullable()->constrained('users')->restrictOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('gym_subscriptions');
    }
};
