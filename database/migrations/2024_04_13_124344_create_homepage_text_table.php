<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHomepageTextTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('homepage_text', function (Blueprint $table) {
            $table->id();

            // Section One
            $table->longText('first_section_small_pretitle')->nullable();
            $table->longText('first_section_main_title')->nullable();
            $table->longText('first_section_small_subtitle')->nullable();
            $table->longText('first_section_main_text')->nullable();

            // Section two
            $table->longText('second_section_main_title')->nullable();
            $table->longText('second_section_subtitle')->nullable();

            // Section 3
            $table->longText('third_section_small_pretitle')->nullable();
            $table->longText('third_section_main_title')->nullable();
            $table->longText('third_section_small_subtitle')->nullable();
            $table->longText('third_section_main_text')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('homepage_text');
    }
}
