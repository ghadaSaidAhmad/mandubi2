<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MandubSuggestion extends Model
{
    use HasFactory;
    protected $fillable=['mandub_id','suggation_type','description','image'];
    protected $table="mandub_suggestions";
}
