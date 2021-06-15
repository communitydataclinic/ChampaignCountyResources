<?php

namespace App\Http\Controllers;

use App\Model\Alt_taxonomy;
use App\Model\Layout;
use App\Model\Map;
use App\Model\Taxonomy;
use Illuminate\Http\Request;
use App\Model\Location;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }
    public function home($value = '')
    {
        $home = Layout::find(1);
        $layout = Layout::find(1);
        $map = Map::find(1);
        $locations = Location::get();
        // $taxonomies = \App\Taxonomy::whereNotNull('taxonomy_grandparent_name')->orderBy('taxonomy_name', 'asc')->get();
        // $grandparent_taxonomies = Taxonomy::whereNotNull('taxonomy_grandparent_name')->groupBy('taxonomy_grandparent_name')->pluck('taxonomy_grandparent_name')->toArray();
        // $parent_taxonomies = \App\Taxonomy::whereNotNull('taxonomy_grandparent_name')->groupBy('taxonomy_parent_name')->pluck('taxonomy_parent_name')->toArray();
        $grandparent_taxonomies = Alt_taxonomy::all();

        $taxonomy_tree = [];
        if (count($grandparent_taxonomies) > 0) {
            foreach ($grandparent_taxonomies as $key => $grandparent) {
                $taxonomy_data['alt_taxonomy_name'] = $grandparent->alt_taxonomy_name;
                $terms = $grandparent->terms()->get();
                $taxonomy_parent_name_list = [];
                foreach ($terms as $term_key => $term) {
                    array_push($taxonomy_parent_name_list, $term->taxonomy_parent_name);
                }

                $taxonomy_parent_name_list = array_unique($taxonomy_parent_name_list);

                $parent_taxonomy = [];
                $grandparent_service_count = 0;
                foreach ($taxonomy_parent_name_list as $term_key => $taxonomy_parent_name) {
                    $parent_count = Taxonomy::where('taxonomy_parent_name', '=', $taxonomy_parent_name)->count();
                    $term_count = $grandparent->terms()->where('taxonomy_parent_name', '=', $taxonomy_parent_name)->count();
                    if ($parent_count == $term_count) {
                        $child_data['parent_taxonomy'] = $taxonomy_parent_name;
                        $child_taxonomies = Taxonomy::where('taxonomy_parent_name', '=', $taxonomy_parent_name)->get(['taxonomy_name', 'taxonomy_id']);
                        $child_data['child_taxonomies'] = $child_taxonomies;
                        array_push($parent_taxonomy, $child_data);
                    } else {
                        foreach ($grandparent->terms()->where('taxonomy_parent_name', '=', $taxonomy_parent_name)->get() as $child_key => $child_term) {
                            $child_data['parent_taxonomy'] = $child_term;
                            $child_data['child_taxonomies'] = "";
                            array_push($parent_taxonomy, $child_data);
                        }
                    }
                }
                $taxonomy_data['parent_taxonomies'] = $parent_taxonomy;
                array_push($taxonomy_tree, $taxonomy_data);
            }
        } else {
            $parent_taxonomies = Taxonomy::whereNull('taxonomy_parent_name')->whereNotNull('taxonomy_services')->get();
            // $parent_taxonomy_data = [];
            // foreach($parent_taxonomies as $parent_taxonomy) {
            //     $child_data['parent_taxonomy'] = $parent_taxonomy->taxonomy_name;
            //     $child_data['child_taxonomies'] = $parent_taxonomy->childs;
            //     array_push($parent_taxonomy_data, $child_data);
            // }
            $taxonomy_tree['parent_taxonomies'] = $parent_taxonomies;
        }

        return view('frontEnd.home', compact('home', 'map', 'grandparent_taxonomies', 'layout', 'locations'))->with('taxonomy_tree', $taxonomy_tree);
    }
    public function dashboard($value = '')
    {
        $layout = Layout::first();
        return view('backEnd.dashboard', compact('layout'));
    }
    public function checkTwillio(Request $request)
    {
        try {
            $sid = $request->get('twillioSid');
            $token = $request->get('twillioKey');
            $twilio = new Client($sid, $token);

            $account = $twilio->api->v2010->accounts("ACd991aaec2fba11620c174e9148e04d7a")
                ->fetch();
            return response()->json([
                'message' => 'Your twillio key is verified!',
                'success' => true,
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;

            return response()->json([
                'message' => $th->getMessage(),
                'success' => false,
            ], 500);
        }
    }

    public function checkSendgrid(Request $request)
    {
        try {
            $key = $request->get('sendgridApiKey');
            $email = new \SendGrid\Mail\Mail();
            $email->setFrom(env('MAIL_FROM_ADDRESS'), 'test');
            $email->setSubject('test');
            $email->addTo('example@example.com', 'test');
            $email->addContent("text/plain", 'test');

            $sendgrid = new \SendGrid($key);
            $response = $sendgrid->send($email);

            if ($response->statusCode() == 202) {
                return response()->json([
                    'message' => 'Your sendgrid key is verified!',
                    'success' => true,
                ], 200);
            } else {
                return response()->json([
                    'message' => 'Your sendgrid key is not valid!',
                    'success' => false,
                ], 500);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(),
                'success' => false,
            ], 500);
        }
    }
}
