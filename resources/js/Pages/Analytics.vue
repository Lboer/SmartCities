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

                if(event.target.value != "select"){
                    try{
                        let promise = await fetch('api/data/' + event.target.value)
                        .then(response => response.json())
                        /** TO DO : Change from an array to a single object */
                        if(promise.length > 1){
                            this.warning = false;
                            this.chartdata = {
                                labels: [this.showDate(promise[0].updated_at), this.showDate(promise[1].updated_at)],
                                datasets: [{
                                    label: "Fullness",
                                    backgroundColor: '#f87979',
                                    data: [promise[0].percentage_full, promise[1].percentage_full]
                                }]
                            };
                            this.options = {
                                responsive: true,
                                maintainAspectRatio: false
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
            showDate(date){
                let dateParts = date.split("-");
                let shortDate = new Date(dateParts[0], dateParts[1] - 1, dateParts[2].substr(0,2)).toLocaleDateString('en-US');
                let hourParts = date.split("T");
                let time = hourParts[1].substr(0,5);
                return shortDate + " " + time;
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
