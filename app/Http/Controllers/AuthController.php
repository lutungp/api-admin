<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use  App\User;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\JWTAuth;

class AuthController extends Controller
{
    /**
     * @var \Tymon\JWTAuth\JWTAuth
     */
    protected $jwt;

    public function __construct(JWTAuth $jwt)
    {
        $this->jwt = $jwt;
    }

    public function login(Request $request)
    {
          //validate incoming request 
        $this->validate($request, [
            'name' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only(['name', 'password']);

        if (! $token = Auth::attempt($credentials)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    public function info(Request $request)
    {
        $user = auth()->user();
        $data["user"] = [
            "roles" => ['admin'],
            "name"  => $user->name,
            "avatar" => "",
            "introduction" => "",
            "email" => "lntngp19@gmail.com"
        ];
        
        return response()->json($data);
    }

    public function logout(Request $request)
    {
        $this->jwt->parseToken()->invalidate();
		
        return ['message'=>'token removed'] ;
    }

}