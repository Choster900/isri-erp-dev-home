<script setup>
import { Head } from "@inertiajs/vue3";
import Datatable from "@/Components-ISRI/Datatable.vue";
import ModalSuppliersVue from '@/Components-ISRI/Tesoreria/ModalSuppliers.vue';
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
</script>
<template>
    <Head title="Catalogo - Proveedor" />
    <AppLayoutVue nameSubModule="Tesoreria - Proveedores">
        <div class="sm:flex sm:justify-end sm:items-center mb-2">
            <div class="grid grid-flow-col sm:auto-cols-max sm:justify-end gap-2">
                <GeneralButton v-if="permits.insertar == 1" @click="addDataSupplier()"
                    color="bg-green-700  hover:bg-green-800" text="Agregar Elemento" icon="add" />
            </div>
        </div>
        <div class="bg-white shadow-lg rounded-sm border border-slate-200 relative">
            <!-- TODO: Improve view table - this is temporal and doesn't mean is permanent -->
            <header class="px-5 py-4">
                <div class="mb-4 md:flex flex-row justify-items-start">
                    <div class="mb-4 md:mr-2 md:mb-0 basis-1/4">
                        <div class="relative flex h-8 w-full flex-row-reverse div-multiselect">
                            <Multiselect v-model="tableData.length" @select="getSuppilers()" :options="perPage"
                                :searchable="true" placeholder="Cantidad a mostrar" />
                            <LabelToInput icon="list2" />
                        </div>
                    </div>
                    <h2 class="font-semibold text-slate-800 pt-1">Total Proveedores
                        <span class="text-slate-400 font-medium">{{ pagination.total }}</span>
                    </h2>
                </div>

            </header>
            <div class="overflow-x-auto">
                <datatable :columns="columns" :sortKey="sortKey" :sortOrders="sortOrders" @sort="sortBy"
                    @datos-enviados="handleData($event)">
                    <tbody class="text-sm divide-y divide-slate-200">
                        <tr v-for="proveedor in proveedores" :key="proveedor.id_proveedor">
                            <td class="px-2 first:pl-5 last:pr-5 td-data-table">
                                <div class="font-medium text-slate-800 text-center">{{ proveedor.id_proveedor }}</div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5 td-data-table">
                                <div v-if="proveedor.dui_proveedor" class="font-medium text-slate-800 text-center">
                                    {{ proveedor.dui_proveedor }}<br>
                                </div>
                                <div v-else class="font-medium text-slate-800 text-center ellipsis">
                                    {{ proveedor.nit_proveedor }}<br>
                                </div>
                            </td>

                            <td class="px-2 first:pl-5 last:pr-5 td-data-table">
                                <div class="font-medium text-slate-800 text-center ellipsis">{{
                                    proveedor.razon_social_proveedor }}
                                </div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5 td-data-table">
                                <div class="font-medium text-slate-800 text-center ellipsis">{{
                                    proveedor.nombre_comercial_proveedor
                                }}</div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5 td-data-table">
                                <div class="font-medium text-slate-800">
                                    <div v-if="(proveedor.estado_proveedor == 1)"
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
                                <div class="space-x-1">
                                    <DropDownOptions>
                                        <div class="flex hover:bg-gray-100 py-1 px-2 rounded cursor-pointer"
                                            v-if="permits.actualizar == 1 && proveedor.estado_proveedor == 1"
                                            @click.stop="getSuppiler(proveedor)">
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
                                            v-if="permits.eliminar == 1"
                                            @click="enableStateForSupplier(proveedor.id_proveedor, proveedor.estado_proveedor)">
                                            <div class="w-8 text-red-900">
                                                <!-- <span class="text-xs">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                        stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                    </svg>
                                                </span> -->
                                                <svg fill="#7F1D1D" version="1.1" id="Capa_1"
                                                    xmlns="http://www.w3.org/2000/svg" width="20px" height="20px"
                                                    xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 97.994 97.994"
                                                    xml:space="preserve" stroke="#7F1D1D">
                                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round"
                                                        stroke-linejoin="round"></g>
                                                    <g id="SVGRepo_iconCarrier">
                                                        <g>
                                                            <g>
                                                                <path
                                                                    d="M97.155,9.939c-0.582-0.416-1.341-0.49-1.991-0.193l-10.848,4.935C74.08,5.29,60.815,0.118,46.966,0.118 c-15.632,0-30.602,6.666-41.07,18.289c-0.359,0.399-0.543,0.926-0.51,1.461c0.033,0.536,0.28,1.036,0.686,1.388l11.301,9.801 c0.818,0.711,2.055,0.639,2.787-0.162c6.866-7.512,16.636-11.821,26.806-11.821c6.135,0,12.229,1.584,17.622,4.583l-7.826,3.561 c-0.65,0.296-1.095,0.916-1.163,1.627c-0.069,0.711,0.247,1.405,0.828,1.82l34.329,24.52c0.346,0.246,0.753,0.373,1.163,0.373 c0.281,0,0.563-0.06,0.828-0.181c0.65-0.296,1.095-0.916,1.163-1.627l4.075-41.989C98.053,11.049,97.737,10.355,97.155,9.939z">
                                                                </path>
                                                                <path
                                                                    d="M80.619,66.937c-0.819-0.709-2.055-0.639-2.787,0.162c-6.866,7.514-16.638,11.822-26.806,11.822 c-6.135,0-12.229-1.584-17.622-4.583l7.827-3.561c0.65-0.296,1.094-0.916,1.163-1.628c0.069-0.711-0.247-1.404-0.828-1.819 L7.237,42.811c-0.583-0.416-1.341-0.49-1.991-0.193c-0.65,0.296-1.094,0.916-1.163,1.627L0.009,86.233 c-0.069,0.712,0.247,1.406,0.828,1.822c0.583,0.416,1.341,0.488,1.991,0.192l10.848-4.935 c10.237,9.391,23.502,14.562,37.351,14.562c15.632,0,30.602-6.666,41.07-18.289c0.358-0.398,0.543-0.926,0.51-1.461 c-0.033-0.536-0.28-1.036-0.687-1.388L80.619,66.937z">
                                                                </path>
                                                            </g>
                                                        </g>
                                                    </g>
                                                </svg>
                                            </div>
                                            <div class="font-semibold">
                                                {{ proveedor.estado_proveedor ? 'Desactivar' : 'Activar' }}
                                            </div>
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
                                        <a @click="getSuppilers(link.url)"
                                            class=" btn bg-white border-slate-200 hover:border-slate-300 cursor-pointer
                                                                                                                                                                                                                                                                                                                                                                                                                                      text-indigo-500">
                                            &lt;-<span class="hidden sm:inline">&nbsp;Anterior</span>
                                        </a>
                                    </div>
                                </span>
                                <span v-else-if="(link.label == 'Siguiente')"
                                    :class="(link.active ? 'inline-flex items-center justify-center rounded-full leading-5 px-2 py-2 bg-white border border-slate-200 text-indigo-500 shadow-sm' : 'inline-flex items-center justify-center leading-5 px-2 py-2 text-slate-600 hover:text-indigo-500 border border-transparent')">
                                    <div class="flex-1 text-right ml-2">
                                        <a @click="getSuppilers(link.url)"
                                            class=" btn bg-white border-slate-200 hover:border-slate-300 cursor-pointer
                                                                                                                                                                                                                                                                                                                                                                                                                                      text-indigo-500">
                                            <span class="hidden sm:inline">Siguiente&nbsp;</span>-&gt;
                                        </a>
                                    </div>
                                </span>
                                <span class="cursor-pointer" v-else
                                    :class="(link.active ? 'inline-flex items-center justify-center rounded-full leading-5 px-2 py-2 bg-white border border-slate-200 text-indigo-500 shadow-sm' : 'inline-flex items-center justify-center leading-5 px-2 py-2 text-slate-600 hover:text-indigo-500 border border-transparent')"><span
                                        class=" w-5" @click="getSuppilers(link.url)">{{ link.label }}</span>
                                </span>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>

        <ModalSuppliersVue :scrollbarModalOpen="scrollbarModalOpen" @close-definitive="scrollbarModalOpen = false"
            :infoSupplier="infoSupplier" @showTableAgain="getSuppilers(lastUrl)" />


    </AppLayoutVue>
</template>
<script>
export default {
    created() {
        this.getSuppilers();
        this.getPermits();
    },
    data: function (data) {
        let sortOrders = {};
        let columns = [
            { width: "5%", label: "Id", name: "id_proveedor", type: "text" },
            { width: "10%", label: "Documentos", name: "dui_proveedor", type: "text" },
            { width: "10%", label: "Razon social", name: "razon_social_proveedor", type: "text" },
            { width: "10%", label: "Nombre comercial", name: "nombre_comercial_proveedor", type: "text" },
            {
                width: "1%",
                label: "Estado",
                name: "estado_proveedor",
                type: "select",
                options: [
                    { value: "", label: "Ninguno" },
                    { value: "1", label: "Activo" },
                    { value: "0", label: "Inactivo" }
                ]
            },
            { width: "1%", label: "", name: "Acciones" },
        ];
        columns.forEach((column) => {
            if (column.name === "id_proveedor")
                sortOrders[column.name] = 1;
            else
                sortOrders[column.name] = -1;
        });
        return {
            permits: [],
            scrollbarModalOpen: false,
            proveedores: [],
            links: [],
            lastUrl: "/proveedores",
            columns: columns,
            sortKey: "id_proveedor",
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
            infoSupplier: [],
        };
    },
    methods: {
        async getSuppilers(url = "/proveedores") {
            this.lastUrl = url;
            this.tableData.draw++;
            await axios.post(url, this.tableData).then((response) => {
                let data = response.data;
                if (this.tableData.draw == data.draw) {
                    this.links = data.data.links;
                    this.pagination.total = data.data.total;
                    this.links[0].label = "Anterior";
                    this.links[this.links.length - 1].label = "Siguiente";
                    this.proveedores = data.data.data;
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
                this.getSuppilers();
            }
        },
        getIndex(array, key, value) {
            return array.findIndex((i) => i[key] == value);
        },
        async getSuppiler(supplier) {
            this.infoSupplier = supplier;
            this.scrollbarModalOpen = !this.scrollbarModalOpen;
        },
        addDataSupplier() {
            this.infoSupplier = [];
            this.scrollbarModalOpen = !this.scrollbarModalOpen;
        },
        async enable(id_persona, estado) {
            await axios.post("/update-state-supplier", { id_proveedor: id_persona, estado_proveedor: estado })
                .then(res => {
                    console.log(res);
                })
                .catch(errors => {
                    let msg = this.manageError(errors);
                    this.$swal.fire({
                        title: "Operación cancelada",
                        text: msg,
                        icon: "warning",
                        timer: 5000,
                    });
                });
            this.getSuppilers(this.lastUrl); //llamamos de nuevo el metodo para que actualize la tabla 
        },
        enableStateForSupplier(id_proveedor, estado) {
            let state = estado == 0 ? "habilitar" : "deshabilitar";
            this.$swal.fire({
                title: "¿Esta seguro de " + state + " el registro ?",
                icon: "question",
                iconHtml: "❓",
                confirmButtonText: "Si, " + state + "",
                confirmButtonColor: "#001b47",
                cancelButtonText: "Cancelar",
                showCancelButton: true,
                showCloseButton: true
            }).then((result) => {
                if (result.isConfirmed) {
                    this.enable(id_proveedor, estado); //peticion async hace la modificacion 
                    //no la llamamos en el mismo metodo por que dejaria de ser asyn y hay problema al momento de actulizar la tabla
                    this.$swal.fire({
                        text: 'Acción realizada!',
                        icon: 'success',
                        timer: 5000
                    })
                }
            });
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
                this.getSuppilers();
            }
            else {
                this.tableData.search = myEventData;
                this.getSuppilers();
            }
        },
        getPermits() {
            var URLactual = window.location.pathname;
            let data = this.$page.props.menu;
            let menu = JSON.parse(JSON.stringify(data["urls"]));
            menu.forEach((value, index) => {
                value.submenu.forEach((value2, index2) => {
                    if (value2.url === URLactual) {
                        var array = { "insertar": value2.insertar, "actualizar": value2.actualizar, "eliminar": value2.eliminar, "ejecutar": value2.ejecutar };
                        this.permits = array;
                    }
                });
            });
        },
    },
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

.wrap,
.wrap2 {
    width: 70%;
    white-space: pre-wrap;
}
</style>