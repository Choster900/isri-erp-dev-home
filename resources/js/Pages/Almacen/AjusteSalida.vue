<template>

    <Head title="Kardex - Ajuste de salida" />
    <AppLayoutVue nameSubModule="Almacen - Ajuste de salida">
        <div v-if="isLoadingTop"
            class="fixed top-0 left-0 right-0 bottom-0 flex items-center justify-center bg-gray-900 opacity-75 z-50">
            <div class="flex items-center justify-center my-4">
                <img src="../../../img/loader-spinner.gif" alt="" class="w-8 h-8">
                <h1 class="ml-4 font-medium text-xl text-white font-[Roboto]">Procesando...</h1>
            </div>
        </div>
        <div class="sm:flex sm:justify-end sm:items-center mb-2">
            <div class="grid grid-flow-col sm:auto-cols-max sm:justify-end gap-2">
                <GeneralButton @click="showModalOutgoingAdjustment = true; objId = 0;" v-if="permits.insertar == 1"
                    color="bg-green-700  hover:bg-green-800" text="Crear Ajuste" icon="add" />
            </div>
        </div>

        <div class="bg-white shadow-lg rounded-sm border border-slate-200 relative">
            <header class="px-5 py-4">
                <div class="mb-4 md:flex flex-row justify-items-start">
                    <div class="mb-4 md:mr-2 md:mb-0 basis-1/4">
                        <div class="relative flex h-8 w-full flex-row-reverse div-multiselect">
                            <Multiselect v-model="tableData.length" @select="getDataToShow()" :options="perPage"
                                :searchable="true" placeholder="Cantidad a mostrar"
                                @deselect=" tableData.length = 5; getDataToShow()"
                                @clear="tableData.length = 5; getDataToShow()" />
                            <LabelToInput icon="list2" />
                        </div>
                    </div>
                    <h2 class="font-semibold text-slate-800 pt-1">Ajustes de salida: <span
                            class="text-slate-400 font-medium">{{
            tableData.total
        }}</span></h2>
                </div>
            </header>
            <div class="overflow-x-auto">
                <datatable :columns="columns" :sortKey="sortKey" :sortOrders="sortOrders" :searchButton="true"
                    :sortIcons="true" :staticSelect="false" @sort="sortBy" @datos-enviados="handleData($event)"
                    @execute-search="getDataToShow()">
                    <tbody v-if="!isLoadinRequest" class="text-sm divide-y divide-slate-200">
                        <tr v-for="obj in dataToShow" :key="obj.id_requerimiento"
                            class="hover:bg-gray-200">
                            <td class="px-2 first:pl-5 last:pr-5">
                                <div class="font-medium text-slate-800 flex items-center justify-center min-h-[40px]">
                                    {{ obj.id_requerimiento }}
                                </div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5  whitespace-nowrap">
                                <div class="font-medium text-slate-800 text-center">
                                    {{ obj.centro_atencion.codigo_centro_atencion }}
                                </div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5  whitespace-nowrap">
                                <div class="font-medium text-slate-800 text-center">
                                    {{ obj.motivo_ajuste.nombre_motivo_ajuste }}
                                </div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5">
                                <div class="font-medium text-slate-800 flex items-center justify-center text-center min-h-[40px]">
                                    {{ obj.proyecto_financiado.codigo_proy_financiado }}
                                </div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5">
                                <div class="font-medium text-slate-800 flex items-center justify-center text-cente min-h-[40px]">
                                    {{ obj.num_requerimiento }}
                                </div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5  whitespace-nowrap">
                                <div class="font-medium text-slate-800 flex items-center justify-center text-cente min-h-[40px]">
                                    {{ moment(obj.fecha_requerimiento, 'YYYY/MM/DD').format('D-MMM-YYYY') }}
                                </div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5  whitespace-nowrap">
                                <div
                                    class="font-bold text-[13px] text-slate-800 flex items-center justify-center text-cente min-h-[40px]">
                                    <div :class="{
            ' text-emerald-600 bg-emerald-100 ': obj.id_estado_req == 1,
            ' text-blue-600 bg-blue-100 ': obj.id_estado_req == 2,
            ' text-red-600 bg-red-100 ': obj.id_estado_req == 4
        }" class="inline-flex font-medium rounded-full px-2.5 py-1 ">
                                        <svg class="w-3 h-3 inline-flex mr-2 my-auto">
                                            <circle class="opacity-80" cx="50%" cy="50%" r="50%" fill="currentColor" />
                                        </svg>
                                        {{ obj.estado_requerimiento.nombre_estado_req }}
                                    </div>
                                </div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5  whitespace-nowrap w-px">
                                <div class="space-x-1 text-center">
                                    <DropDownOptions>
                                        <!-- <div v-if="permits.ejecutar === 1 && obj.id_estado_req == 1"
                                            @click="sendShortageAdjustment(obj.id_requerimiento)"
                                            class="flex hover:bg-gray-100 py-1 px-2 rounded cursor-pointer">
                                            <div class="text-lime-700 w-[24px] h-[24px] mr-1">
                                                <icon-m :iconName="'clipboard-arrow'"></icon-m>
                                            </div>
                                            <div class="font-semibold pt-0.5">Kardex</div>
                                        </div> -->
                                        <div @click="showModalShortageAdjustment = true; objId = obj.id_requerimiento"
                                            v-if="permits.actualizar === 1 && obj.id_estado_req == 1"
                                            class="flex hover:bg-gray-100 py-1 px-2 rounded cursor-pointer">
                                            <div class="text-orange-700 w-[22px] h-[22px] mr-1.5 ml-0.5">
                                                <icon-m :iconName="'editM'"></icon-m>
                                            </div>
                                            <div class="font-semibold pt-0.5">Editar</div>
                                        </div>
                                        <!-- <div @click="showModalShortageAdjustment = true; objId = obj.id_requerimiento"
                                            v-if="obj.id_estado_req == 4 || obj.id_estado_req == 2"
                                            class="flex hover:bg-gray-100 py-1 px-2 rounded cursor-pointer">
                                            <div class="text-blue-800 w-[25px] h-[25px] mr-2">
                                                <icon-m :iconName="'see'"></icon-m>
                                            </div>
                                            <div class="font-semibold pt-0.5">Ver</div>
                                        </div>
                                        <div @click="changeStatusElement(obj.id_requerimiento, obj.id_estado_req)"
                                            v-if="permits.eliminar === 1 && obj.id_estado_req == 1"
                                            class="flex hover:bg-gray-100 py-1 px-2 rounded cursor-pointer">
                                            <div class="text-red-700 w-[24px] h-[24px] mr-1">
                                                <icon-m :iconName="'trash'"></icon-m>
                                            </div>
                                            <div class="font-semibold pt-0.5">Eliminar</div>
                                        </div> -->
                                    </DropDownOptions>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                    <tbody v-else>
                        <tr>
                            <td colspan="7" class="text-center">
                                <img src="../../../img/IsSearching.gif" alt="" class="w-60 h-60 mx-auto">
                                <h1 class="font-medium text-xl mt-4">Cargando!!!</h1>
                                <p class="text-sm text-gray-600 mt-2 pb-10">Por favor espera un momento mientras se
                                    carga la
                                    información.</p>
                            </td>
                        </tr>
                    </tbody>
                    <tbody v-if="emptyObject && !isLoadinRequest">
                        <tr>
                            <td colspan="7" class="text-center">
                                <img src="../../../img/NoData.gif" alt="" class="w-60 h-60 mx-auto">
                                <h1 class="font-medium text-xl mt-4">No se encontraron resultados!</h1>
                                <p class="text-sm text-gray-600 mt-2 pb-10">Parece que no hay registros disponibles en
                                    este
                                    momento.</p>
                            </td>
                        </tr>
                    </tbody>
                </datatable>

            </div>

        </div>
        
        <pagination :emptyObject="emptyObject" :links="links" @get-data="getDataToShow" />

        <modal-ajuste-salida-vue v-if="showModalOutgoingAdjustment" :showModalOutgoingAdjustment="showModalOutgoingAdjustment" :objId="objId"
            @cerrar-modal="showModalOutgoingAdjustment = false" @get-table="getDataToShow(tableData.currentPage)" />

    </AppLayoutVue>
</template>

<script>
import { Head } from "@inertiajs/vue3";
import AppLayoutVue from "@/Layouts/AppLayout.vue";
import Datatable from "@/Components-ISRI/Datatable.vue";
import Pagination from "@/Components-ISRI/Pagination.vue";
import IconM from "@/Components-ISRI/ComponentsToForms/IconM.vue";
import ModalAjusteSalidaVue from '@/Components-ISRI/Almacen/AjusteSalida/ModalAjusteSalida.vue';

import { localeData } from 'moment_spanish_locale';
import moment from 'moment';
moment.locale('es', localeData)

import { ref, toRefs, inject } from 'vue';
import { usePermissions } from '@/Composables/General/usePermissions.js';
import { useToDataTable } from '@/Composables/General/useToDataTable.js';
import { useEnviarAjusteEntrada } from '@/Composables/Almacen/AjusteEntrada/useEnviarAjusteEntrada.js';

export default {
    components: { Head, AppLayoutVue, Datatable, IconM, ModalAjusteSalidaVue, Pagination },
    props: {
        menu: {
            type: Object,
            default: {}
        },
    },
    setup(props, context) {
        const { menu } = toRefs(props);
        const permits = usePermissions(menu.value, window.location.pathname);

        const showModalOutgoingAdjustment = ref(false)
        const showModalSendDonation = ref(false)

        const objId = ref(0)

        const columns = [
            { width: "10%", label: "Id", name: "id_requerimiento", type: "text" },
            {
                width: "10%", label: "Centro", name: "id_centro_atencion", type: "select",
                options: [
                    { value: "1", label: "ADMON" },
                    { value: "2", label: "CAA" },
                    { value: "3", label: "CAL" },
                    { value: "4", label: "CALE" },
                    { value: "5", label: "CRC" },
                    { value: "6", label: "UCE" },
                    { value: "7", label: "CRINA" },
                    { value: "8", label: "CRIO" },
                    { value: "9", label: "CRIOR" },
                    { value: "10", label: "CRP" },
                ]
            },
            { width: "20%", label: "motivo", name: "motivo", type: "text" },
            {
                width: "10%", label: "Fondo", name: "id_proy_financiado", type: "select",
                options: [
                    { value: "1", label: "FG" },
                    { value: "2", label: "FCMF" },
                    { value: "3", label: "RP" },
                    { value: "4", label: "D" },
                ]
            },
            { width: "13%", label: "Numero", name: "num_requerimiento", type: "text" },
            { width: "15%", label: "Fecha", name: "fecha_requerimiento", type: "date" },
            {
                width: "12%", label: "Estado", name: "id_estado_req", type: "select",
                options: [
                    { value: "1", label: "CREADO" },
                    { value: "2", label: "APROBADO" },
                    { value: "4", label: "ELIMINADO" }
                ]
            },
            { width: "10%", label: "Acciones", name: "Acciones" },
        ];
        const requestUrl = "/ajuste-salida"
        const columntToSort = "id_requerimiento"
        const dir = 'desc'

        const {
            dataToShow,
            tableData, perPage,
            links, sortKey,
            sortOrders,
            isLoadinRequest,
            emptyObject,
            getDataToShow, handleData, sortBy
        } = useToDataTable(columns, requestUrl, columntToSort, dir)

        const {
            isLoadingTop,
            changeStatusElement, sendShortageAdjustment
        } = useEnviarAjusteEntrada(context, getDataToShow, tableData.value);


        return {
            permits, dataToShow, showModalOutgoingAdjustment, tableData, perPage, objId, showModalSendDonation,
            links, sortKey, sortOrders, isLoadinRequest, isLoadingTop, emptyObject, columns,
            getDataToShow, handleData, sortBy, changeStatusElement, sendShortageAdjustment, moment
        };
    },
}
</script>

<style></style>