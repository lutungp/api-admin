<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Activity;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getUsers(Request $request)
    {
        $filter = $request->get('filter');
        $role = $request->get('role');
        $users = User::where('user_aktif', 'y');
        if ($filter <> '') {
            $users = $users->whereRaw("UPPER(name) LIKE '%" . strtoupper($filter) . "%'");
        }

        $users = $users->paginate(20);
        $dataUser  = [];
        foreach ($users->items() as $key => $value) {
            if ($role == '') {
                $dataUser[] = [
                    "user_id"   => $value->user_id,
                    "user_kode" => $value->user_kode,
                    "name"      => $value->name,
                    "s_role_id" => $value->s_role_id,
                    "role_nama" => $value->role->role_nama,
                    "socktoken" => $value->socktoken,
                ];
            } else if ($value->role->role_nama == $role) {
                $dataUser[] = [
                    "user_id"   => $value->user_id,
                    "user_kode" => $value->user_kode,
                    "name"      => $value->name,
                    "s_role_id" => $value->s_role_id,
                    "role_nama" => $value->role->role_nama,
                    "socktoken" => $value->socktoken,
                ];
            }

        }

        $data["users"] = $dataUser;
        $data["total"] = $users->total();

        return response()->json($data);
    }

    public function createUsers(Request $request)
    {
        $input = $request->only('user_id', 'user_kode', 'name', 's_role_id', 'role_nama', 'password');
        $validator = Validator::make($input, [
            'name' => 'required|min:3|max:100|unique:users,name',
            's_role_id' => 'required',
            'password' => 'required|min:3',
        ], [
            'name.required' => 'Name cannot be empty',
            'name.min' => 'The name should be at least 3 characters and a maximum of 100 characters',
            'name.max' => 'The name should be at least 3 characters and a maximum of 100 characters',
            'name.unique' => 'The name ' . $input['name'] . ' is already using',
            's_role_id.required' => 'Role cannot be empty',
            'password.required' => 'Password cannot be empty',
            'password.min' => 'Password of at least 3 characters',
        ]);

        if ($validator->fails()) {
            return response()->json(array(
                'status' => 'failure',
                'message' => $validator->getMessageBag()->toArray()
            ), 400);
        }

        $res["status"] = "success";
        try {
            $dataSave = new User();

            $dataSave->user_kode = $input["user_kode"];
            $dataSave->name = $input["name"];
            $dataSave->password = Hash::make($input["password"]);
            $dataSave->s_role_id = $input["s_role_id"];
            $dataSave->socktoken = Str::random(20);

            $dataSave->created_by = auth()->user()->user_id;
            $dataSave->created_date = date("Y-m-d H:i:s");
            $dataSave->save();
            $user_id = $dataSave->user_id;

        } catch (\Throwable $th) {
            $res["status"] = "failure";
            return response()->json($res, 500);
        }

        return response()->json($dataSave);
    }

    public function updateUsers(Request $request)
    {
        $input = $request->only('user_id', 'user_kode', 'name', 's_role_id', 'role_nama', 'password');

        $user_id = $input["user_id"];
        $validator = Validator::make($input, [
            'name' => 'required|min:3|max:100|unique:users,name,' . $user_id . ',user_id',
            'password' => 'required|min:3',
        ], [
            'name.required' => 'Name cannot be empty',
            'name.min' => 'The name should be at least 3 characters and a maximum of 100 characters',
            'name.max' => 'The name should be at least 3 characters and a maximum of 100 characters',
            'name.unique' => 'The name ' . $input['name'] . ' is already using',
            's_role_id.required' => 'Role cannot be empty',
            'password.required' => 'Password cannot be empty',
            'password.min' => 'Password of at least 3 characters',
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

            if ($dataSave->socktoken == null) {
                $dataSave->socktoken = Str::random(20);
            }

            $dataSave->updated_by = auth()->user()->user_id;
            $dataSave->updated_date = date("Y-m-d H:i:s");
            $dataSave->revised = $dataSave->revised+1;

            $dataSave->save();
        } catch (\Throwable $th) {
            $res["status"] = "failure";
            return response()->json($res, 500);
        }

        return response()->json($res);
    }

    public function deleteUsers($id)
    {
        $res["status"] = "success";
        try {
            $user = User::find($id);
            $user->user_aktif = 't';
            $user->disabled_by = auth()->user()->user_id;
            $user->disabled_date = date("Y-m-d H:i:s");
            $user->save();
        } catch (\Throwable $th) {
            $res["status"] = "failure";
            return response()->json($res, 500);
        }

        return response()->json($res);
    }

    public function getActivity(Request $request)
    {
        $user = auth()->user();
        $data = Activity::where('m_user_id', $user->user_id)
                        ->where('activity_status', 'NEW')
                        ->get();

        return response()->json($data, 200);
    }
}
