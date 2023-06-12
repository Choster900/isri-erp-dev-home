<template>
    <li class="mb-1 last:mb-0 hover:scale-110 transform transition-all duration-500">
        <DropdownLink :href="route(infoModule.nombre_ruta)" method="get" as="button" :title="infoModule.nombre_submenu"
            class="block text-slate-400 hover:text-slate-200 transition duration-150 truncate">
            <span class="lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200 no-select"
                :class="match ? 'font-normal text-slate-100' : 'text-sm '">
                {{ infoModule.nombre_submenu }}
            </span>
        </DropdownLink>
    </li>
</template>

<script>
import DropdownLink from '@/Components/DropdownLink.vue';

export default {
    props: ["infoModule"],
    components: { DropdownLink },
    data() {
        return {
            match: false,
        };
    },
    methods: {
        getAbsolutePath() {
            var loc = window.location;
            var pathName = loc.pathname.substring(0, loc.pathname.lastIndexOf('/') + 1);
            return loc.href.substring(0, loc.href.length - ((loc.pathname + loc.search + loc.hash).length - pathName.length));
        }

    },

    created() {
        window.location.pathname == this.infoModule.url ? this.match = true : null;
        window.location.pathname == this.infoModule.url ? this.$emit("coindiciendoRuta") : null;
    }
}
</script>

<style></style>