<script setup>
import { Head } from "@inertiajs/vue3";
import Datatable from "@/Components-ISRI/Datatable.vue";
import ModalLiquidacion from "@/Components-ISRI/Tesoreria/ModalLiquidacionRequerimiento.vue";
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
import axios from 'axios';
</script>
<template>
    <Head title="Liquidaciones" />
    <AppLayoutVue>

        <div class="bg-white shadow-lg rounded-sm border border-slate-200 relative">
            <!-- TODO: Improve view table - this is temporal and doesn't mean is permanent -->
            <header class="px-5 py-4">
                <div class="mb-4 md:flex flex-row justify-items-start">
                    <div class="mb-4 md:mr-2 md:mb-0 basis-1/4">
                        <div class="relative flex h-8 w-full flex-row-reverse div-multiselect">
                            <Multiselect v-model="tableData.length" @select="getDataLiquidaciones()" :options="perPage"
                                :searchable="true" />
                            <LabelToInput icon="date" />
                        </div>
                    </div>
                    <h2 class="font-semibold text-slate-800 pt-1">Requerimiento con quedan asignados
                        <span class="text-slate-400 font-medium">{{ pagination.total }}</span>
                    </h2>
                </div>

            </header>
            <div class="overflow-x-auto">
                <datatable :columns="columns" :sortKey="sortKey" :sortOrders="sortOrders" @sort="sortBy"
                    @datos-enviados="handleData($event)">
                    <tbody class="text-sm divide-y divide-slate-200">
                        <template v-for="data in dataRequerimientoForTable" :key="data.id_requerimiento_pago">
                            <tr v-if="data.quedan != ''">
                                <td class="px-2 first:pl-5 last:pr-5  whitespace-nowrap w-px">
                                    <div class="font-medium text-slate-800 text-center">{{ data.id_requerimiento_pago }}
                                    </div>
                                </td>
                                <td class="px-2 first:pl-5 last:pr-5  whitespace-nowrap w-px">
                                    <div class="font-medium text-slate-800 text-center">
                                        {{ data.numero_requerimiento_pago }}-{{ data.anio_requerimiento_pago }}
                                    </div>
                                </td>
                                <td class="px-2 first:pl-5 last:pr-5 ">
                                    <div class="font-medium text-slate-800 text-center">{{
                                        data.descripcion_requerimiento_pago }}
                                    </div>
                                </td>
                                <td class="px-2 first:pl-5 last:pr-5">
                                    <div class="font-medium text-slate-800 text-center">{{ data.monto_requerimiento_pago }}
                                    </div>
                                </td>
                                <td>
                                    <div v-for="(quedan, i) in  data.quedan" :key="i"
                                        class="font-medium text-slate-800 text-center flex justify-center items-center">
                                        <p
                                            :class="{ 'border-b-2 border-b-gray-500': i < data.quedan.length - 1 && data.quedan.length > 1 }">
                                            QUEDAN: {{ quedan.id_quedan }}
                                            <br>
                                            MONTO TOTAL: {{ quedan.monto_liquido_quedan }}

                                        </p>
                                    </div>
                                </td>


                                <td class="px-2 first:pl-5 last:pr-5  whitespace-nowrap w-px">
                                    <div class="space-x-1 pl-3">
                                        <button class="text-rose-500 hover:text-rose-600 rounded-full"
                                            @click="openModal(data)">
                                            <span class="sr-only">Delete</span>

                                            <svg fill="#000000" width="24px" height="24px" viewBox="0 0 24 24"
                                                id="money-alt-1" data-name="Line Color" xmlns="http://www.w3.org/2000/svg"
                                                class="icon line-color">
                                                <path id="secondary"
                                                    d="M14,7H11.5A1.5,1.5,0,0,0,10,8.5h0A1.5,1.5,0,0,0,11.5,10h1A1.5,1.5,0,0,1,14,11.5h0A1.5,1.5,0,0,1,12.5,13H10"
                                                    style="fill: none; stroke: rgb(46, 158, 1); stroke-linecap: round; stroke-linejoin: round; stroke-width: 2;">
                                                </path>
                                                <path id="secondary-2" data-name="secondary" d="M12,6V7m0,6v1"
                                                    style="fill: none; stroke: rgb(46, 158, 1); stroke-linecap: round; stroke-linejoin: round; stroke-width: 2;">
                                                </path>
                                                <line id="primary" x1="6" y1="21" x2="18" y2="21"
                                                    style="fill: none; stroke: rgb(0, 0, 0); stroke-linecap: round; stroke-linejoin: round; stroke-width: 2;">
                                                </line>
                                                <rect id="primary-2" data-name="primary" x="3" y="3" width="18" height="14"
                                                    rx="1"
                                                    style="fill: none; stroke: rgb(0, 0, 0); stroke-linecap: round; stroke-linejoin: round; stroke-width: 2;">
                                                </rect>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </template>

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

        <ModalLiquidacion :scrollbarModalOpen="showModal" @close-definitive="showModal = false" :dataQuedan="dataQuedan"
            @actualizar-table-data="getDataLiquidaciones()" />

    </AppLayoutVue>
</template>
<script>

export default {

    data: function (data) {
        let sortOrders = {};
        let columns = [
            { width: "10%", label: "Id", name: "id_requerimiento_pago", type: "text" },
            { width: "10%", label: "Requerimiento", name: "numero_requerimiento_pago", type: "text" },
            { width: "20%", label: "Descripcion", name: "descripcion_requerimiento_pago", type: "text" },
            { width: "10%", label: "Monto requerido", name: "monto_requerimiento_pago", type: "text" },
            { width: "20%", label: "Todos los quedan", name: "allQUedan", type: "text" },
            { width: "5%", label: "", name: "Acciones" },
        ];
        columns.forEach((column) => {
            if (column.name === 'id_quedan')
                sortOrders[column.name] = 1;
            else
                sortOrders[column.name] = -1;
        });
        return {
            showModal: false,
            dataQuedan: [],
            dataSuppliers: null,//attr donde guardar la data de proveedores
            permits: [],
            dataForSelect: [],
            scrollbarModalOpen: false,
            dataRequerimientoForTable: [],
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
        }
    },
    methods: {
        async getDataLiquidaciones(url = "/requerimientos") {
            this.lastUrl = url
            this.tableData.draw++
            await axios.get(url, { params: this.tableData }).then((response) => {
                let data = response.data;
                if (this.tableData.draw == data.draw) {
                    this.links = data.data.links
                    //sumando los que requerimiento que tengan queda
                    let nuevoArray = data.data.data.filter(elemento => elemento.quedan != '')
                    let cantidad = nuevoArray.length;
                    this.pagination.total = cantidad
                    this.links[0].label = "Anterior"
                    this.links[this.links.length - 1].label = "Siguiente"
                    this.dataRequerimientoForTable = data.data.data
                }
            }).catch((errors) => {
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
                this.getDataLiquidaciones()
            }
        },
    },
    created() {
        this.getDataLiquidaciones()
    },

};
</script>
  
<style>
.col-6 {
    width: auto;
}
</style>