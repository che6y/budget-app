<template>
    <div class="progress">
        <div class="progress-bar" role="progressbar" :aria-valuenow="barWidth"
             aria-valuemin="0" aria-valuemax="100" :style="{ width: barWidth + '%',
             backgroundColor:'hsl(' + (110 - barWidth) + ', 100%, 45%' }" @click="showPieChart()">
            {{ summary }}
        </div>
    </div>
</template>
<script>
    import options_api from '../api/options';

    export default {
        props: [ 'summary' ],
        data() {
            return {
                monthStart: 1,
                monthIncome: 117000
            }
        },
        computed: {
            barWidth: function() {
                return this.summary/this.monthIncome * 100;
            },
        },
        created() {
            this.getOptions();
        },
        methods: {
            showPieChart() {
                $('.pie-chart').toggle();
            },
            getOptions( ) {
                options_api.find({
                    keys : 'month_income,month_start'
                }).then( (response) => {
                    this.saving = true;
                    setTimeout(() => this.message = null, 3000);
                }).catch( error => {
                    this.error = 'Something went wrong, please try again later';
                    setTimeout(() => this.error = null, 3000);
                }).then(_ => this.saving = false);
            }
        }
    }
</script>

<style scoped>
    .progress {
        position: relative;
        height: 30px;
        margin: 0 0 20px 0;
    }
    .progress .progress-bar {
        font-size: 14px;
        text-shadow: 0 0 1px #ffffff;
    }
</style>