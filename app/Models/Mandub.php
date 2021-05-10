<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;


class Mandub extends Authenticatable implements JWTSubject
{
    use  Notifiable;

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
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'profile_image',
        'national_id_front_image',
        'national_id_back_image',
        'phone',
        'location_lang',
        'location_lat',
        'active_now',
        'whats_number',
        'gender',
        'shipping_type_id',
        'governorate_id',
        'payment_type',
        'shipping_method',
        'admin_agree',
        'complete_register',
        'balance',
        'paid',
        'rate',
        'fcm_token',
        'local'


    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * hash password when created .
     */
    public function setPasswordAttribute($pass){

        $this->attributes['password'] = Hash::make($pass);

    }

    /**
     * Get the orders of the mandub .
     */
    public function Orders()
    {
        return $this->hasMany(Order::class);
    }
    /**
     * Get the notifications of the mandub .
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }


    /**
     * Get the orders of the mandub .
     */
    public function mandubBalances()
    {
        return $this->hasMany(MandubBalance::class);
    }

    /**
     * Get the orders of the mandub .
     */
    public function newOrders()
    {
        return $this->hasMany(MandubOrders::class);
    }
    /**
     * Get the orders of the mandub .
     */
    public function MandubBalance()
    {
        return $this->hasMany(MandubOrders::class);
    }

    /**
     * Get the Governorate of the mandub .
     */
    public function governorate()
    {
        return $this->hasOne(Governorate::class);
    }

    /**
     * Get the ShippingType of the mandub .
     */
    public function shippingType()
    {
        return $this->hasOne(ShippingType::class);
    }

    public function hasVerifiedMobile()
    {
        return !is_null($this->phone_verified_at);
    }

    public function markPhoneAsVerified()
    {
        return $this->forceFill([
            'phone_verified_at' => $this->freshTimestamp(),
        ])->save();
    }

    public function hasAdminVerified()
    {
        return !is_null($this->admin_agree);
    }

    public function markAsVerifiedByAdmin()
    {
        return $this->forceFill([
            'admin_agree' => 1,
        ])->save();
    }

    public function hasCompleteRegister()
    {
        return !is_null($this->complete_register);
    }

    public function markAsCompleteRegister()
    {
        return $this->forceFill([
            'complete_register' => 1,
        ])->save();
    }
}
