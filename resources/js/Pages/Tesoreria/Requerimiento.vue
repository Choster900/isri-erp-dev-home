<script setup>
import Datatable from "@/Components-ISRI/Datatable.vue";
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
import ModalRequerimientoVue from '@/Components-ISRI/Tesoreria/ModalRequerimiento.vue';
import ModalVerRequerimientoVue from '@/Components-ISRI/Tesoreria/ModalVerRequerimiento.vue';
</script>
<template>
    <Head title="Catalogo - Requerimiento" />
    <AppLayoutVue nameSubModule="Tesoreria - Requerimientos">
        <div class="sm:flex sm:justify-end sm:items-center mb-2">
            <div class="grid grid-flow-col sm:auto-cols-max sm:justify-end gap-2">
                <GeneralButton color="bg-green-700  hover:bg-green-800" text="Agregar Requerimiento" icon="add"
                    @click="openModal()" />
            </div>
        </div>
        <div class="bg-white shadow-lg rounded-sm border border-slate-200 relative">
            <header class="px-5 py-4">
                <div class="mb-4 md:flex flex-row justify-items-start">
                    <div class="mb-4 md:mr-2 md:mb-0 basis-1/4">
                        <div class="relative flex h-8 w-full flex-row-reverse div-multiselect">
                            <Multiselect v-model="tableData.length" @select="getDataRequerimiento()" :options="perPage"
                                :searchable="true" placeholder="Cantidad a mostrar" />
                            <LabelToInput icon="list2" />
                        </div>
                    </div>
                    <h2 class="font-semibold text-slate-800 pt-1">Total Requerimientos
                        <span class="text-slate-400 font-medium">{{ pagination.total }}</span>
                    </h2>
                </div>

            </header>
            <div class="overflow-x-auto">
                <datatable :columns="columns" :sortKey="sortKey" :sortOrders="sortOrders" :searchButton="true"
                    @sort="sortBy" @datos-enviados="handleData($event)" @execute-search="getDataRequerimiento()">
                    <tbody class="text-sm divide-y divide-slate-200">
                        <tr v-for="requerimiento in requerimientos" :key="requerimiento.id_requerimiento_pago">
                            <td class="px-2 first:pl-5 last:pr-5 td-data-table">
                                <div class="font-medium text-slate-800 text-center">
                                    {{ requerimiento.numero_requerimiento_pago }}
                                </div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5 td-data-table">
                                <div class="font-medium text-slate-800 text-center ellipsis">
                                    {{ requerimiento.descripcion_requerimiento_pago }}
                                </div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5 td-data-table">
                                <div class="font-medium text-slate-800 text-center">
                                    {{ requerimiento.monto_requerimiento_pago ? '$ ' +
                                        requerimiento.monto_requerimiento_pago
                                        : '$ 0.00' }}
                                </div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5 td-data-table">
                                <div class="font-medium text-slate-800 text-center">
                                    {{ requerimiento.mes_requerimiento_pago }}
                                </div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5 td-data-table">
                                <div class="font-medium text-slate-800 text-center">
                                    {{ requerimiento.anio_requerimiento_pago }}
                                </div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5 td-data-table">
                                <div class="space-x-1 text-center">

                                    <DropDownOptions>
                                        <div v-if="requerimiento.id_estado_req_pago != 2"
                                            class="flex hover:bg-gray-100 py-1 px-2 rounded cursor-pointer"
                                            @click="editRequerimiento(requerimiento)">
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
                                            @click="viewRequest(requerimiento)">
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
            <div v-if="empty_object" class="flex text-center py-2">
                <p class="font-semibold text-[16px]" style="margin: 0 auto; text-align: center;">No se encontraron
                    registros.</p>
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
                                        <a @click="getDataRequerimiento(link.url)"
                                            class=" btn bg-white border-slate-200 hover:border-slate-300 cursor-pointer
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  text-indigo-500">
                                            &lt;-<span class="hidden sm:inline">&nbsp;Anterior</span>
                                        </a>
                                    </div>
                                </span>
                                <span v-else-if="(link.label == 'Siguiente')"
                                    :class="(link.active ? 'inline-flex items-center justify-center rounded-full leading-5 px-2 py-2 bg-white border border-slate-200 text-indigo-500 shadow-sm' : 'inline-flex items-center justify-center leading-5 px-2 py-2 text-slate-600 hover:text-indigo-500 border border-transparent')">
                                    <div class="flex-1 text-right ml-2">
                                        <a @click="getDataRequerimiento(link.url)"
                                            class=" btn bg-white border-slate-200 hover:border-slate-300 cursor-pointer
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  text-indigo-500">
                                            <span class="hidden sm:inline">Siguiente&nbsp;</span>-&gt;
                                        </a>
                                    </div>
                                </span>
                                <span class="cursor-pointer" v-else
                                    :class="(link.active ? 'inline-flex items-center justify-center rounded-full leading-5 px-2 py-2 bg-white border border-slate-200 text-indigo-500 shadow-sm' : 'inline-flex items-center justify-center leading-5 px-2 py-2 text-slate-600 hover:text-indigo-500 border border-transparent')"><span
                                        class=" w-5" @click="getDataRequerimiento(link.url)">{{ link.label }}</span>
                                </span>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>
    </AppLayoutVue>

    <ModalRequerimientoVue :ModalIsOpen="requerimientoModalState" @close-modal="requerimientoModalState = false"
        :modal_data="modal_data" @updateTable="getDataRequerimiento(lastUrl)" />

    <ModalVerRequerimientoVue :view_req_info="view_req_info" :show_request="show_request" :center="true"
        @cerrar-modal="view_req_info = false" />
</template>
<script>
import axios from 'axios';
import moment from 'moment';
export default {
    created() {
        this.getDataRequerimiento();
    },
    data: function (data) {
        let sortOrders = {};
        let columns = [
            { width: "5%", label: "Numero requerimiento", name: "numero_requerimiento_pago", type: "text" },
            { width: "30%", label: "Descripcion", name: "descripcion_requerimiento_pago", type: "text" },
            { width: "10%", label: "Monto", name: "monto_requerimiento_pago", type: "text" },
            { width: "10%", label: "Mes", name: "mes_requerimiento_pago", type: "text" },
            { width: "10%", label: "Año", name: "anio_requerimiento_pago", type: "text" },

            { width: "1%", label: "Acciones", name: "Acciones" },
        ];
        columns.forEach((column) => {
            if (column.name === 'id_persona')
                sortOrders[column.name] = 1;
            else
                sortOrders[column.name] = -1;
        });
        return {
            empty_object:false,
            errors: [],
            scrollbarModalOpen: false,
            requerimientos: [],
            links: [],
            lastUrl: '/requerimientos',
            columns: columns,
            sortKey: "id_requerimiento_pago",
            sortOrders: sortOrders,
            perPage: ["10", "20", "30"],
            tableData: {
                draw: 0,
                length: 5,
                column: 0,
                dir: "desc",
                search: {},
            },
            pagination: {
                lastPage: "",
                currentPage: "",
                total: "",
                lastPageUrl: "",
                nextPageUrl: "",
                prevPageUrl: "",
                from: "",
                to: "",
            },
            requerimientoModalState: false,
            view_req_info: false,
            modal_data: [],
            errorNumber: null,
            show_request: []
        };
    },
    methods: {
        viewRequest(request) {
            this.view_req_info = true
            this.show_request = request
        },
        async getDataRequerimiento(url = "/requerimientos") {
            this.lastUrl = url;
            this.tableData.draw++;
            await axios.post(url, this.tableData).then((response) => {
                let data = response.data;
                if (this.tableData.draw == data.draw) {
                    this.links = data.data.links;
                    this.pagination.total = data.data.total
                    this.links[0].label = "Anterior";
                    this.links[this.links.length - 1].label = "Siguiente";
                    this.requerimientos = data.data.data;
                    this.requerimientos.length>0 ? this.empty_object=false : this.empty_object=true
                }
            }).catch((errors) => {
                let msg = this.manageError(errors);
                this.$swal.fire({
                    title: "Operación cancelada",
                    text: msg,
                    icon: "warning",
                    timer: 5000,
                });
            });
        },
        sortBy(key) {
            if (key != "Acciones") {
                this.sortKey = key;
                this.sortOrders[key] = this.sortOrders[key] * -1;
                this.tableData.column = this.getIndex(this.columns, "name", key);
                this.tableData.dir = this.sortOrders[key] === 1 ? "asc" : "desc";
                this.getDataRequerimiento();
            }
        },
        getIndex(array, key, value) {
            return array.findIndex((i) => i[key] == value);
        },
        editRequerimiento(request) {
            this.modal_data = request
            this.requerimientoModalState = true
        },
        openModal() {
            this.modal_data = []
            this.requerimientoModalState = true
        },
        validarCamposVacios(objeto) {
            for (var propiedad in objeto) {
                if (objeto[propiedad] !== "") {
                    return false;
                }
            }
            return true;
        },
        handleData(myEventData) {
            if (this.validarCamposVacios(myEventData)) {
                this.tableData.search = {};
                this.getDataRequerimiento();
            }
            else {
                this.tableData.search = myEventData;
                //this.getDataRequerimiento();
            }
        },
    },
    computed: {

    }

};
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
