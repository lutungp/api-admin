<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Routes;
use App\Models\Roles;
use DB;
use Illuminate\Support\Facades\Validator;

class RolesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function getRoles(Request $request)
    {
        $data["items"] = Roles::getActive();
        return response()->json($data);
    }

    public function getRoutes(Request $request)
    {
        $routes = Routes::where("route_aktif", "y")->get()->toArray();
        $dataRoutes = [];

        $lv1 = array_filter($routes, function ($var) {
            return $var["route_level"] == 1;
        });

        $lv2 = array_filter($routes, function ($var) {
            return $var["route_level"] == 2;
        });

        foreach ($lv1 as $key => $value) {
            $route_id = $value["route_id"];
            $dataChildren = array_filter($lv2, function ($var) use ($route_id) {
                return $var["s_route_id"] == $route_id;
            });

            $children = [];
            foreach ($dataChildren as $key2 => $value2) {
                $children[] = [
                    "route_id"    => $value2["route_id"],
                    "route_level" => $value2["route_level"],
                    "path"  => $value2["route_path"],
                    "meta" => [
                        "title"     => $value2["route_title"],
                        "hidden"    => $value2["route_hidden"] == "y" ? true : false,
                        "alwaysShow" => $value2["route_alwaysshow"] == "y" ? true : false,
                    ]
                ];
            }

            $dataRoutes[] = [
                "route_id"    => $route_id,
                "route_level" => $value["route_level"],
                "path"  => $value["route_path"],
                "meta" => [
                    "title"     => $value["route_title"],
                    "hidden"    => $value["route_hidden"] == "y" ? true : false,
                    "alwaysShow" => $value["route_alwaysshow"] == "y" ? true : false,
                ],
                "children" => $children
            ];
        }
        $data["routes"] = $dataRoutes;
        return response()->json($data);
    }

    public function createRoles(Request $request)
    {
        $input = $request["role"];

        $validator = Validator::make($input, [
            'role_nama' => 'required'
        ]);

        if ($validator->fails()) {
            return \Response::json(array(
                'status' => 'failure',
                'message' => $validator->getMessageBag()->toArray()
            ), 400);
        }
        
        $dataSave = new Roles();

        $dataSave->role_nama = $input["role_nama"];
        $dataSave->role_keterangan = $input["role_keterangan"];
        $dataSave->created_by = auth()->user()->user_id;
        $dataSave->created_date = date("Y-m-d H:i:s");
        $dataSave->save();
        $dataSave->role_id = $dataSave->role_id;

        return response()->json($dataSave);
    }

    public function updateRoles(Request $request)
    {
        $input = $request["role"];

        $validator = Validator::make($input, [
            'role_nama' => 'required'
        ]);

        if ($validator->fails()) {
            return \Response::json(array(
                'status' => 'failure',
                'message' => $validator->getMessageBag()->toArray()
            ), 400);
        }
        
        $role_id = $input["role_id"];
        $dataSave = Roles::find($role_id);

        $dataSave->role_nama = $input["role_nama"];
        $dataSave->role_keterangan = $input["role_keterangan"];
        $dataSave->updated_by = auth()->user()->user_id;
        $dataSave->updated_date = date("Y-m-d H:i:s");
        $dataSave->revised = DB::raw("revised+1");

        $dataSave->save();

        $dataSave->role_id = $role_id;
        return response()->json($dataSave);
    }

}