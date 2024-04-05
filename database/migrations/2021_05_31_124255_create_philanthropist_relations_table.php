<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePhilanthropistRelationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('philanthropist_relations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('philanthropist_id')->references('id')->on('philanthropists');
            $table->foreignId('related_philanthropist_id')->nullable()->references('id')->on('philanthropists');
            $table->foreignId('relation_type_id')->references('id')->on('relation_types');
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
        Schema::dropIfExists('philanthropist_relations');
    }
}
