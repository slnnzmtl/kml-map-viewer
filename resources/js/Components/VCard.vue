<template>
    <div class="card-item flex flex-col">
        <div v-if="point.media" class="card-item-img">
            <carousel ref="slider" :autoplay="2000" :mouse-drag="true" :touch-drag="true" :wrap-around="true"
                      @init="$refs.slider.restartCarousel()">
                <slide v-for="img in point.media" :key="img.id" class="min-h-[180px]">
                    <img :src="img.original_url" alt="" class="min-h-[180px]">

                </slide>
            </carousel>
        </div>
        <div class="card-body flex flex-col grow">
            <h3 class="card-title mb-4">{{ point.title }}</h3>
            <p class="card-text mb-6 whitespace-pre-wrap">{{ point.description || '' }}</p>
            <div class="flex flex-wrap gap-4 justify-start mt-3">

                <a v-if="point.online_cam_link"
                   :href="point.online_cam_link"
                   class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700" target="_blank">Онлайн камера</a>
                <a v-if="point.view_360_link"
                   :href="point.view_360_link"
                   class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700" target="_blank">3д-тур</a>
                <a v-if="point.direction_link"
                   :href="point.direction_link"
                   class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700" target="_blank">Прокласти маршрут</a>

            </div>
        </div>
    </div>

</template>

<script>
import 'vue3-carousel/dist/carousel.css'
import {Carousel, Slide} from 'vue3-carousel'

export default {
    data: () => ({}),
    name: "VCard",
    components: {
        Carousel,
        Slide,
    },
    props: {
        point: {}
    },
    mounted() {
        this.$nextTick(() => {
            this.$refs.slider.restartCarousel()
        })

    },
    methods: {}
}
</script>

<style scoped>

.card-item {
    border-radius: 8px;
    box-shadow: 3px 4px 20px rgba(0, 0, 0, 0.15);
    background: #FFFFFF;
    overflow: hidden;
}

.card-item-img {
    min-height: 185px;
    max-height: 185px;
    width: 100%;
    overflow: hidden;
    position: relative;
}

.card-item-img img {
    border-radius: 8px 8px 0 0;
    position: absolute;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    margin: auto;
}

.card-body {
    padding: 18px;
}

.card-title {
    font-size: 24px;
    line-height: 29px;
}

.card-text {
    max-height: 250px;
    /*flex-basis: 300px;*/
    overflow-y: auto;
    flex-grow: 1;
    margin: 0;
    font-size: 16px;
    line-height: 26px;
}

.card-item-buttons {
    margin: 20px 11px 0 0;
    display: flex;
    column-gap: 16px;
    flex-wrap: wrap;
    row-gap: 11px;
}

.card-btn {
    border: 2px solid black;
    border-radius: 4px;
    padding: 7px 12px;
    font-weight: 600;
}

</style>
