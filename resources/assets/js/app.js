require('./bootstrap');

window.PlayerEvents = new Vue();

Vue.component('player', require('./components/Player.vue'));
Vue.component('songs-list', require('./components/SongsList.vue'));
Vue.component('search', require('./components/Search.vue'));
Vue.component('modal', require('./components/modal.vue'));

const app = new Vue({
    el: '#app',
    data: {
    	showModal: false
    }
});
