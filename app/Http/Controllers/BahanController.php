<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bahan;
use DB;
use Illuminate\Support\Facades\Validator;

class BahanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getBahan(Request $request)
    {
        $data["items"] = Bahan::getActive();
        return response()->json($data);
    }
}
