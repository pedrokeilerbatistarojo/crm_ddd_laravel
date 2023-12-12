<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateProvincesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'provinces',
            function (Blueprint $table) {
                $table->increments('id');
                $table->string('name');
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->timestamp('updated_at')->nullable()->default(null);
            }
        );

        DB::statement("
            INSERT INTO `provinces` (`id`, `name`, `created_at`, `updated_at`)
            VALUES
                (1, 'Alava', '2022-02-03 20:34:23', NULL),
                (2, 'Albacete', '2022-02-03 20:34:23', NULL),
                (3, 'Alicante', '2022-02-03 20:34:23', NULL),
                (4, 'Almeria', '2022-02-03 20:34:23', NULL),
                (5, 'Avila', '2022-02-03 20:34:23', NULL),
                (6, 'Badajoz', '2022-02-03 20:34:23', NULL),
                (7, 'Baleares', '2022-02-03 20:34:23', NULL),
                (8, 'Barcelona', '2022-02-03 20:34:23', NULL),
                (9, 'Burgos', '2022-02-03 20:34:23', NULL),
                (10, 'Caceres', '2022-02-03 20:34:23', NULL),
                (11, 'Cadiz', '2022-02-03 20:34:23', NULL),
                (12, 'Castellon', '2022-02-03 20:34:23', NULL),
                (13, 'Ciudad Real', '2022-02-03 20:34:23', NULL),
                (14, 'Córdoba', '2022-02-03 20:34:23', NULL),
                (15, 'La Coruña', '2022-02-03 20:34:23', NULL),
                (16, 'Cuenca', '2022-02-03 20:34:23', NULL),
                (17, 'Gerona', '2022-02-03 20:34:23', NULL),
                (18, 'Granada', '2022-02-03 20:34:23', NULL),
                (19, 'Guadalajara', '2022-02-03 20:34:23', NULL),
                (20, 'Guipuzcoa', '2022-02-03 20:34:23', NULL),
                (21, 'Huelva', '2022-02-03 20:34:23', NULL),
                (22, 'Huesca', '2022-02-03 20:34:23', NULL),
                (23, 'Jaen', '2022-02-03 20:34:23', NULL),
                (24, 'León', '2022-02-03 20:34:23', NULL),
                (25, 'Lerida', '2022-02-03 20:34:23', NULL),
                (26, 'Rioja (La)', '2022-02-03 20:34:23', NULL),
                (27, 'Lugo', '2022-02-03 20:34:23', NULL),
                (28, 'Madrid', '2022-02-03 20:34:23', NULL),
                (29, 'Malaga', '2022-02-03 20:34:23', NULL),
                (30, 'Murcia', '2022-02-03 20:34:23', NULL),
                (31, 'Navarra', '2022-02-03 20:34:23', NULL),
                (32, 'Orense', '2022-02-03 20:34:23', NULL),
                (33, 'Asturias', '2022-02-03 20:34:23', NULL),
                (34, 'Palencia', '2022-02-03 20:34:23', NULL),
                (35, 'Palmas (Las)', '2022-02-03 20:34:23', NULL),
                (36, 'Pontevedra', '2022-02-03 20:34:23', NULL),
                (37, 'Salamanca', '2022-02-03 20:34:23', NULL),
                (38, 'S.C.Tenerife', '2022-02-03 20:34:23', NULL),
                (39, 'Cantabria', '2022-02-03 20:34:23', NULL),
                (40, 'Segovia', '2022-02-03 20:34:23', NULL),
                (41, 'Sevilla', '2022-02-03 20:34:23', NULL),
                (42, 'Soria', '2022-02-03 20:34:23', NULL),
                (43, 'Tarragona', '2022-02-03 20:34:23', NULL),
                (44, 'Teruel', '2022-02-03 20:34:23', NULL),
                (45, 'Toledo', '2022-02-03 20:34:23', NULL),
                (46, 'Valencia', '2022-02-03 20:34:23', NULL),
                (47, 'Valladolid', '2022-02-03 20:34:23', NULL),
                (48, 'Vizcaya', '2022-02-03 20:34:23', NULL),
                (49, 'Zamora', '2022-02-03 20:34:23', NULL),
                (50, 'Zaragoza', '2022-02-03 20:34:23', NULL),
                (51, 'Ceuta', '2022-02-03 20:34:23', NULL),
                (52, 'Melilla', '2022-02-03 20:34:23', NULL);
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('provinces');
    }
}
