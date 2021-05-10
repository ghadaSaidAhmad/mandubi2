<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class Client extends Authenticatable implements JWTSubject
{
    use  Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'phone',
        'email',
        'verification_code',
        'password',
        'gender',
        'profile_image',
        'national_id_front_image',
        'national_id_back_image',
        'admin_agree',
        'complete_register',
        'fcm_token',
        'local'
    ];
    protected $appends =['rate'];

    /**
     * hash password when created .
     */
    public function getRateAttribute()
    {
        if (count($this->rates)>0) {
            return  $this->rates()->sum('rate')/$this->rates()->count();
        }
    }
    /**
     * Get the rate of the mandub .
     */
    public function rates()
    {
        return $this->hasMany(ClientRate::class);
    }
    /**
     * hash password when created .
     */
    public function setPasswordAttribute($pass){

        $this->attributes['password'] = Hash::make($pass);

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

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
    public $timestamps = true;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'phone_verified_at' => 'datetime',
    ];

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

    /**
     * Get the orders of the client .
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
}
