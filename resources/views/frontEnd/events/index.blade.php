@extends('layouts.app')
@section('title')
Organizations
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
@include('layouts.filter_organization')
@include('layouts.sidebar_organization')
<div class="inner_services">
    <div id="content" class="container">
    <div class="col-sm-12 p-0 card-columns">
            @foreach($events as $event)
            <div class="card" style="text-align:center;">
                <div class="card-block">
                    @if($event->event_recordid == 1)
                    <img src="/frontend/assets/images/test1.png" alt="" title="" class="org_logo_img" style="width:75%; height:auto;">
                    @endif
                    @if($event->event_recordid == 2)
                    <img src="/frontend/assets/images/test2.png" alt="" title="" class="org_logo_img" style="width:75%; height:auto;">
                    @endif
                    @if($event->event_recordid == 3)
                    <img src="/frontend/assets/images/test3.png" alt="" title="" class="org_logo_img" style="width:75%; height:auto;">
                    @endif
                    <h4 class="card-title">
                        <a href="" class="notranslate title_org" >{{$event->event_title}}</a>
                    </h4>
                    <h5>
                        <a href="" class="notranslate title_org" >Service Name: {{$event->event_service_name}}</a>
                    </h5>
                    
                    @if($event->event_recordid == 1)
                    <div>
                    <button style="background-color:red; color:white;">Covid-19</button>
                    <button style="background-color:green; color:white;">Health</button>
                    <button style="background-color:orange; color:white;">Volunteer</button>
                    </div>
                    @endif
                    @if($event->event_recordid == 2)
                    <div>
                    <button style="background-color:orange; color:white;">Volunteer</button>
                    <button style="background-color:blue; color:white;">Law Enforcement</button>
                    </div>
                    @endif
                    @if($event->event_recordid == 3)
                    <div>
                    <button style="background-color:pink; color:white;">Food Resources</button>
                    </div>
                    @endif
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


