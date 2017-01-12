<template>
	<div class="song-list no-number">
		<span class="not-found" v-if="songs.length === 0">There are no songs in your library</span>
		<div v-for="song in songs" v-if="songs.length > 0">
			<a href="#" @click="play(song)" v-if="currentSong !== song.youtube_id"><i class="fa fa-play"></i></a>
			<span class="icon-music" v-if="currentSong === song.youtube_id">
				<span class="icon-music-bar"></span>
				<span class="icon-music-bar"></span>
				<span class="icon-music-bar"></span>
			</span>
			<span class="song-title">{{ song.artist }} - {{ song.song_name }}</span>
		</div>
	</div>
</template>

<script>
	export default {
		data: function(){
			return {
				songs: [],
				currentSong: null
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
		mounted(){
			var _this = this;

			PlayerEvents.$on('playing', (id) => {
				_this.currentSong = id;
			});
		},
		methods: {
			play: function(song){
				var song = {
					id: {
						videoId: song.youtube_id
					},
					snippet: {
						title: song.artist + '-' + song.song_name
					}
				};

				PlayerEvents.$emit('playSong', song);
			}
		}
	}
</script>