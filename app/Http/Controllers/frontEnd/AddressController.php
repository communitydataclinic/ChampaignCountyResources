<?php

namespace App\Http\Controllers\frontEnd;

use App\Functions\Airtable;
use App\Http\Controllers\Controller;
use App\Imports\AddressImport;
use App\Model\Address;
use App\Model\Airtablekeyinfo;
use App\Model\Airtables;
use App\Model\CSV_Source;
use App\Model\Locationaddress;
use App\Model\Serviceaddress;
use App\Model\Source_data;
use App\Services\Stringtoint;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class AddressController extends Controller
{

    public function airtable($api_key, $base_url)
    {

        $airtable_key_info = Airtablekeyinfo::find(1);
        if (!$airtable_key_info) {
            $airtable_key_info = new Airtablekeyinfo;
        }
        $airtable_key_info->api_key = $api_key;
        $airtable_key_info->base_url = $base_url;
        $airtable_key_info->save();

        //Allow adding new records by not truncating the table when importing
        //Address::truncate();
        
        // $airtable = new Airtable(array(
        //     'api_key'   => env('AIRTABLE_API_KEY'),
        //     'base'      => env('AIRTABLE_BASE_URL'),
        // ));
        $airtable = new Airtable(array(
            'api_key' => $api_key,
            'base' => $base_url,
        ));

        $request = $airtable->getContent('address');

        do {

            $response = $request->getResponse();

            $airtable_response = json_decode($response, true);

            foreach ($airtable_response['records'] as $record) {

                $address = new Address();
                $strtointclass = new Stringtoint();

                $address->address_recordid = $strtointclass->string_to_int($record['id']);

                $address->address_1 = isset($record['fields']['address_1']) ? $record['fields']['address_1'] : null;
                $address->address_2 = isset($record['fields']['address_2']) ? $record['fields']['address_2'] : null;
                $address->address_city = isset($record['fields']['city']) ? $record['fields']['city'] : null;
                $address->address_state_province = isset($record['fields']['State']) ? $record['fields']['State'] : null;
                $address->address_postal_code = isset($record['fields']['Zip Code']) ? $record['fields']['Zip Code'] : null;
                $address->address_region = isset($record['fields']['region']) ? $record['fields']['region'] : null;
                $address->address_country = isset($record['fields']['Country']) ? $record['fields']['Country'] : null;
                $address->address_attention = isset($record['fields']['attention']) ? $record['fields']['attention'] : null;
                $address->address_type = isset($record['fields']['address_type-x']) ? implode(",", $record['fields']['address_type-x']) : null;

                if (isset($record['fields']['locations'])) {
                    $i = 0;
                    foreach ($record['fields']['locations'] as $value) {

                        $addresslocation = $strtointclass->string_to_int($value);

                        if ($i != 0) {
                            $address->address_locations = $address->address_locations . ',' . $addresslocation;
                        } else {
                            $address->address_locations = $addresslocation;
                        }

                        $i++;
                    }
                }

                if (isset($record['fields']['services'])) {
                    $i = 0;
                    foreach ($record['fields']['services'] as $value) {

                        $addressservice = $strtointclass->string_to_int($value);

                        if ($i != 0) {
                            $address->address_services = $address->address_services . ',' . $addressservice;
                        } else {
                            $address->address_services = $addressservice;
                        }

                        $i++;
                    }
                }
                $address->save();
            }
        } while ($request = $response->next());

        $date = date("Y/m/d H:i:s");
        $airtable = Airtables::where('name', '=', 'Address')->first();
        $airtable->records = Address::count();
        $airtable->syncdate = $date;
        $airtable->save();
    }

    public function csv(Request $request)
    {
        try {
            // $path = $request->file('csv_file')->getRealPath();

            // $data = Excel::load($path)->get();

            // $filename = $request->file('csv_file')->getClientOriginalName();
            // $request->file('csv_file')->move(public_path('/csv/'), $filename);

            // if ($filename != 'physical_addresses.csv') {
            //     $response = array(
            //         'status' => 'error',
            //         'result' => 'This CSV is not correct.',
            //     );
            //     return $response;
            // }

            // if (count($data) > 0) {
            //     $csv_header_fields = [];
            //     foreach ($data[0] as $key => $value) {
            //         $csv_header_fields[] = $key;
            //     }
            //     $csv_data = $data;
            // }

            Address::truncate();
            LocationAddress::truncate();
            ServiceAddress::truncate();

            Excel::import(new AddressImport, $request->file('csv_file'));

            $date = Carbon::now();
            $csv_source = CSV_Source::where('name', '=', 'Address')->first();
            $csv_source->records = Address::count();
            $csv_source->syncdate = $date;
            $csv_source->save();
            $response = array(
                'status' => 'success',
                'result' => 'Address imported successfully',
            );
            return $response;
        } catch (\Throwable $th) {
            $response = array(
                'status' => 'false',
                'result' => $th->getMessage(),
            );
            return $response;
        }
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $addresses = Address::orderBy('address_recordid')->paginate(20);
        $source_data = Source_data::find(1);

        return view('backEnd.tables.tb_address', compact('addresses', 'source_data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $address = Address::find($id);
        return response()->json($address);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $address = Address::find($id);
        $address->address_1 = $request->address_1;
        $address->address_2 = $request->address_2;
        $address->address_city = $request->address_city;
        $address->address_state_province = $request->address_state_province;
        $address->address_postal_code = $request->address_postal_code;
        $address->address_region = $request->address_region;
        $address->address_country = $request->address_country;
        $address->address_attention = $request->address_attention;
        $address->address_type = $request->address_type;
        $address->flag = 'modified';
        $address->save();

        return response()->json($address);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
