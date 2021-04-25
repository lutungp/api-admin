<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProdKategori;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use DB;

class ProdKategoriController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getProdKategori(Request $request)
    {
        $data["items"] = ProdKategori::getActive();
        return response()->json($data);
    }

    public function getProdKategorilist(Request $request)
    {
        $prodkategori = ProdKategori::where("prodkategori_aktif", "y")->paginate(20);
        $dataProdKategori  = [];
        foreach ($prodkategori->items() as $key => $value) {
            $dataProdKategori[] = [
                "prodkategori_id" => $value->prodkategori_id,
                "prodkategori_kode" => $value->prodkategori_kode,
                "prodkategori_nama" => $value->prodkategori_nama
            ];
        }

        $data["prodkategori"] = $dataProdKategori;
        $data["total"] = $prodkategori->total();

        return response()->json($data);
    }

    public function createProdKategori(Request $request)
    {
        $input = $request["prodkategori"];
        $validator = Validator::make($input, [
            'prodkategori_kode' => ['required', 'max:25',
                    Rule::unique('m_prodkategori')->where(function ($query) {
                        return $query->where('prodkategori_aktif', 'y');
                    })],
            'prodkategori_nama' => ['required', 'min:3', 'max:25',
                    Rule::unique('m_prodkategori')->where(function ($query) {
                        return $query->where('prodkategori_aktif', 'y');
                    })],
        ],
        $messages = [
            'prodkategori_nama.required' => 'Nama Produk Kategori tidak boleh kosong',
            'prodkategori_nama.min' => 'Nama Produk Kategori minimal 3 karakter',
            'prodkategori_nama.max' => 'Nama Produk Kategori maksimal 100 karakter',
            'prodkategori_kode.unique' => 'Kode Produk Kategori ' . $input['prodkategori_kode'] . ' telah digunakan, masukkan nama lain',
            'prodkategori_nama.unique' => 'Nama Produk Kategori ' . $input['prodkategori_nama'] . ' telah digunakan, masukkan nama lain'
        ]);

        if ($validator->fails()) {
            return response()->json(array(
                'status' => 'failure',
                'message' => $validator->getMessageBag()->toArray()
            ), 400);
        }

        $res["status"] = "success";
        try {
            $dataSave = new ProdKategori();

            $dataSave->prodkategori_kode = $input["prodkategori_kode"];
            $dataSave->prodkategori_nama = $input["prodkategori_nama"];
            $dataSave->created_by = auth()->user()->user_id;
            $dataSave->created_date = date("Y-m-d H:i:s");
            $dataSave->save();
            $prodkategori_id = $dataSave->prodkategori_id;

        } catch (\Throwable $th) {
            $res["status"] = "failure";
            return response()->json($res, 500);
        }

        return response()->json($q);
    }


    public function updateSatuan(Request $request)
    {
        $input = $request["prodkategori"];

        $prodkategori_id = $input["prodkategori_id"];
        $validator = Validator::make($input, [
            'prodkategori_kode' => ['required', 'max:25',
                            Rule::unique('m_prodkategori')->where(function ($query) use ($prodkategori_id) {
                                return $query->where('prodkategori_id', '<>', $prodkategori_id)
                                             ->where('prodkategori_aktif', 'y');
                            })],
            'prodkategori_nama' => ['required', 'max:25',
                            Rule::unique('m_prodkategori')->where(function ($query) use ($prodkategori_id) {
                                return $query->where('prodkategori_id', '<>', $prodkategori_id)
                                            ->where('prodkategori_aktif', 'y');
                            })],
        ],
        $messages = [
            'prodkategori_nama.required' => 'Nama Produk Kategori tidak boleh kosong',
            'prodkategori_nama.min' => 'Nama Produk Kategori minimal 3 karakter',
            'prodkategori_nama.max' => 'Nama Produk Kategori maksimal 25 karakter',
            'prodkategori_nama.unique' => 'Nama Produk Kategori ' . $input['prodkategori_nama'] . ' telah digunakan, masukkan nama lain',
        ]);

        if ($validator->fails()) {
            return response()->json(array(
                'status' => 'failure',
                'message' => $validator->getMessageBag()->toArray()
            ), 400);
        }

        $dataSave = ProdKategori::find($prodkategori_id);

        $res["status"] = "success";
        try {
            $dataSave->prodkategori_kode = $input["prodkategori_kode"];
            $dataSave->prodkategori_nama = $input["prodkategori_nama"];
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
            $prodkategori = ProdKategori::find($id);
            $prodkategori->prodkategori_aktif = 't';
            $prodkategori->disabled_by = auth()->user()->user_id;
            $prodkategori->disabled_date = date("Y-m-d H:i:s");
            $prodkategori->save();
        } catch (\Throwable $th) {
            $res["status"] = "failure";
            return response()->json($res, 500);
        }

        return response()->json($res);
    }
}
