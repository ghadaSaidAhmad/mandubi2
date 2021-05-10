<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{

    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code',
        'from_lang',
        'from_lat',
        'to_lang',
        'to_lat',
        'from_title',
        'to_title',
        'mandub_id',
        'shipping_type_id',
        'shipping_specifications_id',
        'mandub_gender',
        'payment_type',
        'description',
        'order_weight',
        'order_count',
        'order_state',
        'extra',
        'price',
        'product_price',
        'arrived_code',
        'delivery_code'
    ];
    protected $appends = [
        'order_state_type','client_name','mandub_name'
    ];
    /**
     * Set the proper slug attribute.
     *
     * @param string $value
     */
    public function setCodeAttribute($value){
    if (static::whereSlug($slug = str_slug($value))->exists()) {
        $slug = $this->incrementSlug($slug);
    }
    $this->attributes['slug'] = $slug;
}
    protected $hidden = [
        'orderState',
    ];
    public $timestamps = true;

    public function image(){
        return $this->morphOne(Image::class , 'imageable');
    }
    public function getClientNameAttribute()
    {
        return $this->client->name;
    }
    public function getMandubNameAttribute()
    {
        if($this->mandub){
            return $this->mandub->name;
        }

    }


    /**
     * Get the client of the trip .
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    
    /**
     * Get the mandub of the trip .
     */
    public function mandub()
    {

        return $this->belongsTo(Mandub::class);
    }
    /**
     * Get the orders of the mandub .
     */
    public function newOrders()
    {
        return $this->hasMany(MandubOrders::class);
    }


    /**
     * Get the mandub of the trip .
     */
    public function getOrderStateTypeAttribute()
    {
        return $this->orderState;
        //return $type->code;
    }
    public function orderState()
    {
       return $this->hasOne(OrderState::class,'code','order_state');
    }
    //new order  
    public function isNew()
    {
        return $this->order_state == 1;
    }

    //order approved by mandub 
    public function clientApprovedMandub()
    {
        return $this->order_state == 2;
    }

    public function mandubApprovedorder()
    {
        return $this->status == 3;
    }

    /**
     * Get the balance of the mandub .
     */
    public function mandubBalances()
    {
        return $this->hasMany(MandubBalance::class);
    }
}
