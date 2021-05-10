<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientRate extends Model
{
    use HasFactory;
    protected $table ='client_rate';
    protected $fillable=['mandub_id','client_id','rate','comment'];
}
