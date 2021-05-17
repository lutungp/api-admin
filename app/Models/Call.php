<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Call extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'm_calls';
    protected $primaryKey = 'call_id';
    public $timestamps = false;
}
