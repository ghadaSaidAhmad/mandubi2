<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Governorate extends Model
{
    use HasFactory;
    protected $table="governorates";

    /**
     * Get the cities of the Governorates .
     */
    public function cities()
    {
        return $this->hasMany(City::class);
    }
}
