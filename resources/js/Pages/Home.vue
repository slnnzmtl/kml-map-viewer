<template>
    <Head title="Головна сторінка"/>

    <map-guest-layout>
        <Map :affiliates="affiliates" />
        <Logo class="absolute bottom-4 left-4 w-64" />
    </map-guest-layout>
</template>

<script>
import {Head} from '@inertiajs/vue3';

import MapGuestLayout from "@/Layouts/MapGuestLayout.vue";
import Map from "@/Components/Map/Map.vue";
import Logo from '@/Components/Logo.vue';

export default {
    name: "Home",
    components: {
        Head,
        Map,
        MapGuestLayout,
        Logo
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
