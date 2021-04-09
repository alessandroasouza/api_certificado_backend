<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use  App\Models\User;
use  App\Models\Access_tokens;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Collection as Collection;

/**
 * Class SubscriptionController
 * @package App\Http\Controllers
 */
class AuthController  extends BaseController
{
    public function store(Request $request)
    {
        //validate incoming request 
        $this->validate($request, [
            'nome' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'min:6|required_with:password_confirmation|same:password-confirm',
            'password-confirm' => 'min:6'
        ]);

        try {

            $user = new User;
            $user->nome = $request->nome;
            $user->email = $request->email;
            $user->apelido = $request->apelido;
            $user->tipo_usuario = $request->tipo_usuario;
            $user->celular = $request->celular;
            $user->documento = $request->documento;
            $user->campus = $request->campus;
            $plain_Password = $request->password;
            $user->password = app('hash')->make($plain_Password);

            $user->save();

            //return successful response
            return response()->json(['user' => $user, 'message' => 'CREATED'], 201);

        } catch (\Exception $e) {
            //return error message
           // die($e);
            return response()->json(['message' => 'User Registration Failed!'], 409);
        }

    }

    public function login(Request $request)
    {
          //validate incoming request 
        $this->validate($request, [
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only(['email', 'password']);

        if (! $token = Auth::attempt($credentials)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $id = Auth::id();
        $register_token = $this->respondWithToken($token); 
        
        
        $this->login_register($register_token,$id);
        
        return $register_token;
    }

    protected function login_register($register,$id)
    {
        try {
           
            $access_tokens             = new Access_tokens;
            $access_tokens->token      =  $register->getData()->token;
            $access_tokens->expire_at  =  $register->getData()->expires_in;
            $access_tokens->user_id    = $id;
            $access_tokens->save();
            
        } catch (\Exception $e) {
           
           return response()->json(['message' => 'User Registration Failed!'], 409);
        }

    }
        
    
    protected function respondWithToken($token)
    {
        return response()->json([
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 60
        ], 200);
    }
}
