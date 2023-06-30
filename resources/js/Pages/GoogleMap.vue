<template>
    <Head title="Об'єкти на карті"/>

    <map-guest-layout>
        <template #header>
            <div class="bg-white flex w-full justify-between py-2 px-4">
                <div class="flex gap-4 w-max">
                    <button @click="$inertia.get('/')" class="whitespace-nowrap">На головну</button>
                    <button @click="$inertia.get(`/list/${activeForestry.id}`)">
                        Список
                    </button>
                </div>                
                <div class="flex gap-4 w-max">
                    <grid-button @click="toggleKmlLayer" :isActive="kmlLayerIsActive" />
                    <multiselect 
                        v-model="localActiveAffiliate" :can-clear="false" :close-on-deselect="false"
                        :close-on-select="false"
                        :hide-selected="false"
                        :options="notEmptyAffiliates" :show-options="true" class="affiliate" mode="single"
                        value-prop="id"
                    >

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
                    
                    <div class="min-w-[250px] flex gap-2">
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
                    
                </div>
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
            <InfoWindow v-if="kmlLayerInfo.point"
                :options="{ position: kmlLayerInfo.position, zIndex: 20, pixelOffset: {height: -20} }"
                @closeclick="removeActiveKml"
            >
                <v-card :point="kmlLayerInfo.point" style="width: 300px; max-width: 100%"/>
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
import GridButton from '@/Components/GridButton.vue';

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
        Multiselect,
        GridButton,
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
                zoom: 10,
                center: {lat: 51.09896683851218, lng: 25.667232758549517},
            },

            localActiveAffiliate: null,
            localActiveForestry: null,
            bounds: null,
            selectedCategories: [],
            isLoaded: false,
            activeMarker: null,
            kmlLayerIsActive: false,
            kmlLayerInfo: {
                featureData: {},
            },
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
            this.uploadKmlFile()
        }, 500)
    },
    methods: {
        openInfoWindow(marker) {
            this.activeMarker = marker
        },
        toggleKmlLayer() {
            this.kmlLayerIsActive = !this.kmlLayerIsActive
        },
        openKmlWindow(props) {
            console.log(props)
            const { name, description } = props.featureData;
            this.kmlLayerInfo = {
                position: {
                    lat: props.latLng.lat(),
                    lng: props.latLng.lng(),
                },
                point: {
                    title: 'test',
                    description: 'test',
                },
            }
        },
        removeActiveMarker() {
            this.activeMarker = null
            this.fitBounds()
        },
        closeKmlWindow() {
            this.kmlLayerInfo = {}
        },
        fitBounds() {
            setTimeout(() => {
                const { api, map } = this.$refs.map;

                this.bounds = new api.LatLngBounds()
                this.filteredPoints.forEach(point => {
                    this.bounds.extend(point.geo)
                })
                map.fitBounds(this.bounds)
            }, 0)

        },
        uploadKmlFile() {
            const { api, map } = this.$refs.map;
            // const slug = this.activeAffiliate.code
            const slug = 'test3'

            const kml = new api.KmlLayer(`http://dashboard.polisky.com.ua:8787/${slug}.kml`, {
                map,
                suppressInfoWindows: true,
                preserveViewport: false,
            })

            kml.addListener("click", this.openKmlWindow)
            console.log(kml)
            map.kml = kml; 
        },
        goToForestry(id) {
            if (id !== this.activeForestry.id)
                this.$inertia.get(`/map/${id}`)
        }
    },
    watch: {
        selectedCategories() {
            this.fitBounds()
        },
        kmlLayerIsActive(value) {
            const map = this.$refs.map.map
            if (value) {
                map.kml.setMap(this.$refs.map.map)
            } else {
                map.kml.setMap(null)
            }
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
