
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/*******************************************\
*/

window.Vue.prototype.authorize = function (handler) {
    let user = window.App.user;
    return user ? handler(user) : false;
};

window.events = new window.Vue();
window.flash = function (message, status = 'success') {
    window.events.$emit('flash', message, status);
};

/*
\*******************************************/

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('flash', require('./components/FlashComponent.vue'));
Vue.component('thread', require('./pages/Thread.vue'));
Vue.component('paginator', require('./components/PaginatorComponent.vue'));
Vue.component('user-notifications', require('./components/UserNotificationsComponent.vue'));

const app = new Vue({
    el: '#app'
});
