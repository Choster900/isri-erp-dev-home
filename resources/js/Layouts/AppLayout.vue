<script setup>
import { ref, onMounted, onBeforeUnmount, nextTick } from "vue";
import { Head } from "@inertiajs/vue3";
import Modal from "@/Components/Modal.vue";
import HeaderVue from "@/Layouts/Header.vue";
import Sidebar from "@/Layouts/Sidebar.vue";

// Propiedades recibidas
const props = defineProps({
    nameSubModule: {
        type: String,
        default: "",
        required: true,
    },
    autoPadding: {
        type: Boolean,
        default: true,
    },
});

// Estado para el sidebar
const isSidebarOpen = ref(false);
const isBackgroundVisible = ref(true);
// Estado del modal
const isModalOpen = ref(false);

// Método para cambiar el estado del sidebar
const openSidebarAndShowBackground = () => {

    isSidebarOpen.value = true;
    isBackgroundVisible.value = true;
};

// Método para cambiar el estado del background
const closeSidebarAndHideBackground = () => {

    isSidebarOpen.value = false;
    isBackgroundVisible.value = false;
};

// Método para cerrar el modal
const toggleModal = () => {
    isModalOpen.value = !isModalOpen.value;
};

// Manejar clics fuera del modal
const handleClickOutside = (event) => {
    if (event.target.id === "header-content") {
        console.log("entro en el if");
        // Cierra el modal y oculta el sidebar y el fondo si se hace clic fuera del modal
        isModalOpen.value = false; // Cierra el modal
        isBackgroundVisible.value = false; // Oculta el fondo
        isSidebarOpen.value = false; // Cierra el sidebar
    }
};

// @LifecycleHooks: Añadir y eliminar el listener para manejar clics fuera del modal
onMounted(async () => {
    await nextTick(); // Espera a que el DOM esté completamente renderizado
    document.addEventListener("click", handleClickOutside); // Añade el listener para clics en el documento
});

onBeforeUnmount(() => {
    document.removeEventListener("click", handleClickOutside); // Elimina el listener para evitar fugas de memoria
});
</script>

<template>
    <div class="flex h-screen overflow-hidden">

        <Sidebar :isSidebarVisible="isSidebarOpen" @updateSidebarState="closeSidebarAndHideBackground"
            :color="$page.props.menu.sistema === 'Juridico' ? ' bg-[#343a40] ' : ''" />

        <div class="relative flex flex-col flex-1 overflow-y-auto overflow-x-hidden h-full">
            <HeaderVue @OpenSidebarModal="openSidebarAndShowBackground()" :moduleName="nameSubModule"
                :isBackgroundVisible="isBackgroundVisible" />

            <main class="bg-gray-100/50 flex-1">
                <div class="w-full max-w-9xl mx-auto" :class="autoPadding ? 'px-4 sm:px-6 lg:px-8 py-6' : ''">
                    <slot />
                </div>
            </main>
        </div>
        <Modal v-if="isModalOpen" @close="toggleModal"></Modal>
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

/* Estilo para el fondo gris opaco cuando el modal está abierto */
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
