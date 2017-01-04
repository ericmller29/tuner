<template>
    <div class="live-search">
        <div class="search">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="form-input">
                            <form>
                                <label for="js-searchbox" class="hidden">What do you want to listen to?</label>
                                <input 
                                    type="text" 
                                    name="search" 
                                    id="js-searchbox" 
                                    placeholder="What do you want to listen to?"
                                    v-model="query"
                                    v-on:keyup="search($event.target.value)"
                                    autocomplete="off">

                                <i class="glyphicon glyphicon-search search-btn" v-if="!isSearching"></i>
                                <a href="#" v-on:click="clearSearch($event)" v-if="isSearching"><i class="glyphicon glyphicon-remove"></i></a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="search-results" v-bind:class="{ active: isSearching }">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <ul>
                            <li v-for="result in results">
                                <i class="glyphicon glyphicon-music"></i>
                                {{ result.snippet.title }}
                                <nav class="song-actions">
                                    <a href="#" v-on:click="playSong($event, result)" v-if="currentlyPlaying !== result.id.videoId"><i class="glyphicon glyphicon-play"></i></a>
                                    <a href="#" v-on:click="pauseSong" v-if="currentlyPlaying === result.id.videoId"><i class="glyphicon glyphicon-pause"></i></a>
                                </nav>
                            </li>
                        </ul> 
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data: function(){
            return {
                isSearching: false,
                typeTimer: null,
                currentlyPlaying: null,
                results: []
            }
        },
        created(){
            PlayerActions.$on('songStopped', () => {
                this.currentlyPlaying = null;
            });
        },
        methods: {
            search: function(value){
                var _this = this;

                window.clearTimeout(_this.typeTimer);

                if(value === ''){
                    _this.isSearching = false;
                    return false;
                }

                _this.typeTimer = setTimeout(function(){
                    axios.get('/search?q=' + value)
                        .then(function(payload){
                            _this.results = payload.data;
                            _this.isSearching = true;
                        });
                }, 500);
            },
            clearSearch: function(e){
                e.preventDefault();
                this.query = '';
                this.isSearching = false;
            },
            playSong: function(e, data){
                this.currentlyPlaying = data.id.videoId;
                PlayerActions.$emit('newSong', data);
                e.preventDefault();
            },
            pauseSong: function(){
                PlayerActions.$emit('pauseSong');
            }
        }
    }
</script>
