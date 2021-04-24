<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use DB;
use Illuminate\Support\Facades\Validator;

class BarangController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getBarang(Request $request)
    {
        $data["items"] = Barang::getActive();
        return response()->json($data);
    }
}
