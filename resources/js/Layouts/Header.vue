<script setup>
import { ref } from 'vue';
import { usePage } from '@inertiajs/vue3';
import MenuSidebarVue from '@/Components-ISRI/SidebarComponents/MenuSidebar.vue';
import DropdownLink from "@/Components/DropdownLink.vue";
import SearchModal from '@/Components-ISRI/HeaderComponents/ModalSearch.vue';
import UserMenuVue from '@/Components-ISRI/HeaderComponents/UserMenu.vue';
import Notifications from '@/Components-ISRI/HeaderComponents/Notifications.vue';
import Help from '@/Components-ISRI/HeaderComponents/Help.vue';

// @State: Estado local para controlar la visibilidad del modal de búsqueda
const isSearchModalOpen = ref(false);

// @Props: Definición de las propiedades recibidas del componente padre
const props = defineProps({
    moduleName: {
        type: String,
        default: '', // Valor predeterminado si no se proporciona
        required: true, // Propiedad obligatoria para el nombre del submódulo
    },
    isBackgroundVisible: {
        type: Boolean,
        default: usePage().props && usePage().props.menu ? true : false, // Valor predeterminado si no se proporciona
        required: true, // Propiedad obligatoria para la visibilidad del fondo
    },
});
</script>
<template>
    <header class="sticky top-0 bg-white border-b border-slate-200 z-30 " id="header-content"
        :class="{ 'modal-open': isBackgroundVisible }">
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 -mb-px">
                <!-- Header: Left side -->
                <div class="flex">
                    <!-- Hamburger button -->
                    <button class="text-slate-500 hover:text-slate-600 lg:hidden" @click="$emit('OpenSidebarModal')"
                        aria-controls="sidebar">
                        <span class="sr-only">Open sidebar</span>
                        <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <rect x="4" y="5" width="16" height="2" />
                            <rect x="4" y="11" width="16" height="2" />
                            <rect x="4" y="17" width="16" height="2" />
                        </svg>
                    </button>

                    <h1 class="font-semibold text-base text-slate-800 pt-1">
                        <span id="text-submodule" class="truncate">{{ moduleName }}</span>
                    </h1>
                </div>

                <!-- Header: Right side -->
                <div class="flex items-center space-x-3">
                    <!-- <button @click="isSearchModalOpen  = !isSearchModalOpen "
                        class="w-8 h-8 flex items-center justify-center bg-slate-100 hover:bg-slate-200 transition duration-150 rounded-full ml-3"
                        aria-controls="search-modal">
                        <span class="sr-only">Search</span>
                        <svg class="w-4 h-4" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg">
                            <path class="fill-current text-slate-500"
                                d="M7 14c-3.86 0-7-3.14-7-7s3.14-7 7-7 7 3.14 7 7-3.14 7-7 7zM7 2C4.243 2 2 4.243 2 7s2.243 5 5 5 5-2.243 5-5-2.243-5-5-5z">
                            </path>
                            <path class="fill-current text-slate-400"
                                d="M15.707 14.293L13.314 11.9a8.019 8.019 0 01-1.414 1.414l2.393 2.393a.997.997 0 001.414 0 .999.999 0 000-1.414z">
                            </path>
                        </svg>
                    </button>

                    <Notifications />

                    <help /> -->
                    <hr class="w-px h-6 bg-slate-200">
                    <UserMenuVue />
                </div>
            </div>
        </div>
        <SearchModal :stateToModalSearch="isSearchModalOpen" />
    </header>
</template>
<style>
#text-submodule {
    display: block;
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
}

/* Para pantallas pequeñas (móviles) */
@media (max-width: 639px) {
    #text-submodule {
        max-width: 100px;
        /* Ajusta este valor según tus necesidades */
    }
}

/* Para pantallas medianas (tabletas) */
@media (min-width: 640px) and (max-width: 1023px) {
    #text-submodule {
        max-width: 400px;
        /* Ajusta este valor según tus necesidades */
    }
}

/* Para pantallas grandes (escritorios) */
@media (min-width: 1024px) {
    #text-submodule {
        max-width: 400px;
        /* Ajusta este valor según tus necesidades */
    }
}


@media (max-width: 1023px) {
    .modal-open::before {
        content: "";
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 10;
    }
}
</style>
