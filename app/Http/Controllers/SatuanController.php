<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Satuan;
use Illuminate\Support\Facades\Validator;

class SatuanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getSatuan(Request $request)
    {
        $data["items"] = Satuan::getActive();
        return response()->json($data);
    }
}
