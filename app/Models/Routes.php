<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Routes extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 's_routes';
    protected $primaryKey = 'route_id';
    public $timestamps = false;
}