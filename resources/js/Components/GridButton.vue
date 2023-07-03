<template>
    <button v-if="isActive" class="border-transparent p-2 rounded-md bg-white">
        <img alt="" src="@/../images/grid.svg">
    </button>
    <button v-else class="border-transparent p-2 rounded-md bg-white opacity-50">
        <img alt="" src="@/../images/grid.svg">
    </button>
</template>

<script>
    export default {
        name: 'GridButton',
        props: {
            isActive: false,
        }
    }
</script>