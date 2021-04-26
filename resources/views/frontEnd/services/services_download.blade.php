
<div class="wrapper">
    <!-- Page Content Holder -->
    <div id="content" class="container">
        <!-- Example Striped Rows -->

        <div class="container-fluid p-0" style="margin-right: 0">
            <h3>{{$layout->header_pdf}}</h3>
            <div class="col-md-8 pt-15 pr-0">

                @foreach($services as $service)

                <div class="panel content-panel">
                    <div class="panel-body p-20">

                        <h3><span class="badge bg-red">Service:</span> <a class="panel-link" href="{{ config('app.url')}}/services/{{$service->service_recordid}}"> {{$service->service_name}}</a></h3>
                        <h4 class="panel-text"><span class="badge bg-red">Organization:</span>
                            @if($service->service_organization!=0)
                                @if(isset($service->organizations))
                                <a class="panel-link" href="{{ config('app.url')}}/organizations/{{$service->organizations()->first()->organization_recordid}}"> {{$service->organizations()->first()->organization_name}}</a>
                                @endif
                            @endif                        
                        </h4>
                        <h4><span class="badge bg-red">Phone:</span> @foreach($service->phone as $phone) {!! $phone->phone_number !!} @endforeach</h4>
                        <h4><span class="badge bg-blue">Address:</span>
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
                        </h4>
                        <h4><span class="badge bg-blue">Description:</span> {!! $service->service_description !!}</h4>
                    </div>
                </div>
                <hr>
                @endforeach
            </div>
            <h3>{{$layout->footer_pdf}}</h3>
        </div>
    </div>
</div>


