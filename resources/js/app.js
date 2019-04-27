
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
window.VueRouter = require('vue-router').default;

Vue.use(VueRouter);

window.App = require('./components/App').default;
window.Hello = require('./components/Hello').default;

const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/app/app',
            name: 'app',
            component: App
        },
        {
            path: '/app/hello',
            name: 'hello',
            component: Hello
        }
    ],
});

const app = new Vue({
    el: '#app',
    components: { App },
    router
});
