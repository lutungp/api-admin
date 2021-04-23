<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function getData(Request $request)
    {
        $users = User::all();
        $dataUser  = [];
        foreach ($users as $key => $value) {
            $dataUser[] = [
                "user_id" => $value->user_id,
                "user_kode" => $value->user_kode,
                "name" => $value->name,
                "s_role_id" => $value->s_role_id,
                "role_nama" => $value->role->role_nama
            ];
        }

        $data["users"] = $dataUser;
        return response()->json($data);
    }
}