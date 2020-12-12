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
                        <l-popup class="text-center">
                            <inertia-link class="text-blue-500" :href="route('overview.view', {bin: bins[index].id})">
                                <b> {{ bins[index].name }} </b>
                            </inertia-link><br>
                            {{ bins[index].address }}, {{ bins[index].city }} <br>
                            {{ showDate(bins[index].last_active_at) }} <br>
                            Fire status: {{ onFire(bins[index].latest_value.on_fire) }} , Fullness: {{ bins[index].latest_value.percentage_full }}%
                            <br>
                            <inertia-link :href="route('overview.edit_form', {bin: bins[index].id})"
                                          class="text-indigo-600 hover:text-indigo-900">
                                Edit
                            </inertia-link>


                        </l-popup>
                    </l-marker>
                </l-map>
            </div>

            <div class="flex flex-col">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
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
    import {LMap, LTileLayer, LMarker, LPopup} from "vue2-leaflet";

    export default {
        components: {
            AppLayout,
            Container,
            LMap,
            LTileLayer,
            LMarker,
            LPopup
        },
        props: {
            bins: Array,
        },
        data() {
            return {
                markers: null,
                loading: 1,
                zoom: 10,
                center: latLng(52.4040133, 5.2616974),
                url: "https://{s}.tile.osm.org/{z}/{x}/{y}.png",
                attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors| Powered by <a href="https://www.geoapify.com/">Geoapify</a>',
            };
        },
        mounted() {
            this.setMarkers()
        },
        methods: {
            setMarkers: function () {
                let markersArray = [];
                this.bins.forEach(function (bin) {
                    markersArray.push(latLng(bin.lat, bin.lon));
                });
                this.markers = markersArray;
                this.loading = 0;
            },
            /** From SQL Timestamps to short date with time in hh:mm */
            showDate(date){
                let shortDate = new Date(date).toLocaleDateString();
                let hourParts = date.split("T");
                let time = hourParts[1].substr(0,5);
                return time + " " + shortDate;
            },
            onFire(status){
                if(status == 0){
                    return "not on fire."
                } else {
                    return "on fire!"
                }
            }
        }
    }
</script>

<style scoped>
    .map {
        height: 70vh;
    }
</style>
