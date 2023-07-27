<template>
    <button v-if="isLoading" @click="onClick" class="m-0 border-transparent p-2 rounded-md bg-white opacity-50 flex justify-center items-center">
        <Loader />
    </button>
    <button v-else-if="isActive" @click="onClick" class="border-transparent p-2 rounded-md bg-white opacity-50">
        <img alt="" src="@/../images/grid.svg">
    </button>
    <button v-else @click="onClick" class="border-transparent p-2 rounded-md bg-white ">
        <img alt="" src="@/../images/grid.svg">
    </button>
</template>

<script>
import Loader from '@/Components/Loader.vue'
    export default {
        name: 'GridButton',
        components: {
            Loader,
        },
        props: {
            isActive: false,
            isLoading: false,
        },
        methods: {
            onClick(e) {
                this.$emit('onClick', e)
            }
        }
    }
</script>