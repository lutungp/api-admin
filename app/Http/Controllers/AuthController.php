<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use  App\Models\User;
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
        $dataUser = User::find($user->user_id);
        $userinfo = $dataUser->info;
        $userrole = $dataUser->role;

        $userpermission = $dataUser->role->permission;
        $dataPermission = [];
        foreach ($userpermission as $key => $value) {
            $dataPermission[] = [
                "sRouteId" => $value->s_route_id,
                "path" => $value->routes->route_path,
                "create" => $value->create,
                "read" => $value->read,
                "update" => $value->update,
                "delete" => $value->delete,
                "permission1" => $value->permission_1,
                "permission2" => $value->permission_2,
                "permission3" => $value->permission_3,
                "permission4" => $value->permission_4,
            ];
        }

        $data["user"] = [
            "roles" => ['admin'],
            "name"  => $userinfo->userinfo_nama,
            "avatar" => "",
            "introduction" => "",
            "email" => $userinfo->userinfo_email,
            "permission" => $dataPermission
        ];
        
        return response()->json($data);
    }

    public function logout(Request $request)
    {
        $this->jwt->parseToken()->invalidate();
		
        return ['message'=>'token removed'] ;
    }

}