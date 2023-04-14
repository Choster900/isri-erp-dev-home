<script setup>
import Datatable from "@/Components-ISRI/Datatable.vue";
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
import ModalBasicVue from '@/Components-ISRI/AllModal/ModalBasic.vue';
</script>
<template>
    <Head title="Administracion" />
    <AppLayoutVue>
        <div class="sm:flex sm:justify-end sm:items-center mb-2">
            <div class="grid grid-flow-col sm:auto-cols-max sm:justify-end gap-2">
                <GeneralButton color="bg-green-700  hover:bg-green-800" text="Agregar Elemento" icon="add"
                    @click="requerimientoModalOpen = !requerimientoModalOpen" />
            </div>
        </div>
        <div class="bg-white shadow-lg rounded-sm border border-slate-200 relative">
            <header class="px-5 py-4">
                <div class="mb-4 md:flex flex-row justify-items-start">
                    <div class="mb-4 md:mr-2 md:mb-0 basis-1/4">
                        <div class="relative flex h-8 w-full flex-row-reverse div-multiselect">
                            <Multiselect v-model="tableData.length" @select="getDataRequerimiento()" :options="perPage"
                                :searchable="true" />
                            <LabelToInput icon="date" />
                        </div>
                    </div>
                    <h2 class="font-semibold text-slate-800 pt-1">Todos los proveedores
                        <span class="text-slate-400 font-medium">{{ pagination.total }}</span>
                    </h2>
                </div>

            </header>
            <div class="overflow-x-auto">
                <datatable :columns="columns" :sortKey="sortKey" :sortOrders="sortOrders" @sort="sortBy"
                    @datos-enviados="handleData($event)">
                    <tbody class="text-sm divide-y divide-slate-200">
                        <tr v-for="requerimiento in requerimientos" :key="requerimiento.id_requerimiento_pago">
                            <td class="px-2 first:pl-5 last:pr-5  whitespace-nowrap w-px">
                                <div class="font-medium text-slate-800 text-center">{{ requerimiento.id_requerimiento_pago
                                }}
                                </div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5  whitespace-nowrap w-px">
                                <div class="font-medium text-slate-800 text-center">{{
                                    requerimiento.numero_requerimiento_pago }}</div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5  whitespace-nowrap w-px">
                                <div class="font-medium text-slate-800 text-center">{{
                                    requerimiento.mes_requerimiento_pago }}</div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5  whitespace-nowrap w-px">
                                <div class="font-medium text-slate-800 text-center">{{
                                    requerimiento.anio_requerimiento_pago }}</div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5  whitespace-nowrap w-px">
                                <div class="space-x-1">
                                    <button class="text-slate-400 hover:text-slate-500 rounded-full"
                                        @click="editRequerimiento(requerimiento)">
                                        <span class="sr-only">Edit</span>
                                        <svg class="w-8 h-8 fill-current" viewBox="0 0 32 32">
                                            <path
                                                d="M19.7 8.3c-.4-.4-1-.4-1.4 0l-10 10c-.2.2-.3.4-.3.7v4c0 .6.4 1 1 1h4c.3 0 .5-.1.7-.3l10-10c.4-.4.4-1 0-1.4l-4-4zM12.6 22H10v-2.6l6-6 2.6 2.6-6 6zm7.4-7.4L17.4 12l1.6-1.6 2.6 2.6-1.6 1.6z">
                                            </path>
                                        </svg>
                                    </button>
                                    <!-- CAMBIAR ICONO DE BOTON POR QUE VA A SER ACTIVAR Y DESCATIVAR -->
                                    <button class="text-rose-500 hover:text-rose-600 rounded-full">
                                        <span class="sr-only">Delete</span><svg class="w-8 h-8 fill-current"
                                            viewBox="0 0 32 32">
                                            <path d="M13 15h2v6h-2zM17 15h2v6h-2z">
                                            </path>
                                            <path
                                                d="M20 9c0-.6-.4-1-1-1h-6c-.6 0-1 .4-1 1v2H8v2h1v10c0 .6.4 1 1 1h12c.6 0 1-.4 1-1V13h1v-2h-4V9zm-6 1h4v1h-4v-1zm7 3v9H11v-9h10z">
                                            </path>
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



    <ModalBasicVue title="Numero de requerimiento " id="scrollbar-modal" maxWidth="1xl" :modalOpen="requerimientoModalOpen"
        @close-modal-persona="onCloseModalPersona()">

        <div class="py-5">

            <div class="mb-4 md:flex flex-row justify-center">
                <span class="font-semibold text-slate-800 mb-2 text-x underline underline-offset-2">
                    INFORMACIÓN REQUERIDA
                </span>
            </div>


            <div class="flex flex-wrap justify-center items-center mb-7">

                <div class="w-full md:w-auto mb-4 md:mb-0 md:mr-2">
                    <TextInput id="numero-requerimiento" type="text" v-model="dataRequerimiento.numero_requerimiento_pago"
                        placeholder="Numero de requerimiento">
                        <LabelToInput icon="personalInformation" forLabel="numero-requerimiento" />
                    </TextInput>
                </div>
                <div class="w-full md:w-auto">
                    <br>
                    <div class="pt-2"></div>

                    <template v-if="!dataRequerimiento.id_requerimiento_pago">
                        <GeneralButton color="bg-green-700  hover:bg-green-800" text="Agregar Elemento" icon="add"
                            @click="addRequerimiento()" />
                    </template>

                    <template v-else>
                        <GeneralButton color="bg-orange-700  hover:bg-orange-800" text="Modificar Elemento" icon="update"
                            @click="updateRequerimiento()" />
                    </template>
                </div>
            </div>

        </div>

    </ModalBasicVue>
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
            { width: "10%", label: "Id", name: "id_proveedor", type: "text" },
            { width: "10%", label: "Numero requerimiento", name: "numero_requerimiento_pago", type: "text" },
            { width: "10%", label: "Mes", name: "mes_requerimiento_pago", type: "text" },
            { width: "10%", label: "Año", name: "anio_requerimiento_pago", type: "text" },

            { width: "10%", label: "Acciones", name: "Acciones" },
        ];
        columns.forEach((column) => {
            if (column.name === 'id_persona')
                sortOrders[column.name] = 1;
            else
                sortOrders[column.name] = -1;
        });
        return {
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
            requerimientoModalOpen: false,
            dataRequerimiento: {
                id_requerimiento_pago: '',
                numero_requerimiento_pago: '',
                mes_requerimiento_pago: '',
                anio_requerimiento_pago: '',
            },
            errorNumber: null,
        };
    },
    methods: {
        async getDataRequerimiento(url = "/requerimientos") {
            this.lastUrl = url;
            this.tableData.draw++;
            await axios.get(url, { params: this.tableData }).then((response) => {
                let data = response.data;
                if (this.tableData.draw == data.draw) {
                    this.links = data.data.links;
                    this.pagination.total = data.data.total
                    this.links[0].label = "Anterior";
                    this.links[this.links.length - 1].label = "Siguiente";
                    this.requerimientos = data.data.data;
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
                this.getDataRequerimiento();
            }
        },
        addRequerimiento() {
            // Obtener la fecha actual
            let currentDate = new Date();
            this.dataRequerimiento.mes_requerimiento_pago = currentDate.getMonth() + 1;
            this.dataRequerimiento.anio_requerimiento_pago = currentDate.getFullYear();

            axios({
                method: 'POST',
                url: '/add-requerimiento',
                data: this.dataRequerimiento
            }).then((data) => {
                toast.success("Requerimiento agregado con extio", {
                    autoClose: 5000,
                    position: "top-right",
                    transition: "zoom",
                    toastBackgroundColor: "white",
                });
                this.getDataRequerimiento()

            }).catch((Error) => {
                console.log(Error.response.data.errors.numero_requerimiento_pago[0])
                this.errorNumber = Error.response.data.errors.numero_requerimiento_pago[0]
                toast.warning(`${this.errorNumber}`, {
                    autoClose: 5000,
                    position: "top-right",
                    transition: "zoom",
                    toastBackgroundColor: "white",
                });

                setTimeout(() => {
                    this.errorNumber = ''
                }, 9000);
            })

        },

        updateRequerimiento() {
            axios({
                method: 'POST',
                url: '/update-requerimiento',
                data: this.dataRequerimiento
            }).then((data) => {
                toast.success("Requerimiento actualizado con extio", {
                    autoClose: 5000,
                    position: "top-right",
                    transition: "zoom",
                    toastBackgroundColor: "white",
                });
                this.getDataRequerimiento()
            }).catch((Error) => {
                console.log(Error.response.data.errors.numero_requerimiento_pago[0])
                this.errorNumber = Error.response.data.errors.numero_requerimiento_pago[0]

                toast.warning(`${this.errorNumber}`, {
                    autoClose: 5000,
                    position: "top-right",
                    transition: "zoom",
                    toastBackgroundColor: "white",
                });

                setTimeout(() => {
                    this.errorNumber = ''
                }, 9000);
            })
        },

        editRequerimiento(request) {

            let newDataQuedan = JSON.parse(JSON.stringify(request))
            this.dataRequerimiento.id_requerimiento_pago = newDataQuedan.id_requerimiento_pago
            this.dataRequerimiento.numero_requerimiento_pago = newDataQuedan.numero_requerimiento_pago
            this.requerimientoModalOpen = !this.requerimientoModalOpen
        },

        onCloseModalPersona() {
            this.requerimientoModalOpen = !this.requerimientoModalOpen;


            this.dataRequerimiento.id_requerimiento_pago = ''
            this.dataRequerimiento.numero_requerimiento_pago = ''
        }

    },

};
</script>
  
<style>
.wrap,
.wrap2 {
    width: 70%;
    white-space: pre-wrap;
}
</style>