<?php

use App\Model\Organization;
use App\Model\Service;
use App\Model\Suggest;
use App\Model\Error;
use Carbon\Carbon;
?>
@extends('layouts.app')
@section('title')
    {{$service->service_name}}
@stop

@section('content')

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @include('layouts.filter')
    {{-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css"> --}}


    <style type="text/css">
        /* .table a {
            text-decoration: none !important;
            color: rgba(40, 53, 147, .9);
            white-space: normal;
        }

        .footable.breakpoint>tbody>tr>td>span.footable-toggle {
            position: absolute;
            right: 25px;
            font-size: 25px;
            color: #000000;
        }

        .ui-menu .ui-menu-item .ui-state-active {
            padding-left: 0 !important;
        }

        ul#ui-id-1 {
            width: 260px !important;
        }

        #map {
            position: relative !important;
            z-index: 0 !important;
        }

        @media (max-width: 768px) {
            .property {
                padding-left: 30px !important;
            }

            #map {
                display: block !important;
                width: 100% !important;
            }
        } */
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
        <div class="top_services_filter" style="display: inline-block;width: 100%;">
            <div class="container">
            @include('layouts.sidebar')
            <!-- Types Of Services -->
            {{-- <div class="dropdown">
                <button type="button" class="btn dropdown-toggle"  id="exampleSizingDropdown1" data-toggle="dropdown" aria-expanded="false">
                    Types Of Services
                </button>
                <div class="dropdown-menu bullet" aria-labelledby="exampleSizingDropdown1" role="menu">
                    <a class="dropdown-item drop-sort">Service Name</a>
                </div>
            </div> --}}
            <!--end  Types Of Services -->

                <!-- download -->
                <div class="dropdown btn_download float-right">
                    <button type="button" class="float-right btn_share_download dropdown-toggle" id="" data-toggle="dropdown" aria-expanded="false">
                        <img src="/frontend/assets/images/download.png" alt="" title="" class="mr-10"> Download
                    </button>
                    <div class="dropdown-menu bullet" aria-labelledby="exampleBulletDropdown4" role="menu">
                        <a class="dropdown-item" href="/download_service_csv/{{$service->service_recordid}}" role="menuitem">Download CSV</a>
                        <a class="dropdown-item " href="/download_service/{{$service->service_recordid}}" role="menuitem">Download PDF</a>
                    </div>
                </div>
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
                <div class="row">
                    <div class="col-md-8">
                        <div class="card">
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
                                <h4 class="card-title">
                                    <a href="#">{{$service->service_name}}</a>
                                    @if (Auth::user() && Auth::user()->roles && $organization && Auth::user()->user_organization &&
                                    str_contains(Auth::user()->user_organization, $service->organizations()->first()->organization_recordid) && Auth::user()->roles->name == 'Organization Admin')
                                        <a href="/services/{{$service->service_recordid}}/edit" class="float-right">
                                            <i class="icon md-edit mr-0"></i>
                                        </a>
                                    @endif
                                    @if (Auth::user() && Auth::user()->roles && Auth::user()->roles->name == 'System Admin')
                                        <a href="/services/{{$service->service_recordid}}/edit" class="float-right">
                                            <i class="icon md-edit mr-0"></i>
                                        </a>
                                    @endif
                                    @if(isset($service->service_organization) && in_array($service->service_organization, $user_orgs))
                                        <img src="/images/registered.png" style="width:20px; height:20px;">
                                    @else
                                        <img src="/images/away.png" style="width:20px; height:20px;">
                                    @endif
                                </h4>
                                @if(isset($service->service_alternate_name))
                                    <h4>
                                        <span class="subtitle"><b>Program Name: </b></span> {{$service->service_alternate_name}}
                                    </h4>
                                @endif

                                <h4 class="org_title"><span class="subtitle"><b>Organization:</b></span>
                                    @if($service->service_organization!=0)
                                        @if(isset($service->organizations))
                                            <a class="panel-link" class="notranslate" href="/organizations/{{$service->organizations()->first()->organization_recordid}}">
                                                {{$service->organizations()->first()->organization_name}}</a>
                                        @endif
                                    @endif
                                </h4>

                                <h4 class="service-description" style="line-height: inherit;"> {!! nl2br($service->service_description) !!}</h4>

                                @if(isset($service->service_phones))
                                    <h4 style="line-height: inherit;">
                                <span><i class="icon md-phone font-size-18 vertical-align-top mr-0 pr-10"></i>
                                    <a href="tel:{{$phone_number_info}}">{{$phone_number_info}}</a>
                                </span>
                                    </h4>
                                @endif





                            <!--   <h4><span><i class="icon md-phone font-size-18 vertical-align-top mr-0 pr-10"></i> @foreach($service->phone as $phone) {!! $phone->phone_number !!} @endforeach</span></h4> -->

                                <h4 style="line-height: inherit;">
                                <span>
                                    <i class="icon md-globe font-size-18 vertical-align-top mr-0 pr-10"></i>
                                    @if($service->service_url!=NULL)<a href="{!! $service->service_url !!}" target="_blank">{!!
                                        $service->service_url !!}</a> @endif
                                </span>
                                </h4>


                                @if($service->service_email!=NULL)
                                    <h4 style="line-height: inherit;">
                                <span>
                                    <i class="icon md-email font-size-18 vertical-align-top mr-0 pr-10"></i>
                                    {{$service->service_email}}
                                </span>
                                    </h4>
                                @endif

                                @if(isset($service->languages) && count($service->languages) > 0)
                                <h4 style="line-height: inherit;">
                                <span>
                                    <i class="icon fa-language  font-size-18 vertical-align-top mr-0 pr-10"></i>
                                        @foreach($service->languages as $language)
                                            @if($loop->last)
                                                {{$language->language}}
                                            @else
                                                {{$language->language}},
                                            @endif
                                        @endforeach
                                </span>
                                </h4>
                                @endif                                

                                @if($service->service_application_process)
                                    <h4>
                                        <span class="subtitle"><b>Application: </b></span> {!! $service->service_application_process !!}
                                    </h4>
                                @endif

                                @if($service->service_wait_time)
                                    <h4><span class="subtitle"><b>Eligibility: </b></span> {{$service->service_wait_time}}</h4>
                                @endif

                                @if($service->service_fees)
                                    <h4><span class="subtitle"><b>Fees: </b></span> {{$service->service_fees}}</h4>
                                @endif

                                @if($service->service_accreditations)
                                    <h4><span class="subtitle"><b>Documents needed: </b></span> {{$service->service_accreditations}}
                                    </h4>
                                @endif

                                @if($service->service_licenses)
                                    <h4><span class="subtitle"><b>Income guidelines: </b></span> {{$service->service_licenses}}</h4>
                                @endif

                                @if($service->service_insurance)
                                    <h4><span class="subtitle"><b>Insurance Accepted: </b></span> {{$service->service_insurance}}</h4>
                                @endif


                                @if(isset($service->schedules()->first()->schedule_days_of_week))
                                    <h4><span class="subtitle"><b>Schedules</b></span><br />
                                        {{-- @foreach($service->schedules as $schedule)
                                        @if($loop->last)
                                        {{$schedule->schedule_days_of_week}} {{$schedule->schedule_opens_at}}
                                        {{$schedule->schedule_closes_at}}
                                        @else
                                        {{$schedule->schedule_days_of_week}} {{$schedule->schedule_opens_at}}
                                        {{$schedule->schedule_closes_at}},
                                        @endif
                                        @endforeach --}}

                                    </h4>

                                    @foreach($service->schedules as $schedule)

                                        @if ($schedule->schedule_days_of_week)

                                            <h4 style="color:{{ strtolower(\Carbon\Carbon::now()->format('l')) == $schedule->schedule_days_of_week ? 'blue' : '' }}">
                                                <b style="font-weight: 600;color: #000; letter-spacing: 0.5px;">{{ ucfirst($schedule->schedule_days_of_week) }} :</b>
                                                @if ($schedule->schedule_closed == null)
                                                    {{ $schedule->schedule_opens_at }} - {{ $schedule->schedule_closes_at }}
                                                @else
                                                    Closed
                                                @endif
                                            </h4>
                                        @endif
                                    @endforeach
                                    @if (count($holiday_schedules) > 0)
                                        <span style="margin-bottom: 20px;display: inline-block;font-weight: 600;text-decoration: underline; color: #5051db;cursor: pointer;" id="showHolidays"><a>Show holidays</a></span>
                                    @endif
                                    <div style="display: none;" id="holidays">
                                        <span class="subtitle"><b>Holidays</b></span><br />
                                        @foreach($holiday_schedules as $schedule)
                                                <h4 style="color: #000;" >
                                                    {{ $schedule->schedule_start_date }} to {{ $schedule->schedule_end_date }}  :
                                                    @if ($schedule->schedule_closed == null)
                                                        {{ $schedule->schedule_opens_at }} - {{ $schedule->schedule_closes_at }}
                                                    @else
                                                        Closed
                                                    @endif
                                                </h4>
                                        @endforeach
                                        <span style="margin-bottom: 20px;display: inline-block;font-weight: 600;text-decoration: underline; color: #5051db;cursor: pointer;" id="hideHolidays"><a>Hide holidays</a></span> <br>
                                    </div>
                                @endif

                                @if($service->updated_at)
                                    <h4><span class="subtitle"><b>Last update: </b></span> {{date_format($service->updated_at, 'm/d/Y')}}</h4>
                                @endif

                                <h4>
                                <span class="pl-0 category_badge subtitle"><!--b>Types of Services:</b-->
                                    @if($service->service_taxonomy != null)
                                        @foreach($service_taxonomy_info_list as $key => $service_taxonomy_info)
                                            <a class="panel-link {{str_replace(' ', '_', $service_taxonomy_info->taxonomy_name)}}"
                                               at="child_{{$service_taxonomy_info->taxonomy_recordid}}">{{$service_taxonomy_info->taxonomy_name}}</a>
                                        @endforeach
                                    @endif
                                </span>
                                </h4>
                                <h4>
                                <span class="subtitle" style="display:flex; justify-content: center;">
                                <button class = "suggest-button" type="button" data-toggle="modal" data-target="#suggestModal">
                                <img src="../../../../images/suggest-icon.png" alt="" width="25" height="25">
                                Make Suggestions</button>
                                <button class = "suggest-button" type="button" data-toggle="modal" data-target="#reportModal">
                                <img src="../../../../images/error-icon.png" alt="" width="25" height="25">
                                Report Errors</button>
                                </span>
                                </h4>
                                @if(!Auth::user() || (isset($service->service_organization) && Auth::user() && $service->service_organization != Auth::user()->user_organization))
                                    <h4 style="line-height: inherit;" class="text-center">
                                    <span>
                                        <a href="/register"> Are you the organization manager of this services? Create Your Account today! </a>
                                    </span>
                                    </h4>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 property">
                        {{-- <div class="pt-10 pb-10 pl-0 btn-download">
                            <a href="/download_service/{{$service->service_recordid}}"
                                class="btn btn-primary btn-button">Download PDF</a>
                            <button type="button" class="btn btn-primary btn-button" style="padding: 1px;">
                                <div class="sharethis-inline-share-buttons"></div>
                            </button>
                        </div> --}}
                        @if ((Auth::user() && Auth::user()->roles && Auth::user()->user_organization && str_contains(Auth::user()->user_organization,$service->organizations()->first()->organization_recordid) && Auth::user()->roles->name == 'Organization Admin'))
                            <div style="display: flex;" class="mb-20">
                                <div class="dropdown add_new_btn" style="width: 100%; float: right;">
                                    <button class="btn btn-primary dropdown-toggle btn-block" type="button" id="dropdownMenuButton-group"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-plus"></i> Add New
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton-new">
                                        {{-- <a href="/service_create/{{$service->service_recordid}}" id="add-new-services">Add New Service</a> --}}
                                        <a href="/facility_create/{{$service->service_recordid}}/service" id="add-new-services">Add New Facility</a>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if (Auth::user() && Auth::user()->roles && Auth::user()->roles->name == 'System Admin')
                            <div style="display: flex;" class="mb-20">
                                <div class="dropdown add_new_btn" style="width: 100%; float: right;">
                                    <button class="btn btn-primary dropdown-toggle btn-block" type="button" id="dropdownMenuButton-group"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-plus"></i> Add New
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton-new">
                                        {{-- <a href="/service_create/{{$service->service_recordid}}" id="add-new-services">Add New Service</a> --}}
                                        <a href="/contact_create/{{$service->service_recordid}}/service" id="add-new-services">Add New Contact</a>
                                        <a href="/facility_create/{{$service->service_recordid}}/service" id="add-new-services">Add New Facility</a>
                                    </div>
                                </div>
                            </div>
                        @endif
                    <!-- Locations area design -->
                        <div class="card">
                            <div class="card-block p-0">
                                <div id="map" style="width: 100%; height: 60vh;border-radius:12px;box-shadow: none;">
                                </div>
                                <div class="p-25">
                                    <h4 class="card_services_title">
                                        <b>Locations</b>
                                        @if (Auth::user() && Auth::user()->roles && $organization && Auth::user()->user_organization &&
                                        str_contains(Auth::user()->user_organization, $organization->organization_recordid) && Auth::user()->roles->name == 'Organization Admin')
                                            <a href="/facilities/{{$service->service_locations}}/edit" class="float-right">
                                                <i class="icon md-edit mr-0"></i>
                                            </a>
                                        @endif
                                    </h4>
                                    <div>
                                        @if(isset($service->locations))
                                            @if($service->locations != null)
                                                @foreach($service->locations as $location)

                                                    <div class="location_border">
                                                        <h4>
                                                            @if (Auth::user() && Auth::user()->roles && Auth::user()->roles->name == 'System Admin')
                                                                <a href="/facilities/{{$location->location_recordid}}/edit" class="float-right">
                                                                    <i class="icon md-edit mr-0"></i>
                                                                </a>
                                                            @endif
                                                        </h4>
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
                                                                <span><i class="icon fa-bus font-size-18 vertical-align-top "></i>
                                                                    {{$location->location_transportation}}
                                                                </span>
                                                                </h4>
                                                            @endif
                                                            @if($location->location_accessibility)
                                                                <h4>
                                                                <span><i class="icon fa-wheelchair font-size-18 vertical-align-top "></i>
                                                                    {{$location->location_accessibility}}
                                                                </span>
                                                                </h4>
                                                            @endif
                                                            @if($location->location_gender_equity)
                                                                <h4>
                                                                <span><i class="icon fa-transgender-alt font-size-18 vertical-align-top "></i>
                                                                    {{$location->location_gender_equity}}
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
                                                            @if(isset($location->schedules()->first()->schedule_days_of_week))
                                                                <h4 class="panel-text">
                                                                    <span class="badge bg-red"><b>Schedules:</b></span>
                                                                    @if($location->schedules != null)
                                                                        @foreach($location->schedules as $schedule)
                                                                            @if($loop->last)
                                                                                {{$schedule->schedule_days_of_week}} {{$schedule->schedule_opens_at}}
                                                                                {{$schedule->schedule_closes_at}}
                                                                            @else
                                                                                {{$schedule->schedule_days_of_week}} {{$schedule->schedule_opens_at}}
                                                                                {{$schedule->schedule_closes_at}},
                                                                            @endif
                                                                        @endforeach
                                                                    @endif
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

                        <!-- contact area design -->
                        @if($contact_info_list && count($contact_info_list) > 0)
                            <div class="card">
                                <div class="card-block">
                                    <h4 class="card_services_title"> Contacts </h4>
                                    @foreach($contact_info_list as $contact_info)
                                        <div class="location_border">
                                            @if (Auth::user() && Auth::user()->roles && Auth::user()->roles->name == 'System Admin')
                                                <a href="/contacts/{{$contact_info->contact_recordid}}/edit" class="float-right">
                                                    <i class="icon md-edit mr-0"></i>
                                                </a>
                                            @endif
                                            <table class="table ">
                                                <tbody>
                                                @if($contact_info->contact_name)
                                                    <tr>
                                                        <td>
                                                            <h4 class="m-0"><span><b>Name:</b></span> </h4>
                                                        </td>
                                                        <td>
                                                            <h4 class="m-0"><a href="/contacts/{{$contact_info->contact_recordid}}">{{$contact_info->contact_name}}</a></h4>
                                                        </td>
                                                    </tr>
                                                @endif
                                                @if($contact_info->contact_title)
                                                    <tr>
                                                        <td>
                                                            <h4 class="m-0"><span><b>Title:</b></span> </h4>
                                                        </td>
                                                        <td>
                                                            <h4 class="m-0"><span>{{$contact_info->contact_title}}</span></h4>
                                                        </td>
                                                    </tr>
                                                @endif
                                                @if($contact_info->contact_department)
                                                    <tr>
                                                        <td>
                                                            <h4 class="m-0"><span><b>Department:</b></span> </h4>
                                                        </td>
                                                        <td>
                                                            <h4 class="m-0"><span>{{$contact_info->contact_department}}</span></h4>
                                                        </td>
                                                    </tr>
                                                @endif
                                                @if($contact_info->contact_email)
                                                    <tr>
                                                        <td>
                                                            <h4 class="m-0"><span><b>Email:</b></span> </h4>
                                                        </td>
                                                        <td>
                                                            <h4 class="m-0"><span>{{$contact_info->contact_email}}</span></h4>
                                                        </td>
                                                    </tr>
                                                @endif
                                                @if($contact_info->contact_phones)
                                                    @if(isset($contact_info->phone->phone_number))
                                                        <tr>
                                                            <td>
                                                                <h4 class="m-0"><span><b>Phones:</b></span> </h4>
                                                            </td>
                                                            <td>
                                                                <h4 class="m-0"><span> {{$contact_info->phone->phone_number}}</span></h4>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                            <!-- contact area design -->
                            </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="suggestModal" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Suggest Form</h4>
                    </div>
                    <div class="modal-body">
                        <div class="card all_form_field">
                            <div class="card-block">
                                <h4 class="card-title mb-30 ">
                                    <p>Suggest A Change</p>
                                </h4>
                                {{-- <form action="/add_new_suggestion" method="GET"> --}}
                                {!! Form::open(['route' => 'suggest.store']) !!}
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Organization * </label>
                                            <p>Select the organization for which you're suggesting a change</p>
                                            {!! Form::select('suggest_organization',Organization::pluck('organization_name', "organization_recordid"),$service->organizations()->first() ? $service->organizations()->first()->organization_recordid : '',['class'=> 'form-control selectpicker','id' => 'suggest_organization','data-live-search' => 'true','data-size' => '5']) !!}
                                            @error('suggest_organization')
                                            <span class="error-message"><strong>{{ $message }}</strong></span>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label>Service * </label>
                                            <p>Select the service for which you're reporting</p>
                                            {!! Form::select('suggest_service',Service::pluck('service_name', "service_recordid"),$service->service_recordid,['class'=> 'form-control selectpicker','id' => 'suggest_service','data-live-search' => 'true','data-size' => '5']) !!}
                                        </div>
                                        <div class="form-group">
                                            <label>Suggestion * </label>
                                            <p>Explain what should be changed: Please be specific-reference the field that contains information which is incorrect or incomplete, and tell us what should be there instead. Thank you!</p>
                                            <textarea id="suggest_content" name="suggest_content" rows="3" required></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Your Name * </label>
                                            {!! Form::text('name',null,['class' => 'form-control','id' => 'name']) !!}
                                            @error('name')
                                            <span class="error-message"><strong>{{ $message }}</strong></span>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label>Your Email * </label>
                                            {!! Form::email('email',null,['class' => 'form-control','id' => 'email']) !!}

                                            @error('email')
                                            <span class="error-message"><strong>{{ $message }}</strong></span>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label>Your Phone </label>
                                            {!! Form::text('phone',null,['class' => 'form-control','id' => 'phone']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-12 mt-20 text-center">
                                        <!-- <a href="/contacts" class="btn btn-raised btn-lg btn_darkblack waves-effect waves-classic waves-effect waves-classic" id="view-contact-btn"><i class="fa fa-arrow-left"></i> Back</a> -->
                                        <button type="submit" class="btn btn-primary btn-lg btn_padding waves-effect waves-classic waves-effect waves-classic" id="save-suggestion-btn">Submit</button>
                                    </div>
                                </div>
                                {{-- </form> --}}
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div id="reportModal" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Report Form</h4>
                    </div>
                    <div class="modal-body">
                        <div class="card all_form_field">
                            <div class="card-block">
                                <h4 class="card-title mb-30 ">
                                    <p>Report Errors</p>
                                </h4>
                                {{-- <form action="/add_new_error" method="GET"> --}}
                                {!! Form::open(['route' => 'error.store']) !!}
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Organization * </label>
                                            <p>Select the organization for which you're reporting</p>
                                            {!! Form::select('error_organization',Organization::pluck('organization_name', "organization_recordid"),$service->organizations()->first() ? $service->organizations()->first()->organization_recordid : '',['class'=> 'form-control selectpicker','id' => 'error_organization','data-live-search' => 'true','data-size' => '5']) !!}
                                            @error('suggest_organization')
                                            <span class="error-message"><strong>{{ $message }}</strong></span>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label>Service * </label>
                                            <p>Select the service for which you're reporting</p>
                                            {!! Form::select('error_service',Service::pluck('service_name', "service_recordid"),$service->service_recordid,['class'=> 'form-control selectpicker','id' => 'error_service','data-live-search' => 'true','data-size' => '5']) !!}
                                        </div>
                                        <div class="form-group">
                                            <label>Error reported * </label>
                                            <p>Sorry for the error. Please tell us the problem:</p>
                                            <textarea id="error_content" name="error_content" class="selectpicker" rows="3"></textarea>
                                            @error('error_content')
                                            <span class="error-message"><strong>{{ $message }}</strong></span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Your Name * </label>
                                            {!! Form::text('error_name',null,['class' => 'form-control','id' => 'error_name']) !!}
                                            @error('name')
                                            <span class="error-message"><strong>{{ $message }}</strong></span>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label>Your Email </label>
                                            {!! Form::email('error_email',null,['class' => 'form-control','id' => 'error_email']) !!}

                                            @error('email')
                                            <span class="error-message"><strong>{{ $message }}</strong></span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label>Your Phone </label>
                                            {!! Form::text('error_phone',null,['class' => 'form-control','id' => 'error_phone']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-12 mt-20 text-center">
                                        <!-- <a href="/contacts" class="btn btn-raised btn-lg btn_darkblack waves-effect waves-classic waves-effect waves-classic" id="view-contact-btn"><i class="fa fa-arrow-left"></i> Back</a> -->
                                        <button type="submit" class="btn btn-primary btn-lg btn_padding waves-effect waves-classic waves-effect waves-classic" id="save-suggestion-btn">Submit</button>
                                    </div>
                                </div>
                                {{-- </form> --}}
                                {!! Form::close() !!}
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
                // console.log(id);
                // $("#category_" +  id).prop( "checked", true );
                // $("#checked_" +  id).prop( "checked", true );
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