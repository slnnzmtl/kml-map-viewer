<template>
    <Head title="Points"/>

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-900 leading-tight">Об'єкти</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

                <div class="grid gap-4 items-start grid-cols-2">

                    <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6 col-span-2 sm:col-span-1">
                        <form @submit.prevent="submit">
                            <div class="flex gap-y-4 flex-col ">

                                <form-group :error="errors.title" label="Назва">
                                    <input
                                        id="name"
                                        v-model="form.title"
                                        class=" appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                        placeholder="Назва" type="text">
                                </form-group>
                                <form-group :error="errors.description" label="Опис">
                                    <textarea
                                        id="description" v-model="form.description"
                                        class="block appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                        placeholder="Опис"
                                        type="text"
                                        іьфдд @keydown.ctrl.enter="submit"></textarea>
                                </form-group>
                                <form-group :error="errors.category_id" label="Категорія">
                                    <select id="grid-state"
                                            v-model="form.category_id"
                                            class="block appearance-none w-full border-2 border-gray-200 text-gray-700 py-2 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500">
                                        <option v-if="!form.category_id" :value="null" selected>Категорія</option>
                                        <template v-else>
                                            <option v-for="category in categories" :key="category.id"
                                                    :value="category.id">
                                                {{ category.title }}
                                            </option>
                                        </template>
                                    </select>

                                </form-group>

                                <form-group label="філія">
                                    <select
                                        v-model="activeAffiliateId"
                                        class="block appearance-none w-full border-2 border-gray-200 text-gray-700 py-2 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500">
                                        <option v-for="affiliate in notEmptyAffiliates" :key="affiliate.id"
                                                :selected="affiliate.id === activeAffiliateId"
                                                :value="affiliate.id">
                                            {{ affiliate.title }}
                                        </option>
                                    </select>
                                </form-group>
                                <form-group label="Лісництво">
                                    <select
                                        v-model="form.forestry_id"
                                        class="block appearance-none w-full border-2 border-gray-200 text-gray-700 py-2 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500">
                                        <option v-for="forestry in activeAffiliate?.forestry || []"
                                                :key="forestry.id"
                                                :value="forestry.id">
                                            {{ forestry.title }}
                                        </option>
                                    </select>
                                </form-group>

                                <form-group label="Координати">
                                    <input
                                        id="tour"
                                        v-model="textCenter"
                                        class="appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                        placeholder="Координати" type="text">
                                </form-group>
                                <form-group>
                                    <GoogleMap ref="map"
                                               :center="form.geo" api-key="AIzaSyC0_AC0vJLbsCJ8XVkVc_a4JdDSwC0gQkc"
                                               v-bind="mapOptions"
                                               @click="changeGeo"
                                    >
                                        <Marker :options="{position: form.geo}"/>
                                    </GoogleMap>
                                </form-group>

                                <form-group>
                                    <template #label>
                                        360-тур <span class="text-gray-200">(не обов’язково)</span>
                                    </template>
                                    <input
                                        id="view_360_link"
                                        v-model="form.view_360_link"
                                        class="appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                        name="view_360_link"
                                        placeholder="Посилання"
                                        type="url">
                                </form-group>

                                <form-group>
                                    <template #label>
                                        Онлайн-камера <span class="text-gray-200">(не обов’язково)</span>
                                    </template>
                                    <input
                                        id="online_cam_link"
                                        v-model="form.online_cam_link"
                                        class="appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                        name="online_cam_link"
                                        placeholder="Посилання"
                                        type="url">
                                </form-group>
                                <form-group>
                                    <template #label>
                                        Прокласти маршрут <span class="text-gray-200">(не обов’язково)</span>
                                    </template>
                                    <input
                                        id="direction_link"
                                        v-model="form.direction_link"
                                        class="appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
                                        name="direction_link"
                                        placeholder="Посилання"
                                        type="url">
                                </form-group>
                                <form-group label="Картинки">
                                    <input-file-image-preview v-model:images="form.images" :edit-mode="isEditing"
                                                              :multiple="true"
                                                              @remove:media="removePointMedia(form.id, $event)"/>
                                </form-group>

                                <div class="flex justify-between">
                                    <button :disabled="form.processing"
                                            class="  focus:shadow-outline focus:outline-none text-[#3B82F6] font-bold py-2"
                                            type="button" @click="resetForm">
                                        Скасувати
                                    </button>
                                    <button :disabled="form.processing"
                                            class="shadow bg-[#3B82F6] hover:bg-blue-300 focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded"
                                            type="submit">
                                        {{ !isEditing ? 'Додати новий об`єкт' : 'Оновити об`єкт' }}
                                    </button>
                                </div>
                                <!--                                </form-group>-->
                            </div>
                        </form>
                    </div>

                    <div class="bg-white shadow-sm rounded-lg p-1 col-span-2 sm:col-span-1 sm:order-first">

                        <ul class="p-0 m-0">

                            <template v-if="points.length">
                                <li v-for="point in points" :key="point.id"
                                    :title="`лісництво: '${point.forestry.title}', id: '${point.forestry.id}' `"
                                    class="flex justify-between hover:bg-gray-100 p-3 hover:cursor-pointer"
                                    @click="edit(point)">
                                    <div class="basis-[40%]">
                                        <span>{{ point.title }}</span>
                                    </div>
                                    <div class="flex items-center">
                                        <img alt="" class="mr-3"
                                             src="@/../images/./upload_image.svg">
                                        <span>{{ point.images_count }}</span>
                                    </div>
                                    <button class="text-red-600" @click.stop="removePoint(point.id)">Видалити</button>
                                </li>
                            </template>
                            <li v-else class="flex justify-between hover:bg-gray-100 p-3 hover:cursor-pointer">
                                Об'єкти на знайдено
                            </li>
                        </ul>
                    </div>

                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, useForm} from '@inertiajs/vue3';
import FormGroup from "@/Components/FormGroup.vue";
import InputFileImagePreview from "@/Components/InputFileImagePreview.vue";
import {CustomMarker, GoogleMap, Marker} from 'vue3-google-map'

const pointTemplate = {
    title: '',
    description: '',
    category_id: null,
    forestry_id: null,
    geo: {lat: 51.09896683851218, lng: 25.667232758549517},
    images: [],
    view_360_link: '',
    online_cam_link: '',
    direction_link: '',
}

export default {
    components: {InputFileImagePreview, Marker, FormGroup, GoogleMap, CustomMarker, Head, AuthenticatedLayout},
    data: () => ({
        mapOptions: {
            zoom: 11,
            streetViewControl: false,
            draggable: false,
            mapTypeControl: false,
            fullscreenControl: false,
        },
        form: useForm(pointTemplate),
        activeAffiliateId: null,
        isEditing: false,
        pointTemplate
    }),
    props: {
        errors: {},
        points: {
            type: Array
        },
        categories: {},
        affiliates: {}
    },
    computed: {
        notEmptyAffiliates() {
            return this.affiliates.filter(a => a.forestry.length)
        },
        textCenter: {
            get() {
                return `${this.form.geo.lat}, ${this.form.geo.lng}`
            },
            set(value) {
                this.form.geo = {
                    lat: parseFloat(value.split(',')[0].trim()),
                    lng: parseFloat(value.split(',')[1].trim()),
                }
            }
        },
        activeAffiliate() {
            return this.affiliates.find(a => a.id === this.activeAffiliateId)
        }
    },
    mounted() {
        this.form.category_id = this.categories.length ? this.categories[0]?.id : null
        this.activeAffiliateId = this.affiliates[0].id
    },
    watch: {
        activeAffiliate(category) {
            this.form.forestry_id = this.activeAffiliate.forestry[0].id
        }
    },
    methods: {
        resetForm() {
            this.form.clearErrors()
            this.form = useForm(pointTemplate)
            this.form.category_id = this.categories.length ? this.categories[0]?.id : null
            this.activeAffiliateId = this.affiliates[0].id
            this.isEditing = false
        },
        edit(point) {
            this.isEditing = true

            this.activeAffiliateId = point.forestry.affiliate_id

            this.form.reset(pointTemplate)

            const {lat, lng} = JSON.parse(point.geo)

            this.form = useForm({
                ...point,
                geo: {lat: +lat, lng: +lng},
                category_id: point.category.id,
                forestry_id: point.forestry.id,
                images: point.media
            })
        },
        submit() {
            if (!this.isEditing)
                this.add()
            else
                this.updatePoint()
        },
        updatePoint() {
            this.form
                .post(`/dashboard/points/update/${this.form.id}`)

            this.resetForm()
        },
        add() {
            this.form.transform((data) => {
                return {
                    ...data,
                    geo: JSON.stringify(data.geo || this.mapOptions.center)
                }
            })
                .post('/dashboard/points/add')

            this.resetForm()
        },
        removePoint(id) {
            const form = useForm({})
            form.delete('/dashboard/points/destroy/' + id)

            this.resetForm()
        },
        removePointMedia(pointId, imgId) {
            const form = useForm({})
            form.delete(`/dashboard/points/destroy/media/${pointId}/${imgId}`)
        },
        changeGeo(e) {
            this.form.geo = e.latLng.toJSON()
        },
    }
}
</script>

<style>
.mapdiv {
    min-height: 400px;
}

.map {
    position: relative;
}


.map::before, .map::after {

    content: '';
    position: absolute;

    background-color: rgba(134, 34, 34, 0.44);
    z-index: 2;
}


.map::before {
    width: 100%;
    height: 1px;
    top: 50%;
    left: 0;

}

.map::after {
    width: 1px;
    height: 100%;
    top: 0;
    left: 50%;
}
</style>
