
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
import router from './routes';
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('sidebar', require('./components/Sidebar.vue'));
Vue.component('navbar', require('./components/Navbar.vue'));
Vue.component('example', require('./components/Example.vue'));
Vue.component(
    'passport-clients',
    require('./components/passport/Clients.vue')
);

Vue.component(
    'passport-authorized-clients',
    require('./components/passport/AuthorizedClients.vue')
);

Vue.component(
    'passport-personal-access-tokens',
    require('./components/passport/PersonalAccessTokens.vue')
);
import NProgress from 'nprogress'

axios.interceptors.request.use((config) => {
    NProgress.start();
    return config;
}, (error) => {
	NProgress.done();
    return Promise.reject(error);
});

axios.interceptors.response.use((response) => {
    NProgress.done();
    return response;
}, (error) => {
	NProgress.done();
    return Promise.reject(error);
});

import auth from './auth'

router.beforeEach((to, from, next) => {
	if (to.fullPath === '/login')
		next()
	else {
		if (!auth.user.authenticated)
			axios.get('api/me')
			.then( response => {
				next()
				auth.user.name = response.data.name
				auth.user.username = response.data.username
				auth.user.role = response.data.role
				auth.user.authenticated = true
			}).catch( error => {
				next({ path: '/login'})
			})
		else
			next()
	}
})


const app = new Vue({
    el: '#app',
    data: {
    	user: auth.user
    },
    router
});
