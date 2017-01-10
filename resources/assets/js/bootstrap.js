window._ = require('lodash');

window.VeeValidate = require('vee-validate');
window.VueRouter = require('vue-router');

window.noUiSlider = require('./nouislider');

window.Vue = require('vue');
require('vue-resource');

Vue.use(VeeValidate);
Vue.use(VueRouter);

Vue.http.interceptors.push((request, next) => {
    request.headers.set('X-CSRF-TOKEN', Laravel.csrfToken);

    next();
});

window.axios = require('axios');
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

