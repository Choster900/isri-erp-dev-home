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
                                        <button class="text-rose-500 hover:text-rose-600 rounded-full" title="Liquidar Req."
                                            @click="openModal(data)">
                                            <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg"
                                                xmlns:xlink="http://www.w3.org/1999/xlink"
                                                viewBox="-20.48 -20.48 552.96 552.96" xml:space="preserve" width="25px"
                                                height="25px" fill="#000000" stroke="#000000" stroke-width="4.096"
                                                transform="matrix(-1, 0, 0, -1, 0, 0)rotate(0)">
                                                <path style="fill:#1a539e;"
                                                    d="M467.163,512H44.837c-7.586,0-13.734-6.149-13.734-13.734V13.734C31.102,6.149,37.251,0,44.837,0 h422.326c7.586,0,13.734,6.149,13.734,13.734v252.338c0,7.586-6.149,13.734-13.734,13.734s-13.734-6.149-13.734-13.734V27.469 H58.571v457.062h394.857V349.393c0-7.586,6.149-13.734,13.734-13.734s13.734,6.149,13.734,13.734v148.872 C480.898,505.851,474.749,512,467.163,512z" />
                                                <rect x="214.793" y="67.265" style="fill:#e8773b;" width="192.283"
                                                    height="54.481" />
                                                <path style="fill:#1a539e;"
                                                    d="M407.079,135.48H214.796c-7.586,0-13.734-6.149-13.734-13.734V67.266 c0-7.586,6.149-13.734,13.734-13.734h192.283c7.586,0,13.734,6.149,13.734,13.734v54.481 C420.813,129.331,414.664,135.48,407.079,135.48z M228.53,108.011h164.814v-27.01H228.53V108.011z" />
                                                <rect x="214.793" y="174.923" style="fill:#e8773b;" width="192.283"
                                                    height="54.481" />
                                                <path style="fill:#1a539e;"
                                                    d="M407.079,243.143H214.796c-7.586,0-13.734-6.149-13.734-13.734v-54.481 c0-7.586,6.149-13.734,13.734-13.734h192.283c7.586,0,13.734,6.149,13.734,13.734v54.481 C420.813,236.994,414.664,243.143,407.079,243.143z M228.53,215.674h164.814v-27.012H228.53V215.674z" />
                                                <rect x="214.793" y="282.587" style="fill:#e8773b;" width="192.283"
                                                    height="54.481" />
                                                <g>
                                                    <path style="fill:#1a539e;"
                                                        d="M407.079,350.806H214.796c-7.586,0-13.734-6.149-13.734-13.734v-54.481 c0-7.586,6.149-13.734,13.734-13.734h192.283c7.586,0,13.734,6.149,13.734,13.734v54.481 C420.813,344.658,414.664,350.806,407.079,350.806z M228.53,323.337h164.814v-27.012H228.53V323.337z" />
                                                    <path style="fill:#1a539e;"
                                                        d="M407.079,458.47H214.796c-7.586,0-13.734-6.149-13.734-13.734s6.149-13.734,13.734-13.734h178.548 v-27.012H214.796c-7.586,0-13.734-6.149-13.734-13.734s6.149-13.734,13.734-13.734h192.283c7.586,0,13.734,6.149,13.734,13.734 v54.481C420.813,452.321,414.664,458.47,407.079,458.47z" />
                                                    <path style="fill:#1a539e;"
                                                        d="M159.858,135.48H104.92c-7.586,0-13.734-6.149-13.734-13.734V67.266 c0-7.586,6.149-13.734,13.734-13.734h54.938c7.586,0,13.734,6.149,13.734,13.734v54.481 C173.592,129.331,167.443,135.48,159.858,135.48z M118.654,108.011h27.469v-27.01h-27.469V108.011z" />
                                                    <path style="fill:#1a539e;"
                                                        d="M159.858,243.143H104.92c-7.586,0-13.734-6.149-13.734-13.734v-54.481 c0-7.586,6.149-13.734,13.734-13.734h54.938c7.586,0,13.734,6.149,13.734,13.734v54.481 C173.592,236.994,167.443,243.143,159.858,243.143z M118.654,215.674h27.469v-27.012h-27.469V215.674z" />
                                                    <path style="fill:#1a539e;"
                                                        d="M159.858,350.806H104.92c-7.586,0-13.734-6.149-13.734-13.734v-54.481 c0-7.586,6.149-13.734,13.734-13.734h54.938c7.586,0,13.734,6.149,13.734,13.734v54.481 C173.592,344.658,167.443,350.806,159.858,350.806z M118.654,323.337h27.469v-27.012h-27.469V323.337z" />
                                                </g>
                                                <rect x="104.917" y="390.252" style="fill:#e8773b;" width="54.938"
                                                    height="54.481" />
                                                <path style="fill:#1a539e;"
                                                    d="M159.858,458.47H104.92c-7.586,0-13.734-6.149-13.734-13.734v-54.481 c0-7.586,6.149-13.734,13.734-13.734h54.938c7.586,0,13.734,6.149,13.734,13.734v54.481 C173.592,452.321,167.443,458.47,159.858,458.47z M118.654,431.001h27.469v-27.012h-27.469V431.001z" />

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