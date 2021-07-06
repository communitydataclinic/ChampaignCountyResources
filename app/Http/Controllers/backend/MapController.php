<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Model\Address;
use App\Model\Layout;
use App\Model\Location;
use App\Model\Map;
use Curl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Spatie\Geocoder\Geocoder;

class MapController extends Controller
{
    /**
     * Post Repository
     *
     * @var Post
    //  */
    // protected $about;

    // public function __construct(About $about)
    // {
    //     $this->about = $about;
    // }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $layout = Layout::first();
        
        $map = Map::find(1);
        $geocode_status_text = 'Not Started';
        $enrich_status_text = '';

        $location_count = Location::whereNotNull('location_name')->count();
        $unenriched_location_count = Location::whereNotNull('location_name')->whereNull('enrich_flag')->count();

        $ungeocoded_location_numbers = Location::whereNull('enrich_flag')->count();
        if ($ungeocoded_location_numbers == 0) {
            $geocode_status_text = 'All valid locations in Champaign County have already been geocoded.';
        }
        
        $invalid_location_info_count = Location::where('enrich_flag', '=', 'ADDRESS_ERR')->count();
        if ($invalid_location_info_count > 0) {
            $enrich_status_text = 'There are invalid locations in Champaign County for geocoding.';
        }

        return view('backEnd.pages.map', compact('map', 'ungeocoded_location_numbers', 'invalid_location_info_count', 'location_count', 'unenriched_location_count', 'geocode_status_text', 'enrich_status_text', 'layout'));
    }

    public function scan_ungeocoded_location(Request $request)
    {
        $map = Map::find(1);
        $geocode_status_text = 'Not Started';
        $enrich_status_text = '';

        $location_count = Location::whereNotNull('location_name')->count();
        $unenriched_location_count = Location::whereNotNull('location_name')->whereNull('enrich_flag')->count();

        $ungeocoded_location_numbers = Location::whereNull('enrich_flag')->count();
        if ($ungeocoded_location_numbers == 0) {
            $geocode_status_text = 'All valid locations in Champaign County have already been geocoded.';
        }
        
        $invalid_location_info_count = Location::where('enrich_flag', '=', 'ADDRESS_ERR')->count();
        if ($invalid_location_info_count > 0) {
            $enrich_status_text = 'There are invalid locations in Champaign County for geocoding.';
        }

        return view('backEnd.pages.map', compact('map', 'ungeocoded_location_numbers', 'invalid_location_info_count', 'geocode_status_text', 'location_count', 'unenriched_location_count', 'enrich_status_text'));
    }

    public function scan_enrichable_location(Request $request)
    {
        $map = Map::find(1);
        $geocode_status_text = '';
        $enrich_status_text = 'Not Started';
        $ungeocoded_location_numbers = Location::whereNull('location_latitude')->count();
        $invalid_location_info_count = Location::whereNull('location_name')->count();
        if ($ungeocoded_location_numbers == $invalid_location_info_count) {
            $geocode_status_text = 'All valid locations have already been geocoded.';
        }
        $location_count = Location::whereNotNull('location_name')->count();
        $unenriched_location_count = Location::whereNotNull('location_name')->whereNull('enrich_flag')->count();
        if ($unenriched_location_count == 0) {
            $enrich_status_text = 'All valid locations have already been enriched before.';
        }

        return view('backEnd.pages.map', compact('map', 'ungeocoded_location_numbers', 'invalid_location_info_count', 'geocode_status_text', 'location_count', 'unenriched_location_count', 'enrich_status_text'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.posts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $map = Map::find(1);
        $map->active = $request->active;
        $map->api_key = $request->api_key;
        $map->state = $request->state;
        $map->lat = $request->lat;
        $map->long = $request->long;
        $map->zoom = $request->zoom;
        $map->zoom_profile = $request->profile_zoom;

        $map->save();

        Session::flash('message', 'Map updated!');
        Session::flash('status', 'success');

        return redirect('apis');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $post = $this->post->findOrFail($id);

        return view('admin.posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $post = $this->post->find($id);

        if (is_null($post)) {
            return Redirect::route('posts.index');
        }

        return view('admin.posts.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id, Request $request)
    {
        try {
            $map = Map::find(1);
            // var_dump($request->input('active'));
            // exit();
            if ($request->input('active') == 'checked') {
                $map->active = 1;
                $map->api_key = $request->input('api_key');
                $map->state = $request->input('state');
                $map->lat = $request->input('lat');
                $map->long = $request->input('long');
                $map->zoom = $request->input('zoom');
                $map->zoom_profile = $request->input('profile_zoom');
            } else {
                $map->active = 0;
            }

            $map->save();

            Session::flash('message', 'Map updated!');
            Session::flash('status', 'success');

            return redirect('map');
        } catch (\Throwable $th) {
            Session::flash('message', $th->getMessage());
            Session::flash('status', 'error');

            return redirect('map');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $this->post->find($id)->delete();

        return Redirect::route('admin.posts.index');
    }

    public function upload(Request $request)
    {
        $file = $request->file('file');
        $input = array('image' => $file);
        $rules = array(
            'image' => 'image',
        );
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            return Response::json(array('success' => false, 'errors' => $validator->getMessageBag()->toArray()));
        }

        $fileName = time() . '-' . $file->getClientOriginalName();
        $destination = public_path() . '/uploads/';
        $file->move($destination, $fileName);

        echo url('/uploads/' . $fileName);
    }

    public function apply_geocode(Request $request)
    {
        $ungeocoded_location_info_list = Location::whereNull('location_latitude')->whereNull('enrich_flag')->get();

        $client = new \GuzzleHttp\Client();
        $geocoder = new Geocoder($client);
        $geocode_api_key = env('GEOCODE_GOOGLE_APIKEY');
        $geocoder->setApiKey($geocode_api_key);

        if ($ungeocoded_location_info_list) {
            
            foreach ($ungeocoded_location_info_list as $key => $location_info) {
                
                if ($location_info->address) {  
                    $address = $location_info->address->first();
                    
                    if (isset($address->address_1)) {                    
                        $full_address_info = $address->address_1;
                        if ($address->address_city)
                            $full_address_info .= ', ' . $address->address_city;
                        
                        if ($address->address_state_province)
                            $full_address_info .= ', ' . $address->address_state_province;

                        if ($address->address_postal_code)
                            $full_address_info .= ' ' . $address->address_postal_code;

                        $response = $geocoder->getCoordinatesForAddress($full_address_info);

                        $location_info->location_latitude = null;
                        $location_info->location_longitude = null;
                        if ($response['formatted_address'] != 'result_not_found') {
                            // Check if the coordinates are in the polygon of Champaign County, IL
                            // lat >= 39.88 && lat <= 40.39
                            // lng <= -87.93 && lng >= -88.45
                            if (($response['lat'] >= 39.88 && $response['lat'] <= 40.39) 
                                && ($response['lng'] >= -88.45 && $response['lng'] <= -87.93)) {        
                                    
                                $location_info->location_latitude = $response['lat'];
                                $location_info->location_longitude = $response['lng'];        
                                $location_info->enrich_flag = 'GEOCODED';                                               
                            }
                            else {
                                // Set a flag to avoid checking this address again
                                $location_info->enrich_flag = 'OUT_CHMPGN';                            
                            }                    
                        }
                        else {
                            // Set a flag to avoid checking this address again BUT indicate should be corrected
                            $location_info->enrich_flag = 'ADDRESS_ERR';                            
                        }    
                        $location_info->save();                
                    }
                }
            }
        }

        /* 
        Does not seem helpful because we always guarantee that latitute and longitude have a value, empty or null

        */
        /* 
        $badgeocoded_location_info_list = Location::where('location_latitude', '=', '')->get();
        if ($badgeocoded_location_info_list) {
            foreach ($badgeocoded_location_info_list as $key => $location_info) {
                if ($location_info->address) {
                    if (isset(($location_info->address)->address)) {
                        $address_info = ($location_info->address)->address;
                        // $response = $geocoder->getCoordinatesForAddress('30-61 87th Street, Queens, NY, 11369');
                        $response = $geocoder->getCoordinatesForAddress($address_info);
                        $latitude = $response['lat'];
                        $longitude = $response['lng'];
                        $location_info->location_latitude = $latitude;
                        $location_info->location_longitude = $longitude;
                        $location_info->save();
                    }
                }
            }
        } 
        */

        return redirect('map');
    }

    public function apply_enrich(Request $request)
    {
        $valid_location_list = Location::whereNotNull('address_id')->get();
        $unenriched_location_list = Location::whereNotNull('location_name')->whereNull('enrich_flag')->get();
        set_time_limit(0);
        foreach ($unenriched_location_list as $key => $unenriched_location) {

            $address = Address::whereId($unenriched_location->address_id)->first();

            $borough = $address->city;
            // $house_address_info = explode(' ', $address->address);
            $house_number = '';
            // if (isset($house_address_info)) {
            $house_number = $address->house_number;
            // }
            $street = '';
            // if (isset($house_address_info[1])) {
            $street = $address->street_name;
            // }
            $app_id = 'b985eb41';
            $app_key = '9e7522143dca2c6347306d73882b6e3f';

            // $request_url = 'https://api.cityofnewyork.us/geoclient/v1/address.json?houseNumber='.$house_number.'&street='.$street.' st&borough='.$borough.'&app_id='.$app_id.'&app_key='.$app_key;

            $response = Curl::to('https://api.cityofnewyork.us/geoclient/v1/address.json')
                ->withData(['houseNumber' => $house_number, 'street' => $street, 'borough' => $borough, 'app_id' => $app_id, 'app_key' => $app_key])
                ->get();
            $data = json_decode($response);
            if ($data) {
                if (isset($data->address)) {
                    $address_info = $data->address;
                    if (isset($address_info->cityCouncilDistrict)) {
                        $address->address_city_council_district = $address_info->cityCouncilDistrict;
                        $unenriched_location->location_city_council_district = $address_info->cityCouncilDistrict;
                    }
                    if (isset($address_info->communityDistrict)) {
                        $address->address_community_district = $address_info->communityDistrict;
                        $unenriched_location->location_community_district = $address_info->communityDistrict;
                    }
                }
            }
            $unenriched_location->enrich_flag = 'modified';
            $address->save();
            $unenriched_location->save();
        }
        return redirect('map');
    }
}
