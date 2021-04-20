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
        $data["users"] = User::all();
        return response()->json($data);
    }
}