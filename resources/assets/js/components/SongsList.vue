<template>
	<div class="song-list">
		<div>
			The bouncing souls - leen on me sheena
		</div>
		<div>
			The bouncing souls - leen on me sheena
		</div>
	</div>
</template>

<script>
	export default {
		props: ['loggedIn'],
		mounted(){
			console.log((!this.loggedIn) ? 'false' : 'true');
		}
	}
</script>