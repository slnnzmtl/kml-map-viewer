<template>
    <Head title="Об'єкти у вигляді списку"/>

    <map-guest-layout>
        <template #header>
            <div class="flex w-full justify-between px-4 py-2 min-h-[42px] box-content">
                <div class="flex gap-4">
                    <button @click="$inertia.get('/')">На головну</button>
                    <button @click="$inertia.get(`/map/${activeForestry.id}`)">
                        Карта
                    </button>
                </div>
                <div class="min-w-[250px]">
                    <multiselect v-model="localActiveAffiliate" :can-clear="false" :close-on-deselect="false"
                                :close-on-select="false"
                                :hide-selected="false"
                                :options="notEmptyAffiliates" :show-options="true" class="affiliate" mode="single"
                                value-prop="id">


                        <template #multiplelabel="{values}">
                            <div class="p-2 mr-auto">
                                Вибрано - {{ values }}
                            </div>
                        </template>

                        <template #singlelabel>
                            <div class="mr-auto p-2">{{ activeAffiliate.title }} - {{ activeForestry.title }}</div>
                        </template>

                        <template #option="{option:affiliate, isSelected}">
                            <div class="relative w-full ">
                                <span>{{ affiliate.title }}</span>

                                <template v-if="isSelected(affiliate)">
                                    <div class="absolute left-full top-0 bg-white rounded overflow-hidden text-black"
                                        style="min-width: 200px">
                                        <ul>
                                            <li v-for="forestry in affiliate.forestry" :key="forestry.id"
                                                :class="{'bg-gray-200 pointer-events-none': localActiveForestry === forestry.id, 'pointer-events-none': forestry.id === activeForestry.id}"
                                                :value="forestry.id"
                                                class="p-3"
                                                @click="goToForestry(forestry.id)">
                                                {{ forestry.title }}
                                            </li>
                                        </ul>
                                    </div>
                                </template>
                            </div>
                        </template>
                    </multiselect>
                </div>
            </div>
        </template>
        <main class=" overflow-y-auto w-full">
            <div class="container pt-[80px] mx-auto">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-8 px-6 mb-6">
                    <v-card v-for="point in points" :key="point.id" :point="point"/>
                </div>
            </div>
        </main>
    </map-guest-layout>

</template>

<script>
import {Head} from '@inertiajs/vue3';

import MapGuestLayout from "@/Layouts/MapGuestLayout.vue";
import VList from "@/Components/VList.vue";
import Dropdown from "@/Components/Dropdown.vue";
import VCard from "@/Components/VCard.vue";

export default {
    name: "Home",
    components: {
        VCard,
        Dropdown,
        VList,
        MapGuestLayout,
        Head,
    },
    data: () => ({
        localActiveAffiliate: null,
        localActiveForestry: null,
    }),
    props: {
        activeForestry: {
            type: Object
        },
        activeAffiliate: {},
        affiliates: {
            type: Array
        },
        categories: {
            type: Array
        },
        points: {
            type: Array
        }
    },
    computed: {
        notEmptyAffiliates() {
            return this.affiliates.filter(a => a.forestry.filter(f => f.points_count).length)
                .map(a => ({
                    ...a,
                    forestry: a.forestry.filter(f => f.points_count)
                }))
        }
    }
}
</script>

<style></style>
