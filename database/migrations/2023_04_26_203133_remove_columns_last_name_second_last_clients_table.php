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
        $clients = \Illuminate\Support\Facades\DB::table('clients')->get();
        foreach ($clients as $client) {
            $name = $client->first_name;

            if ($client->last_name) {
                $name .= ' ' . $client->last_name;
            }

            if ($client->second_last_name) {
                $name .= ' ' . $client->second_last_name;
            }

            \Illuminate\Support\Facades\DB::table('clients')
                ->where('id', $client->id)->update([
                    'first_name' => $name
                ]);
        }
        Schema::table('clients', function (Blueprint $table) {
            $table->renameColumn('first_name', 'name');
            $table->dropColumn('last_name');
            $table->dropColumn('second_last_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->renameColumn('name', 'first_name');
            $table->string('last_name')->nullable();
            $table->string('second_last_name')->nullable();
        });

        $clients = \Illuminate\Support\Facades\DB::table('clients')->get();
        foreach ($clients as $client) {
            $name = explode(' ', $client->first_name);
            $client->first_name = $name[0];
            $client->last_name = $name[1];

            if (isset($name[2])) {
                $client->second_last_name = $name[2];
            }

            \Illuminate\Support\Facades\DB::table('clients')
                ->where('id', $client->id)->update([
                    'first_name' => $client->first_name,
                    'last_name' => $client->last_name,
                    'second_last_name' => $client->second_last_name,
                ]);
        }
    }
};
