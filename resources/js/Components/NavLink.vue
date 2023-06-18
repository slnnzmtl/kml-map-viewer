<script setup>
import {computed} from 'vue';
import {Link} from '@inertiajs/vue3';

const props = defineProps({
    href: {
        type: String,
        required: true,
    },
    active: {
        type: Boolean,
    },
});

const classes = computed(() =>
    props.active
        ? 'border-indigo-400  text-sky-600 focus:outline-none focus:border-indigo-700'
        : 'border-transparent text-gray-900 hover:text-sky-600 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300'
);
</script>

<template>
    <Link :href="href" :class="classes"
          class="inline-flex  items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 transition duration-150 ease-in-out">
        <slot/>
    </Link>
</template>
