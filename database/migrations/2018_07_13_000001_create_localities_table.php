<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateLocalitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'localities',
            function (Blueprint $table) {
                $table->increments('id');
                $table->char('zip_code', 5);
                $table->char('municipio_id', 5);
                $table->string('locality');
                $table->char('population_unit_code', 7);
                $table->string('singular_entity_name');
                $table->string('population');
                $table->integer('province_id')->unsigned();
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->timestamp('updated_at')->nullable()->default(null);

                $table->foreign('province_id')->references('id')->on('provinces')->onDelete('restrict');
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('localities');
    }
}
