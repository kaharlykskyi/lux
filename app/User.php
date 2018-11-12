<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'sername', 'last_name', 'phone', 'country', 'city', 'role', 'logo','permission'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function cars(){
        return $this->hasMany('App\UserCar');
    }

    public function deliveryInfo(){
        return $this->hasOne('App\DeliveryInfo');
    }

    public function orders(){
        return $this->hasMany('App\Order');
    }
}
