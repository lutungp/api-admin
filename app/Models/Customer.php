<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Call;

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

    /**
     * Get all of the calls for the Customer
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function calls()
    {
        return $this->hasMany(Call::class, 'm_customerrelated_id', 'customer_id');
    }
}
