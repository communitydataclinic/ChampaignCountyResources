<?php

use App\Model\Organization;
use App\Model\Service;
use App\Model\Suggest;
use App\Model\Error;
use Carbon\Carbon;
?>
@extends('layouts.app')
@section('title')
    Services
@stop
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

@section('content')
    <style type="text/css">
        .savedservicestitle 
        {
            padding: 40px 0px;
            text-align: center;
            background: #00bda7;
            color: white;
            font-size: 32px;
            font-family: "Neue Haas Grotesk Display Medium";
        }

        .savedservicestitletext
        {
            color: white;
            text-align: center;
            font-size: 32px;
            font-family: "Neue Haas Grotesk Display Medium";
        }

    </style>
   <script type="text/javascript">
         
        </script>
    <div class="savedservicestitle">
        <h1 class="savedservicestitletext"> Saved Services </h1>
    </div>
    <div>

        <!-- Page Content Holder -->
        <div class="top_services_filter">
            <div class="container">
            @include('layouts.sidebar')
            <!-- Types Of Services -->
                <div class="dropdown">
                    <button type="button" class="btn dropdown-toggle"  id="exampleSizingDropdown1" data-toggle="dropdown" aria-expanded="false">
                        Types Of Services
                    </button>
                    <div class="dropdown-menu bullet" aria-labelledby="exampleSizingDropdown1" role="menu" id="sidebar_tree">
                    </div>
                </div>
                <!--end  Types Of Services -->

                <!-- Sort By -->
                <div class="dropdown">
                    <button type="button" class="btn dropdown-toggle"  id="exampleSizingDropdown2" data-toggle="dropdown" aria-expanded="false">
                        Sort By
                    </button>
                    <div class="dropdown-menu bullet" aria-labelledby="exampleSizingDropdown2" role="menu">
                        <a @if(isset($sort) && $sort == 'Service Name') class="dropdown-item drop-sort active" @else class="dropdown-item drop-sort" @endif href="javascript:void(0)" role="menuitem">Service Name</a>
                        <a @if(isset($sort) && $sort == 'Organization Name') class="dropdown-item drop-sort active" @else class="dropdown-item drop-sort" @endif href="javascript:void(0)" role="menuitem">Organization Name</a>
                        @if($sort_by_distance_clickable)
                            <a @if(isset($sort) && $sort == 'Distance from Address') class="dropdown-item drop-sort active" @else class="dropdown-item drop-sort" @endif href="javascript:void(0)" role="menuitem" >Distance from Address</a>
                        @endif
                    </div>
                </div>
                <!--end  Sort By -->

                <!-- Results Per Page -->
                <div class="dropdown">
                    <button type="button" class="btn dropdown-toggle"  id="exampleSizingDropdown3" data-toggle="dropdown" aria-expanded="false">
                        Results Per Page
                    </button>
                    <div class="dropdown-menu bullet" aria-labelledby="exampleSizingDropdown3" role="menu">
                        <a @if(isset($pagination) && $pagination == '10') class="dropdown-item drop-paginate active" @else class="dropdown-item drop-paginate" @endif href="javascript:void(0)" role="menuitem" >10</a>
                        <a @if(isset($pagination) && $pagination == '25') class="dropdown-item drop-paginate active" @else class="dropdown-item drop-paginate" @endif href="javascript:void(0)" role="menuitem">25</a>
                        <a @if(isset($pagination) && $pagination == '50') class="dropdown-item drop-paginate active" @else class="dropdown-item drop-paginate" @endif href="javascript:void(0)" role="menuitem">50</a>
                    </div>
                </div>
                <!--end Results Per Page -->
                <!--Printing the Page-->
                    <!-- <script type="text/JavaScript" src="path/to/jquery.print.js"></script>

                    <a href="" @click.prevent="printme" target="_blank" class="btn btn-default"><i class="fa fa-print"></i>Print</a>
                    <script type="text/JavaScript">
                        const app = new Vue({
                            el: "#app",
                            router,
                            data:{
                                search:''
                            },
                            methods:{
                                search: 
                            }
                        })
                    
                    </script> -->
                    
                    <!-- <script type="text/JavaScript" src="{{ asset('js/jquery.js') }}"></script>
                    <script type="text/JavaScript" src="{{ asset('js/jquery.print.js') }}"></script>
                    <script type="text/JavaScript" src="path/to/jquery.print.js"></script>
                    <script type="text/JavaScript" src="{{ asset('js/scripts.js') }}"></script> -->
                    <!-- <script type="text/JavaScript" src="path/to/jquery.print.js"></script> -->

                <!-- <script type="text/JavaScript">
                    $('#print').on('click', function() {
                    $("#print").print(
                        {
                                globalStyles: true,
                                mediaPrint: false,
                                stylesheet: null,
                                noPrintSelector: ".no-print",
                                iframe: true,
                                append: null,
                                prepend: null,
                                manuallyCopyFormValues: true,
                                deferred: $.Deferred(),
                                timeout: 750,
                                title: null,
                                doctype: '<!doctype html>'
                        }
                        );

                    let CSRF_TOKEN = $('meta[name="csrf-token"').attr('content');

                    $.ajaxSetup({
                    url: '/favorites',
                    type: 'POST',
                    data: {
                        _token: CSRF_TOKEN,
                    },
                    beforeSend: function() {
                        console.log('printing ...');
                    },
                    complete: function() {
                        console.log('printed!');
                    }
                    });

                    $.ajax({
                    success: function(viewContent) {
                        $.allFavorites(viewContent); // This is where the script calls the printer to print the view's content.
                    }
                    });
                    });
                </script> -->
                
                 <!-- <style type="text/css">
                         body {visibility:hidden;}
                         .print {visibility:visible;}
                    </style> -->
                <div class="dropdown btn_download float-right">
                    <img onClick="window.print()" src="/frontend/assets/images/print.png" style="padding-top: 10px; padding-left: 10px" alt="" title="">
                    <button onClick="window.print()" class="float-right btn_share_download"> Print PDF</button>
                </div>
                <!--end Printing page-->
                <!-- download -->
                <!-- <div class="dropdown btn_download float-right">
                    <button type="button" class="float-right btn_share_download dropdown-toggle" id="exampleBulletDropdown4" data-toggle="dropdown" aria-expanded="false">
                        <img src="/frontend/assets/images/download.png" alt="" title="" class="mr-10"> Download
                    </button>
                    <div class="dropdown-menu bullet" aria-labelledby="exampleBulletDropdown4" role="menu">
                        <a class="dropdown-item download_csv" href="javascript:void(0)" role="menuitem">Download CSV</a>
                        <a class="dropdown-item download_pdf" href="javascript:void(0)" role="menuitem">Download PDF</a>
                    </div>
                </div>
            {{-- <div class="btn-group dropdown btn-feature">
                <button type="button" class="btn btn-primary dropdown-toggle btn-button" id="exampleBulletDropdown1" data-toggle="dropdown" aria-expanded="false">
                    Download
                </button>
                <div class="dropdown-menu bullet" aria-labelledby="exampleBulletDropdown1" role="menu">
                    <a class="dropdown-item download_csv" href="javascript:void(0)" role="menuitem">Download CSV</a>
                    <a class="dropdown-item download_pdf" href="javascript:void(0)" role="menuitem">Download PDF</a>
                </div>
            </div> --}} -->
            <!--end download -->

                <!-- share btn -->
                <button type="button" class="float-right btn_share_download">
                    <img src="/frontend/assets/images/share.png" alt="" title="" class="mr-10 share_image">
                    <div class="sharethis-inline-share-buttons"></div>
                </button>
                <!--end share btn -->
            </div>
        </div>
        <div class="inner_services">
            <div id="content" class="container">
                <!-- Example Striped Rows -->
                {{-- <div class="col-md-8 pt-15 pb-15 pl-15">
                    <div class="btn-group btn-feature">
                        @if(isset($search_results))
                        <p class="m-0 btn btn-primary btn-button">Results: {{$search_results}}</p>
                        @endif
                    </div>
                </div> --}}
                <div class="row" >
                    <div class="col-md-8">
                        @if (session('address'))
                            <div class="alert dark alert-danger alert-dismissible ml-15" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">??</span>
                                </button>
                                <b>{{ session('address') }}</b>
                            </div>
                        @endif
                        @if(count($services) != 0)
                            @foreach($services as $service)
                                @if($service->service_name != null)
                                    <div class="card" id="{{$service -> service_recordid}}">
                                        <div class="card-block">
                                            <?php
                                            $rest = str_replace(array('"', '[', ']', '.000000Z'), '', Error::where('error_service', '=', $service->service_recordid)->pluck('created_at'));
                                            $rest = str_replace(array('T'), ' ', $rest);
                                            $time_list = explode(",", $rest);
                                            $now = Carbon::now();
                                            $time_work = [];
                                            $diff = [];
                                            $count = 0;
                                            for($i = 0; $i < count($time_list); $i++){
                                                $time_work[$i] = new Carbon($time_list[$i]);
                                                $diff[$i] = $time_work[$i]->diffInSeconds($now);
                                                //86400
                                                if($diff[$i] >= 1){
                                                    $count++;
                                                }
                                            }
                                            if($count > 0): ?>
                                            <h4>
                                                <img src="../../../../images/error-report.png" alt="" width="25" height="25" style="margin-right:10px">
                                                <?php
                                                $rest = str_replace(array('"', '[', ']', '.000000Z'), '', Error::where('error_service', '=', $service->service_recordid)->pluck('created_at'));
                                                $rest = str_replace(array('T'), ' ', $rest);
                                                $time_list = explode(",", $rest);
                                                $now = Carbon::now();
                                                $time_work = [];
                                                $diff = [];
                                                $count = 0;
                                                for($i = 0; $i < count($time_list); $i++){
                                                    $time_work[$i] = new Carbon($time_list[$i]);
                                                    $diff[$i] = $time_work[$i]->diffInSeconds($now);
                                                    //86400
                                                    if($diff[$i] >= 1){
                                                        $count++;
                                                    }
                                                }
                                                if ($count > 1)
                                                    echo $count . " users ";
                                                else
                                                    echo $count . " user ";
                                                ?>
                                                reported the information to be inaccurate
                                            </h4>

                                            <?php endif; ?>
                                            <h4 class="card-title" style="width: 90%">
                                                @if(isset($service->organizations))
                                                    <a class="notranslate" href="/organizations/{{$service->organizations()->first()->organization_recordid}}"> {{$service->organizations()->first()->organization_name}}</a>
                                                @endif
                                                @if(isset($service->service_organization) && in_array($service->service_organization, $user_orgs))
                                                        <?php // get 24h login org admin
                                                        $time = date('Y-m-d H:i:s', time() - 86400);
                                                        $latest_login_org_admin = \App\User::where([
                                                            'role_id' => 3,
                                                            'user_organization' => $service->service_organization
                                                        ])->orderBy('last_login', 'desc')->first();?>
                                                    <img src="images/blue.png" data-toggle="popover" data-placement="top" data-content="This account is verified" style="width:20px; height:20px;">

                                                    @if($latest_login_org_admin && $latest_login_org_admin->last_login > $time)
                                                        <img src="images/registered.png" data-toggle="popover" data-placement="top" data-content="Online"  style="width:20px; height:20px;">
                                                    @else
                                                        <img src="images/away.png" @if(isset($latest_login_org_admin->last_login)) data-toggle="popover" data-placement="top" data-content="Last Updated: {{$latest_login_org_admin->last_login}}" @endif style="width:20px; height:20px;">
                                                    @endif
                                                @endif
                                                <p style="float: right;">{{ isset($service->miles)  ? floatval(number_format($service->miles,2)) .' miles'  : '' }}</p>
                                            </h4>
                                            <div id="fav-save{{$service->service_recordid}}" onclick="removeService('{{$service-> service_recordid}}')" style="float:right;">
                                                <i id='star{{$service-> service_recordid}}' class="fa fa-star" data-toggle="tooltip" data-placement="top" title="" data-original-title="Unsave"  style="color: #ff0000; font-size:18px; padding-right:5px; display: inline-block;"></i>
                                                <div id="savebutton{{$service-> service_recordid}}" style="color: #ff0000; display: inline-block;" >Unsave</div>
                                            </div>
                                            <h4 class="org_title"><!--span class="subtitle"><b>Service:</b></span-->
                                                <a class="panel-link" href="/services/{{$service->service_recordid}}">{{$service->service_name}}</a>
                                            </h4>
                                            <h4 class="org_title">
                                                Program Name: {{$service->service_alternate_name}}
                                            </h4>
                                            
                                            <h4  style="line-height: inherit;">{!! Str::limit(str_replace(array('\n', '/n', '*'), array(' ', ' ', ' '), $service->service_description), 200) !!}</h4>
                                            <h4>
                                        <span><i class="icon md-phone font-size-18 vertical-align-top  mr-5 pr-10"></i> @foreach($service->phone as $phone)
                                                <a href="tel:{{$phone->phone_number}}">
                                                {!! $phone->phone_number !!} @endforeach
                                            </a>
                                        </span>
                                            </h4>
                                            <h4><span><i class="icon md-pin font-size-18 vertical-align-top mr-5 pr-10"></i>
                                        @if(isset($service->address))
                                                        @foreach($service->address as $address)
                                                            {{ $address->address_1 }} {{ $address->address_2 }} {{ $address->address_city }} {{ $address->address_state_province }} {{ $address->address_postal_code }}
                                                        @endforeach
                                                    @endif
                                                    {{-- @if(isset($service->address))
                                                        @foreach($service->locations()->first()->address as $address)
                                                        {{ $address->address_1 }} {{ $address->address_2 }} {{ $address->address_city }} {{ $address->address_state_province }} {{ $address->address_postal_code }}
                                                        @endforeach
                                                    @endif --}}

                                        </span>
                                            </h4>
                                            <h4 class="org_title">
                                                <!--span class="pl-0 category_badge subtitle"><b>Types of Services:</b-->
                                                <span class="pl-0 category_badge subtitle">
                                            @if($service->service_taxonomy != null)
                                                        @php $service_taxonomy_recordid_list = explode(',', $service->service_taxonomy);
                                                        @endphp
                                                        @foreach($service_taxonomy_recordid_list as $key => $service_taxonomy_recordid)
                                                            @php $taxonomy_name = $service_taxonomy_info_list[$service_taxonomy_recordid];
                                                            @endphp
                                                            @if($taxonomy_name)
                                                                <a class="panel-link {{str_replace(' ', '_', $taxonomy_name)}}" at="child_{{$service_taxonomy_recordid}}">{{$taxonomy_name}}</a>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                        </span>
                                            </h4>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                            <div id="no-services" class="alert dark alert-warning ml-15" role="alert" style="background-color: lightblue; border-color: lightblue; display: none;">
                            <span style="color: #ffffff;">
                                <b>No saved services available to display.</b>
                            </span>
                            </div>
                        @else
                            <div id="no-services" class="alert dark alert-warning ml-15" role="alert" style="background-color: lightblue; border-color: lightblue;">
                            <span style="color: #ffffff;">
                                <b>No saved services available to display.</b>
                            </span>
                            </div>
                        @endif
                    </div>

                    <div class="col-md-4 property">
                        <div class="card">
                            <div class="card-block p-0">
                                <div id="map" style="width: 100%; height: 100vh;border-radius:12px;box-shadow: none;">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="example col-md-12">
                        <div class="row">
                            <div class="col-md-6 pagination_text">
                                <?php
                                // ServiceController defines default results per page using the $pagination parameter

                                // Paginate parameter defines the selected number of results in the dropdownlist on this page
                                if (Request::get("paginate") != null) $pagination = Request::get("paginate");
                                ?>
                                <p>Showing {{ $services->currentPage() * Request::get('paginate') - intval(Request::get('paginate') - 1)  }}-{{ $services->currentPage() * Request::get('paginate')  }} of {{ $services->total() }} items  <span>Show {{ Request::get('paginate') }} per page</span></p>

                            </div>
                            <div class="col-md-6 text-right">
                                {{ $services->appends(\Request::except('page'))->render() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        let numServices = '{{count($services)}}';
        $(document).ready(function(){

            $("[data-toggle='popover']").popover();

            setTimeout(function(){
                var locations = <?php print_r(json_encode($locations)) ?>;
                var maplocation = <?php print_r(json_encode($map)) ?>;
                // var chip_address = <?php if(isset($chip_address)){print_r(json_encode($chip_address));}else{echo '0';} ?>;
                var chip_address = "{{isset($chip_address) ? $chip_address : '0'}}"
                // var avarageLatitude = <?php if(isset($avarageLatitude)){print_r(json_encode($avarageLatitude));}else{echo '0';} ?>;
                var avarageLatitude = "{{ isset($avarageLatitude) ? $avarageLatitude : '0'  }}"
                // var avarageLongitude = <?php if(isset($avarageLongitude)){print_r(json_encode($avarageLongitude));}else{echo '0';} ?>;
                var avarageLongitude = "{{ isset($avarageLongitude) ? $avarageLongitude : '0' }}"
                var sumlat = 0.0;
                var sumlng = 0.0;
                var length = 0;
                if(maplocation.active == 1){
                    avglat = maplocation.lat;
                    avglng = maplocation.long;
                    zoom = maplocation.zoom;
                }
                else
                {
                    avglat = 40.730981;
                    avglng = -73.998107;
                    zoom = 14;
                }
                if(chip_address != '0' && chip_address != ''){
                    zoom = 15
                    if(avarageLatitude != '0'){
                        avglat = avarageLatitude
                    }
                    if(avarageLongitude != '0'){
                        avglng = avarageLongitude
                    }
                }
                var mymap = new GMaps({
                    el: '#map',
                    lat: avglat,
                    lng: avglng,
                    zoom: zoom
                });
                let checkunKnownAddress = [];
                $.each( locations, function(index, value ){

                    var name = value.organization==null?'':value.organization.organization_name;


                    var content = '<div id="iw-container">';
                    for(i = 0; i < value.services.length; i ++){
                        content +=  '<div class="iw-title"> <a href="/services/'+value.services[i].service_recordid+'">'+value.services[i].service_name+'</a></div>';
                    }
                    if(value.organization){

                        content += '<div class="iw-content">' +
                            '<div class="iw-subTitle">Organization</div>' +
                            '<a href="/organizations/' + value.organization.organization_recordid + '">' + value.organization.organization_name +'</a>';
                    }
                    if(value.address){
                        for(i = 0; i < value.address.length; i ++){
                            content +=  '<div class="iw-subTitle">Address</div>'+
                                '<a href="https://www.google.com/maps/dir/?api=1&destination=' + value.address[i].address_1 + '" target="_blank">' + value.address[i].address_1 +'</a>';
                        }
                    }
                    content += '</div>' +
                        '<div class="iw-bottom-gradient"></div>' +
                        '</div>';


                    if(value.location_latitude){

                        if(chip_address != '0' && avarageLatitude != '0' && avarageLongitude != '0' && value.location_latitude == avarageLatitude && value.location_longitude == avarageLongitude){
                            checkunKnownAddress.push(1)
                            let url = "http://maps.google.com/mapfiles/ms/icons/yellow-dot.png";
                            mymap.addMarker({

                                lat: value.location_latitude,
                                lng: value.location_longitude,
                                title: value.city,

                                infoWindow: {
                                    maxWidth: 250,
                                    content: (content)
                                },
                                icon: {
                                    url: url,
                                    scaledSize: new google.maps.Size(40, 40), // size
                                }
                            });
                        }else{
                            mymap.addMarker({

                                lat: value.location_latitude,
                                lng: value.location_longitude,
                                title: value.city,

                                infoWindow: {
                                    maxWidth: 250,
                                    content: (content)
                                }
                            });
                        }
                    }
                });
                let content = '';
                if(chip_address != '0'){
                    content =  '<div class="iw-content"><div class="iw-subTitle">Address</div>'+
                        '<a href="https://www.google.com/maps/dir/?api=1&destination=' + chip_address + '" target="_blank">' + chip_address +'</a></div>';
                }
                if(chip_address != '0' && avarageLatitude != '0' && avarageLongitude != '0' && checkunKnownAddress.length == 0){
                    let url = "http://maps.google.com/mapfiles/ms/icons/yellow-dot.png";
                    mymap.addMarker({

                        lat: avarageLatitude,
                        lng: avarageLongitude,

                        infoWindow: {
                            maxWidth: 250,
                            content: (content)
                        },
                        icon: {
                            url: url,
                            scaledSize: new google.maps.Size(40, 40), // size
                        }
                    });
                }
            }, 2000);

            $('.panel-link').on('click', function(e){
                if($(this).hasClass('target-population-link') || $(this).hasClass('target-population-child'))
                    return;
                var id = $(this).attr('at');
                selected_taxonomy_ids = id.toString();
                $("#selected_taxonomies").val(selected_taxonomy_ids);
                $("#filter").submit();
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

        function removeService(serviceId) {

            numServices--;
            console.log(numServices);
            console.log("called");
            console.log(serviceId);
            $(`#star${serviceId}`).attr('title', 'Save')
            .tooltip('_fixTitle')
            .tooltip('show');
            document.getElementById(`fav-save${serviceId}`).onclick = function() {removeService(`${serviceId}`)}
            document.getElementById(serviceId).style.display = "none";
            if (numServices <= 0) {
                document.getElementById("no-services").style.display = "block";
            }

            axios({
                method: 'get',
                url: `/services/${serviceId}/unsave`
            });

        }
    </script>
@endsection


