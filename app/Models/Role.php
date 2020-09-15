<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uid',
        'name',
        'description',
        'composite',
        'nb_users',
    ];


    public function getRoles()
    {
        return json_decode(json_encode ($this->roles), FALSE);        
    }

 
}