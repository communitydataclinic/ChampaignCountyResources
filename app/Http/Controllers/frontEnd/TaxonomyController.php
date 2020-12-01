<?php

namespace App\Http\Controllers\frontEnd;

use App\Functions\Airtable;
use App\Http\Controllers\Controller;
use App\Imports\ServiceTaxonomyImport;
use App\Imports\TaxonomyImport;
use App\Model\Airtablekeyinfo;
use App\Model\Airtables;
use App\Model\Alt_taxonomy;
use App\Model\CSV_Source;
use App\Model\Servicetaxonomy;
use App\Model\Source_data;
use App\Model\Taxonomy;
use App\Services\Stringtoint;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;

class TaxonomyController extends Controller
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
        //Taxonomy::truncate();

        // $airtable = new Airtable(array(
        //     'api_key'   => env('AIRTABLE_API_KEY'),
        //     'base'      => env('AIRTABLE_BASE_URL'),
        // ));
        $airtable = new Airtable(array(
            'api_key' => $api_key,
            'base' => $base_url,
        ));

        $request = $airtable->getContent('taxonomy');

        do {

            $response = $request->getResponse();

            $airtable_response = json_decode($response, true);

            foreach ($airtable_response['records'] as $record) {

                $taxonomy = new Taxonomy();
                $strtointclass = new Stringtoint();

                $taxonomy->taxonomy_recordid = $strtointclass->string_to_int($record['id']);
                $taxonomy->taxonomy_id = $record['id'];
                // $taxonomy->taxonomy_recordid = $record[ 'id' ];
                $taxonomy->taxonomy_name = isset($record['fields']['name']) ? $record['fields']['name'] : null;
                $taxonomy->taxonomy_parent_name = isset($record['fields']['parent_name']) ? implode(",", $record['fields']['parent_name']) : null;
                if ($taxonomy->taxonomy_parent_name != null) {
                    $taxonomy->taxonomy_parent_name = $strtointclass->string_to_int($taxonomy->taxonomy_parent_name);
                }
                $taxonomy->taxonomy_vocabulary = isset($record['fields']['vocabulary']) ? $record['fields']['vocabulary'] : null;
                $taxonomy->taxonomy_x_description = isset($record['fields']['description-x']) ? $record['fields']['description-x'] : null;
                $taxonomy->taxonomy_x_notes = isset($record['fields']['notes-x']) ? $record['fields']['notes-x'] : null;

                if (isset($record['fields']['services'])) {
                    $i = 0;
                    foreach ($record['fields']['services'] as $value) {

                        $taxonomyservice = $strtointclass->string_to_int($value);

                        if ($i != 0) {
                            $taxonomy->taxonomy_services = $taxonomy->taxonomy_services . ',' . $taxonomyservice;
                        } else {
                            $taxonomy->taxonomy_services = $taxonomyservice;
                        }

                        $i++;
                    }
                }

                $taxonomy->save();
            }
        } while ($request = $response->next());

        $date = date("Y/m/d H:i:s");
        $airtable = Airtables::where('name', '=', 'Taxonomy')->first();
        $airtable->records = Taxonomy::count();
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

            // if ($filename != 'taxonomy.csv') {
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

            Excel::import(new TaxonomyImport, $request->file('csv_file'));

            $date = Carbon::now();
            $csv_source = CSV_Source::where('name', '=', 'Taxonomy')->first();
            $csv_source->records = Taxonomy::count();
            $csv_source->syncdate = $date;
            $csv_source->save();
            $response = array(
                'status' => 'success',
                'result' => 'Taxonomy imported successfully',
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

    public function csv_services_taxonomy(Request $request)
    {
        try {
            // $path = $request->file('csv_file')->getRealPath();

            // $data = Excel::load($path)->get();

            // $filename = $request->file('csv_file')->getClientOriginalName();
            // $request->file('csv_file')->move(public_path('/csv/'), $filename);

            // if ($filename != 'services_taxonomy.csv') {
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

            ServiceTaxonomy::truncate();

            Excel::import(new ServiceTaxonomyImport, $request->file('csv_file'));

            $date = date("Y/m/d H:i:s");
            $csv_source = CSV_Source::where('name', '=', 'Services_taxonomy')->first();
            $csv_source->records = ServiceTaxonomy::count();
            $csv_source->syncdate = $date;
            $csv_source->save();
            $response = array(
                'status' => 'success',
                'result' => 'Service txonomy imported successfully',
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
        $taxonomies = Taxonomy::orderBy('taxonomy_recordid')->get();
        $source_data = Source_data::find(1);
        $alt_taxonomies = Alt_taxonomy::all();

        return view('backEnd.tables.tb_taxonomy', compact('taxonomies', 'source_data', 'alt_taxonomies'));
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
        $taxonomy = Taxonomy::find($id);
        return response()->json($taxonomy);
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
        $taxonomy = Taxonomy::find($id);
        $taxonomy->taxonomy_name = $request->taxonomy_name;
        $taxonomy->taxonomy_vocabulary = $request->taxonomy_vocabulary;
        $taxonomy->taxonomy_x_description = $request->taxonomy_x_description;
        $taxonomy->taxonomy_grandparent_name = $request->taxonomy_grandparent_name;
        $taxonomy->taxonomy_x_notes = $request->taxonomy_x_notes;
        $taxonomy->flag = 'modified';

        if($request->hasFile('category_logo')){
            $category_logo = $request->file('category_logo');
            $name = time().'category_logo_'.$id.$category_logo->getClientOriginalExtension();
            $path = public_path('uploads/images');
            $category_logo->move($path,$name);
            $taxonomy->category_logo = '/uploads/images'.$name;
        }
        if($request->hasFile('category_logo_white')){
            $category_logo_white = $request->file('category_logo_white');
            $name = time().'category_logo_white_'.$id.$category_logo_white->getClientOriginalExtension();
            $path = public_path('uploads/images');
            $category_logo_white->move($path,$name);
            $taxonomy->category_logo_white = '/uploads/images'.$name;
        }



        $taxonomy->save();

        return response()->json($taxonomy);
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
    public function taxonommyUpdate(Request $request)
    {
        try {
            $id = $request->id;
            $taxonomy = Taxonomy::find($id);
            $taxonomy->taxonomy_name = $request->taxonomy_name;
            $taxonomy->taxonomy_vocabulary = $request->taxonomy_vocabulary;
            $taxonomy->taxonomy_x_description = $request->taxonomy_x_description;
            $taxonomy->taxonomy_grandparent_name = $request->taxonomy_grandparent_name;
            $taxonomy->taxonomy_x_notes = $request->taxonomy_x_notes;
            $taxonomy->flag = 'modified';

            if($request->hasFile('category_logo')){
                $category_logo = $request->file('category_logo');
                $name = time().'category_logo_'.$id.'.'.$category_logo->getClientOriginalExtension();
                $path = public_path('uploads/images/');
                $category_logo->move($path,$name);
                $taxonomy->category_logo = '/uploads/images/'.$name;
            }
            if($request->hasFile('category_logo_white')){
                $category_logo_white = $request->file('category_logo_white');
                $name = time().'category_logo_white_'.$id.'.'.$category_logo_white->getClientOriginalExtension();
                $path = public_path('uploads/images/');
                $category_logo_white->move($path,$name);
                $taxonomy->category_logo_white = '/uploads/images/'.$name;
            }



            $taxonomy->save();
            Session::flash('message','Taxonomy update successfully!');
            Session::flash('status','success');
            return redirect()->back();
        } catch (\Throwable $th) {
            Session::flash('message',$th->getMessage());
            Session::flash('status','error');
            return redirect()->back();
        }
    }
}
