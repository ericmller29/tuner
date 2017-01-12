<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Youtube;
use Auth;
use App\User;
use App\Song;

class SearchController extends Controller
{
    public function search(Request $request){
    	$results = Youtube::searchVideos($request->get('query'), 20, null, ['id', 'snippet']);
    	$parsedResults = [];

    	foreach($results as $i){
    		$song = Song::where('youtube_id', '=', $i->id->videoId)->first();

    		if(isset($song)){
    			$userSaved = Auth::user()->songs->find($song->id);
    			$i->snippet->title = $song->artist . ' - ' . $song->song_name;
    			$i->recommended = true;
    			$i->user_saved = (isset($userSaved)) ? true : false;
    		}

    		$parsedResults[] = $i;
    	}

    	if($request->ajax()){
    		return response()->json($parsedResults);
    	}
    }
}
