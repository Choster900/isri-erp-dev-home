<script setup>
import { Head } from "@inertiajs/vue3";
import Datatable from "@/Components-ISRI/Datatable.vue";
import ModalQuedan from "@/Components-ISRI/Tesoreria/ModalAsignacionRequerimiento.vue";
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
import axios from 'axios';
</script>
<template>
    <Head title="Proceso - Asignacion" />
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
                                    <DropDownOptions>
                                        <div class="flex hover:bg-gray-100 py-1 px-2 rounded cursor-pointer"
                                            v-if="data.id_estado_quedan === 2" @click="takeOf(data.id_quedan)">
                                            <div class="w-8 text-orange-900">
                                                <span class="text-xs">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                        stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                    </svg>
                                                </span>
                                            </div>
                                            <div class="font-semibold">Desactivar</div>
                                        </div>
                                        <div class="flex hover:bg-gray-100 py-1 px-2 rounded cursor-not-allowed" v-else>
                                            <div class="w-8 text-blue-900">
                                                <span class="text-xs">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                        stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                </span>
                                            </div>
                                            <div class="font-semibold">En liquidacion</div>
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
                this.getDataQuedan();
            }
        },
        getIndex(array, key, value) {
            return array.findIndex((i) => i[key] == value);
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
  
<style>
.wrap,
.wrap2 {
    width: 100%;
    white-space: pre-wrap;
}
</style>