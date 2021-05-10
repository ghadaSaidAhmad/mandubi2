<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MandubBalance extends Model
{
    use HasFactory;
    protected $table = 'mandub_balance';
    protected $fillable = [
        'order_id',
        'date',
        'sender',
        'location_from',
        'location_to',
        'product_price',
        'shipping_cost',
        'mandub_id',
        'commission_ratio',
        'commission',
        'net_profit',
        'paid'
    ];
}
