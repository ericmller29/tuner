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
	export default{
		data: function(){
			return {
				scrubber: null,
				volume: null,
				player: null,
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
				_this.player = new YT.Player('audio', {
					width: 100,
					height: 100,
					videoId: 'bDoqw51G3Do',
					events: {
						'onReady': _this.playerReady,
						'onStateChange': _this.stateChange
					},
					playerVars: {
						'showinfo': 0,
						'controls': 0, 
						'rel' : 0
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
				var _this = this;

				if(_this.playing){
					_this.player.pauseVideo();
				}else{
					_this.player.playVideo();
				}

				_this.playing = !_this.playing;

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

				_this.playPercent = (_this.player.getCurrentTime() / _this.player.getDuration()) * 100;
				_this.scrubber.noUiSlider.set(_this.playPercent);
			},
			changeVolume: function(vol){
				var _this = this;

				if(!_this.player){
					return false;
				}

				var volume = vol[0] / 1;

				_this.player.setVolume(volume);
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
				var _this = this;

				if(!_this.player){
					return false;
				}

				var time = _this.player.getDuration() * (t[0] / 100);
				_this.player.playVideo();
				_this.player.seekTo(time);

				_this.playing = true;
				_this.playPercent = time * 100;
			}
		}
	}
</script>