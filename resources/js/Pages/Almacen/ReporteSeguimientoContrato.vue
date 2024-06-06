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
            <div class="my-2 md:flex flex-row justify-around mx-2 pb-6">

                <div class="mb-4 md:mr-2 md:mb-0 basis-[15%] flex items-end">
                    <DateSelect @optionId="getOption" />
                </div>

                <div class="mb-4 md:mr-2 md:mb-0 basis-[12%]">
                    <DateTimePickerM v-model="reportInfo.startDate" :showIcon="false" :label="'Fecha inicio'"
                        :placeholder="'Seleccione'" :required="true" />
                    <InputError v-for="(item, index) in errors.startDate" :key="index" class="mt-2" :message="item" />
                </div>

                <div class="mb-4 md:mr-2 md:mb-0 basis-[12%]">
                    <DateTimePickerM v-model="reportInfo.endDate" :showIcon="false" :label="'Fecha fin'"
                        :placeholder="'Seleccione'" :required="true" />
                    <InputError v-for="(item, index) in errors.endDate" :key="index" class="mt-2" :message="item" />
                </div>

                <div class="mb-4 md:mr-2 md:mb-0 basis-[20%]">
                    <label class="block mb-2 text-[13px] font-medium text-gray-600 dark:text-white">Numero contrato
                        <span class="text-red-600 font-extrabold">*</span></label>
                    <div class="relative font-semibold flex h-[30px] w-full">
                        <Multiselect v-model="reportInfo.contractId" :options="contracts" :searchable="true"
                            :noOptionsText="'Lista vacía.'" placeholder="Seleccione" @change="changeContract($event)"
                            :classes="{ optionSelected: 'text-white bg-[#001c48] bg-opacity-80', optionSelectedPointed: 'text-white bg-[#001c48] opacity-80', noOptions: 'py-2 px-3 text-[12px] text-gray-600 bg-white text-left rtl:text-right', search: 'w-full absolute uppercase inset-0 outline-none focus:ring-0 appearance-none box-border border-0 text-base font-sans bg-white rounded pl-3.5 rtl:pl-0 rtl:pr-3.5', optionPointed: 'text-white bg-[#001c48] bg-opacity-40', }" />
                    </div>
                    <InputError v-for="(item, index) in errors.contractId" :key="index" class="mt-2" :message="item" />
                </div>

                <div class="mb-4 md:mr-2 md:mb-0 basis-[20%]">
                    <label class="block mb-2 text-[13px] font-medium text-gray-600 dark:text-white">
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

                <div class="mb-4 md:mr-0 md:mb-0 basis-[20%] flex items-end justify-center">
                    <button @click="getContractTrackingReport()"
                        class="bg-blue-700 hover:bg-blue-800 flex items-center justify-center text-white font-medium text-[12px] px-2.5 py-0.5 rounded-lg mr-1.5">
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

            <div id="content" class="mx-4">
                <div id="doc-header" class="py-1 flex justify-between items-center border-y-4 border-gray-600 mb-2">
                    <div class="text-left flex flex-col">
                        <p class="font-[Roboto] text-[13px]">INSTITUTO SALVADOREÑO DE REHABILITACION INTEGRAL</p>
                        <p class="font-[Roboto] text-[13px]">ALMACEN GENERAL</p>
                        <p class="font-[Roboto] text-[13px]">SEGUIMIENTO DE PRODUCTOS POR CONTRATO</p>
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
                <div>
                    <p class="font-[Roboto] text-[12px] font-bold mb-1 pb-2">N° CONTRATO: {{ contractName }}</p>
                </div>

                <div class="w-full overflow-y-auto">
                    <div
                        class="grid grid-cols-[18%_82%] max-w-full w-full bg-[#001c48] text-white border border-gray-600 bg-opacity-80 min-w-[900px]">
                        <!-- Table header -->
                        <div class="w-full flex items-center justify-center border-r border-gray-600 h-[30px]">
                            <p class="text-center font-[MuseoSans] text-[11px]">PRODUCTO
                            </p>
                        </div>
                        <div class="w-full flex items-center justify-center  h-[30px]">
                            <p class="text-center font-[MuseoSans] text-[11px] ">RECEPCIONES MENSUALES
                                {{ purchaseProcess === 5 ? 'EN DÓLARES' : 'EN UNIDADES' }}
                            </p>
                        </div>
                    </div>
                    <template v-for="(prod, index) in products" :key="index">
                        <!-- Table content -->
                        <div
                            class="grid grid-cols-[18%_82%] hover:bg-gray-200 max-w-full w-full bg-white border-l border-gray-600 bg-opacity-80 min-w-[900px]">
                            <div
                                class="w-full max-h-[131px] overflow-y-auto flex items-center justify-center border-b border-r border-gray-600">
                                <p class="text-left font-[MuseoSans] text-[11px] font-bold p-1 h-full">{{ prod.producto }}
                                </p>
                            </div>
                            <div class="w-full flex-row items-center justify-center h-full">
                                <div class="w-full">
                                    <div class="w-full grid grid-cols-6">
                                        <template v-for="(month, index2) in prod.meses" :key="index2">
                                            <div v-if="index2 <= 5"
                                                class="w-full flex-row items-center justify-center border-r border-b border-gray-600">
                                                <p class="font-[MuseoSans] text-[12px] text-center underline">
                                                    {{
                                                        month.mes }}</p>
                                                <div class="grid grid-cols-2">
                                                    <p class="font-[MuseoSans] text-gray-500 text-[11px] text-center">
                                                        Contratado</p>
                                                    <p class="font-[MuseoSans] text-gray-500 text-[11px] text-center">
                                                        Recibido </p>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                    <div class="w-full grid grid-cols-6 h-[30px] border-b border-gray-600">
                                        <template v-for="(month, index3) in prod.meses" :key="index3">
                                            <div v-if="index3 <= 5" class="w-full grid grid-cols-2">
                                                <div
                                                    class="w-full flex items-center justify-center border-r border-gray-600">
                                                    <p class="font-[MuseoSans] text-[11px] text-center">
                                                        {{ month.res.cant_pa }} </p>
                                                </div>
                                                <div
                                                    class="w-full flex items-center justify-center border-r border-gray-600">
                                                    <p class="font-[MuseoSans] text-[11px] text-center">
                                                        {{ month.res.cant_rec }} </p>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                                <div class="w-full">
                                    <div class="w-full grid grid-cols-6">
                                        <template v-for="(month, index2) in prod.meses" :key="index2">
                                            <div v-if="index2 >= 6"
                                                class="w-full flex-row items-center justify-center border-r border-b border-gray-600">
                                                <p class="font-[MuseoSans] text-[12px] text-center underline">
                                                    {{
                                                        month.mes }}</p>
                                                <div class="grid grid-cols-2">
                                                    <p class="font-[MuseoSans] text-gray-500 text-[11px] text-center">
                                                        Contratado</p>
                                                    <p class="font-[MuseoSans] text-gray-500 text-[11px] text-center">
                                                        Recibido </p>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                    <div class="w-full grid grid-cols-6 h-[30px] border-b border-gray-600">
                                        <template v-for="(month, index3) in prod.meses" :key="index3">
                                            <div v-if="index3 >= 6" class="w-full grid grid-cols-2">
                                                <div
                                                    class="w-full flex items-center justify-center border-r border-gray-600">
                                                    <p class="font-[MuseoSans] text-[11px] text-center">
                                                        {{ month.res.cant_pa }} </p>
                                                </div>
                                                <div
                                                    class="w-full flex items-center justify-center border-r border-gray-600">
                                                    <p class="font-[MuseoSans] text-[11px] text-center">
                                                        {{ month.res.cant_rec }} </p>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
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

        const firstSemester = ref([
            { id: 1, nombre: 'Enero' },
            { id: 2, nombre: 'Febrero' },
            { id: 3, nombre: 'Marzo' },
            { id: 4, nombre: 'Abril' },
            { id: 5, nombre: 'Mayo' },
            { id: 6, nombre: 'Junio' },
        ])

        const secondSemester = ref([
            { id: 7, nombre: 'Julio' },
            { id: 8, nombre: 'Agosto' },
            { id: 9, nombre: 'Septiembre' },
            { id: 10, nombre: 'Octubre' },
            { id: 11, nombre: 'Noviembre' },
            { id: 12, nombre: 'Diciembre' },
        ])

        const {
            isLoadingReport, reportInfo, errors, contracts, filterItems, products,
            contractName, purchaseProcess, testMap,
            getOption, changeContract, getContractTrackingReport
        } = useReporteSeguimientoContrato(context);

        return {
            permits, isLoadingReport, reportInfo, errors, contracts, filterItems,
            products, moment, contractName, firstSemester, secondSemester, purchaseProcess,
            testMap,
            getOption, changeContract, getContractTrackingReport
        };
    },
}

</script>

<style>
.dp__theme_light {
    --dp-primary-color: rgba(0, 28, 72, 0.8);
}

.dp__input_wrap {
    height: 30px;
}
</style>