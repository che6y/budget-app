<template>
    <div>
        <div class="pie-chart container"></div>
    </div>
</template>
<script>
    import * as d3 from 'd3';

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
                        this.createPie();
                    }).catch( error => {
                    this.error = error.response.data.message || error.message;
                });
            },
            createPie() {
                var width  = 600,
                    height = 600,
                    margin = 100;
                var radius = Math.min(width, height) / 2 - margin;

                var svg = d3.select(".pie-chart")
                    .append("svg")
                    .attr("width", '100%')
                    .attr("height", height)
                    .append("g")
                    .attr("transform", "translate(" + width / 2 + "," + height / 2 + ")");

                var data = this.pieData;

                var color = d3.scaleOrdinal()
                    .domain(data.map(d => d.title))
                    .range(d3.schemeSet3);

                var pie = d3.pie()
                    .sort(null)
                    .value(function(d) { return d.value.cost; });
                var data_ready = pie(d3.entries(data));

                var arc = d3.arc()
                    .innerRadius(radius * 0.5)
                    .outerRadius(radius * 0.8);

                var outerArc = d3.arc()
                    .innerRadius(radius * 0.9)
                    .outerRadius(radius * 0.9);

                svg
                    .selectAll('allSlices')
                    .data(data_ready)
                    .enter()
                    .append('path')
                    .attr('d', arc)
                    .attr('fill', function(d){ return(color(d.data.key)) })
                    .attr("stroke", "white")
                    .style("stroke-width", "2px")
                    .style("opacity", 0.7);

                // Legend
                // var legend = d3.select(".pie-chart")
                //     .append("svg")
                //     .attr("class", "legend")
                //     .attr("width", 120)
                //     .attr("height", 300)
                //     .selectAll("g")
                //     .data(data_ready)
                //     .enter()
                //     .append("g")
                //     .attr("transform", function(d, i) { return "translate(0," + i * 20 + ")"; });
                //
                // legend.append("rect")
                //     .attr("width", 18)
                //     .attr("height", 18)
                //     .style("fill", color);
                //
                // legend.append("text")
                //     .attr("x", 24)
                //     .attr("y", 9)
                //     .attr("dy", ".35em")
                //     .text(function(d) { return d.data.value.title; });
                // svg
                //     .selectAll('allLabels')
                //     .data(data_ready)
                //     .enter()
                //     .append('text')
                //     .attr("x", 24)
                //     .attr("y", 9)
                //     .attr("dy", ".35em")
                //     .text(function(d) { return d.data.value.title; })
                //     .append("rect")
                //     .attr("width", 18)
                //     .attr("height", 18)
                //     .attr('fill', function(d){ return(color(d.data.key)) });


                // var legend = d3.select("body").append("svg")
                //     .attr("class", "legend")
                //     .attr("width", 120)
                //     .attr("height", (data.columns.length - 1) * 20)
                //     .selectAll("g")
                //     .data(data.columns.slice(1).reverse())
                //     .enter().append("g")
                //     .attr("transform", function(d, i) { return "translate(0," + i * 20 + ")"; });
                //
                // legend.append("rect")
                //     .attr("width", 18)
                //     .attr("height", 18)
                //     .style("fill", color);
                //
                // legend.append("text")
                //     .attr("x", 24)
                //     .attr("y", 9)
                //     .attr("dy", ".35em")
                //     .text(function(d) { return d; });
            }
        }
    }
</script>

<style scoped>
    .pie-chart {
        position: fixed;
        display: none;
        margin-top: -20px;
        background-color: #ffffff;
        z-index: 99999999;
    }
</style>