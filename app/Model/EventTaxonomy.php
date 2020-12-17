<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class EventTaxonomy extends Model
{
    protected $fillable = [
    	'event_recordid','taxonomy_recordid', 'taxonomy_name', 'color'
    ];
}
