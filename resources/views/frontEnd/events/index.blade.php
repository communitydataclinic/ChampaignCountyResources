@extends('layouts.app')
@section('title')
Events
@stop
<link href="{{ URL::asset('css/main.css') }}" rel='stylesheet' />
<script src="{{ URL::asset('js/main.js') }}"></script>
<style>
    .button {
        border: none;
        padding: 15px 32px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
        }
    #calendar {
    max-width: 80%;
    margin: 0 auto;
  }
</style>
@section('content')
@include('layouts.filter_event')
@include('layouts.sidebar_organization')
<div class="top_services_filter">
    <div class="container" style="text-align:right;">
        <input type="checkbox" class="checkbox" onclick="myFunction()" id="myCheck">
        <span class="checkmark">Calendar</span>
    </div>
</div>
<div class="inner_services" id="inner_services">
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
                        <a href="" class="notranslate title_org" >Date: {{$event->event_time}}</a>
                    </h5>
                    <h5>
                        <a href="/services/{{$event->event_service}}" class="notranslate title_org" >Organized by: {{$event->event_organization_name}}</a>
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
<div id="wrap">
    <div id="calendar" class="calendar" style="display:none;"></div>
</div>
<script>
function myFunction(){
    var checkBox = document.getElementById("myCheck");
    var listDisplay = document.getElementById("inner_services");
    var calendar = document.getElementById("calendar"); 
    if(checkBox.checked == true){
        calendar.style.display = "block";
        listDisplay.style.display = "none";
        window.dispatchEvent(new Event('resize'));
    }else{
        calendar.style.display = "none";
        listDisplay.style.display = "block";
    }

}
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var today = new Date();
    var date = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate();
    var sets= {!! json_encode($events->toArray(), JSON_HEX_TAG) !!};
    console.log(sets.data[0]);
    var events_array = [];
    sets.data.forEach(element => {
        var value = {
            title: element.event_title,
            start: (element.start == '0000-00-00 00:00:00') ? '' : element.start.replace(' ', 'T'),
            end: (element.end == '0000-00-00 00:00:00') ? '' : element.end.replace(' ', 'T')
        }
        events_array.push(value);
    });
    console.log(events_array);
    var calendar = new FullCalendar.Calendar(calendarEl, {
      headerToolbar: {
        left: 'prev,next today',
        center: 'title',
        right: 'dayGridMonth,timeGridWeek,timeGridDay'
      },
      initialDate: date,
      navLinks: true, // can click day/week names to navigate views
      selectable: true,
      selectMirror: true,
      editable: true,
      dayMaxEvents: true, // allow "more" link when too many events
      events: events_array
    });

    calendar.render();
  });
</script>
@endsection
