<template>
    <multiselect 
        v-model="localActiveAffiliate" :can-clear="false" :close-on-deselect="false" 
        :canDeselect="false"
        :close-on-select="false"
        :hide-selected="false"
        :options="notEmptyAffiliates" :show-options="true" class="affiliate relative" mode="single"
        value-prop="id"
        @click="calculateDropdownOffset"
    >

        <template #multiplelabel="{values}">
            <div class="p-2 mr-auto">
                Вибрано - {{ values }}
            </div>
        </template>

        <template #singlelabel>
            <div class="mr-auto p-2">{{ activeAffiliate.title }} - {{ activeForestry.title }}</div>
        </template>

        <template #option="{option:affiliate, isSelected}">
            <div class="w-full overflow-visible max-h-min">
                <span>{{ affiliate.title }}</span>

                <template v-if="isSelected(affiliate)">
                    <div ref="container" class="absolute left-full top-6 bg-white rounded overflow-auto text-black"
                        style="min-width: 200px"
                    >
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
</template>

<script>
import Multiselect from '@vueform/multiselect'

export default {
    name: 'AffiliateForestrySelect',
    components: {
        Multiselect,
    },
    props: {
        activeAffiliate: null,
        activeForestry: null,
        affiliates: {},
    },
    data: function () {
        return {
            localActiveAffiliate: null,
            localActiveForestry: null,
        }
    },
    beforeMount() {
        this.localActiveAffiliate = this.activeAffiliate.id
        this.localActiveForestry = this.activeForestry.id
    },
    methods: {
        goToForestry(id) {
            if (id !== this.activeForestry.id)
                this.$inertia.get(`/map/${id}`)
        },
        showDropdown(isSelected) {
            return isSelected
        }
    },
    computed: {
        notEmptyAffiliates() {
            const result = this.affiliates.filter(a => a.forestry.filter(f => f.points_count).length)
                .map(a => ({
                    ...a,
                    forestry: a.forestry.filter(f => f.points_count)
                }))

                return result
        },
    }
}
</script>


<style>
.affiliate .multiselect-dropdown {
    overflow: initial;
    max-height: initial;
}

.multiselect-wrapper {
    min-width: 250px !important;
}

.align-top {
    top: auto;
    bottom: 100%;
}

.align-bottom {
    top: 100%;
    bottom: auto;
}

</style>
