<template>
    <div class="w-2/3 m-auto flex justify-center">
        <MapSvg />
    </div>
    <div v-if="showPanel"
         :style="panelPositionStyle"
         class="fixed w-auto max-w-[300px] bg-gray-100 shadow-md rounded pt-8" tabindex="0">
        <button
            class="bg-white top-[5px] right-[5px] absolute rounded-md inline-flex items-center justify-center text-gray-400 hover:text-gray-500 hover:bg-white focus:outline-none focus:ring-2 focus:ring-inset focus:ring-gray-300"
            type="button"
            @click="showPanel = false">
            <span class="sr-only">Close menu</span>
            <svg aria-hidden="true" class="h-6 w-6" fill="none" stroke="currentColor"
                 viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="M6 18L18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
            </svg>
        </button>
        <ul class="list-outside hover:list-inside">
            <template v-if="forestry.length">
                <template v-if="filteredForestry.length">
                    <li v-for="f in filteredForestry" :key="f.id" class="hover:cursor-pointer menu-item"
                        @click="goToMapPage(f.id)">
                        {{ f.title }}
                    </li>
                </template>
                <li class="menu-item" v-else>Лісництв з об'єктами не знайдено</li>
            </template>
            <li class="menu-item" v-else>Лісництв не знайдено</li>
        </ul>
    </div>
</template>

<script>
import MapSvg from './MapSvg.vue';

export default {
    name: "Map",
    components: {
        MapSvg,
    },
    data: () => ({
        panelCoordinate: {},
        activeAffiliates: null,
        showPanel: false,
        test: false,
    }),
    props: {
        affiliates: {}
    },
    computed: {
        panelPositionStyle() {
            return {
                top: `${this.panelCoordinate.top}px`,
                left: `${this.panelCoordinate.left}px`,
            }
        },
        forestry() {
            return this.activeAffiliates?.forestry.sort((f1, f2) => f1.title > f2.title ? 1 : -1) || []
        },
        filteredForestry() {
            return this.forestry.filter(f => f.points.length) || []
        }
    },
    mounted() {
        this.showForestryPopup()

        this.affiliates.forEach(affiliate => {
            const hasPoints = !!(affiliate.forestry.filter(f => f.points.length).length)
            if (!hasPoints)
                document.querySelector(`g#${affiliate.code}`).classList.add('disabled')
        })

        const paths = document.querySelectorAll('g[id]')
    },
    methods: {
        showForestryPopup() {
            const paths = document.querySelectorAll('g[id]')
            paths.forEach((path) => {
                path.addEventListener('click', (event) => {

                    if (this.activeAffiliates) {
                        document.querySelector(`g#${this.activeAffiliates.code}`).classList.remove('active')
                    }
                    path.classList.add('active')

                    this.showPanel = false
                    const id = event.currentTarget.id
                    const rec = event.currentTarget.getClientRects()[0]
                    this.panelCoordinate = {
                        top: rec.top + (rec.height / 2) + 20,
                        left: rec.left + (rec.width / 4)
                    }

                    
                    this.activeAffiliates = this.affiliates.find((af => af.code === id))
                    console.log(this.activeAffiliates)

                    this.showPanel = true
                })
            })

            const map = document.querySelector('svg#map');
            document.body.addEventListener('click', (event) => {
                const { target } = event;
                
                if (!map.contains(target)) {
                    this.showPanel = false
                }
            })
        },
        goToMapPage(id) {
            this.$inertia.get(`/map/${id}`)
        }
    }
}
</script>

<style>

path {
    stroke-width: 1.87px;
    fill: #0b6822;
    fill-rule: nonzero;
    position: relative;
}

g.active > path {
    fill: rgba(11, 104, 34, 0.7);
}

g:hover > path {
    fill: rgba(11, 104, 34, 0.5);
    cursor: pointer;
}

g.disabled > path {
    fill: rgba(11, 104, 34, 0.3);
}

path.disabled:hover {
    cursor: default;
    pointer-events: none;
}

.menu-item {
    padding: 6px 16px 6px 16px;
}

.menu-item:hover {
    background-color: #f3f3f3;
}


</style>
