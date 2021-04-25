<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use DB;

class ProdukController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getProduk(Request $request)
    {
        $data["items"] = Produk::getActive();
        return response()->json($data);
    }

    public function getProduklist(Request $request)
    {
        $produk = Produk::where("produk_aktif", "y")->paginate(20);
        $dataProduk  = [];
        foreach ($produk->items() as $key => $value) {
            $dataProduk[] = [
                "produk_id" => $value->produk_id,
                "produk_kode" => $value->produk_kode,
                "produk_nama" => $value->produk_nama,
                "m_satuan_id" => $value->m_satuan_id,
                "satuan_nama" => $value->satuan_nama
            ];
        }

        $data["produk"] = $dataProduk;
        $data["total"] = $produk->total();

        return response()->json($data);
    }

    public function createproduk(Request $request)
    {
        $input = $request["produk"];
        $validator = Validator::make($input, [
            'produk_kode' => ['required', 'max:25',
                    Rule::unique('m_produk')->where(function ($query) {
                        return $query->where('produk_aktif', 'y');
                    })],
            'produk_nama' => ['required', 'min:3', 'max:25',
                    Rule::unique('m_produk')->where(function ($query) {
                        return $query->where('produk_aktif', 'y');
                    })],
        ],
        $messages = [
            'produk_nama.required' => 'Nama Produk tidak boleh kosong',
            'produk_nama.min' => 'Nama Produk minimal 3 karakter',
            'produk_nama.max' => 'Nama Produk maksimal 100 karakter',
            'produk_kode.unique' => 'Kode Produk ' . $input['produk_kode'] . ' telah digunakan, masukkan nama lain',
            'produk_nama.unique' => 'Nama Produk ' . $input['produk_nama'] . ' telah digunakan, masukkan nama lain'
        ]);

        if ($validator->fails()) {
            return response()->json(array(
                'status' => 'failure',
                'message' => $validator->getMessageBag()->toArray()
            ), 400);
        }

        $res["status"] = "success";
        try {
            $dataSave = new Produk();

            $dataSave->produk_kode = $input["produk_kode"];
            $dataSave->produk_nama = $input["produk_nama"];
            $dataSave->m_satuan_id = $input["m_satuan_id"];
            $dataSave->created_by = auth()->user()->user_id;
            $dataSave->created_date = date("Y-m-d H:i:s");
            $dataSave->save();
            $produk_id = $dataSave->produk_id;

        } catch (\Throwable $th) {
            $res["status"] = "failure";
            return response()->json($res, 500);
        }

        return response()->json($q);
    }


    public function updateproduk(Request $request)
    {
        $input = $request["produk"];

        $produk_id = $input["produk_id"];
        $validator = Validator::make($input, [
            'produk_kode' => ['required', 'max:25',
                            Rule::unique('m_produk')->where(function ($query) use ($produk_id) {
                                return $query->where('produk_id', '<>', $produk_id)
                                             ->where('produk_aktif', 'y');
                            })],
            'produk_nama' => ['required', 'max:25',
                            Rule::unique('m_produk')->where(function ($query) use ($produk_id) {
                                return $query->where('produk_id', '<>', $produk_id)
                                            ->where('produk_aktif', 'y');
                            })],
        ],
        $messages = [
            'produk_nama.required' => 'Nama Produk tidak boleh kosong',
            'produk_nama.min' => 'Nama Produk minimal 3 karakter',
            'produk_nama.max' => 'Nama Produk maksimal 25 karakter',
            'produk_nama.unique' => 'Nama Produk ' . $input['produk_nama'] . ' telah digunakan, masukkan nama lain',
        ]);

        if ($validator->fails()) {
            return response()->json(array(
                'status' => 'failure',
                'message' => $validator->getMessageBag()->toArray()
            ), 400);
        }

        $dataSave = Produk::find($produk_id);

        $res["status"] = "success";
        try {
            $dataSave->produk_kode = $input["produk_kode"];
            $dataSave->produk_nama = $input["produk_nama"];
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

    public function deleteproduk($id)
    {
        $res["status"] = "success";
        try {
            $produk = Produk::find($id);
            $produk->produk_aktif = 't';
            $produk->disabled_by = auth()->user()->user_id;
            $produk->disabled_date = date("Y-m-d H:i:s");
            $produk->save();
        } catch (\Throwable $th) {
            $res["status"] = "failure";
            return response()->json($res, 500);
        }

        return response()->json($res);
    }
}
