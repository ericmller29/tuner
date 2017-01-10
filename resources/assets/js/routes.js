import VueRouter from 'vue-router';

let routes = [
	{
		path: '/',
		component: require('./views/Home.vue')
	},
	{
		path: '/playlists',
		component: require('./views/Playlists.vue')
	}
];

export default new VueRouter({
	mode: 'history',
	routes
})