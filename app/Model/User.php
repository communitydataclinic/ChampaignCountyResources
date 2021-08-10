<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use ChristianKuri\LaravelFavorite\Traits\Favoriteability;

class User extends Model
{
    protected $fillable = [
         'first_name','last_name','email','role_id','user_organization'
    ];
    use Favoriteability;

}




