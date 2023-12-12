<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('cif');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('address');
            $table->string('zip_code');
            $table->string('locality');
            $table->string('province');
            $table->timestamps();
        });

        \Domain\Companies\Models\Company::query()->create([
            'name' => 'Balneario Thermas de Griñon',
            'cif' => 'B83720698',
            'email' => 'info@thermasdegrinon.com',
            'phone' => '+34 918 103 526',
            'address' => 'Ctra. De Torrejón de la Calzada a Griñón Km 3.200',
            'zip_code' => '28971',
            'locality' => 'Griñón',
            'province' => 'Madrid',
        ]);

        \Domain\Companies\Models\Company::query()->create([
            'name' => 'JOSÉ LUIS LÓPEZ SIMÓN',
            'cif' => '05872364G',
            'email' => 'info@thermasdegrinon.com',
            'phone' => '+34 918 103 526',
            'address' => 'Ctra. De Torrejón de la Calzada a Griñón Km 3.200',
            'zip_code' => '28971',
            'locality' => 'Griñón',
            'province' => 'Madrid',
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
