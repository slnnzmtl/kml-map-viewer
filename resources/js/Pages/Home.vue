<template>
    <Head title="Головна сторінка"/>

    <map-guest-layout>
        <map-s-v-g :affiliates="affiliates"/>
    </map-guest-layout>
</template>

<script>
import {Head} from '@inertiajs/vue3';

import MapGuestLayout from "@/Layouts/MapGuestLayout.vue";
import MapSVG from "@/Components/Map/MapSVG.vue";

export default {
    name: "Home",
    components: {
        Head,
        MapSVG,
        MapGuestLayout,
    },
    props: {
        auth: {},
        affiliates: {}
    },
    computed: {
        haveUser() {
            return !!this.auth?.user
        },
    },
    data: () => ({}),
    methods: {
    }
}
</script>
<style>

</style>
