Categories.vue
<template>
    <Head title="Categories"/>

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-900 leading-tight">Категорії</h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid gap-4 items-start grid-cols-2">
                    <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6 col-span-2 sm:col-span-1">
                        <!--                        <form @submit.prevent="submit">-->
                        <!--                            <div class="flex flex-col gap-y-4">-->


                        <!--                                <form-group :error="errors.title" label="Назва категорії">-->
                        <!--                                    <input-->
                        <!--                                        id="name"-->
                        <!--                                        v-model="form.title"-->
                        <!--                                        class=" appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none bg-white"-->
                        <!--                                        placeholder="Назва" type="text">-->
                        <!--                                </form-group>-->

                        <!--                                <form-group :error="errors.image" label="Картинка">-->
                        <!--                                    <input-file-image-preview v-model:images="form.image" :edit-mode="isEditing"/>-->
                        <!--                                </form-group>-->

                        <!--                                <div :class="[!isEditing ? 'justify-end': 'justify-between']" class="flex ">-->
                        <!--                                    <button :disabled="form.processing"-->
                        <!--                                            class=" text-sky-500 hover:underline focus:shadow-outline focus:outline-none font-bold py-2 px-4 rounded"-->
                        <!--                                            type="button" @click="resetForm">-->
                        <!--                                        Скасувати-->
                        <!--                                    </button>-->
                        <!--                                    <button :disabled="form.processing"-->
                        <!--                                            class="shadow bg-sky-500 hover:bg-blue-300 focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded"-->
                        <!--                                            type="submit">-->
                        <!--                                        {{ !isEditing ? 'Додати нову категорію' : 'Оновити категорію' }}-->
                        <!--                                    </button>-->
                        <!--                                </div>-->
                        <!--                            </div>-->
                        <!--                        </form>-->
                    </div>
                    <div class="bg-white shadow-sm rounded-lg p-1 col-span-2 sm:col-span-1 sm:order-first">
                        <table class="table-auto w-full">
                            <template v-if="affiliates.length">
                                <tr class="border-b">
                                    <th class="text-left px-3">Назва</th>
                                    <th class="text-right">кіль-сть Лісництв</th>
                                </tr>
                                <template v-for="affiliate in sortedByForestry  " :key="affiliate.id">
                                    <tr :class="{'bg-gray-100': affiliate.id === activeAffiliateId, 'pointer-events-none text-gray-200': !affiliate.forestry.length}"
                                        class="hover:bg-gray-100 hover:cursor-pointer"
                                        @click="activeAffiliateId = activeAffiliateId === affiliate.id? null : affiliate.id">
                                        <td class="text-left px-3 py-3">
                                            <span>{{ affiliate.title }}</span> <span><b>філія</b></span>
                                        </td>
                                        <td class="text-right">
                                            {{ affiliate.forestry.length }}
                                        </td>
                                    </tr>

                                    <template v-if="affiliate.id === activeAffiliateId">
                                        <tr class="bg-gray-50">
                                            <th class="text-[12px]" colspan="2">Лісництва</th>
                                        </tr>
                                        <tr v-for="forestry in affiliate.forestry" :key="forestry.id"
                                            :class="{'bg-gray-100': affiliate.id === activeAffiliateId}"
                                            class="hover:bg-gray-50 hover:cursor-pointer">
                                            <td class="px-6 py-1" colspan="2">
                                                {{ forestry.title }}
                                            </td>
                                        </tr>
                                    </template>
                                </template>
                            </template>
                            <tr v-else>
                                <td class="p-3 text-center">Філії відсутні</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head} from '@inertiajs/vue3';
import {useForm} from '@inertiajs/vue3'
import FormGroup from "@/Components/FormGroup.vue";
import InputFileImagePreview from "@/Components/InputFileImagePreview.vue";

export default {
    data: () => ({
        form: useForm({}),
        activeAffiliateId: null
    }),
    components: {
        Head, AuthenticatedLayout
    },
    props: {
        affiliates: {
            type: Array
        },
        errors: {}
    },
    computed: {
        sortedAffiliates() {
            return [...this.affiliates].sort((a1, a2) => a1.title > a2.title ? 1 : -1)
        },
        sortedByForestry() {
            return [...this.sortedAffiliates].sort((a1, a2) => a1.forestry.length > a2.forestry.length ? -1 : 1)
        }
    },
    methods: {
        // submit() {
        //     if (!this.isEditing)
        //         this.save()
        //     else
        //         this.updateCategory()
        // }
    }
}
</script>
