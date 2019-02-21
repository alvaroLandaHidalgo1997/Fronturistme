<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Login;
use \Firebase\JWT\JWT;


class LoginController extends Controller
{
    
	public function login()
	{
		$key = $this->key;

		if($_POST["email"] == null or $_POST["password"] == null or $_POST["email"] == "" or $_POST["password"] == "" )
		{
			return response()->json([
                    'message' => 'todos los campos deben rellenarse', 'code' => 400
                ], 400);
		}
		$user = User::where('email', $_POST['email'])->first();

		if (empty($user))
		{
			return response()->json([
                    'message' => 'el email introducido no es valido', 'code' =>401
                ], 400); // mail no autorizado 
		}
		if($user->role_id == 2){
			return response()->json([
                    'message' => 'no tienes permisos de administrador'
                ],400);
			
		}
		if($_POST["password"] == decrypt($user->password))
		{
			$tokenParams = [
				'user' => $user,
				'random' => time()
			];
			$token = JWT::encode($tokenParams, $key);
			return response()->json([
				'token'=> $token,
			]);
		}
		else
		{
			return response()->json([
                    'message' => 'contraseña incorrecta', 'code' => 400
                ], 400);
		}

	}


}
