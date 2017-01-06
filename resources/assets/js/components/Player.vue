<template>
	<div class="player">
		<div class="album-art">
			<div class="artist-info">
				<span class="artist">The Bouncing Souls</span>
				<span class="song">Lean on me Sheena</span>
			</div>
			<div class="default">
				<i class="fa fa-headphones"></i>
			</div>
			<div id="audio"></div>
		</div>
		<nav class="player-controls">
			<div class="scrubber" id="scrubber"></div>
			<a href="#">
				<i class="fa fa-backward"></i>
			</a>
			<a href="#" class="play-btn" v-on:click="playPauseClick($event)">
				<i class="fa fa-play" v-if="!playing"></i>
				<i class="fa fa-pause" v-if="playing"></i>
			</a>
			<a href="#">
				<i class="fa fa-forward"></i>
			</a>
			<div class="volume">
				<a href="#">
					<i class="fa fa-volume-up"></i>
				</a>
				<div class="volume-slider" id="volume"></div>
			</div>
		</nav>
	</div>
</template>

<script>
	window.player = null;

	export default{
		data: function(){
			return {
				scrubber: null,
				volume: null,
				playing: false,
				playTimer: null,
				playPercent: 0
			}
		},
		beforeCreate(){
			/*
			* Set up the stupid youtube library.
			* I don't know why it has to be done this way,
			* but either way here we are.
			*/
			var tag = document.createElement('script');

			tag.src = "https://www.youtube.com/iframe_api";
			var firstScriptTag = document.getElementsByTagName('script')[0];
			firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
		},
		mounted(){
			var _this = this;

			_this.scrubber = document.getElementById('scrubber'),
			_this.volume = document.getElementById('volume');

			/*
			* Playback Scrubber
			*/
			noUiSlider.create(scrubber, {
				start: [0],
				connect: [true, false],
				padding: 0,
				range: {
					'min': [0],
					'max': [100]
				}
			});
			_this.scrubber.noUiSlider.on('start', _this.slide);
			_this.scrubber.noUiSlider.on('end', _this.addSlideBack);
			_this.scrubber.noUiSlider.on('slide', _this.changePlace);

			/*
			* Volume Slider
			*/
			// volume.setAttribute('disabled', true);
			noUiSlider.create(volume, {
				start: [100],
				connect: [true, false],
				range: {
					'min': [0],
					'max': [100]
				}
			});
			_this.volume.noUiSlider.on('update', _this.changeVolume);

			window.onYouTubeIframeAPIReady = function(){
				player = new YT.Player('audio', {
					width: 1,
					height: 1,
					videoId: 'zQwqC8t-5ag',
					events: {
						'onReady': _this.playerReady,
						'onStateChange': _this.stateChange
					}
				});
			}

			PlayerEvents.$on('playSong', _this.playPause)
		},
		methods: {
			playerReady: function(){
				var _this = this;

				// player.playVideo();
				_this.scrubber.setAttribute('disabled', true);
			},
			playPauseClick: function(e){
				this.playPause();
				e.preventDefault();
			},
			playPause: function(){
				if(this.playing){
					player.pauseVideo();
				}else{
					player.playVideo();
				}

				this.playing = !this.playing;

				// e.preventDefault();
			},
			stateChange: function(e){
				var _this = this;

				clearInterval(_this.playTimer);

				if(e.data === -1){ //player not started
					_this.scrubber.setAttribute('disabled', true);
				}else if(e.data === 0){//song ended
					_this.scrubber.setAttribute('disabled', true);
					
					_this.playPercent = 0;
					_this.playing = false;
					_this.scrubber.noUiSlider.set(0);
				}else if(e.data === 1){//song playing
					_this.scrubber.removeAttribute('disabled');

					_this.playTimer = setInterval(function(){
						_this.updateScrubber();
					}, 100);
				}else if(e.data === 2){//song paused
					_this.scrubber.removeAttribute('disabled');
				}
			},
			updateScrubber: function(){
				var _this = this;

				_this.playPercent = (player.getCurrentTime() / player.getDuration()) * 100;
				_this.scrubber.noUiSlider.set(_this.playPercent);
			},
			changeVolume: function(vol){
				if(!player){
					return false;
				}

				var volume = vol[0] / 1;

				player.setVolume(volume);
			},
			slide: function(){
				this.playPause();

				this.scrubber.noUiSlider.off('slide');
			},
			addSlideBack: function(t){
				this.scrubber.noUiSlider.on('slide', this.changePlace);

				this.changePlace(t);
			},
			changePlace: function(t){
				if(!player){
					return false;
				}

				var time = player.getDuration() * (t[0] / 100);
				player.playVideo();
				player.seekTo(time);

				this.playing = true;
				this.playPercent = time * 100;
			}
		}
	}
</script>