<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'm_customer';
    protected $primaryKey = 'customer_id';
    public $timestamps = false;

}
