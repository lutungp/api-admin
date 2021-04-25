<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 's_permission';
    protected $primaryKey = 'permission_id';
    public $timestamps = false;

    protected $fillable = ['s_role_id', 's_route_id', 'created_by', 'created_date', 'create', 'read', 'update', 'delete'];
    protected $hidden = ['created_by', 'created_date', 'updated_by', 'updated_date'];

    function routes(){
      return $this->hasOne('App\Models\Routes', 'route_id', 's_route_id')
                      ->select('route_id', 'route_level', 'route_path', 's_route_id', 'route', 'read', 'update', 'delete')
                      ->where('route_aktif', 'y');
    }
}
