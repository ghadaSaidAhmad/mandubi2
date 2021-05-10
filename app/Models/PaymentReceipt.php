<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentReceipt extends Model
{
    use HasFactory;
    protected $table = "payment_receipt";
    protected $fillable =['receipt_image','description','mandub_id','title','payment_method_id'];
}
