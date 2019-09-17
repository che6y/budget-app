<template>
    <div class="reports">

        <canvas id="pie-chart" width="300" height="300">Your browser does not support the canvas element.</canvas>

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
                    <option value="0" >All</option>
                    <option v-for="category in categories" :value="category.id" >{{category.title }}</option>
                </select>
            </div>
            <div class="form-group col-md-2">
                <label for="purchase_date_from">Date From</label>
                <input class="form-control mx-sm-6" id="purchase_date_from" type="text"
                       v-model="purchase.date_from" autocomplete="off" />
            </div>
            <div class="form-group col-md-2">
                <label for="purchase_date_to">Date To</label>
                <input class="form-control mx-sm-6" id="purchase_date_to" type="text"
                       v-model="purchase.date_to" autocomplete="off"/>
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
    import reports_api from '../api/reports';
    import * as moment from 'moment';
    import Chart from 'chart.js';
    import colors from '../colors.js';

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
                    date_to     : moment( new Date() ).add(1, 'd').format( 'YYYY-MM-DD' ),
                    category_id : 0,
                },
                colors: colors,
                purchases: null,
                totalCost: null,
                pieData  : null,
                PieChart : null
            }
        },
        mounted() {
            const _this = this;
            console.log(this.colors);
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
        created() {
            this.fetchReportsData();
        },
        methods: {
            fetchReportsData() {
                axios
                    .get( '/api/reports' )
                    .then( response => {
                        this.pieData            = response.data.pieData;
                        this.purchase.date_from = response.data.dateFrom;
                        this.totalCost          = response.data.totalCost;
                        this.createPieChart();
                    }).catch( error => {
                    this.error = error.response.data.message || error.message;
                });
            },
            createPieChart(){
                var ctx     = document.getElementById('pie-chart');
                var labels  = Object.keys(this.pieData);
                var data    = Object.values(this.pieData);
                this.PieChart = new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        datasets: [{
                            data: data,
                            backgroundColor: colors
                        }],

                        labels: labels
                    },
                    options: {
                        legend: {
                            position: 'bottom'
                        },
                        title: {
                            display : true,
                            position: 'bottom',
                            text    : 'Total: ' + this.totalCost,
                            fontSize: 16,
                        }
                    },

                });
            },
            onSubmit() {
                reports_api.find({
                    title       : this.purchase.title,
                    category_id : this.purchase.category_id,
                    date_from   : this.purchase.date_from,
                    date_to     : this.purchase.date_to
                }).then( (response) => {
                    if ( response.data.purchases.data === 0 )
                        this.message = 'There is no purchases from this period of time :(';
                    setTimeout(() => this.message = null, 3000);
                    this.purchases = response.data.purchases.data;
                    this.totalCost = response.data.totalCost;
                    this.pieData   = response.data.pieData;
                    this.PieChart.data.datasets[0].data = Object.values(this.pieData);
                    this.PieChart.data.labels           = Object.keys(this.pieData);
                    this.PieChart.options.title.text    = 'Total: ' + this.totalCost;
                    this.PieChart.update();
                }).catch( error => {
                    console.log(error);
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
    canvas {
        margin: 0 0 20px 0;
    }
</style>