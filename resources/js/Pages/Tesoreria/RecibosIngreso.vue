<script setup>
import Modal from "@/Components/Modal.vue";
import { Head } from "@inertiajs/vue3";
import AppLayoutVue from "@/Layouts/AppLayout.vue";
import Datatable from "@/Components-ISRI/Datatable.vue";
import ModalVue from "@/Components-ISRI/AllModal/BasicModal.vue";
import ModalIncomeReceiptVue from '@/Components-ISRI/Tesoreria/ModalIncomeReceipt.vue';
import ModalReceiptFormatVue from '@/Components-ISRI/Tesoreria/ModalReceiptFormat.vue';
import moment from 'moment';

import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';

import axios from 'axios';

</script>

<template>
    <Head title="Proceso - Ingreso" />
    <AppLayoutVue nameSubModule="Tesoreria - Recibos de Ingreso">
        <div class="sm:flex sm:justify-end sm:items-center mb-2">
            <div class="grid grid-flow-col sm:auto-cols-max sm:justify-end gap-2">
                <GeneralButton @click="addIncomeConcept()" v-if="permits.insertar == 1"
                    color="bg-green-700  hover:bg-green-800" text="Agregar Recibo" icon="add" />
            </div>
        </div>
        <div class="bg-white shadow-lg rounded-sm border border-slate-200 relative">
            <header class="px-5 py-4">
                <div class="mb-4 md:flex flex-row justify-items-start">
                    <div class="mb-4 md:mr-2 md:mb-0 basis-1/4">
                        <div class="relative flex h-8 w-full flex-row-reverse div-multiselect">
                            <Multiselect v-model="tableData.length" @select="getIncomeReceipts()" :options="perPage"
                                :searchable="true" placeholder="Cantidad a mostrar" />
                            <LabelToInput icon="list2" />
                        </div>
                    </div>
                    <h2 class="font-semibold text-slate-800 pt-1">Total Recibos Ingreso <span
                            class="text-slate-400 font-medium">{{
                                tableData.total
                            }}</span></h2>
                </div>
            </header>

            <div class="overflow-x-auto">
                <datatable :columns="columns" :sortKey="sortKey" :sortOrders="sortOrders" @sort="sortBy"
                    @datos-enviados="handleData($event)">
                    <tbody class="text-sm divide-y divide-slate-200">
                        <tr v-for="receipt in income_receipts" :key="receipt.id_recibo_ingreso">
                            <td class="px-2 first:pl-5 last:pr-5 td-data-table">
                                <div class="font-medium text-slate-800 text-center">{{ receipt.numero_recibo_ingreso }}
                                </div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5 td-data-table">
                                <div class="font-medium text-slate-800 ellipsis text-center">
                                    {{ formatearFecha(receipt.fecha_recibo_ingreso) }}
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
                                            @click="editIncomeReceipt(receipt)"
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
                                        <div class="flex hover:bg-gray-100 py-1 px-2 rounded cursor-pointer"
                                            @click="changeStateIncomeReceipt(receipt.id_recibo_ingreso, receipt.numero_recibo_ingreso, receipt.estado_recibo_ingreso)"
                                            v-if="permits.eliminar == 1">
                                            <div class="w-8 text-red-900">
                                                <span class="text-xs">
                                                    <svg width="25px" height="25px" viewBox="0 0 48.00 48.00" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round"
                                                            stroke-linejoin="round"></g>
                                                        <g id="SVGRepo_iconCarrier">
                                                            <rect width="48" height="48" fill="white" fill-opacity="0.01">
                                                            </rect>
                                                            <path
                                                                d="M34 6.67564C39.978 10.1337 44 16.5972 44 24M34 6.67564V14M34 6.67564H41.3244M41.3244 34C37.8663 39.978 31.4028 44 24 44M41.3244 34H34M41.3244 34V41.3244M14 41.3244C8.02199 37.8663 4 31.4028 4 24M14 41.3244V34M14 41.3244H6.67564M6.67564 14C10.1337 8.02199 16.5972 4 24 4M6.67564 14H14M6.67564 14V6.67564"
                                                                stroke="#ff0000" stroke-width="2.16" stroke-linecap="round"
                                                                stroke-linejoin="round"></path>
                                                            <path
                                                                d="M41.3244 34C37.8663 39.978 31.4028 44 24 44M41.3244 34H34M41.3244 34V41.3244"
                                                                stroke="#ff0000" stroke-width="2.16" stroke-linecap="round"
                                                                stroke-linejoin="round"></path>
                                                            <path
                                                                d="M14 41.3244C8.02199 37.8663 4 31.4028 4 24M14 41.3244V34M14 41.3244H6.67564"
                                                                stroke="#ff0000" stroke-width="2.16" stroke-linecap="round"
                                                                stroke-linejoin="round"></path>
                                                            <path
                                                                d="M6.67566 14C10.1338 8.02199 16.5972 4 24 4M6.67566 14H14M6.67566 14V6.67564"
                                                                stroke="#ff0000" stroke-width="2.16" stroke-linecap="round"
                                                                stroke-linejoin="round"></path>
                                                            <path
                                                                d="M34 6.67578C39.978 10.1339 44 16.5973 44 24.0001M34 6.67578V14.0001M34 6.67578H41.3244"
                                                                stroke="#ff0000" stroke-width="2.16" stroke-linecap="round"
                                                                stroke-linejoin="round"></path>
                                                        </g>
                                                    </svg>
                                                </span>
                                            </div>
                                            <div class="font-semibold">
                                                {{ receipt.estado_recibo_ingreso ? 'Desactivar' : 'Activar' }}
                                            </div>
                                        </div>
                                        <div class="flex hover:bg-gray-100 py-1 px-2 rounded cursor-pointer"
                                            v-if="receipt.estado_recibo_ingreso == 1" @click="viewReceipt(receipt)">
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
                </datatable>

            </div>
        </div>

        <div class="px-6 py-8 bg-white shadow-lg rounded-sm border-slate-200 relative">
            <div>
                <nav class="flex justify-between" role="navigation" aria-label="Navigation">
                    <div class="grow text-center">
                        <ul class="inline-flex text-sm font-medium -space-x-px">
                            <li v-for="link in links" :key="link.label">
                                <span v-if="(link.label == 'Anterior')"
                                    :class="(link.active ? 'inline-flex items-center justify-center rounded-full leading-5 px-2 py-2 bg-white border border-slate-200 text-indigo-500 shadow-sm' : 'inline-flex items-center justify-center leading-5 px-2 py-2 text-slate-600 hover:text-indigo-500 border border-transparent')">

                                    <div class="flex-1 text-right ml-2">
                                        <a @click="getIncomeReceipts(link.url)" class=" btn bg-white border-slate-200 hover:border-slate-300 cursor-pointer
                                            text-indigo-500">
                                            &lt;-<span class="hidden sm:inline">&nbsp;Anterior</span>
                                        </a>
                                    </div>
                                </span>
                                <span v-else-if="(link.label == 'Siguiente')"
                                    :class="(link.active ? 'inline-flex items-center justify-center rounded-full leading-5 px-2 py-2 bg-white border border-slate-200 text-indigo-500 shadow-sm' : 'inline-flex items-center justify-center leading-5 px-2 py-2 text-slate-600 hover:text-indigo-500 border border-transparent')">
                                    <div class="flex-1 text-right ml-2">
                                        <a @click="getIncomeReceipts(link.url)" class=" btn bg-white border-slate-200 hover:border-slate-300 cursor-pointer
                                            text-indigo-500">
                                            <span class="hidden sm:inline">Siguiente&nbsp;</span>-&gt;
                                        </a>
                                    </div>
                                </span>
                                <span class="cursor-pointer" v-else
                                    :class="(link.active ? 'inline-flex items-center justify-center rounded-full leading-5 px-2 py-2 bg-white border border-slate-200 text-indigo-500 shadow-sm' : 'inline-flex items-center justify-center leading-5 px-2 py-2 text-slate-600 hover:text-indigo-500 border border-transparent')"><span
                                        class=" w-5" @click="getIncomeReceipts(link.url)">{{ link.label }}</span>
                                </span>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>

        <ModalIncomeReceiptVue :show_modal_receipt="show_modal_receipt" :modal_data="modal_data"
            :budget_accounts="budget_accounts" :treasury_clerk="treasury_clerk" @cerrar-modal="show_modal_receipt = false"
            @get-table="getIncomeReceipts(tableData.currentPage)" />

        <ModalReceiptFormatVue :view_receipt="view_receipt" :receipt_to_print="receipt_to_print"
            @cerrar-modal="view_receipt = false" />

    </AppLayoutVue>
</template>

<script>
export default {
    created() {
        this.getIncomeReceipts()
        this.getPermits()
        this.getModalReceiptSelects()
    },
    data() {
        let sortOrders = {};
        let columns = [
            { width: "11%", label: "Numero", name: "numero_recibo_ingreso", type: "text" },
            { width: "6%", label: "Fecha", name: "fecha_recibo_ingreso", type: "date" },
            { width: "25%", label: "Cliente", name: "cliente_recibo_ingreso", type: "text" },
            { width: "25%", label: "Descripcion", name: "descripcion_recibo_ingreso", type: "text" },
            { width: "5%", label: "Especifico", name: "id_ccta_presupuestal", type: "text" },
            { width: "5%", label: "Monto", name: "monto_recibo_ingreso", type: "text" },
            {
                width: "5%", label: "Estado", name: "estado_recibo_ingreso", type: "select",
                options: [
                    { value: "1", label: "Activo" },
                    { value: "0", label: "Inactivo" }
                ]
            },
            { width: "18%", label: "Acciones", name: "Acciones" },
        ];
        columns.forEach((column) => {
            if (column.name === 'id_recibo_ingreso')
                sortOrders[column.name] = 1;
            else
                sortOrders[column.name] = -1;
        });
        return {
            view_receipt: false,
            receipt_to_print: [],
            //Data for datatable
            income_receipts: [],
            //Data for modal
            income_concepts: [],
            financing_sources: [],
            treasury_clerk: [],
            show_modal_receipt: false,
            modal_data: [],
            permits: [],
            budget_accounts: [],
            links: [],
            columns: columns,
            sortKey: "id_recibo_ingreso",
            sortOrders: sortOrders,
            perPage: ["10", "20", "30"],
            tableData: {
                currentPage: '',
                draw: 0,
                length: 5,
                search: "",
                column: 0,
                dir: "desc",
                total: ""
            },
        }
    },
    methods: {
        formatearFecha(date) {
            return moment(date).format('DD/MM/YYYY');
        },
        viewReceipt(receipt) {
            const filteredReceipt = {
                ...receipt,
                detalles: receipt.detalles.filter(detalle => detalle.estado_det_recibo_ingreso === 1)
            };
            this.receipt_to_print = filteredReceipt
            this.view_receipt = true
        },
        editIncomeReceipt(income_concept) {
            this.modal_data = income_concept
            this.show_modal_receipt = true
        },
        addIncomeConcept() {
            this.modal_data = []
            this.show_modal_receipt = true
        },
        getModalReceiptSelects() {
            axios.get("/get-modal-receipt-selects")
                .then((response) => {
                    this.budget_accounts = response.data.budget_accounts
                    this.income_concepts = response.data.income_concepts
                    this.treasury_clerk = response.data.treasury_clerk
                    //this.financing_sources = response.data.financing_sources
                })
                .catch((errors) => {
                    let msg = this.manageError(errors);
                    this.$swal.fire({
                        title: "Operación cancelada",
                        text: msg,
                        icon: "warning",
                        timer: 5000,
                    });
                    this.$emit("cerrar-modal");
                });
        },
        changeStateIncomeReceipt(income_receipt_id, income_receipt_number, income_receipt_state) {
            let msg
            income_receipt_state == 1 ? msg = "Desactivar" : msg = "Activar"
            this.$swal.fire({
                title: msg + ' recibo de ingreso numero: ' + income_receipt_number + '.',
                text: "¿Estas seguro?",
                icon: "question",
                iconHtml: "❓",
                confirmButtonText: 'Si, ' + msg,
                confirmButtonColor: "#001b47",
                cancelButtonText: "Cancelar",
                showCancelButton: true,
                showCloseButton: true
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.post("/change-state-income-receipt", {
                        income_receipt_id: income_receipt_id,
                        income_receipt_state: income_receipt_state
                    })
                        .then((response) => {
                            this.$swal.fire({
                                text: response.data.mensaje,
                                icon: 'success',
                                timer: 5000
                            })
                            this.getIncomeReceipts(this.tableData.currentPage);
                        })
                        .catch((errors) => {
                            let msg = this.manageError(errors)
                            this.$swal.fire({
                                title: 'Operación cancelada',
                                text: msg,
                                icon: 'warning',
                                timer: 5000
                            })
                        })
                }
            })
        },
        async getIncomeReceipts(url = "/recibos-ingreso") {
            this.tableData.draw++;
            this.tableData.currentPage = url
            await axios.post(url, this.tableData).then((response) => {
                let data = response.data;
                if (this.tableData.draw == data.draw) {
                    this.links = data.data.links;
                    this.tableData.total = data.data.total;
                    this.links[0].label = "Anterior";
                    this.links[this.links.length - 1].label = "Siguiente";
                    this.income_receipts = data.data.data;
                }
            }).catch((errors) => {
                let msg = this.manageError(errors)
                this.$swal.fire({
                    title: 'Operación cancelada',
                    text: msg,
                    icon: 'warning',
                    timer: 5000
                })
                //console.log(errors);
            })
        },
        sortBy(key) {
            if (key != "Acciones") {
                this.sortKey = key;
                this.sortOrders[key] = this.sortOrders[key] * -1;
                this.tableData.column = this.getIndex(this.columns, "name", key);
                this.tableData.dir = this.sortOrders[key] === 1 ? "asc" : "desc";
                this.getIncomeReceipts();
            }
        },
        getIndex(array, key, value) {
            return array.findIndex((i) => i[key] == value);
        },
        getPermits() {
            var URLactual = window.location.pathname
            let data = this.$page.props.menu;
            let menu = JSON.parse(JSON.stringify(data['urls']))
            menu.forEach((value, index) => {
                value.submenu.forEach((value2, index2) => {
                    if (value2.url === URLactual) {
                        var array = { 'insertar': value2.insertar, 'actualizar': value2.actualizar, 'eliminar': value2.eliminar, 'ejecutar': value2.ejecutar }
                        this.permits = array
                    }
                })
            })
        },
        handleData(myEventData) {
            this.tableData.search = myEventData;
            this.getIncomeReceipts()
        }
    }
}
</script>

<style>.td-data-table {
    max-width: 100px;
    white-space: nowrap;
    height: 50px;
}

.ellipsis {
    overflow: hidden;
    text-overflow: ellipsis;
}</style>
