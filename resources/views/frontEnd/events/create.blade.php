@extends('layouts.app')
@section('title')
Event Create
@stop
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

<style type="text/css">
    button[data-id="event_organization"], button[data-id="event_service"],button[data-id="taxonomies"] {
        height: 100%;
        border: 1px solid #ddd;
    }
    
</style>

@section('content')
<div class="top_header_blank"></div>
<div class="inner_services">
    <div id="contacts-content" class="container">
        <div class="row">
            <!-- <div class="col-md-12">
                <input type="hidden" id="checked_terms" name="checked_terms">
            </div> -->
            <div class="col-md-12">
                <div class="card all_form_field">
                    <div class="card-block">
                        <h4 class="card-title mb-30 ">
                            <p>Create New Event</p>
                        </h4>
                        {{-- <form action="/add_new_event" method="GET"> --}}
                            {!! Form::open(['route' => 'events.store']) !!}
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Event Name: </label>
                                        <input class="form-control selectpicker" type="text" id="event_name"name="event_name" value="" >
                                        @error('event_name')
                                            <span class="error-message"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Service Name: </label>                                    
                                        <select class="form-control selectpicker" data-live-search="true" id="event_service"
                                            name="event_service" data-size="5" >
                                            @foreach($service_name_list as $key => $ser_name)
                                            <option value="{{$ser_name}}">{{$ser_name}}</option>
                                            @endforeach
                                        </select>
                                        @error('event_service')
                                            <span class="error-message"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Organization Name: </label>                                    
                                        <select class="form-control selectpicker" data-live-search="true" id="event_organization"
                                            name="event_organization" data-size="5" >
                                            @foreach($organization_name_list as $key => $org_name)
                                            <option value="{{$org_name}}">{{$org_name}}</option>
                                            @endforeach
                                        </select>
                                        @error('event_organization')
                                            <span class="error-message"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Event Locations: </label>                                    
                                        <input class="form-control selectpicker" type="text" id="event_locations"
                                            name="event_locations" value="" >
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Event Contact Name: </label>
                                        <input class="form-control selectpicker" type="text" id="event_contact_name" name="event_contact_name"
                                            value="">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Event Contact Email: </label>
                                        <input class="form-control selectpicker" type="email" id="event_contact_email"
                                            name="event_contact_email" value="">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Event Contact Phone: </label>
                                        <input class="form-control selectpicker" type="text" id="event_contact_phone"
                                            name="event_contact_phone" value="">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Event Description: </label>
                                        <input class="form-control selectpicker" type="text" id="event_description"
                                            name="event_description" value="" >
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Event Application Process: </label>
                                        <input class="form-control selectpicker" type="text" id="event_application_process"
                                            name="event_application_process" value="">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                    <label>Taxonomies: </label>                                    
                                        <select class="form-control selectpicker" multiple data-live-search="true" id="taxonomies"
                                            name="taxonomies[]" data-size="5" >
                                            @foreach($taxonomy_info_list as $key => $taxonomy_info)
                                            <option value="{{$taxonomy_info->taxonomy_recordid}}">{{$taxonomy_info->taxonomy_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Event Fees: </label>
                                        <input class="form-control selectpicker" type="text" id="event_fee"
                                            name="event_fee" value="">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Event Time: </label>
                                        <input class="form-control selectpicker" type="text" id="event_time"
                                            name="event_time" value="">
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Start Time: </label>                                    
                                        <input class="form-control selectpicker" type="text" id="start_time"
                                            name="start_time" value="">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>End Time: </label>                                    
                                        <input class="form-control selectpicker" type="text" id="end_time"
                                            name="end_time" value="">
                                    </div>
                                </div>
                                <div class="col-md-12 text-center">
                                    <button type="button" class="btn btn-raised btn-lg btn_darkblack waves-effect waves-classic waves-effect waves-classic yellow_btn" id="back-service-btn"> Back </button>
                                    <button type="submit" class="btn btn-primary btn-lg btn_padding waves-effect waves-classic waves-effect waves-classic green_btn" id="save-service-btn"> Save </button>
                                </div>
                            </div>
                            {!! Form::close() !!}
                        {{-- </form> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $('#back-service-btn').click(function() {
        history.go(-1);
        return false;
    });
    $(document).ready(function() {
        $('select#event_organization').val([]).change();
    });
    $(document).ready(function() {
        $('select#event_service').val([]).change();
    });
</script>
@endsection
