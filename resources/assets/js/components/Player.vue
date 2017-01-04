<template>
	<div class="player">
		<div class="scrubber" v-bind:style="{ width: playPercent + '%' }"></div>
		<div class="container">
			<div class="row">
				<div class="col-xs-11">
					<a href="#" v-on:click="play($event)" v-if="!playing"><i class="glyphicon glyphicon-play"></i></a>
					<a href="#" v-on:click="pause($event)" v-if="playing"><i class="glyphicon glyphicon-pause"></i></a>
					<span class="song-title">{{ currentSong.title }}</span>
					<div id="audio"></div>
				</div>
				<div class="col-xs-1 text-right">
					<a href="#" v-if="!muted" v-on:click="mute"><i class="glyphicon glyphicon-volume-up"></i></a>
					<a href="#" v-if="muted" v-on:click="unmute"><i class="glyphicon glyphicon-volume-off"></i></a>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
	export default{
		data: function(){
			return {
				playing: false,
				playTimer: null,
				playPercent: 0,
				currentSong: {},
				muted: false
			}
		},
		beforeCreate(){
			var tag = document.createElement('script');

			tag.src = "https://www.youtube.com/iframe_api";
			var firstScriptTag = document.getElementsByTagName('script')[0];
			firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

			window.player = null;
		},
		mounted(){
			var _this = this;

			window.onYouTubeIframeAPIReady = function(){
			    player = new YT.Player('audio', {
			        width: 1,
			        height: 1,
			        videoId: null,
					events: {
						'onStateChange': _this.stateChange
					}
			    });
			}
		},
		created(){
			var _this = this;

			PlayerActions.$on('newSong', (song) => {
				player.loadVideoById(song.id.videoId, 'small');
				_this.currentSong = song.snippet;
				player.playVideo();
			});

			PlayerActions.$on('pauseSong', () => {
				// _this.pause();
				player.pauseVideo();
			});
		},
		methods: {
			play: function(e){
				player.playVideo();
				e.preventDefault();
			},
			pause: function(e){
				player.pauseVideo();
				e.preventDefault();
			},
			mute: function(){
				player.mute();
				this.muted = true;
			},
			unmute: function(){
				player.unMute();
				this.muted = false;
			},
			stateChange: function(e){
				clearInterval(this.playTimer);

				if(e.data === 1){
					this.playTimer = setInterval(this.updateScrubber, 1);
					this.playing = true;
				}else if(e.data === 0){
					this.playing = false;
					this.playPercent = 0;
					PlayerActions.$emit('songStopped');
					this.currentSong = {};
				}else if(e.data === 2){
					this.playing = false;
				}
			},
			updateScrubber: function(){
				var currentTime = Math.round(player.getCurrentTime()),
					totalTime = Math.round(player.getDuration()),
					percent = (player.getCurrentTime() / player.getDuration()) * 100;

				// console.log(percent);
				this.playPercent = percent;
			}
		}
	}
</script>