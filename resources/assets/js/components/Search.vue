<template>
	<div class="search" v-bind:class="{ 'margin-bottom': !searching }">
		<form>
			<div class="form-input icon-right has-shadow">
				<label for="keyword" class="hidden">What do you want to hear?</label>
				<input type="text" id="keyword" name="keyword" placeholder="What do you want to hear?" autocomplete="off"
					v-model="query"
					v-on:keyup="search">
				<i class="fa fa-search" v-if="searchQuery === ''"></i>
				<i class="fa fa-times" v-if="searchQuery !== ''" @click="clearSearch"></i>
			</div>
		</form>
        <div class="search-results" v-if="searching">
            <header class="search-header">
                Search results for "<span>{{ searchQuery }}</span>"
            </header>
			<div class="song-list no-number">
				<span class="not-found" v-if="results.length === 0">There are no songs in your library</span>
				<div v-for="song in results" v-if="results.length > 0">
					{{ song.snippet.title }}
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
				results: []
			}
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
					axios.post('/app/search', { query: _this.query })
						.then(function(payload){
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
				AppEvents.$emit('search', null);
			}
		}
	}
</script>