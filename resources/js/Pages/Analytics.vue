<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Analytics
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <select id="selectBin" @change="selectBin"
                                    class="w-6/12 flex my-8 mx-auto items-center shadow appearance-none border rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                    <option value="select">Select a bin</option>
                                <option v-for="bin in bins"
                                        :key="bin.id"
                                        :value="bin.id">
                                    {{ bin.name }}
                                </option>
                            </select>
                    <template>
                        <div class="container my-8">
                            <line-chart
                            v-if="loaded"
                            :chartdata="chartdata"
                            :options="options"
                            />
                            <p class="container my-8 text-center" v-if="warning">This bin does not have enough data to create a graph.</p>
                            <jet-button class="flex my-8 mx-auto items-center" @click.native="predictFullness" v-if="loaded && !predicted">
                                Predict the next half hour
                            </jet-button>
                            <jet-button class="flex my-8 mx-auto items-center" @click.native="showChart" v-if="predicted && !loaded">
                                Render chart
                            </jet-button>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
    import AppLayout from '@/Layouts/AppLayout'
    import LineChart from '@/Pages/Analytics/Chart.vue'
    import JetButton from '@/Jetstream/Button.vue'

    export default {
        props:{
            bins: Array
        },
        methods:{
            async selectBin(event){
                this.predictAmmount = 0;
                this.predictTotalAdded = 0;
                this.predictMedianAdd = 0;
                this.loaded = false;
                if(event.target.value != "select"){
                    try{
                        let promise = await fetch('api/data/' + event.target.value)
                        .then(response => response.json())
                        if(promise.length > 1){
                            this.warning = false;
                            this.chartdata = {
                                labels: [],
                                datasets: [{
                                    label: "Fullness",
                                    backgroundColor: '#72bcd4',
                                    data: []
                                }]
                            };
                            for(let i = 0; i < promise.length; i++){
                                this.chartdata.labels.push(this.showDate(promise[i].updated_at))
                                this.chartdata.datasets[0].data.push(promise[i].percentage_full)
                            };
                            this.options = {
                                responsive: true,
                                maintainAspectRatio: false,
                                scales: {
                                    yAxes: [{
                                        ticks:{
                                            max: 100,
                                            min: 0,
                                            stepSize: 10
                                        }
                                    }]
                                }
                            }
                            this.showChart();
                        }
                        else{
                            this.loaded = false;
                            this.warning = true;
                        }
                    } catch (e) {
                    console.error(e)
                    }
                }
            },
            /** From SQL Timestamps to short date with time in hh:mm */
            showDate(date){
                let dateParts = date.split("-");
                let shortDate = new Date(dateParts[0], dateParts[1] - 1, dateParts[2].substr(0,2)).toLocaleDateString('en-US');
                let hourParts = date.split("T");
                let time = hourParts[1].substr(0,5);
                return time + " " + shortDate;
            },
            /** predict the next half hour */
            predictFullness(event){
                this.loaded = false;
                // get the median increase
                for(let i = 1; i < this.chartdata.labels.length; i++){
                    if(this.chartdata.datasets[0].data[i-1] < this.chartdata.datasets[0].data[i]){
                        this.predictAmmount++;
                        this.predictTotalAdded += this.chartdata.datasets[0].data[i] - this.chartdata.datasets[0].data[i-1];
                    }
                }
                this.predictMedianAdd = this.predictTotalAdded / this.predictAmmount;
                let beginValue = this.chartdata.datasets[0].data[this.chartdata.datasets[0].data.length-1];
                for(let i = 0; i < 6; i++){
                    beginValue = this.safeIncrease(beginValue);
                    this.chartdata.labels.push("predict +" + ((i+1) * 5) + " mins")
                    this.chartdata.datasets[0].data.push(beginValue)
                }
                this.predicted = true;
            },
            safeIncrease(value){
                value += this.predictMedianAdd;
                if(value > 100){
                    value = 100;
                }
                return value;
            },
            showChart(){
                this.loaded = true;
            }
        },
        name: 'LineChartContainer',
        components: {
            AppLayout, 
            LineChart,
            JetButton
            },
        data: () => ({
            loaded: false,
            chartdata: null,
            options: null,
            warning: false,
            predicted: false,
            predictAmmount: 0,
            predictTotalAdded: 0,
            predictMedianAdd: 0
        }),
    }
</script>
