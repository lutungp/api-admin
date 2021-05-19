<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Customer;
use App\Models\User;

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

    protected $hidden = ['call_aktif', 'created_by', 'updated_by', 'updated_date', 'disabled_by', 'disabled_date', 'revised'];

    /**
     * Get the user associated with the Call
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function customer()
    {
        return $this->hasOne(Customer::class, 'customer_id', 'm_customerrelated_id')
                    ->select('customer_firstname', 'customer_lastname');
    }

    /**
     * Get the user associated with the Call
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function owner()
    {
        return $this->hasOne(User::class, 'user_id', 'm_userowner_id')
                    ->select('name');
    }
}
