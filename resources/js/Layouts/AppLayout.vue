<script setup>
import { ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import Modal from "@/Components/Modal.vue";
import HeaderVue from "@/Layouts/Header.vue";
import Sidebar from "@/Layouts/Sidebar.vue";

// Propiedades recibidas
const props = defineProps({
    nameSubModule: {
        type: String,
        default: 'nombre modulo',
        required: true,
    },
    autoPadding: {
        type: Boolean,
        default: true,
    }
});

// Estado para el sidebar
const changeState = ref(false);

// Método para cambiar el estado del sidebar
const onChangeState = () => {
    changeState.value = !changeState.value;
};
</script>
<template>
    <div class="flex h-screen overflow-hidden">
        <Sidebar
            :propToChangeStateSidebar="changeState"
            :color="$page.props.menu.sistema === 'Juridico' ? ' bg-[#343a40] ' : ''"
        />
        <div class="relative flex flex-col flex-1 overflow-y-auto overflow-x-hidden h-full">
            <HeaderVue
                @OpenOrCloseModal="onChangeState"
                :nameSubModule="nameSubModule"
            />
            <main class="bg-gray-100/50 flex-1">
                <div class="w-full max-w-9xl mx-auto" :class="autoPadding ? 'px-4 sm:px-6 lg:px-8 py-6' : ''">
                    <slot />
                </div>
            </main>
        </div>
    </div>
</template>
