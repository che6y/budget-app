<template>
    <div class="purchases">
        <div v-if="message" class="alert alert-success" role="alert">{{ message }}</div>

        <div v-if="error" class="alert alert-danger" role="alert">
            {{ error }}
        </div>

        <form class="form-row justify-content-start" @submit.prevent="onSubmit($event)">
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
                <input v-model="purchase.category_id" type="hidden" />
            </div>
            <div class="form-group col-md-6">
                <button class="btn btn-outline-primary" type="submit" :disabled="saving">Create</button>
            </div>
        </form>

        <div class="flex-wrap categories-list" role="group">
            <button v-for="category in categories" v-on:click="onCategoryClick( category.id, category.title, category.icon )" type="button" class="btn btn-outline-info">{{ category.title }}
                <i v-if="category.icon" class="fas" v-bind:class="[category.icon ? 'fa-' + category.icon : '']"></i>
            </button>
        </div>
        <button type="button" class="btn btn-outline-info" data-toggle="modal" data-target="#add-category-form">
            <i class="fas fa-plus"></i>
        </button>


        <ul v-if="purchases" class="list-group">
            <li v-for="purchase in purchases" class="list-group-item">
                <div class="row justify-content-end">
                    <div class="col-sm-3 col-4">
                        {{ purchase.cost * purchase.amount }} baht
                    </div>
                    <div class="col-sm-4 col-4">
                        <i v-if="purchase.icon" class="fas" v-bind:class="[purchase.icon ? 'fa-' + purchase.icon : '']"></i>
                        {{ purchase.title }}
                    </div>
                    <div class="col-sm-3 col-4">
                        {{ purchase.created_at }}
                    </div>
                    <div class="col-sm-2 col-4 ">
                        <router-link :to="{ name: 'purchases.edit', params: { purchase: purchase.id } }">Edit</router-link>
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
                                    <button v-for="icon in icons" v-on:click="onIconClick( icon )" type="button" class="btn btn-outline-info">
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

    export default {
        data() {
            return {
                message: null,
                error: null,
                categoryFormError: null,
                loading: false,
                saving: false,
                // create: false,
                purchases: null,
                icons: ['coffee','home', 'egg', 'sad-cry', 'skiing', 'tshirt', 'wallet', 'tv', 'lightbulb',
                    'heartbeat', 'gift'],
                purchase: {
                    id: null,
                    title: "",
                    cost: "",
                    amount: 1,
                    icon: null,
                    category_id: null,
                },
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
                        this.loading = false;
                        this.purchases = response.data.data;
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
                    category_id: this.purchase.category_id,
                }).then( (response) => {
                    this.message = 'Purchase Added';
                    setTimeout(() => this.message = null, 1500);
                    this.purchases.unshift(response.data.data);
                    this.purchase.title = '';
                    this.purchase.cost = '';
                    this.purchase.amount = 1;
                }).catch( error => {
                    this.error = 'Something went wrong, please try again later'
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
                    this.categoryFormError = 'Something went wrong, please try again later'
                    setTimeout(() => this.categoryFormError = null, 1500);
                });
            },
            onCategoryClick( categoryID, categoryTitle, categoryIcon ) {
                if (this.purchase.title === "") {
                    this.purchase.title = categoryTitle;
                }
                this.purchase.category_id = categoryID;
                this.purchase.icon = categoryIcon;
            },
            onIconClick ( icon ) {
                this.category.icon = icon;
            },
        }
    }
</script>

<style scoped>
    .categories-list {
        margin: 30px 0;
    }
</style>