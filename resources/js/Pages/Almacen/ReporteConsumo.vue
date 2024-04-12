<template>

    <Head title="Reporte - Empleados" />
    <AppLayoutVue nameSubModule="Almacen - Reporte financiero" :autoPadding="false" :class="'bg-gray-200'">
        <div class="w-[95%] my-4 h-full mx-auto bg-white border border-gray-300">
            <div class="mt-4 md:flex flex-row justify-start mx-2 gap-1 items-center">
                <div class="mb-4 md:mr-0 md:mb-0 basis-[25%]">
                    <label class="block mb-2 text-[13px] font-medium text-gray-600">Fuente Financiamiento
                        <span class="text-red-600 font-extrabold">*</span></label>
                    <div class="relative flex h-[30px] w-full">
                        <Multiselect v-model="reportInfo.financingSourceId" :options="financingArray" :searchable="true"
                            :noOptionsText="'Lista vacía.'" placeholder="Seleccione" />
                    </div>
                    <InputError class="mt-2" :message="errors[`reportInfo.financingSourceId`]" />
                </div>
                <div class="mb-4 md:mr-0 md:mb-0 basis-[25%]">
                    <label class="block mb-2 text-[13px] font-medium text-gray-600">Centro Atencion <span
                            class="text-red-600 font-extrabold">*</span></label>
                    <div class="relative flex h-[30px] w-full">
                        <Multiselect v-model="reportInfo.financingSourceId" :options="financingArray" :searchable="true"
                            :noOptionsText="'Lista vacía.'" placeholder="Seleccione" />
                    </div>
                    <InputError class="mt-2" :message="errors[`reportInfo.financingSourceId`]" />
                </div>
                <div class="mb-4 md:mr-0 md:mb-0 basis-[25%]">
                    <label class="block mb-2 text-[13px] font-medium text-gray-600">Tipo Transaccion <span
                            class="text-red-600 font-extrabold">*</span></label>
                    <div class="relative flex h-[30px] w-full">
                        <Multiselect v-model="reportInfo.financingSourceId" :options="financingArray" :searchable="true"
                            :noOptionsText="'Lista vacía.'" placeholder="Seleccione" />
                    </div>
                    <InputError class="mt-2" :message="errors[`reportInfo.financingSourceId`]" />
                </div>
                <div class="mb-4 md:mr-0 md:mb-0 basis-[25%]">
                    <label class="block mb-2 text-[13px] font-medium text-gray-600">Numero <span
                            class="text-red-600 font-extrabold">*</span></label>
                    <div class="relative flex h-[30px] w-full">
                        <Multiselect v-model="reportInfo.financingSourceId" :options="financingArray" :searchable="true"
                            :noOptionsText="'Lista vacía.'" placeholder="Seleccione" />
                    </div>
                    <InputError class="mt-2" :message="errors[`reportInfo.financingSourceId`]" />
                </div>
            </div>
            <div class="mb-2 mt-1 md:flex flex-row justify-start gap-2 mx-2 items-end">
                <!-- <DateSelect @optionId="getOption" /> -->
                <div class="w-1/4">
                    <DateTimePickerM v-model="reportInfo.startDate" :showIcon="false" :label="'Fecha inicio'"
                        :placeholder="'Seleccione'" :required="true" />
                    <InputError class="mt-2" :message="errors[`reportInfo.startDate`]" />
                </div>
                <div class="w-1/4">
                    <date-time-picker-m v-model="reportInfo.endDate" :showIcon="false" :label="'Fecha fin'"
                        :placeholder="'Seleccione'" :required="true" />
                    <InputError class="mt-2" :message="errors[`reportInfo.endDate`]" />
                </div>


                <div>
                    <label class="block mb-2 text-[13px] font-medium text-gray-600">Tipo Reporte<span
                            class="text-red-600 font-extrabold">*</span></label>
                    <div class="flex gap-2">
                        <label>
                            <input type="radio" value="1" class="peer hidden" name="framework" />
                            <div
                                class="hover:bg-gray-50 flex items-center justify-between px-4 border-2 rounded cursor-pointer text-sm border-gray-200 group peer-checked:border-blue-500">
                                <h2 class="font-medium text-gray-700">Consolidado</h2>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor"
                                    class="w-7 h-7 text-blue-600 invisible group-[.peer:checked+&]:visible">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </label>

                        <label>
                            <input type="radio" value="1" class="peer hidden" name="framework" />
                            <div
                                class="hover:bg-gray-50 flex items-center justify-between px-4 border-2 rounded cursor-pointer text-sm border-gray-200 group peer-checked:border-blue-500">
                                <h2 class="font-medium text-gray-700">Detallado</h2>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor"
                                    class="w-7 h-7 text-blue-600 invisible group-[.peer:checked+&]:visible">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </label>
                    </div>

                </div>


                <button @click="getInformacionReport()"
                    class="bg-[#001c48] hover:bg-[#001c48]/90 flex h-[30px] items-center justify-center text-white font-medium text-[12px] px-2.5 py-0.5 rounded mr-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                    <span class="mt-0.5 text-white font-medium">Generar Reporte</span>
                </button>

                <!-- <div class="flex" style="display: non;">
                    <div class="flex items-center cursor-pointer text-slate-700 hover:text-green-600"><svg
                            class="h-4 w-4 text-green-500" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg"
                            xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 26 26" xml:space="preserve"
                            fill="#000000">
                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                            <g id="SVGRepo_iconCarrier">
                                <g>
                                    <path fill="currentColor"
                                        d="M25.162,3H16v2.984h3.031v2.031H16V10h3v2h-3v2h3v2h-3v2h3v2h-3v3h9.162 C25.623,23,26,22.609,26,22.13V3.87C26,3.391,25.623,3,25.162,3z M24,20h-4v-2h4V20z M24,16h-4v-2h4V16z M24,12h-4v-2h4V12z M24,8 h-4V6h4V8z">
                                    </path>
                                    <path fill="currentColor"
                                        d="M0,2.889v20.223L15,26V0L0,2.889z M9.488,18.08l-1.745-3.299c-0.066-0.123-0.134-0.349-0.205-0.678 H7.511C7.478,14.258,7.4,14.494,7.277,14.81l-1.751,3.27H2.807l3.228-5.064L3.082,7.951h2.776l1.448,3.037 c0.113,0.24,0.214,0.525,0.304,0.854h0.028c0.057-0.198,0.163-0.492,0.318-0.883l1.61-3.009h2.542l-3.037,5.022l3.122,5.107 L9.488,18.08L9.488,18.08z">
                                    </path>
                                </g>
                            </g>
                        </svg><span class="ml-2 font-semibold text-[14px]">EXPORTAR</span></div>
                    <div class="flex ml-4 items-center cursor-pointer text-slate-700 hover:text-red-600"><svg
                            class="h-4 w-4 text-red-500" fill="currentColor" viewBox="0 0 1920 1920"
                            xmlns="http://www.w3.org/2000/svg">
                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                            <g id="SVGRepo_iconCarrier">
                                <g fill-rule="evenodd">
                                    <path
                                        d="M1185.46.034V564.74h564.705v1355.294H168.99V.034h1016.47ZM900.508 677.68c-16.829 0-31.963 7.567-42.918 21.007-49.807 59.972-31.398 193.016-18.748 272.075 2.823 17.958.452 36.141-7.228 52.518l-107.86 233.223c-7.905 16.942-20.555 30.608-36.592 39.53-68.104 37.835-182.287 89.675-196.066 182.626-4.97 30.268 5.082 56.357 28.574 79.85 15.925 15.133 35.238 22.7 56.245 22.7 81.43 0 132.819-71.717 188.273-148.517 24.62-34.221 61.666-55.229 102.437-60.876 76.349-10.503 167.83-32.527 223.172-46.983 27.897-7.341 56.358-5.534 83.802 3.162 48.565 15.586 66.975 25.073 122.768 25.073 50.371 0 84.818-11.746 101.534-34.447 13.44-16.828 16.715-39.53 10.164-65.619-11.858-42.804-2.033-89.675-133.044-89.675-29.365 0-57.94 2.824-81.77 6.099-36.819 4.97-73.299-10.955-97.016-40.885-32.301-40.546-65.167-88.433-87.981-123.219-16.151-24.508-21.572-53.986-16.264-83.124 15.473-84.706 18.41-147.615-23.492-206.683-17.619-25.186-41.223-37.835-67.99-37.835Zm397.903-660.808 434.936 434.937h-434.936V16.873Z">
                                    </path>
                                    <path
                                        d="M791.057 1297.943c92.273-43.37 275.916-65.28 275.916-65.28-92.386-88.998-145.92-215.04-145.92-215.04-43.257 126.607-119.718 264.282-129.996 280.32">
                                    </path>
                                </g>
                            </g>
                        </svg><span class="ml-2 text-[14px] font-semibold">PDF</span></div>
                </div> -->
            </div>

            <div class="px-2 flex gap-3">
                <h1 class="font-medium">Reporte de consumo: 40 registros</h1>
                <div class="flex" style="display: non;">
                    <div @click="exportExcel" class="flex items-center cursor-pointer text-slate-700 hover:text-green-600"><svg
                            class="h-4 w-4 text-green-500" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg"
                            xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 26 26" xml:space="preserve"
                            fill="#000000">
                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                            <g id="SVGRepo_iconCarrier">
                                <g>
                                    <path fill="currentColor"
                                        d="M25.162,3H16v2.984h3.031v2.031H16V10h3v2h-3v2h3v2h-3v2h3v2h-3v3h9.162 C25.623,23,26,22.609,26,22.13V3.87C26,3.391,25.623,3,25.162,3z M24,20h-4v-2h4V20z M24,16h-4v-2h4V16z M24,12h-4v-2h4V12z M24,8 h-4V6h4V8z">
                                    </path>
                                    <path fill="currentColor"
                                        d="M0,2.889v20.223L15,26V0L0,2.889z M9.488,18.08l-1.745-3.299c-0.066-0.123-0.134-0.349-0.205-0.678 H7.511C7.478,14.258,7.4,14.494,7.277,14.81l-1.751,3.27H2.807l3.228-5.064L3.082,7.951h2.776l1.448,3.037 c0.113,0.24,0.214,0.525,0.304,0.854h0.028c0.057-0.198,0.163-0.492,0.318-0.883l1.61-3.009h2.542l-3.037,5.022l3.122,5.107 L9.488,18.08L9.488,18.08z">
                                    </path>
                                </g>
                            </g>
                        </svg><span class="ml-2 font-semibold text-[14px]">EXPORTAR</span></div>
                    <div class="flex ml-4 items-center cursor-pointer text-slate-700 hover:text-red-600"><svg
                            class="h-4 w-4 text-red-500" fill="currentColor" viewBox="0 0 1920 1920"
                            xmlns="http://www.w3.org/2000/svg">
                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                            <g id="SVGRepo_iconCarrier">
                                <g fill-rule="evenodd">
                                    <path
                                        d="M1185.46.034V564.74h564.705v1355.294H168.99V.034h1016.47ZM900.508 677.68c-16.829 0-31.963 7.567-42.918 21.007-49.807 59.972-31.398 193.016-18.748 272.075 2.823 17.958.452 36.141-7.228 52.518l-107.86 233.223c-7.905 16.942-20.555 30.608-36.592 39.53-68.104 37.835-182.287 89.675-196.066 182.626-4.97 30.268 5.082 56.357 28.574 79.85 15.925 15.133 35.238 22.7 56.245 22.7 81.43 0 132.819-71.717 188.273-148.517 24.62-34.221 61.666-55.229 102.437-60.876 76.349-10.503 167.83-32.527 223.172-46.983 27.897-7.341 56.358-5.534 83.802 3.162 48.565 15.586 66.975 25.073 122.768 25.073 50.371 0 84.818-11.746 101.534-34.447 13.44-16.828 16.715-39.53 10.164-65.619-11.858-42.804-2.033-89.675-133.044-89.675-29.365 0-57.94 2.824-81.77 6.099-36.819 4.97-73.299-10.955-97.016-40.885-32.301-40.546-65.167-88.433-87.981-123.219-16.151-24.508-21.572-53.986-16.264-83.124 15.473-84.706 18.41-147.615-23.492-206.683-17.619-25.186-41.223-37.835-67.99-37.835Zm397.903-660.808 434.936 434.937h-434.936V16.873Z">
                                    </path>
                                    <path
                                        d="M791.057 1297.943c92.273-43.37 275.916-65.28 275.916-65.28-92.386-88.998-145.92-215.04-145.92-215.04-43.257 126.607-119.718 264.282-129.996 280.32">
                                    </path>
                                </g>
                            </g>
                        </svg><span class="ml-2 text-[14px] font-semibold">PDF</span></div>
                </div>
            </div>
            <TableReporteConsumo :dataReporteInfo="dataReporteInfo" />
        </div>
    </AppLayoutVue>
</template>

<script>
import { Head } from "@inertiajs/vue3";
import AppLayoutVue from "@/Layouts/AppLayout.vue";
import { useReporteFinanciero } from "@/Composables/Almacen/Reportes/useReporteFinanciero.js";
import moment from "moment";
import InputError from "@/Components/InputError.vue";
import { jsPDF } from "jspdf";
import { ref, toRefs, computed, onMounted, watch } from "vue";
import { usePermissions } from "@/Composables/General/usePermissions.js";
import DateTimePickerM from "@/Components-ISRI/ComponentsToForms/DateTimePickerM.vue";
import DateSelect from "@/Components/DateSelect.vue";
import { toast } from "vue3-toastify";
import "vue3-toastify/dist/index.css";
import TableReporteConsumo from "@/Components-ISRI/Almacen/Reportes/TableReporteConsumo.vue";
import axios from "axios";

export default {
    components: { Head, AppLayoutVue, DateTimePickerM, DateSelect, TableReporteConsumo },
    props: {
        menu: {
            type: Object,
            default: {},
        },
    },
    setup(props, context) {
        const { menu } = toRefs(props);
        const permits = usePermissions(menu.value, window.location.pathname);
        const {
            isLoadingExport,
            reportInfo,
            errors,
            financingArray,
            dataReporteInfo,
        } = useReporteFinanciero(context);

        const getOption = (e) => {
            moment.locale("en");
            switch (e) {
                case 0:
                    reportInfo.value.startDate = moment()
                        .startOf("month")
                        .format("ddd MMM DD YYYY HH:mm:ss [GMT]ZZ");
                    reportInfo.value.endDate = moment().format("ddd MMM DD YYYY HH:mm:ss [GMT]ZZ");
                    break;
                case 1:
                    reportInfo.value.startDate = moment()
                        .subtract(1, "month")
                        .startOf("month")
                        .format("YYYY-MM-DD");
                    reportInfo.value.endDate = moment()
                        .subtract(1, "month")
                        .endOf("month")
                        .format("YYYY-MM-DD");
                    break;
                case 2:
                    reportInfo.value.startDate = moment().startOf("year").format("YYYY-MM-DD");
                    reportInfo.value.endDate = moment().format("YYYY-MM-DD");
                    break;
                case 3:
                    reportInfo.value.startDate = moment()
                        .subtract(6, "months")
                        .startOf("month")
                        .format("YYYY-MM-DD");
                    reportInfo.value.endDate = moment().format("YYYY-MM-DD");
                    break;
                case 4:
                    reportInfo.value.startDate = ""; // Asigna el primer día de tu rango de datos
                    reportInfo.value.endDate = ""; // Asigna el último día de tu rango de datos
                    break;
                default:
                    break;
            }
        };


        const exportExcel = async () => {
            try {
                const response = await axios.post(
                    "/get-excel-document-reporte-consumo",
                    null,
                    { responseType: "blob" }
                );

                // Crear una URL para el blob
                const url = window.URL.createObjectURL(new Blob([response.data]));

                // Crear un enlace temporal y simular un clic para descargar el archivo
                const link = document.createElement("a");
                link.href = url;
                link.setAttribute("download", "nombre_del_archivo.xlsx"); // Nombre del archivo deseado
                document.body.appendChild(link);
                link.click();

                // Liberar la URL del blob después de la descarga
                window.URL.revokeObjectURL(url);
            } catch (error) {
                console.error("Error al descargar el archivo:", error);
            }
        };

        const getInformacionReport = async () => {
            try {
                isLoadingExport.value = true;
                const resp = await axios.post(
                    "/get-reporte-consumo",
                    { reportInfo: reportInfo.value }
                );
                const { data } = resp;

                console.log(data);

                dataReporteInfo.value = data;
                isLoadingExport.value = false;
            } catch (error) {
                console.error("Ocurrió un error al obtener la información del reporte:", error);
                isLoadingExport.value = false;

                /* if (error.response.status === 422) {
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
                } */

                // Aquí podrías mostrar un mensaje de error al usuario, o manejar el error de otra manera según sea necesario.
            }
        };

        return {
            getOption,
            permits,
            isLoadingExport,
            reportInfo,
            exportExcel,
            errors,
            financingArray,
            dataReporteInfo,
            getInformacionReport,
        };
    },
};
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
