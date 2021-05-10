<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MandubRate extends Model
{
    use HasFactory;
    protected $table ='mandub_rate';
    protected $fillable=['client_id','mandub_id','rate','comment'];
}
