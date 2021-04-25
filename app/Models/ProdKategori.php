<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProdKategori extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'm_prodkategori';
    protected $primaryKey = 'prodkategori_id';
    public $timestamps = false;

    public static function getActive()
    {
        return Self::select("prodkategori_id", "prodkategori_kode", "prodkategori_nama")
                    ->where("prodkategori_aktif", "y")->get();
    }
}
