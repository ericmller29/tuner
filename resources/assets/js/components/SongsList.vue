<template>
	<div class="content">
		<search></search>
		<nav class="main-nav">
			<!-- <a href="#">Popular</a> -->
			<a href="#" class="active">My Library</a>
			<a href="#">Playlists</a>
		</nav>
		<div class="song-list">
			<div>
				The bouncing souls - leen on me sheena
			</div>
			<div>
				The bouncing souls - leen on me sheena
			</div>
		</div>
	</div>
</template>

<script>
	export default {
		props: ['loggedIn'],
		mounted(){
			console.log(this.loggedIn);
		}
	}
</script>