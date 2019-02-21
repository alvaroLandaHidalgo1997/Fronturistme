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
             return response()->json([
                    'message' => 'todos los campos deben de rellenarse', 'code' => 404
                ]);
        }   
        if (strlen($password = $_POST['password']) < 8)
        {
            return response()->json([
                    'message' => 'la contraseña debe tener al menos 8 caracteres','code'=>411
                ]);
        } 
        if(preg_match('/\s/',$request->name) == true){
            return response()->json([
                    'message' => 'el nombre no debe contener espacios', 'code'=>400
                ]);
        }
        $users = User::where('email', $request->email)->get();
        foreach ($users as $key => $value) 
        {
            if ($request->email == $value->email) 
            {
                return response()->json([
                    'message' => 'El correo introducido ya está en uso'
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
             return response()->json([
                    'message' => 'se debe introducir un correo válido'
                ]);
            }
        }

        if($_POST["passwordConfirm"] == $_POST["password"])
        {  
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = encrypt($request->password);

            $user->role_id = $request->role_id;
            $user->save();
            
            $tokenParams = [ 
            'user' => $user,
            ];

            $token = JWT::encode($tokenParams, $key);
            return response()->json([
               'token' => $token, 'message' => 'se ha registrado un usuario correctamente'
            ]);
        }
        else
        {
            return response()->json([
                    'message' => 'las contraseñas deben coincidir'
                ]);
		}
	}
    public function update(Request $request, User $user){

        $headers = getallheaders();
        $key = $this->key;
        $userData = JWT::decode($headers['Authorization'],$key, array('HS256'));

        if ($request->newname==null or $request->newEmail==null or $request->newPassword==null) 
        {
             return response()->json([
                    'message' => 'no has modificado ningún campo', 'code' => 404
                ]);
        } 
        if(!empty($request->newname && preg_match('/\s/',$request->newname) == false)){
            $user->name = $request->newname;
            $user->save();
            return response()->json([
                    'message' => 'se ha  actualizado el nombre correctamente', 'code'=>200
                ],200);
        }
        if(preg_match('/\s/',$request->newname) == true){
            return response()->json([
                    'message' => 'el nombre no debe contener espacios', 'code'=>200
                ]);
        }
        if(!empty($request->newPassword) && strlen($request->newPassword) >= 8 && isset($request->newPassword) == true)
        {
            $user->password = encrypt($request->newPassword);
            $user->save();
            return response()->json([
                    'message' => 'se ha cambiado la contraseña correctamente', 'code'=>200
                ],200);   
        }
        if(!empty($request->newPassword) && strlen($request->newPassword) < 8 && isset($request->newPassword) == true){
            return response()->json([
                    'message' => 'la contraseña debe tener al menos 8 caracteres','code'=>200
                ]);
        } 
        if (isset($request->newEmail) == true && empty($request->newEmail) == false)
        {
            if(filter_var($request->newEmail, FILTER_VALIDATE_EMAIL) == true)
            {
                $user->email = $request->newEmail;
                $user->save();
                return response()->json([
                    'message' => 'se ha actualizado el Email correctamente', 'code'=>200
                ],200);
            }
        }
        $user->save();
    }

    public function destroy($id)
    {
        
        $headers = getallheaders();
        $token = $headers['Authorization'];
        $key = $this->key;
        //$userData = JWT::decode($token, $key, array('HS256'));
        $user_id = $id;
        $id_user = User::where('id',$user_id)->first()->id;
        if (is_null($id_user)) {
                return $this->error(400,'el usuario no existe');
        }else{
                    $user_name = User::where('id',$id_user)->first();
                    $user_name->delete();
                return response()->json([
                    'message' => "usuario borrado",
                ], 200);
          }
        }

        public function index()
        {
        $header = getallheaders();
        $userParams = JWT::decode($header['Authorization'], $this->key, array('HS256'));
        if ($userParams->user->id == 1) {
            return User::where('role_id',2)->get();
        }else{
            return response()->json([
                'message' =>  "no tiene los permisos suficientes permisos", 403
            ]);
        }
        
    }
}

