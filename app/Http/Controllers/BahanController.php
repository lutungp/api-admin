<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bahan;
use Illuminate\Support\Facades\Validator;

class BahanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getBahan(Request $request)
    {
        $bahan = Bahan::where("bahan_aktif", "y")->paginate(20);
        $dataBahan  = [];
        foreach ($bahan->items() as $key => $value) {
            $dataBahan[] = [
                "bahan_id" => $value->bahan_id,
                "bahan_kode" => $value->bahan_kode,
                "bahan_nama" => $value->bahan_nama,
                "m_satuan_id" => $value->m_satuan_id,
                "satuan_nama" => $value->satuan->satuan_nama
            ];
        }

        $data["bahan"] = $dataBahan;
        $data["total"] = $bahan->total();

        return response()->json($data);
    }
}
