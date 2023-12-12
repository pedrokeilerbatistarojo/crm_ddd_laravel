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
        Schema::create('gym_fee_types', static function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('price');
            $table->string('period_type');
            $table->integer('payment_day');
            $table->integer('biweekly_payment_day')->nullable();
            $table->string('hour_from');
            $table->string('hour_to');
            $table->boolean('monday_access')->default(false);
            $table->boolean('tuesday_access')->default(false);
            $table->boolean('wednesday_access')->default(false);
            $table->boolean('thursday_access')->default(false);
            $table->boolean('friday_access')->default(false);
            $table->boolean('saturday_access')->default(false);
            $table->boolean('sunday_access')->default(false);
            $table->boolean('unlimited_access')->default(false);
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
        Schema::dropIfExists('gym_fee_types');
    }
};
