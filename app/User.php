<?php

namespace App;

use App\Notifications\VerifyEmail;
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
        'name', 'email', 'password', 'sername', 'last_name', 'phone', 'country', 'city', 'role', 'logo','permission','discount_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmail);
    }

    public function cars(){
        return $this->hasMany('App\UserCar');
    }

    public function deliveryInfo(){
        return $this->hasOne('App\DeliveryInfo');
    }

    public function balance(){
        return $this->hasOne('App\UserBalance');
    }

    public function historyBalance(){
        return $this->hasMany('App\UserBalanceHistory');
    }

    public function discount(){
        return $this->belongsTo('App\Discount');
    }

    public function cart(){
        return $this->hasMany('App\Cart');
    }

    public function userPhones(){
        return $this->hasMany('App\UserPhone');
    }
}
