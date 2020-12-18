<?php

namespace App\Http\Controllers\frontEnd;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Map;
use App\Model\Organization;
use App\Model\Service;
use App\Model\Error;
use App\Model\Suggest;
use App\Model\Email;
use App\Model\Event;
use App\Model\Location;
use App\Model\LocationAddress;
use App\Model\LocationPhone;

use App\Model\Layout;
use App\Model\EventTaxonomy;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use SendGrid;
use SendGrid\Mail\Mail;
use App\Model\Taxonomy;
use Auth;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Set default number of events per page
        $pagination = 10;
        
        $events = Event::orderBy('event_recordid')->paginate($pagination);
        $map = Map::find(1);
        $taxonomy_list = EventTaxonomy::get();
        $service = Service::get();

        
        return view('frontEnd.events.index', compact('pagination', 'events', 'map', 'taxonomy_list', 'service'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $map = Map::find(1);
        $events = Event::get();
        $taxonomy_list = EventTaxonomy::get();

        if (Auth::user() && Auth::user()->user_organization && Auth::user()->roles->name == 'Organization Admin') {
            $organization_recordid = Auth::user()->organizations ? Auth::user()->organizations->pluck('organization_recordid') : [];
            $organization_names = Organization::select("organization_name")->whereIn('organization_recordid', $organization_recordid)->distinct()->get();
        } else {
            $organization_names = Organization::select("organization_name")->distinct()->get();
        }
        $service_names = Service::select("service_name")->distinct()->get();
        $service_name_list = [];
        foreach ($service_names as $key => $value) {
            $ser_names = explode(", ", trim($value->service_name));
            $service_name_list = array_merge($service_name_list, $ser_names);
        }
        $service_name_list = array_unique($service_name_list);
        $organization_name_list = [];
        foreach ($organization_names as $key => $value) {
            $org_names = explode(", ", trim($value->organization_name));
            $organization_name_list = array_merge($organization_name_list, $org_names);
        }
        $organization_name_list = array_unique($organization_name_list);
        $taxonomy_info_list = Taxonomy::select('taxonomy_recordid', 'taxonomy_name')->orderBy('taxonomy_name')->distinct()->get();

        return view('frontEnd.events.create', compact('map', 'events', 'taxonomy_list', 'service_name_list', 'organization_name_list', 'taxonomy_info_list'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'event_name' => 'required',
            'event_service' => 'required',
            'event_organization' => 'required'
        ]);

        try {
            $event = new Event;

            $event_recordids = Event::select("event_recordid")->distinct()->get();
            $event_recordid_list = array();
            foreach ($event_recordids as $key => $value) {
                $event_recordid = $value->event_recordid;
                array_push($event_recordid_list, $event_recordid);
            }
            $event_recordid_list = array_unique($event_recordid_list);

            $new_recordid = Event::max('event_recordid') + 1;
            if (in_array($new_recordid, $event_recordid_list)) {
                $new_recordid = Event::max('event_recordid') + 1;
            }
            $event->event_recordid = $new_recordid;

            $event->event_title = $request->event_name;
            $event->locations = $request->event_locations;
            $event->event_contact_name = $request->event_contact_name;
            $event->event_contact_email = $request->event_contact_email;
            $event->event_contact_phone = $request->event_contact_phone;

            $event->event_detail = $request->event_description;
            $event->event_application_process = $request->event_application_process;

            $event->event_fees = $request->event_fee;
            $event->event_time = $request->event_time;
            $event->start = $request->start_time;
            $event->end = $request->end_time;


            $service_name = $request->event_service;
            $event->event_service_name = $service_name;
            $event_service = Service::where('service_name', '=', $service_name)->first();
            $event_service_id = $event_service["service_recordid"];
            $event->event_service = $event_service_id;


            $organization_name = $request->event_organization;
            $event->event_organization_name = $organization_name;
            $event_organization = Organization::where('organization_name', '=', $organization_name)->first();
            $event_organization_id = $event_organization["organization_recordid"];
            $event->event_organization = $event_organization_id;

            // if ($request->event_locations) {
            //     foreach ($request->event_locations as $key => $locationId) {
            //         EventLocation::create([
            //             'event_recordid' => $new_recordid,
            //             'location_recordid' => $locationId
            //         ]);
            //     }
            //     $event->event_locations = join(',', $request->event_locations);
            // } else {
            //     $event->event_locations = '';
            // }

            if ($request->event_taxonomies) {
                $event->event_taxonomy = join(',', $request->event_taxonomies);
            } else {
                $event->event_taxonomy = '';
            }


            $event->save();

            Session::flash('message', 'Event created successfully');
            Session::flash('status', 'success');
            return redirect('events');
        } catch (\Throwable $th) {
            dd($th);
            Session::flash('message', $th->getMessage());
            Session::flash('status', 'error');
            return redirect('events');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $map = Map::find(1);
        $events = Event::where('event_recordid', '=', $id)->first();
        $taxonomy_list = EventTaxonomy::get();
        $locations = Location::where('location_recordid', '=', $events->locations)->get();
        $organization = Organization::where('organization_recordid', '=', $events->event_organization)->first();


        return view('frontEnd.events.show', compact('map', 'events', 'taxonomy_list', 'locations', 'organization'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        
    }
}
