-- Update all locations to have their organization based on their services
update chresoprs_dev.locations l 
	inner join chresoprs_dev.service_locations sl on sl.location_recordid = l.location_recordid
    inner join chresoprs_dev.services s on sl.service_recordid = s.service_recordid
set l.location_organization = s.service_organization;

select * from chresoprs_test12.locations l where l.location_organization is null;




