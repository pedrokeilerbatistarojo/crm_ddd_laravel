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
        Schema::create('gym_subscription_member_access_rights', static function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id', 'fk_subscription_member')->constrained('gym_subscription_members')->restrictOnDelete();
            $table->date('date_from');
            $table->date('date_to');
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
        Schema::dropIfExists('gym_subscription_member_access_rights');
    }
};
