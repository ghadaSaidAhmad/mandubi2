<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MandubOrders extends Model
{
    use HasFactory;
    protected $fillable =['order_id','mandub_id'];
    protected $table="mandub_orders";
}
