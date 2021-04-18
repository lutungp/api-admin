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
        return Self::where("role_aktif", "y")->get();
    }

    function permission(){
		return $this->hasMany('App\Models\Permission', 's_role_id', 'role_id');
	}
    
}