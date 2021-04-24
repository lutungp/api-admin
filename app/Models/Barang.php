<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'm_barang';
    protected $primaryKey = 'barang_id';
    public $timestamps = false;

    public static function getBarang()
    {
        return Self::select("barang_id", "barang_kode", "barang_nama")
                    ->where("barang_aktif", "y")->get();
    }
}
