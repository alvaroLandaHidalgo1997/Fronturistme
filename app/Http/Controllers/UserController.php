<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Routing\Controller as BaseController;
use Firebase\JWT\JWT;

class UserController extends Controller
{
    
	public function store(Request $request)
	{
	
        
		$user = new User();
        $key = $this->key;

        if ($request->name==null or $request->email==null or $request->password==null or $request->passwordConfirm==null) 
        {
            return (204); // No content
        }   
        if (strlen($password = $_POST['password']) < 8)
        {
            return response(411); //Length required
        } 

        $users = User::where('email', $request->email)->get();
        foreach ($users as $key => $value) 
        {
            if ($request->email == $value->email) 
            {
                return response()->json([
                    'ERROR' => 'The email is in use'
                ]);
            }
        }


        if (isset($_POST['email']) == true && empty($_POST['email']) == false)
        {
            $email = $_POST['email'];
            if(filter_var($email, FILTER_VALIDATE_EMAIL) == true)
            {
               // ok
            }else
            {
             return response(415); //invalid email
            }
        }

        if($_POST["passwordConfirm"] == $_POST["password"])
        {  
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = encrypt($request->password);

            $user->passwordConfirm = encrypt($request->passwordConfirm);        
            $user->role_id = 2;
            $user->save();
            
            $tokenParams = [ 
            'user' => $user,
            ];

            $token = JWT::encode($tokenParams, $key);
            return response()->json([
               'token' => $token,
            ]);
        
        }

        else
        {
            return response()->json([
                    'ERROR' => 'No se ha podido crear el usuario'
                ]);

		}

	}
}

