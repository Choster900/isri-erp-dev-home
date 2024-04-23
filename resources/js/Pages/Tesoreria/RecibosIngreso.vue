<template>
    <Head title="Proceso - Ingreso" />
    <AppLayoutVue nameSubModule="Tesoreria - Recibos de Ingreso">
        <div v-if="isLoadingTop"
            class="fixed top-0 left-0 right-0 bottom-0 flex items-center justify-center bg-gray-800 bg-opacity-50 z-50">
            <div role="status" class="flex items-center">
                <svg aria-hidden="true" class="w-8 h-8 mr-2 text-gray-200 animate-spin  fill-blue-800"
                    viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                        fill="currentColor" />
                    <path
                        d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                        fill="currentFill" />
                </svg>
                <div class="bg-gray-200 rounded-lg p-1 font-semibold">
                    <span class="text-blue-800">CARGANDO...</span>
                </div>
            </div>
        </div>
        <div class="sm:flex sm:justify-end sm:items-center mb-2">
            <div class="grid grid-flow-col sm:auto-cols-max sm:justify-end gap-2">
                <GeneralButton @click="show_modal_receipt = true; incomeReceiptId=0;" v-if="permits.insertar == 1"
                    color="bg-green-700  hover:bg-green-800" text="Agregar Recibo" icon="add" />
            </div>
        </div>
        <div class="bg-white shadow-lg rounded-sm border border-slate-200 relative">
            <header class="px-5 py-4">
                <div class="mb-4 md:flex flex-row justify-items-start">
                    <div class="mb-4 md:mr-2 md:mb-0 basis-1/4">
                        <div class="relative flex h-8 w-full flex-row-reverse div-multiselect">
                            <Multiselect v-model="tableData.length" @select="getDataToShow()" :options="perPage"
                                @deselect=" tableData.length = 5; getDataToShow()"
                                @clear="tableData.length = 5; getDataToShow()" :searchable="true"
                                placeholder="Cantidad a mostrar" />
                            <LabelToInput icon="list2" />
                        </div>
                    </div>
                    <h2 class="font-semibold text-slate-800 pt-1">Recibos Ingreso: <span
                            class="text-slate-400 font-medium">{{
                                tableData.total
                            }}</span></h2>
                </div>
            </header>

            <div class="overflow-x-auto">
                <datatable :columns="columns" :sortKey="sortKey" :sortOrders="sortOrders" :searchButton="true"
                    @sort="sortBy" @datos-enviados="handleData($event)" @execute-search="getDataToShow()">
                    <tbody class="text-sm divide-y divide-slate-200" v-if="!isLoadinRequest">
                        <tr v-for="receipt in dataToShow" :key="receipt.id_recibo_ingreso">
                            <td class="px-2 first:pl-5 last:pr-5 td-data-table">
                                <div class="font-medium text-slate-800 text-center">{{ receipt.numero_recibo_ingreso }}
                                </div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5 td-data-table">
                                <div class="font-medium text-slate-800 ellipsis text-center">
                                    {{ moment(receipt.fecha_recibo_ingreso).format('DD/MM/YYYY') }}
                                </div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5 td-data-table">
                                <div class="font-medium text-slate-800 ellipsis text-center">{{
                                    receipt.cliente_recibo_ingreso }}</div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5 td-data-table">
                                <div class="font-medium text-slate-800 ellipsis text-center">{{
                                    receipt.descripcion_recibo_ingreso }}
                                </div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5 td-data-table">
                                <div class="font-medium text-slate-800 text-center">{{ receipt.id_ccta_presupuestal }}</div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5 td-data-table">
                                <div class="font-medium text-slate-800 text-center">
                                    {{ receipt.monto_recibo_ingreso ? '$' + receipt.monto_recibo_ingreso : '' }}
                                </div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5 td-data-table">
                                <div class="font-medium text-slate-800 text-center">
                                    <div v-if="(receipt.estado_recibo_ingreso == 1)"
                                        class="inline-flex font-medium rounded-full text-center px-2.5 py-0.5 bg-emerald-100 text-emerald-500">
                                        Activo
                                    </div>
                                    <div v-else
                                        class="inline-flex font-medium rounded-full text-center px-2.5 py-0.5 bg-rose-100 text-rose-600">
                                        Inactivo
                                    </div>
                                </div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5 td-data-table">
                                <div class="flex justify-center items-center">


                                    <DropDownOptions>
                                        <div class="flex hover:bg-gray-100 py-1 px-2 rounded cursor-pointer"
                                            @click="show_modal_receipt = true; incomeReceiptId = receipt.id_recibo_ingreso;"
                                            v-if="permits.actualizar == 1 && receipt.estado_recibo_ingreso == 1">
                                            <div class="w-8 text-green-900">
                                                <span class="text-xs">

                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                        stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M13.5 16.875h3.375m0 0h3.375m-3.375 0V13.5m0 3.375v3.375M6 10.5h2.25a2.25 2.25 0 002.25-2.25V6a2.25 2.25 0 00-2.25-2.25H6A2.25 2.25 0 003.75 6v2.25A2.25 2.25 0 006 10.5zm0 9.75h2.25A2.25 2.25 0 0010.5 18v-2.25a2.25 2.25 0 00-2.25-2.25H6a2.25 2.25 0 00-2.25 2.25V18A2.25 2.25 0 006 20.25zm9.75-9.75H18a2.25 2.25 0 002.25-2.25V6A2.25 2.25 0 0018 3.75h-2.25A2.25 2.25 0 0013.5 6v2.25a2.25 2.25 0 002.25 2.25z" />
                                                    </svg>
                                                </span>
                                            </div>
                                            <div class="font-semibold">Editar</div>
                                        </div>

                                        <div @click="changeStatus(receipt.id_recibo_ingreso, receipt.estado_recibo_ingreso)"
                                            v-if="permits.eliminar == 1"
                                            class="flex hover:bg-gray-100 py-1 px-2 rounded cursor-pointer">
                                            <div class="ml-0.5 mr-2 w-5 h-5"
                                                :class="receipt.estado_recibo_ingreso == 1 ? 'text-red-800' : 'text-green-800'">
                                                <span class="text-xs ">
                                                    <icon-m
                                                        :iconName="receipt.estado_recibo_ingreso == 1 ? 'desactivate' : 'activate'">
                                                    </icon-m>
                                                </span>
                                            </div>
                                            <div class="font-semibold">
                                                {{ receipt.estado_recibo_ingreso == 1 ? 'Desactivar' : 'Activar' }}
                                            </div>
                                        </div>
                                        <div class="flex hover:bg-gray-100 py-1 px-2 rounded cursor-pointer"
                                            v-if="receipt.estado_recibo_ingreso == 1" @click="view_receipt=true;incomeReceiptId=receipt.id_recibo_ingreso;">
                                            <div class="w-8 text-blue-900">
                                                <span class="text-xs">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                        stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    </svg>
                                                </span>
                                            </div>
                                            <div class="font-semibold">Ver</div>
                                        </div>
                                    </DropDownOptions>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                    <tbody v-else>
                        <tr>
                            <td colspan="8" class="text-center">
                                <img src="../../../img/IsSearching.gif" alt="" class="w-60 h-60 mx-auto">
                                <h1 class="font-medium text-xl mt-4">Cargando!!!</h1>
                                <p class="text-sm text-gray-600 mt-2 pb-10">Por favor espera un momento mientras se carga la
                                    información.</p>
                            </td>
                        </tr>
                    </tbody>
                    <tbody v-if="emptyObject && !isLoadinRequest">
                        <tr>
                            <td colspan="8" class="text-center">
                                <img src="../../../img/NoData.gif" alt="" class="w-60 h-60 mx-auto">
                                <h1 class="font-medium text-xl mt-4">No se encontraron resultados!</h1>
                                <p class="text-sm text-gray-600 mt-2 pb-10">Parece que no hay registros disponibles en este
                                    momento.</p>
                            </td>
                        </tr>
                    </tbody>
                </datatable>

            </div>
        </div>

        <div v-if="!emptyObject" class="px-6 py-8 bg-white shadow-lg rounded-sm border-slate-200 relative">
            <div>
                <nav class="flex justify-between" role="navigation" aria-label="Navigation">
                    <div class="grow text-center">
                        <ul class="inline-flex text-sm font-medium -space-x-px">
                            <li v-for="link in links" :key="link.label">
                                <span v-if="(link.label == 'Anterior')"
                                    :class="(link.active ? 'inline-flex items-center justify-center rounded-full leading-5 px-2 py-2 bg-white border border-slate-200 text-indigo-500 shadow-sm' : 'inline-flex items-center justify-center leading-5 px-2 py-2 text-slate-600 hover:text-indigo-500 border border-transparent')">

                                    <div class="flex-1 text-right ml-2">
                                        <a @click="link.url ? getDataToShow(link.url) : ''" class=" btn bg-white border-slate-200 hover:border-slate-300 cursor-pointer
                                            text-indigo-500">
                                            &lt;-<span class="hidden sm:inline">&nbsp;Anterior</span>
                                        </a>
                                    </div>
                                </span>
                                <span v-else-if="(link.label == 'Siguiente')"
                                    :class="(link.active ? 'inline-flex items-center justify-center rounded-full leading-5 px-2 py-2 bg-white border border-slate-200 text-indigo-500 shadow-sm' : 'inline-flex items-center justify-center leading-5 px-2 py-2 text-slate-600 hover:text-indigo-500 border border-transparent')">
                                    <div class="flex-1 text-right ml-2">
                                        <a @click="link.url ? getDataToShow(link.url) : ''" class=" btn bg-white border-slate-200 hover:border-slate-300 cursor-pointer
                                            text-indigo-500">
                                            <span class="hidden sm:inline">Siguiente&nbsp;</span>-&gt;
                                        </a>
                                    </div>
                                </span>
                                <span class="cursor-pointer" v-else
                                    :class="(link.active ? 'inline-flex items-center justify-center rounded-full leading-5 px-2 py-2 bg-white border border-slate-200 text-indigo-500 shadow-sm' : 'inline-flex items-center justify-center leading-5 px-2 py-2 text-slate-600 hover:text-indigo-500 border border-transparent')"><span
                                        class=" w-5" @click="getDataToShow(link.url)">{{ link.label }}</span>
                                </span>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>

        <modal-income-receipt-vue v-if="show_modal_receipt" :show_modal_receipt="show_modal_receipt" :incomeReceiptId="incomeReceiptId"
            :budget_accounts="budget_accounts" :treasury_clerk="treasury_clerk" @cerrar-modal="show_modal_receipt = false"
            @get-table="tableData.column = -1; getDataToShow(tableData.currentPage)" />

        <modal-receipt-format-vue v-if="view_receipt" :view_receipt="view_receipt" :incomeReceiptId="incomeReceiptId"
            @cerrar-modal="view_receipt = false" />

    </AppLayoutVue>
</template>

<script>
import { Head } from "@inertiajs/vue3";
import AppLayoutVue from "@/Layouts/AppLayout.vue";
import Datatable from "@/Components-ISRI/Datatable.vue";
import ModalIncomeReceiptVue from '@/Components-ISRI/Tesoreria/ModalIncomeReceipt.vue';
import ModalReceiptFormatVue from '@/Components-ISRI/Tesoreria/ModalReceiptFormat.vue';
import IconM from "@/Components-ISRI/ComponentsToForms/IconM.vue";

import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
import moment from 'moment';

import { usePermissions } from '@/Composables/General/usePermissions.js';
import { useToDataTable } from '@/Composables/General/useToDataTable.js';
import { ref, toRefs } from 'vue';


export default {
    components: { Head, AppLayoutVue, Datatable, ModalIncomeReceiptVue, ModalReceiptFormatVue, IconM },
    props: {
        menu: {
            type: Object,
            default: {}
        },
    },
    setup(props, context) {
        const { menu } = toRefs(props);
        const permits = usePermissions(menu.value, window.location.pathname);

        const incomeReceiptId = ref(0)
        const show_modal_receipt = ref(false)
        const view_receipt = ref(false)
        const columns = [
            { width: "11%", label: "Numero", name: "numero_recibo_ingreso", type: "text" },
            { width: "6%", label: "Fecha", name: "fecha_recibo_ingreso", type: "date" },
            { width: "25%", label: "Cliente", name: "cliente_recibo_ingreso", type: "text" },
            { width: "25%", label: "Descripcion", name: "descripcion_recibo_ingreso", type: "text" },
            { width: "6%", label: "Especifico", name: "id_ccta_presupuestal", type: "text" },
            { width: "9%", label: "Monto", name: "monto_recibo_ingreso", type: "text" },
            {
                width: "10%", label: "Estado", name: "estado_recibo_ingreso", type: "select",
                options: [
                    { value: "1", label: "Activo" },
                    { value: "0", label: "Inactivo" }
                ]
            },
            { width: "7%", label: "Acciones", name: "Acciones" },
        ];
        const requestUrl = "/recibos-ingreso"
        const columntToSort = "id_recibo_ingreso"
        const initialCol = -1 // Opcional, el composable recibe 0 por defecto
        const dir = 'desc'
        const {
            dataToShow,
            tableData, perPage,
            links, sortKey,
            sortOrders,
            isLoadinRequest,
            isLoadingTop,
            emptyObject,
            getDataToShow, handleData, sortBy, changeStatusElement
        } = useToDataTable(columns, requestUrl, columntToSort, dir, initialCol)

        const changeStatus = async (id, status) => {
            await changeStatusElement(id, status, "/change-state-income-receipt")
        }

        return {
            permits, dataToShow, tableData,
            perPage, links, sortKey, isLoadingTop,
            sortOrders, sortBy, incomeReceiptId,
            handleData, isLoadinRequest,
            getDataToShow, changeStatus, show_modal_receipt, view_receipt,
            emptyObject, columns, moment
        }
    }
}
</script>

<style>
.td-data-table {
    max-width: 100px;
    white-space: nowrap;
    height: 50px;
}

.ellipsis {
    overflow: hidden;
    text-overflow: ellipsis;
}
</style>
