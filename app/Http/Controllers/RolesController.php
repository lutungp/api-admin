<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Routes;
use App\Models\Roles;
use App\Models\Permission;
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

    public function getPermission($id)
    {
        $permission = Permission::where("s_role_id", $id)->get()->toArray();

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

            $dataChildren = array_values($dataChildren);
            $children = [];
            foreach ($dataChildren as $key2 => $value2) {
                $route_id2 = $value2["route_id"];
                $permissionExist = array_filter($permission, function ($var) use ($route_id2)
                {
                    return $var["s_route_id"] == $route_id2;
                });

                $create  = "t";
                $read  = "t";
                $update  = "t";
                $delete  = "t";
                $permission_id = 0;
                if (!empty($permissionExist)) {
                    $permissionExist = array_values($permissionExist);
                    $permissionExist = $permissionExist[0];

                    $create = $permissionExist["create"];
                    $read = $permissionExist["read"];
                    $update = $permissionExist["update"];
                    $delete = $permissionExist["delete"];
                    $permission_id = $permissionExist["permission_id"];
                }

                $children[] = [
                    "permission_id" => $permission_id,
                    "route_id"    => $value2["route_id"],
                    "route_level" => $value2["route_level"],
                    "path"        => $value2["route_path"],
                    "s_route_id"  => $value2["s_route_id"],
                    "create" => $create == "y" ? true : false,
                    "read"   => $read == "y" ? true : false,
                    "update" => $update == "y" ? true : false,
                    "delete" => $delete == "y" ? true : false,
                    "meta" => [
                        "title"     => $value2["route_title"],
                        "hidden"    => $value2["route_hidden"] == "y" ? true : false,
                        "alwaysShow" => $value2["route_alwaysshow"] == "y" ? true : false,
                    ],
                    "children" => []
                ];
            }

            $permissionExist = array_filter($permission, function ($var) use ($route_id)
            {
                return $var["s_route_id"] == $route_id;
            });

            $create  = "t";
            $read  = "t";
            $update  = "t";
            $delete  = "t";
            $permission_id = 0;
            if (!empty($permissionExist)) {
                $permissionExist = array_values($permissionExist);
                $permissionExist = $permissionExist[0];

                $create = $permissionExist["create"];
                $read = $permissionExist["read"];
                $update = $permissionExist["update"];
                $delete = $permissionExist["delete"];
                $permission_id = $permissionExist["permission_id"];
            }

            $dataRoutes[] = [
                "permission_id" => $permission_id,
                "route_id"    => $route_id,
                "route_level" => $value["route_level"],
                "path"  => $value["route_path"],
                "create" => $create == "y" ? true : false,
                "read"   => $read == "y" ? true : false,
                "update" => $update == "y" ? true : false,
                "delete" => $delete == "y" ? true : false,
                "meta" => [
                    "title"     => $value["route_title"],
                    "hidden"    => $value["route_hidden"] == "y" ? true : false,
                    "alwaysShow" => $value["route_alwaysshow"] == "y" ? true : false,
                ],
                "children" => $children
            ];
        }

        return response()->json($dataRoutes);
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
                    "permission_id" => 0,
                    "route_id"    => $value2["route_id"],
                    "route_level" => $value2["route_level"],
                    "path"  => $value2["route_path"],
                    "s_route_id" => $value2["s_route_id"],
                    "create" => false,
                    "read"   => false,
                    "update" => false,
                    "delete" => false,
                    "meta" => [
                        "title"     => $value2["route_title"],
                        "hidden"    => $value2["route_hidden"] == "y" ? true : false,
                        "alwaysShow" => $value2["route_alwaysshow"] == "y" ? true : false,
                    ],
                    "children" => []
                ];
            }

            $dataRoutes[] = [
                "permission_id" => 0,
                "route_id"    => $route_id,
                "route_level" => $value["route_level"],
                "path"  => $value["route_path"],
                "create" => false,
                "read"   => false,
                "update" => false,
                "delete" => false,
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

        try {
            $dataSave = new Roles();

            $dataSave->role_nama = $input["role_nama"];
            $dataSave->role_keterangan = $input["role_keterangan"];
            $dataSave->created_by = auth()->user()->user_id;
            $dataSave->created_date = date("Y-m-d H:i:s");
            $dataSave->save();
            $role_id = $dataSave->role_id;

            $routes = $input["routes"];
            $this->setPermission($role_id, $routes);

        } catch (\Throwable $th) {
            $res["status"] = "failure";
            return response()->json($res, 500);
        }


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

        try {
            $dataSave->role_nama = $input["role_nama"];
            $dataSave->role_keterangan = $input["role_keterangan"];
            $dataSave->updated_by = auth()->user()->user_id;
            $dataSave->updated_date = date("Y-m-d H:i:s");
            $dataSave->revised = DB::raw("revised+1");

            $dataSave->save();

            $dataSave->role_id = $role_id;

            $routes = $input["routes"];
            $this->setPermission($role_id, $routes);
        } catch (\Throwable $th) {
            $res["status"] = "failure";
            return response()->json($res, 500);
        }

        return response()->json($routes);
    }

    public function setPermission($role_id, $routes)
    {
        Permission::where("s_role_id", $role_id)->delete();
        foreach ($routes as $key => $value) {
                $permission = new Permission();
                $permission->s_role_id  = $role_id;
                $permission->s_route_id = $value["route_id"];
                $permission->created_by = auth()->user()->user_id;
                $permission->created_date = date("Y-m-d H:i:s");
                $permission->create = $value["create"] ? "y" : "t";
                $permission->read = "y";
                $permission->update = "y";
                $permission->delete = "y";
                $permission->save();

            $children = $value["children"];
            foreach ($children as $key2 => $value2) {
                $permission = new Permission();
                $permission->s_role_id  = $role_id;
                $permission->s_route_id = $value2["route_id"];
                $permission->created_by = auth()->user()->user_id;
                $permission->created_date = date("Y-m-d H:i:s");
                $permission->create = $value["create"] ? "y" : "t";
                $permission->read = $value["read"] ? "y" : "t";
                $permission->update = $value["update"] ? "y" : "t";
                $permission->delete = $value["delete"] ? "y" : "t";
                $permission->save();
            }
        }
    }

    public function deleteRoles($id)
    {
        $res["status"] = "success";
        try {
            $role = Roles::find($id);
            $role->role_aktif = 't';
            $user->disabled_by = auth()->user()->user_id;
            $bahan->disabled_date = date("Y-m-d H:i:s");
            $role->save();
        } catch (\Throwable $th) {
            $res["status"] = "failure";
            return response()->json($res, 500);
        }

        return response()->json($res);
    }

}
