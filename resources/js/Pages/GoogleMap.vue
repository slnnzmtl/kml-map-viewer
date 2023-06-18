<template>
    <Head title="Об'єкти на карті"/>

    <map-guest-layout>
        <template #header>
            <div class="min-w-[250px]">
                <multiselect v-model="localActiveAffiliate" :can-clear="false" :close-on-deselect="false"
                             :close-on-select="false"
                             :hide-selected="false"
                             :options="notEmptyAffiliates" :show-options="true" class="affiliate" mode="single"
                             value-prop="id">


                    <template #multiplelabel="{values}">
                        <div class="p-2 mr-auto">
                            Вибрано - {{ values }}
                        </div>
                    </template>

                    <template #singlelabel>
                        <div class="mr-auto p-2">{{ activeAffiliate.title }} - {{ activeForestry.title }}</div>
                    </template>

                    <template #option="{option:affiliate, isSelected}">
                        <div class="relative w-full ">
                            <span>{{ affiliate.title }}</span>

                            <template v-if="isSelected(affiliate)">
                                <div class="absolute left-full top-0 bg-white rounded overflow-hidden text-black"
                                     style="min-width: 200px">
                                    <ul>
                                        <li v-for="forestry in affiliate.forestry" :key="forestry.id"
                                            :class="{'bg-gray-200 pointer-events-none': localActiveForestry === forestry.id, 'pointer-events-none': forestry.id === activeForestry.id}"
                                            :value="forestry.id"
                                            class="p-3"
                                            @click="goToForestry(forestry.id)">
                                            {{ forestry.title }}
                                        </li>
                                    </ul>
                                </div>
                            </template>
                        </div>
                    </template>
                </multiselect>
            </div>

            <div class="absolute top-0 mx-auto w-[50px] left-0 right-0 text-center">
                <button class="shadow-md p-3 bg-white rounded-b" @click="$inertia.get(`/list/${activeForestry.id}`)">
                    Список
                </button>
            </div>

            <div class="min-w-[250px]">
                <multiselect v-model="selectedCategories" :close-on-select="false" :hide-selected="false"
                             :options="filteredCategories"
                             mode="multiple"
                             placeholder="Категорії"
                             value-prop="id">

                    <template #multiplelabel="{values}">
                        <div class="p-2 mr-auto">
                            Вибрано - {{ values.length }}
                        </div>
                    </template>

                    <template #placeholder>
                        <div class="p-2 mr-auto">
                            Категорії
                        </div>
                    </template>

                    <template #option="{option:category, isSelected}">
                        <div>
                            <img :src="category.image_url" alt="" class="inline-block mr-2" width="20">
                            <span>{{ category.title }}</span>
                        </div>
                    </template>
                </multiselect>
            </div>
        </template>

        <google-map ref="map" :api-key="apiKey" class="google__map" v-bind="mapOptions">
            <custom-marker v-for="marker in filteredPoints" :options="{...markerOptions, position: marker.geo}"
                           @click="openInfoWindow(marker)">
                <div :title="marker.title">
                    <img :src="marker.category.image_url" alt="" width="40">
                </div>

            </custom-marker>
            <InfoWindow v-if="activeMarker"
                        :options="{ position: activeMarker.geo, zIndex: 20, pixelOffset: {height: -20} }"
                        @closeclick="removeActiveMarker">
                <v-card :point="activeMarker" style="width: 300px; max-width: 100%"/>
            </InfoWindow>
        </google-map>
    </map-guest-layout>
</template>

<script>
import {Head} from '@inertiajs/vue3';

import {GoogleMap, CustomMarker, InfoWindow} from 'vue3-google-map'

import MapGuestLayout from "@/Layouts/MapGuestLayout.vue";
import Dropdown from "@/Components/Dropdown.vue";
import NavLink from "@/Components/NavLink.vue";
import {Menu, MenuItem, MenuItems} from "@headlessui/vue";
import Multiselect from '@vueform/multiselect'
import VCard from "@/Components/VCard.vue";

import("@vueform/multiselect/themes/default.css")
export default {
    name: "Home",
    components: {
        VCard,
        MenuItem,
        MenuItems,
        Menu,
        NavLink,
        Dropdown,
        MapGuestLayout,
        GoogleMap, CustomMarker, InfoWindow,
        Head,
        Multiselect
    },
    data: function () {
        return {
            apiKey: 'AIzaSyC0_AC0vJLbsCJ8XVkVc_a4JdDSwC0gQkc',
            markerOptions: {anchorPoint: 'CENTER'},
            mapOptions: {
                mapTypeId: "hybrid",
                draggable: true,
                streetViewControl: false,
                mapTypeControl: false,
                fullscreenControl: false,
                zoom: 15,
                center: {lat: 51.09896683851218, lng: 25.667232758549517},
            },

            localActiveAffiliate: null,
            localActiveForestry: null,
            bounds: null,
            selectedCategories: [],
            isLoaded: false,
            activeMarker: null
        }
    },
    props: {
        activeAffiliate: {},
        activeForestry: {},
        affiliates: {},
        points: {},
        categories: {}
    },
    computed: {
        filteredPoints() {

            return this.points
                .filter(point => !this.selectedCategories.length || this.selectedCategories.includes(point.category.id))
                .map(point => {
                    const {lat, lng} = JSON.parse(point.geo)
                    return {
                        ...point,
                        geo: {lat: +lat, lng: +lng}
                    }
                })
        },
        filteredCategories() {
            return this.categories.filter(cat => this.points.filter(point => point.category.id === cat.id).length)
        },
        notEmptyAffiliates() {
            return this.affiliates.filter(a => a.forestry.filter(f => f.points_count).length)
                .map(a => ({
                    ...a,
                    forestry: a.forestry.filter(f => f.points_count)
                }))
        }
    },
    beforeMount() {
        this.localActiveAffiliate = this.activeAffiliate.id
        this.localActiveForestry = this.activeForestry.id
    },
    mounted() {
        setTimeout(() => {
            this.fitBounds()
        }, 500)

    },
    methods: {
        openInfoWindow(marker) {
            this.activeMarker = marker
        },
        removeActiveMarker() {
            this.activeMarker = null
            this.fitBounds()
        },
        fitBounds() {
            setTimeout(() => {
                this.bounds = new this.$refs.map.api.LatLngBounds()
                this.filteredPoints.forEach(point => {
                    this.bounds.extend(point.geo)
                })
                this.$refs.map.map.fitBounds(this.bounds)


                // const kml = new this.$refs.map.api.KmlLayer('https://developers.google.com/maps/documentation/javascript/examples/kml/westcampus.kml', {

                setTimeout(() => {


                    const kml = new this.$refs.map.api.KmlLayer('http://localhost:8080/google-kml?file=cta.kml', {
                        // url: 'http://localhost:8080/google-kml?file=cta.kml',
                        // zIndex: 999,
                        // screenOverlays: true,
                        // suppressInfoWindows: true,
                        // preserveViewport: false,
                        map: this.$refs.map.map
                    })
                    //
                    console.log(kml)
                }, 1000)
            }, 0)

        },
        goToForestry(id) {
            if (id !== this.activeForestry.id)
                this.$inertia.get(`/map/${id}`)
        }
    },
    watch: {
        selectedCategories() {
            this.fitBounds()
        }
    }
}
</script>

<style>
.google__map {
    width: 100%;
    height: 100vh;
}

.affiliate .multiselect-dropdown {
    overflow: initial;
    max-height: initial;
}
</style>
