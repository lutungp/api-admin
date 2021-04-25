<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bahan;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

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

    public function createBahan(Request $request)
    {
        $input = $request["bahan"];
        $bahan_nama = $input["bahan_nama"];
        $validator = Validator::make($input, [
            'bahan_nama' => ['required', 'max:25',
                        Rule::unique('m_bahan')->where(function ($query) {
                            return $query->where('bahan_aktif', 'y');
                        })],
            'm_satuan_id' => 'required',
        ], [
            'bahan_nama.required' => 'Nama Bahan tidak boleh kosong',
            'bahan_nama.min' => 'Nama Bahan minimal 3 karakter',
            'bahan_nama.max' => 'Nama Bahan maksimal 100 karakter',
            'bahan_nama.unique' => 'Nama Bahan ' . $input['bahan_nama'] . ' telah digunakan, masukkan nama lain',
            'm_satuan_id.required' => 'Satuan tidak boleh kosong',
        ]);

        if ($validator->fails()) {
            return response()->json(array(
                'status' => 'failure',
                'message' => $validator->getMessageBag()->toArray()
            ), 400);
        }

        $res["status"] = "success";
        try {
            $dataSave = new bahan();

            $dataSave->bahan_kode = $input["bahan_kode"];
            $dataSave->bahan_nama = $input["bahan_nama"];
            $dataSave->m_satuan_id = $input["m_satuan_id"];
            $dataSave->created_by = auth()->user()->user_id;
            $dataSave->created_date = date("Y-m-d H:i:s");
            $dataSave->save();
            $bahan_id = $dataSave->bahan_id;

        } catch (\Throwable $th) {
            $res["status"] = "failure";
            return response()->json($res, 500);
        }

        return response()->json($dataSave);
    }


    public function updateBahan(Request $request)
    {
        $input = $request["bahan"];

        $bahan_id = $input["bahan_id"];
        $validator = Validator::make($input, [
            'bahan_nama' => ['required', 'max:25',
                        Rule::unique('m_bahan')->where(function ($query) use ($bahan_id) {
                            return $query->where('bahan_id', '<>', $bahan_id)
                                         ->where('bahan_aktif', 'y');
                        })],
            'm_satuan_id' => 'required',
        ], [
            'bahan_nama.required' => 'Nama Bahan tidak boleh kosong',
            'bahan_nama.min' => 'Nama Bahan minimal 3 karakter',
            'bahan_nama.max' => 'Nama Bahan maksimal 25 karakter',
            'bahan_kode.unique' => 'Kode Bahan ' . $input['bahan_kode'] . ' telah digunakan, masukkan nama lain',
            'bahan_nama.unique' => 'Nama Bahan ' . $input['bahan_nama'] . ' telah digunakan, masukkan nama lain',
            'm_satuan_id.required' => 'bahan Role tidak boleh kosong',
        ]);

        if ($validator->fails()) {
            return response()->json(array(
                'status' => 'failure',
                'message' => $validator->getMessageBag()->toArray()
            ), 400);
        }

        $dataSave = Bahan::find($bahan_id);

        $res["status"] = "success";
        try {
            $dataSave->bahan_kode = $input["bahan_kode"];
            $dataSave->bahan_nama = $input["bahan_nama"];
            $dataSave->m_satuan_id = $input["m_satuan_id"];
            $dataSave->updated_by = auth()->user()->user_id;
            $dataSave->updated_date = date("Y-m-d H:i:s");
            $dataSave->revised = $dataSave->revised+1;

            $dataSave->save();
        } catch (\Throwable $th) {
            $res["status"] = "failure";
            return response()->json($res, 500);
        }

        return response()->json($res);
    }

    public function deleteBahan($id)
    {
        $res["status"] = "success";
        try {
            $bahan = Bahan::find($id);
            $bahan->bahan_aktif = 't';
            $bahan->disabled_by = auth()->user()->user_id;
            $bahan->disabled_date = date("Y-m-d H:i:s");
            $bahan->save();
        } catch (\Throwable $th) {
            $res["status"] = "failure";
            return response()->json($res, 500);
        }

        return response()->json($res);
    }
}
