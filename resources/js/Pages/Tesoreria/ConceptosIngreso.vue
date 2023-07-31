<script setup>
import Modal from "@/Components/Modal.vue";
import { Head } from "@inertiajs/vue3";
import AppLayoutVue from "@/Layouts/AppLayout.vue";
import Datatable from "@/Components-ISRI/Datatable.vue";
import ModalVue from "@/Components-ISRI/AllModal/BasicModal.vue";
import ModalIncomeConceptVue from '@/Components-ISRI/Tesoreria/ModalIncomeConcept.vue';
import moment from 'moment';

import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';

import axios from 'axios';

</script>

<template>
    <Head title="Catalogo - Conceptos" />
    <AppLayoutVue nameSubModule="Tesoreria - Conceptos de Ingreso">
        <div class="sm:flex sm:justify-end sm:items-center mb-2">
            <div class="grid grid-flow-col sm:auto-cols-max sm:justify-end gap-2">
                <GeneralButton @click="addIncomeConcept()" v-if="permits.insertar == 1"
                    color="bg-green-700  hover:bg-green-800" text="Agregar Concepto" icon="add" />
            </div>
        </div>
        <div class="bg-white shadow-lg rounded-sm border border-slate-200 relative">
            <header class="px-5 py-4">
                <div class="mb-4 md:flex flex-row justify-items-start">
                    <div class="mb-4 md:mr-2 md:mb-0 basis-1/4">
                        <div class="relative flex h-8 w-full flex-row-reverse div-multiselect">
                            <Multiselect v-model="tableData.length" @select="getIncomeConcept()" :options="perPage"
                                :searchable="true" placeholder="Cantidad a mostrar" />
                            <LabelToInput icon="list2" />
                        </div>
                    </div>
                    <h2 class="font-semibold text-slate-800 pt-1">Concepto Ingresos: <span
                            class="text-slate-400 font-medium">{{
                                tableData.total
                            }}</span></h2>
                </div>
            </header>

            <div class="overflow-x-auto">
                <datatable :columns="columns" :sortKey="sortKey" :sortOrders="sortOrders" :searchButton="true"
                    @sort="sortBy" @datos-enviados="handleData($event)" @execute-search="getIncomeConcept()">
                    <tbody class="text-sm divide-y divide-slate-200">
                        <tr v-for="service in income_concept" :key="service.id_concepto_ingreso">
                            <td class="px-2 first:pl-5 last:pr-5  whitespace-nowrap w-px">
                                <div class="font-medium text-slate-800 text-center">{{ service.id_concepto_ingreso }}</div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5 td-data-table">
                                <div class="font-medium text-slate-800 ellipsis text-center">
                                    {{ service.codigo_dependencia && service.nombre_dependencia ? service.codigo_dependencia
                                        + ' - ' +
                                        service.nombre_dependencia : '' }}
                                </div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5 td-data-table">
                                <div class="font-medium text-slate-800 ellipsis text-center">{{
                                    service.nombre_concepto_ingreso }}</div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5  whitespace-nowrap w-px">
                                <div class="font-medium text-slate-800 text-center">{{ service.id_ccta_presupuestal }}</div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5  whitespace-nowrap w-px">
                                <div class="font-medium text-slate-800">
                                    <div v-if="(service.estado_concepto_ingreso == 1)"
                                        class="inline-flex font-medium rounded-full text-center px-2.5 py-0.5 bg-emerald-100 text-emerald-500">
                                        Activo
                                    </div>
                                    <div v-else
                                        class="inline-flex font-medium rounded-full text-center px-2.5 py-0.5 bg-rose-100 text-rose-600">
                                        Inactivo
                                    </div>
                                </div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5  whitespace-nowrap w-px">
                                <div class="space-x-1 text-center">
                                    <DropDownOptions>
                                        <div class="flex hover:bg-gray-100 py-1 px-2 rounded cursor-pointer"
                                            v-if="permits.actualizar == 1 && service.estado_concepto_ingreso == 1"
                                            @click="editIncomeConcept(service)">
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
                                            @click="changeStateIncomeConcept(service.id_concepto_ingreso, service.nombre_concepto_ingreso, service.estado_concepto_ingreso)"
                                            v-if="permits.eliminar == 1">
                                            <div class="w-8">
                                                <svg v-if="service.estado_concepto_ingreso == 1" width="28px"
                                                    style="margin-left: -2px;" height="28px" viewBox="0 0 24 24"
                                                    fill="none">
                                                    <path
                                                        d="M12.0769 19C13.5389 19 14.9634 18.532 16.1462 17.6631C17.329 16.7942 18.2094 15.569 18.6612 14.1631C19.1129 12.7572 19.1129 11.2428 18.6612 9.83688C18.2094 8.43098 17.329 7.20578 16.1462 6.33688C14.9634 5.46798 13.5389 5 12.0769 5C10.6149 5 9.19043 5.46799 8.00764 6.33688C6.82485 7.20578 5.94447 8.43098 5.49268 9.83688C5.0409 11.2428 5.0409 12.7572 5.49269 14.1631M6.5 12.7778L5.53846 14.3333L4 13.1667"
                                                        stroke="#a80000" stroke-linecap="round" stroke-linejoin="round">
                                                    </path>
                                                </svg>

                                                <svg v-else style="margin-left: -2px;" width="28px" height="28px"
                                                    viewBox="0 0 24 24" fill="none">
                                                    <path
                                                        d="M11.9231 19C10.4611 19 9.03659 18.532 7.85379 17.6631C6.671 16.7942 5.79063 15.569 5.33884 14.1631C4.88705 12.7572 4.88705 11.2428 5.33884 9.83688C5.79063 8.43098 6.671 7.20578 7.8538 6.33688C9.03659 5.46798 10.4611 5 11.9231 5C13.3851 5 14.8096 5.46799 15.9924 6.33688C17.1752 7.20578 18.0555 8.43098 18.5073 9.83688C18.9591 11.2428 18.9591 12.7572 18.5073 14.1631M17.5 12.7778L18.4615 14.3333L20 13.1667"
                                                        stroke="#006113" stroke-linecap="round" stroke-linejoin="round">
                                                    </path>

                                                </svg>
                                            </div>
                                            <div class="font-semibold">
                                                {{ service.estado_concepto_ingreso ? 'Desactivar' : 'Activar' }}
                                            </div>
                                        </div>
                                    </DropDownOptions>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </datatable>

            </div>
            <div v-if="empty_object" class="flex text-center py-2">
                <p class="font-semibold text-red-500 text-[16px]" style="margin: 0 auto; text-align: center;">No se encontraron registros.</p>
            </div>
        </div>

        <div v-if="!empty_object" class="px-6 py-8 bg-white shadow-lg rounded-sm border-slate-200 relative">
            <div>
                <nav class="flex justify-between" role="navigation" aria-label="Navigation">
                    <div class="grow text-center">
                        <ul class="inline-flex text-sm font-medium -space-x-px">
                            <li v-for="link in links" :key="link.label">
                                <span v-if="(link.label == 'Anterior')"
                                    :class="(link.active ? 'inline-flex items-center justify-center rounded-full leading-5 px-2 py-2 bg-white border border-slate-200 text-indigo-500 shadow-sm' : 'inline-flex items-center justify-center leading-5 px-2 py-2 text-slate-600 hover:text-indigo-500 border border-transparent')">

                                    <div class="flex-1 text-right ml-2">
                                        <a @click="getIncomeConcept(link.url)" class=" btn bg-white border-slate-200 hover:border-slate-300 cursor-pointer
                                  text-indigo-500">
                                            &lt;-<span class="hidden sm:inline">&nbsp;Anterior</span>
                                        </a>
                                    </div>
                                </span>
                                <span v-else-if="(link.label == 'Siguiente')"
                                    :class="(link.active ? 'inline-flex items-center justify-center rounded-full leading-5 px-2 py-2 bg-white border border-slate-200 text-indigo-500 shadow-sm' : 'inline-flex items-center justify-center leading-5 px-2 py-2 text-slate-600 hover:text-indigo-500 border border-transparent')">
                                    <div class="flex-1 text-right ml-2">
                                        <a @click="getIncomeConcept(link.url)" class=" btn bg-white border-slate-200 hover:border-slate-300 cursor-pointer
                                  text-indigo-500">
                                            <span class="hidden sm:inline">Siguiente&nbsp;</span>-&gt;
                                        </a>
                                    </div>
                                </span>
                                <span class="cursor-pointer" v-else
                                    :class="(link.active ? 'inline-flex items-center justify-center rounded-full leading-5 px-2 py-2 bg-white border border-slate-200 text-indigo-500 shadow-sm' : 'inline-flex items-center justify-center leading-5 px-2 py-2 text-slate-600 hover:text-indigo-500 border border-transparent')"><span
                                        class=" w-5" @click="getIncomeConcept(link.url)">{{ link.label }}</span>
                                </span>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>

        <ModalIncomeConceptVue :showModalIncome="showModalIncome" :modalData="modalData"
            :financing_sources="financing_sources" :budget_accounts="budget_accounts" :dependencies="dependencies"
            @cerrar-modal="showModalIncome = false" @get-table="getIncomeConcept(tableData.currentPage)" />

    </AppLayoutVue>
</template>

<script>
export default {
    created() {
        this.getIncomeConcept()
        this.getPermissions(this)
        this.getSelectsIncomeConcept()
    },
    data() {
        let sortOrders = {};
        let columns = [
            { width: "10%", label: "ID", name: "id_concepto_ingreso", type: "text" },
            { width: "30%", label: "Dependencia", name: "nombre_dependencia", type: "text" },
            { width: "30%", label: "Concepto Ingreso", name: "nombre_concepto_ingreso", type: "text" },
            { width: "10%", label: "Especifico", name: "id_ccta_presupuestal", type: "text" },
            {
                width: "10%", label: "Estado", name: "estado_concepto_ingreso", type: "select",
                options: [
                    { value: "1", label: "Activo" },
                    { value: "0", label: "Inactivo" }
                ]
            },
            { width: "10%", label: "Acciones", name: "Acciones" },
        ];
        columns.forEach((column) => {
            if (column.name === 'id_concepto_ingreso')
                sortOrders[column.name] = 1;
            else
                sortOrders[column.name] = -1;
        });
        return {
            empty_object: false,
            //Data for datatable
            income_concept: [],
            //Data for modal
            showModalIncome: false,
            modalData: [],

            permits: [],
            budget_accounts: [],
            dependencies: [],
            financing_sources: [],
            links: [],
            columns: columns,
            sortKey: "id_concepto_ingreso",
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
        editIncomeConcept(income_concept) {
            //var array = {nombre_marca:marca.nombre_marca}
            this.modalData = income_concept
            this.showModalIncome = true
        },
        addIncomeConcept() {
            this.modalData = []
            this.showModalIncome = true
        },
        getSelectsIncomeConcept() {
            axios.get("/get-selects-income-concept")
                .then((response) => {
                    this.budget_accounts = response.data.budget_accounts
                    this.dependencies = response.data.dependencies
                    this.financing_sources = response.data.financing_sources
                })
                .catch((errors) => {
                    this.manageError(errors,this)
                    this.$emit("cerrar-modal");
                });
        },
        changeStateIncomeConcept(id_service, name_service, state_service) {
            let msg
            state_service == 1 ? msg = "Desactivar" : msg = "Activar"
            this.$swal.fire({
                title: msg + ' concepto de ingreso: ' + name_service + '.',
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
                    axios.post("/change-state-income-concept", {
                        id_service: id_service,
                        state_service: state_service
                    })
                        .then((response) => {
                            toast.success(response.data.mensaje, {
                                autoClose: 4000,
                                position: "top-right",
                                transition: "zoom",
                                toastBackgroundColor: "white",
                            });
                            this.getIncomeConcept(this.tableData.currentPage);
                        })
                        .catch((errors) => {
                            this.manageError(errors,this)
                        })
                }
            })
        },
        async getIncomeConcept(url = "/ingresos") {
            this.tableData.draw++;
            this.tableData.currentPage = url
            await axios.post(url, this.tableData).then((response) => {
                let data = response.data;
                if (this.tableData.draw == data.draw) {
                    this.links = data.data.links;
                    this.tableData.total = data.data.total;
                    this.links[0].label = "Anterior";
                    this.links[this.links.length - 1].label = "Siguiente";
                    this.income_concept = data.data.data;
                    this.income_concept.length > 0 ? this.empty_object = false : this.empty_object = true
                }
            }).catch((errors) => {
                this.manageError(errors,this)
                //console.log(errors);
            })
        },
        sortBy(key) {
            if (key != "Acciones") {
                this.sortKey = key;
                this.sortOrders[key] = this.sortOrders[key] * -1;
                this.tableData.column = this.getIndex(this.columns, "name", key);
                this.tableData.dir = this.sortOrders[key] === 1 ? "asc" : "desc";
                this.getIncomeConcept();
            }
        },
        getIndex(array, key, value) {
            return array.findIndex((i) => i[key] == value);
        },
        handleData(myEventData) {
            this.tableData.search = myEventData;
            const data = Object.values(myEventData);
            if (data.every(error => error === '')) {
                this.getIncomeConcept()
            }
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