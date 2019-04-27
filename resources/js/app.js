
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
window.Purchases = require('./components/PurchasesIndex').default;
window.PurchasesEdit = require('./components/PurchasesEdit').default;

const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/purchases',
            name: 'purchases.index',
            component: Purchases
        },
        {
            path: '/purchases/:id/edit',
            name: 'purchases.edit',
            component: PurchasesEdit,
        },
    ],
});

const app = new Vue({
    el: '#app',
    components: { App },
    router
});
