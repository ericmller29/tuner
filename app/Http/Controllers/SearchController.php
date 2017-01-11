<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Youtube;

class SearchController extends Controller
{
    public function search(Request $request){
    	$results = Youtube::searchVideos($request->get('query'), 50, null, ['id', 'snippet']);

    	if($request->ajax()){
    		return response()->json($results);
    	}
    }
}
