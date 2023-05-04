<script setup>
import { Head } from "@inertiajs/vue3";
import Datatable from "@/Components-ISRI/Datatable.vue";
import ModalQuedan from "@/Components-ISRI/Tesoreria/ModalQuedan.vue";
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
import axios from 'axios';
</script>
<template>
    <Head title="Quedan" />
    <AppLayoutVue>
        <div class="sm:flex sm:justify-end sm:items-center mb-2">
            <div class="grid grid-flow-col sm:auto-cols-max sm:justify-end gap-2">
                <GeneralButton @click="createQuedan()" color="bg-green-700  hover:bg-green-800" text="Agregar Elemento"
                    icon="add" />
            </div>
        </div>
        <div class="bg-white shadow-lg rounded-sm border border-slate-200 relative">
            <header class="px-5 py-4">
                <div class="mb-4 md:flex flex-row justify-items-start">
                    <div class="mb-4 md:mr-2 md:mb-0 basis-1/4">
                        <div class="relative flex h-8 w-full flex-row-reverse div-multiselect">
                            <Multiselect v-model="tableData.length" @select="getDataQuedan()" :options="perPage"
                                :searchable="true" />
                            <LabelToInput icon="date" />
                        </div>
                    </div>
                    <h2 class="font-semibold text-slate-800 pt-1">Todos los seguimientos de pagos
                        <span class="text-slate-400 font-medium">{{ pagination.total }}</span>
                    </h2>

                </div>

            </header>
            <div class="overflow-x-auto">
                <datatable :columns="columns" :sortKey="sortKey" :sortOrders="sortOrders" @sort="sortBy"
                    @datos-enviados="handleData($event)">
                    <tbody class="text-sm divide-y divide-slate-200">
                        <tr v-for="data in dataQuedanForTable" :key="data.id_quedan">
                            <td class="px-2 first:pl-5 last:pr-5  whitespace-nowrap w-px">
                                <div class="font-medium text-slate-800 text-center">{{ data.id_quedan }}</div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5  whitespace-nowrap w-px">
                                <div class="font-medium text-slate-800 text-center wrap">{{
                                    data.fecha_emision_quedan
                                }}</div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5  whitespace-nowrap w-px">
                                <div class="font-medium text-slate-800 text-center wrap">{{
                                    data.proveedor.razon_social_proveedor
                                }}</div>
                            </td>

                            <td class="first:pl-5 last:pr-5 whitespace-nowrap w-px">
                                <div v-for="(detalle, i) in  data.detalle_quedan" :key="i"
                                    class="font-medium text-slate-800 text-center flex justify-center items-center">
                                    <p
                                        :class="{ 'border-b-2 border-b-gray-500': i < data.detalle_quedan.length - 1 && data.detalle_quedan.length > 1 }">
                                        FACTURA: {{ detalle.numero_factura_det_quedan }}
                                        <br>
                                        PRODUCTO: ${{ (detalle.producto_factura_det_quedan !== '') ?
                                            detalle.producto_factura_det_quedan : '0.00' }}
                                        <br>
                                        SERVICIO: ${{ (detalle.servicio_factura_det_quedan !== '') ?
                                            detalle.servicio_factura_det_quedan : '0.00' }}
                                        <br>
                                        TOTAL: ${{
                                            (!isNaN(parseFloat(detalle.servicio_factura_det_quedan))
                                                ? parseFloat(detalle.servicio_factura_det_quedan) : 0) +

                                            (!isNaN(parseFloat(detalle.producto_factura_det_quedan)) ?
                                                parseFloat(detalle.producto_factura_det_quedan) : 0)
                                        }}
                                    </p>
                                </div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5  whitespace-nowrap w-px">
                                <div class="font-medium text-slate-800 text-center" v-if="data.requerimiento_pago">
                                    {{
                                        data.requerimiento_pago.numero_requerimiento_pago
                                    }}-{{ data.requerimiento_pago.anio_requerimiento_pago }}
                                </div>

                                <div class="font-medium text-slate-800 text-center" v-else>
                                    <div
                                        class="text-xs inline-flex font-medium bg-rose-100 text-rose-600 rounded-full text-center px-2.5 py-1">
                                        Sin asignar
                                    </div>
                                </div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5  whitespace-nowrap w-px">
                                <div class="font-medium text-slate-800 text-center">${{
                                    data.monto_liquido_quedan
                                }}</div>
                            </td>

                            <td class="px-2 first:pl-5 last:pr-5  whitespace-nowrap w-px">
                                <div class="font-medium text-slate-800 text-center">

                                    <div v-if="data.id_estado_quedan === 1"
                                        class="text-xs inline-flex font-medium bg-emerald-100 text-emerald-600 rounded-full text-center px-2.5 py-1">
                                        Abierto
                                    </div>

                                    <div v-else-if="data.id_estado_quedan === 2"
                                        class="text-xs inline-flex font-medium bg-blue-100 text-blue-600 rounded-full text-center px-2.5 py-1">
                                        Req. Asignado
                                    </div>
                                    <div v-else-if="data.id_estado_quedan === 3"
                                        class="text-xs inline-flex font-medium bg-blue-100 text-orange-600 rounded-full text-center px-2.5 py-1">
                                        Liq. Parcial
                                    </div>
                                    <div v-else-if="data.id_estado_quedan === 4"
                                        class="text-xs inline-flex font-medium bg-blue-100 text-red-600 rounded-full text-center px-2.5 py-1">
                                        Liquidado
                                    </div>
                                </div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5  whitespace-nowrap w-px">
                                <div class="space-x-1">
                                    <button @click.stop="showQuedan(data)" title="Ver Documento"
                                        class="text-slate-400 hover:text-slate-500 rounded-full">
                                        <!-- Uploaded to: SVG Repo, www.svgrepo.com, Transformed by: SVG Repo Mixer Tools -->
                                        <svg width="30px" height="30px" viewBox="0 0 24.00 24.00" fill="none"
                                            xmlns="http://www.w3.org/2000/svg" stroke="#000000" stroke-width="0.384">

                                            <g id="SVGRepo_bgCarrier" stroke-width="0" />

                                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round" />

                                            <g id="SVGRepo_iconCarrier">
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M11.9995 8.00001C9.79023 8.00001 8 9.79065 8 12.0005C8 14.2096 9.79043 16 11.9995 16C14.2082 16 16 14.21 16 12.0005C16 9.79024 14.2084 8.00001 11.9995 8.00001ZM10 12.0005C10 10.8948 10.8952 10 11.9995 10C13.1043 10 14 10.8952 14 12.0005C14 13.1046 13.1045 14 11.9995 14C10.895 14 10 13.105 10 12.0005Z"
                                                    fill="#152C70" />
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M16.2389 5.00144C13.5704 3.66721 10.4295 3.66721 7.76104 5.00144C6.14869 5.80761 4.79966 7.05685 3.8722 8.60262L2.65499 10.6313C2.14951 11.4738 2.14951 12.5263 2.65499 13.3687L3.8722 15.3974C4.79966 16.9432 6.14869 18.1924 7.76104 18.9986C10.4295 20.3328 13.5704 20.3328 16.2389 18.9986C17.8512 18.1924 19.2003 16.9432 20.1277 15.3974L21.3449 13.3687C21.8504 12.5263 21.8504 11.4738 21.3449 10.6313L20.1277 8.60262C19.2002 7.05685 17.8512 5.80761 16.2389 5.00144ZM8.65546 6.79029C10.7609 5.73759 13.239 5.73759 15.3444 6.79029C16.6166 7.42636 17.681 8.41201 18.4127 9.63161L19.6299 11.6603C19.7554 11.8694 19.7554 12.1306 19.6299 12.3397L18.4127 14.3684C17.681 15.588 16.6166 16.5737 15.3444 17.2097C13.239 18.2624 10.7609 18.2624 8.65546 17.2097C7.38332 16.5737 6.31895 15.588 5.58718 14.3684L4.36998 12.3397C4.24451 12.1306 4.24451 11.8694 4.36997 11.6603L5.58718 9.63161C6.31895 8.41201 7.38333 7.42636 8.65546 6.79029Z"
                                                    fill="#152C70" />
                                            </g>
                                        </svg>
                                    </button>
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
                                        <a @click="getDataQuedan(link.url)"
                                            class=" btn bg-white border-slate-200 hover:border-slate-300 cursor-pointer
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          text-indigo-500">
                                            &lt;-<span class="hidden sm:inline">&nbsp;Anterior</span>
                                        </a>
                                    </div>
                                </span>
                                <span v-else-if="(link.label == 'Siguiente')"
                                    :class="(link.active ? 'inline-flex items-center justify-center rounded-full leading-5 px-2 py-2 bg-white border border-slate-200 text-indigo-500 shadow-sm' : 'inline-flex items-center justify-center leading-5 px-2 py-2 text-slate-600 hover:text-indigo-500 border border-transparent')">
                                    <div class="flex-1 text-right ml-2">
                                        <a @click="getDataQuedan(link.url)"
                                            class=" btn bg-white border-slate-200 hover:border-slate-300 cursor-pointer
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          text-indigo-500">
                                            <span class="hidden sm:inline">Siguiente&nbsp;</span>-&gt;
                                        </a>
                                    </div>
                                </span>
                                <span class="cursor-pointer" v-else
                                    :class="(link.active ? 'inline-flex items-center justify-center rounded-full leading-5 px-2 py-2 bg-white border border-slate-200 text-indigo-500 shadow-sm' : 'inline-flex items-center justify-center leading-5 px-2 py-2 text-slate-600 hover:text-indigo-500 border border-transparent')"><span
                                        class=" w-5" @click="getDataQuedan(link.url)">{{ link.label }}</span>
                                </span>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>

        <ModalQuedan :showModal="showModal" @cerrar-modal="closeVars()" :data-quedan="dataQuedan"
            :dataForSelectInRow="dataForSelectInRow" @actualizar-table-data="getDataQuedan()"
            :dataSuppliers="dataSuppliers" />


    </AppLayoutVue>
</template>
<script>

export default {

    data: function (data) {
        let sortOrders = {};
        let columns = [
            { width: "10%", label: "Id", name: "id_quedan", type: "text" },
            { width: "10%", label: "Fecha de emision", name: "fecha_emision_quedan", type: "date" },
            { width: "30%", label: "Proveedor", name: "razon_social_proveedor", type: "text" },
            { width: "30%", label: "Detalle quedan", name: "buscar_por_detalle_quedan", type: "text" },
            { width: "10%", label: "Numero requerimiento", name: "numero_requerimiento_pago", type: "text" },

            { width: "10%", label: "Monto", name: "monto_liquido_quedan", type: "text" },
            {
                width: "10%", label: "Estado", name: "id_estado_quedan", type: "select",
                options: [
                    { value: "", label: "Ninguno" },
                    { value: "1", label: "Abierto" },
                    { value: "2", label: "Req.Asignado" }
                ]
            },
            { width: "5%", label: "", name: "Acciones" },
        ];
        columns.forEach((column) => {
            if (column.name === 'id_quedan')
                sortOrders[column.name] = 1;
            else
                sortOrders[column.name] = -1;
        });
        return {
            dropdownOpen: '',
            trigger: '',
            dropdown: '',
            isOpen: false,
            showModal: false,
            dataQuedan: [],
            dataSuppliers: null,//attr donde guardar la data de proveedores
            permits: [],
            dataForSelectInRow: [],
            scrollbarModalOpen: false,
            dataQuedanForTable: [],
            links: [],
            lastUrl: '/quedan',
            columns: columns,
            sortKey: "id_quedan",
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
        async getDataQuedan(url = "/quedan") {
            this.lastUrl = url;
            this.tableData.draw++;
            await axios.get(url, { params: this.tableData }).then((response) => {
                let data = response.data;
                if (this.tableData.draw == data.draw) {
                    this.links = data.data.links;
                    this.pagination.total = data.data.total
                    this.links[0].label = "Anterior";
                    this.links[this.links.length - 1].label = "Siguiente";
                    this.dataQuedanForTable = data.data.data;
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
                this.getDataQuedan();
            }
        },
        getIndex(array, key, value) {
            return array.findIndex((i) => i[key] == value);
        },

        closeVars() {
            this.showModal = false
        },
        async createQuedan() {

            this.dataQuedan = []
            this.showModal = true

        },
        async getListForSelect() {
            await axios.get('/get-list-select').then((response) => {

                this.dataForSelectInRow = response.data;
            }).catch((error) => {
            });
        },
        async showQuedan(dataQuedan) {
            this.dataQuedan = dataQuedan
            this.showModal = true
        },
        getAllSuppliers() {
            axios.get("/getAllSuppliers").then(res => {
                this.dataSuppliers = res.data
            })
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
                this.getDataQuedan()
            } else {
                this.tableData.search = myEventData;
                this.getDataQuedan()
            }
        },


    },
    created() {
        this.getAllSuppliers()
        this.getDataQuedan()
        this.getListForSelect()
    },

};
</script>
  
<style>.wrap,
.wrap2 {
    width: 100%;
    white-space: pre-wrap;
}</style>