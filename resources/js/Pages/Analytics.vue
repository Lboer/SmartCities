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

    export default {
        props:{
            bins: Array
        },
        methods:{
            async selectBin(event){
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
                            this.loaded = true;
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
            }
        },
        name: 'LineChartContainer',
        components: {
            AppLayout, 
            LineChart 
            },
        data: () => ({
            loaded: false,
            chartdata: null,
            options: null,
            warning: false
        }),
    }
</script>
