<template>
	<div class="song-list no-number">
		<span class="not-found" v-if="songs.length === 0">There are no songs in your library</span>
		<div v-for="(song, index) in songs" v-if="songs.length > 0">
			<a href="#" @click.prevent="play(song, index)" v-if="currentSong !== song.youtube_id"><i class="fa fa-play"></i></a>
			<span class="icon-music" v-if="currentSong === song.youtube_id">
				<span class="icon-music-bar"></span>
				<span class="icon-music-bar"></span>
				<span class="icon-music-bar"></span>
			</span>
			<span class="song-title">{{ song.artist }} - {{ song.song_name }}</span>
			<button type="button" class="btn danger just-icon" @click="remove(song, index)">
				<i class="fa fa-trash"></i>
			</button>
		</div>
	</div>
</template>

<script>
	export default {
		props: ['currentSong'],
		data: function(){
			return {
				songs: []
			}
		},
		created(){
			var _this = this;
			axios.get('/app/songs')
				.then(function(payload){
					_this.songs = payload.data;
				})
				.catch((err) => {
					console.log(err);
				})
		},
		methods: {
			play: function(song, index){
				var song = {
					id: {
						videoId: song.youtube_id
					},
					snippet: {
						title: song.artist + '-' + song.song_name
					}
				};
				
				PlayerEvents.$emit('playSong', song);
			},
			remove: function(song, index){
				var _this = this;

				axios.post('/app/song/delete', { songId: song.youtube_id })
					.then(function(payload){
						_this.songs.splice(index, 1);				
					})
			}
		}
	}
</script>