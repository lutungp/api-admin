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

    function routes(){
      return $this->hasOne('App\Models\Routes', 'route_id', 's_route_id')
                      ->where('route_aktif', 'y');
    }
}