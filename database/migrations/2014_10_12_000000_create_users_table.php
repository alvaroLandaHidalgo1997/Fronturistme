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
            $table->integer('role_id');
            $table->string('name');
            $table->string('email', 50)->unique()->index();
            $table->string('password');
            $table->timestamps();
        });
       DB::table('users')->insert(
        ['name'=>'admin',
        'email'=>'admin@cev.com',
         'password'=>encrypt('12345678'),

         'role_id'=>1]
     );     
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
