@extends('layouts.app')
@section('title')
Events
@stop
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
<style>
    .button {
        border: none;
        padding: 15px 32px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
        }
</style>
@section('content')
@include('layouts.filter_event')
@include('layouts.sidebar_organization')
<div class="top_services_filter">
    <div class="container" style="text-align:right;">
        <input type="checkbox" class="checkbox">
        <span class="checkmark">Calendar</span>
    </div>
</div>
<div class="inner_services">
    <div id="content" class="container">
    <div class="col-sm-12 p-0 card-columns">
            @foreach($events as $event)
            <div class="card" style="text-align:center;">
                <div class="card-block">
                    <img src=".{{$event->logo}}" alt="" title="" class="org_logo_img" style="width:75%; height:auto;">
                    <h4 class="card-title">
                        <a href="/events/{{$event->event_recordid}}" class="notranslate title_org" >{{$event->event_title}}</a>
                    </h4>
                    <h5>
                        <a href="/services/{{$event->event_service}}" class="notranslate title_org" >Service Name: {{$event->event_service_name}}</a>
                    </h5>
                    <div>
                    @foreach($taxonomy_list as $tax)
                        @if($tax->event_recordid == $event->event_recordid)
                            <button style="background-color:{{$tax->color}}; color:white;">{{$tax->taxonomy_name}}</button>
                        @endif
                    @endforeach
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="example col-md-12">
            <div class="row">
                <div class="col-md-6 pagination_text">
                    <p>Showing {{ $events->currentPage() * Request::get('paginate') - intval(Request::get('paginate') - 1)  }}-{{ $events->currentPage() * Request::get('paginate')  }} of {{ $events->total() }} items  <span>Show {{ Request::get('paginate') }} per page</span></p>
                </div>
                <div class="col-md-6 text-right">
                    {{ $events->appends(\Request::except('page'))->render() }}
                </div>
            </div>
        </div>
    </div>
</div>

@endsection


