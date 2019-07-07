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
    export default {
        props: [ 'summary' ],
        computed: {
            barWidth: function() {
                return this.summary/117000 * 100;
            },
        },
        methods: {
            showPieChart() {
                $('.pie-chart').toggle();
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