require('./bootstrap');
import router from './routes';

window.PlayerEvents = new Vue();

Vue.component('player', require('./components/Player.vue'));
Vue.component('songs-list', require('./components/SongsList.vue'));
Vue.component('search', require('./components/Search.vue'));
Vue.component('modal', require('./components/modal.vue'));
Vue.component('register', require('./components/Register.vue'));
Vue.component('login', require('./components/Login.vue'));

const app = new Vue({
    el: '#app',
    router: router,
    data: {
    	showLoginModal: false,
    	showRegisterModal: false
    }
});
