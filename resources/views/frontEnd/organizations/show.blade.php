<?php
use App\Model\Organization;
use App\Model\Service;
use App\Model\Suggest;
use App\Model\Error;
use App\Model\Event;
use Carbon\Carbon;
?>
@extends('layouts.app')
@section('title')
{{$organization->organization_name}}
@stop
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

@section('content')
@include('layouts.filter_organization')
@include('layouts.sidebar_organization')
<style type="text/css">
    .grid-container {
        display: grid;
        grid-template-columns: 200px 100px 80px;
        overflow: scroll;
        margin-top: 5px;
    }
    /* Responsive layout - makes a one column layout instead of a two-column layout */
    @media (max-width: 800px) {
        .flex-container {
            flex-direction: column;
        }
    }
</style>
<div class="breadcume_top">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/organizations">Organizations</a></li>
                    <li class="breadcrumb-item active" aria-current="page">
                        @if($organization->organization_name!='')
                            {{$organization->organization_name}}
                        @endif
                    </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
<div class="inner_services">
    <div id="content" class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="card detail_services">
                    <div class="card-block">
                        <h4 class="card-title m-0">
                            <a href="">@if($organization->organization_logo_x)
                                <img src="{{$organization->organization_logo_x}}" height="80">@endif
                                {{$organization->organization_name}}
                                @if($organization->organization_alternate_name!='')
                                    ({{$organization->organization_alternate_name}})
                                @endif
                            </a>
                            @if (Auth::user() && Auth::user()->roles && Auth::user()->status == 0 && Auth::user()->user_organization && str_contains(Auth::user()->user_organization,
                            $organization->organization_recordid) && Auth::user()->roles->name == 'Organization Admin')
                            <a href="/organizations/{{$organization->organization_recordid}}/edit" class="float-right">
                                <i class="icon md-edit mr-0"></i>
                            </a>
                            @endif
                            @if (Auth::user() && Auth::user()->roles && Auth::user()->status == 0 && Auth::user()->roles->name == 'System Admin')
                            <a href="/organizations/{{$organization->organization_recordid}}/edit" class="float-right">
                                <i class="icon md-edit mr-0"></i>
                            </a>
                            @endif
                        </h4>
                        <h4>
                            <span class="subtitle"><b>Status:</b></span>
                            {{$organization->organization_status_x}}
                        </h4>
                        {{-- <h4 class="panel-text"><span class="badge bg-red">Alternate Name:</span> {{$organization->organization_alternate_name}}
                        </h4> --}}
                        <h4 style="line-height:inherit"> {!! nl2br($organization->organization_description) !!}</h4>
                        <h4 style="line-height: inherit;">
                            <span><i class="icon md-globe font-size-18 vertical-align-top pr-10 m-0"></i>
                                <a href="{{$organization->organization_url}}"> {{$organization->organization_url}}</a>
                            </span>
                        </h4>
                        @if($organization->phones)
                        <h4 style="line-height: inherit;">
                            <span><i class="icon md-phone font-size-18 vertical-align-top pr-10  m-0"></i>
                                @foreach($organization->phones as $phone)
                                @if ($phone->phone_number)
                                <a href="tel:{{$phone->phone_number}}">{{$phone->phone_number}}
                                </a>
                                @endif
                                @endforeach
                            </span>
                        </h4>
                        @endif
                        @if(isset($organization->organization_forms_x_filename))
                        <h4 class="py-10" style="line-height: inherit;"><span class="mb-10"><b>Referral Forms:</b></span>
                            <a href="{{$organization->organization_forms_x_url}}" class="panel-link">
                                {{$organization->organization_forms_x_filename}}
                            </a>
                        </h4>
                        @endif
                    </div>
                </div>

                <!-- Services area design -->
                @if(isset($organization_services))
                <div class="card">
                    <div class="card-block ">
                        <h4 class="card_services_title">Services
                            (@if(isset($organization_services)){{$organization_services->count()}}@else 0 @endif)
                        </h4>
                        @foreach($organization_services as $service)
                        <div class="organization_services">
                            <h4 class="card-title">
                                <a href="/services/{{$service->service_recordid}}">{{$service->service_name}}</a>
                                @if (Auth::user() && Auth::user()->roles && Auth::user()->status == 0 && Auth::user()->user_organization && str_contains(Auth::user()->user_organization,
                                $organization->organization_recordid) && Auth::user()->roles->name == 'Organization Admin')
                                <a href="/services/{{$service->service_recordid}}/edit" class="float-right">
                                    <i class="icon md-edit mr-0"></i>
                                </a>
                                @endif
                                @if (Auth::user() && Auth::user()->roles && Auth::user()->roles->name == 'System Admin' && Auth::user()->status == 0)
                                <a href="/services/{{$service->service_recordid}}/edit" class="float-right">
                                    <i class="icon md-edit mr-0"></i>
                                </a>
                                @endif
                            </h4>
                            <h4 style="line-height: inherit;">{!! Str::limit($service->service_description, 200) !!}</h4>
                            <h4 style="line-height: inherit;">
                                <span><i class="icon md-phone font-size-18 vertical-align-top pr-10  m-0"></i>
                                    @foreach($service->phone as $phone)
                                    <a href="tel:{{$phone->phone_number}}">
                                        {!! $phone->phone_number !!}
                                    </a>
                                      @endforeach
                                  </span>
                            </h4>
                            <h4>
                                <span>
                                    <i class="icon md-pin font-size-18 vertical-align-top pr-10  m-0"></i>
                                    @if(isset($service->address))
                                    @foreach($service->address as $address)
                                    {{ $address->address_1 }} {{ $address->address_2 }} {{ $address->address_city }}
                                    {{ $address->address_state_province }} {{ $address->address_postal_code }}
                                    @endforeach
                                    @endif
                                </span>
                            </h4>

                            @if($service->service_details!=NULL)
                            @php
                            $show_details = [];
                            @endphp
                            @foreach($service->details->sortBy('detail_type') as $detail)
                            @php
                            for($i = 0; $i < count($show_details); $i ++){ if($show_details[$i]['detail_type']==$detail->
                            detail_type)
                            break;
                            }
                            if($i == count($show_details)){
                            $show_details[$i] = array('detail_type'=> $detail->detail_type, 'detail_value'=>
                            $detail->detail_value);
                            }
                            else{
                            $show_details[$i]['detail_value'] = $show_details[$i]['detail_value'].',
                            '.$detail->detail_value;
                            }
                            @endphp
                            @endforeach
                            @foreach($show_details as $detail)
                            <h4><span class="subtitle"><b>{{ $detail['detail_type'] }}:</b></span> {!!
                                $detail['detail_value'] !!}</h4>
                            @endforeach
                            @endif
                            <h4>
                                <span class="pl-0 category_badge subtitle"><b>Types of Services:</b>
                                    @if($service->service_taxonomy != 0 || $service->service_taxonomy==null)
                                    @php
                                    $names = [];
                                    @endphp
                                    @foreach($service->taxonomy->sortBy('taxonomy_name') as $key => $taxonomy)
                                    @if(!in_array($taxonomy->taxonomy_grandparent_name, $names))
                                    @if($taxonomy->taxonomy_grandparent_name && $taxonomy->taxonomy_parent_name !=
                                    'Target Populations')
                                    <a class="panel-link {{str_replace(' ', '_', $taxonomy->taxonomy_grandparent_name)}}"
                                        at="{{str_replace(' ', '_', $taxonomy->taxonomy_grandparent_name)}}">{{$taxonomy->taxonomy_grandparent_name}}</a>
                                    @php
                                    $names[] = $taxonomy->taxonomy_grandparent_name;
                                    @endphp
                                    @endif
                                    @endif
                                    @if(!in_array($taxonomy->taxonomy_parent_name, $names))
                                    @if($taxonomy->taxonomy_parent_name && $taxonomy->taxonomy_parent_name != 'Target
                                    Populations')
                                    @if($taxonomy->taxonomy_grandparent_name)
                                    <a class="panel-link {{str_replace(' ', '_', $taxonomy->taxonomy_parent_name)}}"
                                        at="{{str_replace(' ', '_', $taxonomy->taxonomy_grandparent_name)}}_{{str_replace(' ', '_', $taxonomy->taxonomy_parent_name)}}">{{$taxonomy->taxonomy_parent_name}}</a>
                                    @endif
                                    @php
                                    $names[] = $taxonomy->taxonomy_parent_name;
                                    @endphp
                                    @endif
                                    @endif
                                    @if(!in_array($taxonomy->taxonomy_name, $names))
                                    @if($taxonomy->taxonomy_name && $taxonomy->taxonomy_parent_name != 'Target
                                    Populations')
                                    <a class="panel-link {{str_replace(' ', '_', $taxonomy->taxonomy_name)}}"
                                        at="{{$taxonomy->taxonomy_recordid}}">{{$taxonomy->taxonomy_name}}</a>
                                    @php
                                    $names[] = $taxonomy->taxonomy_name;
                                    @endphp
                                    @endif
                                    @endif

                                    @endforeach
                                    @endif
                                </span>
                            </h4>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
                <!-- Services area design -->
                <!-- Events area design -->
                @if(Auth::user() && Auth::user()->roles && str_contains(Event::pluck('event_organization'), $organization->organization_recordid))
                <div class="card">
                    <div class="card-block">
                        <h4 class="card_services_title">Events</h4>
                        <h4 style="margin-top: 20px;">
                            @foreach($event_list as $key => $event)
                            <div class="organization_services">
                                    <h4 class="card-title">
                                        <a href="/events/{{$event->event_recordid}}">{{$event->event_title}}</a>
                                        @if (Auth::user() && Auth::user()->roles && Auth::user()->status == 0 && Auth::user()->user_organization && str_contains(Auth::user()->user_organization,
                                        $organization->organization_recordid) && Auth::user()->roles->name == 'Organization Admin')
                                        <a href="/events/{{$event->event_recordid}}/edit" class="float-right">
                                            <i class="icon md-edit mr-0"></i>
                                        </a>
                                        @endif
                                        @if (Auth::user() && Auth::user()->roles && Auth::user()->roles->name == 'System Admin' && Auth::user()->status == 0)
                                        <a href="/events/{{$event->event_recordid}}/edit" class="float-right">
                                            <i class="icon md-edit mr-0"></i>
                                        </a>
                                        @endif
                                    </h4>
                                    <h4 style="line-height: inherit;">Service: {{$event->event_service_name}}</h4>
                                    <h4 style="line-height: inherit;">Contact Name: {{$event->event_contact_name}}</h4>
                                    <h4 style="line-height: inherit;">Contact Email: {{$event->event_contact_email}}</h4>
                                    <h4 style="line-height: inherit;">Contact Number: {{$event->event_contact_phone}}</h4>
                                
                            </div>
                            @endforeach
                        </h4>
                    </div>
                </div>
                @endif
                <!-- Events area design -->
                <!--Error changing design-->
                @if(Auth::user() && Auth::user()->roles && str_contains(Error::pluck('error_organization'), $organization->organization_recordid))
                <div class="card">
                    <div class="card-block">
                        <h4 class="card_services_title">Reported Errors</h4>
                        <h4 style="margin-top: 20px;">
                            @foreach($error_list as $key => $error)
                            <div>
                                <div class="grid-container">
                                    <div>Created at: {{$error->created_at}}</div>
                                    <div><button class = "myBtn" id="myBtn" onclick="ShowModal('myModal-{{$error->error_recordid}}')">See details</button></div>
                                    <div><button type="button" class="red_btn" id="delete-error-btn" value="{{$error->error_recordid}}" data-toggle="modal" data-target=".bs-delete-modal-lg" >Delete</button></div>
                                    
                                </div>
                                <div id="myModal-{{$error->error_recordid}}" class="modal" role="dialog">
                                    <div class="modal-dialog">
                                    <!-- Modal content-->
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" onclick="closeModal('myModal-{{$error->error_recordid}}')">&times;</button>
                                                <h4 class="modal-title">Report details</h4>
                                            </div>
                                            <div class="modal-body">
                                                <div class="card all_form_field">
                                                        <div class="card-block">
                                                            <div>Service: {{$error->error_service_name}}</div>
                                                            <div>Reporting Content: {{$error->error_content}}</div>
                                                            <div>Reporter: {{$error->error_username}}</div>
                                                            <div>Contact Email: {{$error->error_user_email}}</div>
                                                            <div>Contact Phone: {{$error->error_user_divhone}}</div>
                                                            <div>Created Time: {{$error->created_at}}</div>
                                                        </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="modal fade bs-delete-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('delete_error') }}" method="POST" id="error_delete_filter">
                                                {!! Form::token() !!}
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                                                    </button>
                                                    <h4 class="modal-title" id="myModalLabel">Delete service</h4>
                                                </div>
                                                <div class="modal-body text-center">
                                                    <input type="hidden" id="error_recordid" name="error_recordid">
                                                    <h4>Are you sure to delete this error?</h4>
                                                    <div id="{{$error->error_service_name}}"></div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-raised btn-lg btn_danger waves-effect waves-classic waves-effect waves-classic">Delete</button>
                                                    <button type="button" class="btn btn-raised btn-lg btn_darkblack waves-effect waves-classic waves-effect waves-classic" data-dismiss="modal">Close</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </h4>
                    </div>
                </div>
                @endif
                <!--Error changing design-->
                <!-- comment area design -->

                <!-- Comment area not used in this project. Hidden to users -->
                @if (Auth::user() && Auth::user()->roles && false)
                    <div class="card">
                        <div class="card-block">
                            <h4 class="card_services_title">Comments</h4>
                            <div class="comment-body media-body pt-30">
                                @foreach($comment_list as $key => $comment)
                                <div class="main_commnetbox">
                                    <div class="comment_inner">
                                        <div class="commnet_letter">

                                            {{ $comment->comments_user_firstname[0]  . (isset($comment->comments_user_lastname[0]) ? $comment->comments_user_lastname[0] : $comment->comments_user_firstname[1]) }}
                                        </div>
                                        <div class="comment_author">
                                            <h5>
                                                <a class="comment-author" href="javascript:void(0)">
                                                    {{$comment->comments_user_firstname}} {{$comment->comments_user_lastname}}
                                                </a>
                                            </h5>
                                            <p class="date">{{$comment->comments_datetime}}</p>
                                        </div>
                                    </div>
                                    <div class="commnet_content">
                                        <p>{{$comment->comments_content}}</p>
                                    </div>
                                </div>
                                @endforeach
                                <a class="active comment_add" id="reply-btn" href="javascript:void(0)" role="button">Add a comment</a>

                                {!! Form::open(['route' => ['organization_comment',$organization->organization_recordid]]) !!}
                                    <div class="form-group">
                                        <textarea class="form-control" id="reply_content" name="comment" rows="3">
                                          </textarea>
                                        @error('comment')
                                            <span class="error-message"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary btn-lg waves-effect waves-classic" style="padding:22.5px 54px" >Post</button>
                                        {{-- <button type="button" id="close-reply-window-btn" class="btn btn-raised btn-lg btn_darkblack waves-effect waves-classic">Close</button> --}}
                                    </div>
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                @endif
                <!-- comment area design -->

            </div>

            <div class="col-md-4 property">
                @if ((Auth::user() && Auth::user()->roles && Auth::user()->user_organization  && Auth::user()->status == 0 && str_contains(Auth::user()->user_organization,$organization->organization_recordid) && Auth::user()->roles->name == 'Organization Admin') )
                <div style="display: flex;" class="mb-20">
                    <div class="dropdown add_new_btn" style="width: 100%; float: right;">
                        <button class="btn btn-primary dropdown-toggle btn-block" type="button" id="dropdownMenuButton-group"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-plus"></i> Add New
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton-new">
                            <a href="{{ route('services.create') }}" id="add-new-services">Add New Service</a>
                            <a href="{{ route('facilities.create') }}" id="add-new-services">Add New Facility</a>
                            <a href="{{ route('events.create') }}" id="add-new-services">Add New Event</a>
                        </div>
                    </div>
                </div>
                @endif
                
                @if(Auth::user() && Auth::user()->roles && Auth::user()->roles->name == 'System Admin' && Auth::user()->status == 0 )
                <div style="display: flex;" class="mb-20">
                    <div class="dropdown add_new_btn" style="width: 100%; float: right;">
                        <button class="btn btn-primary dropdown-toggle btn-block" type="button" id="dropdownMenuButton-group"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-plus"></i> Add New
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton-new">
                        <a href="{{ route('organizations.create') }}" id="add-new-services">Add New Organization</a>
                            <a href="{{ route('services.create') }}" id="add-new-services">Add New Service</a>
                            <a href="{{ route('facilities.create') }}" id="add-new-services">Add New Facility</a>
                            <a href="{{ route('contacts.create') }}" id="add-new-services">Add New Contact</a>
                            <a href="{{ route('events.create') }}" id="add-new-services">Add New Event</a>
                        </div>
                    </div>
                </div>
                @endif
                @if (false && Auth::user() && Auth::user()->roles && Auth::user()->roles->name != 'Organization Admin')
                <div class="pt-10 pb-10 pl-0 btn-download">
                    {{-- <form method="GET" action="/organizations/{{$organization->organization_recordid}}/tagging"
                        id="organization_tagging"> --}}
                        {!! Form::open(['route' => ['organization_tag',$organization->organization_recordid]]) !!}
                        <div class="row" id="tagging-div">
                            <div class="col-md-9">
                                <input type="text" class="form-control" id="tokenfield" name="tokenfield" value="{{$organization->organization_tag}}" />
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn_darkblack" style="float: right;">
                                    <i class="fas fa-save"></i>
                                </button>
                            </div>
                        </div>
                    {{-- </form> --}}
                    {!! Form::close() !!}
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
                                @if (Auth::user() && Auth::user()->roles && isset($service) && Auth::user()->status == 0 && Auth::user()->user_organization && str_contains(Auth::user()->user_organization,
                                $organization->organization_recordid) && Auth::user()->roles->name == 'Organization Admin')
                                <a href="/facilities/{{$service->service_locations}}/edit" class="float-right">
                                    <i class="icon md-edit mr-0"></i>
                                </a>
                                @endif
                            </h4>
                            <div>
                                @if($location_info_list)
                                @foreach($location_info_list as $location)

                                <div class="location_border">
                                    <h4>
                                        @if (Auth::user() && Auth::user()->roles && Auth::user()->roles->name == 'System Admin')
                                        <a href="/facilities/{{$location->location_recordid}}/edit" class="float-right">
                                            <i class="icon md-edit mr-0"></i>
                                        </a>
                                        @endif
                                        <span><i class="icon fas fa-building font-size-18 vertical-align-top"></i>
                                            <a href="/facilities/{{$location->location_recordid}}">{{$location->location_name}}</a>
                                        </span>
                                    </h4>
                                    <h4>
                                        <span><i class="icon md-pin font-size-18 vertical-align-top"></i>
                                            @if(isset($location->address))
                                            @foreach($location->address as $address)
                                            {{ $address->address_1 }} {{ $address->address_2 }} {{ $address->address_city }}
                                            {{ $address->address_state_province }} {{ $address->address_postal_code }}
                                            @endforeach
                                            @endif
                                        </span>
                                    </h4>
                                    <h4>
                                        <span><i class="icon md-phone font-size-18 vertical-align-top  "></i>
                                            @php
                                                $phones = '';
                                            @endphp
                                            @foreach($location->phones as $phone)
                                            @php
                                            $phoneNo = '<a href="tel:'.$phone->phone_number.'">'.$phone->phone_number.' , ' .'</a>';
                                            $phones .= $phoneNo;
                                            @endphp
                                            @endforeach
                                            @if(isset($phones))
                                            {!! rtrim($phones, ',') !!}
                                            @endif
                                        </span>
                                    </h4>
                                </div>
                                @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Locations area design -->


                <!-- Websitye rating area design -->
                @if (Auth::user() && Auth::user()->roles && Auth::user()->roles->name == 'System Admin')
                    @if ($organization->organization_website_rating)
                        <div class="card">
                            <div class="card-block">
                                <h4 class="card_services_title">Website Rating:  {{$organization->organization_website_rating}}</h4>
                                {{-- <div class="rating-body media-body" style="text-align: center;">
                                    <h1><b>{{$organization->organization_website_rating}}</b></h1>
                                </div> --}}
                            </div>
                        </div>
                    @endif
                @endif
                <!-- Websitye rating area design -->

                <!-- Contact area design -->
                @if(isset($organization->contact))
                    @if ($organization->contact->count() > 0 && ((Auth::user() && Auth::user()->roles && Auth::user()->roles->name == 'System Admin') || (Auth::user() && Auth::user()->roles && Auth::user()->user_organization && str_contains(Auth::user()->user_organization,
                    $organization->organization_recordid) && Auth::user()->roles->name == 'Organization Admin')))
                    <div class="card">
                        <div class="card-block">
                            <h4 class="card_services_title"> Contacts
                                {{-- (@if(isset($organization->contact)){{$organization->contact->count()}}@else 0 @endif) --}}
                            </h4>
                            @foreach($organization->contact as $contact_info)
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
                                            @if($contact_info->phone->count())
                                                <tr>
                                                    <td>
                                                        <h4 class="m-0"><span><b>Phones:</b></span> </h4>
                                                    </td>
                                                    <td>
                                                        <h4 class="m-0"><span>
                                                            @foreach($contact_info->phone as $phone_info)
                                                            <a href="tel:'.$phone_info->phone_number.'">
                                                                {{$phone_info->phone_number}},
                                                            </a>
                                                            @endforeach
                                                        </span></h4>
                                                    </td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                @endif
                <!-- Contact area design -->

                <!-- Session area design -->
                @if (false && ((Auth::user() && Auth::user()->roles && Auth::user()->user_organization && str_contains(Auth::user()->user_organization,$organization->organization_recordid) && Auth::user()->roles->name == 'Organization Admin') || Auth::user() && Auth::user()->roles && Auth::user()->roles->name == 'System Admin'))
                    <div class="card">
                        <div class="card-block">
                            <h4 class="card_services_title mb-20">Session
                                <a class="float-right comment_add" href="/session_create/{{$organization->organization_recordid}}" >Add Session</a>
                                <a href="{{route('session_download',$organization->organization_recordid)}}">
                                    <img src="/frontend/assets/images/download.png" alt="" title="" class="mr-10">
                                </a>
                            </h4>
                            <div class="session-body media-body col-md-12 p-0">
                                <table class="table jambo_table bulk_action nowrap" id="tbl-session">
                                    <thead>
                                        <tr>
                                            <th class="default-active">Date</th>
                                            <th class="default-active">Status</th>
                                            <th class="default-active">Edits</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($session_list as $key => $session)
                                        <tr>
                                            <td>
                                                <h4 class="m-0">
                                                    <a href="/session/{{$session->session_recordid}}" target="_blank" style="color: #1b1b1b; text-decoration:none">
                                                        {{$session->session_performed_at}}
                                                    </a>
                                                </h4>
                                            </td>
                                            <td><h4 class="m-0"><span>{{$session->session_verification_status}}</span></h4></td>
                                            <td><h4 class="m-0"><span>{{$session->session_edits}}</span></h4></td>
                                        </tr>
                                        @endforeach
                                    <tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif
                <!-- Session area design -->



            </div>
        </div>
    </div>
    <!-- <div class="modal fade bs-delete-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('delete_error') }}" method="POST" id="error_delete_filter">
                    {!! Form::token() !!}
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                        </button>
                        <h4 class="modal-title" id="myModalLabel">Delete service</h4>
                    </div>
                    <div class="modal-body text-center">
                        <input type="hidden" id="error_recordid" name="error_recordid">
                        <h4>Are you sure to delete this error?</h4>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-raised btn-lg btn_danger waves-effect waves-classic waves-effect waves-classic">Delete</button>
                        <button type="button" class="btn btn-raised btn-lg btn_darkblack waves-effect waves-classic waves-effect waves-classic" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div> -->
</div>


<script type="text/javascript" src="http://sliptree.github.io/bootstrap-tokenfield/dist/bootstrap-tokenfield.js">
</script>
<script type="text/javascript"
    src="http://sliptree.github.io/bootstrap-tokenfield/docs-assets/js/typeahead.bundle.min.js"></script>

<script>

    var tag_source = <?php print_r(json_encode($existing_tags)) ?>;
  $(document).ready(function() {
      $('#tokenfield').tokenfield({
      autocomplete: {
          source: tag_source,
          delay: 100
      },
      showAutocompleteOnFocus: true
      });
  });
  $(document).ready(function() {
      $('.comment-reply').hide();
      $('#reply_content').val('');
  });
  $(document).ready(function(){
      var locations = <?php print_r(json_encode($locations)) ?>;
      var organization = <?php print_r(json_encode($organization->organization_name)) ?>;
      var maplocation = <?php print_r(json_encode($map)) ?>;
    //   console.log(locations);
      if(maplocation.active == 1){
        avglat = maplocation.lat;
        avglng = maplocation.long;
        zoom = maplocation.zoom_profile;
      }
      else
      {
          avglat = 40.730981;
          avglng = -73.998107;
          zoom = 12;
      }
      latitude = locations[0].location_latitude;
      longitude = locations[0].location_longitude;
      if(latitude == null){
        latitude = avglat;
        longitude = avglng;
      }
      var map = new google.maps.Map(document.getElementById('map'), {
          zoom: zoom,
          center: {lat: parseFloat(latitude), lng: parseFloat(longitude)}
      });
      var latlongbounds = new google.maps.LatLngBounds();
      var markers = locations.map(function(location, i) {
          var position = {
              lat: location.location_latitude,
              lng: location.location_longitude
          }
          var latlong = new google.maps.LatLng(position.lat, position.lng);
          latlongbounds.extend(latlong);
           var content = '<div id="iw-container">';
                   for(i = 0; i < location.services.length; i ++){
                            content +=  '<div class="iw-title"> <a href="/services/'+location.services[i].service_recordid+'">'+location.services[i].service_name+'</a></div>';
                        }
                        // '<div class="iw-title"> <a href="/services/'+ location.service_recordid +'">' + location.service_name + '</a> </div>' +
                        content += '<div class="iw-content">' +
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
      if (locations.length > 1) {
          map.fitBounds(latlongbounds);
      }
  });
  $(document).ready(function() {
    var showChar = 250;
    var ellipsestext = "...";
    var moretext = "More";
    var lesstext = "Less";
    $('.more').each(function() {
      var content = $(this).html();
      if(content.length > showChar) {
        var c = content.substr(0, showChar);
        var h = content.substr(showChar, content.length - showChar);
        var html = c + '<span class="moreelipses">'+ellipsestext+'</span><span class="morecontent"><span>' + h + '</span>&nbsp;&nbsp;<a href="" class="morelink">'+moretext+'</a></span>';
        $(this).html(html);
      }
    });
    $(".morelink").click(function(){
      if($(this).hasClass("less")) {
        $(this).removeClass("less");
        $(this).html(moretext);
      } else {
        $(this).addClass("less");
        $(this).html(lesstext);
      }
      $(this).parent().prev().toggle();
      $(this).prev().toggle();
      return false;
    });
    $('.panel-link').on('click', function(e){
          if($(this).hasClass('target-population-link') || $(this).hasClass('target-population-child'))
              return;
          var id = $(this).attr('at');
        //   console.log(id);
          $("#category_" +  id).prop( "checked", true );
          $("#checked_" +  id).prop( "checked", true );
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
  $("#reply-btn").on('click', function(e) {
      e.preventDefault();
      $('.comment-reply').show();
  });
  $("#close-reply-window-btn").on('click', function(e) {
      e.preventDefault();
      $('.comment-reply').hide();
  });
  $('button#delete-error-btn').on('click', function() {
        var value = $(this).val();
        $('input#error_recordid').val(value);
    });
  function ShowModal(id)
{
  var modal = document.getElementById(id);
  modal.style.display = "block";
}
function closeModal(id)
{
  var modal = document.getElementById(id);
  modal.style.display = "none";
}
</script>
@endsection
