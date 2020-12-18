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
    max-width: 1100px;
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
<div id="wrap" onload="test_resize()">
    <div id="calendar" style="display:none;"></div>
</div>
<script>
function myFunction(){
    var checkBox = document.getElementById("myCheck");
    var listDisplay = document.getElementById("inner_services");
    var calendar = document.getElementById("calendar"); 
    if(checkBox.checked == true){
        listDisplay.style.display = "none";
        calendar.style.display = "block";
    }else{
        listDisplay.style.display = "block";
        calendar.style.display = "none";
    }
}

document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
      headerToolbar: {
        left: 'prev,next today',
        center: 'title',
        right: 'dayGridMonth,timeGridWeek,timeGridDay'
      },
      initialDate: '2020-09-12',
      navLinks: true, // can click day/week names to navigate views
      selectable: true,
      selectMirror: true,
      select: function(arg) {
        var title = prompt('Event Title:');
        if (title) {
          calendar.addEvent({
            title: title,
            start: arg.start,
            end: arg.end,
            allDay: arg.allDay
          })
        }
        calendar.unselect()
      },
      eventClick: function(arg) {
        if (confirm('Are you sure you want to delete this event?')) {
          arg.event.remove()
        }
      },
      editable: true,
      dayMaxEvents: true, // allow "more" link when too many events
      events: [
        {
          title: 'All Day Event',
          start: '2020-09-01'
        },
        {
          title: 'Long Event',
          start: '2020-09-07',
          end: '2020-09-10'
        },
        {
          groupId: 999,
          title: 'Repeating Event',
          start: '2020-09-09T16:00:00'
        },
        {
          groupId: 999,
          title: 'Repeating Event',
          start: '2020-09-16T16:00:00'
        },
        {
          title: 'Conference',
          start: '2020-09-11',
          end: '2020-09-13'
        },
        {
          title: 'Meeting',
          start: '2020-09-12T10:30:00',
          end: '2020-09-12T12:30:00'
        },
        {
          title: 'Lunch',
          start: '2020-09-12T12:00:00'
        },
        {
          title: 'Meeting',
          start: '2020-09-12T14:30:00'
        },
        {
          title: 'Happy Hour',
          start: '2020-09-12T17:30:00'
        },
        {
          title: 'Dinner',
          start: '2020-09-12T20:00:00'
        },
        {
          title: 'Birthday Party',
          start: '2020-09-13T07:00:00'
        },
        {
          title: 'Click for Google',
          url: 'http://google.com/',
          start: '2020-09-28'
        }
      ]
    });

    calendar.render();
  });

</script>
@endsection


