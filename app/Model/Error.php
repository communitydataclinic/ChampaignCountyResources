<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Error extends Model
{
    protected $fillable = [
        'error_recordid', 'error_organization', 'error_service', 'error_service_name', 'error_content', 'error_username', 'error_user_email', 'error_user_phone'
    ];

    public function organization()
    {
        return $this->belongsTo('App\Model\Organization', 'error_organization', 'organization_recordid');
    }
}
