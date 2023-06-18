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
                        <form @submit.prevent="submit">
                            <div class="flex flex-col gap-y-4">
                                <form-group :error="errors.title" label="Назва категорії">
                                    <input
                                        id="name"
                                        v-model="form.title"
                                        class=" appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none bg-white"
                                        placeholder="Назва" type="text">
                                </form-group>
                                <form-group :error="errors.image" label="Картинка">
                                    <input-file-image-preview v-model:images="form.image" :edit-mode="isEditing"/>
                                </form-group>
                                <div :class="[!isEditing ? 'justify-end': 'justify-between']" class="flex ">
                                    <button :disabled="form.processing"
                                            class=" text-sky-500 hover:underline focus:shadow-outline focus:outline-none font-bold py-2 px-4 rounded"
                                            type="button" @click="resetForm">
                                        Скасувати
                                    </button>
                                    <button :disabled="form.processing"
                                            class="shadow bg-sky-500 hover:bg-blue-300 focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded"
                                            type="submit">
                                        {{ !isEditing ? 'Додати нову категорію' : 'Оновити категорію' }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="bg-white shadow-sm rounded-lg p-1 col-span-2 sm:col-span-1 sm:order-first">
                        <table class="table-auto w-full">
                            <template v-if="categories.length">
                                <tr v-for="category in categories" :key="category.id"
                                    :title="`Редагувати '${category.title}\'`"
                                    class="hover:bg-gray-100 hover:cursor-pointer"
                                    @click="edit(category)">
                                    <td class="px-1 py-3 flex justify-center items-center">
                                        <template v-if="category.media.length">
                                            <img :src="`${category.media[0].original_url}`" alt="" class="w-[40px]">
                                        </template>
                                        <template v-else>
                                            <img alt="" src="@/../images/upload_image.svg">
                                        </template>
                                    </td>
                                    <td class="text-left px-1 py-3">
                                        <span>{{ category.title }}</span>
                                    </td>
                                    <td class="px-1 py-3">{{ category.points_count }} об'єктів</td>
                                    <td class="p-3 text-right">
                                        <button class="text-red-600 hover:underline" @click.stop="removeCategory(category.id)">Видалити
                                        </button>
                                    </td>
                                </tr>
                            </template>
                            <tr v-else>
                                <td class="p-3 text-center" colspan="4">Категорії відсутні</td>
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

const categoryTemplate = {
    id: null,
    title: '',
    image: null
}

export default {
    data: () => ({
        form: useForm(categoryTemplate),
        image: null,
        isEditing: false
    }),
    components: {InputFileImagePreview, FormGroup, Head, AuthenticatedLayout},
    props: {
        categories: {
            type: Array
        },
        errors: {}
    },
    methods: {
        resetForm() {
            this.form.clearErrors()
            this.form = useForm(categoryTemplate)
            this.isEditing = false
        },
        removeCategory(id) {
            const form = useForm({})
            form.delete('/dashboard/categories/' + id)
        },
        save() {
            this.form
                .transform((data) => ({
                    ...data,
                    image: data.image
                }))
                .post('/dashboard/categories/add')
            this.form.reset()
            this.form = useForm(categoryTemplate)
        },
        edit(category) {
            this.isEditing = true

            this.form = useForm({
                ...category,
                image: category.media
            })
        },
        updateCategory() {
            this.form.post('/dashboard/categories/update/' + this.form.id)
            this.form.reset()
            this.form = useForm(categoryTemplate)
            this.isEditing = false
        },
        submit() {
            if (!this.isEditing)
                this.save()
            else
                this.updateCategory()
        }
    }
}
</script>
