<template>
    <div>
        <canvas id="pie-chart" width="400" height="400"></canvas>
    </div>
</template>
<script>
    import Chart from 'chart.js';

    export default {
        created() {
            this.fetchPieData();
        },
        methods: {
            fetchPieData() {
                axios
                    .get( '/api/pie-data' )
                    .then( response => {
                        this.pieData = response.data;
                        this.createPieChart();
                    }).catch( error => {
                    this.error = error.response.data.message || error.message;
                });
            },
            createPieChart(){
                console.log(this.pieData);
                var ctx = document.getElementById('pie-chart');
                var PieChart = new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        datasets: [{
                            data: [10, 20, 30],
                            backgroundColor:
                                ['#aec7e8','#ffbb78','#98df8a','#ff9896','#c5b0d5','#c49c94','#f7b6d2','#c7c7c7','#dbdb8d','#9edae5']
                        }],

                        labels: [
                            'Red',
                            'Yellow',
                            'Blue'
                        ]
                    },
                    options: {}
                });
            }
        }
    }
</script>

<style scoped>

</style>