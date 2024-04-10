<template>

    <Head title="Reporte - Empleados" />
    <AppLayoutVue nameSubModule="Almacen - Reporte financiero" :autoPadding="false" :class="'bg-gray-200'">
        <div class="w-[95%] my-4 h-full mx-auto bg-white border border-gray-300" style="border-radius: 9px;">
            <div class="mb-2 mt-4 md:flex flex-row justify-around mx-4">


                <div class="mb-4 md:mr-2 md:mb-0 basis-[25%]">
                    <date-time-picker-m v-model="reportInfo.startDate" :showIcon="false" :label="'Fecha inicio'"
                        :placeholder="'Seleccione'" :required="true" />
                    <InputError v-for="(item, index) in errors.startDate" :key="index" class="mt-2" :message="item" />
                </div>

                <div class="mb-4 md:mr-2 md:mb-0 basis-[25%]">
                    <date-time-picker-m v-model="reportInfo.endDate" :showIcon="false" :label="'Fecha fin'"
                        :placeholder="'Seleccione'" :required="true" />
                    <InputError v-for="(item, index) in errors.endDate" :key="index" class="mt-2" :message="item" />
                </div>

                <div class="mb-4 md:mr-0 md:mb-0 basis-[25%]">
                    <label class="block mb-2 text-[13px] font-medium text-gray-600 ">Fuente de
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
                    class="bg-red-700 hover:bg-red-800 flex items-center justify-center text-white font-medium text-[12px] px-2.5 py-1.5 rounded-lg mr-1.5 mb-2">
                    <svg fill="#ffffff" width="28px" height="28px" viewBox="0 0 1024 1024"
                        xmlns="http://www.w3.org/2000/svg">

                            <path
                                d="M691.2 410V256H333v154h-77v256h76.8v102h358.4V666H768V410h-76.8zM640 717H384V589h256v128zM384 410V307h256v103H384zm307.2 102c-14.1 0-25.6-11.5-25.6-25.6 0-14.1 11.5-25.6 25.6-25.6 14.1 0 25.6 11.5 25.6 25.6 0 14.1-11.5 25.6-25.6 25.6z">
                            </path>

                    </svg>
                    <span class="mt-0.5 text-white font-medium">GENERAR REPORTE</span>
                </button>
                <button
                    class="bg-red-700 hover:bg-red-800 flex items-center justify-center gap-1 text-white font-medium text-[12px] px-2.5 py-1.5 rounded-lg mr-1.5 mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"  width="28px" height="28px" color="#FFFFFF"
                        fill="none">
                        <path
                            d="M2.45898 9C3.7364 4.94289 7.53608 2 12.0248 2C17.223 2 21.4971 5.94668 22 11L20 10.5929"
                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        <path
                            d="M21.541 15C20.2636 19.0571 16.4639 22 11.9752 22C6.77707 22 2.50297 18.0533 2 13L4.00005 13.4071"
                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        <path
                            d="M9.00198 13.5279V15.5197C9.00198 15.8464 9.15779 16.1573 9.43401 16.3301C10.2776 16.8578 10.9193 17.0125 12.0054 17.046C13.0061 17.0729 13.6336 16.9156 14.5679 16.3316C14.8481 16.1564 15.0089 15.8427 15.0089 15.5113V13.5279M17.0112 11.0149V14.0304M7.04924 10.8436C7.41086 10.0796 9.65363 8.74957 11.7009 8.09253C11.8984 8.02914 12.1106 8.03542 12.3053 8.10674C14.1154 8.76943 16.1288 9.77168 16.8597 10.5853C17.2414 11.01 16.8682 11.552 16.4068 11.8874C15.4704 12.5682 14.4364 13.0978 12.3491 13.8996C12.1282 13.9844 11.8828 13.9873 11.6608 13.9049C9.52012 13.1101 7.51902 12.0842 7.06587 11.1817C7.01245 11.0752 6.9983 10.9512 7.04924 10.8436Z"
                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <span class="mt-0.5 text-white font-medium">LIMPIAR</span>
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
