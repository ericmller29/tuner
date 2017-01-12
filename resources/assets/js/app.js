require('./bootstrap');
import router from './routes';

window.PlayerEvents = new Vue();
window.AppEvents = new Vue();

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
    	showRegisterModal: false,
        searching: false,
        currentSong: null
    },
    mounted(){
        var _this = this;

        AppEvents.$on('search', this.search);

        PlayerEvents.$on('playing', (id) => {
            _this.currentSong = id;
        });
    },
    methods: {
    	playPause: function(){
    		PlayerEvents.$emit('playSong');
    	},
        search: function(val){
            if(!val){
                this.searching = false;
                return false;
            }
            this.searching = true;
        }
    }
});
