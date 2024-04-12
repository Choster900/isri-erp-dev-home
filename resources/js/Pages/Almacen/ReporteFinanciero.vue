<template>

    <Head title="Reporte - Empleados" />
    <AppLayoutVue nameSubModule="Almacen - Reporte financiero" :autoPadding="false" :class="'bg-gray-200'">
        <div class="w-[95%] my-4 h-full mx-auto bg-white border border-gray-300 ">
            <div class="mb-2 mt-4 md:flex flex-row justify-around mx-4 items-end">

                <DateSelect @optionId="getOption" />

                <div class="">
                    <DateTimePickerM v-model="reportInfo.startDate" :showIcon="false" :label="'Fecha inicio'"
                        :placeholder="'Seleccione'" :required="true" />
                    <InputError  class="mt-2" :message="errors[`reportInfo.startDate`]" />
                </div>

                <div class="">
                    <date-time-picker-m v-model="reportInfo.endDate" :showIcon="false" :label="'Fecha fin'"
                        :placeholder="'Seleccione'" :required="true" />
                        <InputError  class="mt-2" :message="errors[`reportInfo.endDate`]" />
                </div>

                <div class="mb-4 md:mr-0 md:mb-0 basis-[25%]">
                    <label class="block mb-2 text-[13px] font-medium text-gray-600 ">Fuente de
                        financiamiento
                        <span class="text-red-600 font-extrabold">*</span></label>
                    <div class="relative  flex h-[30px] w-full">
                        <Multiselect v-model="reportInfo.financingSourceId" :options="financingArray" :searchable="true"
                            :noOptionsText="'Lista vacía.'" placeholder="Seleccione" />
                    </div>
                    <InputError  class="mt-2" :message="errors[`reportInfo.financingSourceId`]" />

                </div>
                <button @click="getInformacionReport()"
                    class="bg-[#001c48] hover:bg-[#001c48]/90 flex h-[30px] items-center justify-center text-white font-medium text-[12px] px-2.5 py-0.5 rounded mr-1.5 ">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>

                    <span class="mt-0.5 text-white font-medium">Generar Reporte</span>
                </button>


            </div>
           <!--  <div class="md:flex flex md:items-center  mb-2 sticky flex-row justify-center  border-gray-400 border-b">
            </div> -->

            <TableReporteFinanciero :dataReporteInfo="dataReporteInfo" :isLoadingExport="isLoadingExport" :paramsToRequest="reportInfo" />
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
import DateSelect from "@/Components/DateSelect.vue";
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
import TableReporteFinanciero from "@/Components-ISRI/Almacen/Reportes/TableReporteFinanciero.vue"
import axios from 'axios';

export default {
    components: { Head, AppLayoutVue, DateTimePickerM, DateSelect, TableReporteFinanciero },
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
            isLoadingExport, reportInfo, errors, financingArray, dataReporteInfo
        } = useReporteFinanciero(context);


        const getOption = (e) => {
            moment.locale('en');
            switch (e) {
                case 0:
                    reportInfo.value.startDate = moment().startOf('month').format('ddd MMM DD YYYY HH:mm:ss [GMT]ZZ');
                    reportInfo.value.endDate = moment().format('ddd MMM DD YYYY HH:mm:ss [GMT]ZZ');
                    break;
                case 1:
                    reportInfo.value.startDate = moment().subtract(1, 'month').startOf('month').format('YYYY-MM-DD');
                    reportInfo.value.endDate = moment().subtract(1, 'month').endOf('month').format('YYYY-MM-DD');
                    break;
                case 2:
                    reportInfo.value.startDate = moment().startOf('year').format('YYYY-MM-DD');
                    reportInfo.value.endDate = moment().format('YYYY-MM-DD');
                    break;
                case 3:
                    reportInfo.value.startDate = moment().subtract(6, 'months').startOf('month').format('YYYY-MM-DD');
                    reportInfo.value.endDate = moment().format('YYYY-MM-DD');
                    break;
                case 4:
                    reportInfo.value.startDate = ''; // Asigna el primer día de tu rango de datos
                    reportInfo.value.endDate = ''; // Asigna el último día de tu rango de datos
                    break;
                default:
                    break;
            }


        }

        const getInformacionReport = async () => {
            try {
                isLoadingExport.value = true;
                const resp = await axios.post("/get-reporte-financiero-almacen-bienes-existencia", { reportInfo: reportInfo.value });
                const { data } = resp;

                console.log(data);

                dataReporteInfo.value = data;
                isLoadingExport.value = false;
            } catch (error) {
                console.error('Ocurrió un error al obtener la información del reporte:', error);
                isLoadingExport.value = false;


                if (error.response.status === 422) {
                    // Obtiene los errores del cuerpo de la respuesta y los transforma a un formato más manejable
                    let data = error.response.data.errors;
                    var myData = new Object();
                    for (const errorBack in data) {
                        myData[errorBack] = data[errorBack][0];
                    }
                    // Actualiza el estado "errors" con los errores y los limpia después de 5 segundos
                    errors.value = myData;
                    setTimeout(() => {
                        errors.value = [];
                    }, 5000);
                    console.error("Error en guardar los datos:", errors.value);
                }

                // Aquí podrías mostrar un mensaje de error al usuario, o manejar el error de otra manera según sea necesario.
            }
        }

        return {
            getOption, permits, isLoadingExport, reportInfo, errors, financingArray, dataReporteInfo, getInformacionReport
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

.dp__input_wrap {
    height: 30px;
}
</style>
