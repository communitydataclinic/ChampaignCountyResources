<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'event_recordid', 'event_organization', 'event_service', 'event_service_name', 'event_organization_name', 'event_contact_name', 'event_contact_email', 'event_contact_phone', 'event_title', 'event_detail', 'locations', 'event_application_process', 'event_fees', 'logo'
    ];

    public function organization()
    {
        return $this->belongsTo('App\Model\Organization', 'event_organization', 'organization_recordid');
    }
}
