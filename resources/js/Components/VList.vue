<template>
    <div class="list__items">
        <div class="list__items-item" v-for="(item, index) in items" :key="index">
            <v-card :title="item.title" :description="item.description" :image="item.image" />
        </div>
    </div>

</template>

<script>
import VCard from "@/Components/VCard.vue";


export default {
    name: "VList",
    components: {
        VCard
    },
    props: {
        items: {
            type: Object
        }
    }
}
</script>

<style scoped>
.list__items {
    padding: 90px 15px;
    display: flex;
    column-gap: 40px;
    row-gap: 32px;
    flex-wrap: wrap
}

.list__items-item {
    flex: 1 0 25%;
    max-width: 25%;
    /* padding: 0 25px; */
}
</style>
