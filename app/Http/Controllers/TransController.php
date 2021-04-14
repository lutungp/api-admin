<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TransController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function getData(Request $request)
    {
        return response()->json("hy");
    }
}