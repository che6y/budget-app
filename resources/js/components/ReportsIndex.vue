<template>
    <div class="reports">

        <div v-if="message" class="alert alert-success" role="alert">{{ message }}</div>
        <div v-if="error" class="alert alert-danger" role="alert">{{ error }}</div>

        <form class="form-row align-items-end" @submit.prevent="onSubmit()">
            <input type="hidden" name="user_id"/>

            <div class="form-group col-md-3">
                <label for="purchase_title">Title</label>
                <input class="form-control" id="purchase_title" v-model="purchase.title" />
            </div>
            <div class="form-group col-md-3">
                <label for="category-id">Category</label>
                <select v-if="categories" id="category-id" v-model="purchase.category_id" class="form-control">
                    <option v-for="category in categories" :value="category.id" >{{category.title }}</option>
                </select>
            </div>
            <div class="form-group col-md-2">
                <label for="purchase_date_from">Date From</label>
                <input class="form-control mx-sm-6" id="purchase_date_from" type="text"
                       v-model="purchase.date_from" />
            </div>
            <div class="form-group col-md-2">
                <label for="purchase_date_to">Date To</label>
                <input class="form-control mx-sm-6" id="purchase_date_to" type="text"
                       v-model="purchase.date_to" />
            </div>
            <div class="form-group col-md-2">
                <button class="btn btn-outline-primary" type="submit">Find</button>
            </div>
        </form>

        <ul v-if="purchases" class="list-group">
            <li v-for="(purchase, index) in purchases" :id="['purchase-' + index]"
                class="list-group-item list-group-item-action">
                <div class="row justify-content-end">
                    <div class="col-4">
                        <i v-if="purchase.icon" class="fa" :class="[purchase.icon ? 'fa-' + purchase.icon : '']"></i>
                        {{ purchase.title }}
                    </div>
                    <div class="col-4">
                        {{ purchase.cost * purchase.amount }} baht
                    </div>
                    <div class="col-4">
                        {{ formatDate(purchase.created_at) }}
                    </div>
                </div>
            </li>
        </ul>

    </div>
</template>

<script>
    import purchases_api from '../api/purchases';
    import * as moment from 'moment';

    export default {
        name: "ReportsIndex",
        props: [ 'categories' ],
        data() {
            return {
                error   : null,
                message : null,
                purchase: {
                    title       : null,
                    date_from   : null,
                    date_to     : null,
                    category_id : 0,
                },
                purchases: null,
            }
        },
        mounted() {
            const _this = this;
            $('#purchase_date_from').datepicker({
                dateFormat  : "yy-mm-dd",
                changeMonth : true,
                minDate     : '-2y',
                maxDate     : '+1m',
                onSelect    :function(selectedDate, datePicker) {
                    _this.purchase.date_from = selectedDate;
                }
            });
            $('#purchase_date_to').datepicker({
                dateFormat  : "yy-mm-dd",
                changeMonth : true,
                minDate     : '-2y',
                maxDate     : '+1m',
                onSelect    : function(selectedDate, datePicker) {
                    _this.purchase.date_to = selectedDate;
                }
            });
        },
        methods: {
            onSubmit() {
                purchases_api.find({
                    title       : this.purchase.title,
                    category_id : this.purchase.category_id,
                    date_from   : this.purchase.date_from,
                    date_to     : this.purchase.date_to
                }).then( (response) => {
                    this.purchases = response.data.data;
                }).catch( error => {
                    this.error = 'Something went wrong, please try again later';
                    setTimeout(() => this.error = null, 3000);
                });
            },
            formatDate( date ) {
                return moment( date ).format( 'D MMM' );
            },
        },
    }
</script>

<style scoped>

</style>