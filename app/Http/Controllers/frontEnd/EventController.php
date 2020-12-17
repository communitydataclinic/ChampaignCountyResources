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

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $events = Event::orderBy('event_recordid')->paginate(20);
        $map = Map::find(1);
        $taxonomy_list = EventTaxonomy::get();
        $service = Service::get();

        
        return view('frontEnd.events.index', compact('events', 'map', 'taxonomy_list', 'service'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $map = Map::find(1);
        $events = Event::pluck('event_title', "event_recordid");
        $taxonomy_list = EventTaxonomy::get();
        $service = Service::get();

        return view('frontEnd.events.create', compact('map', 'events', 'taxonomy_list', 'service'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
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


        return view('frontEnd.events.show', compact('map', 'events', 'taxonomy_list', 'locations'));
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
