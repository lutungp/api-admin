<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Model implements JWTSubject, AuthenticatableContract
{
    use Authenticatable;
    // use Authenticatable, Authorizable;
    // use Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $primaryKey = 'user_id';
    public $timestamps = false;

    protected $fillable = [
        'user_kode', 'user_nama', 'user_aktif', 'disabled_by', 'disabled_date'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->password;
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    function info(){
		return $this->hasOne('App\Models\UserInfo', 's_user_id', 'user_id')
                    ->where('userinfo_aktif', 'y');
	}

    function role(){
		return $this->hasOne('App\Models\Roles', 'role_id', 's_role_id');
	}

}
