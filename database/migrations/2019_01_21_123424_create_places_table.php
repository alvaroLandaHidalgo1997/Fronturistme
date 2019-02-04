<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlacesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('places', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('description');
            $table->string('startDate');
            $table->string('endDate');
            $table->double('coordX');
            $table->double('coordY');
            $table->integer('user_id')->unsigned();
            $table->timestamps();
            $table->rememberToken();
        });
        Schema::table('places', function(Blueprint $table){
            $table->foreign('id')->references('id')->('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('places');
    }
}
