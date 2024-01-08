<script setup>
import Modal from "@/Components/Modal.vue";
import { Head } from "@inertiajs/vue3";
import AppLayoutVue from "@/Layouts/AppLayout.vue";
import Datatable from "@/Components-ISRI/Datatable.vue";
import ModalVue from "@/Components-ISRI/AllModal/BasicModal.vue";
import ModalIncomeConceptVue from '@/Components-ISRI/Tesoreria/ModalIncomeConcept.vue';
import moment from 'moment';

import IconM from "@/Components-ISRI/ComponentsToForms/IconM.vue";

import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';

import axios from 'axios';

</script>

<template>
    <Head title="Catalogo - Conceptos" />
    <AppLayoutVue nameSubModule="Tesoreria - Conceptos de Ingreso">
        <div v-if="isLoadinTop"
            class="fixed top-0 left-0 right-0 bottom-0 flex items-center justify-center bg-gray-800 bg-opacity-50 z-50">
            <div role="status" class="flex items-center">
                <svg aria-hidden="true" class="w-8 h-8 mr-2 text-gray-200 animate-spin dark:text-gray-600 fill-blue-800"
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
                    <tbody class="text-sm divide-y divide-slate-200" v-if="!isLoadinRequest">
                        <tr v-for="service in income_concept" :key="service.id_concepto_ingreso">
                            <td class="px-2 first:pl-5 last:pr-5  whitespace-nowrap w-px">
                                <div class="font-medium text-slate-800 text-center">{{ service.id_concepto_ingreso }}</div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5 td-data-table">
                                <div class="font-medium text-slate-800 ellipsis text-center">
                                    {{ service.codigo_centro_atencion && service.nombre_centro_atencion ?
                                        service.codigo_centro_atencion
                                        + ' - ' +
                                        service.nombre_centro_atencion : 'N/A' }}
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
                                        <div @click="changeStateIncomeConcept(service.id_concepto_ingreso, service.nombre_concepto_ingreso, service.estado_concepto_ingreso)"
                                            v-if="permits.actualizar == 1"
                                            class="flex hover:bg-gray-100 py-1 px-2 rounded cursor-pointer">
                                            <div class="ml-0.5 mr-2 w-5 h-5"
                                                :class="service.estado_concepto_ingreso == 1 ? 'text-red-800' : 'text-green-800'">
                                                <span class="text-xs ">
                                                    <IconM
                                                        :iconName="service.estado_concepto_ingreso == 1 ? 'desactivate' : 'activate'">
                                                    </IconM>
                                                </span>
                                            </div>
                                            <div class="font-semibold">
                                                {{ service.estado_concepto_ingreso == 1 ? 'Desactivar' : 'Activar' }}
                                            </div>
                                        </div>
                                    </DropDownOptions>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                    <tbody v-else>
                        <tr>
                            <td colspan="6" class="text-center">
                                <img src="../../../img/IsSearching.gif" alt="" class="w-60 h-60 mx-auto">
                                <h1 class="font-medium text-xl mt-4">Cargando!!!</h1>
                                <p class="text-sm text-gray-600 mt-2 pb-10">Por favor espera un momento mientras se carga la
                                    información.</p>
                            </td>
                        </tr>
                    </tbody>
                    <tbody v-if="empty_object && !isLoadinRequest">
                        <tr>
                            <td colspan="6" class="text-center">
                                <img src="../../../img/NoData.gif" alt="" class="w-60 h-60 mx-auto">
                                <h1 class="font-medium text-xl mt-4">No se encontraron resultados!</h1>
                                <p class="text-sm text-gray-600 mt-2 pb-10">Parece que no hay registros disponibles en este
                                    momento.</p>
                            </td>
                        </tr>
                    </tbody>
                </datatable>

            </div>
            <div v-if="empty_object" class="flex text-center py-2">
                <p class="font-semibold text-red-500 text-[16px]" style="margin: 0 auto; text-align: center;">No se
                    encontraron registros.</p>
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
                                        <a @click="link.url ? getIncomeConcept(link.url) : ''" class=" btn bg-white border-slate-200 hover:border-slate-300 cursor-pointer
                                  text-indigo-500">
                                            &lt;-<span class="hidden sm:inline">&nbsp;Anterior</span>
                                        </a>
                                    </div>
                                </span>
                                <span v-else-if="(link.label == 'Siguiente')"
                                    :class="(link.active ? 'inline-flex items-center justify-center rounded-full leading-5 px-2 py-2 bg-white border border-slate-200 text-indigo-500 shadow-sm' : 'inline-flex items-center justify-center leading-5 px-2 py-2 text-slate-600 hover:text-indigo-500 border border-transparent')">
                                    <div class="flex-1 text-right ml-2">
                                        <a @click="link.url ? getIncomeConcept(link.url) : ''" class=" btn bg-white border-slate-200 hover:border-slate-300 cursor-pointer
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
            isLoadinRequest: false,
            isLoadinTop: false,
            //Until here
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
                    this.manageError(errors, this)
                    this.$emit("cerrar-modal");
                });
        },
        async changeStateIncomeConcept(id_service, name_service, state_service) {
            let msg
            state_service == 1 ? msg = "Desactivar" : msg = "Activar"
            this.$swal
                .fire({
                    title: msg + ' concepto de ingreso: ' + name_service + '.',
                    text: "¿Estas seguro?",
                    icon: "question",
                    iconHtml: "❓",
                    confirmButtonText: 'Si, ' + msg,
                    confirmButtonColor: "#001b47",
                    cancelButtonText: "Cancelar",
                    showCancelButton: true,
                    showCloseButton: true
                })
                .then(async (result) => {
                    if (result.isConfirmed) {
                        this.isLoadinTop = true
                        await axios.post("/change-state-income-concept", {
                            id_service: id_service,
                            state_service: state_service
                        })
                            .then((response) => {
                                this.showToast(toast.success, response.data.mensaje);
                                this.getIncomeConcept(this.tableData.currentPage);
                            })
                            .catch((errors) => {
                                this.manageError(errors, this)
                            })
                            .finally(() => {
                                this.isLoadinTop = false;
                            });
                    }
                });
        },
        // changeStateIncomeConcept(id_service, name_service, state_service) {
        //     let msg
        //     state_service == 1 ? msg = "Desactivar" : msg = "Activar"
        //     this.$swal.fire({
        //         title: msg + ' concepto de ingreso: ' + name_service + '.',
        //         text: "¿Estas seguro?",
        //         icon: "question",
        //         iconHtml: "❓",
        //         confirmButtonText: 'Si, ' + msg,
        //         confirmButtonColor: "#001b47",
        //         cancelButtonText: "Cancelar",
        //         showCancelButton: true,
        //         showCloseButton: true
        //     }).then((result) => {
        //         if (result.isConfirmed) {
        //             axios.post("/change-state-income-concept", {
        //                 id_service: id_service,
        //                 state_service: state_service
        //             })
        //                 .then((response) => {
        //                     toast.success(response.data.mensaje, {
        //                         autoClose: 4000,
        //                         position: "top-right",
        //                         transition: "zoom",
        //                         toastBackgroundColor: "white",
        //                     });
        //                     this.getIncomeConcept(this.tableData.currentPage);
        //                 })
        //                 .catch((errors) => {
        //                     this.manageError(errors, this)
        //                 })
        //         }
        //     })
        // },
        async getIncomeConcept(url = "/ingresos") {
            this.tableData.draw++;
            this.tableData.currentPage = url
            this.isLoadinRequest = true
            await axios.post(url, this.tableData).then((response) => {
                let data = response.data;
                if (this.tableData.draw == data.draw) {
                    this.links = data.data.links;
                    this.tableData.total = data.data.total;
                    this.links[0].label = "Anterior";
                    this.links[this.links.length - 1].label = "Siguiente";
                    this.income_concept = data.data.data;
                    this.income_concept.length > 0 ? this.empty_object = false : this.empty_object = true
                    this.isLoadinRequest = false
                }
            }).catch((errors) => {
                this.manageError(errors, this)
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