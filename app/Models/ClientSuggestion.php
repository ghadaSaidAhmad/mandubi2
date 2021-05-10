<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientSuggestion extends Model
{
    use HasFactory;
    protected $fillable=['client_id','suggation_type','description','image'];
    protected $table="client_suggestions";
}
