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
                                    class="w-full mb-8 shadow appearance-none border rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                    <option>Select a bin</option>
                                <option v-for="bin in bins"
                                        :key="bin.id"
                                        :value="bin.id">
                                    {{ bin.name }}
                                </option>
                            </select>
                    <template>
                        <div class="container">
                            <line-chart
                            v-if="loaded"
                            :chartdata="chartdata"
                            />
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
            selectBin(event){
                try{
                    let data = fetch('api/data/' + event.target.value);
                    console.log(data);
                    this.chartdata = data;
                    this.loaded = true;
                } catch (e) {
                   console.error(e)
                }
                
            }
        },
        name: 'LineChartContainer',
        components: {
            AppLayout, 
            LineChart 
            },
        data: () => ({
            loaded: false,
            chartdata: null
        }),
        // async mounted () {
        //     this.loaded = false
        //     try {
        //     let { userlist } = await fetch('/api/data/2')
            
        //     if(this.chartdata != undefined){
        //         this.loaded = true
        //     }
        //     } catch (e) {
        //     console.error(e)
        //     }
        // }
    }
</script>
