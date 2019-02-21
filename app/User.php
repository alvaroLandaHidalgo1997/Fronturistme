<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
   
   protected $table = 'users';

    protected $fillable = [
        'name', 'email', 'password', 'passwordConfirm', 'role_id'];


    public function places(){

        return $this->hasMany('App\Place');
    }

    public function rol(){
			return $this->belongsTo('App/Role');
	}
}