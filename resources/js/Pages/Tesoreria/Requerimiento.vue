<script setup>
import Datatable from "@/Components-ISRI/Datatable.vue";
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
import ModalBasicVue from '@/Components-ISRI/AllModal/ModalBasic.vue';
import ProcessModal from '@/Components-ISRI/AllModal/ProcessModal.vue'
import InputError from "@/Components/InputError.vue";
</script>
<template>
    <Head title="Catalogo - Requerimiento" />
    <AppLayoutVue>
        <div class="sm:flex sm:justify-end sm:items-center mb-2">
            <div class="grid grid-flow-col sm:auto-cols-max sm:justify-end gap-2">
                <GeneralButton color="bg-green-700  hover:bg-green-800" text="Agregar Elemento" icon="add"
                    @click="openModal()" />
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
                    <h2 class="font-semibold text-slate-800 pt-1">Total Requerimientos
                        <span class="text-slate-400 font-medium">{{ pagination.total }}</span>
                    </h2>
                </div>

            </header>
            <div class="overflow-x-auto">
                <datatable :columns="columns" :sortKey="sortKey" :sortOrders="sortOrders" @sort="sortBy"
                    @datos-enviados="handleData($event)">
                    <tbody class="text-sm divide-y divide-slate-200">
                        <tr v-for="requerimiento in requerimientos" :key="requerimiento.id_requerimiento_pago">
                            <td class="px-2 first:pl-5 last:pr-5 td-data-table">
                                <div class="font-medium text-slate-800 text-center">
                                    {{ requerimiento.numero_requerimiento_pago }}
                                </div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5 td-data-table">
                                <div class="font-medium text-slate-800 text-center ellipsis">
                                    {{ requerimiento.descripcion_requerimiento_pago }}
                                </div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5 td-data-table">
                                <div class="font-medium text-slate-800 text-center">
                                    {{ requerimiento.monto_requerimiento_pago ? '$ ' +
                                        requerimiento.monto_requerimiento_pago
                                        : '$ 0.00' }}
                                </div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5 td-data-table">
                                <div class="font-medium text-slate-800 text-center">
                                    {{ requerimiento.mes_requerimiento_pago }}
                                </div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5 td-data-table">
                                <div class="font-medium text-slate-800 text-center">
                                    {{ requerimiento.anio_requerimiento_pago }}
                                </div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5 td-data-table">
                                <div class="space-x-1">

                                    <DropDownOptions>
                                        <div class="flex hover:bg-gray-100 py-1 px-2 rounded cursor-pointer"
                                            @click="editRequerimiento(requerimiento)">
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
                                            @click="viewRequest(requerimiento)">
                                            <div class="w-8 text-blue-900">
                                                <span class="text-xs">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                        stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
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


    <ModalBasicVue title="Gestión número de requerimiento. " id="scrollbar-modal" maxWidth="2xl"
        :modalOpen="requerimientoModalOpen" @close-modal-persona="requerimientoModalOpen = false">
        <div class="py-5">
            <div class="flex-row justify-center items-center mb-7 mx-4">
                <div class="mb-4 md:flex flex-row justify-items-start mx-8">
                    <div class="md:mr-2 md:mb-0 w-1/2">
                        <TextInput v-model="dataRequerimiento.numero_requerimiento_pago" :label-input="true"
                            id="numero-requerimiento" type="text" placeholder="Requerimiento">
                            <LabelToInput icon="personalInformation" forLabel="numero-requerimiento" />
                        </TextInput>
                        <InputError v-for="(item, index) in errors.numero_requerimiento_pago" :key="index" class="mt-2"
                            :message="item" />
                    </div>
                    <div class="md:mr-0 md:mb-0 w-1/2">
                        <TextInput v-model="dataRequerimiento.monto_requerimiento_pago" :label-input="true"
                            @update:modelValue="typeAmount()" id="monto-requerimiento" type="text" placeholder="Monto">
                            <LabelToInput icon="money" forLabel="monto-requerimiento" />
                        </TextInput>
                        <InputError v-for="(item, index) in errors.monto_requerimiento_pago" :key="index" class="mt-2"
                            :message="item" />
                    </div>
                </div>
                <div class="mb-4 mx-8 basis-full" style="border: none; background-color: transparent;">
                    <label class="block mb-2 text-xs font-light text-gray-600" for="descripcion">
                        Descripcion <span class="text-red-600 font-extrabold">*</span>
                    </label>
                    <textarea v-model="dataRequerimiento.descripcion_requerimiento_pago" id="descripcion" name="descripcion"
                        class="resize-none w-full h-16 overflow-y-auto peer text-xs font-semibold rounded-r-md border border-slate-400 px-2 text-slate-900 transition-colors duration-300 focus:border-[#001b47] focus:outline-none"></textarea>
                    <InputError v-for="(item, index) in errors.description" :key="index" class="mt-2" :message="item" />
                </div>
                <!-- Buttons -->
                <div class="mt-4 mb-4 md:flex flex-row justify-center">
                    <GeneralButton v-if="dataRequerimiento.id_requerimiento_pago" @click="updateRequerimiento()"
                        color="bg-orange-700  hover:bg-orange-800" text="Actualizar" icon="add" />
                    <GeneralButton v-else @click="addRequerimiento()" color="bg-green-700  hover:bg-green-800"
                        text="Agregar" icon="add" />
                    <div class="mb-4 md:mr-2 md:mb-0 px-1">
                        <GeneralButton text="Cancelar" icon="add" @click="requerimientoModalOpen = false" />
                    </div>
                </div>
            </div>
        </div>
    </ModalBasicVue>

    <ProcessModal maxWidth='4xl' :show="view_req_info" :center="true" @close="view_req_info = false"
        :show_request="show_request">
        <div class="m-1.5 p-2 bg-white max-w-full max-h-full">
            <div class="flex justify-center items-center mt-5">
                <h2 class="font-bold">Requerimiento {{ show_request.numero_requerimiento_pago }}-{{
                    show_request.anio_requerimiento_pago }}</h2>
            </div>
            <div class="tabla-modal mt-4">
                <table class="w-full" id="tabla_modal_validacion_arranque">
                    <thead class="bg-[#1F3558] text-white">
                        <tr class="">
                            <th class="rounded-tl-lg w-10">N° QUEDAN</th>
                            <th class="w-60">PROVEEDOR</th>
                            <th class="w-10">IVA</th>
                            <th class="w-10">RENTA</th>
                            <th class="rounded-tr-lg w-10">MONTO</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm divide-y divide-slate-200">
                        <tr v-for="quedan in show_request.quedan" :key="quedan.id_quedan" class="hover:bg-[#141414]/10">
                            <td class="text-center whitespace-normal">{{ quedan.id_quedan }}</td>
                            <td class="text-center whitespace-normal">{{ quedan.proveedor.razon_social_proveedor }}</td>
                            <td class="text-center whitespace-normal text-red-600">{{ quedan.monto_iva_quedan }}</td>
                            <td class="text-center whitespace-normal text-red-600">{{ quedan.monto_isr_quedan }}</td>
                            <td class="text-center whitespace-normal text-green-600">{{ quedan.monto_liquido_quedan }}</td>
                        </tr>
                        <tr v-if="show_request.quedan != ''" class="font-bold">
                            <td class="text-center whitespace-normal"></td>
                            <td class="text-center whitespace-normal">Total</td>
                            <td class="text-center whitespace-normal">{{ total_iva_quedan }}</td>
                            <td class="text-center whitespace-normal">{{ total_isr_quedan }}</td>
                            <td class="text-center whitespace-normal">{{ total_liquido_quedan }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div v-if="show_request.quedan == ''"
                class="w-full flex justify-between items-center mt-5 rounded-md font-bold">
                <div class="flex w-full justify-center text-left text-lg">
                    <p>Sin Quedan Asignados</p>
                </div>
            </div>

        </div>
    </ProcessModal>
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
            { width: "5%", label: "Numero requerimiento", name: "numero_requerimiento_pago", type: "text" },
            { width: "20%", label: "Descripcion", name: "descripcion_requerimiento_pago", type: "text" },
            { width: "10%", label: "Monto", name: "monto_requerimiento_pago", type: "text" },
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
            errors: [],
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
            view_req_info: false,
            dataRequerimiento: {
                id_requerimiento_pago: '',
                numero_requerimiento_pago: '',
                monto_requerimiento_pago: '',
                mes_requerimiento_pago: '',
                anio_requerimiento_pago: '',
                descripcion_requerimiento_pago: ''
            },
            errorNumber: null,
            show_request: []
        };
    },
    methods: {
        viewRequest(request) {
            this.view_req_info = true
            this.show_request = request
        },
        typeAmount() {
            let x = this.dataRequerimiento.monto_requerimiento_pago.replace(/^\./, '').replace(/[^0-9.]/g, '')
            this.dataRequerimiento.monto_requerimiento_pago = x
            const regex = /^(\d+)?([.]?\d{0,2})?$/
            if (!regex.test(this.dataRequerimiento.monto_requerimiento_pago)) {
                this.dataRequerimiento.monto_requerimiento_pago = this.dataRequerimiento.monto_requerimiento_pago.match(regex) || x.substring(0, x.length - 1)
            }
        },
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
            this.$swal.fire({
                title: "¿Está seguro de guardar el nuevo requerimiento?",
                icon: "question",
                iconHtml: "✅",
                confirmButtonText: "Si, Guardar",
                confirmButtonColor: "#15803D",
                cancelButtonText: "Cancelar",
                showCancelButton: true,
                showCloseButton: true,
            })
                .then((result) => {
                    if (result.isConfirmed) {
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
                            this.requerimientoModalOpen = false

                        }).catch((errors) => {
                            console.log(errors);
                            if (errors.response.status === 422) {
                                toast.warning(
                                    "Tienes algunos errores por favor verifica tus datos.",
                                    {
                                        autoClose: 5000,
                                        position: "top-right",
                                        transition: "zoom",
                                        toastBackgroundColor: "white",
                                    }
                                );
                                this.errors = errors.response.data.errors;
                            } else {
                                let msg = this.manageError(errors);
                                this.$swal.fire({
                                    title: "Operación cancelada",
                                    text: msg,
                                    icon: "warning",
                                    timer: 5000,
                                });
                                this.$emit("cerrar-modal");
                            }
                        })
                    }
                });

        },

        updateRequerimiento() {
            this.$swal
                .fire({
                    title: "¿Está seguro de actualizar el requerimiento?",
                    icon: "question",
                    iconHtml: '❓',
                    confirmButtonText: "Si, Actualizar",
                    confirmButtonColor: "#D2691E",
                    cancelButtonText: "Cancelar",
                    showCancelButton: true,
                    showCloseButton: true,
                })
                .then((result) => {
                    if (result.isConfirmed) {
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
                            this.getDataRequerimiento(this.lastUrl)
                            this.requerimientoModalOpen = false
                        }).catch((errors) => {
                            if (errors.response.status === 422) {
                                toast.warning(
                                    "Tienes algunos errores por favor verifica tus datos.",
                                    {
                                        autoClose: 5000,
                                        position: "top-right",
                                        transition: "zoom",
                                        toastBackgroundColor: "white",
                                    }
                                );
                                console.log(errors);
                                this.errors = errors.response.data.errors;
                            } else {
                                let msg = this.manageError(errors);
                                this.$swal.fire({
                                    title: "Operación cancelada",
                                    text: msg,
                                    icon: "warning",
                                    timer: 5000,
                                });
                                this.$emit("cerrar-modal");
                            }
                        })
                    }
                });
        },

        editRequerimiento(request) {
            this.errors = []
            let newDataQuedan = JSON.parse(JSON.stringify(request))
            this.dataRequerimiento.id_requerimiento_pago = newDataQuedan.id_requerimiento_pago
            this.dataRequerimiento.numero_requerimiento_pago = newDataQuedan.numero_requerimiento_pago
            this.dataRequerimiento.monto_requerimiento_pago = newDataQuedan.monto_requerimiento_pago
            this.dataRequerimiento.descripcion_requerimiento_pago = newDataQuedan.descripcion_requerimiento_pago
            this.requerimientoModalOpen = true
        },
        openModal() {
            this.errors = []
            this.dataRequerimiento.id_requerimiento_pago = ''
            this.dataRequerimiento.monto_requerimiento_pago = ''
            this.dataRequerimiento.numero_requerimiento_pago = ''
            this.dataRequerimiento.descripcion_requerimiento_pago = ''
            this.requerimientoModalOpen = true
        },
    },
    computed: {
        total_liquido_quedan() {
            if (this.show_request == '') {
                return '0.00'
            } else {
                let total = 0
                this.show_request.quedan.forEach((value, index) => {
                    total += parseFloat(value.monto_liquido_quedan)
                })
                return total.toFixed(2)
            }
        },
        total_iva_quedan() {
            if (this.show_request == '') {
                return '0.00'
            } else {
                let total = 0
                this.show_request.quedan.forEach((value, index) => {
                    total += parseFloat(value.monto_iva_quedan)
                })
                return total.toFixed(2)
            }
        },
        total_isr_quedan() {
            if (this.show_request == '') {
                return '0.00'
            } else {
                let total = 0
                this.show_request.quedan.forEach((value, index) => {
                    total += parseFloat(value.monto_isr_quedan)
                })
                return total.toFixed(2)
            }
        },
    }

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
</style>