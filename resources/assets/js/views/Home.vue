<template>
	<songs-list :current-song="currentSong"></songs-list>
</template>
<script>
	export default{
		props: ['currentSong'],
		created(){
			window.document.title = "My Library | Tuner";
		}
	}
</script>