<template>
    <div class="purchases">
        <div v-if="message" class="alert alert-success" role="alert">{{ message }}</div>

        <div v-if="error" class="alert alert-danger" role="alert">
            {{ error }}
        </div>

        <form class="form-row justify-content-start" @submit.prevent="onSubmit($event)">
            <input v-model="purchase.cat_id" type="hidden" />
            <div class="form-group col-md-6">
                <label for="purchase_title">Title</label>
                <input class="form-control" id="purchase_title" v-model="purchase.title" />
            </div>
            <div class="form-group col-md-3">
                <label for="purchase_cost">Price</label>
                <input class="form-control" id="purchase_cost" type="number" v-model="purchase.cost" />
            </div>
            <div class="form-group col-md-3">
                <label for="purchase_amount">Amount</label>
                <input class="form-control mx-sm-6" id="purchase_amount" v-model="purchase.amount" type="number" min="1" step="1" />
            </div>
            <div class="form-group col-md-6">
                <button class="btn btn-outline-primary" type="submit" :disabled="saving">Create</button>
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
                <router-link class="dropdown-item" :to="{ name: 'purchases.edit', params: { purchase: purchase.id } }">Edit</router-link>
                <button class="dropdown-item" data-toggle="modal" data-target="#add-category-form" type="button">
                    Add New
                </button>
            </div>
        </div>

        <ul v-if="purchases" class="list-group">
            <li class="list-group-item list-group-item-info">Today</li>
            <li v-for="purchase in purchases" class="list-group-item list-group-item-action" v-if="purchase.isToday">
                <div class="row justify-content-end">
                    <div class="col-sm-3 col-4">
                        {{ purchase.cost * purchase.amount }} baht
                    </div>
                    <div class="col-sm-5 col-4">
                        <i v-if="purchase.icon" class="fas" v-bind:class="[purchase.icon ? 'fa-' + purchase.icon : '']"></i>
                        {{ purchase.title }}
                    </div>
                    <div class="col-sm-3 col-4">
                        {{ purchase.created_at }}
                    </div>
                    <div class="col-sm-1 col-4 ">
                        <div class="dropdown">
                            <button class="btn btn-outline-info dropdown-toggle btn-sm" type="button"
                                    id="purchase-actions"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-ellipsis-h"></i>
                            </button>
                            <div class="dropdown-menu" aria-labelledby="purchase-actions">
                                <router-link class="dropdown-item" :to="{ name: 'purchases.edit', params: { purchase: purchase.id } }">Edit</router-link>
                                <button class="dropdown-item" :disabled="saving" @click.prevent="onDelete( $event,
                                purchase.id )">Delete</button>
                            </div>
                        </div>
                    </div>
                </div>
            </li>
            <li class="list-group-item list-group-item-info"></li>
            <li v-for="purchase in purchases" class="list-group-item list-group-item-action" v-if="purchase.isToday
            === false">
                <div class="row justify-content-end">
                    <div class="col-sm-3 col-4">
                        {{ purchase.cost * purchase.amount }} baht
                    </div>
                    <div class="col-sm-5 col-4">
                        <i v-if="purchase.icon" class="fas" v-bind:class="[purchase.icon ? 'fa-' + purchase.icon : '']"></i>
                        {{ purchase.title }}
                    </div>
                    <div class="col-sm-3 col-4">
                        {{ purchase.created_at }}
                    </div>
                    <div class="col-sm-1 col-4 ">
                        <div class="dropdown">
                            <button class="btn btn-outline-info dropdown-toggle btn-sm" type="button"
                                    id="purchase-actions"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-ellipsis-h"></i>
                            </button>
                            <div class="dropdown-menu" aria-labelledby="purchase-actions">
                                <router-link class="dropdown-item" :to="{ name: 'purchases.edit', params: { purchase: purchase.id } }">Edit</router-link>
                                <button class="dropdown-item" :disabled="saving" @click.prevent="onDelete( $event,
                                purchase.id )">Delete</button>
                            </div>
                        </div>
                    </div>
                </div>
            </li>
        </ul>

        <!-- Modal -->
        <div class="modal fade" id="add-category-form" tabindex="-1" role="dialog" aria-labelledby="modal-title" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal-title">Add new category</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div v-if="categoryFormError" class="alert alert-danger" role="alert">
                            {{ categoryFormError }}
                        </div>
                        <form class="form-row justify-content-start" @submit.prevent="onCategorySubmit($event)">
                            <div class="form-group col-md-6">
                                <label for="category-title">Category Name</label>
                                <input class="form-control" id="category-title" v-model="category.title" />
                            </div>
                            <div class="form-group col-md-6">
                                <label for="category-icon">Choose icon</label>
                                <input class="form-control" id="category-icon" type="hidden" v-model="category.icon" />

                                <div class="flex-wrap icons-list" role="group">
                                    <button v-for="icon in icons" v-on:click="onIconClick( $event, icon )" type="button"
                                            class="btn btn-outline-info" >
                                        <i class="fas" v-bind:class="[ 'fa-' + icon]"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <button type="submit" class="btn btn-outline-primary" >Add</button>
                                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>
<script>
    import axios from 'axios';
    import purchases_api from '../api/purchases';
    import categories_api from '../api/categories';

    const moment = require('moment');

    export default {
        data() {
            return {
                message: null,
                error: null,
                categoryFormError: null,
                loading: false,
                saving: false,
                purchases: null,
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
                purchase_id: null,
                categories: null,
                category: {
                    id: null,
                    title: "",
                    icon: null
                },
            };
        },
        created() {
            this.fetchPurchaseData();
            this.fetchCategoryData();
        },
        methods: {
            fetchPurchaseData() {
                this.error = this.purchases = null;
                this.loading = true;
                axios
                    .get('/api/purchases')
                    .then(response => {
                        let now = new Date();
                        this.purchases = response.data.data;
                        this.loading = false;

                        for ( var i in this.purchases ){
                            let date = moment( this.purchases[i]['created_at'] ).format('YYYY-MM-DD');
                            this.purchases[i]['isToday'] = moment( date ).isSame( now, 'day' );
                        }

                    }).catch(error => {
                        this.loading = false;
                        this.error = error.response.data.message || error.message;
                    });
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
    .categories-list {
        margin: 30px 0;
    }
    .dropdown-toggle::after {
        display: none;
    }
    #purchase-actions.dropdown-toggle.btn-sm {
        border: none;
    }
    .btn-outline-info:focus,
    .btn-outline-info.focus {
        box-shadow: none;
    }
    .active {
        color: #212529;
        background-color: #6cb2eb;
        border-color: #6cb2eb;
    }
</style>