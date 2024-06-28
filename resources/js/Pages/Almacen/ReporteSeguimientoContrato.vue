<template>

    <Head title="Reporte - Seguimiento contratos" />
    <AppLayoutVue nameSubModule="Almacen - Seguimiento contratos" :autoPadding="false" :class="'bg-gray-200'">
        <div v-if="isLoadingReport"
            class="fixed top-0 left-0 right-0 bottom-0 flex items-center justify-center bg-gray-900 opacity-75 z-50">
            <div class="flex items-center justify-center my-4">
                <img src="../../../img/loader-spinner.gif" alt="" class="w-8 h-8">
                <h1 class="ml-4 font-medium text-xl text-white font-[Roboto]">Procesando...</h1>
            </div>
        </div>

        <div class="w-[97%] my-4 h-full mx-auto bg-white border border-gray-300" style="border-radius: 7px;">
            <div class="my-4 md:flex flex-row justify-around px-2 pb-6 border-b border-gray-300 ">

                <div class="mb-4 md:mr-2 md:mb-0 basis-[15%] flex items-end">
                    <DateSelect @optionId="getOption" />
                </div>

                <div class="mb-4 md:mr-2 md:mb-0 basis-[12%]">
                    <DateTimePickerM v-model="reportInfo.startDate" :showIcon="false" :label="'Fecha inicio'"
                        :placeholder="'Seleccione'" :required="true" :inputWrapHeight="'30px'" />
                    <InputError v-for="(item, index) in errors.startDate" :key="index" class="mt-2" :message="item" />
                </div>

                <div class="mb-4 md:mr-2 md:mb-0 basis-[12%]">
                    <DateTimePickerM v-model="reportInfo.endDate" :showIcon="false" :label="'Fecha fin'"
                        :placeholder="'Seleccione'" :required="true" :inputWrapHeight="'30px'" />
                    <InputError v-for="(item, index) in errors.endDate" :key="index" class="mt-2" :message="item" />
                </div>

                <div class="mb-4 md:mr-2 md:mb-0 basis-[20%]">
                    <label class="block mb-2 text-[13px] font-medium text-gray-600">Numero contrato
                        <span class="text-red-600 font-extrabold">*</span></label>
                    <div class="relative font-semibold flex h-[30px] w-full">
                        <Multiselect v-model="reportInfo.contractId" :options="contracts" :searchable="true"
                            :noOptionsText="'Lista vacía.'" placeholder="Seleccione" @change="changeContract($event)"
                            :classes="{ optionSelected: 'text-white bg-[#001c48] bg-opacity-80', optionSelectedPointed: 'text-white bg-[#001c48] opacity-80', noOptions: 'py-2 px-3 text-[12px] text-gray-600 bg-white text-left rtl:text-right', search: 'w-full absolute uppercase inset-0 outline-none focus:ring-0 appearance-none box-border border-0 text-base font-sans bg-white rounded pl-3.5 rtl:pl-0 rtl:pr-3.5', optionPointed: 'text-white bg-[#001c48] bg-opacity-40', }" />
                    </div>
                    <InputError v-for="(item, index) in errors.contractId" :key="index" class="mt-2" :message="item" />
                </div>

                <div class="mb-4 md:mr-2 md:mb-0 basis-[20%]">
                    <label class="block mb-2 text-[13px] font-medium text-gray-600">
                        Item de contrato
                        <span class="text-red-600 font-extrabold">*</span></label>
                    <div class="relative font-semibold flex h-[30px] w-full">
                        <Multiselect v-model="reportInfo.itemContractId" :options="filterItems" :searchable="true"
                            :noOptionsText="'Seleccione un contrato.'" placeholder="Seleccione"
                            :classes="{ optionSelected: 'text-white bg-[#001c48] bg-opacity-80', optionSelectedPointed: 'text-white bg-[#001c48] opacity-80', noOptions: 'py-2 px-3 text-[12px] text-gray-600 bg-white text-left rtl:text-right', search: 'w-full absolute uppercase inset-0 outline-none focus:ring-0 appearance-none box-border border-0 text-base font-sans bg-white rounded pl-3.5 rtl:pl-0 rtl:pr-3.5', optionPointed: 'text-white bg-[#001c48] bg-opacity-40', }" />
                    </div>
                    <InputError v-for="(item, index) in errors.itemContractId" :key="index" class="mt-2"
                        :message="item" />
                </div>

                <div class="mb-4 md:mr-0 md:mb-0 basis-[20%] flex items-end justify-end">
                    <button @click="getContractTrackingReport()"
                        class="bg-[#001c48] bg-opacity-80 hover:bg-opacity-90 flex items-center justify-center text-white font-medium text-[12px] px-2.5 rounded-lg mr-1.5">
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
                        <span class="mt-0.5 text-white font-medium">GENERAR</span>
                    </button>
                </div>

            </div>

            <div v-if="load === 0" class="flex items-center justify-center py-4 mb-6">
                <div class="bg-blue-100 border-l-4 border-blue-400 text-blue-600 p-4 rounded-md" role="alert">
                    <div class="flex">
                        <div class="py-1">
                            <svg class="fill-current h-6 w-6 mr-4" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 20 20">
                                <path d="M10 2a8 8 0 100 16 8 8 0 000-16zM9 12V10h2v4H9v-2zm0-4V5h2v3H9z" />
                            </svg>
                        </div>
                        <div>
                            <p class="mt-1 font-[Roboto]">Por favor, seleccione los criterios de
                                búsqueda y presione
                                generar.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div v-else-if="products.length === 0" class="flex items-center justify-center py-10">
                <div class="flex border border-gray-400 rounded-lg mt-1 mx-auto w-[50%] h-[100px]">
                    <div class="border-r border-gray-400 w-[15%] flex items-center justify-center py-8">
                        <svg class="text-orange-400 animate-bounce-svg" fill="currentColor" width="40px" height="40px"
                            viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" stroke="currentColor">
                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                            <g id="SVGRepo_iconCarrier">
                                <g>
                                    <path
                                        d="M19.79,16.72,11.06,1.61A1.19,1.19,0,0,0,9,1.61L.2,16.81C-.27,17.64.12,19,1.05,19H19C19.92,19,20.26,17.55,19.79,16.72ZM11,17H9V15h2Zm0-4H9L8.76,5h2.45Z">
                                    </path>
                                </g>
                            </g>
                        </svg>
                    </div>
                    <div class="w-[85%] rounded-r-lg text-[14px] flex flex-col items-center justify-center text-center">
                        <h2 class="text-orange-500 font-semibold font-[Roboto]">¡Sin resultados!</h2>
                        <p class="mx-2 mb-1 font-[Roboto]">No se han encontrado resultados en base a los
                            criterios de búsqueda proporcionados, por favor cambie los criterios e intente nuevamente.
                        </p>
                    </div>
                </div>
            </div>


            <div v-else id="content" class="mx-4 mb-4">
                <div class="text-[13px] py-2 flex justify-between items-center">
                    <p class="text-orange-600 font-semibold mr-8">PRODUCTOS <span class="text-gray-500">({{
                        products.length
                            }})</span></p>
                    <div class="flex">
                        <div @click="exportExcel()" class="flex items-center cursor-pointer text-slate-700 hover:text-green-600">
                            <svg class="h-4 w-4 text-green-500" xmlns:xlink="http://www.w3.org/1999/xlink"
                                viewBox="0 0 26 26" fill="#000000">
                                <path fill="currentColor"
                                    d="M25.162,3H16v2.984h3.031v2.031H16V10h3v2h-3v2h3v2h-3v2h3v2h-3v3h9.162 C25.623,23,26,22.609,26,22.13V3.87C26,3.391,25.623,3,25.162,3z M24,20h-4v-2h4V20z M24,16h-4v-2h4V16z M24,12h-4v-2h4V12z M24,8 h-4V6h4V8z">
                                </path>
                                <path fill="currentColor"
                                    d="M0,2.889v20.223L15,26V0L0,2.889z M9.488,18.08l-1.745-3.299c-0.066-0.123-0.134-0.349-0.205-0.678 H7.511C7.478,14.258,7.4,14.494,7.277,14.81l-1.751,3.27H2.807l3.228-5.064L3.082,7.951h2.776l1.448,3.037 c0.113,0.24,0.214,0.525,0.304,0.854h0.028c0.057-0.198,0.163-0.492,0.318-0.883l1.61-3.009h2.542l-3.037,5.022l3.122,5.107 L9.488,18.08L9.488,18.08z">
                                </path>
                            </svg>
                            <span class="ml-2 font-semibold text-[13px]">EXCEL</span>
                        </div>
                        <div @click="generatePDF()"
                            class="flex ml-4 items-center cursor-pointer text-slate-700 hover:text-red-600">
                            <svg class="h-4 w-4 text-red-500" fill="currentColor" viewBox="0 0 1920 1920"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M1185.46.034V564.74h564.705v1355.294H168.99V.034h1016.47ZM900.508 677.68c-16.829 0-31.963 7.567-42.918 21.007-49.807 59.972-31.398 193.016-18.748 272.075 2.823 17.958.452 36.141-7.228 52.518l-107.86 233.223c-7.905 16.942-20.555 30.608-36.592 39.53-68.104 37.835-182.287 89.675-196.066 182.626-4.97 30.268 5.082 56.357 28.574 79.85 15.925 15.133 35.238 22.7 56.245 22.7 81.43 0 132.819-71.717 188.273-148.517 24.62-34.221 61.666-55.229 102.437-60.876 76.349-10.503 167.83-32.527 223.172-46.983 27.897-7.341 56.358-5.534 83.802 3.162 48.565 15.586 66.975 25.073 122.768 25.073 50.371 0 84.818-11.746 101.534-34.447 13.44-16.828 16.715-39.53 10.164-65.619-11.858-42.804-2.033-89.675-133.044-89.675-29.365 0-57.94 2.824-81.77 6.099-36.819 4.97-73.299-10.955-97.016-40.885-32.301-40.546-65.167-88.433-87.981-123.219-16.151-24.508-21.572-53.986-16.264-83.124 15.473-84.706 18.41-147.615-23.492-206.683-17.619-25.186-41.223-37.835-67.99-37.835Zm397.903-660.808 434.936 434.937h-434.936V16.873Z">
                                </path>
                                <path
                                    d="M791.057 1297.943c92.273-43.37 275.916-65.28 275.916-65.28-92.386-88.998-145.92-215.04-145.92-215.04-43.257 126.607-119.718 264.282-129.996 280.32">
                                </path>
                            </svg>
                            <span class="ml-2 text-[13px] font-semibold">PDF</span>
                        </div>
                    </div>
                </div>

                <div id="doc-header" class="py-1 flex justify-between items-center border-y-4 border-gray-600 mb-2">
                    <div class="text-left flex flex-col">
                        <p class="font-[Roboto] text-[13px] font-bold">INSTITUTO SALVADOREÑO DE REHABILITACION INTEGRAL
                        </p>
                        <p class="font-[Roboto] text-[13px] font-bold">ALMACEN GENERAL</p>
                        <p class="font-[Roboto] text-[13px] font-bold">SEGUIMIENTO DE PRODUCTOS POR CONTRATO</p>
                    </div>
                    <div class="text-right">
                        <p class="font-[Roboto] uppercase text-[12px]">DEL
                            {{ moment(reportInfo.startDate).format('DD,MMMM, YYYY') }}
                        </p>
                        <p class="font-[Roboto] uppercase text-[12px]">AL
                            {{ moment(reportInfo.endDate).format('DD,MMMM, YYYY') }}
                        </p>
                    </div>
                </div>
                <div class="flex justify-between">
                    <p class="font-[Roboto] text-[12px] mb-1 pb-2">CONTRATO: <span
                            class="font-[Roboto] text-[12px] font-bold">{{ contractName }}</span></p>
                    <p v-if="purchaseProcess === 5"
                        class="font-[Roboto] text-[12px] mb-1 pb-2 font-bold text-orange-600">SEGUIMIENTO EN DOLARES ($)</p>
                </div>

                <div class="w-full overflow-y-auto pb-2">
                    <table class="w-full bg-opacity-80 min-w-[900px]">
                        <!-- Table header -->
                        <thead class="bg-[#001c48] text-white border border-gray-600">
                            <tr>
                                <th class="min-w-[250px] border-r border-gray-500 h-[30px] text-center font-[MuseoSans] text-[10px]">
                                    PRODUCTO
                                </th>
                                <th v-for="(month, index) in months" :key="index"
                                    class="min-w-[200px] border-r border-gray-500 h-[30px] text-center font-[MuseoSans] text-[10px]">
                                    {{ month }}
                                </th>
                            </tr>
                        </thead>
                        <!-- Table body -->
                        <tbody>
                            <tr v-for="(prod, index) in products" :key="index"
                                :class="index % 2 === 0 ? 'bg-gray-100' : 'bg-white'"
                                class="hover:bg-gray-300 border-x border-b border-gray-600 h-[60px]">
                                <td
                                    class="h-[60px] overflow-y-auto min-w-[250px] border-r border-gray-600 ">
                                    <div class="text-center h-[60px] font-[MuseoSans] text-[10px] font-bold overflow-y-auto">{{ prod.producto }}</div>
                                    
                                </td>
                                <td v-for="(month, index2) in prod.meses" :key="index2"
                                    class="min-w-[200px] border-r border-gray-600 h-[60px]">
                                    <div class="grid grid-cols-3 ">
                                        <div class="border-r border-gray-600 text-center font-[MuseoSans] text-[10px] text-gray-500">
                                            Contratado</div>
                                        <div class="border-r border-gray-600 text-center font-[MuseoSans] text-[10px] text-gray-500">
                                            Recibido</div>
                                        <div class="text-center font-[MuseoSans] text-[10px] text-gray-500">Saldo</div>
                                    </div>
                                    <div class="grid grid-cols-3 ">
                                        <div class="border-r border-gray-600 text-center font-[MuseoSans] text-[10px] font-bold"
                                        :class="month.res.cant_pa > 0 ? 'text-green-800 bg-green-200' : ''">
                                            {{ month.res.cant_pa }}</div>
                                        <div class="border-r border-gray-600 text-center font-[MuseoSans] text-[10px] font-bold"
                                        :class="month.res.cant_pa > 0 ? (month.res.cant_rec > 0 ? 'text-blue-800 bg-blue-200' : 'text-red-800 bg-red-200') : ''">
                                            {{ month.res.cant_rec }}</div>
                                        <div class="text-center font-[MuseoSans] text-[10px] font-bold"
                                        :class="month.res.cant_pa > 0 ? (month.res.saldo > 0 ? 'text-yellow-800 bg-yellow-200' : 'text-green-800 bg-green-200') : ''">
                                        {{ month.res.saldo }}
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>




    </AppLayoutVue>
</template>

<script>
import { ref, toRefs, inject } from 'vue';
import { Head } from "@inertiajs/vue3";
import AppLayoutVue from "@/Layouts/AppLayout.vue";
import moment from 'moment';
import { usePermissions } from '@/Composables/General/usePermissions.js';
import { useReporteSeguimientoContrato } from '@/Composables/Almacen/Reportes/useReporteSeguimientoContrato.js';
import DateTimePickerM from "@/Components-ISRI/ComponentsToForms/DateTimePickerM.vue";
import DateSelect from "@/Components/DateSelect.vue";

export default {
    components: { Head, AppLayoutVue, DateTimePickerM, DateSelect },
    props: {
        menu: {
            type: Object,
            default: {}
        },
    },
    setup(props, context) {
        const { menu } = toRefs(props);
        const permits = usePermissions(menu.value, window.location.pathname);

        const months = ref([
            'ENERO', 'FEBRERO', 'MARZO', 'ABRIL', 'MAYO', 'JUNIO', 'JULIO', 'AGOSTO', 'SEPTIEMBRE', 'OCTUBRE', 'NOVIEMBRE', 'DICIEMBRE'
        ])

        const {
            isLoadingReport, reportInfo, errors, contracts, filterItems, products,
            contractName, purchaseProcess, load,
            getOption, changeContract, getContractTrackingReport, generatePDF, exportExcel
        } = useReporteSeguimientoContrato(context);

        return {
            permits, isLoadingReport, reportInfo, errors, contracts, filterItems,
            products, moment, contractName, purchaseProcess, load, months,
            getOption, changeContract, getContractTrackingReport, generatePDF, exportExcel
        };
    },
}

</script>

<style>
.dp__theme_light {
    --dp-primary-color: rgba(0, 28, 72, 0.8);
}

@keyframes bounce-svg {
    0% {
        transform: translateY(0);
    }

    100% {
        transform: translateY(-10px);
    }
}

.animate-bounce-svg {
    animation: bounce-svg 0.5s infinite alternate;
}

@media (max-width: 640px) {
    .rotate-down {
        transform: rotate(90deg);
        /* Rotar la flecha 90 grados */
    }
}
</style>