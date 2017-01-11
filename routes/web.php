<?php

Route::group(['prefix' => 'app'], function(){
	Auth::routes();
	Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');
	Route::post('search', 'SearchController@search');
});

Route::get('/{vue_capture?}', function () {
  return view('app');
})->where('vue_capture', '[\/\w\.-]*');

// Route::get('search', 'SearchController@search');
// Auth::routes();
// Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');
// Route::get('/home', 'HomeController@index');
