<template>
    <div class="grid grid-cols-6">
        <div class="col-span-6 sm:col-span-2">
            <label :for="id" class="block text-sm font-medium leading-6 text-gray-900">
                <slot name="label">
                    {{ label }}
                </slot>
            </label>
        </div>

        <div class="col-span-6 sm:col-span-4">
            <slot/>
        </div>

        <div class="col-span-12 text-sm text-red-600 opacity-75">{{ error }}</div>
    </div>
</template>

<script>
export default {
    name: "FormGroup",
    props: {
        label: {},
        id: {},
        error: {}
    }
}
</script>

<style scoped>

</style>
