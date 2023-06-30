<template>
    <div class="w-2/3 m-auto flex justify-center">
        <MapSVG />
    </div>
    <div v-if="showPanel"
         :style="panelPositionStyle"
         class="fixed w-auto max-w-[300px] bg-gray-100 shadow-md rounded" tabindex="0">
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
import MapSVG from './MapSVG.vue';

export default {
    name: "Map",
    components: {
        MapSVG,
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
    transition: fill 0.1s ease-in-out;
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
