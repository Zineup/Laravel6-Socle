<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uid',
        'last_name',
        'first_name',
        'username',
        'email',
        'createdTimestamp',
        'roles',
        'confirmed',
        'password',
    ];

    public function getCreatedAt()
    {
        $timestamp = substr($this->createdTimestamp, 0, -3);
        return Carbon::createFromTimestamp($timestamp);
        
    }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    
}
