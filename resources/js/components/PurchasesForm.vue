<template>
    <div>
        <form class="form-row justify-content-start" @submit.prevent="onSubmit($event)">
            <input type="hidden" />
            <div class="form-group col-md-6">
                <label for="purchase_title">Title</label>
                <input class="form-control" id="purchase_title" />
            </div>
            <div class="form-group col-md-3">
                <label for="purchase_cost">Price</label>
                <input class="form-control" id="purchase_cost" type="number" />
            </div>
            <div class="form-group col-md-3">
                <label for="purchase_amount">Amount</label>
                <input class="form-control mx-sm-6" id="purchase_amount" type="number" min="1" step="1" />
            </div>
            <div class="form-group col-md-6">
                <button class="btn btn-outline-primary" type="submit">Create</button>
            </div>
        </form>

        <div class="flex-wrap categories-list" role="group">
            <button v-for="category in categories" v-on:click="onCategoryClick( $event, category.id, category.title,
            category.icon )" type="button" class="btn btn-outline-info btn-sm">{{ category.title }}
                <i v-if="category.icon" class="fas" v-bind:class="[category.icon ? 'fa-' + category.icon : '']"></i>
            </button>
            <button class="btn btn-outline-info dropdown-toggle btn-sm" type="button" id="category-actions"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-plus"></i>
            </button>
            <div class="dropdown-menu" aria-labelledby="category-actions">
                <router-link class="dropdown-item" :to="{ name: 'categories.index' }">Edit</router-link>
                <button class="dropdown-item" data-toggle="modal" data-target="#add-category-form" type="button">
                    Add New
                </button>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';
    import purchases_api from '../api/purchases';
    import categories_api from '../api/categories';

    export default {
        name: "PurchasesForm",
        data() {
            return {
                message: null,
                saving: false,
                icons: ['coffee','home', 'egg', 'sad-cry', 'skiing', 'tshirt', 'wallet', 'tv', 'lightbulb',
                    'heartbeat', 'gift'],
                purchase: {
                    id: null,
                    title: "",
                    cost: "",
                    amount: 1,
                    icon: null,
                    cat_id: null,
                },
                categories: null,
                category: {
                    id: null,
                    title: "",
                    icon: null
                },
            }
        },
        created(){
            this.fetchCategoryData();
        },
        methods: {
            onSubmit( event ) {
                this.saving = true;
                purchases_api.post( {
                    title: this.purchase.title,
                    cost: this.purchase.cost,
                    amount: this.purchase.amount,
                    category_id: this.purchase.cat_id,
                }).then( (response) => {
                    this.message = 'Purchase Added';
                    setTimeout(() => this.message = null, 1500);
                    var purchase_arr = response.data.data;
                    purchase_arr['isToday'] = true;
                    this.purchases.unshift(purchase_arr);
                    this.purchase.title = '';
                    this.purchase.cost = '';
                    this.purchase.amount = 1;
                }).catch( error => {
                    this.error = 'Something went wrong, please try again later';
                    setTimeout(() => this.error = null, 1500);
                }).then(_ => this.saving = false);
            },
            fetchCategoryData() {
                this.error = this.categories = null;
                this.loading = true;
                axios
                    .get( '/api/categories' )
                    .then( response => {
                        this.loading = false;
                        this.categories = response.data.data;
                    }).catch( error => {
                    this.loading = false;
                    this.error = error.response.data.message || error.message;
                });
            },
            onCategorySubmit( event ) {
                categories_api.post( {
                    title: this.category.title,
                    icon: this.category.icon,
                }).then( (response) => {
                    $('#add-category-form').modal('hide')
                    this.message = 'Category Added';
                    setTimeout(() => this.message = null, 1500);
                    this.categories.unshift(response.data.data);
                    this.category.title = '';
                    this.category.icon = null;
                }).catch( error => {
                    this.categoryFormError = 'Something went wrong, please try again later';
                    setTimeout(() => this.categoryFormError = null, 1500);
                });
            },
            onCategoryClick( event, categoryID, categoryTitle, categoryIcon ) {
                this.purchase.title  = categoryTitle;
                this.purchase.cat_id = categoryID;
                this.purchase.icon   = categoryIcon;
                $( event.target )
                    .closest('button')
                    .addClass('active')
                    .siblings()
                    .removeClass('active');
            },
            onIconClick ( event, icon ) {
                this.category.icon = icon;
                $( event.target )
                    .closest('button')
                    .addClass('active')
                    .siblings()
                    .removeClass('active');
            },
            onDelete( event, id ) {
                this.saving = true;
                $(event.target).closest('.list-group-item').hide();
                purchases_api.delete( id )
                    .then(( response ) => {
                        this.message = 'Item Deleted';
                    });
            },
        }
    }
</script>

<style scoped>

</style>