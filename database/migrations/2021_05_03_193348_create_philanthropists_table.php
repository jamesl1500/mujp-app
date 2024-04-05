<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePhilanthropistsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('philanthropists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('created_by')->references('id')->on('users');
            $table->enum('status', ['active', 'passive', 'pending'])->default('pending');
            $table->string('firstname', 128);
            $table->string('lastname', 128);
            $table->string('city_of_birth_other')->nullable();
            $table->smallInteger('year_of_birth')->nullable();;
            $table->tinyInteger('month_of_birth')->nullable();;
            $table->tinyInteger('date_of_birth')->nullable();;
            $table->smallInteger('jewish_year_of_birth')->nullable();
            $table->tinyInteger('jewish_month_of_birth')->nullable();
            $table->tinyInteger('jewish_date_of_birth')->nullable();
            $table->smallInteger('year_of_death')->nullable();
            $table->tinyInteger('month_of_death')->nullable();
            $table->tinyInteger('date_of_death')->nullable();
            $table->smallInteger('jewish_year_of_death')->nullable();
            $table->tinyInteger('jewish_month_of_death')->nullable();
            $table->tinyInteger('jewish_date_of_death')->nullable();
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
        Schema::dropIfExists('philanthropists');
    }
}
