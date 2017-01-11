<template>
	<div class="search" v-bind:class="{ 'margin-bottom': !searching }">
		<form>
			<div class="form-input icon-right has-shadow">
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
					<span class="badge" v-if="false">Recommended</span>
				</div>
			</div>
        </div>
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
				currentSong: null
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
			}
		}
	}
</script>