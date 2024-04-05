<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePhilanthropistInstitutionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('philanthropist_institutions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('philanthropist_id')->references('id')->on('philanthropists');
            $table->foreignId('institution_id')->nullable()->references('id')->on('institutions');
            $table->foreignId('institution_role_id')->references('id')->on('institution_roles');
            $table->foreignId('institution_type_id')->references('id')->on('institution_types');
            $table->unsignedMediumInteger('city_id')->nullable();
            $table->foreign('city_id')->references('id')->on('cities');
            $table->foreignId('created_by')->references('id')->on('users');
            $table->string('institution_other')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('philanthropist_institutions');
    }
}
