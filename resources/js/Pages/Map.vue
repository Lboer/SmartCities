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
                            <b>{{ locations[index].bin.name }}</b> <br>
                            {{ locations[index].bin.last_active_at }} <br>
                            Temp: {{ locations[index].bin.temperature}}°C, Distance: {{ locations[index].bin.distance }} cm


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
        height: 70vh;
    }
</style>
