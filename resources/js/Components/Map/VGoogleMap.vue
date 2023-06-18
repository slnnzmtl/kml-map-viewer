<template>
    <GoogleMap ref="map" :api-key="apiKey" class="google__map" v-bind="mapOptionsCenter" @change_center="centerChange">

        <template v-if="markers.length">
            <template v-for="marker in markers">
                <custom-marker :options="{...markerOptions, position: marker.position}">
                    <slot :marker="marker" name="marker"/>
                </custom-marker>
            </template>
        </template>

    </GoogleMap>

</template>

<script>
import {CustomControl, CustomMarker, GoogleMap} from 'vue3-google-map'

export default {
    name: "VGoogleMap",
    components: {GoogleMap, CustomMarker, CustomControl},
    props: {
        mapOptions: {},
        markerOptions: {},
        markers: {
            type: Array,
            default: () => []
        },
        haveMarkers: {
            default: false
        },
        showSearch: {
            default: false
        },
        center: {}
    },
    data: () => ({
        apiKey: 'AIzaSyDezJbfeP1zB4Lj6KQA5uxBn1EjvSNQXJs',
        bounds: null
    }),
    computed: {
        mapOptionsCenter() {
            return {
                ...this.mapOptions,
                // center: this.center
            }
        },

    },
    mounted() {
        setTimeout(() => {
            // console.log(this.$refs.map.map);
            this.bounds = new this.$refs.map.api.LatLngBounds()
            // console.log(this.bounds)
        }, 500)

    },
    emits: ['update:center'],
    methods: {
        centerChange() {
            setTimeout(() => {
                this.$emit('update:center', this.$refs.map.map.center.toUrlValue())
            }, 200)

        },
    },
    watch: {
        center(center) {
            this.$nextTick(() => {
                this.$refs.map.map.setCenter({
                    lat: center.split(',')[0].trim(),
                    lng: center.split(',')[1].trim()
                })
            })
        }
    }
}
</script>

<style scoped>
.google__map {
    width: 100%;
    height: 100%;
}
</style>
