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

    public static function getActive()
    {
        return Self::select("bahan_id", "bahan_kode", "bahan_nama")
                    ->where("bahan_aktif", "y")->get();
    }

    /**
     * Get the satuan associated with the Bahan
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function satuan()
    {
        return $this->hasOne(Satuan::class, 'satuan_id', 'm_satuan_id')
                            ->select("satuan_id", "satuan_kode", "satuan_nama");
    }

    public static function getProdukBahan($produk)
    {
        $data = DB::table('m_produkdet')
                    ->select('produkdet_id', 'm_produk_id', 'm_bahan_id', 'm_bahan.bahan_nama', 'm_bahan.m_satuan_id', 'm_satuan.satuan_nama',
                                'produkdet_hpp', 'produkdet_qty', 'produkdet_hpp_subtotal', 'm_bahan.m_satuan_id')
                    ->join('m_bahan', 'm_bahan.bahan_id', 'm_produkdet.m_bahan_id')
                    ->join('m_satuan', 'm_satuan.satuan_id', 'm_bahan.m_satuan_id')
                    ->where('m_produkdet.m_produk_id', $produk)
                    ->where('m_produkdet.produkdet_aktif', 'y')
                    ->get();

        return $data;
    }

    public static function getUnitBahan($page, $filter, $unit)
    {
        $tanggal = date('Y-m-d H:i:s');
        $whereunit = $unit > 0 ? " AND m_unit_id = " . $unit : "";
        $sql = " SELECT
                    bahantrans_tgl,
                    m_satuan.satuan_id,
                    m_satuan.satuan_nama,
                    m_bahan.bahan_id,
                    m_bahan.bahan_kode,
                    m_bahan.bahan_nama,
                    COALESCE ( bahantrans_akhir, 0 ) AS bahantrans_akhir
                FROM
                    m_bahan
                    LEFT JOIN (
                    SELECT
                        *
                    FROM
                        (
                        SELECT ROW_NUMBER
                            ( ) OVER ( PARTITION BY t_bahantrans.m_bahan_id ORDER BY bahantrans_tgl DESC, bahantrans_id DESC ) AS rnumber,
                            bahantrans_tgl,
                            t_bahantrans.m_satuan_id,
                            t_bahantrans.m_bahan_id,
                            bahantrans_akhir
                        FROM
                            t_bahantrans
                        WHERE
                            bahantrans_tgl <= '$tanggal'
                            $whereunit
                        ) AS bahantrans
                    WHERE
                        bahantrans.rnumber <= 1
                    ) AS bahantrans ON bahantrans.m_bahan_id = m_bahan.bahan_id
                    LEFT JOIN m_satuan ON m_satuan.satuan_id = m_bahan.m_satuan_id
                WHERE m_bahan.bahan_aktif = 'y' ";

        if ($filter <> '') {
            $sql .= " AND UPPER(m_bahan.bahan_nama) LIKE '%".strtoupper($filter)."%'";
        }

        $sql.= " ORDER BY m_bahan.bahan_nama OFFSET $page LIMIT 20";
        $bahan = DB::select($sql);

        return $bahan;
    }

}
