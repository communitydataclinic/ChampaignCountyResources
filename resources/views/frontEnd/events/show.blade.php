<?php

use App\Model\Organization;
use App\Model\Service;
use App\Model\Suggest;
use App\Model\Error;
use Carbon\Carbon;
?>
@extends('layouts.app')
@section('title')
{{$events->event_title}}
@stop

@section('content')

@include('layouts.filter')
{{-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css"> --}}


<style type="text/css">
    
    .suggest-button{
        display: inline-block;
        border-radius: 15px;
        border-color: #beb6b6;
        color: black;
        background-color: #ffffff;
        padding-left: 5%;
        padding-right: 5%;
        margin-left: 10%;
        margin-right: 10%;
        font-family: "Neue Haas Grotesk Display Roman";
        margin-top: 5;
        margin-bottom: 1;
        border-width: thin;
        padding-top: 1%;
        padding-bottom: 1%;
    }
    button[data-id="suggest_organization"] {
        height: 100%;
        border: 1px solid #ddd;
    }
    .error-category{
        font-family: "Neue Haas Grotesk Display Roman";
        width:100% !important;

    }
    .select-extend{
    }
</style>

<div>

    <!-- Page Content Holder -->
    <div class="inner_services">
        <div id="content" class="container">
            <!-- Example Striped Rows -->
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-block">
                            <div class="container">

                                <img src="{{$events->logo}}" alt="" title="" class="org_logo_img" style="width:50%; height:auto;">
                                <!-- share btn -->
                                <button type="button" class="float-right btn_share_download" style="background-color: grey;">
                                    <div class="sharethis-inline-share-buttons"></div>
                                </button>
                                <!--end share btn -->
                            </div>
                            <h4 class="card-title">
                                <a href="#">{{$events->event_title}}</a>
                                @if (Auth::user() && Auth::user()->roles && $organization && Auth::user()->user_organization &&
                                str_contains(Auth::user()->user_organization, $event_organization) && Auth::user()->roles->name == 'Organization Admin')
                                <a href="/events/{{$events->event_recordid}}/edit" class="float-right">
                                    <i class="icon md-edit mr-0"></i>
                                </a>
                                @endif
                                @if (Auth::user() && Auth::user()->roles && Auth::user()->roles->name == 'System Admin')
                                <a href="/{{$events->event_recordid}}/edit" class="float-right">
                                    <i class="icon md-edit mr-0"></i>
                                </a>
                                @endif
                            </h4>

                            <h4 class="org_title"><span class="subtitle"><b>Organization:</b></span>
                                @if($events->event_organization!=0)
                                    @if(isset($events->event_organization))
                                    <a class="panel-link" class="notranslate" href="/organizations/{{$events->event_organization}}">
                                        {{$events->event_organization_name}}</a>
                                    @endif
                                @endif
                            </h4>
                            <h4 class="org_title"><span class="subtitle"><b>Location:</b></span>
                                @if($events->locations!=0)
                                    @foreach($locations as $location)
                                        @if($location->location_recordid == $events->locations)
                                            {{$location->location_name}}
                                        @endif
                                    @endforeach
                                @endif
                            </h4>
                            <h4 class="service-description" style="line-height: inherit;"> {!! nl2br($events->event_detail) !!}</h4>

                            
                            <h4 style="line-height: inherit;">
                                <span><i class="icon md-phone font-size-18 vertical-align-top mr-0 pr-10"></i>
                                    <a href="tel:{{$events->event_contact_phone}}">{{$events->event_contact_phone}}</a>
                                </span>
                            </h4>



                            @if($events->event_email!=NULL)
                            <h4 style="line-height: inherit;">
                                <span>
                                    <i class="icon md-email font-size-18 vertical-align-top mr-0 pr-10"></i>
                                    {{$events->event_email}}
                                </span>
                            </h4>
                            @endif


                            @if($events->event_application_process)
                            <h4>
                                <span class="subtitle"><b>Application: </b></span> {!! $events->event_application_process
                                !!}
                            </h4>
                            @endif

                            @if($events->event_fees)
                            <h4><span class="subtitle"><b>Fees: </b></span> {{$events->event_fees}}</h4>
                            @endif


                            <h4>
                                <span class="pl-0 category_badge subtitle"><!--b>Types of Services:</b-->
                                @foreach($taxonomy_list as $tax)
                                    @if($tax->event_recordid == $events->event_recordid)
                                        <button style="background-color:{{$tax->color}}; color:white; pointer-events: none;">{{$tax->taxonomy_name}}</button>
                                    @endif
                                @endforeach
                                </span>
                            </h4>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 property">
                    
                    <!-- Locations area design -->
                    <div class="card">
                        <div class="card-block p-0">
                            <div id="map" style="width: 100%; height: 60vh;border-radius:12px;box-shadow: none;">
                            </div>
                            <div class="p-25">
                                <h4 class="card_services_title">
                                    <b>Locations</b>
                                </h4>
                                <div>
                                    @if(isset($events->locations))
                                        @if($events->locations != null)
                                            @foreach($locations as $location)

                                                <div class="location_border">
                                                    <div>
                                                        @if($location->location_name)
                                                            <h4>
                                                                <span><i class="icon fas fa-building font-size-18 vertical-align-top  "></i>
                                                                    {{$location->location_name}}
                                                                </span>
                                                            </h4>
                                                        @endif
                                                        <h4>
                                                            <span><i class="icon md-pin font-size-18 vertical-align-top "></i>
                                                                @if(isset($location->address))
                                                                @if($location->address != null)
                                                                @foreach($location->address as $address)
                                                                {{ $address->address_1 }} {{ $address->address_2 }}
                                                                {{ $address->address_city }} {{ $address->address_state_province }}
                                                                {{ $address->address_postal_code }}
                                                                @endforeach
                                                                @endif
                                                                @endif
                                                            </span>
                                                        </h4>
                                                        @if($location->location_hours)
                                                            <h4>
                                                                <span><i class="icon fa-clock-o font-size-18 vertical-align-top "></i>
                                                                    {{$location->location_hours}}
                                                                </span>
                                                            </h4>
                                                        @endif
                                                        @if($location->location_transportation)
                                                            <h4>
                                                                <span><i class="icon fa-truck font-size-18 vertical-align-top "></i>
                                                                    {{$location->location_transportation}}
                                                                </span>
                                                            </h4>
                                                        @endif
                                                        @if(isset($location->phones))
                                                            @if($location->phones != null)
                                                                @if(count($location->phones) > 0)
                                                                <h4>
                                                                    <span>
                                                                        <i class="icon md-phone font-size-18 vertical-align-top "></i>
                                                                        @php
                                                                        $phones = '';
                                                                        @endphp
                                                                        @foreach($location->phones as $phone)
                                                                        @php

                                                                        $phoneNo = '<a href="tel:'.$phone->phone_number.'">'.$phone->phone_number.' , ' .'</a>';
                                                                        $phones .= $phoneNo;

                                                                        @endphp
                                                                        @endforeach
                                                                        {!! rtrim($phones, ',') !!}
                                                                    </span>
                                                                </h4>
                                                                @endif
                                                            @endif
                                                        @endif
                                                        @if(isset($location->accessibilities()->first()->accessibility))
                                                            <h4>
                                                                <span><b>Accessibility for disabilities:</b></span>
                                                                <br />
                                                                {{$location->accessibilities()->first()->accessibility}}
                                                            </h4>
                                                        @endif
                                                        
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
</div>
<script>
    $(document).ready(function(){
        // navigator.geolocation.getCurrentPosition(showPosition)
        //$('select#suggest_organization').val([]).change();
    setTimeout(function(){
        var locations = <?php print_r(json_encode($locations)) ?>;
        var maplocation = <?php print_r(json_encode($map)) ?>;
        
        
        var show = 1;
        if(locations.length == 0){
          show = 0;
        }

        if(maplocation.active == 1){
            avglat = maplocation.lat;
            avglng = maplocation.long;
            zoom = maplocation.zoom_profile;
        }
        else
        {
            avglat = 40.730981;
            avglng = -73.998107;
            zoom = 10
        }

        latitude = null;
        longitude = null;

        if (locations[0]) {
            latitude = locations[0].location_latitude;
            longitude = locations[0].location_longitude;
        }
        if(latitude == null){
            latitude = avglat;
            longitude = avglng;
        }

        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: zoom,
            center: {lat: parseFloat(latitude), lng: parseFloat(longitude)}
        });

        // var latlongbounds = new google.maps.LatLngBounds();
        var markers = locations.map(function(location, i) {

            var position = {
                lat: location.location_latitude,
                lng: location.location_longitude
            }
            // var latlong = new google.maps.LatLng(position.lat, position.lng);
            // latlongbounds.extend(latlong);

            var content = '<div id="iw-container">' +
                        '<div class="iw-title"> <a href="#">' + location.service + '</a> </div>' +
                        '<div class="iw-content">' +
                            '<div class="iw-subTitle">Organization Name</div>' +
                            '<a href="/organizations/' + location.organization_recordid + '">' + location.organization_name +'</a>'+
                            '<div class="iw-subTitle">Address</div>' +
                            '<a href="https://www.google.com/maps/dir/?api=1&destination=' + location.address_name + '" target="_blank">' + location.address_name +'</a>'+
                        '</div>' +
                        '<div class="iw-bottom-gradient"></div>' +
                        '</div>';

            var infowindow = new google.maps.InfoWindow({
                content: content
            });

            var marker = new google.maps.Marker({
                position: position,
                map: map,
                title: location.location_name,
            });
            marker.addListener('click', function() {
                infowindow.open(map, marker);
            });
            return marker;
        });

        map.fitBounds(latlongbounds);

    }, 2000);


    $('.panel-link').on('click', function(e){
        if($(this).hasClass('target-population-link') || $(this).hasClass('target-population-child'))
            return;
        var id = $(this).attr('at');
    });

    $('.panel-link.target-population-link').on('click', function(e){
        $("#target_all").val("all");
        $("#filter").submit();
    });

    $('.panel-link.target-population-child').on('click', function(e){
        var id = $(this).attr('at');
        $("#target_multiple").val(id);
        $("#filter").submit();

    });
});
$('#showHolidays').click(function(){
    $('#holidays').show();
    $('#hideHolidays').show();
    $(this).hide()
})
$('#hideHolidays').click(function(){
    $('#holidays').hide();
    $('#showHolidays').show();
    $(this).hide()
})

</script>
@endsection
