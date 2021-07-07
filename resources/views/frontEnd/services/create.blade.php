@extends('layouts.app')
@section('title')
    Service Create
@stop
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

<style type="text/css">
    button[data-id="service_organization"], button[data-id="service_locations"], button[data-id="service_status"], button[data-id="service_taxonomies"], button[data-id="service_schedules"], button[data-id="service_contacts"], button[data-id="service_details"], button[data-id="service_address"] {
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
                                <p>Create New Service</p>
                            </h4>
                            {{-- <form action="/add_new_service" method="GET"> --}}
                            {!! Form::open(['route' => 'services.store']) !!}
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Service Keywords: </label>
                                        <input class="form-control selectpicker" type="text" id="service_name"name="service_name" value="{{ old('service_name') }}" >
                                        @error('service_name')
                                        <span class="error-message"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Service Program: </label>
                                        <input class="form-control selectpicker" type="text" id="service_alternate_name"
                                               name="service_alternate_name" value="{{ old('service_alternate_name') }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Organization Name: </label>
                                        <select class="form-control selectpicker" data-live-search="true" id="service_organization"
                                                name="service_organization" {{old('service_organization')}} data-size="5" >
                                            @foreach($organization_name_list as $key => $org_name)
                                                <option {{ (old("service_organization") == $org_name ? "selected":"") }} value="{{$org_name}}">{{$org_name}}</option>
                                            @endforeach
                                        </select>
                                        @error('service_organization')
                                        <span class="error-message"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Service Description: </label>
                                        <!-- <input class="form-control selectpicker" type="text" id="service_description"
                                            name="service_description" value="" > -->
                                        <textarea rows="10" cols="5" class="selectpicker" type="text" id="service_description"
                                                  name="service_description">{{{old('service_description')}}}</textarea>
                                        @error('service_description')
                                        <span class="error-message"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Service Locations: </label>
                                        <select class="form-control selectpicker" multiple data-live-search="true" id="service_locations"
                                                name="service_locations[]" data-size="5" >
                                        @foreach($facility_info_list as $key => $location_info)
                                            <!-- <option value="{{$location_info->location_recordid}}">{{$location_info->location_name}}</option> -->
                                                <option {{ (old("service_locations") && in_array($location_info->location_recordid, old("service_locations")) ? "selected":"") }} value="{{$location_info->location_recordid}}">{{$location_info->location_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Service URL: </label>
                                        <input class="form-control selectpicker" type="text" id="service_url" name="service_url"
                                               value="{{ old('service_url') }}">
                                    </div>
                                </div>
                                <!-- <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Service Program: </label>
                                        <input class="form-control selectpicker" type="text" id="service_program"
                                            name="service_program" value="">
                                    </div>
                                </div> -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Service Email: </label>
                                        <input class="form-control selectpicker" type="email" id="service_email"
                                               name="service_email" value="{{ old('service_email') }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Status(Verified): </label>
                                        <select class="form-control selectpicker" data-live-search="true" id="service_status"
                                                name="service_status" data-size="5" >
                                            @foreach($service_status_list as $key => $service_status)
                                                <option value="{{$service_status}}">{{$service_status}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Categories: </label>
                                        <select class="form-control selectpicker" multiple data-live-search="true" id="service_taxonomies"
                                                name="service_taxonomies[]" data-size="5" >
                                            @foreach($taxonomy_info_list as $key => $taxonomy_info)
                                                <option value="{{$taxonomy_info->taxonomy_recordid}}">{{$taxonomy_info->taxonomy_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Application Process: </label>
                                        <input class="form-control selectpicker" type="text" id="service_application_process"
                                               name="service_application_process" value="{{ old('service_application_process') }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Eligibility: </label>
                                        <input class="form-control selectpicker" type="text" id="service_wait_time"
                                               name="service_wait_time" value="{{ old('service_wait_time') }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Service Fees: </label>
                                        <input class="form-control selectpicker" type="text" id="service_fees"
                                               name="service_fees" value="{{ old('service_fees') }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Documents needed: </label>
                                        <input class="form-control selectpicker" type="text" id="service_accrediations"
                                               name="service_accrediations" value="{{ old('service_accrediations') }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Income guidelines: </label>
                                        <input class="form-control selectpicker" type="text" id="service_licenses"
                                               name="service_licenses" value="{{ old('service_licenses') }}">
                                    </div>
                                </div>
                                <!-- <div class="form-group">
                                    <label>Service Phone: </label>
                                        <input class="form-control selectpicker" type="text" id="service_phones"
                                            name="service_phones" value="">
                                        <p id="error_service_phones" style="font-style: italic; color: red;">Invalid phone number! Example: +39 422 789611, 0422-78961, (042)589-6000, +39 (0422)7896, 0422 (789611), 39 422/789 611 </p>
                                    </div>
                                </div> -->

                            <!-- <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Service Schedule: </label>
                                        <select class="form-control selectpicker" multiple data-live-search="true" id="service_schedules"
                                            name="service_schedules[]" data-size="5" >
                                            @foreach($schedule_info_list as $key => $schedule_info)
                                <option value="{{$schedule_info->schedule_recordid}}">{{$schedule_info->schedule_opens_at}} ~ {{$schedule_info->schedule_closes_at}}</option>
                                            @endforeach
                                    </select>
                                </div>
                            </div> -->
                            <!-- <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Contacts: </label>
                                        <select class="form-control selectpicker" multiple data-live-search="true" id="service_contacts"
                                            name="service_contacts[]" data-size="5" >
                                            @foreach($contact_info_list as $key => $contact_info)
                                <option value="{{$contact_info->contact_recordid}}">{{$contact_info->contact_name}}</option>
                                            @endforeach
                                    </select>
                                </div>
                            </div> -->
                            <!-- <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Service Details: </label>
                                        <select class="form-control selectpicker" multiple data-live-search="true" id="service_details"
                                            name="service_details[]" data-size="5" >
                                            @foreach($detail_info_list as $key => $detail_info)
                                <option value="{{$detail_info->detail_recordid}}">{{$detail_info->detail_value}}</option>
                                            @endforeach
                                    </select>
                                </div>
                            </div> -->
                            <!-- <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Service Address: </label>
                                        <select class="form-control selectpicker" multiple data-live-search="true" id="service_address"
                                            name="service_address[]" data-size="5" >
                                            @foreach($address_info_list as $key => $address_info)
                                @if($address_info->address_1)
                                    <option value="{{$address_info->address_recordid}}">{{$address_info->address_1}}, {{$address_info->address_city}}, {{$address_info->address_state_province}}, {{$address_info->address_postal_code}}</option>
                                            @endif
                            @endforeach
                                    </select>
                                </div>
                            </div> -->
                                <!-- <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Meta Data: </label>
                                        <input class="form-control selectpicker" type="text" id="service_metadata"
                                            name="service_metadata" value="">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Airs Taxonomy X: </label>
                                        <input class="form-control selectpicker" type="text" id="service_airs_taxonomy_x"
                                            name="service_airs_taxonomy_x" value="">
                                    </div>
                                </div> -->

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Service Schedule: </label>
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                <th colspan="4" class="text-center">Regular Schedule</th>
                                                </thead>
                                                <thead>
                                                <th>Weekday</th>
                                                <th>Opens</th>
                                                <th>Closes</th>
                                                <th>Closed</th>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <td>
                                                        Monday
                                                        <input type="hidden" name="schedule_days_of_week[]" value="monday">
                                                    </td>
                                                    <td>
                                                        {!! Form::time('schedule_opens_at[]',  null, ['class' => 'form-control']) !!}
                                                    </td>
                                                    <td>
                                                        {!! Form::time('schedule_closes_at[]',  null, ['class' => 'form-control']) !!}
                                                    </td>
                                                    <td>
                                                        <input type="checkbox" name="schedule_closed[]" id="" value="1" >
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        Tuesday
                                                        <input type="hidden" name="schedule_days_of_week[]" value="tuesday">
                                                    </td>
                                                    <td>
                                                        {!! Form::time('schedule_opens_at[]', null, ['class' => 'form-control']) !!}
                                                    </td>
                                                    <td>
                                                        {!! Form::time('schedule_closes_at[]', null, ['class' => 'form-control']) !!}
                                                    </td>
                                                    <td>
                                                        <input type="checkbox" name="schedule_closed[]" id="" value="2">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Wednesday
                                                        <input type="hidden" name="schedule_days_of_week[]" value="wednesday">
                                                    </td>
                                                    <td>
                                                        {!! Form::time('schedule_opens_at[]', null, ['class' => 'form-control']) !!}
                                                    </td>
                                                    <td>
                                                        {!! Form::time('schedule_closes_at[]', null, ['class' => 'form-control']) !!}
                                                    </td>
                                                    <td>
                                                        <input type="checkbox" name="schedule_closed[]" id="" value="3" >
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Thursday
                                                        <input type="hidden" name="schedule_days_of_week[]" value="thursday">
                                                    </td>
                                                    <td>
                                                        {!! Form::time('schedule_opens_at[]',  null, ['class' => 'form-control']) !!}
                                                    </td>
                                                    <td>
                                                        {!! Form::time('schedule_closes_at[]', null, ['class' => 'form-control']) !!}
                                                    </td>
                                                    <td>
                                                        <input type="checkbox" name="schedule_closed[]" id="" value="4" >
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Friday
                                                        <input type="hidden" name="schedule_days_of_week[]" value="friday">
                                                    </td>
                                                    <td>
                                                        {!! Form::time('schedule_opens_at[]', null, ['class' => 'form-control']) !!}
                                                    </td>
                                                    <td>
                                                        {!! Form::time('schedule_closes_at[]', null, ['class' => 'form-control']) !!}
                                                    </td>
                                                    <td>
                                                        <input type="checkbox" name="schedule_closed[]" id="" value="5" >
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Saturday
                                                        <input type="hidden" name="schedule_days_of_week[]" value="saturday">
                                                    </td>
                                                    <td>
                                                        {!! Form::time('schedule_opens_at[]', null, ['class' => 'form-control']) !!}
                                                    </td>
                                                    <td>
                                                        {!! Form::time('schedule_closes_at[]',null, ['class' => 'form-control']) !!}
                                                    </td>
                                                    <td>
                                                        <input type="checkbox" name="schedule_closed[]" id="" value="6" >
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Sunday
                                                        <input type="hidden" name="schedule_days_of_week[]" value="sunday">
                                                    </td>
                                                    <td>
                                                        {!! Form::time('schedule_opens_at[]', null, ['class' => 'form-control']) !!}
                                                    </td>
                                                    <td>
                                                        {!! Form::time('schedule_closes_at[]', null, ['class' => 'form-control']) !!}
                                                    </td>

                                                    <td>
                                                        <input type="checkbox" name="schedule_closed[]" id="" value="7" >
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Holiday Schedule: </label>
                                        <div class="table-responsive">
                                            <table class="table table-bordered" id="myTable">
                                                <thead>
                                                <th>Start</th>
                                                <th>End</th>
                                                <th>Opens</th>
                                                <th>Closes</th>
                                                <th>Closed</th>
                                                <th>Remove</th>
                                                </thead>
                                                <tbody>
                                                <!-- <tr>
                                                    <td>
                                                        <input type="date" name="holiday_start_date[]" id="">
                                                    </td>
                                                    <td>
                                                        <input type="date" name="holiday_end_date[]" id="">
                                                    </td>
                                                    <td>
                                                        <input type="time" name="holiday_open_at[]" id="">
                                                    </td>
                                                    <td>
                                                        <input type="time" name="holiday_close_at[]" id="">
                                                    </td>
                                                    <td>
                                                        <input type="checkbox" name="holiday_closed[]" id="" value="1">
                                                    </td>
                                                    <td></td>
                                                </tr> -->

                                                <tr id="addTr">
                                                    <td colspan="6" class="text-center">
                                                        <a href="javascript:void(0)" id="addData" style="color:blue;"> <i class="fa fa-plus-circle" aria-hidden="true"></i> </a>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Phones: <a id="add-phone-input"><i class="fas fa-plus btn-success btn float-right mb-5"></i></a> </label>
                                        <ol id="phones-ul" class="row p-0 m-0" style="list-style: none; ">
                                            <li class="service-phones-li mb-2 col-md-4">
                                                <input class="form-control selectpicker service_phones"  type="text" name="service_phones[]" value="">
                                            </li>
                                        </ol>
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
            
            setTimeout(function () {

                @if(! empty(old('service_organization')))
                $('select#service_organization').val('{{ old('service_organization') }}').change();
                @endif

                @if(! empty(old('service_taxonomies')))
                console.log({!! json_encode(old('service_taxonomies')) !!})
                $('select#service_taxonomies').val({!! json_encode(old('service_taxonomies')) !!} ).change();
                @endif
            }, 1000)
            x = '{!! json_encode(old('service_taxonomies')) !!}';
            //$('select#service_organization').val([]).change();
            //$('select#service_locations').val([]).change();
            //$('select#service_schedules').val([]).change();
        });
        // $(document).ready(function(){
        //     $('#error_service_phones').hide();
        //     $("#service-create-content").submit(function(event){
        //         // var mob = /^((\+)?[1-9]{1,2})?([-\s\.])?((\(\d{1,4}\))|\d{1,4})(([-\s\.])?[0-9]{1,12})$/;
        //         var mob = /^(?!.*([\(\)\-\/]{2,}|\([^\)]+$|^[^\(]+\)|\([^\)]+\(|\s{2,}).*)\+?([\-\s\(\)\/]*\d){9,15}[\s\(\)]*$/;
        //         var service_phones = $("#service_phones").val();
        //         if (service_phones != ''){
        //             if(mob.test(service_phones) == false && service_phones != 10){
        //                 $('#error_service_phones').show();
        //                 event.preventDefault();
        //             }
        //         }

        //     });
        // });
        $(document).ready(function() {
            $('a .removePhone').on('click',function(){
                alert(654654)
                console.log($(this))
            })
        })
        $("#add-phone-input").click(function(){
            $("ol#phones-ul").append(
                "<li class='service-phones-li mb-2 col-md-4'>"
                + "<input class='form-control selectpicker service_phones'  type='text' name='service_phones[]'>"
                + "<a class='removePhone'><i class='fas fa-minus btn-danger btn float-right mb-5' style='border-radius: 50%;    font-size: 13px;width: 20px;height: 20px; position: absolute;top: 0;right: 15px;padding: 0;'></i></a>"
                + "</li>" );
        });
        $(document).on('click', '.removeData', function(){
            $(this).closest('tr').remove()
        });
        let i = 1;
        $('#addData').click(function(){
            $('#myTable tr:last').before('<tr><td><input type="date" name="holiday_start_date[]" id=""></td><td><input type="date" name="holiday_end_date[]" id=""></td><td><input type="time" name="holiday_open_at[]" id=""></td><td><input type="time" name="holiday_close_at[]" id=""></td><td><input type="checkbox" name="holiday_closed[]" id="" value="'+i+'" ></td><td class="text-center"><a href="javascript:void(0)" class="removeData" style="color:red;"> <i class="fa fa-minus-circle" aria-hidden="true"></i> </a></td></tr>');
            i++;
        });
        $('button.delete-td').on('click', function() {
            var value = $(this).val();
            $('input#service_recordid').val(value);
        });

        $("#add-phone-input").click(function(){
            $("ol#phones-ul").append(
                "<li class='service-phones-li mb-2  col-md-12 p-0'>"
                + "<input class='form-control selectpicker service_phones'  type='text' name='service_phones[]'>"
                + "</li> " );
        });
    </script>
@endsection
