<template>
    <div class="purchases">
        <canvas width="600" height="500"></canvas>

        <div class="progress">
            <div class="progress-bar" role="progressbar" :aria-valuenow="barWidth"
                 aria-valuemin="0" aria-valuemax="100" :style="{ width: barWidth + '%',
                 backgroundColor:'hsl('+(110-barWidth)+',100%,45%' }">
                {{ summary }}
            </div>
        </div>
        <div v-if="message" class="alert alert-success" role="alert">{{ message }}</div>

        <div v-if="error" class="alert alert-danger" role="alert">{{ error }}</div>

        <purchases-form :purchases="purchases" :categories="categories" :summary="summary"
                        :change-summary="changeSummary"></purchases-form>

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
                                <button class="btn btn-outline-info btn-sm" @click.prevent="onBtnClick( $event, index )">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                            </div>
                        </div>
                        <purchase-edit :purchase="purchase" :categories="categories" :on-submit="onSubmit"
                                       :on-delete="onDelete" :changeIcon="changeIcon"></purchase-edit>

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
    import * as d3 from 'd3';

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
                pieData: null,
            };
        },
        computed: {
            barWidth: function () {
                return this.summary/118000 * 100;
            },
        },
        created() {
            this.fetchPurchaseData();
            this.fetchCategoryData();
            this.fetchPieData();
        },
        mounted() {

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
            fetchPieData() {
                axios
                    .get( '/api/pie-data' )
                    .then( response => {
                        this.pieData = response.data;
                        this.createPie();
                    }).catch( error => {
                    this.error = error.response.data.message || error.message;
                });
            },
            onSubmit( event, purchase ) {
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
                }).catch(error => {
                    console.log(error);
                }).then(_ => this.saving = false);
            },
            onDelete( event, purchase ) {
                this.saving = true;
                let number = purchase.amount * purchase.cost;
                $(event.target).closest('.list-group-item').hide();
                purchases_api.delete( purchase.id )
                    .then(( response ) => {
                        this.changeSummary( number, '-');

                        this.message = 'Item Deleted';
                        setTimeout(() => this.message = null, 3000);
                        $('.purchase-edit').hide();
                    });
            },
            onBtnClick( event, index ) {
                const parent = $(event.target).closest('#purchase-'+index);

                if ( parent.find('.purchase-edit').css('display') === 'none' ){
                    // $('#purchase_date').datepicker({
                    //     dateFormat: "yy-mm-dd",
                    //     changeMonth: true,
                    //     minDate: '-1y',
                    //     maxDate: '+1m'
                    // });

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
                var d        = new Date(date),
                    month    = '' + d.getMonth(),
                    day      = '' + d.getDate(),
                    monthArr = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];

                return day + ' ' + monthArr[month];
            },
            createPie( ) {
                var svg = d3.select("body")
                    .append("svg")
                    .append("g")

                svg.append("g")
                    .attr("class", "slices");
                svg.append("g")
                    .attr("class", "labels");
                svg.append("g")
                    .attr("class", "lines");

                var width = 960,
                    height = 450,
                    radius = Math.min(width, height) / 2;

                var pie = d3.layout.pie()
                    .sort(null)
                    .value(function(d) {
                        return d.value;
                    });

                var arc = d3.svg.arc()
                    .outerRadius(radius * 0.8)
                    .innerRadius(radius * 0.4);

                var outerArc = d3.svg.arc()
                    .innerRadius(radius * 0.9)
                    .outerRadius(radius * 0.9);

                svg.attr("transform", "translate(" + width / 2 + "," + height / 2 + ")");

                var key = function(d){ return d.title; };

                var color = d3.scale.ordinal()
                    .domain(["Lorem ipsum", "dolor sit", "amet", "consectetur", "adipisicing", "elit", "sed", "do", "eiusmod", "tempor", "incididunt"])
                    .range(["#98abc5", "#8a89a6", "#7b6888", "#6b486b", "#a05d56", "#d0743c", "#ff8c00"]);

                function randomData (){
                    var labels = color.domain();
                    return labels.map(function(label){
                        return { label: label, value: Math.random() }
                    });
                }

                change(randomData());

                d3.select(".randomize")
                    .on("click", function(){
                        change(randomData());
                    });


                function change(data) {

                    /* ------- PIE SLICES -------*/
                    var slice = svg.select(".slices").selectAll("path.slice")
                        .data(pie(data), key);

                    slice.enter()
                        .insert("path")
                        .style("fill", function(d) { return color(d.data.label); })
                        .attr("class", "slice");

                    slice
                        .transition().duration(1000)
                        .attrTween("d", function(d) {
                            this._current = this._current || d;
                            var interpolate = d3.interpolate(this._current, d);
                            this._current = interpolate(0);
                            return function(t) {
                                return arc(interpolate(t));
                            };
                        })

                    slice.exit()
                        .remove();

                    /* ------- TEXT LABELS -------*/

                    var text = svg.select(".labels").selectAll("text")
                        .data(pie(this.pieData), key);

                    text.enter()
                        .append("text")
                        .attr("dy", ".35em")
                        .text(function(d) {
                            return d.data.label;
                        });

                    function midAngle(d){
                        return d.startAngle + (d.endAngle - d.startAngle)/2;
                    }

                    text.transition().duration(1000)
                        .attrTween("transform", function(d) {
                            this._current = this._current || d;
                            var interpolate = d3.interpolate(this._current, d);
                            this._current = interpolate(0);
                            return function(t) {
                                var d2 = interpolate(t);
                                var pos = outerArc.centroid(d2);
                                pos[0] = radius * (midAngle(d2) < Math.PI ? 1 : -1);
                                return "translate("+ pos +")";
                            };
                        })
                        .styleTween("text-anchor", function(d){
                            this._current = this._current || d;
                            var interpolate = d3.interpolate(this._current, d);
                            this._current = interpolate(0);
                            return function(t) {
                                var d2 = interpolate(t);
                                return midAngle(d2) < Math.PI ? "start":"end";
                            };
                        });

                    text.exit()
                        .remove();

                    /* ------- SLICE TO TEXT POLYLINES -------*/

                    var polyline = svg.select(".lines").selectAll("polyline")
                        .data(pie(data), key);

                    polyline.enter()
                        .append("polyline");

                    polyline.transition().duration(1000)
                        .attrTween("points", function(d){
                            this._current = this._current || d;
                            var interpolate = d3.interpolate(this._current, d);
                            this._current = interpolate(0);
                            return function(t) {
                                var d2 = interpolate(t);
                                var pos = outerArc.centroid(d2);
                                pos[0] = radius * 0.95 * (midAngle(d2) < Math.PI ? 1 : -1);
                                return [arc.centroid(d2), outerArc.centroid(d2), pos];
                            };
                        });

                    polyline.exit()
                        .remove();
                };
            }
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
    .fa {
        padding-right: 10px;
    }
</style>