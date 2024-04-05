<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePhilanthropistAssociatedPeoplesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('philanthropist_associated_peoples', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('philanthropist_id');
            $table->foreign('philanthropist_id','fk_ph_id')->references('id')->on('philanthropists');
            $table->unsignedBigInteger('associated_philanthropist_id')->nullable();
            $table->foreign('associated_philanthropist_id','fk_as_ph_id')->nullable()->references('id')->on('philanthropists');
            $table->foreignId('created_by')->references('id')->on('users');
            $table->string('firstname', 128)->nullable();
            $table->string('lastname', 128)->nullable();
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
        Schema::dropIfExists('philanthropist_associated_peoples');
    }
}
