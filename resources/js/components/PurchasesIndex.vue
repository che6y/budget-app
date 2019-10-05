<template>
    <div class="purchases">

        <div v-if="message" class="alert alert-success" role="alert">{{ message }}</div>
        <div v-if="error" class="alert alert-danger" role="alert">{{ error }}</div>

        <progress-bar :summary="summary"></progress-bar>

        <purchases-form
                :categories="categories"
                :change-summary="changeSummary"
                :new-purchase-to-arr="newPurchaseToArr"
                :add-category="addCategory"
        >
        </purchases-form>

        <div v-if="!purchases" class="spinner">
            <div class="bounce1"></div>
            <div class="bounce2"></div>
            <div class="bounce3"></div>
        </div>

        <ul v-if="purchases" class="list-group">
            <li class="list-group-item d-flex justify-content-around">
                <div>Today's total <span class="badge badge-primary">{{ todaysTotal }}</span></div>
                <div>Last month
                    <span class="badge" :class="[(lastMonthTotal.saved >= 0) ? 'badge-success' : 'badge-danger']">
                        {{ lastMonthTotal.saved }}
                    </span>
                </div>
                <div>Saved
                    <span class="badge" :class="[(saved >= 0) ? 'badge-success' : 'badge-danger']">
                        {{ saved }}
                    </span>
                </div>
            </li>
            <li v-for="(purchase, index) in purchases" :id="['purchase-' + index]"
                class="list-group-item list-group-item-action">
                <div class="row justify-content-end">
                    <div class="col-sm-5 col-4">
                        <i v-if="purchase.icon" class="fa" :class="[purchase.icon ? 'fa-' + purchase.icon : '']"></i>
                        {{ purchase.title }}
                    </div>
                    <div class="col-sm-3 col-4">
                        {{ purchase.cost * purchase.amount }} baht
                    </div>
                    <div class="col-sm-3 col-4">
                        {{ formatDate(purchase.created_at) }}
                    </div>
                    <div class="col-sm-1 col-4 text-right">
                        <button class="btn btn-outline-info btn-sm" @click.prevent="onBtnClick( $event, index, purchase
                         )">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                    </div>
                </div>
                <div class="purchase-edit">
                    <form class="form-row align-items-end" @submit.prevent="onPurchaseEdit($event, purchase)">
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
                                    class="form-control" @change="changeIcon($event, purchase, categories)">
                                <option v-for="category in categories" :selected="category.id === purchase.category_id"
                                        :value="category.id" >{{ category.title }}</option>
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="purchase_date">Date</label>
                            <input class="form-control mx-sm-6" id="purchase_date" type="text"
                                   v-model="purchase.created_at" autocomplete="off" />
                        </div>
                        <div class="form-group col-md-3">
                            <button class="btn btn-outline-primary" type="submit" :disabled="saving">Update</button>
                            <button class="btn btn-outline-danger" :disabled="saving"
                                    @click.prevent="onPurchaseDelete($event, purchase)">Delete</button>
                        </div>
                    </form>
                </div>

            </li>
        </ul>
    </div>
</template>
<script>
    import axios from 'axios';
    import purchases_api from '../api/purchases';
    import * as moment from 'moment';

    export default {
        props: [ 'total', 'monthlySpendings', 'categories', 'addCategory' ],
        data() {
            return {
                message    : null,
                error      : null,
                saving     : false,
                purchases  : null,
                summary    : this.total,
                todaysTotal: 0,
            };
        },
        computed: {
            saved: function() {
                var total = 0;
                this.monthlySpendings.forEach( function( item ) {
                    total += item.saved;
                });
                return total;
            },
            lastMonthTotal: function() {
                var length = this.monthlySpendings.length;
                return this.monthlySpendings[length - 1];
            }

        },
        created() {
            this.fetchPurchaseData();
        },
        watch: {
            purchases: function (val) {
                var today = moment();
                this.todaysTotal = val
                    .filter( item => moment(item.created_at).isSame(today, 'day'))
                    .reduce((acc, curr) => {
                        return acc + (curr.cost * curr.amount);
                    }, 0);
            }
        },
        methods: {
            fetchPurchaseData() {
                this.error = this.purchases = null;
                this.saving = true;
                axios
                    .get('/api/purchases')
                    .then(response => {
                        this.purchases = response.data.data;
                        this.saving = false;
                    }).catch(error => {
                        this.saving = false;
                        this.error = error.response.data.message || error.message;
                    });
            },

            newPurchaseToArr( newPurchase ) {
                this.purchases.unshift(newPurchase);
            },

            onPurchaseEdit( event, purchase ) {
                this.saving = true;

                purchases_api.update(purchase.id, {
                    title: purchase.title,
                    cost: purchase.cost,
                    amount: purchase.amount,
                    category_id: purchase.category_id,
                    created_at: purchase.created_at
                }).then((response) => {
                    this.message = 'Purchase updated';
                    setTimeout(() => this.message = null, 3000);
                    $('.purchase-edit').hide();
                    this.saving = false;
                }).catch(error => {
                    console.log(error);
                    this.saving = false;
                });
            },
            onPurchaseDelete( event, purchase ) {
                this.saving   = true;
                const sum     = purchase.amount * purchase.cost;
                const id      = purchase.id;

                $(event.target).closest('.list-group-item').hide();
                purchases_api
                    .delete( purchase.id )
                    .then(( response ) => {
                        this.changeSummary( sum, '-');
                        const index = this.purchases.findIndex(function ( purchase ) {
                            return purchase.id === id;
                        });
                        this.purchases.slice(index, 1);

                        this.message = 'Item Deleted';
                        setTimeout(() => this.message = null, 3000);
                        $('.purchase-edit').hide();
                    })
                    .catch( error => {
                        console.log(error);
                        this.error = 'Something went wrong, please try again later';
                        setTimeout(() => this.error = null, 3000);
                    }).then(_ => this.saving = false);
            },
            onBtnClick( event, index, purchase ) {
                const parent = $(event.target).closest('#purchase-'+index);

                if ( parent.find('.purchase-edit').css('display') === 'none' ){
                    $('#purchase_date').datepicker({
                        dateFormat: "yy-mm-dd",
                        changeMonth: true,
                        minDate: '-1y',
                        maxDate: '+1m',
                        onSelect:function(selectedDate, datePicker) {
                            purchase.created_at = selectedDate;
                        }
                    });

                    parent.find('.purchase-edit').show();
                    parent.siblings().find('.purchase-edit').hide();
                } else {
                    parent.find('.purchase-edit').hide();
                }
            },
            changeIcon( event, purchase, categories ){
                let value = $(event.target).val();
                for ( var i in categories ){
                    if ( parseInt( value ) === categories[i].id ){
                        return purchase.icon = categories[i].icon;
                    }
                }
            },
            changeSummary( number, sign ) {
                if ( sign === '+' )
                    this.summary += number;
                else
                    this.summary -= number;
            },
            formatDate( date ) {
                return moment( date ).format( 'D MMM' );
            },
        },

    }
</script>

<style scoped>
    .badge {
        font-size: 85%;
    }

    .fa {
        padding-right: 10px;
    }

    .purchase-edit {
        display: none;
    }

    .spinner {
        margin: 100px auto 0;
        width: 70px;
        text-align: center;
    }

    .spinner > div {
        width: 18px;
        height: 18px;
        background-color: #e9ecef;
        border-radius: 100%;
        display: inline-block;
        -webkit-animation: sk-bouncedelay 1.4s infinite ease-in-out both;
        animation: sk-bouncedelay 1.4s infinite ease-in-out both;
    }

    .spinner .bounce1 {
        -webkit-animation-delay: -0.32s;
        animation-delay: -0.32s;
    }

    .spinner .bounce2 {
        -webkit-animation-delay: -0.16s;
        animation-delay: -0.16s;
    }

    @-webkit-keyframes sk-bouncedelay {
        0%, 80%, 100% { -webkit-transform: scale(0) }
        40% { -webkit-transform: scale(1.0) }
    }

    @keyframes sk-bouncedelay {
        0%, 80%, 100% {
            -webkit-transform: scale(0);
            transform: scale(0);
        } 40% {
            -webkit-transform: scale(1.0);
            transform: scale(1.0);
        }
    }
</style>