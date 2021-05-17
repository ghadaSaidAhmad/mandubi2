<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'content', 'order_id', 'mandub_id', 'client_id'];
    protected $appends = [
        'client_name', 'mandub_name', 'order_state', 'order_price'
    ];

    public function getOrderPriceAttribute()
    {
        if ($this->order) {
            return $this->order->price;
        }

    }

    public function getOrderStateAttribute()
    {
        if ($this->order) {
            return $this->order->order_state;
        }
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
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

    public function getClientNameAttribute()
    {
        if ($this->client) {
            return $this->client->name;
        }
        return null;
    }

    public function getMandubNameAttribute()
    {
        if ($this->mandub) {
            return $this->mandub->name;
        }
        return null;

    }

}
