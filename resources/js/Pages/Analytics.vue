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
                            <p class="container my-8 text-center" v-if="loaded"> {{ showBinInformation() }} </p>
                            <line-chart
                            v-if="loaded"
                            :chartdata="chartdata"
                            :options="options"
                            />
                            <p class="container my-8 text-center" v-if="warning">This bin does not have enough data to create a graph.</p>
                            <div class="w-full flex my-8 mx-auto items-center justify-center">
                                <jet-button class="flex my-8 mx-auto items-center justify-center" @click.native="predictFullness" v-if="loaded && !predicted">
                                    Predict the next half hour
                                </jet-button>
                            </div>
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
            showBinInformation(){
                var object = this.$props.bins.filter(obj => {
                    return obj.id == this.selectedBinId;
                })
                if(object[0].address != null && object[0].city != null){
                    return object[0].address + ", " + object[0].city;
                } else if(object[0].address != null){
                    return object[0].address;
                } else if(object[0].city != null){
                    return object[0].city;
                }
            },
            resetGraph(){
                this.predictAmmount = 0;
                this.predictTotalAdded = 0;
                this.predictMedianAdd = 0;
                this.loaded = false;
                this.predicted = false;
            },
            async selectBin(event){
                this.resetGraph();
                if(event.target.value != "select"){
                    try{
                        this.selectedBinId = event.target.value;
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
                // verstop de "predict the next half hour" button
                this.predicted = true;
                // gebruik een anonymous callback om de graph opnieuw te renderen
                this.$nextTick( () => {
                    this.showChart();
                });
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
                let rearangedData = this.chartdata.labels[this.finalOriginalValue].split(" ");
                let rearangedHours = rearangedData[0];
                rearangedData = rearangedData[1].split("-");
                rearangedData = rearangedData[2] + "-" + rearangedData[1] + "-" + rearangedData[0];
                let oldDate = new Date(rearangedData + " " + rearangedHours);
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
            predictMedianAdd: 0,
            selectedBinId: null,
        }),
    }
</script>