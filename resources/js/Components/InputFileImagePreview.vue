<template>
    <div class="flex flex-wrap gap-3">
        <label
            class="block w-[80px] h-[80px] hover:cursor-pointer border border-dashed border-gray-200 flex justify-center items-center"
            for="file">
            <input id="file" :multiple="multiple" accept="image/*" class="absolute w-0 h-0 " type="file"
                   @change="loadImg">
            <img alt="" src="@/../images/./upload_image.svg">
        </label>

        <template v-if="images">

            <template v-if="multiple">

                <div v-for="(image, index) in images" :key="index"
                     class="img-preview relative flex justify-center items-center overflow-hidden w-[80px] h-[80px] border border-dashed border-gray-200 p-1">
                    <img :src="getImgUrl(image)" alt="" class="max-w-fit max-h-fit">

                    <div
                        class="absolute hidden m-1 w-full h-full flex justify-center items-center bg-clip-padding border-solid border-[5px] hover:cursor-pointer border-transparent border-wid"
                        style="background-color: rgba(255,0,0,0.51);"
                        @click="removeImg(image, index)"
                    >
                        <img alt="" src="@/../images/Delete.svg">
                    </div>
                </div>
            </template>

            <div v-else
                 class="flex justify-center items-center overflow-hidden w-[80px] h-[80px] border border-dashed border-gray-200 p-1">
                <img :src="getImgUrl(images[0])" alt="" class="max-w-fit max-h-fit">
            </div>

        </template>
    </div>
</template>

<script>
export default {
    name: "InputFileImagePreview",
    props: {
        multiple: {
            type: Boolean,
            default: false
        },
        images: {
            default: null
        },
        editMode: {
            type: Boolean,
            default: false
        }
    },
    emits: ['update:images', 'remove:media'],
    methods: {
        getImgUrl(image) {
            if (!image) return ''
            try {
                return URL.createObjectURL(image)
            } catch (error) {
                return image.original_url
            }
        },
        loadImg(event) {
            const value = this.multiple ? [...this.images || [], ...event.target.files] : event.target.files
            this.$emit('update:images', value)
        },
        removeImg(img, index) {
            if (img.id)
                this.$emit('remove:media', img.id)
            else {
                const images = this.images.filter((img, i) => i !== index)
                this.$emit('update:images', images)
            }
        }
    }
}
</script>

<style scoped>
img {
    max-width: 100%;
}

.img-preview:hover > div {
    display: flex;
}
</style>
