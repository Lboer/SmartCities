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
import ConfirmsPasswordVue from '../../../vendor/laravel/jetstream/stubs/inertia/resources/js/Jetstream/ConfirmsPassword.vue'

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
                this.predicted = false;
                if(event.target.value != "select"){
                    try{
                        // get json data as a promise
                        let promise = await fetch('api/data/' + event.target.value)
                        .then(response => response.json())
                        // If there are 2 or more points of data, create a graph
                        if(promise.length > 1){
                            this.warning = false;
                            //set standard data
                            this.chartdata = {
                                labels: [],
                                datasets: [{
                                    label: "Fullness",
                                    backgroundColor: '#72bcd4',
                                    data: []
                                }]
                            };
                            // for each data point in the promise, render in graph
                            for(let i = 0; i < promise.length; i++){
                                this.chartdata.labels.push(this.showDate(promise[i].updated_at))
                                this.chartdata.datasets[0].data.push(promise[i].percentage_full)
                            };
                            // add chart with min of 0, max of 100 and steps of 10
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
                        // else tell user that a graph requires more data
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
                let shortDate = new Date(date).toLocaleDateString();
                let hourParts = date.split("T");
                let time = hourParts[1].substr(0,5) + ":00";
                return time + " " + shortDate;
            },
            /** predict the next half hour */
            predictFullness(event){
                this.loaded = false;
                this.finalOriginalValue = this.chartdata.labels.length - 1;
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
                    this.chartdata.labels.push(this.renderName((i+1)*5));
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
            },
            // render name with "prediction: " at the start, so the user knows it is not actual data.
            renderName(value){
                let oldDate = new Date(this.chartdata.labels[this.finalOriginalValue].replace("-", "/"));
                let date = new Date(oldDate.getTime() + value*60000).toLocaleString();
                let switchDate = date.split(" ");
                let splitDate = switchDate[0].split("-");
                let finalDate = splitDate[1] + "-" + splitDate[0] + "-" + splitDate[2];
                return "prediction: " + switchDate[1] + " " + finalDate;
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
            finalOriginalValue: null,
            predictAmmount: 0,
            predictTotalAdded: 0,
            predictMedianAdd: 0
        }),
    }
</script>
