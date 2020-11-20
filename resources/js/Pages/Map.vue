<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Map
            </h2>
        </template>

        <container>

            <div v-if="loading === 0">
                <l-map class="map" :zoom="zoom" :center="center">
                    <l-tile-layer :url="url" :attribution="attribution"></l-tile-layer>
                    <l-marker v-for="(marker, index) in markers" :key="index" :lat-lng="marker">
                    </l-marker>
                </l-map>
            </div>

            <div class="flex flex-col">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                <tr>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                        Name
                                    </th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                        Last Active At
                                    </th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                        X
                                    </th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                        Y
                                    </th>
                                </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="location in locations" :key="location.id">
                                    <td class="px-6 py-4 whitespace-no-wrap">
                                        <div class="text-sm leading-5 text-gray-900">
                                            {{ location.bin.name }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-no-wrap">
                                        <div class="text-sm leading-5 text-gray-900">
                                            {{ location.bin.last_active_at }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-no-wrap">
                                        <div class="text-sm leading-5 text-gray-900">
                                            {{ location.x }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-no-wrap">
                                        <div class="text-sm leading-5 text-gray-900">
                                            {{ location.y }}
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </container>
    </app-layout>
</template>

<script>
    import AppLayout from './../Layouts/AppLayout'
    import Container from './../Components/Container'
    import {latLng} from "leaflet";
    import {LMap, LTileLayer, LMarker} from "vue2-leaflet";

    export default {
        components: {
            AppLayout,
            Container,
            LMap,
            LTileLayer,
            LMarker
        },
        props: {
            locations: Array,
        },
        data() {
            return {
                markers: null,
                loading: 1,
                zoom: 10,
                center: latLng(52.4040133, 5.2616974),
                url: "https://{s}.tile.osm.org/{z}/{x}/{y}.png",
                attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors',
            };
        },
        mounted() {
            this.setMarkers()
        },
        methods: {
            setMarkers: function () {
                let markersArray = [];
                this.locations.forEach(function (location) {
                    markersArray.push(latLng(location.x, location.y));
                });
                this.markers = markersArray;
                this.loading = 0;
            }
        }
    }
</script>

<style scoped>
    .map {
        height: 30vh;
    }
</style>
