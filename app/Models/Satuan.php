<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Satuan extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'm_satuan';
    protected $primaryKey = 'satuan_id';
    public $timestamps = false;

    public static function getActive()
    {
        return Self::select("satuan_id", "satuan_kode", "satuan_nama")
                    ->where("satuan_aktif", "y")->get();
    }
}
