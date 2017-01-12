<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Validator;

use App\Song;
use Auth;

class SongController extends Controller
{
	public function checkForSong(Request $request){
		$name = trim($request->get('song_name'));
		$artist = trim($request->get('artist'));

		$song = Song::where('song_name', '=', $name)->where('artist', '=', $artist)->first();

		// return response()->json($song);
		if(!isset($song)){
			return response()->json(['song_found' => false], 200);
		}

		return response()->json(['song_found' => true, 'song' => $song], 200);
	}	

    public function createSong(Request $request){
    	$validate = Validator::make($request->all(), [
    		'youtube_id' => 'required',
    		'artist' => 'required',
    		'song_name' => 'required'
    	]);

    	if($validate->fails()){
    		return response()->json($validate->errors()->all(), 422);
    	}

    	// $song = Song::firstOrCreate($request->all());
		$name = trim($request->get('song_name'));
		$artist = trim($request->get('artist'));

		$song = Song::where('song_name', '=', $name)->where('artist', '=', $artist)->first();

		if(!isset($song)){
			$song = Song::create([
				'song_name' => $name,
				'artist' => $artist,
				'youtube_id' => $request->get('youtube_id'),
				'approved' => 1,
				'runtime' => ''
			]);
		}

    	$user = Auth::user();

    	$user->songs()->attach($song->id);
    }

    public function getSongs(){
    	$songs = Auth::user()->songs()->orderBy('songs.artist', 'asc')->get();

    	return response()->json($songs);
    }

    public function removeSong(Request $request){
    	$song = Song::where('youtube_id', '=', $request->get('songId'))->first();
    	$user = Auth::user();

    	$song->user()->detach($user->id);

    	return response()->json([$song]);
    }
}
