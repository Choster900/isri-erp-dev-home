<script setup>
import Modal from "@/Components/Modal.vue";
import HeaderVue from "@/Layouts/Header.vue";
import Sidebar from "@/Layouts/Sidebar.vue";
import { Head } from "@inertiajs/vue3";
</script>

<template>
    <div class="flex h-screen overflow-hidden">
        <Sidebar :propToChangeStateSidebar="changeState" />
        <div class="relative flex flex-col flex-1 overflow-y-auto overflow-x-hidden h-full">
            <HeaderVue @OpenOrCloseModal="changeState = !changeState" :nameSubModule="nameSubModule" />
            <main class="bg-gray-100/50 flex-1">
                <div class=" w-full max-w-9xl mx-auto" :class="autoPadding ? 'px-4 sm:px-6 lg:px-8 py-6' : ''">
                    <slot />
                </div>
            </main>
        </div>
    </div>
</template>


<script>
export default {
    data: function () {
        return {
            changeState: false,
        };
    },
    props: {
        nameSubModule: {//prop muestra estado del modal para abrir y cerrar
            type: String,
            default: 'nombre modulo',
            required: true,
        },
        autoPadding: {//prop muestra estado del modal para abrir y cerrar
            type: Boolean,
            default: true,
        }
    }
}
</script>



