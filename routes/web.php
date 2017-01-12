<?php

Route::group(['prefix' => 'app'], function(){
	Auth::routes();
	Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');
	Route::post('search', 'SearchController@search');

	Route::post('song/check', 'SongController@checkForSong');
	Route::post('song/create', 'SongController@createSong');
	Route::get('songs', 'SongController@getSongs');
});

Route::get('/{vue_capture?}', function () {
  return view('app');
})->where('vue_capture', '[\/\w\.-]*');

// Route::get('search', 'SearchController@search');
// Auth::routes();
// Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');
// Route::get('/home', 'HomeController@index');
