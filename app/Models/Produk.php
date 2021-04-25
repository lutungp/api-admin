<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'm_produk';
    protected $primaryKey = 'produk_id';
    public $timestamps = false;

    public static function getActive()
    {
        return Self::select("produk_id", "produk_kode", "produk_nama", "m_satuan_id")
                    ->where("produk_aktif", "y")->get();
    }

    /**
     * Get the satuan associated with the Produk
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function satuan()
    {
        return $this->hasOne(Satuan::class, 'satuan_id', 'm_satuan_id')
                        ->select(['satuan_id', 'satuan_kode', 'satuan_nama']);
    }
}
