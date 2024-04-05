<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBiographyColumnToPhilanthropistsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('philanthropists', function (Blueprint $table) {
            $table->text('biography')->nullable()->after('jewish_date_of_death');
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
            //
        });
    }
}
