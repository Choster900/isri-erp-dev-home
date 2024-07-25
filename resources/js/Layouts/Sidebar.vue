<script setup>
import { ref, watch, toRefs } from 'vue';
import { Head } from '@inertiajs/vue3';
import MenuSidebarVue from '@/Components-ISRI/SidebarComponents/MenuSidebar.vue';
import DropdownLink from '@/Components/DropdownLink.vue';

// Propiedades recibidas
const props = defineProps({
    isSidebarVisible: {
        type: Boolean,
        required: true,
    },
    sidebarColor: {
        type: String,
        default: '',
    },
});
// Convertir las propiedades a referencias reactivas
const { isSidebarVisible } = toRefs(props)

// @Reactivity: Estado del sidebar
const sidebarState = ref(true); // Sidebar abierto por defecto

// @Emit: Definir los eventos que el componente puede emitir
const emit = defineEmits(['updateSidebarState']);

// @Methods: Métodos para manejar el estado del sidebar
const toggleSidebar = () => {
    sidebarState.value = !sidebarState.value; // Alternar el estado del sidebar

};
const closeSidebar = () => {
    sidebarState.value = false; // Cerramos el modal
    emit('updateSidebarState', sidebarState.value); // Emitir el evento para cerrar el sidebar

};

// @Watch: Observar los cambios en la propiedad `isSidebarVisible`
watch(() => isSidebarVisible.value, (newVal) => {
    sidebarState.value = newVal; // Actualizar el estado del sidebar con el nuevo valor de la propiedad
    // Nota: Esta función se ejecutará cada vez que `isSidebarVisible` cambie
});
</script>
<template>
    <div id="sidebar" :class="{
        'lg:overflow-y-auto': sidebarState,
        [sidebarColor !== '' ? sidebarColor : 'bg-[#001c48]']: true,
        '-translate-x-0': sidebarState,
        '-translate-x-64': !sidebarState
    }"
        class="sidebar-style-isri flex flex-col absolute z-40 left-0 top-0 lg:static lg:left-auto lg:top-auto lg:translate-x-0 h-screen overflow-y-scroll shadow-2xl no-scrollbar w-64 lg:w-20 lg:sidebar-expanded:!w-64 2xl:!w-64 shrink-0 p-4 transition-all duration-200 ease-in-out">
        <!-- Sidebar header -->
        <div class="flex justify-center mb-10 pr-3 sm:px-2">
            <!-- Close button -->
            <button @click="closeSidebar()" class="lg:hidden text-slate-500 hover:text-slate-400"
                aria-controls="sidebar" aria-expanded="true">
                <span class="sr-only">Close sidebar</span>
                <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M10.7 18.7l1.4-1.4L7.8 13H20v-2H7.8l4.3-4.3-1.4-1.4L4 12z"></path>
                </svg>
            </button>
            <!-- Logo -->
            <DropdownLink :href="route('dashboard')" method="get" as="button" aria-current="page"
                class="router-link-active router-link-exact-active block">
                <img style="width:200px" src="../../img/logoSidebar.png" alt="GOBIERNO DE EL SALVADOR">
            </DropdownLink>
        </div>
        <MenuSidebarVue :color="sidebarColor" :stateFromSidebarProp="sidebarState"
            @emitToShowModalFromMenu="toggleSidebar" />
        <!-- Expand / collapse button -->
        <div class="pt-3 hidden lg:inline-flex 2xl:hidden justify-end mt-auto">
            <div class="px-3 py-2">
                <button @click="toggleSidebar">
                    <span class="sr-only">Expand / collapse sidebar</span>
                    <svg class="w-6 h-6 fill-current" :class="sidebarState ? 'rotate-180' : 'rotate-360'"
                        viewBox="0 0 24 24">
                        <path class="text-slate-400"
                            d="M19.586 11l-5-5L16 4.586 23.414 12 16 19.414 14.586 18l5-5H7v-2z">
                        </path>
                        <path class="text-slate-600" d="M3 23H1V1h2z"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</template>
<style>
@media (min-width: 1024px) {
    .lg\:overflow-y-auto {
        width: 16rem !important;
    }
}

.sidebar-style-isri {
    overflow: auto;
    scrollbar-width: none;
}

.sidebar-style-isri::-webkit-scrollbar {
    width: 0;
}
</style>
