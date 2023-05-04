<script setup>
import { Head } from "@inertiajs/vue3";
import Datatable from "@/Components-ISRI/Datatable.vue";
import ModalSuppliersVue from '@/Components-ISRI/Tesoreria/ModalSuppliers.vue';
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';  
</script>
<template>
    <Head title="Administracion" />
    <AppLayoutVue>
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
                                :searchable="true" />
                            <LabelToInput icon="date" />
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
                                    <button v-if="permits.actualizar == 1" @click.stop="getSuppiler(proveedor)"
                                        class="text-slate-400 hover:text-slate-500 rounded-full">
                                        <span class="sr-only">Edit</span>
                                        <svg width="25px" height="25px" viewBox="0 0 24.00 24.00" fill="none"
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
                                    <!-- CAMBIAR ICONO DE BOTON POR QUE VA A SER ACTIVAR Y DESCATIVAR -->
                                    <button class="text-rose-500 hover:text-rose-600 rounded-full"
                                        v-if="permits.eliminar == 1"
                                        @click="enableStateForSupplier(proveedor.id_proveedor, proveedor.estado_proveedor)">

                                        <svg fill="#000000" width="25px" height="25px" viewBox="0 0 24 24" id="delete-alt"
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
        this.getPermits()
    },
    data: function (data) {
        let sortOrders = {};
        let columns = [
            { width: "10%", label: "Id", name: "id_proveedor", type: "text" },
            { width: "15%", label: "Documentos", name: "dui_proveedor", type: "text" },
            { width: "20%", label: "Razon social", name: "razon_social_proveedor", type: "text" },
            { width: "20%", label: "Nombre comercial", name: "nombre_comercial_proveedor", type: "text" },
            {
                width: "10%", label: "Estado", name: "estado_proveedor", type: "select",
                options: [
                    { value: "", label: "Ninguno" },
                    { value: "1", label: "Activo" },
                    { value: "0", label: "Inactivo" }
                ]
            },
            { width: "5%", label: "", name: "Acciones" },
        ];
        columns.forEach((column) => {
            if (column.name === 'id_proveedor')
                sortOrders[column.name] = 1;
            else
                sortOrders[column.name] = -1;
        });
        return {
            permits: [],
            scrollbarModalOpen: false,
            proveedores: [],
            links: [],
            lastUrl: '/proveedores',
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
            await axios.get(url, { params: this.tableData }).then((response) => {
                let data = response.data;
                if (this.tableData.draw == data.draw) {
                    this.links = data.data.links;
                    this.pagination.total = data.data.total
                    this.links[0].label = "Anterior";
                    this.links[this.links.length - 1].label = "Siguiente";
                    this.proveedores = data.data.data;
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
                this.getSuppilers();
            }
        },
        getIndex(array, key, value) {
            return array.findIndex((i) => i[key] == value);
        },
        async getSuppiler(supplier) {
            this.infoSupplier = supplier
            this.scrollbarModalOpen = !this.scrollbarModalOpen
        },
        addDataSupplier() {
            this.infoSupplier = []
            this.scrollbarModalOpen = !this.scrollbarModalOpen

        },
        async enable(id_persona, estado) {
            await axios.post("/update-state-supplier", { id_proveedor: id_persona, estado_proveedor: estado })
                .then(res => {
                    console.log(res)
                })
                .catch(err => {
                    console.error(err);
                })
            this.getSuppilers(this.lastUrl)//llamamos de nuevo el metodo para que actualize la tabla 
        },

        enableStateForSupplier(id_proveedor, estado) {
            let state = estado == 0 ? 'habilitar' : 'deshabilitar'
            this.$swal.fire({
                title: '¿Esta seguro de ' + state + ' el registro ?',
                icon: 'question',
                iconHtml: '❓',
                confirmButtonText: 'Si, ' + state + '',
                confirmButtonColor: '#001b47',
                cancelButtonText: 'Cancelar',
                showCancelButton: true,
                showCloseButton: true
            }).then((result) => {
                if (result.isConfirmed) {
                    this.enable(id_proveedor, estado)//peticion async hace la modificacion 
                    //no la llamamos en el mismo metodo por que dejaria de ser asyn y hay problema al momento de actulizar la tabla
                    toast.info("Hecho", {
                        autoClose: 5000,
                        position: "top-right",
                        transition: "bounce",
                        toastBackgroundColor: "white",
                        icon: "✔️",
                    });
                }
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
                this.getSuppilers()
            } else {
                this.tableData.search = myEventData;
                this.getSuppilers()
            }
        },
        getPermits() {
            var URLactual = window.location.pathname
            let data = this.$page.props.menu;
            let menu = JSON.parse(JSON.stringify(data['urls']))
            menu.forEach((value, index) => {
                value.submenu.forEach((value2, index2) => {
                    if (value2.url === URLactual) {
                        var array = { 'insertar': value2.insertar, 'actualizar': value2.actualizar, 'eliminar': value2.eliminar, 'ejecutar': value2.ejecutar }
                        this.permits = array
                    }
                })
            })
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