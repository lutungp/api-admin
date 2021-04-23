<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getUsers(Request $request)
    {
        $users = User::paginate(20);
        $dataUser  = [];
        foreach ($users->items() as $key => $value) {
            $dataUser[] = [
                "user_id" => $value->user_id,
                "user_kode" => $value->user_kode,
                "name" => $value->name,
                "s_role_id" => $value->s_role_id,
                "role_nama" => $value->role->role_nama
            ];
        }

        $data["users"] = $dataUser;
        $data["total"] = $users->total();

        return response()->json($data);
    }

    public function createUsers(Request $request)
    {
        $input = $request["user"];

        $validator = Validator::make($input, [
            'name' => 'required|min:3|max:100|unique:users,name',
            'password' => 'required|min:3',
        ], [
            'name.required' => 'Nama tidak boleh kosong',
            'password.required' => 'Password tidak boleh kosong',
            'password.min' => 'Password minimal 3 karakter',
        ]);

        if ($validator->fails()) {
            return response()->json(array(
                'status' => 'failure',
                'message' => $validator->getMessageBag()->toArray()
            ), 400);
        }

        $res["status"] = "success";
        // try {
            $dataSave = new User();

            $dataSave->user_kode = $input["user_kode"];
            $dataSave->name = $input["name"];
            $dataSave->password = Hash::make($input["password"]);
            $dataSave->s_role_id = $input["s_role_id"];
            $dataSave->created_by = auth()->user()->user_id;
            $dataSave->created_date = date("Y-m-d H:i:s");
            $dataSave->save();
            $user_id = $dataSave->user_id;

        // } catch (\Throwable $th) {
        //     $res["status"] = "failure";
        //     return response()->json($res, 500);
        // }

        return response()->json($dataSave);
    }

    public function updateUsers(Request $request)
    {
        $input = $request["user"];

        $user_id = $input["user_id"];
        $validator = Validator::make($input, [
            'name' => 'required|min:3|max:100|unique:users,name,' . $user_id . ',user_id',
            'password' => 'required|min:3',
        ], [
            'name.required' => 'Nama tidak boleh kosong',
            'password.required' => 'Password tidak boleh kosong',
            'password.min' => 'Password minimal 3 karakter',
        ]);

        if ($validator->fails()) {
            return response()->json(array(
                'status' => 'failure',
                'message' => $validator->getMessageBag()->toArray()
            ), 400);
        }

        $dataSave = User::find($user_id);

        $res["status"] = "success";
        try {
            $dataSave->user_kode = $input["user_kode"];
            $dataSave->name = $input["name"];
            $dataSave->password = Hash::make($input["password"]);
            $dataSave->s_role_id = $input["s_role_id"];
            $dataSave->updated_by = auth()->user()->user_id;
            $dataSave->updated_date = date("Y-m-d H:i:s");
            $dataSave->revised = DB::raw("revised+1");

            $dataSave->save();
        } catch (\Throwable $th) {
            $res["status"] = "failure";
            return response()->json($res, 500);
        }

        return response()->json($res);
    }
}
