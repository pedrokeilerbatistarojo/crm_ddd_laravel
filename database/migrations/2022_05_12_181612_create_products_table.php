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
        Schema::create('products', static function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_type_id')->nullable()->constrained()->nullOnDelete();
            $table->string('sku')->nullable();
            $table->string('image')->nullable();
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('short_description')->nullable();
            $table->text('description')->nullable();
            $table->float('price')->default(0);
            $table->string('price_type')->default('Fijo');
            $table->unsignedTinyInteger('circuit_sessions')->default(0);
            $table->unsignedTinyInteger('treatment_sessions')->default(0);
            $table->boolean('online_sale')->default(true);
            $table->boolean('editable')->default(true);
            $table->boolean('available')->default(true);
            $table->unsignedBigInteger('priority');
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
        Schema::dropIfExists('products');
    }
};
