<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    protected $primaryKey = 'favoriteable_id';

    protected $fillable = [
        'user_id', 'favoriteable_type', 'favoriteable_id'
    ];
}


