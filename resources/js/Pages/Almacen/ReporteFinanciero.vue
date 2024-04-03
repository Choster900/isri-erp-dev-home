<template>

    <Head title="Reporte - Empleados" />
    <AppLayoutVue nameSubModule="Almacen - Reporte financiero" :autoPadding="false" :class="'bg-gray-200'">
        <div class="w-[85%] my-4 h-full mx-auto bg-white border border-gray-300" style="border-radius: 9px;">
            <div class="mb-2 mt-4 md:flex flex-row justify-around mx-4">

                <div class="mb-4 md:mr-2 md:mb-0 basis-[25%]">
                    <date-time-picker-m v-model="reportInfo.startDate" :showIcon="false" :label="'Fecha inicio'"
                        :placeholder="'Seleccione'" />
                    <InputError v-for="(item, index) in errors.startDate" :key="index" class="mt-2" :message="item" />
                </div>

                <div class="mb-4 md:mr-2 md:mb-0 basis-[25%]">
                    <date-time-picker-m v-model="reportInfo.endDate" :showIcon="false" :label="'Fecha fin'"
                        :placeholder="'Seleccione'" />
                    <InputError v-for="(item, index) in errors.endDate" :key="index" class="mt-2" :message="item" />
                </div>

                <div class="mb-4 md:mr-0 md:mb-0 basis-[25%]">
                    <label class="block mb-2 text-[13px] font-medium text-gray-600 dark:text-white">Fuente de
                        financiamiento
                        <span class="text-red-600 font-extrabold">*</span></label>
                    <div class="relative font-semibold flex h-[40px] w-full">
                        <Multiselect v-model="reportInfo.financingSourceId" :options="financingSourcers"
                            :searchable="true" :noOptionsText="'Lista vacía.'" placeholder="Seleccione" />
                    </div>
                    <InputError v-for="(item, index) in errors.financingSourceId" :key="index" class="mt-2"
                        :message="item" />
                </div>

            </div>

            <div
                class="md:flex flex md:items-center py-3 mb-2 sticky flex-row justify-center mx-4 border-gray-400 border-b">
                <button
                    class="bg-blue-700 hover:bg-blue-800 flex items-center justify-center text-white font-medium text-[12px] px-2.5 py-1.5 rounded-lg mr-1.5 mb-2">
                    <svg fill="#ffffff" width="28px" height="28px" viewBox="0 0 1024 1024"
                        xmlns="http://www.w3.org/2000/svg">
                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                        <g id="SVGRepo_iconCarrier">
                            <path
                                d="M691.2 410V256H333v154h-77v256h76.8v102h358.4V666H768V410h-76.8zM640 717H384V589h256v128zM384 410V307h256v103H384zm307.2 102c-14.1 0-25.6-11.5-25.6-25.6 0-14.1 11.5-25.6 25.6-25.6 14.1 0 25.6 11.5 25.6 25.6 0 14.1-11.5 25.6-25.6 25.6z">
                            </path>
                        </g>
                    </svg>
                    <span class="mt-0.5 text-white font-medium">GENERAR REPORTE</span>
                </button>
            </div>

        </div>
    </AppLayoutVue>
</template>

<script>
import { Head } from "@inertiajs/vue3";
import AppLayoutVue from "@/Layouts/AppLayout.vue";
import { useReporteFinanciero } from '@/Composables/Almacen/Reportes/useReporteFinanciero.js';
import moment from 'moment';
import InputError from "@/Components/InputError.vue";
import { jsPDF } from "jspdf";
import { ref, toRefs, computed, onMounted, watch } from 'vue';
import { usePermissions } from '@/Composables/General/usePermissions.js';
import DateTimePickerM from "@/Components-ISRI/ComponentsToForms/DateTimePickerM.vue";

import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';

import axios from 'axios';

export default {
    components: { Head, AppLayoutVue, DateTimePickerM },
    props: {
        menu: {
            type: Object,
            default: {}
        },
    },
    setup(props, context) {
        const { menu } = toRefs(props);
        const permits = usePermissions(menu.value, window.location.pathname);

        const {
            isLoadingExport, reportInfo, errors, financingSourcers
        } = useReporteFinanciero(context);

        return {
            permits, isLoadingExport, reportInfo, errors, financingSourcers
        };
    },
}
</script>

<style>
.sin-scroll {
    overflow: auto;
}

/* Estilos para ocultar la barra de desplazamiento en navegadores webkit */
.sin-scroll::-webkit-scrollbar {
    width: 0;
    height: 0;
}

/* Estilos para Firefox */
.sin-scroll {
    scrollbar-width: none;
    /* Firefox */
}

/* Definición de la transición */
.modal-fade-enter-active,
.modal-fade-leave-active {
    transition: opacity 0.3s ease;
}

.modal-fade-enter,
.modal-fade-leave-to {
    opacity: 0;
}

/* Animación de deslizamiento */
.modal-slide {
    transform: translateY(0);
}

.modal-slide-enter,
.modal-slide-leave-to {
    transform: translateY(20px);
    /* Ajusta según tu necesidad */
}
</style>