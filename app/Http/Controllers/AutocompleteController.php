<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Service;
use Illuminate\Support\Facades\Log;

class AutocompleteController extends Controller
{
    public function autocompleteSearch(Request $request)
    {
          $query = $request->get('query');
          $filterResult = Service::where('service_name', 'LIKE', '%'. $query. '%')->get();
          LOG::info($filterResult);
          $filterList = [];

          for ($i = 0; $i < sizeof($filterResult); $i++) {
              // LOG::info($filterResult[$i]-> service_name);
              $filterItem = $filterResult[$i]->service_name;
              if (!in_array($filterItem, $filterList)) {
                array_push($filterList, $filterItem);
              }
          }

          // Log::info($filterList);
          return response()->json($filterList);
    } 
}
