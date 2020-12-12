<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Route
            </h2>
        </template>

        <container>
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-auto shadow-xl sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                        <tr>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                Bins to retrieve
                            </th>
                            <td class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                Fullness
                            </td>
                            <td class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                Total distance
                            </td>
                            <td class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                Total estimated time
                            </td>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        <tr>
                            <th class="px-6 py-4 whitespace-no-wrap">
                                {{ routeData.properties.legs.length - 1 }}
                            </th>
                            <td class="px-6 py-4 whitespace-no-wrap">
                                {{ fullness }} %
                            </td>
                            <td class="px-6 py-4 whitespace-no-wrap">
                                {{ (routeData.properties.time/60).toFixed(0) }} minutes
                            </td>
                            <td class="px-6 py-4 whitespace-no-wrap">
                                {{ (routeData.properties.distance/1000).toFixed(2) }} kilometres
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </container>

        <container v-for="(leg, index) in routeData.properties.legs" :key="index">
            <div>
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white overflow-auto shadow-xl sm:rounded-lg">
                        <div class="p-6 sm:px-20 bg-white border-b border-gray-200 overflow-auto">
                            <span v-if="routeData.properties.legs.length === index + 1">
                                <b>To depot</b>
                            </span>
                            <span v-else>
                                <b>To garbage bin {{ index + 1 }}</b>
                            </span>
                            <br>
                            Estimated time to location: {{ (leg.time/60).toFixed(0) }} minutes
                            <br>
                            Distance to location: {{ (leg.distance/1000).toFixed(2) }} kilometres
                        </div>
                        <div v-if="loading === 0">
                            <l-map class="map" :zoom="zoom" :center="center">
                                <l-tile-layer :url="url" :attribution="attribution"></l-tile-layer>
                                <l-polyline :lat-lngs="coordinates[index]" />
                            </l-map>
                        </div>
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                            <tr>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                    #
                                </th>
                                <td class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                    Direction
                                </td>
                                <td class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                    Distance
                                </td>
                            </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="(step, index) in leg.steps" :key="index">
                                <th class="px-6 py-4 whitespace-no-wrap">
                                    {{ index + 1 }}
                                </th>
                                <td class="px-6 py-4 whitespace-no-wrap">
                                    {{ step.instruction.text }}
                                </td>
                                <td class="px-6 py-4 whitespace-no-wrap">
                                    {{ (step.distance/1000).toFixed(2) }} kilometres
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </container>
    </app-layout>
</template>

<script>
    import AppLayout from '../../Layouts/AppLayout'
    import Container from '../../Components/Container'
    import {latLng} from "leaflet";
    import {LMap, LTileLayer, LPolyline} from "vue2-leaflet";

    export default {
        components: {
            AppLayout,
            Container,
            LMap,
            LTileLayer,
            LPolyline
        },
        props: {
            routeData: Object,
            fullness: Number,
        },
        data() {
            return {
                loading: 1,
                zoom: 11,
                center: latLng(52.372020, 5.216710),
                url: "https://{s}.tile.osm.org/{z}/{x}/{y}.png",
                attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors| Powered by <a href="https://www.geoapify.com/">Geoapify</a>',
                coordinates: []
            }
        }, mounted() {
            let geometry = this.$props.routeData.geometry.coordinates
            let coordinates = this.coordinates

            this.$props.routeData.properties.legs.forEach(function(element, index)   {
                let leg = []
                for (let i = 0; i < geometry[index].length; i++) {
                    [geometry[index][i][0],geometry[index][i][1]] = [geometry[index][i][1],geometry[index][i][0]]
                    leg.push(geometry[index][i])
                }
                coordinates.push(leg)
            })
            this.coordinates = coordinates
            this.loading = 0
        }
    }
</script>

<style scoped>
    .map {
        height: 30vh;
    }
</style>
