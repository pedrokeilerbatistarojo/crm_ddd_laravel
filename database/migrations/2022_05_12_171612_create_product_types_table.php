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
        Schema::create('product_types', static function (Blueprint $table) {
            $table->id();
            $table->enum(
                'category',
                ['General', 'Promociones', 'Masajes-Tratamientos', 'Belleza-Salud', 'Tratamientos-Premium', 'Circuitos']
            )->index();
            $table->string('name');
            $table->unsignedBigInteger('priority');
            $table->char('background_color', 7)->default('#0A427D');
            $table->char('text_color', 7)->default('#FFFFFF');
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
        Schema::dropIfExists('product_types');
    }
};
