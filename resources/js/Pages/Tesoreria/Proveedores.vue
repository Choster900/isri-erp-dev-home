<script setup>
import { Head } from "@inertiajs/vue3";
import Datatable from "@/Components-ISRI/Datatable.vue";
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
import ModalProveedortVue from '@/Components-ISRI/Tesoreria/ModalProveedor.vue';
</script>
<template>
    <Head title="Catalogo - Proveedor" />
    <AppLayoutVue nameSubModule="Tesoreria - Proveedores">
        <div class="sm:flex sm:justify-end sm:items-center">
            <div class="grid grid-flow-col sm:auto-cols-max sm:justify-end gap-2">
                <GeneralButton v-if="permits.insertar == 1" @click="addDataSupplier()"
                    color="bg-green-700  hover:bg-green-800" text="Agregar Proveedor" icon="add" />
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
                <datatable :columns="columns" :sortKey="sortKey" :sortOrders="sortOrders" :searchButton="true"
                    @sort="sortBy" @datos-enviados="handleData($event)" @execute-search="getSuppilers()">
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
                                <div class="space-x-1 text-center">
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
                                            <div class="w-8">
                                                <svg v-if="proveedor.estado_proveedor == 1" width="28px"
                                                    style="margin-left: -2px;" height="28px" viewBox="0 0 24 24"
                                                    fill="none">
                                                    <path
                                                        d="M12.0769 19C13.5389 19 14.9634 18.532 16.1462 17.6631C17.329 16.7942 18.2094 15.569 18.6612 14.1631C19.1129 12.7572 19.1129 11.2428 18.6612 9.83688C18.2094 8.43098 17.329 7.20578 16.1462 6.33688C14.9634 5.46798 13.5389 5 12.0769 5C10.6149 5 9.19043 5.46799 8.00764 6.33688C6.82485 7.20578 5.94447 8.43098 5.49268 9.83688C5.0409 11.2428 5.0409 12.7572 5.49269 14.1631M6.5 12.7778L5.53846 14.3333L4 13.1667"
                                                        stroke="#a80000" stroke-linecap="round" stroke-linejoin="round">
                                                    </path>
                                                </svg>

                                                <svg v-else style="margin-left: -2px;" width="28px" height="28px"
                                                    viewBox="0 0 24 24" fill="none">
                                                    <path
                                                        d="M11.9231 19C10.4611 19 9.03659 18.532 7.85379 17.6631C6.671 16.7942 5.79063 15.569 5.33884 14.1631C4.88705 12.7572 4.88705 11.2428 5.33884 9.83688C5.79063 8.43098 6.671 7.20578 7.8538 6.33688C9.03659 5.46798 10.4611 5 11.9231 5C13.3851 5 14.8096 5.46799 15.9924 6.33688C17.1752 7.20578 18.0555 8.43098 18.5073 9.83688C18.9591 11.2428 18.9591 12.7572 18.5073 14.1631M17.5 12.7778L18.4615 14.3333L20 13.1667"
                                                        stroke="#006113" stroke-linecap="round" stroke-linejoin="round">
                                                    </path>

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
            <div v-if="empty_object" class="flex text-center py-2">
                <p class="font-semibold text-[16px]" style="margin: 0 auto; text-align: center;">No se encontraron
                    registros.</p>
            </div>
        </div>
        <div v-if="!empty_object" class="px-6 py-4 bg-white shadow-lg rounded-sm border-slate-200 relative">
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
        <ModalProveedortVue :ModalIsOpen="stateModal" @close-modal="stateModal = false" :infoSupplier="infoSupplier"
            @reload-table="getSuppilers(lastUrl)" />
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
            empty_object:false,
            permits: [],
            stateModal: false,
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
                    this.stateModal = false
                    this.proveedores.length>0 ? this.empty_object=false : this.empty_object=true
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
            this.stateModal = !this.stateModal;
        },
        addDataSupplier() {
            this.infoSupplier = [];
            this.stateModal = !this.stateModal;
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
            let stateToas = estado == 0 ? "habilitado" : "deshabilitado";

            this.$swal.fire({
                title: `¿Esta seguro de ${state}  el registro ?`,
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
                    toast.success(`El proveedor se ha ${stateToas} con exito`, {
                        autoClose: 4000,
                        position: "top-right",
                        transition: "zoom",
                        toastBackgroundColor: "white",
                    });
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
                //this.getSuppilers();
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