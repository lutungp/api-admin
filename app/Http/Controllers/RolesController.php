<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Routes;

class RolesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function getRoles(Request $request)
    {
        return response()->json("hy");
    }

    public function getRoutes(Request $request)
    {
        $data["route"] = Routes::all();
        return response()->json($data);
    }

}