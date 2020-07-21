<?php

namespace App\Models\CRUD;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $fillable = [
        'name', 
        'postal_code', 
        'population', 
        'region', 
        'country'
    ];
}
