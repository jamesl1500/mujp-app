<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMostLivedLocationColumnsToPhilanthropistsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('philanthropists', function (Blueprint $table) {
            $table->unsignedMediumInteger('country_of_most_lived_in')->nullable()->after('city_of_birth');
            $table->foreign('country_of_most_lived_in')->references('id')->on('countries');
            $table->unsignedMediumInteger('state_of_most_lived_in')->nullable()->after('country_of_most_lived_in');
            $table->foreign('state_of_most_lived_in')->references('id')->on('states');
            $table->unsignedMediumInteger('city_of_most_lived_in')->nullable()->after('state_of_most_lived_in');
            $table->foreign('city_of_most_lived_in')->references('id')->on('cities');
            $table->string('city_of_most_lived_in_other')->after('city_of_birth_other')->nullable();
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
            $table->dropColumn('city_of_most_lived_in_other');
            $table->dropForeign('philanthropists_city_of_most_lived_in_foreign');
            $table->dropColumn('city_of_most_lived_in');
            $table->dropForeign('philanthropists_state_of_most_lived_in_foreign');
            $table->dropColumn('state_of_most_lived_in');
            $table->dropForeign('philanthropists_country_of_most_lived_in_foreign');
            $table->dropColumn('country_of_most_lived_in');
        });
    }
}
