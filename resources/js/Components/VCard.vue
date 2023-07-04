<template>
    <div class="card-item">
        <div class="card-item-img">
            <carousel :itemsToScroll="point.media.length > 1 ? 1 : 0" ref="slider" :autoplay="2000" :mouse-drag="true" :touch-drag="true" :wrap-around="true"
                    @init="$refs.slider.restartCarousel()">
                <slide v-for="img in point.media" :key="img.id" class="min-h-[180px]">
                    <img :src="img.original_url" alt="" class="min-h-[180px]">
                </slide>
            </carousel>
        </div>
        <div class="card-body">
            <h3 class="card-title mb-4">{{ point.title }}</h3>
            <p class="card-text mb-6" :class="{ expanded }">
                <span>{{ description }}</span>
                <br>
                <button v-if="!expanded" class="font-semibold" @click="expanded = true">Розгорнути повністю</button>
            </p>
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
        point: {},
    },
    data: () => ({
        expanded: false,
        description: '',
        descriptionPreviewLength: 300,
    }),
    mounted() {
        this.$nextTick(() => {
            this.$refs.slider.restartCarousel()
        })
    },
    computed: {
        description() {
            const { description } = this.point

            if (this.expanded || description < this.descriptionPreviewLength) return description

            return description.slice(0, this.descriptionPreviewLength) + '...'
        }
    }
}
</script>

<style scoped>

.card-item {
    border-radius: 8px;
    box-shadow: 3px 4px 20px rgba(0, 0, 0, 0.15);
    background: #FFFFFF;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    height: min-content;
}

.card-item-img {
    width: 100%;
    overflow: hidden;
    position: relative;
    max-height: 350px;
}

.card-item-img img {
    min-width: 100%;
    border-radius: 8px 8px 0 0;
    margin: auto;
}

.card-title {
    font-size: 20px;
    font-weight: bolder;
    line-height: 1;
	margin-bottom: 8px;
    margin-top: 16px;
    flex-grow: 1;
    padding: 0 12px;
}

.card-text {
    overflow-y: auto;
    flex-grow: 1;
    margin: 0;
    font-size: 16px;
    line-height: 26px;
    padding: 0 12px;
    transition: all 0.5s ease-in-out;
    max-height: 260px;
}

.card-text.expanded {
    max-height: 1000px;
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
