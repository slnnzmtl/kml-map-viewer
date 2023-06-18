<template>
    <div class="min-h-screen max-h-screen flex flex-col sm:pt-0 bg-gray-100 bg-white">
        <div class="fixed w-full flex top-0 left-0 justify-between z-10">
            <div class="flex w-full">
                <slot name="logo"/>

                <div class="flex justify-between w-full">
                    <slot name="header"/>
                </div>
            </div>
        </div>

        <div class="w-full grow overflow-hidden flex justify-center" :class="containerClasses">
            <slot/>
        </div>

        <div class="fixed bottom-0 left-0 w-full flex justify-between  overflow-y-auto">
            <slot name="footer"/>
        </div>
    </div>
</template>

<script>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import {Link} from '@inertiajs/vue3';
import ResponsiveNavLink from "@/Components/ResponsiveNavLink.vue";
import NavLink from "@/Components/NavLink.vue";

export default {
    components: {NavLink, ResponsiveNavLink},
    props: {
        containerClasses: {
            type: String
        },
        auth: {}
    }
}
</script>
