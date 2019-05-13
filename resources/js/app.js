
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
window.VueRouter = require('vue-router').default;

Vue.use(VueRouter);

const App = require('./components/App').default;
const Purchases = require('./components/PurchasesIndex').default;
const PurchasesEdit = require('./components/PurchasesEdit').default;
const CategoriesIndex = require('./components/CategoriesIndex').default;

window.PurchasesForm = require('./components/PurchasesForm').default;
Vue.component( 'purchases-form', PurchasesForm );

const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/purchases',
            name: 'purchases.index',
            component: Purchases
        },
        {
            path: '/purchases/:purchase/edit',
            name: 'purchases.edit',
            component: PurchasesEdit,
        },
        {
            path: '/categories',
            name: 'categories.index',
            component: CategoriesIndex
        },
    ],
});

const app = new Vue({
    el: '#app',
    components: { App },
    router
});
