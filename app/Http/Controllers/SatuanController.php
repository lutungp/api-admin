<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Satuan;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use DB;

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

    public function getSatuanlist(Request $request)
    {
        $satuan = Satuan::where("satuan_aktif", "y")->paginate(20);
        $dataSatuan  = [];
        foreach ($satuan->items() as $key => $value) {
            $dataSatuan[] = [
                "satuan_id" => $value->satuan_id,
                "satuan_kode" => $value->satuan_kode,
                "satuan_nama" => $value->satuan_nama
            ];
        }

        $data["satuan"] = $dataSatuan;
        $data["total"] = $satuan->total();

        return response()->json($data);
    }

    public function createSatuan(Request $request)
    {
        $input = $request["satuan"];
        $validator = Validator::make($input, [
            'satuan_kode' => ['required', 'max:25',
                    Rule::unique('m_satuan')->where(function ($query) {
                        return $query->where('satuan_aktif', 'y');
                    })],
            'satuan_nama' => ['required', 'min:3', 'max:25',
                    Rule::unique('m_satuan')->where(function ($query) {
                        return $query->where('satuan_aktif', 'y');
                    })],
        ],
        $messages = [
            'satuan_nama.required' => 'Nama Satuan tidak boleh kosong',
            'satuan_nama.min' => 'Nama Satuan minimal 3 karakter',
            'satuan_nama.max' => 'Nama Satuan maksimal 100 karakter',
            'satuan_kode.unique' => 'Kode Satuan ' . $input['satuan_kode'] . ' telah digunakan, masukkan nama lain',
            'satuan_nama.unique' => 'Nama Satuan ' . $input['satuan_nama'] . ' telah digunakan, masukkan nama lain'
        ]);

        if ($validator->fails()) {
            return response()->json(array(
                'status' => 'failure',
                'message' => $validator->getMessageBag()->toArray()
            ), 400);
        }

        $res["status"] = "success";
        try {
            $dataSave = new Satuan();

            $dataSave->satuan_kode = $input["satuan_kode"];
            $dataSave->satuan_nama = $input["satuan_nama"];
            $dataSave->created_by = auth()->user()->user_id;
            $dataSave->created_date = date("Y-m-d H:i:s");
            $dataSave->save();
            $satuan_id = $dataSave->satuan_id;

        } catch (\Throwable $th) {
            $res["status"] = "failure";
            return response()->json($res, 500);
        }

        return response()->json($q);
    }


    public function updateSatuan(Request $request)
    {
        $input = $request["satuan"];

        $satuan_id = $input["satuan_id"];
        $validator = Validator::make($input, [
            'satuan_kode' => ['required', 'max:25',
                            Rule::unique('m_satuan')->where(function ($query) use ($satuan_id) {
                                return $query->where('satuan_id', '<>', $satuan_id)
                                             ->where('satuan_aktif', 'y');
                            })],
            'satuan_nama' => ['required', 'max:25',
                            Rule::unique('m_satuan')->where(function ($query) use ($satuan_id) {
                                return $query->where('satuan_id', '<>', $satuan_id)
                                            ->where('satuan_aktif', 'y');
                            })],
        ],
        $messages = [
            'satuan_nama.required' => 'Nama Satuan tidak boleh kosong',
            'satuan_nama.min' => 'Nama Satuan minimal 3 karakter',
            'satuan_nama.max' => 'Nama Satuan maksimal 25 karakter',
            'satuan_nama.unique' => 'Nama Satuan ' . $input['satuan_nama'] . ' telah digunakan, masukkan nama lain',
        ]);

        if ($validator->fails()) {
            return response()->json(array(
                'status' => 'failure',
                'message' => $validator->getMessageBag()->toArray()
            ), 400);
        }

        $dataSave = Satuan::find($satuan_id);

        $res["status"] = "success";
        try {
            $dataSave->satuan_kode = $input["satuan_kode"];
            $dataSave->satuan_nama = $input["satuan_nama"];
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

    public function deleteSatuan($id)
    {
        $res["status"] = "success";
        try {
            $satuan = Satuan::find($id);
            $satuan->satuan_aktif = 't';
            $satuan->disabled_by = auth()->user()->user_id;
            $satuan->disabled_date = date("Y-m-d H:i:s");
            $satuan->save();
        } catch (\Throwable $th) {
            $res["status"] = "failure";
            return response()->json($res, 500);
        }

        return response()->json($res);
    }
}
