
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
window.VueRouter = require('vue-router').default;

Vue.use(VueRouter);

const App             = require('./components/App').default;
const Purchases       = require('./components/PurchasesIndex').default;
const PurchasesEdit   = require('./components/PurchasesEdit').default;
const PurchasesForm   = require('./components/PurchasesForm').default;
const CategoriesIndex = require('./components/CategoriesIndex').default;
const CategoriesList  = require('./components/CategoriesList').default;

Vue.component( 'purchases-form', PurchasesForm );
Vue.component( 'purchase-edit', PurchasesEdit );
Vue.component( 'categories-list', CategoriesList );

const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/',
            name: 'purchases.index',
            component: Purchases
        },
        {
            path: '/categories',
            name: 'categories.index',
            component: CategoriesIndex
        },
    ]
});

const app = new Vue({
    el: '#app',
    components: { App },
    router
});
