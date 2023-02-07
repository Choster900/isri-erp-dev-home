<template>
    <li class="mb-1 last:mb-0 hover:scale-110 transform transition-all duration-500">
        <DropdownLink :href="route(infoModule.nombre_ruta)" method="get"
            as="a" class="block text-slate-400 hover:text-slate-200 transition duration-150 truncate">
            <span class="lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200"
                :class="match ? 'font-bold text-slate-100' : 'font-medium text-sm '">
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
    return: function () {
        return { match: false }
    },
    created() {
        var URLactual = window.location.href.split("/");
        URLactual[6] == this.infoModule.id_menu ? this.match = true : null;
        URLactual[6] == this.infoModule.id_menu ? this.$emit("coindiciendoRuta") : null;
    }
}
</script>

<style>

</style>