<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateCountriesStatesCitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $countries = realpath(__DIR__.'/../queries/countries.sql');
        $states = realpath(__DIR__.'/../queries/states.sql');
        $cities = realpath(__DIR__.'/../queries/cities.sql');
        DB::unprepared( file_get_contents($countries) );
        DB::unprepared( file_get_contents($states) );
        DB::unprepared( file_get_contents($cities) );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cities');
        Schema::dropIfExists('states');
        Schema::dropIfExists('countries');
    }
}
