<template>
    <div class="purchases">
        <div class="progress">
            <div class="progress-bar" role="progressbar" :aria-valuenow="summaryP"
                 aria-valuemin="0" aria-valuemax="100" :style="{ width: summaryP + '%' }">{{ summary }}</div>
        </div>
        <div v-if="message" class="alert alert-success" role="alert">{{ message }}</div>

        <div v-if="error" class="alert alert-danger" role="alert">
            {{ error }}
        </div>

        <purchases-form :purchases="purchases" :categories="categories"></purchases-form>

<!--        TODO: IMPROVEMENT: Add ability to choose day/week/month/year -->
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
                                <button class="btn btn-outline-info btn-sm" @click.prevent="onBtnClick( $event, index )">
                                    <i class="fas fa-ellipsis-h"></i>
                                </button>
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
        props: [ 'total' ],
        data() {
            return {
                message: null,
                error: null,
                loading: false,
                saving: false,
                purchases: null,
                categories: null,
                summary: this.total,
                summaryP: null,
            };
        },
        created() {
            this.fetchPurchaseData();
            this.fetchCategoryData();
            this.summaryP = this.summary/118000 * 100;
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
                    setTimeout(() => this.message = null, 3000);
                    $('.purchase-edit').hide();
                }).catch(error => {
                    console.log(error);
                }).then(_ => this.saving = false);
            },
            onDelete( event, id ) {
                this.saving = true;
                $(event.target).closest('.list-group-item').hide();
                purchases_api.delete( id )
                    .then(( response ) => {
                        this.message = 'Item Deleted';
                        this.saving = false;
                        setTimeout(() => this.message = null, 3000);
                        $('.purchase-edit').hide();
                    });
            },
            onBtnClick( event, index ) {
                const parent = $(event.target).closest('#purchase-'+index);

                if ( parent.find('.purchase-edit').css('display') === 'none' ){
                    parent.find('.purchase-edit').show();
                    parent.siblings().find('.purchase-edit').hide();
                } else {
                    parent.find('.purchase-edit').hide();
                }
            },
            //TODO: Change icone on category change in the Purchase Edit
            // changeIcon( event ){
            //     const value = $(event.target).val();
            // }
        }
    }
</script>

<style scoped>
    .progress {
        height: 30px;
        margin: 0 0 20px 0;
    }
    .progress .progress-bar {
        font-size: 14px;
        text-shadow: 0px 0px 1px #ffffff;
    }
    .purchase-edit {
        display: none;
    }
</style>