<script setup>
import { Head } from "@inertiajs/vue3";
import Datatable from "@/Components-ISRI/Datatable.vue";
import ModalQuedan from "@/Components-ISRI/Tesoreria/ModalQuedan.vue";
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
import axios from 'axios';
import moment from 'moment';
import { jsPDF } from "jspdf";
import html2pdf from 'html2pdf.js'
</script>
<template>
    <Head title="Proceso - Quedan" />
    <AppLayoutVue nameSubModule="Tesoreria - Quedan">
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
                            <Multiselect v-model="tableData.length" placeholder="Cantidad a mostrar"
                                @select="getDataQuedan()" :options="perPage" :searchable="true" />
                            <LabelToInput icon="list2" />
                        </div>
                    </div>
                    <h2 class="font-semibold text-slate-800 pt-1">Quedan :
                        <span class="text-slate-400 ">{{ pagination.total }}</span>
                    </h2>

                </div>

            </header>
            <div class="overflow-x-auto">
                <datatable :columns="columns" :sortKey="sortKey" :sortOrders="sortOrders" @sort="sortBy"
                    @datos-enviados="handleData($event)">
                    <tbody class="text-sm divide-y divide-slate-200">
                        <tr v-for="data in dataQuedanForTable" :key="data.id_quedan">
                            <td class="px-2 first:pl-5 last:pr-5  whitespace-nowrap w-px">
                                <div class="text-slate-800 text-center text-[9pt]">{{ data.id_quedan }}</div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5  whitespace-nowrap w-px">
                                <div class=" text-slate-800 text-center wrap text-[9pt]">
                                    {{ formatDate(data.fecha_emision_quedan) }}</div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5">
                                <div class=" text-slate-800 text-center text-[10pt]">{{
                                    data.proveedor.razon_social_proveedor
                                }}</div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5  whitespace-nowrap w-px">
                                <div class=" text-slate-800 text-center text-[9pt]" v-if="data.requerimiento_pago">
                                    {{ data.requerimiento_pago.numero_requerimiento_pago
                                    }}-{{ data.requerimiento_pago.anio_requerimiento_pago }}
                                </div>
                                <div class=" text-slate-800 text-center" v-else>
                                    <div
                                        class="text-[8pt] inline-flex  bg-rose-100 text-rose-600 rounded-full text-center px-2.5 py-1">
                                        N/Asing
                                    </div>
                                </div>
                            </td>
                            <td class="px-5">
                                <div class="max-h-40 overflow-y-auto scrollbar">
                                    <template v-for="(detalle, i) in data.detalle_quedan" :key="i">
                                        <div class="mb-2 text-center">
                                            <p class="text-[10pt]">
                                                <span class="font-medium">FACTURA</span>
                                                {{ detalle.numero_factura_det_quedan }}<br> <span class="font-medium">MONTO:
                                                </span> ${{ (parseFloat(detalle.servicio_factura_det_quedan) || 0)
                                                    + (parseFloat(detalle.producto_factura_det_quedan) || 0) }}
                                            </p>
                                        </div>
                                        <template v-if="i < data.detalle_quedan.length - 1">
                                            <hr class="my-2 border-t border-gray-300">
                                        </template>
                                    </template>
                                </div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5  whitespace-nowrap w-px">
                                <div class=" text-slate-800 text-center text-[10pt]">${{ data.monto_liquido_quedan }}</div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5  whitespace-nowrap w-px">
                                <div class=" text-slate-800 text-center">
                                    <div v-if="data.id_estado_quedan === 1"
                                        class="text-xs inline-flex text-[8pt] bg-emerald-100 text-emerald-600 rounded-full text-center px-2.5 py-1">
                                        Abierto
                                    </div>
                                    <div v-else-if="data.id_estado_quedan === 2"
                                        class="text-xs inline-flex text-[8pt] bg-blue-100 text-blue-600 rounded-full text-center px-2.5 py-1">
                                        Req.Asignado
                                    </div>
                                    <div v-else-if="data.id_estado_quedan === 3"
                                        class="text-xs inline-flex text-[8pt] bg-blue-100 text-orange-600 rounded-full text-center px-2.5 py-1">
                                        Liq. Parcial
                                    </div>
                                    <div v-else-if="data.id_estado_quedan === 4"
                                        class="text-xs inline-flex text-[8pt] bg-blue-100 text-red-600 rounded-full text-center px-2.5 py-1">
                                        Liquidado
                                    </div>
                                </div>
                            </td>
                            <td class="first:pl-5 last:pr-5">
                                <div class="space-x-1">
                                    <DropDownOptions>
                                        <div class="flex hover:bg-gray-100 py-1 px-2 rounded cursor-pointer"
                                            @click.stop="showQuedan(data)">
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
                                            <div class="font-semibold">VER</div>
                                        </div>
                                        <div class="flex hover:bg-gray-100 py-1 px-2 rounded cursor-pointer" 
                                            @click.stop="printPdf(data)">
                                            <div class="w-8 text-blue-900">
                                                <span class="text-xs">
                                                    <svg fill="#bc0101" width="23px" height="23px" viewBox="0 0 1920 1920"  class="pr-1"
                                                        xmlns="http://www.w3.org/2000/svg" stroke="#bc0101">
                                                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round"
                                                            stroke-linejoin="round"></g>
                                                        <g id="SVGRepo_iconCarrier">
                                                            <g fill-rule="evenodd">
                                                                <path
                                                                    d="M1185.46.034V564.74h564.705v1355.294H168.99V.034h1016.47ZM900.508 677.68c-16.829 0-31.963 7.567-42.918 21.007-49.807 59.972-31.398 193.016-18.748 272.075 2.823 17.958.452 36.141-7.228 52.518l-107.86 233.223c-7.905 16.942-20.555 30.608-36.592 39.53-68.104 37.835-182.287 89.675-196.066 182.626-4.97 30.268 5.082 56.357 28.574 79.85 15.925 15.133 35.238 22.7 56.245 22.7 81.43 0 132.819-71.717 188.273-148.517 24.62-34.221 61.666-55.229 102.437-60.876 76.349-10.503 167.83-32.527 223.172-46.983 27.897-7.341 56.358-5.534 83.802 3.162 48.565 15.586 66.975 25.073 122.768 25.073 50.371 0 84.818-11.746 101.534-34.447 13.44-16.828 16.715-39.53 10.164-65.619-11.858-42.804-2.033-89.675-133.044-89.675-29.365 0-57.94 2.824-81.77 6.099-36.819 4.97-73.299-10.955-97.016-40.885-32.301-40.546-65.167-88.433-87.981-123.219-16.151-24.508-21.572-53.986-16.264-83.124 15.473-84.706 18.41-147.615-23.492-206.683-17.619-25.186-41.223-37.835-67.99-37.835Zm397.903-660.808 434.936 434.937h-434.936V16.873Z">
                                                                </path>
                                                                <path
                                                                    d="M791.057 1297.943c92.273-43.37 275.916-65.28 275.916-65.28-92.386-88.998-145.92-215.04-145.92-215.04-43.257 126.607-119.718 264.282-129.996 280.32">
                                                                </path>
                                                            </g>
                                                        </g>
                                                    </svg>
                                                </span>
                                            </div>
                                            <div class="font-semibold">GENERAR QUEDAN</div>
                                        </div>
                                        <div class="flex hover:bg-gray-100 py-1 px-2 rounded cursor-pointer" v-if="data.monto_total_quedan > 113"
                                            @click.stop="generarComprobanteRetencionPdf(data)">
                                            <div class="w-8 text-blue-900">
                                                <span class="text-xs">
                                                    <svg fill="#bc0101" width="23px" height="23px" viewBox="0 0 1920 1920" class="pr-1"
                                                        xmlns="http://www.w3.org/2000/svg" stroke="#bc0101">
                                                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round"
                                                            stroke-linejoin="round"></g>
                                                        <g id="SVGRepo_iconCarrier">
                                                            <g fill-rule="evenodd">
                                                                <path
                                                                    d="M1185.46.034V564.74h564.705v1355.294H168.99V.034h1016.47ZM900.508 677.68c-16.829 0-31.963 7.567-42.918 21.007-49.807 59.972-31.398 193.016-18.748 272.075 2.823 17.958.452 36.141-7.228 52.518l-107.86 233.223c-7.905 16.942-20.555 30.608-36.592 39.53-68.104 37.835-182.287 89.675-196.066 182.626-4.97 30.268 5.082 56.357 28.574 79.85 15.925 15.133 35.238 22.7 56.245 22.7 81.43 0 132.819-71.717 188.273-148.517 24.62-34.221 61.666-55.229 102.437-60.876 76.349-10.503 167.83-32.527 223.172-46.983 27.897-7.341 56.358-5.534 83.802 3.162 48.565 15.586 66.975 25.073 122.768 25.073 50.371 0 84.818-11.746 101.534-34.447 13.44-16.828 16.715-39.53 10.164-65.619-11.858-42.804-2.033-89.675-133.044-89.675-29.365 0-57.94 2.824-81.77 6.099-36.819 4.97-73.299-10.955-97.016-40.885-32.301-40.546-65.167-88.433-87.981-123.219-16.151-24.508-21.572-53.986-16.264-83.124 15.473-84.706 18.41-147.615-23.492-206.683-17.619-25.186-41.223-37.835-67.99-37.835Zm397.903-660.808 434.936 434.937h-434.936V16.873Z">
                                                                </path>
                                                                <path
                                                                    d="M791.057 1297.943c92.273-43.37 275.916-65.28 275.916-65.28-92.386-88.998-145.92-215.04-145.92-215.04-43.257 126.607-119.718 264.282-129.996 280.32">
                                                                </path>
                                                            </g>
                                                        </g>
                                                    </svg>
                                                </span>
                                            </div>
                                            <div class="font-semibold">GENERAR COMPROBANTE DE RETENCION</div>
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
                        <ul class="inline-flex text-sm  -space-x-px">
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

        <ModalQuedan :showModal="showModal" @cerrar-modal="showModal = false" :data-quedan="dataQuedan"
            :dataForSelectInRow="dataForSelectInRow" @actualizar-table-data="getDataQuedan()"
            :totalAmountBySupplier="totalAmountBySupplier" />


    </AppLayoutVue>
</template>
<script>
import quedanPDFVue from '@/pdf/Tesoreria/quedanPDF.vue';
import comprobanteRetencion from '@/pdf/Tesoreria/Retencion.vue';
import { createApp, h } from 'vue'

export default {

    data: function (data) {
        let sortOrders = {};
        let columns = [
            { width: "15%", label: "Id", name: "id_quedan", type: "text" },
            { width: "10%", label: "Fecha", name: "fecha_emision_quedan", type: "date" },
            { width: "20%", label: "Proveedor", name: "razon_social_proveedor", type: "text" },
            { width: "5%", label: "Numero requerimiento", name: "numero_requerimiento_pago", type: "text" },
            { width: "40%", label: "Detalle quedan", name: "buscar_por_detalle_quedan", type: "text" },
            { width: "10%", label: "Monto", name: "monto_liquido_quedan", type: "text" },
            {
                width: "10%", label: "Estado", name: "id_estado_quedan", type: "select",
                options: [
                    { value: "", label: "Ninguno" },
                    { value: "1", label: "Abierto" },
                    { value: "2", label: "Req.Asignado" },
                    { value: "3", label: "Liq. Parcial" },
                    { value: "4", label: "Liquidado" },
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
            dataQuedan: [],//Datos del quedan hasta los detalles de este
            totalAmountBySupplier: [],//Datos de proveedores
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
            // Guardar la URL actual y actualizar el contador de dibujos (draw)
            this.lastUrl = url;
            this.tableData.draw++;

            try {
                // Realizar una solicitud POST a la URL especificada
                const response = await axios.post(url, this.tableData);
                const data = response.data;

                // Verificar si la respuesta corresponde al dibujo actual
                if (this.tableData.draw === data.draw) {
                    // Actualizar los enlaces de paginación y la información de la tabla
                    this.showModal = false;
                    this.links = data.data.links;
                    this.pagination.total = data.data.total;
                    this.links[0].label = "Anterior";
                    this.links[this.links.length - 1].label = "Siguiente";
                    this.dataQuedanForTable = data.data.data;

                    // Obtener el monto por proveedor
                    this.getAmountBySupplier();
                }
            } catch (error) {
                let msg = this.manageError(error);
                this.$swal.fire({
                    title: "Operación cancelada",
                    text: msg,
                    icon: "warning",
                    timer: 5000,
                });
            }
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
        async createQuedan() {
            this.dataQuedan = []
            this.showModal = true
        },
        async getListForSelect() {
            //Metodo que traer un listado de catalogos para poder usarlos en MultiSelect y otras variantes
            await axios.get('/get-list-select').then((response) => {
                this.dataForSelectInRow = response.data;
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
        async getAmountBySupplier(dataQuedan) {
            //metodo que trae todos los proveedores del mes actual, se mandan los parametros al modal
            await axios.post('/getAmountBySupplierPerMonth').then((response) => {
                this.totalAmountBySupplier = response.data
            }).catch((errors) => {
                console.log(errors);
            });
        },
        async showQuedan(dataQuedan) {
            // await this.getAmountBySupplier(dataQuedan)
            this.dataQuedan = dataQuedan
            this.showModal = true
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
        formatDate(dateString) {
            const date = new Date(dateString);
            const day = date.getDate().toString().padStart(2, '0');
            const month = (date.getMonth() + 1).toString().padStart(2, '0');
            const year = date.getFullYear().toString();
            return `${day}/${month}/${year}`;
        },

        printPdf(dataQuedan) {
            // Opciones de configuración para generar el PDF
            let fecha = moment().format('DD-MM-YYYY');
            let name = 'QUEDAN ' + dataQuedan.id_quedan + ' - ' + fecha;
            const opt = {
                margin: 0.1,
                filename: name,
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 2, useCORS: true },
                jsPDF: { unit: 'in', format: 'a4', orientation: 'portrait' },
            };

            // Crear una instancia de la aplicación Vue para generar el componente quedanPDFVue
            const app = createApp(quedanPDFVue, {
                dataQuedan: dataQuedan,
                totalCheque: dataQuedan.monto_total_quedan,
                renta: dataQuedan.monto_isr_quedan,
                iva: dataQuedan.monto_iva_quedan,
                cheque: dataQuedan.monto_liquido_quedan,
            });

            // Crear un elemento div y montar la instancia de la aplicación en él
            const div = document.createElement('div');
            const pdfPrint = app.mount(div);
            const html = div.outerHTML;

            // Generar y guardar el PDF utilizando html2pdf
            html2pdf().set(opt).from(html).save();
        },
        generarComprobanteRetencionPdf(dataQuedan) {
            if (dataQuedan.numero_retencion_iva_quedan === null) {
                // Validar si se ha ingresado un número de retención
                toast.error("El documento necesita un número de retención", {
                    autoClose: 5000,
                    position: "top-right",
                    transition: "zoom",
                    toastBackgroundColor: "red",
                });
            } else {
                // Opciones de configuración para generar el PDF
                let fecha = moment().format('DD-MM-YYYY');
                let name = 'COMPROBANTE DE RENTENCION ' + dataQuedan.numero_retencion_iva_quedan + ' - ' + fecha;
                const opt = {
                    margin: 0.1,
                    filename: name,
                    image: { type: 'jpeg', quality: 0.98 },
                    html2canvas: { scale: 2 },
                    jsPDF: { unit: 'in', format: 'letter', orientation: 'landscape' },
                };

                // Crear una instancia de la aplicación Vue para generar el componente comprobanteRetencion
                const app = createApp(comprobanteRetencion, {
                    dataQuedan: dataQuedan,
                    totalCheque: dataQuedan.monto_total_quedan,
                    renta: dataQuedan.monto_isr_quedan,
                    iva: dataQuedan.monto_iva_quedan,
                    cheque: dataQuedan.monto_liquido_quedan,
                });

                // Crear un elemento div y montar la instancia de la aplicación en él
                const div = document.createElement('div');
                const pdfPrint = app.mount(div);
                const html = div.outerHTML;

                // Generar y guardar el PDF utilizando html2pdf
                html2pdf().set(opt).from(html).save();

                // Actualizar la fecha de retención de IVA en el servidor
                axios.post('/updateFechaRetencionIva', { id_quedan: dataQuedan.id_quedan })
                    .then((response) => {
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

.scrollbar::-webkit-scrollbar {
    width: 5px;
}

.scrollbar::-webkit-scrollbar-track {
    background-color: #f1f1f1;
}

.scrollbar::-webkit-scrollbar-thumb {
    background-color: #001c48;
    border-radius: 4px;
}

.scrollbar::-webkit-scrollbar-thumb:hover {
    background-color: #555;
}</style>