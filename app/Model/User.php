<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $fillable = [
         'first_name','last_name','email','role_id','user_organization'
    ];
}
