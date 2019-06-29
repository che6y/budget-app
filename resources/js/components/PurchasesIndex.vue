<template>
    <div class="purchases">
        <div v-if="message" class="alert alert-success" role="alert">{{ message }}</div>

        <div v-if="error" class="alert alert-danger" role="alert">
            {{ error }}
        </div>

        <purchases-form :purchases="purchases" :categories="categories"></purchases-form>

<!--        TODO: IMPROVEMENT: Add ability to choose day/wekk/month/year -->
<!--        <nav>-->
<!--            <div class="nav nav-tabs" id="nav-tab" role="tablist">-->
<!--                <a class="nav-item nav-link active" id="nav-week-tab" data-toggle="tab" href="#nav-week" role="tab"-->
<!--                   aria-controls="nav-week" aria-selected="true">Week</a>-->
<!--                <a class="nav-item nav-link" id="nav-month-tab" data-toggle="tab" href="#nav-month" role="tab"-->
<!--                   aria-controls="nav-month" aria-selected="false">Month</a>-->
<!--                <a class="nav-item nav-link" id="nav-year-tab" data-toggle="tab" href="#nav-year" role="tab"-->
<!--                   aria-controls="nav-year" aria-selected="false">Year</a>-->
<!--            </div>-->
<!--        </nav>-->
<!--        <div class="tab-content" id="nav-tabContent">-->
<!--            <div class="tab-pane fade show active" id="nav-week" role="tabpanel" aria-labelledby="nav-week-tab">-->
                <ul v-if="purchases" class="list-group">
                    <li v-for="(purchase, index) in purchases" :id="['purchase-' + index]"
                        class="list-group-item list-group-item-action">
                        <div class="row justify-content-end">
                            <div class="col-sm-5 col-4">
                                <i v-if="purchase.icon" class="fas" :class="[purchase.icon ? 'fa-' + purchase.icon : '']"></i>
                                {{ purchase.title }}
                            </div>
                            <div class="col-sm-3 col-4">
                                {{ purchase.cost * purchase.amount }} baht
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
                                        <button class="dropdown-item" @click.prevent="onEdit( $event,
                                            index )">Edit</button>
                                        <button class="dropdown-item" @click.prevent="onDelete( $event,
                                            purchase.id )">Delete</button>
                                    </div>
                                </div>
                            </div>
                        </div>
<!--                        <purchase-edit :item="purchase" ></purchase-edit>-->
                        <div class="purchase-edit">
                            <form class="form-row align-items-end" @submit.prevent="onSubmit($event, purchase.id,
                            purchase.title, purchase.cost, purchase.amount, purchase.category_id)">
                                <div class="form-group col-md-6">
                                    <label for="purchase_title">Title</label>
                                    <input class="form-control" id="purchase_title" v-model="purchase.title" required />
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="purchase_cost">Price</label>
                                    <input class="form-control" id="purchase_cost" type="number" v-model="purchase.cost" required />
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="purchase_amount">Amount</label>
                                    <input class="form-control mx-sm-6" id="purchase_amount" type="number" v-model="purchase.amount" min="1" step="1" required />
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="category-id">Category</label>
                                    <select v-if="categories" id="category-id" v-model="purchase.category_id"
                                            class="form-control" @change="changeIcon($event)">
                                        <option v-for="category in categories" :selected="category.id == purchase.category_id"
                                                :value="category.id" >{{category.title }}</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <button class="btn btn-outline-primary" type="submit" :disabled="saving">Update</button>
                                    <button class="btn btn-outline-danger" :disabled="saving"
                                            @click.prevent="onDelete($event, purchase.id)">Delete</button>
                                    <button class="btn btn-outline-secondary" type="button" :disabled="saving"
                                            @click.prevent="onClose($event, index)">Close</button>
                                </div>
                            </form>
                        </div>
                    </li>
                </ul>
<!--        TODO: END -->
<!--            </div>-->
<!--            <div class="tab-pane fade" id="nav-month" role="tabpanel" aria-labelledby="nav-month-tab">...</div>-->
<!--            <div class="tab-pane fade" id="nav-year" role="tabpanel" aria-labelledby="nav-year-tab">...</div>-->
<!--        </div>-->

    </div>
</template>
<script>
    import axios from 'axios';
    import purchases_api from '../api/purchases';

    export default {
        data() {
            return {
                message: null,
                error: null,
                loading: false,
                saving: false,
                purchases: null,
                categories: null,
            };
        },
        created() {
            this.fetchPurchaseData();
            this.fetchCategoryData();
        },
        methods: {
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
            fetchPurchaseData() {
                this.error = this.purchases = null;
                this.loading = true;
                axios
                    .get('/api/purchases')
                    .then(response => {
                        this.purchases = response.data.data;
                        this.loading = false;
                    }).catch(error => {
                        this.loading = false;
                        this.error = error.response.data.message || error.message;
                    });

            },
            onSubmit( event, id, title, cost, amount, cat_id ) {
                this.saving = true;

                purchases_api.update(id, {
                    title: title,
                    cost: cost,
                    amount: amount,
                    category_id: cat_id,
                }).then((response) => {
                    this.message = 'Purchase updated';
                    setTimeout(() => this.message = null, 2000);
                }).catch(error => {
                    console.log(error);
                }).then(_ => this.saving = false);
            },

            onEdit( event, index ) {
                const parent = $(event.target).closest('#purchase-'+index);
                parent.find('.purchase-edit').show();
                parent.siblings().find('.purchase-edit').hide();
            },
            onDelete( event, id ) {
                if ( confirm("Do you really want to remove this item?") ) {
                    this.saving = true;
                    $(event.target).closest('.list-group-item').hide();
                    purchases_api.delete( id )
                        .then(( response ) => {
                            this.message = 'Item Deleted';
                            setTimeout(() => this.message = null, 1500);
                            this.saving = false;
                        });
                } else {
                    return true;
                }
            },
            onClose( event, index ) {
                const parent = $(event.target).closest('#purchase-'+index);
                parent.find('.purchase-edit').hide();
            },
            changeIcon( event ){
                const value = $(event.target).val();
            }
        }
    }
</script>

<style scoped>
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
    .purchase-edit {
        display: none;
    }
</style>