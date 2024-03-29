<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Roles extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 's_roles';
    protected $primaryKey = 'role_id';
    public $timestamps = false;

    public static function getActive()
    {
        return Self::select("role_id", "role_kode", "role_nama", "role_keterangan")
                    ->where("role_aktif", "y")
                    ->orderBy("role_nama")
                    ->get();
    }

    function permission(){
		return $this->hasMany('App\Models\Permission', 's_role_id', 'role_id')
                    ->select('s_route_id', 'create', 'update', 'read', 'delete',
                             'permission_1', 'permission_2', 'permission_3', 'permission_4');
	}

}
