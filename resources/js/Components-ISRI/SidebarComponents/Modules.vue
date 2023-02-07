<template>

    <li class="px-3 py-2 rounded-sm mb-0.5 last:mb-0 cursor-pointer"
        :class="matchPath ? 'bg-[#02143c]' : ''">
        <a @click="StateFromModal ? stateToMenu = !stateToMenu : this.$emit('emitToShowSidebarFromModules')"
            class="block text-slate-200 hover:text-white truncate transition duration-150">
            <div class="flex items-center justify-between">
                <div class="flex items-center">

                    <svg v-if="stateToMenu" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" class="text-white"
                            d="M2 20V4C2 3.44772 2.44772 3 3 3H8.44792C8.79153 3 9.11108 3.17641 9.29416 3.46719L10.5947 5.53281C10.7778 5.82359 11.0974 6 11.441 6H21C21.5523 6 22 6.44772 22 7V20C22 20.5523 21.5523 21 21 21H3C2.44772 21 2 20.5523 2 20Z" />
                    </svg>
                    <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3.75 9.776c.112-.017.227-.026.344-.026h15.812c.117 0 .232.009.344.026m-16.5 0a2.25 2.25 0 00-1.883 2.542l.857 6a2.25 2.25 0 002.227 1.932H19.05a2.25 2.25 0 002.227-1.932l.857-6a2.25 2.25 0 00-1.883-2.542m-16.5 0V6A2.25 2.25 0 016 3.75h3.879a1.5 1.5 0 011.06.44l2.122 2.12a1.5 1.5 0 001.06.44H18A2.25 2.25 0 0120.25 9v.776" />
                    </svg>


                    <span class="text-sm font-medium ml-3 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">
                        {{ modulo.menu }}
                    </span>
                </div>
                <div class="flex shrink-0 ml-2">
                    <svg class="w-3 h-3 shrink-0 ml-1 fill-current text-slate-400 rotate-180" viewBox="0 0 12 12">
                        <path d="M5.9 11.4L.5 6l1.4-1.4 4 4 4-4L11.3 6z"></path>
                    </svg>
                </div>
            </div>
        </a>
        <div class="lg:sidebar-expanded:block 2xl:block">
            <ul class="pl-9 mt-1" :class="stateToMenu ? 'hidden' : ''">
                <LinksSidebar v-for="(m, i) in modulo.submenu" :key="i" :infoModule="m"
                    @coindiciendoRuta="currenPath" />
            </ul>
        </div>
    </li>
</template>

<script>
import LinksSidebar from './LinksSidebar.vue'
export default {
    components: { LinksSidebar },
    props: ["StateFromModal", "modulo"],
    data: function () {
        return {
            stateToMenu: false,
            matchPath: false,
        }
    },
    methods: {
        currenPath() {
            this.matchPath = true
        }
    },
}
</script>

<style>

</style>