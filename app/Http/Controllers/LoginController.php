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

		if($_POST["email"] == null or $_POST["password"] == null)
			{
				return response(204);
			}

		$user = User::where('email', $_POST['email'])->first();

		if (empty($user))
		{

			return response(401); // mail no autorizado 
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
			return response(400); // Respuesta contraseña incorrecta
		}

	}
}
