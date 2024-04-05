<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('key')->unique();
            $table->string('name');
            $table->string('description');
            $table->timestamps();
        });

        DB::table('roles')->insert(array(
            array('key' => 'admin', 'name' => 'Admin', 'description' => 'Admin role.'),
            array('key' => 'data-entry', 'name' => 'Data Entry', 'description' => 'Data entry role.'),
            array('key' => 'member', 'name' => 'Member', 'description' => 'Member role.')
        ));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roles');
    }
}
