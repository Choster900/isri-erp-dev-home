<script setup>
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
import moment from 'moment';
import ProcessModal from '@/Components-ISRI/AllModal/ProcessModal.vue'
import { jsPDF } from "jspdf";
import html2pdf from 'html2pdf.js'
</script>
<template>
    <div class="m-1.5">
        <ProcessModal maxWidth='6xl' :show="showModal" @close="$emit('cerrar-modal')">

            <div class="">
                <div class="text-center">
                    <div class=" flex   justify-center pt-2 content-between">
                        <div class="px-2" v-if="dataQuedan == ''">
                            <GeneralButton color="bg-green-700   hover:bg-green-800" text="AGREGAR" icon="add"
                                @click="createQuedan()" />
                        </div>
                        <div class="px-2" v-else>
                            <template v-if="dataQuedan.id_estado_quedan === 1">
                                <GeneralButton color="bg-orange-700   hover:bg-orange-800" text="MODIFICAR" icon="update"
                                    @click="updateQuedan()" />
                            </template>
                        </div>
                        <div class="px-2" v-if="dataQuedan != ''">
                            <GeneralButton color="bg-red-700   hover:bg-red-800" text="GENERAR QUEDAN" icon="pdf"
                                @click="printPdf()" />
                        </div>
                        <div class="px-2" v-if="dataQuedan != ''">
                            <GeneralButton color="bg-[#0A3158]/90  hover:bg-[#0A3158]" text="GENERAR RETENCION" icon="pdf"
                                @click="generarComprobanteRetencionPdf()" />
                        </div>
                        <svg class="h-7 w-7 absolute top-0 right-0 mt-2 cursor-pointer" viewBox="0 0 25 25"
                            @click="$emit('cerrar-modal')">
                            <path fill="currentColor"
                                d="M7.95 6.536l4.242-4.243a1 1 0 111.415 1.414L9.364 7.95l4.243 4.242a1 1 0 11-1.415 1.415L7.95 9.364l-4.243 4.243a1 1 0 01-1.414-1.415L6.536 7.95 2.293 3.707a1 1 0 011.414-1.414L7.95 6.536z" />
                        </svg>

                    </div>

                    <button type="button" @click="addRow()"
                        style="float: right;margin-right:-4px;margin-top:399px; font-size: 30px; padding: 0 10px; border: 0; background-color: transparent;">
                        <span type="button" data-toggle="tooltip" data-placement="right" title="AGREGAR FACTURA">
                            +
                        </span>
                    </button>

                    <div class="border-2 border-black mx-8 mb-7" id="main-container" ref="contenedorPdfQuedan">

                        <div class="mb-7 md:flex flex-row justify-between">
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/4 pt-1 px-8">
                            </div>
                            <div class="mb-4 md:mx-10 md:mb-0 basis-1/2 pt-7 ">
                                <div class="border-2 border-black ">
                                    <h3 style class="text-[20px] px-7 py-1.5 font-semibold text-gray-600">
                                        Documento de seguimiento de pagos
                                    </h3>
                                </div>
                            </div>
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/4 pt-1 px-8 ">
                            </div>
                        </div>

                        <div class="mx-20 mb-3 encabezado">
                            <div class="mb-7 md:flex flex-row justify-items-center">
                                <div class="mb-4 md:mr-2 md:mb-0 w-100">
                                    <div class="relative flex w-full flex-row">
                                        <label for="" class="flex items-center text-[14px] text-sm">Giro del
                                            proveedor:</label>
                                        <input type="text" style="width: 350px;" readonly v-model="dataInputs.giro"
                                            class="border-b-2 border-black placeholder-slate-400 text-sm py-0 transition-colors duration-300 focus:border-[#001b47] focus:outline-none">
                                    </div>
                                </div>
                                <div class="mb-4 md:mr-2 md:mb-0 w-25">
                                    <div class="relative flex w-full flex-row">
                                        <label for="" class="flex items-center text-[14px]">Porcentaje de IVA:</label>
                                        <input type="text" style="width: 75px;" v-model="dataInputs.iva" readonly
                                            class="border-b-2 border-black placeholder-slate-400 text-sm text-center py-0 transition-colors duration-300 focus:border-[#001b47] focus:outline-none">
                                    </div>
                                </div>
                                <div class="mb-4 md:mr-2 md:mb-0 w-25">
                                    <div class="relative flex w-full flex-row">
                                        <label for="" class="flex items-center text-[14px] text-sm">Porcentaje de
                                            ISR:</label>
                                        <input type="text" style="width: 75px;" v-model="dataInputs.irs" readonly
                                            class="border-b-2 border-black placeholder-slate-400 text-sm py-0 text-center transition-colors duration-300 focus:border-[#001b47] focus:outline-none">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="pl-16 mb-7 encabezado">
                            <div class=" mb-7 md:flex flex-row ">
                                <div class="mb-4 md:mr-2 md:mb-0 w-25 h-5">
                                    <div class="relative flex  w-full flex-row">
                                        <label for="" class="flex items-center  text-[14px] text-sm">Quedan:
                                        </label>
                                        <input type="text" style="width: 80px;" readonly v-model="dataInputs.id_quedan"
                                            class="placeholder-slate-400 text-sm py-0 text-center font-bold transition-colors duration-300 focus:border-[#001b47] focus:outline-none border-b-0">
                                    </div>
                                </div>
                                <div class="mb-4 md:mr-2 md:mb-0 w-25 h-5">
                                    <div class="relative flex  w-full flex-row">
                                        <label for="" class="flex items-center  text-[14px] text-sm">Fecha emision:
                                        </label>
                                        <input type="date" style="width: 150px;" v-model="dataInputs.fecha_emision"
                                            class="placeholder-slate-400 text-sm py-0 text-center font-bold transition-colors duration-300 focus:border-[#001b47 focus:outline-none border-b-0">
                                    </div>
                                </div>

                                <div class="mb-4 md:mr-2 md:mb-0  w-40 h-5">
                                    <div class="relative flex  w-full flex-row">
                                        <label for="" class="flex items-center text-[14px]">Cheque :</label>
                                        <input type="text" style="width: 80px;" v-model="dataInputs.monto_liquido_quedan"
                                            readonly
                                            class="placeholder-slate-400 text-sm text-center py-0 transition-colors duration-300 focus:border-[#001b47] focus:outline-none border-b-0">
                                    </div>
                                </div>

                                <div class="mb-4 md:mr-2 md:mb-0 w-25 h-5">
                                    <div class="relative flex  w-full flex-row">
                                        <label for="" class="flex items-center text-[14px] text-sm">Renta:</label>
                                        <input type="text" style="width: 80px;" v-model="dataInputs.monto_isr_quedan"
                                            readonly
                                            class="placeholder-slate-400 text-sm py-0 text-center  transition-colors duration-300 focus:border-[#001b47] focus:outline-none border-b-0">
                                    </div>
                                </div>

                                <div class="mb-4 md:mr-2 md:mb-0 w-25 h-5">
                                    <div class="relative flex  w-full flex-row">
                                        <label for="" class="flex items-center text-[14px]">IVA: </label>
                                        <input type="text" style="width: 80px;" v-model="dataInputs.monto_iva_quedan"
                                            readonly
                                            class="placeholder-slate-400 text-sm text-center py-0 transition-colors duration-300 focus:border-[#001b47] focus:outline-none border-b-0">
                                    </div>
                                </div>

                                <div class="mb-4 md:mr-2 md:mb-0 w-25 h-5">
                                    <div class="relative flex  w-full flex-row">
                                        <label for="" class="flex items-center  text-[14px] text-sm">Total:
                                        </label>
                                        <input type="text" style="width: 80px;" readonly
                                            v-model="dataInputs.monto_total_quedan"
                                            class="placeholder-slate-400 text-sm py-0 text-center font-bold transition-colors duration-300 focus:border-[#001b47] focus:outline-none border-b-0">
                                    </div>
                                </div>


                            </div>
                        </div>

                        <div class="max-w-7xl mx-auto pb-20 sm:px-6 lg:px-8 overflow-x-auto">
                            <table class="table-auto mx-auto">
                                <thead>
                                    <tr>
                                        <th class="border-2 border-black h-7 " colspan="2">
                                            <p class="px-[55px] text-sm text-gray-600">PROVEEDOR</p>
                                        </th>
                                        <th class="border-2 border-black text-sm text-gray-600" colspan="9"
                                            contenteditable="false">
                                            DATOS DEL QUEDAN
                                        </th>
                                    </tr>
                                    <tr>
                                        <td class="border-2 border-black" colspan="2" contenteditable="false">
                                            <div class="relative flex h-8 w-full flex-row-reverse "
                                                :class="{ 'condition-select': dataInputs.id_proveedor == '' }">
                                                <Multiselect v-model="dataInputs.id_proveedor"
                                                    :disabled="dataQuedan.id_estado_quedan > 1 ? true : false"
                                                    :options="dataForSelectInRow.proveedor" :searchable="true"
                                                    @select="getInformationBySupplier($event)" />
                                            </div>
                                        </td>
                                        <th class="border-2 border-black text-sm text-gray-600" colspan="3">
                                            NUMERO DE ACUERDO
                                        </th>
                                        <th class="border-2 border-black text-sm text-gray-600" colspan="4">
                                            NUMERO DE COMPROMISO
                                        </th>
                                        <th class="border-2 border-black text-sm text-gray-600" colspan="4">
                                            NUMERO DE RETENCION
                                        </th>

                                    </tr>

                                    <tr>
                                        <th class="border-2 border-black h-16 text-sm text-gray-600" colspan="2">
                                            ACUERDO CONTRATACION
                                        </th>
                                        <td class="border-2 border-black"
                                            :class="dataInputs.numero_acuerdo_quedan == '' ? 'bg-[#fdfd96]' : ''"
                                            colspan="3" contenteditable="false">
                                            <input type="text" v-model="dataInputs.numero_acuerdo_quedan"
                                                :disabled="dataQuedan.id_estado_quedan > 1 ? true : false"
                                                :class="dataQuedan.id_estado_quedan > 1 ? 'bg-[#dcdcdc]' : ''"
                                                class="peer w-full text-sm bg-transparent text-center h-16 border-none px-2 text-slate-900 placeholder-slate-400 transition-colors duration-300 focus:border-none focus:outline-none">
                                        </td>
                                        <td class="border-2 border-black"
                                            :class="dataInputs.numero_compromiso_ppto_quedan == '' ? 'bg-[#fdfd96]' : ''"
                                            colspan="4" contenteditable="false">
                                            <input type="text" v-model="dataInputs.numero_compromiso_ppto_quedan"
                                                :disabled="dataQuedan.id_estado_quedan > 1 ? true : false"
                                                :class="dataQuedan.id_estado_quedan > 1 ? 'bg-[#dcdcdc]' : ''"
                                                class="peer w-full text-sm bg-transparent text-center h-16 border-none px-2 text-slate-900 placeholder-slate-400 transition-colors duration-300 focus:border-none focus:outline-none">
                                        </td>
                                        <td class="border-2 border-black"
                                            :class="dataInputs.numero_retencion_iva_quedan == '' ? 'bg-[#fdfd96]' : ''"
                                            colspan="4" contenteditable="false">
                                            <input type="text" v-model="dataInputs.numero_retencion_iva_quedan"
                                                :disabled="dataQuedan.id_estado_quedan > 1 ? true : false"
                                                :class="dataQuedan.id_estado_quedan > 1 ? 'bg-[#dcdcdc]' : ''"
                                                class="peer w-full text-sm bg-transparent text-center h-16 border-none px-2 text-slate-900 placeholder-slate-400 transition-colors duration-300 focus:border-none focus:outline-none">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="border-2 border-black" colspan="2" contenteditable="false">
                                            <div class="relative flex h-8 w-full flex-row-reverse"
                                                :class="{ 'condition-select': dataInputs.id_acuerdo_compra == '' }">
                                                <Multiselect v-model="dataInputs.id_acuerdo_compra"
                                                    :disabled="dataQuedan.id_estado_quedan > 1 ? true : false"
                                                    :options="dataForSelectInRow.acuerdoCompras" :searchable="true" />
                                            </div>
                                        </td>
                                        <th class="border-2 border-black text-sm text-gray-600" colspan="9"
                                            contenteditable="false">
                                            DETALLE QUEDAN
                                        </th>
                                    </tr>
                                    <tr>
                                        <th class="border-2 border-black text-sm w-24 text-gray-600">FACTURA</th>
                                        <th class="border-2 border-black text-sm text-gray-600">FECHA DE EMISION</th>
                                        <th class="border-2 border-black text-sm px-10 text-gray-600" colspan="2">
                                            DEPENDENCIA</th>
                                        <th class="border-2 border-black text-sm px-8 text-gray-600" colspan="2">NUMERO ACTA
                                        </th>
                                        <th class="border-2 border-black w-56 max-w-[256px] text-sm px-10 text-gray-600"
                                            colspan="3">
                                            CONCEPTO</th>
                                        <th class="border-2 border-black w-40 text-sm px-10 text-gray-600" colspan="4">MONTO
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="text-sm" id="content">

                                    <template v-for="( row, rowIndex ) in  rowsData " :key="rowIndex">
                                        <template v-if="row[7]">

                                            <tr @dblclick="deleteRow(rowIndex)">
                                                <template v-for="( cell, cellIndex ) in  row " :key="cellIndex">

                                                    <td v-if="cellIndex == 2"
                                                        @input="onCellEdit(rowIndex, cellIndex, $event.target.innerText)"
                                                        class="border-2 border-black" :class="[cell == '' ? 'bg-[#fdfd96]' : '',
                                                        dataQuedan.id_estado_quedan > 1 ? 'bg-[#dcdcdc]' : '']"
                                                        :contenteditable="dataQuedan.id_estado_quedan > 1 ? false : true">
                                                        {{ cell }}
                                                    </td>
                                                    <td v-if="cellIndex == 2" class="border-2 border-black">
                                                        <div class="mb-4  md:mb-0">
                                                            <flat-pickr v-model="rowsData[rowIndex][8]"
                                                                :disabled="dataQuedan.id_estado_quedan > 1 ? true : false"
                                                                :class="[rowsData[rowIndex][8] == '' ? 'bg-[#fdfd96]' : '', dataQuedan.id_estado_quedan > 1 ? 'bg-[#dcdcdc]' : '']"
                                                                class="w-[126px]  text-xs text-center cursor-pointer rounded-md border h-8 border-slate-400 text-slate-900 placeholder-slate-400 transition-colors duration-300 focus:border-[#001b47] focus:outline-none"
                                                                :config="config" />
                                                        </div>

                                                    </td>
                                                    <td v-else-if="cellIndex == 3" class="border-2 border-black" colspan="2"
                                                        :class="{ 'condition-select': rowsData[rowIndex][3] == '' }">
                                                        <div class="relative flex h-10 w-full flex-row-reverse ">
                                                            <Multiselect v-model="rowsData[rowIndex][3]"
                                                                :options="dataForSelectInRow.dependencias"
                                                                :searchable="true"
                                                                :disabled="dataQuedan.id_estado_quedan > 1 ? true : false"
                                                                @select="onCellEdit(rowIndex, cellIndex, $event)" />
                                                        </div>
                                                    </td>
                                                    <td v-else-if="cellIndex == 4" class="border-2 border-black" colspan="2"
                                                        :class="[
                                                            cell == '' ? 'bg-[#fdfd96]' : '',
                                                            errosDetalleQuedan[rowIndex] ? 'bg-[#fd9696]' : '',
                                                            errosrNumeroActa.includes(rowIndex) ? 'bg-[#fd9696]' : '',
                                                            dataQuedan.id_estado_quedan > 1 ? 'bg-[#dcdcdc]' : ''
                                                        ]"
                                                        @input="onCellEdit(rowIndex, cellIndex, $event.target.innerText)"
                                                        :contenteditable="dataQuedan.id_estado_quedan > 1 ? false : true">{{
                                                            cell }}</td>
                                                    <td v-else-if="cellIndex == 5" colspan="4"
                                                        @input="onCellEdit(rowIndex, cellIndex, $event.target.innerText)"
                                                        class="border-2 border-black px-1 "
                                                        :class="dataQuedan.id_estado_quedan > 1 ? 'bg-[#dcdcdc]' : ''"
                                                        :contenteditable="dataQuedan.id_estado_quedan > 1 ? false : true">
                                                        {{ cell }}
                                                    </td>

                                                    <td v-else-if="cellIndex == 6" class="border-2 border-black"
                                                        @keypress="onlyNumberDecimal($event)">

                                                        <table>
                                                            <tr>
                                                                <th class="border-2 border-r-black border-b-black border-l-transparent border-t-transparent text-sm text-gray-600 py-2"
                                                                    style="writing-mode: vertical-rl; transform: rotate(180deg);">
                                                                    PRODUCTO
                                                                </th>
                                                                <td :contenteditable="dataQuedan.id_estado_quedan > 1 ? false : true"
                                                                    :class="[cell.producto == '' ? 'bg-[#fdfd96]' : '', dataQuedan.id_estado_quedan > 1 ? 'bg-[#dcdcdc]' : '']"
                                                                    @input="onCellEdit(rowIndex, cellIndex, $event.target.innerText, 'producto')"
                                                                    class="w-full border-2 border-b-black border-x-transparent border-t-transparent">
                                                                    {{ cell.producto }}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th class="border-2 border-r-black border-t-black border-l-transparent border-b-transparent text-gray-600 py-2"
                                                                    style="writing-mode: vertical-rl; transform: rotate(180deg);">
                                                                    SERVICIO
                                                                </th>
                                                                <td :contenteditable="dataQuedan.id_estado_quedan > 1 ? false : true"
                                                                    :class="[cell.servicio == '' ? 'bg-[#fdfd96]' : '', dataQuedan.id_estado_quedan > 1 ? 'bg-[#dcdcdc]' : '']"
                                                                    @input="onCellEdit(rowIndex, cellIndex, $event.target.innerText, 'servicio')">
                                                                    {{ cell.servicio }}
                                                                </td>
                                                            </tr>
                                                        </table>

                                                    </td>

                                                </template>

                                            </tr>

                                        </template>
                                    </template>
                                </tbody>
                                <tbody>
                                    <tr id="esconder" class="border-none">
                                        <td contenteditable="false" class="py-3 border-none"></td>
                                    </tr>
                                    <tr>
                                        <td class="border-2 border-black " colspan="2" rowspan="2" contenteditable="false">
                                            Descripción
                                        </td>
                                        <td class="border-2 border-black" colspan="5" rowspan="2"
                                            :contenteditable="dataQuedan.id_estado_quedan > 1 ? false : true"
                                            @input="onInputDescripcionQuedan"
                                            :class="dataQuedan.id_estado_quedan > 1 ? 'bg-[#dcdcdc]' : ''">
                                            {{ dataInputs.descripcion_quedan }}

                                        </td>
                                        <th class="border-2 border-black text-sm text-gray-600" colspan="2">
                                            PRIORIDAD DE PAGO
                                        </th>
                                        <th class="border-2 border-black py-0 text-sm text-gray-600" colspan="3">
                                            PROYECTO FINANCIADO
                                        </th>
                                    </tr>
                                    <tr>

                                        <td class="border-2 border-black " colspan="2" contenteditable="false">
                                            <div class="relative flex h-8 w-full flex-row-reverse "
                                                :class="{ 'condition-select': dataInputs.id_prioridad_pago == '' }">
                                                <Multiselect v-model="dataInputs.id_prioridad_pago"
                                                    :disabled="dataQuedan.id_estado_quedan > 1 ? true : false"
                                                    :options="dataForSelectInRow.prioridadPago" :searchable="true" />
                                            </div>
                                        </td>
                                        <td class="border-2 border-black " colspan="3" contenteditable="false">
                                            <div class="relative flex h-8 w-full flex-row-reverse "
                                                :class="{ 'condition-select': dataInputs.id_proy_financiado == '' }">
                                                <Multiselect v-model="dataInputs.id_proy_financiado"
                                                    :disabled="dataQuedan.id_estado_quedan > 1 ? true : false"
                                                    :options="dataForSelectInRow.proyectoFinanciado" :searchable="true" />
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>


                            <div class="row footer mt-4 mb-10">
                                <div class="text-center">
                                    <input type="text"
                                        class="border-2 border-solid border-gray-400 text-center w-80 text-[15px]"
                                        :value="dataInputs.nombre_empleado_tesoreria" disabled>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </ProcessModal>
    </div>
</template>

<script>

import quedanPDFVue from '@/pdf/Tesoreria/quedanPDF.vue';
import comprobanteRetencion from '@/pdf/Tesoreria/ComprobatenRetencionPdf.vue';
import { createApp, h } from 'vue'

export default {

    props: {
        showModal: {//prop muestra estado del modal para abrir y cerrar
            type: Boolean,
            default: false,
        },
        dataQuedan: {//prop has total information from "quedan"
            type: Object,
            required: true,
        },
        dataForSelectInRow: {//prop que muestra information en row 
            type: [],
            required: true,
        },
        dataSuppliers: {//prop muestra array de suppliers para imprimir en select
            type: [],
            required: true,
        },
        totalAmountBySupplier: {
            type: Array,
            default: [],
            required: true,
        }

    },
    data: function () {
        return {
            rowsData: [],//attr donde guardar la information
            errosDetalleQuedan: [],
            errosrNumeroActa: [],
            montoTotalProveedorMes: [],
            dataInputs: {
                giro: '',
                irs: '',
                iva: '',
                id_proveedor: '',
                id_acuerdo_compra: '',
                numero_acuerdo_quedan: '',
                numero_compromiso_ppto_quedan: '',
                numero_retencion_iva_quedan: '',
                descripcion_quedan: '',
                monto_liquido_quedan: '',
                monto_iva_quedan: '',
                monto_isr_quedan: '',
                monto_total_quedan: '',
                nombre_empleado_tesoreria: '',
                fecha_emision: '',
                id_quedan: '',
                id_prioridad_pago: '',
                id_proy_financiado: '',
            },
            dataForCalculate: {
                giro: '',
                irs: '',
                iva: '',
                id_proveedor: '',
                monto_liquido_quedan: '',
                monto_iva_quedan: '',
                monto_isr_quedan: '',
                monto_total_quedan: '',
                monto_total_quedan_por_proveedor: '',
            },
            config: {
                altInput: true,
                static: true,
                monthSelectorType: 'static',
                altFormat: "F-j Y",
                dateFormat: "Y-m-d",
                locale: {
                    firstDayOfWeek: 1,
                    weekdays: {
                        shorthand: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
                        longhand: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
                    },
                    months: {
                        shorthand: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
                        longhand: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                    },
                },
            },

        }
    },
    components: {
        quedanPDFVue
    },
    methods: {
        printPdf() {
            const opt = {
                margin: 0.1,
                filename: 'output.pdf',
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 2, useCORS: true },
                jsPDF: { unit: 'in', format: 'a4', orientation: 'portrait' },
            };

            const app = createApp(quedanPDFVue, {
                dataQuedan: this.dataQuedan,
                totalCheque: this.dataInputs.monto_total_quedan,
                renta: this.dataInputs.monto_isr_quedan,
                iva: this.dataInputs.monto_iva_quedan,
                cheque: this.dataInputs.monto_liquido_quedan,
            });

            const div = document.createElement('div');
            const pdfPrint = app.mount(div);
            const html = div.outerHTML;

            html2pdf().set(opt).from(html).save();

        },
        generarComprobanteRetencionPdf() {

            if (this.dataInputs.numero_retencion_iva_quedan === null) {
                toast.error("El documento necesita un numero de retencion", {
                    autoClose: 5000,
                    position: "top-right",
                    transition: "zoom",
                    toastBackgroundColor: "red",
                });
            } else {
                const opt = {
                    margin: 0.1,
                    filename: 'Comprobante_retencion.pdf',
                    image: { type: 'jpeg', quality: 0.98 },
                    html2canvas: { scale: 2 },
                    jsPDF: { unit: 'in', format: 'letter', orientation: 'landscape' },
                };

                const app = createApp(comprobanteRetencion, {
                    dataQuedan: this.dataQuedan,
                    totalCheque: this.dataInputs.monto_total_quedan,
                    renta: this.dataInputs.monto_isr_quedan,
                    iva: this.dataInputs.monto_iva_quedan,
                    cheque: this.dataInputs.monto_liquido_quedan,
                });
                const div = document.createElement('div');
                const pdfPrint = app.mount(div);
                const html = div.outerHTML;

                html2pdf().set(opt).from(html).save();

                axios.post('/updateFechaRetencionIva', { id_quedan: this.dataInputs.id_quedan }).then((response) => {
                    console.log(response);
                }).catch(errors => {
                    let msg = this.manageError(errors);
                    this.$swal.fire({
                        title: "Operación cancelada",
                        text: msg,
                        icon: "warning",
                        timer: 5000,
                    });
                })
            }
        },

        onCellEdit(rowIndex, cellIndex, value, type = '') {//editando la celda RECIVE ROW, CELL Y EL VALOR A MODIFICAR
            if (type) {//ejecutando la accion cuando escriba en la celda [monto,tipo_prestacion]
                if (type == 'producto') {
                    this.rowsData[rowIndex][cellIndex].producto = value
                } else {
                    this.rowsData[rowIndex][cellIndex].servicio = value
                }
                this.calculateAmount()
            } else {
                this.rowsData[rowIndex][cellIndex] = value
            }
        },
        getInformationBySupplier(supplier) {
            this.dataSuppliers.forEach((suppliers, index) => {
                if (suppliers["id_proveedor"] == supplier) {
                    //data que se pinta en inputs
                    this.dataInputs.giro = suppliers.giro.codigo_giro + " - " + suppliers.giro.nombre_giro
                    this.dataInputs.irs = (suppliers.sujeto_retencion.isrl_sujeto_retencion * 100) + " %"
                    this.dataInputs.iva = (suppliers.sujeto_retencion.iva_sujeto_retencion * 100) + " %"

                    //data que se usa para calculos
                    this.dataForCalculate.irs = suppliers.sujeto_retencion.isrl_sujeto_retencion
                    this.dataForCalculate.iva = suppliers.sujeto_retencion.iva_sujeto_retencion
                }
            })
            this.getAmountBySupplier(supplier)
            this.calculateAmount()
        },
        calculateAmount() {//calculando montos
            let totMontoByRow = 0
            let liquido = 0;
            let montoServicios = 0

            this.rowsData.forEach((valores, index) => {//sumando todos los montos de todas las filas
                totMontoByRow = (!isNaN(parseFloat(totMontoByRow)) ? parseFloat(totMontoByRow) : 0) + (!isNaN(parseFloat(valores[6].producto)) ? parseFloat(valores[6].producto) : 0) + (!isNaN(parseFloat(valores[6].servicio)) ? parseFloat(valores[6].servicio) : 0);
            })
            this.rowsData.forEach((valores, index) => {
                if (valores[6].servicio != '') {//sumando todos solo los montos de servicios de todas las filas
                    montoServicios = (!isNaN(parseFloat(montoServicios)) ? parseFloat(montoServicios) : 0) + (!isNaN(parseFloat(valores[6].servicio)) ? parseFloat(valores[6].servicio) : 0)
                }
            })
            liquido = (totMontoByRow / 1.13).toFixed(2);

            let montoIvaQuedan = 0
            if ((parseFloat(this.dataForCalculate.monto_total_quedan_por_proveedor) + parseFloat(totMontoByRow)) >= 113) {
                montoIvaQuedan = (liquido * this.dataForCalculate.iva);
            }
            console.log(parseFloat(this.dataForCalculate.monto_total_quedan_por_proveedor) + parseFloat(totMontoByRow));
            this.dataInputs.monto_iva_quedan = montoIvaQuedan.toFixed(2);

            let montoIsrQuedan = (montoServicios * this.dataForCalculate.irs).toFixed(2);
            this.dataInputs.monto_isr_quedan = montoIsrQuedan;
            let montoLiquidoQuedan = (totMontoByRow - montoIvaQuedan - montoIsrQuedan).toFixed(2);
            this.dataInputs.monto_liquido_quedan = montoLiquidoQuedan;
            this.dataInputs.monto_total_quedan = totMontoByRow.toFixed(2);
        },
        getAmountBySupplier(id_proveedor) {
            let newDataSupplier = JSON.parse(JSON.stringify(this.totalAmountBySupplier))
            let total = 0;
            newDataSupplier.forEach((element, index) => {
                if (element.id_proveedor === id_proveedor) {
                    if (this.dataInputs.id_quedan != "") {
                        let quedanArray = element.quedan.filter((element) => element.id_quedan <= this.dataInputs.id_quedan);
                        total = quedanArray.sort((a, b) => b.id_quedan - a.id_quedan) // ordena el array por id_quedan en orden descendente
                            .reduce((acc, obj) => acc + parseFloat(obj.monto_total_quedan), 0); // suma el monto_total_quedan de cada objeto
                    } else {
                        let montoTotal = element.quedan.reduce((total, element) =>
                            (parseFloat(total) + parseFloat(element.monto_total_quedan)).toFixed(2), 0);
                        total = montoTotal;
                    }
                }
            });
            console.log(total);
            this.dataForCalculate.monto_total_quedan_por_proveedor = ((!isNaN(parseFloat(total)) ? parseFloat(total) : 0) - (this.dataInputs.monto_total_quedan == '' ? 0 : this.dataInputs.monto_total_quedan))
        },
        onlyNumberDecimal(event) {
            const charCode = (event.which) ? event.which : event.keyCode;
            const value = event.target.textContent;
            const decimalCount = (value.match(/\./g) || []).length;
            if (charCode == 46) {
                if (decimalCount >= 1) {
                    event.preventDefault();
                }
            } else if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                event.preventDefault();
            } else if (decimalCount > 0 && value.split('.')[1].length >= 2) {
                event.preventDefault();
            }
        },
        onInputDescripcionQuedan(event) {
            this.dataInputs.descripcion_quedan = event.target.innerText;
        },
        setValuesToInput() {//funcion para setear la data que trae la prop a attr nuevos
            this.dataInputs.giro = this.dataQuedan.proveedor.giro.codigo_giro + " - " + this.dataQuedan.proveedor.giro.nombre_giro
            this.dataInputs.irs = (this.dataQuedan.proveedor.sujeto_retencion.isrl_sujeto_retencion * 100) + " %"
            this.dataInputs.iva = (this.dataQuedan.proveedor.sujeto_retencion.iva_sujeto_retencion * 100) + " %"
            this.dataInputs.id_proveedor = this.dataQuedan.proveedor.id_proveedor
            this.dataInputs.id_acuerdo_compra = this.dataQuedan.id_acuerdo_compra
            this.dataInputs.numero_acuerdo_quedan = this.dataQuedan.numero_acuerdo_quedan
            this.dataInputs.numero_compromiso_ppto_quedan = this.dataQuedan.numero_compromiso_ppto_quedan
            this.dataInputs.descripcion_quedan = this.dataQuedan.descripcion_quedan
            this.dataInputs.monto_liquido_quedan = this.dataQuedan.monto_liquido_quedan
            this.dataInputs.monto_iva_quedan = this.dataQuedan.monto_iva_quedan
            this.dataInputs.monto_isr_quedan = this.dataQuedan.monto_isr_quedan
            this.dataInputs.nombre_empleado_tesoreria = this.dataQuedan.tesorero.nombre_empleado_tesoreria
            this.dataInputs.fecha_emision = this.dataQuedan.fecha_emision_quedan
            this.dataInputs.id_quedan = this.dataQuedan.id_quedan
            this.dataInputs.id_prioridad_pago = this.dataQuedan.id_prioridad_pago
            this.dataInputs.id_proy_financiado = this.dataQuedan.id_proy_financiado
            this.dataInputs.monto_total_quedan = this.dataQuedan.monto_total_quedan
            this.dataInputs.numero_retencion_iva_quedan = this.dataQuedan.numero_retencion_iva_quedan

        },
        resetValuesToInput() {//funcion para limpiar la data que la llamaremos cuando la data no traiga nada
            this.dataInputs.giro = ""
            this.dataInputs.irs = ""
            this.dataInputs.iva = ""
            this.dataInputs.id_proveedor = ""
            this.dataInputs.id_acuerdo_compra = ""
            this.dataInputs.numero_acuerdo_quedan = ""
            this.dataInputs.numero_compromiso_ppto_quedan = ""
            this.dataInputs.descripcion_quedan = ""
            this.dataInputs.monto_liquido_quedan = ""
            this.dataInputs.monto_iva_quedan = ""
            this.dataInputs.monto_isr_quedan = ""
            this.dataInputs.total = ""
            this.dataInputs.nombre_empleado_tesoreria = ""
            this.dataInputs.fecha_emision = ""
            this.dataInputs.id_quedan = ""
            this.dataInputs.id_prioridad_pago = ""
            this.dataInputs.id_proy_financiado = ""
            this.dataInputs.monto_total_quedan = ""
            this.dataInputs.numero_retencion_iva_quedan = ""

            this.dataForCalculate.irs = ''
            this.dataForCalculate.iva = ''
            this.dataForCalculate.id_proveedor = ''
            this.dataForCalculate.monto_liquido_quedan = ''
            this.dataForCalculate.monto_iva_quedan = ''
            this.dataForCalculate.monto_isr_quedan = ''
            this.dataForCalculate.monto_total_quedan = ''

        },
        addRow() {//agregando fila
            this.rowsData.push({
                0: 1,//numberRow,
                1: '',//Id__detalle_quedan,
                2: '',//FACTURA,
                3: '',//DEPENDENCIA,
                4: '',//NUMERO ACTA	,
                5: '',//CONCEPTO	,
                6: { producto: '', servicio: '' },//Monto en producto y servicio
                7: true,//eliminado_logico,
                8: ''//fecha_factura_det_quedan
            })
        },
        paintPositionRepet() {
            const duplicatePositions = {}; //get position where the acta number was duplicated
            for (let i = 0; i < this.rowsData.length; i++) {
                const row = this.rowsData[i];
                const number = row[4]; // get numbers en position 4
                const dependency = row[3]; // get dependency en position 3

                if (row[7] !== false) {
                    if (!duplicatePositions[dependency]) {
                        duplicatePositions[dependency] = {};
                    }
                    if (duplicatePositions[dependency][number]) {
                        duplicatePositions[dependency][number].push(i);
                    } else {
                        duplicatePositions[dependency][number] = [i];
                    }
                }
            }
            const dependencies = Object.keys(duplicatePositions);
            dependencies.forEach((dependency) => {
                const numbers = Object.keys(duplicatePositions[dependency]).filter(
                    (number) => duplicatePositions[dependency][number].length > 1
                );
                numbers.forEach((number) => {
                    duplicatePositions[dependency][number].forEach((position) => {
                        this.errosrNumeroActa.push(position);
                    });
                });
            });

            setTimeout(() => {
                this.errosrNumeroActa = [];
            }, 5000);

            return this.errosrNumeroActa.length;
        },
        async createQuedan() {
            const confirmed = await this.$swal.fire({
                title: '¿Esta seguro de crear un nuevo quedan?',
                icon: 'question',
                iconHtml: '❓',
                confirmButtonText: 'Si, Agregar el quedan',
                confirmButtonColor: '#141368',
                cancelButtonText: 'Cancelar',
                showCancelButton: true,
                showCloseButton: true
            });
            if (confirmed.isConfirmed) {
                if (this.paintPositionRepet() == 0) {
                    axios.post('/add-quedan', { quedan: this.dataInputs, detalle_quedan: this.rowsData }).then((response) => {
                        this.$emit("actualizar-table-data")
                        this.rowsData = []
                        this.resetValuesToInput()
                        this.addRow()
                        toast.success("El quedan fue guardado con exito", {
                            autoClose: 5000,
                            position: "top-right",
                            transition: "zoom",
                            toastBackgroundColor: "white",
                        });
                    }).catch((Error) => {
                        console.log(Error);
                        if (Error.response.status === 422) {
                            if (Error.response.data.message === "LA DEPENDENCIA ES UN DATO REQUERIDO") {
                                toast.error("La dependencia es requerida en este caso", {
                                    autoClose: 5000,
                                    position: "top-right",
                                    transition: "zoom",
                                    toastBackgroundColor: "white",
                                });
                            } else {
                                toast.error("Al parecer el numero de acta ya existe en este contexto", {
                                    autoClose: 5000,
                                    position: "top-right",
                                    transition: "zoom",
                                    toastBackgroundColor: "white",
                                });
                                let data = Error.response.data.errors
                                var myData = new Object();
                                for (const errorBack in data) {
                                    let split = errorBack.split(".")
                                    let newIndexSplit = split[1]
                                    myData[newIndexSplit] = data[errorBack][0]
                                }
                                this.errosDetalleQuedan = myData;
                                setTimeout(() => {
                                    this.errosDetalleQuedan = [];

                                }, 5000);
                            }
                        } else {
                            toast.error("Al parecer te hacen falta datos por ingresar", {
                                autoClose: 5000,
                                position: "top-right",
                                transition: "zoom",
                                toastBackgroundColor: "white",
                            });
                        }
                    });
                } else {
                    toast.error("Al parecer el numero de acta ya existe en este contexto", {
                        autoClose: 5000,
                        position: "top-right",
                        transition: "zoom",
                        toastBackgroundColor: "white",
                    });
                }
            }
        },
        async updateQuedan() {
            const confirmed = await this.$swal.fire({
                title: '¿Esta seguro de actualizar el quedan?',
                icon: 'question',
                iconHtml: '❓',
                confirmButtonText: 'Si, Actualizar el quedan',
                confirmButtonColor: '#141368',
                cancelButtonText: 'Cancelar',
                showCancelButton: true,
                showCloseButton: true
            });
            if (confirmed.isConfirmed) {
                if (this.paintPositionRepet() == 0) {
                    axios.post('/update-detalle-quedan', { quedan: this.dataInputs, id_quedan: this.dataQuedan.id_quedan, detalle_quedan: this.rowsData })
                        .then((response) => {
                            this.$emit("actualizar-table-data")
                            toast.success("El quedan se ha actualizado con exito", {
                                autoClose: 5000,
                                position: "top-right",
                                transition: "zoom",
                                toastBackgroundColor: "white",
                            });
                        }).catch((Error) => {
                            if (Error.response.status === 422) {
                                if (Error.response.data.message === "LA DEPENDENCIA ES UN DATO REQUERIDO") {
                                    toast.error("La dependencia es requerida en este caso", {
                                        autoClose: 5000,
                                        position: "top-right",
                                        transition: "zoom",
                                        toastBackgroundColor: "white",
                                    });
                                } else {
                                    toast.error("Al parecer el numero de acta ya existe en este contexto", {
                                        autoClose: 5000,
                                        position: "top-right",
                                        transition: "zoom",
                                        toastBackgroundColor: "white",
                                    });
                                    let data = Error.response.data.errors
                                    var myData = new Object();
                                    for (const errorBack in data) {
                                        let split = errorBack.split(".")
                                        let newIndexSplit = split[1]
                                        myData[newIndexSplit] = data[errorBack][0]
                                    }
                                    this.errosDetalleQuedan = myData;
                                    setTimeout(() => {
                                        this.errosDetalleQuedan = [];

                                    }, 5000);
                                }
                            } else {
                                toast.error("Al parecer te hacen falta datos por ingresar", {
                                    autoClose: 5000,
                                    position: "top-right",
                                    transition: "zoom",
                                    toastBackgroundColor: "white",
                                });
                            }
                        });

                } else {
                    toast.error("Al parecer el numero de acta ya existe en este contexto", {
                        autoClose: 5000,
                        position: "top-right",
                        transition: "zoom",
                        toastBackgroundColor: "white",
                    });

                }

            }
        },
        deleteRow(rowIndex) {
            this.$swal.fire({
                title: '¿Esta seguro de eliminar la fila actual?',
                icon: 'question',
                iconHtml: '❓',
                confirmButtonText: 'Si, Eliminar',
                confirmButtonColor: '#D2691E',
                cancelButtonText: 'Cancelar',
                showCancelButton: true,
                showCloseButton: true
            }).then((result) => {
                if (result.isConfirmed) {
                    this.rowsData[rowIndex][7] = false;
                    toast.warning("La fila actual se elimino temporalmente hasta que guarde los cambios", {
                        autoClose: 5000,
                        position: "top-right",
                        transition: "zoom",
                        toastBackgroundColor: "white",
                    });
                }
            })
        },
    },
    watch: {
        showModal: function () {

            if (this.showModal) {//when modal is open we set information tho the atribut
                if (this.dataQuedan != "") {//SI dataQuedan VIENE LLENO HACEMOS ESTO
                    let newDataQuedan = JSON.parse(JSON.stringify(this.dataQuedan))
                    this.conditionButton = false;//cambiamos estado del boton para que actualise en lugar de agregar
                    this.setValuesToInput()
                    this.getInformationBySupplier(newDataQuedan.proveedor.id_proveedor)
                    newDataQuedan.detalle_quedan.forEach((value, index) => {
                        this.rowsData.push({
                            0: '',//numberRow,
                            1: value.id_det_quedan,//Id__detalle_quedan,
                            2: value.numero_factura_det_quedan,//FACTURA,
                            3: value.id_dependencia,//DEPENDENCIA	,
                            4: value.numero_acta_det_quedan,//NUMERO ACTA	,
                            5: value.descripcion_det_quedan,//CONCEPTO,
                            6: { producto: value.producto_factura_det_quedan, servicio: value.servicio_factura_det_quedan },//Monto de producto y servicio,
                            7: true,//eliminado_logico
                            8: value.fecha_factura_det_quedan,//fecha_factura_det_quedan
                        })
                    })
                    this.calculateAmount()
                    this.getAmountBySupplier(newDataQuedan.proveedor.id_proveedor)
                } else {//SI dataQuedan VIENE VACIO HACEMOS ESTO
                    this.addRow()
                    this.resetValuesToInput()
                }

            } else {
                this.rowsData = []
                this.conditionButton = true;//cambiamos estado del boton para que actualise en lugar de agregar
            }
        }
    }


}
</script>

<style>
.encabezado input {
    border: none;
    border-bottom: 2px solid black;
}

.encabezado input[type="date"] {
    width: 50%;
}

.encabezado input[type="text"] {
    width: 60%;
}

.encabezado .row:nth-child(2) .col-6:nth-child(1) {
    margin-left: 60px;
}

.encabezado .row:nth-child(2) .col-6:nth-child(1) input {
    width: 80%;
}

.encabezado .row:nth-child(2) .col-2:nth-child(2) {
    margin-left: -25px;
}

.encabezado .row:nth-child(2) .col-6:nth-child(2) input {
    width: 20%;
}

table.rounded-lg {
    border: 2px solid red;
}

/* ESTILOS PARA ERROR SELECT */
.condition-select .multiselect {
    /* BACKGROUND SELECT */
    background: var(--ms-bg, #fdfd96);

}

.condition-select .multiselect-placeholder {
    /* COLOR SELECT TEXT */
    color: #000000;
}

.condition-select .multiselect-wrapper input {
    /* BACKGROUN COLOR INPUT */
    background-color: #fdfd96;
}

.condition-acta {
    /* BACKGROUND SELECT */
    background: var(--ms-bg, #fd9696);

}

input[type="date"]:focus::-webkit-calendar-picker-indicator {
    -webkit-animation-name: none !important;
    animation-name: none !important;
}

td {
    outline: none;
    /* Desactiva el marco de enfoque */
}
</style>