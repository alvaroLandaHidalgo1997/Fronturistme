<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('role_id')->unsigned();
            $table->string('name')->unique();
            $table->string('email', 50)->unique()->index();
            $table->string('password');
            $table->string('passwordConfirm');
            $table->timestamps();
        });
        Schema::table('users', function(Blueprint $table){
            $table->foreign('role_id')->references('id')->on('RolUser');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
