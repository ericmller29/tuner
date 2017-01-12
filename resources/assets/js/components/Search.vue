<template>
	<div class="search" v-bind:class="{ 'margin-bottom': !searching }">
		<form>
			<div class="form-input icon-right has-shadow search-box">
				<label for="keyword" class="hidden">What do you want to hear?</label>
				<input type="text" id="keyword" name="keyword" placeholder="What do you want to hear?" autocomplete="off"
					v-model="query"
					v-on:keyup="search">
				<i class="fa fa-search" v-if="searchQuery === '' && !loading"></i>
				<span class="loader inline" v-if="loading"><img src="/img/loader.gif" width="16" height="16"></span>
				<i class="fa fa-times" v-if="searchQuery !== '' && !loading" @click="clearSearch"></i>
			</div>
		</form>
        <div class="search-results" v-if="searching">
            <header class="search-header">
                Search results for "<span>{{ searchQuery }}</span>"
            </header>
			<div class="song-list no-number">
				<span class="not-found" v-if="results.length === 0">No songs found.</span>
				<div v-for="song in results" v-if="results.length > 0">
					<a href="#" @click="play($event, song)" v-if="currentSong !== song.id.videoId"><i class="fa fa-play"></i></a>
					<span class="icon-music" v-if="currentSong === song.id.videoId">
						<span class="icon-music-bar"></span>
						<span class="icon-music-bar"></span>
						<span class="icon-music-bar"></span>
					</span>
					<span class="song-title">{{ song.snippet.title }}</span>
					<button type="button" class="btn" @click="checkSong(song)" v-if="!song.user_saved">
						<i class="fa fa-plus"></i>
						Add
					</button>
					<button type="button" class="btn danger" v-if="song.user_saved">
						<i class="fa fa-trash"></i>
						Remove
					</button>
					<span class="badge" v-if="song.recommended">Recommended</span>
				</div>
			</div>
        </div>
        <modal v-if="showInfoModal" @close="showInfoModal = false">
            <h3 slot="header">Song Information</h3>
            <div slot="content">
                <div class="form">
			        <div class="message success slam" v-if="success">
			            <i class="fa fa-check-circle"></i>
			            <p><strong>Thank you!</strong></p>
			        </div>
	                <p>This song was not found in our database! Could you help us out and see if we got the information correct?</p>
			        <form v-on:submit.prevent="addSong()">
			            <div class="form-input" :class="{'has-error': errors.has('artist') }">
			                <label for="artist">Artist:</label>
			                <input type="text" name="artist" id="artist" 
			                    v-model="songInfo.artist"
			                    v-validate
			                    data-vv-rules="required">
			            </div>
			            <div class="form-input" :class="{'has-error': errors.has('song_name') }">
			                <label for="song_name">Song Name:</label>
			                <input type="text" name="song_name" id="song_name" 
			                    v-model="songInfo.song_name"
			                    v-validate
			                    data-vv-rules="required">
			            </div>
			            <div class="form-input">
			                <button type="submit" class="btn full-width">
			                    Add this song to my library
			                </button>
			            </div>
			        </form>
                </div>
            </div>
        </modal>
	</div>
</template>
<script>
	export default{
		props: ['searching'],
		data: function(){
			return {
				query: '',
				searchQuery: '',
				typeTimer: null,
				results: [],
				loading: false,
				currentSong: null,
				showInfoModal: false,
				success: false,
				songInfo: {
					youtube_id: '',
					song_name: '',
					artist: '',
					runtime: '',
					approved: 1
				}
			}
		},
		mounted(){
			var _this = this;
			PlayerEvents.$on('playing', (id) => {
				_this.currentSong = id;
			});
		},
		methods: {
			search: function(e){
				var _this = this;

				window.clearTimeout(_this.typeTimer);

				if(_this.query.replace(" ", "") === ''){
					AppEvents.$emit('search', null);
					return false;
				}

				_this.typeTimer = setTimeout(function(){
					_this.loading = true;

					axios.post('/app/search', { query: _this.query })
						.then(function(payload){
							_this.loading = false;
							_this.searchQuery = _this.query;
							AppEvents.$emit('search', _this.query);
							_this.results = payload.data;
						})
				}, 500);
			},
			clearSearch: function(){
				var _this = this;

				_this.query = '';
				_this.searchQuery = '';
				_this.loading = false;
				AppEvents.$emit('search', null);
			},
			play: function(e, song){
				PlayerEvents.$emit('playSong', song);
				e.preventDefault();
			},
			checkSong: function(song){
				var _this = this,
					songInfo = {
						artist: song.snippet.title.split('-')[0],
						song_name: song.snippet.title.split('-')[1]
					};

				axios.post('/app/song/check', songInfo)
					.then(function(payload){
						var data = payload.data;
						
						if(!data.song_found){
							_this.showInfoModal = true;

							_this.songInfo.artist = songInfo.artist;
							_this.songInfo.song_name = songInfo.song_name;
							_this.songInfo.youtube_id = song.id.videoId;

							return false;
						}

						_this.songInfo = data.song;
						_this.addSong(song);
					})
					.catch(function(err){
						console.log(err);
					});
			},
			addSong: function(song){
				var _this = this;

				axios.post('/app/song/create', _this.songInfo)
					.then(function(payload){
						_this.success = true;

						setTimeout(() => {
							_this.showInfoModal = false;
							_this.success = false;
						}, 1000)
					})
					.catch(function(err){
						console.log(err.response.data);
					});
			}
		}
	}
</script>