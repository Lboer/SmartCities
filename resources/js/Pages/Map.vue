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
                            </inertia-link>
                            <br>
                            {{ bins[index].address }}, {{ bins[index].city }} <br>
                            {{ bins[index].last_active_at }} <br>
                            On fire: {{ bins[index].latest_value.on_fire }} , Full: {{
                            bins[index].latest_value.percentage_full }}%
                            cm <br>
                            <inertia-link :href="route('overview.edit_form', {bin: bins[index].id})"
                                          class="text-indigo-600 hover:text-indigo-900">
                                Edit
                            </inertia-link>
                        </l-popup>
                    </l-marker>
                </l-map>
            </div>
        </container>

        <container>
            <div>
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                        <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                            <div class="mt-8 text-2xl">
                                Route
                            </div>
                            <form @submit.prevent="submit">
                                <div class="mt-6 text-gray-500">
                                    Retrieve garbage bins with a fullness of <input id="fullness" class="form-input"
                                                                                    type="number"
                                                                                    min="0" max="100"
                                                                                    v-model="form.fullness"/>%
                                </div>
                                <jet-button type="submit" class="flex my-8 mx-auto items-center justify-center">
                                    Determine pick-up route
                                </jet-button>
                            </form>
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
    import {LMap, LTileLayer, LMarker, LPopup, LPolyline} from "vue2-leaflet";
    import JetButton from '@/Jetstream/Button.vue';

    export default {
        components: {
            AppLayout,
            Container,
            LMap,
            LTileLayer,
            LMarker,
            LPopup,
            LPolyline,
            JetButton
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
                form: {
                    fullness: 70
                },
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
            submit() {
                this.$inertia.post('/map/route', this.form)
            }
        }
    }
</script>

<style scoped>
    .map {
        height: 40vh;
    }
</style>
