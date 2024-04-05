<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCityOfBirthColumnToPhilanthropistsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('philanthropists', function (Blueprint $table) {
            $table->unsignedMediumInteger('city_of_birth')->nullable()->after('created_by');
            $table->foreign('city_of_birth')->references('id')->on('cities');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('philanthropists', function (Blueprint $table) {
            $table->dropForeign('philanthropists_city_of_birth_foreign');
            $table->dropColumn('city_of_birth');
        });
    }
}
