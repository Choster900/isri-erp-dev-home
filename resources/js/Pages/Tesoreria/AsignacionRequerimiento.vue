<script setup>
import { Head } from "@inertiajs/vue3";
import Datatable from "@/Components-ISRI/Datatable.vue";
import ModalQuedan from "@/Components-ISRI/Tesoreria/ModalAsignacionRequerimiento.vue";
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
import axios from 'axios';
</script>
<template>
    <Head title="Administracion" />
    <AppLayoutVue>
        <div class="sm:flex sm:justify-end sm:items-center mb-2">
            <div class="grid grid-flow-col sm:auto-cols-max sm:justify-end gap-2">
                <GeneralButton @click="modalAsignacionRequerimiento()" color="bg-green-700  hover:bg-green-800"
                    text="Agregar Elemento" icon="add" />
            </div>
        </div>
        <div class="bg-white shadow-lg rounded-sm border border-slate-200 relative">
            <!-- TODO: Improve view table - this is temporal and doesn't mean is permanent -->
            <header class="px-5 py-4">
                <div class="mb-4 md:flex flex-row justify-items-start">
                    <div class="mb-4 md:mr-2 md:mb-0 basis-1/4">
                        <div class="relative flex h-8 w-full flex-row-reverse div-multiselect">
                            <Multiselect v-model="tableData.length" @select="getDataQuedan()" :options="perPage"
                                :searchable="true" />
                            <LabelToInput icon="date" />
                        </div>
                    </div>
                    <h2 class="font-semibold text-slate-800 pt-1">Todos los requerimientos
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

                            <td class="px-2 first:pl-5 last:pr-5 whitespace-nowrap w-px">
                                <div v-for="(detalle, i) in  data.detalle_quedan" :key="i"
                                    class="font-medium text-slate-800 text-center wrap flex justify-center items-center">
                                    <p
                                        :class="{ 'border-b-2 border-b-gray-500': i < data.detalle_quedan.length - 1 && data.detalle_quedan.length > 1 }">
                                        FACTURA: {{ detalle.numero_factura_det_quedan }}
                                        <br>
                                        PRODUCTO: ${{ detalle.producto_factura_det_quedan }}
                                        <br>
                                        SERVICIO: ${{ detalle.servicio_factura_det_quedan }}
                                        <br>
                                        TOTAL: ${{ parseFloat(detalle.servicio_factura_det_quedan) +
                                            parseFloat(detalle.producto_factura_det_quedan) }}
                                    </p>
                                </div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5  whitespace-nowrap w-px">
                                <div class="font-medium text-slate-800 text-center">{{
                                    data.requerimiento_pago.numero_requerimiento_pago
                                }}-{{ data.requerimiento_pago.anio_requerimiento_pago }}</div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5  whitespace-nowrap w-px">
                                <div class="font-medium text-slate-800 text-center">
                                    COMPROMISO: {{ data.numero_compromiso_ppto_quedan }}
                                    <hr class="border-b-2 border-b-gray-500">
                                    ACUERDO: {{ data.numero_acuerdo_quedan }}
                                </div>
                            </td>

                            <td class="px-2 first:pl-5 last:pr-5  whitespace-nowrap w-px">
                                <div class="font-medium text-slate-800 text-center">{{
                                    data.monto_liquido_quedan
                                }}</div>
                            </td>


                            <td class="px-2 first:pl-5 last:pr-5  whitespace-nowrap w-px">
                                <div class="space-x-1">
                                    <button class="text-rose-500 hover:text-rose-600 rounded-full" title="Eliminar Req."
                                        v-if="data.id_estado_quedan === 2" @click="takeOf(data.id_quedan)">
  

                                        <!-- Uploaded to: SVG Repo, www.svgrepo.com, Transformed by: SVG Repo Mixer Tools -->
                                        <svg fill="#000000" width="35px" height="35px" viewBox="0 0 24 24" id="delete-alt"
                                            data-name="Flat Color" xmlns="http://www.w3.org/2000/svg"
                                            class="icon flat-color" stroke="#000000" stroke-width="0.264">

                                            <g id="SVGRepo_bgCarrier" stroke-width="0" />

                                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round" />

                                            <g id="SVGRepo_iconCarrier">

                                                <path id="secondary"
                                                    d="M16,8a1,1,0,0,1-1-1V4H9V7A1,1,0,0,1,7,7V4A2,2,0,0,1,9,2h6a2,2,0,0,1,2,2V7A1,1,0,0,1,16,8Z"
                                                    style="fill: #ffffff;" />

                                                <path id="primary"
                                                    d="M20,6H4A1,1,0,0,0,4,8H5V20a2,2,0,0,0,2,2H17a2,2,0,0,0,2-2V8h1a1,1,0,0,0,0-2Z"
                                                    style="fill: #c10000e0;" />

                                                <path id="secondary-2" data-name="secondary"
                                                    d="M10,18a1,1,0,0,1-1-1V11a1,1,0,0,1,2,0v6A1,1,0,0,1,10,18Zm5-1V11a1,1,0,0,0-2,0v6a1,1,0,0,0,2,0Z"
                                                    style="fill: #ffffff;" />

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


        <ModalQuedan :scrollbarModalOpen="showModal" @close-definitive="showModal = false" :dataForSelect="dataForSelect"
            @reload-table="getDataQuedan()" />

    </AppLayoutVue>
</template>
<script>

export default {

    data: function (data) {
        let sortOrders = {};
        let columns = [
            { width: "10%", label: "Id", name: "id_quedan", type: "text" },
            { width: "10%", label: "Fecha de emision", name: "fecha_emision_quedan", type: "date" },
            { width: "20%", label: "Proveedor", name: "razon_social_proveedor", type: "text" },
            { width: "30%", label: "Detalle quedan", name: "buscar_por_detalle_quedan", type: "text" },
            { width: "10%", label: "Numero requerimiento", name: "numero_requerimiento_pago", type: "text" },
            { width: "10%", label: "Numero compromiso numero acuerdo", name: "id_requerimiento_pago", type: "text" },
            { width: "10%", label: "Monto", name: "monto_liquido_quedan", type: "text" },

            { width: "10%", label: "", name: "Acciones" },
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
                allWithANumberRequest: 1,
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

        async getListForSelect() {
            await axios.get('/get-list-select').then((response) => {

                this.dataForSelect = response.data;
            }).catch((error) => {
            });
        },

        closeVars() {
            this.showModal = false
        },
        async modalAsignacionRequerimiento() {

            this.dataQuedan = []
            this.showModal = true

        },
        async takeOfNumberRequest(id_quedan) {

            try {
                await axios.post('/take-of-number-request', { id_quedan: id_quedan })
                this.getDataQuedan(this.lastUrl)
                return true; // indicate success
            } catch (error) {
                return false; // indicate failure
            }
        },

        async takeOf(id_quedan) {
            const confirmed = await this.$swal.fire({
                title: `Esta seguro de remover el numero de requerimiento al quedan N° ${id_quedan} ?`,
                icon: 'question',
                iconHtml: '❓',
                confirmButtonText: 'Si, Remover el numero de requerimiento',
                confirmButtonColor: '#D2691E',
                cancelButtonText: 'Cancelar',
                showCancelButton: true,
                showCloseButton: true
            });
            if (confirmed.isConfirmed) {
                const successTakeOf = await this.takeOfNumberRequest(id_quedan);

                if (successTakeOf) {
                    toast.warning(`Se removio el numero de requerimiento al quedan ${id_quedan}`, {
                        autoClose: 5000,
                        position: "top-right",
                        transition: "zoom",
                        toastBackgroundColor: "white",
                    });

                } else {
                    toast.error("ERROR", {
                        autoClose: 5000,
                        position: "top-right",
                        transition: "zoom",
                        toastBackgroundColor: "white",
                    });
                }
            }


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