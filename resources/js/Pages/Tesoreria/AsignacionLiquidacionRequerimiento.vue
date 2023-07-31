<script setup>
import { Head } from "@inertiajs/vue3";
import Datatable from "@/Components-ISRI/Datatable.vue";
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
import axios from 'axios';
import ModalAsignacionRequerimiento from "@/Components-ISRI/Tesoreria/ModalAsignacionRequerimiento.vue";
import ModalLiquidacionRequerimientoVue from '@/Components-ISRI/Tesoreria/ModalLiquidacionRequerimiento.vue';
</script>
<template>
    <Head title="Proceso - Asignacion" />
    <AppLayoutVue nameSubModule="Tesoreria - Asignacion y Liquidacion de requerimientos">
        <div class="sm:flex sm:justify-end sm:items-center mb-2">
            <div class="grid grid-flow-col sm:auto-cols-max sm:justify-end gap-2">
                <GeneralButton @click="showModalAsignacionRequerimiento = true" color="bg-green-700  hover:bg-green-800"
                    text="Agregar requerimientos" icon="add" />
            </div>
        </div>
        <div class="bg-white shadow-lg rounded-sm border border-slate-200 relative">
            <header class="px-5 py-4">
                <div class="mb-4 md:flex flex-row justify-items-start">
                    <div class="mb-4 md:mr-2 md:mb-0 basis-1/4">
                        <div class="relative flex h-8 w-full flex-row-reverse div-multiselect">
                            <Multiselect v-model="tableData.length" @select="getDataLiquidaciones(lastUrl)"
                                @deselect=" tableData.length = 5; getDataLiquidaciones(lastUrl)"
                                @clear="tableData.length = 5; getDataLiquidaciones(lastUrl)" :options="perPage" :searchable="true"
                                placeholder="Cantidad a mostrar" />
                            <LabelToInput icon="list2" />
                        </div>
                    </div>
                    <h2 class="font-semibold text-slate-800 pt-1">Requerimientos:
                        <span class="text-slate-400 font-medium">{{ pagination.total }}</span>
                    </h2>
                </div>

            </header>
            <div class="overflow-x-auto">
                <datatable :columns="columns" :sortKey="sortKey" :sortOrders="sortOrders" :searchButton="true"
                    @sort="sortBy" @datos-enviados="handleData($event)" @execute-search="getDataLiquidaciones(lastUrl)">
                    <tbody class="text-sm divide-y divide-slate-200">
                        <template v-for="data in dataRequerimientoForTable" :key="data.id_requerimiento_pago">
                            <tr v-if="data.quedan != ''">

                                <td class="px-2 first:pl-5 last:pr-5  whitespace-nowrap w-px">
                                    <div class="font-medium text-slate-800 text-center">
                                        {{ data.numero_requerimiento_pago }}
                                    </div>
                                </td>
                                <td class="px-2 first:pl-5 last:pr-5 ">
                                    <div class="font-medium text-slate-800 text-center">{{
                                        data.descripcion_requerimiento_pago }}
                                    </div>
                                </td>
                                <td>
                                    <div class="max-h-[165px] overflow-y-auto scrollbar">
                                        <template v-for="(quedan, i) in data.quedan" :key="i">
                                            <div class="mb-2 text-center">
                                                <p class="text-[10pt]">
                                                    <span class="font-medium">FACTURA</span>{{ quedan.id_quedan }}<br>
                                                    <span class="font-medium">MONTO:</span> ${{ quedan.monto_liquido_quedan
                                                    }}
                                                </p>
                                            </div>
                                            <template v-if="i < data.quedan.length - 1">
                                                <hr class="my-2 border-t border-gray-300">
                                            </template>
                                        </template>
                                    </div>
                                </td>
                                <td class="px-2 first:pl-5 last:pr-5">
                                    <div class="font-medium text-slate-800 text-center">{{ data.monto_requerimiento_pago }}
                                    </div>
                                </td>
                                <td class="px-2 first:pl-5 last:pr-5  whitespace-nowrap w-px text-center">
                                    <div v-if="data.id_estado_req_pago === 1"
                                        class="text-xs inline-flex font-medium bg-emerald-100 text-emerald-600 rounded-full text-center px-2.5 py-1">
                                        Abierto
                                    </div>

                                    <div v-else-if="data.id_estado_req_pago === 2"
                                        class="text-xs inline-flex font-medium bg-red-100 text-red-600 rounded-full text-center px-2.5 py-1">
                                        Cerrado
                                    </div>
                                </td>
                                <td class="px-2 first:pl-5 last:pr-5  whitespace-nowrap w-px">
                                    <div class="space-x-1 pl-3">

                                        <DropDownOptions>
                                            <div class="flex hover:bg-gray-100 py-1 px-2 rounded cursor-pointer"
                                                @click="openModalLiquidacionQuedan(data)">
                                                <div class="w-8 text-blue-900">
                                                    <span class="text-xs">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                            class="w-6 h-6">
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
                        </template>

                    </tbody>
                </datatable>
            </div>
            <div v-if="empty_object" class="flex text-center py-2">
                <p class="text-red-500 font-semibold text-[16px]" style="margin: 0 auto; text-align: center;">No se
                    encontraron
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
                                        <a @click="getDataLiquidaciones(link.url)"
                                            class=" btn bg-white border-slate-200 hover:border-slate-300 cursor-pointer
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              text-indigo-500">
                                            &lt;-<span class="hidden sm:inline">&nbsp;Anterior</span>
                                        </a>
                                    </div>
                                </span>
                                <span v-else-if="(link.label == 'Siguiente')"
                                    :class="(link.active ? 'inline-flex items-center justify-center rounded-full leading-5 px-2 py-2 bg-white border border-slate-200 text-indigo-500 shadow-sm' : 'inline-flex items-center justify-center leading-5 px-2 py-2 text-slate-600 hover:text-indigo-500 border border-transparent')">
                                    <div class="flex-1 text-right ml-2">
                                        <a @click="getDataLiquidaciones(link.url)"
                                            class=" btn bg-white border-slate-200 hover:border-slate-300 cursor-pointer
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              text-indigo-500">
                                            <span class="hidden sm:inline">Siguiente&nbsp;</span>-&gt;
                                        </a>
                                    </div>
                                </span>
                                <span class="cursor-pointer" v-else
                                    :class="(link.active ? 'inline-flex items-center justify-center rounded-full leading-5 px-2 py-2 bg-white border border-slate-200 text-indigo-500 shadow-sm' : 'inline-flex items-center justify-center leading-5 px-2 py-2 text-slate-600 hover:text-indigo-500 border border-transparent')"><span
                                        class=" w-5" @click="getDataLiquidaciones(link.url)">{{ link.label }}</span>
                                </span>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>


        <ModalAsignacionRequerimiento :modalIsOpen="showModalAsignacionRequerimiento"
            @close-definitive="showModalAsignacionRequerimiento = false" :dataForSelect="dataForSelect"
            @reload-table="[getDataLiquidaciones(lastUrl), getListForSelect()]" />


        <ModalLiquidacionRequerimientoVue :modalIsOpen="showModalLiquidacionRequerimiento"
            @close-definitive="showModalLiquidacionRequerimiento = false" @reload-table="getDataLiquidaciones(lastUrl)"
            @reload-table-and-close="[getDataLiquidaciones(lastUrl), showModalLiquidacionRequerimiento = false]"
            :dataLiquidaciones="dataLiquidaciones" @reload-data-for-select="getListForSelect()" />
    </AppLayoutVue>
</template>
<script>

export default {

    data: function (data) {
        let sortOrders = {};
        let columns = [
            { width: "10%", label: "Requerimiento", name: "numero_requerimiento_pago", type: "text" },
            { width: "20%", label: "Descripcion", name: "descripcion_requerimiento_pago", type: "text" },
            { width: "20%", label: "Todos los quedan", name: "allQUedan", type: "text" },
            { width: "10%", label: "Monto requerido", name: "monto_requerimiento_pago", type: "text" },
            {
                width: "10%", label: "Estado", name: "id_estado_req_pago", type: "select", options: [
                    { value: "", label: "Ninguno" },
                    { value: "1", label: "Abierto" },
                    { value: "2", label: "Cerrado" },

                ]
            },
            { width: "5%", label: "", name: "Acciones" },
        ];
        columns.forEach((column) => {
            if (column.name === 'id_requerimiento_pago')
                sortOrders[column.name] = 1;
            else
                sortOrders[column.name] = -1;
        });
        return {
            empty_object: false,
            showModalAsignacionRequerimiento: false,
            showModalLiquidacionRequerimiento: false,
            dataLiquidaciones: [],//data que contiene todos los totales de todos los requerimientos
            permits: [],
            dataForSelect: [],
            dataRequerimientoForTable: [],
            links: [],
            lastUrl: '/asignados',
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
                onlyLiquida: true,
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
        }
    },
    methods: {
        async getDataLiquidaciones(url = "/asignados") {
            this.lastUrl = url
            this.tableData.draw++
            await axios.post(url, this.tableData).then((response) => {
                let data = response.data;
                console.log(data);
                if (this.tableData.draw == data.draw) {
                    this.links = data.data.links
                    this.pagination.total = data.data.total
                    this.links[0].label = "Anterior"
                    this.links[this.links.length - 1].label = "Siguiente"
                    this.dataRequerimientoForTable = data.data.data
                    this.dataRequerimientoForTable.length > 0 ? this.empty_object = false : this.empty_object = true
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
        openModalLiquidacionQuedan(dataLiquidaciones) {
            this.showModalLiquidacionRequerimiento = !this.showModalLiquidacionRequerimiento
            this.dataLiquidaciones = this.dataRequerimientoForTable.filter((e) => e.id_requerimiento_pago == dataLiquidaciones.id_requerimiento_pago)
        },
        async getListForSelect() {
            await axios.get('/get-list-select').then((response) => {
                this.dataForSelect = response.data;
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
                this.getDataLiquidaciones();
            }
        },
        getIndex(array, key, value) {
            return array.findIndex((i) => i[key] == value);
        },

        openModal(dataQuedan) {
            this.showModal = !this.showModal
            this.dataQuedan = dataQuedan
        },
        validarCamposVacios(objeto) {
            for (var propiedad in objeto) {
                if (objeto[propiedad] !== '') {
                    return false;
                }
            }
            return true;
        },
        handleData(myEventData) {
            if (this.validarCamposVacios(myEventData)) {
                this.tableData.search = {};
                this.getDataLiquidaciones()
            } else {
                this.tableData.search = myEventData;
                //this.getDataLiquidaciones()
            }
        },
    },
    created() {
        this.getDataLiquidaciones()
        this.getListForSelect()
        this.getPermissions(this)
    },

};
</script>
  
<style>
.wrap,
.wrap2 {
    width: 100%;
    white-space: pre-wrap;
}


.scrollbar::-webkit-scrollbar {
    width: 5px;
}

.scrollbar::-webkit-scrollbar-track {
    background-color: #f1f1f1;
}

.scrollbar::-webkit-scrollbar-thumb {
    background-color: #001c48;
    border-radius: 4px;
}

.scrollbar::-webkit-scrollbar-thumb:hover {
    background-color: #555;
}
</style>