<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 't_activity';
    protected $primaryKey = 'activity_id';
    public $timestamps = false;

    /**
     * Get the customer associated with the Activity
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function customer()
    {
        return $this->hasOne('App\Models\User', 'user_id', 'm_user_id')
                    ->select(['name']);
    }
}
