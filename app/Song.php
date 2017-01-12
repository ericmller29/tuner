<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Song extends Model
{
    protected $table = 'songs';

	protected $fillable = [
		'youtube_id',
		'artist',
		'song_name',
		'runtime',
		'approved'
	];

	public function user(){
		return $this->belongsToMany('App\User', 'favorites');
	}
}
