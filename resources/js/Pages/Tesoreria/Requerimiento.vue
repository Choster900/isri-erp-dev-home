<script setup>
import Datatable from "@/Components-ISRI/Datatable.vue";
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
import ModalBasicVue from '@/Components-ISRI/AllModal/ModalBasic.vue';
import ProcessModal from '@/Components-ISRI/AllModal/ProcessModal.vue'
import InputError from "@/Components/InputError.vue";
</script>
<template>
    <Head title="Tesoreria" />
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
                                    <button class="text-slate-400 hover:text-slate-500 rounded-full"
                                        @click="editRequerimiento(requerimiento)">
                                        <svg width="33px" height="33px" viewBox="0 0 24.00 24.00" fill="none"
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
                                    <button class="text-blue-500 hover:text-blue-600 rounded-full"
                                        @click="viewRequest(requerimiento)">
                                        <svg viewBox="-20.48 -20.48 552.96 552.96" width="25px" height="25px" fill="#000000" class=""
                                            stroke="#000000" stroke-width="4.096"
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
            { width: "10%", label: "Numero requerimiento", name: "numero_requerimiento_pago", type: "text" },
            { width: "45%", label: "Descripcion", name: "descripcion_requerimiento_pago", type: "text" },
            { width: "15%", label: "Monto", name: "monto_requerimiento_pago", type: "text" },
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