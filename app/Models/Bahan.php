<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bahan extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'm_bahan';
    protected $primaryKey = 'bahan_id';
    public $timestamps = false;

    public static function getBahan()
    {
        return Self::select("bahan_id", "bahan_kode", "bahan_nama")
                    ->where("bahan_aktif", "y")->get();
    }
}
