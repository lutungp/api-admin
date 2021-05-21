<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\JWTAuth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

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
        $input = $request->only('name', 'password');
        $name = $input["name"];
        //validate incoming request
        $validator = Validator::make($input, [
            'name' => 'required|exists:users,name,user_aktif,y',
            'password' => 'required|string|min:3',
        ], [
            'name.required' => 'Username tidak boleh kosong',
            'name.exist' => 'Username tidak dikenali',
            'password.required' => 'Password tidak boleh kosong',
            'password.min' => 'Password minimal 3 karakter'
        ]);

        if ($validator->fails()) {
            return response()->json(array(
                'status' => 'failure',
                'message' => $validator->getMessageBag()->toArray()
            ), 400);
        }

        $credentials = $request->only(['name', 'password']);

        if (! $token = Auth::attempt($credentials)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // return $this->respondWithToken($token);
        $dataAuth['token'] = $token;
        $dataAuth['socktoken'] = Str::random(15);
        return response()->json($dataAuth, 200);
    }

    public function info(Request $request)
    {
        $user = auth()->user();
        $dataUser = User::find($user->user_id);
        $userinfo = $dataUser->info;
        $userrole = $dataUser->role;

        // $userpermission = $dataUser->role->permission;
        $dataPermission = [];
        $parent = [];
        // foreach ($userpermission as $key => $value) {
        //     $parent[] = $value->read == 'y' ? $value->routes->s_route_id : 0;
        //     $dataPermission[] = [
        //         "sRouteId" => $value->s_route_id,
        //         "path" => $value->routes->route_path,
        //         "create" => $value->create,
        //         "read" => $value->read,
        //         "update" => $value->update,
        //         "delete" => $value->delete,
        //         "permission1" => $value->permission_1,
        //         "permission2" => $value->permission_2,
        //         "permission3" => $value->permission_3,
        //         "permission4" => $value->permission_4,
        //     ];
        // }

        $dataPermission = array_map(function ($var) use ($parent)
                            {
                                $route_id = $var["sRouteId"];
                                if (in_array($route_id, $parent)) {
                                    $var["create"] = 'y';
                                    $var["read"] = 'y';
                                    $var["update"] = 'y';
                                    $var["delete"] = 'y';
                                }

                                return $var;
                            }, $dataPermission);

        $data["data"] = [
            "role"         => $userrole->role_nama,
            "name"         => isset($userinfo->userinfo_nama) ? $userinfo->userinfo_nama : "",
            "avatar"       => "",
            "introduction" => "",
            "email"        => isset($userinfo->userinfo_email) ? $userinfo->userinfo_email : "",
            "permission"   => $dataPermission
        ];

        return response()->json($data);
    }

    public function logout(Request $request)
    {
        $this->jwt->parseToken()->invalidate();

        return ['message'=>'token removed'] ;
    }

}
