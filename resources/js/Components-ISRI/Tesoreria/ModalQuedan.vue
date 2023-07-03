<script setup>
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
import ProcessModal from '@/Components-ISRI/AllModal/ProcessModal.vue'
</script>
<template>
    <div class="m-1.5">
        <ProcessModal maxWidth='6xl' :show="showModal" @close="$emit('cerrar-modal')">

            <div class="">
                <div class="text-center">
                    <div class=" flex   justify-center pt-2 content-between">
                        <div class="px-2" v-if="dataQuedan == ''">
                            <template v-if="dataForCalculate.montoSuperador == false">
                                <GeneralButton color="bg-green-700   hover:bg-green-800" text="AGREGAR" icon="add"
                                    @click="createQuedan()" />
                            </template>
                            <div v-else class="py-4"></div>
                        </div>
                        <div class="px-2" v-else>

                            <template v-if="dataForCalculate.montoSuperador == false">
                                <GeneralButton
                                    :color="['bg-orange-700 hover:bg-orange-800', incoherencia ? 'animate-pulse animate-infinite animate-duration-[3000ms]' : '']"
                                    text="MODIFICAR" icon="update" @click="updateQuedan()" />
                            </template>
                            <div v-else class="py-4"></div>
                        </div>
                        <svg class="h-7 w-7 absolute top-0 right-0 mt-2 cursor-pointer" viewBox="0 0 25 25"
                            @click="$emit('cerrar-modal')">
                            <path fill="currentColor"
                                d="M7.95 6.536l4.242-4.243a1 1 0 111.415 1.414L9.364 7.95l4.243 4.242a1 1 0 11-1.415 1.415L7.95 9.364l-4.243 4.243a1 1 0 01-1.414-1.415L6.536 7.95 2.293 3.707a1 1 0 011.414-1.414L7.95 6.536z" />
                        </svg>

                    </div>

                    <button type="button" @click="addRow()"
                        :class="rowsData.filter((e) => e['isDelete'] === true).length === 7 ? 'cursor-not-allowed' : ''"
                        style="float: right;margin-right:-4px;margin-top:399px; font-size: 30px; padding: 0 10px; border: 0; background-color: transparent;">
                        <span type="button" data-toggle="tooltip" data-placement="right"
                            :title="rowsData.length === 7 ? 'Llego al limite del factura por quedan' : 'Agregar factura'">
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

                        <div class="overflow-x-auto">

                            <div class="mx-20 mb-3 encabezado">
                                <div class="mb-7 md:flex flex-row justify-items-center">
                                    <div class="mb-4 md:mr-2 md:mb-0 w-100">
                                        <div class="relative flex w-full flex-row">
                                            <label for="" class="flex items-center text-[14px] text-sm">Giro del
                                                proveedor:</label>
                                            <input type="text" style="width: 350px;" disabled v-model="dataInputs.giro"
                                                class="border-b-2 border-black bg-gray-200 placeholder-slate-400 text-sm py-0 transition-colors duration-300 focus:border-[#001b47] focus:outline-none">
                                        </div>
                                    </div>
                                    <div class="mb-4 md:mr-2 md:mb-0 w-25">
                                        <div class="relative flex w-full flex-row">
                                            <label for="" class="flex items-center text-[14px]">Porcentaje de IVA:</label>
                                            <input type="text" style="width: 75px;" v-model="dataInputs.iva" disabled
                                                class="border-b-2 border-black bg-gray-200 placeholder-slate-400 text-sm text-center py-0 transition-colors duration-300 focus:border-[#001b47] focus:outline-none">
                                        </div>
                                    </div>
                                    <div class="mb-4 md:mr-2 md:mb-0 w-25">
                                        <div class="relative flex w-full flex-row">
                                            <label for="" class="flex items-center text-[14px] text-sm">Porcentaje de
                                                ISR:</label>
                                            <input type="text" style="width: 75px;" v-model="dataInputs.irs" disabled
                                                class="border-b-2 border-black bg-gray-200 placeholder-slate-400 text-sm py-0 text-center transition-colors duration-300 focus:border-[#001b47] focus:outline-none">
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
                                            <input type="text" style="width: 80px;" disabled v-model="dataInputs.id_quedan"
                                                class="placeholder-slate-400 bg-gray-200 text-sm py-0 text-center font-bold transition-colors duration-300 focus:border-[#001b47] focus:outline-none border-b-0">
                                        </div>
                                    </div>
                                    <div class="mb-4 md:mr-2 md:mb-0 w-25 h-5">
                                        <div class="relative flex  w-full flex-row">
                                            <label for="" class="flex items-center  text-[14px] text-sm">Fecha emision:
                                            </label>
                                            <input type="date" style="width: 150px;" v-model="dataInputs.fecha_emision"
                                                class="placeholder-slate-400 bg-gray-200 text-sm py-0 text-center font-bold transition-colors duration-300 focus:border-[#001b47 focus:outline-none border-b-0">
                                        </div>
                                    </div>

                                    <div class="mb-4 md:mr-2 md:mb-0  w-40 h-5">
                                        <div class="relative flex  w-full flex-row">
                                            <label for="" class="flex items-center text-[14px]">Cheque :</label>
                                            <input type="text" style="width: 80px;"
                                                v-model="dataInputs.monto_liquido_quedan" disabled
                                                class="placeholder-slate-400 text-sm bg-gray-200 text-center py-0 transition-colors duration-300 focus:border-[#001b47] focus:outline-none border-b-0">
                                        </div>
                                    </div>

                                    <div class="mb-4 md:mr-2 md:mb-0 w-25 h-5">
                                        <div class="relative flex  w-full flex-row">
                                            <label for="" class="flex items-center text-[14px] text-sm">Renta:</label>
                                            <input type="text" style="width: 80px;" v-model="dataInputs.monto_isr_quedan"
                                                disabled
                                                class="placeholder-slate-400 bg-gray-200 text-sm py-0 text-center  transition-colors duration-300 focus:border-[#001b47] focus:outline-none border-b-0">
                                        </div>
                                    </div>

                                    <div class="mb-4 md:mr-2 md:mb-0 w-25 h-5">
                                        <div class="relative flex  w-full flex-row">
                                            <label for="" class="flex items-center text-[14px]">IVA: </label>
                                            <input type="text" style="width: 80px;" v-model="dataInputs.monto_iva_quedan"
                                                disabled
                                                class="placeholder-slate-400 bg-gray-200 text-sm text-center py-0 transition-colors duration-300 focus:border-[#001b47] focus:outline-none border-b-0">
                                        </div>
                                    </div>

                                    <div class="mb-4 md:mr-2 md:mb-0 w-25 h-5">
                                        <div class="relative flex  w-full flex-row">
                                            <label for="" class="flex items-center  text-[14px] text-sm">Total:
                                            </label>
                                            <input type="text" style="width: 80px;" disabled
                                                v-model="dataInputs.monto_total_quedan"
                                                class="placeholder-slate-400 bg-gray-200 text-sm py-0 text-center font-bold transition-colors duration-300 focus:border-[#001b47] focus:outline-none border-b-0">
                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>

                        <div class="max-w-7xl mx-auto pb-20 sm:px-6 lg:px-8 overflow-x-auto">
                            <table class="table-auto mx-auto">
                                <thead>
                                    <tr>
                                        <th class="border-2 border-black h-7 " colspan="1">
                                            <p class="px-[55px] text-sm text-gray-600">PROVEEDOR</p>
                                        </th>
                                        <th class="border-2 border-black text-sm text-gray-600" colspan="6">
                                            DATOS DEL QUEDAN
                                        </th>
                                    </tr>


                                    <tr>
                                        <td class="border-2 border-black" colspan="1">
                                            <div class="relative flex h-8 w-full flex-row-reverse "
                                                :class="{ 'condition-select': dataInputs.id_proveedor == '' }">
                                                <Multiselect v-model="dataInputs.id_proveedor"
                                                    :disabled="dataQuedan.id_estado_quedan > 1 ? true : false"
                                                    noOptionsText="<p class='text-xs'>sin proveedores<p>"
                                                    noResultsText="<p class='text-xs'>sin registros<p>"
                                                    :options="dataForSelectInRow.proveedor" :searchable="true"
                                                    @input="getInformationBySupplier($event, true)" />
                                            </div>
                                        </td>
                                        <th class="border-2 border-black text-xs text-gray-600" colspan="3">
                                            TIPO CONTRATACION
                                        </th>
                                        <th class="border-2 border-black text-xs text-gray-600">
                                            NUMERO DE ACUERDO
                                        </th>
                                        <th class="border-2 border-black text-xs text-gray-600">
                                            NUMERO DE COMPROMISO
                                        </th>
                                        <th class="border-2 border-black text-xs text-gray-600">
                                            NUMERO DE RETENCION
                                        </th>

                                    </tr>


                                    <tr>
                                        <th class="border-2 border-black h-8 text-sm text-gray-600" colspan="1">
                                            CONTRATO
                                        </th>
                                        <td class="border-2 border-black" colspan="3">
                                            <input type="text" v-model="dataInputs.nombre_tipo_doc_adquisicion"
                                                :class="dataQuedan.id_estado_quedan > 1 ? 'bg-[#dcdcdc]' : ''" disabled
                                                class="peer w-full text-sm bg-transparent text-center h-10 border-none px-2 text-slate-900 placeholder-slate-400 transition-colors duration-300 focus:border-none focus:outline-none">
                                        </td>
                                        <td class="border-2 border-black"
                                            :class="{ 'bg-[#fdfd96]': dataInputs.numero_doc_adquisicion == '' }">
                                            <input type="text" v-model="dataInputs.numero_doc_adquisicion" maxlength="20"
                                                :disabled="documentoAdquisicion == '' ? false : true"
                                                class="peer w-full text-sm bg-transparent text-center h-10 border-none px-2 text-slate-900 placeholder-slate-400 transition-colors duration-300 focus:border-none focus:outline-none">
                                        </td>
                                        <td class="border-2 border-black"
                                            :class="{ 'bg-[#fdfd96]': dataInputs.compromiso_ppto_det_doc_adquisicion == '' }">

                                            <input type="text" v-model="dataInputs.compromiso_ppto_det_doc_adquisicion"
                                                :disabled="documentoAdquisicion == '' ? false : true" maxlength="20"
                                                class="peer w-full text-sm bg-transparent text-center h-10 border-none px-2 text-slate-900 placeholder-slate-400 transition-colors duration-300 focus:border-none focus:outline-none">
                                        </td>
                                        <td class="border-2 border-black"
                                            :class="dataInputs.numero_retencion_iva_quedan == '' ? 'bg-[#fdfd96]' : ''">
                                            <input type="number" v-model="dataInputs.numero_retencion_iva_quedan"
                                                maxlength="20" :disabled="dataQuedan.id_estado_quedan > 1 ? true : false"
                                                :class="dataQuedan.id_estado_quedan > 1 ? 'bg-[#dcdcdc]' : ''"
                                                class="peer w-full text-sm bg-transparent text-center h-10 border-none px-2 text-slate-900 placeholder-slate-400 transition-colors duration-300 focus:border-none focus:outline-none">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="border-2 border-black" colspan="1">
                                            <div class="relative flex h-8 w-full flex-row-reverse"
                                                :class="[documentoAdquisicion != '' ? { 'condition-select': dataInputs.id_det_doc_adquisicion == '' } : '']">
                                                <Multiselect v-model="dataInputs.id_det_doc_adquisicion"
                                                    @deselect="clearDataWhenIsDisSelectDocumentAdquisicion()"
                                                    @clear="clearDataWhenIsDisSelectDocumentAdquisicion()"
                                                    @select="DocumentoAdquisicionSelected($event, true)"
                                                    :placeholder="documentoAdquisicion == '' ? 'sin contratos' : 'seleccione contrato'"
                                                    :classes="{
                                                        containerDisabled: 'cursor-not-allowed bg-gray-200 ', placeholder: 'flex items-center text-center h-full absolute left-0 top-0 pointer-events-none bg-transparent leading-snug pl-3.5 text-gray-400 rtl:left-auto rtl:right-0 rtl:pl-0 rtl:pr-3.5',
                                                    }" noOptionsText="<p class='text-xs'>sin contratos<p>"
                                                    noResultsText="<p class='text-xs'>contrato no encontrados<p>"
                                                    :disabled="dataQuedan.id_estado_quedan > 1 ? true : documentoAdquisicion == '' ? true : false"
                                                    :options="documentoAdquisicion" :searchable="true" />
                                            </div>
                                        </td>
                                        <th class="border-2 border-black text-sm text-gray-600" colspan="6">
                                            DETALLE QUEDAN
                                        </th>
                                    </tr>


                                    <tr>
                                        <th class="border-2 border-black text-sm px-2 text-gray-600 w-40">FACTURA</th>
                                        <th class="border-2 border-black text-sm px-2 text-gray-600" colspan="2">FECHA
                                            EMISION</th>
                                        <th class="border-2 border-black text-sm px-3 text-gray-600">DEPENDENCIA</th>
                                        <th class="border-2 border-black text-sm px-4 text-gray-600">NUMERO ACTA</th>
                                        <th class="border-2 border-black text-sm px-7 text-gray-600">MONTO</th>
                                        <th class="border-2 border-black text-sm px-7 text-gray-600">RETENCIONES</th>
                                    </tr>
                                </thead>

                                <tbody class="text-sm" id="content">

                                    <template v-for="( row, rowIndex ) in  rowsData " :key="rowIndex">
                                        <template v-if="row['isDelete']">

                                            <tr @contextmenu.prevent="deleteRow(rowIndex)">
                                                <template v-for="( cell, cellIndex ) in  row " :key="cellIndex">
                                                    <td v-if="cellIndex == 'numero_factura_det_quedan'"
                                                        class="border-2 border-black"
                                                        :class="[cell == '' ? 'bg-[#fdfd96]' : '', dataQuedan.id_estado_quedan > 1 ? 'bg-[#dcdcdc]' : '']">

                                                        <input type="text"
                                                            v-model="rowsData[rowIndex]['numero_factura_det_quedan']"
                                                            maxlength="10"
                                                            :disabled="dataQuedan.id_estado_quedan > 1 ? true : false"
                                                            :class="[cell == '' ? 'bg-[#fdfd96]' : '', dataQuedan.id_estado_quedan > 1 ? 'bg-[#dcdcdc]' : '']"
                                                            class="peer w-full text-sm bg-transparent text-center  border-none px-2 text-slate-900 placeholder-slate-400 transition-colors duration-300 focus:border-none focus:outline-none">
                                                    </td>
                                                    <td v-else-if="cellIndex == 'fecha_factura_det_quedan'" colspan="2"
                                                        class="border-2 border-black">
                                                        <div class="mb-4  md:mb-0">
                                                            <flat-pickr placeholder="seleccione"
                                                                v-model="rowsData[rowIndex]['fecha_factura_det_quedan']"
                                                                :disabled="dataQuedan.id_estado_quedan > 1 ? true : false"
                                                                :class="[rowsData[rowIndex]['fecha_factura_det_quedan'] == '' ? 'bg-[#fdfd96]' : '',
                                                                dataQuedan.id_estado_quedan > 1 ? 'bg-[#dcdcdc]' : ''

                                                                ]"
                                                                class="w-[126px] text-xs text-center cursor-pointer rounded-[5px] border h-8 border-slate-400 text-slate-900 placeholder-slate-400 transition-colors duration-300 focus:border-[#001b47] focus:outline-none"
                                                                :config="config" />
                                                        </div>

                                                    </td>
                                                    <td v-else-if="cellIndex == 'id_dependencia'"
                                                        class="border-2 border-black"
                                                        :class="{ 'condition-select': rowsData[rowIndex]['id_dependencia'] == '' }">
                                                        <div class="relative flex h-8 w-full flex-row-reverse ">
                                                            <Multiselect v-model="rowsData[rowIndex]['id_dependencia']"
                                                                placeholder="seleccione" :classes="{
                                                                    placeholder: 'flex items-center h-full absolute left-0 top-0 pointer-events-none bg-transparent leading-snug pl-3.5 text-gray-400 rtl:left-auto rtl:right-0 rtl:pl-0 rtl:pr-3.5',
                                                                }" :options="dataForSelectInRow.dependencias"
                                                                :searchable="true"
                                                                :disabled="dataQuedan.id_estado_quedan > 1 ? true : false"
                                                                @select="onCellEdit(rowIndex, cellIndex, $event)" />
                                                        </div>
                                                    </td>
                                                    <td v-else-if="cellIndex == 'numero_acta_det_quedan'"
                                                        class="border-2 border-black max-w-[75px]" :class="[
                                                            cell == '' ? 'bg-[#fdfd96]' : '',
                                                            errosDetalleQuedan[rowIndex] ? 'bg-[#fd9696]' : '',
                                                            errosrNumeroActa.includes(rowIndex) ? 'bg-[#fd9696]' : '',
                                                            dataQuedan.id_estado_quedan > 1 ? 'bg-[#dcdcdc]' : ''
                                                        ]"
                                                        @input="onCellEdit(rowIndex, cellIndex, $event.target.innerText)"
                                                        :contenteditable="dataQuedan.id_estado_quedan > 1 ? false : true">
                                                        {{ cell }}</td>


                                                    <td v-else-if="cellIndex == 'monto'" class="border-2 border-black">
                                                        <table>
                                                            <tr>
                                                                <th class="border-2 border-r-black border-b-black border-l-transparent border-t-transparent text-xs text-gray-600 py-1"
                                                                    style="writing-mode: vertical-rl; transform: rotate(180deg);">
                                                                    PRODUCTO
                                                                </th>
                                                                <td :class="[cell.producto_factura_det_quedan == '' ? 'bg-[#fdfd96]' : '', dataQuedan.id_estado_quedan > 1 ? 'bg-[#dcdcdc]' : '']"
                                                                    class="w-full border-2 border-b-black border-x-transparent border-t-transparent">
                                                                    <input type="text"
                                                                        v-model="rowsData[rowIndex]['monto'].producto_factura_det_quedan"
                                                                        maxlength="10"
                                                                        @input="onlyNumberDecimal(rowIndex, cellIndex, $event, 'producto_factura_det_quedan')"
                                                                        :disabled="dataQuedan.id_estado_quedan > 1 ? true : false"
                                                                        class="peer w-full  text-sm bg-transparent text-center  border-none px-2 text-slate-900 placeholder-slate-400 transition-colors duration-300 focus:border-none focus:outline-none">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th class="border-2 border-r-black border-t-black border-l-transparent border-b-transparent text-xs text-gray-600 py-1"
                                                                    style="writing-mode: vertical-rl; transform: rotate(180deg);">
                                                                    SERVICIO
                                                                </th>
                                                                <td
                                                                    :class="[cell.servicio_factura_det_quedan == '' ? 'bg-[#fdfd96]' : '', dataQuedan.id_estado_quedan > 1 ? 'bg-[#dcdcdc]' : '']">
                                                                    <input type="text"
                                                                        v-model="rowsData[rowIndex]['monto'].servicio_factura_det_quedan"
                                                                        maxlength="10"
                                                                        @input="onlyNumberDecimal(rowIndex, cellIndex, $event, 'servicio_factura_det_quedan')"
                                                                        :disabled="dataQuedan.id_estado_quedan > 1 ? true : false"
                                                                        class="peer w-full  text-sm bg-transparent text-center  border-none px-2 text-slate-900 placeholder-slate-400 transition-colors duration-300 focus:border-none focus:outline-none">
                                                                </td>
                                                            </tr>
                                                        </table>

                                                    </td>

                                                    <td v-else-if="cellIndex == 'retenciones'"
                                                        class="border-2 border-black">
                                                        <button type="button"
                                                            @click="rowsData[rowIndex].reajuste = !rowsData[rowIndex].reajuste; taxesByRow()"
                                                            title="Reajustar factura"
                                                            style="float: right;margin-right: -42px;margin-top: -1px;x;font-size: 30px;padding: 0px 10px;border: 0px;background-color: transparent;">
                                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                                class="w-6 h-6">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z" />
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            </svg>

                                                        </button>
                                                        <table>
                                                            <tr>
                                                                <th class="border-2 border-r-black border-b-black border-l-transparent border-t-transparent text-sm text-gray-600"
                                                                    style="writing-mode: vertical-rl; transform: rotate(180deg);">
                                                                    <span class="py-6">IVA</span>
                                                                </th>
                                                                <td :class="[dataQuedan.id_estado_quedan > 1 ? 'bg-[#dcdcdc]' : '']"
                                                                    class="w-full border-2 border-b-black border-x-transparent border-t-transparent ">
                                                                    {{ cell.iva }}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th class="border-2 border-r-black border-t-black border-l-transparent border-b-transparent text-gray-600"
                                                                    style="writing-mode: vertical-rl; transform: rotate(180deg);">
                                                                    <span class="py-2">RENTA</span>
                                                                </th>
                                                                <td :class="[dataQuedan.id_estado_quedan > 1 ? 'bg-[#dcdcdc]' : '']"
                                                                    class="">
                                                                    {{ cell.renta }}
                                                                </td>
                                                            </tr>
                                                        </table>

                                                    </td>

                                                </template>

                                            </tr>
                                            <tr v-show="row['reajuste']">
                                                <!-- <th class="" rowspan="2"></th> -->
                                                <th class="text-xs border-2 border-black  text-white bg-[#E75E2B]"
                                                    colspan="1" rowspan="2">
                                                    <div class="flex justify-center ">
                                                        <span class="font-semibold text-white mt-0.5">JUSTIICACIÓN
                                                            REAJUSTES</span>
                                                    </div>
                                                </th>
                                                <th class="border-2 border-black text-sm px-3 text-gray-600" colspan="4"
                                                    rowspan="2"
                                                    :class="row['justificacion_det_quedan'] == '' || row['justificacion_det_quedan'] === null ? 'bg-[#fdfd96]' : ''">

                                                    <input type="text" v-model="row['justificacion_det_quedan']"
                                                        maxlength="50"
                                                        :disabled="dataQuedan.id_estado_quedan > 1 ? true : false"
                                                        :class="dataQuedan.id_estado_quedan > 1 ? 'bg-[#dcdcdc]' : ''"
                                                        class="peer w-full text-sm font-medium bg-transparent text-center h-10 border-none px-2 text-slate-900  transition-colors duration-300 focus:border-none focus:outline-none">
                                                </th>
                                                <th class="border-2 border-black text-sm px-3 text-white bg-[#E75E2B]">
                                                    REAJUSTE
                                                    PRODUCTO</th>
                                                <th class="border-2 border-black text-sm px-3 text-white bg-[#E75E2B]">
                                                    REAJUSTE
                                                    SERVICIO</th>
                                            </tr>
                                            <tr v-show="row['reajuste']">

                                                <td class="border-2 border-black text-sm px-3"
                                                    :class="row['reajuste_monto'].ajuste_producto_factura_det_quedan == '' || row['reajuste_monto'].ajuste_producto_factura_det_quedan === null ? 'bg-[#fdfd96]' : ''">
                                                    <input type="text"
                                                        v-model="row['reajuste_monto'].ajuste_producto_factura_det_quedan"
                                                        maxlength="10"
                                                        @input="onlyNumberDecimal(rowIndex, 'reajuste_monto', $event, 'ajuste_producto_factura_det_quedan')"
                                                        :disabled="dataQuedan.id_estado_quedan > 1 ? true : false"
                                                        :class="dataQuedan.id_estado_quedan > 1 ? 'bg-[#dcdcdc]' : ''"
                                                        class="peer w-full text-sm font-normal  bg-transparent text-center h-6 border-none px-2   transition-colors duration-300 focus:border-none focus:outline-none">
                                                </td>
                                                <td class="border-2 border-black text-sm px-3"
                                                    :class="row['reajuste_monto'].ajuste_servicio_factura_det_quedan == '' || row['reajuste_monto'].ajuste_servicio_factura_det_quedan === null ? 'bg-[#fdfd96]' : ''">
                                                    <input type="text"
                                                        v-model="row['reajuste_monto'].ajuste_servicio_factura_det_quedan"
                                                        maxlength="10"
                                                        @input="onlyNumberDecimal(rowIndex, 'reajuste_monto', $event, 'ajuste_servicio_factura_det_quedan')"
                                                        :disabled="dataQuedan.id_estado_quedan > 1 ? true : false"
                                                        :class="dataQuedan.id_estado_quedan > 1 ? 'bg-[#dcdcdc]' : ''"
                                                        class="peer w-full text-sm font-normal bg-transparent text-center h-6 border-none px-2  transition-colors duration-300 focus:border-none focus:outline-none">
                                                </td>
                                            </tr>

                                        </template>
                                    </template>
                                </tbody>
                                <tbody>
                                    <tr id="esconder" class="border-none">
                                        <td contenteditable="false" class="py-3 border-none"></td>
                                    </tr>
                                    <tr>
                                        <td class="border-2 border-black " colspan="1" rowspan="2" contenteditable="false">
                                            Descripción
                                        </td>
                                        <td class="border-2 border-black max-w-[250px]" colspan="4" rowspan="2"
                                            :contenteditable="dataQuedan.id_estado_quedan > 1 ? false : true"
                                            @input="onInputDescripcionQuedan"
                                            :class="dataQuedan.id_estado_quedan > 1 ? 'bg-[#dcdcdc]' : ''">
                                            {{ dataInputs.descripcion_quedan }}

                                        </td>
                                        <th class="border-2 border-black text-sm text-gray-600" colspan="1">
                                            PRIORIDAD DE PAGO
                                        </th>
                                        <th class="border-2 border-black py-0 text-sm text-gray-600" colspan="1">
                                            PROYECTO FINANCIADO
                                        </th>
                                    </tr>
                                    <tr>
                                        <td class="border-2 border-black " colspan="1" contenteditable="false">
                                            <div class="relative flex h-8 w-full flex-row-reverse "
                                                :class="{ 'condition-select': dataInputs.id_prioridad_pago == '' || dataInputs.id_prioridad_pago === null }">
                                                <Multiselect v-model="dataInputs.id_prioridad_pago"
                                                    :disabled="dataQuedan.id_estado_quedan > 1 ? true : false"
                                                    :options="dataForSelectInRow.prioridadPago" :searchable="true" />
                                            </div>
                                        </td>
                                        <td class="border-2 border-black " colspan="1" contenteditable="false">
                                            <div class="relative flex h-8 w-full flex-row-reverse "
                                                :class="{ 'condition-select': dataInputs.id_proy_financiado == '' }">
                                                <Multiselect v-model="dataInputs.id_proy_financiado"
                                                    :disabled="dataQuedan.id_estado_quedan > 1 ? true : documentoAdquisicion != '' ? true : false"
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
            type: Object,
            required: true,
        },
        totalAmountBySupplier: {
            type: Array,
            default: [],
            required: true,
        },
        amountByAcquisitionDocumentsDetails: {
            type: Array,
            default: [],
            required: true,
        },

    },
    data() {
        return {
            rowsData: [], // Atributo donde se guarda la información
            errosDetalleQuedan: [], // Errores relacionados con detalle_quedan
            errosrNumeroActa: [], // Errores relacionados con numero_acta
            montoTotalProveedorMes: [], // Monto total del proveedor por mes
            documentoAdquisicion: [], // Contiene contratos por proveedor
            dataInputs: {
                giro: '',
                irs: '',
                iva: '',
                id_proveedor: '',
                id_det_doc_adquisicion: '',
                id_tipo_doc_adquisicion: '',
                nombre_tipo_doc_adquisicion: '',
                numero_doc_adquisicion: '',
                compromiso_ppto_det_doc_adquisicion: '',
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
                numero_acuerdo_quedan: '',
                numero_compromiso_ppto_quedan: '',
            },
            dataForCalculate: {
                giro: '',
                irs: '',
                iva: '',
                id_proveedor: '',
                dui_proveedor: '',
                montoLiquidoQuedan: '',
                monto_iva_quedan: '',
                monto_isr_quedan: '',
                monto_total_quedan: '',
                monto_doc_adquisicion: '',
                montoTotalDetalleDocumentoAdquisicion: '',//OBTIENE EL TOTAL UTILIZADO 
                monto_det_doc_adquisicion: '',//OBTIENE EL TOTAL DEL DETALLE QUE ESTA EN LA BASE
                montoSuperador: false,
            },
            config: {
                altInput: true,
                static: true,
                monthSelectorType: 'static',
                altFormat: "d/m/Y",
                dateFormat: 'Y-m-d',
                maxDate: new Date(), // Bloquear fechas futuras
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
            incoherencia: false,
        };
    },
    methods: {
        onCellEdit(rowIndex, cellIndex, event) {
            this.rowsData[rowIndex][cellIndex] = event
        },
        // Valida que sean solo decimales en los inputs
        onlyNumberDecimal(rowIndex, cellIndex, event, type) {
            let inputValue = event.target.value;
            const regex = /^(\d+)?(\.\d{0,2})?$/;
            inputValue = inputValue.replace(/^\./, ''); // Eliminar punto al inicio
            inputValue = inputValue.replace(/\.(?=.*\.)/g, ''); // Eliminar puntos adicionales
            inputValue = inputValue.replace(/[^0-9.]/g, ''); // Eliminar caracteres no permitidos

            if (!regex.test(inputValue)) {
                const previousValue = event.target.value;
                event.target.value = previousValue.substring(0, previousValue.length - 1);
                const selection = window.getSelection();
                const range = document.createRange();
                range.selectNodeContents(event.target);
                range.collapse(false); // Colapsar al final del rango
                selection.removeAllRanges();
                selection.addRange(range);
            } else {
                if (event.target.value != inputValue) {
                    event.target.value = inputValue
                    const selection = window.getSelection();
                    const range = document.createRange();
                    range.selectNodeContents(event.target);
                    range.collapse(false); // Colapsar al final del rango
                    selection.removeAllRanges();
                    selection.addRange(range);
                } else {
                    event.target.value = inputValue;
                }
            }
            //Seteamos la informacion a objeto con indice x y celda y
            this.rowsData[rowIndex][cellIndex][type] = event.target.value;
            // this.calculateAmount()
            this.taxesByRow(rowIndex)
        },
        //TODO:REVISAR POR QUE CUANDO TENGO UN PROVEEDOR SELECCIONADO AL SELECCINA UN PROVEEDOR SIN CONTRATO NO ME PONE EL NOMBRE DE FACTURA EN TIPO DE CONTRATO
        getInformationBySupplier(supplier, clear = false) {
            if (supplier) {
                // Buscar el proveedor en la lista de proveedores
                const selectedSupplier = this.dataForSelectInRow.proveedor.find((suppliers) => suppliers.value === supplier);

                // Limpiar la variable (this.documentoAdquisicion, this.dataInputs.id_det_doc_adquisicion) que contiene los contratos antes de asignarle nuevos contratos
                this.documentoAdquisicion = []

                if (clear) {//Este if basicamente es utilizado para limpiar toda esta data cuando y solo cuando se haga la seleccion desde el MultiSelect
                    this.dataInputs.id_det_doc_adquisicion = ''//ID_DETALLE_CONTRATO
                    this.dataInputs.id_tipo_doc_adquisicion = ''//ID TIPO CONTRATO
                    this.dataInputs.nombre_tipo_doc_adquisicion = ''//NOMBRE CONTRATO
                    this.dataInputs.numero_doc_adquisicion = ''
                    this.dataInputs.compromiso_ppto_det_doc_adquisicion = ''
                    this.dataInputs.id_proy_financiado = ''
                }
                // Filtrar los contratos por proveedor
                const filteredContracts = JSON.parse(JSON.stringify(this.dataForSelectInRow.documentoAdquisicion.filter((doc) => doc.id_proveedor === supplier)));

                if (filteredContracts != "") {
                    this.documentoAdquisicion = filteredContracts
                    this.dataInputs.nombre_tipo_doc_adquisicion = ''
                } else {
                    this.dataInputs.id_tipo_doc_adquisicion = 3//ID TIPO CONTRATO
                    this.dataInputs.nombre_tipo_doc_adquisicion = "FACTURA"//NOMBRE CONTRATO
                }


                // Datos que se pintan en los inputs
                this.dataInputs.giro = selectedSupplier.codigo_giro && selectedSupplier.nombre_giro ? `${selectedSupplier.codigo_giro} - ${selectedSupplier.nombre_giro}` : 'GIRO NO ESPECIFICADO!';
                this.dataInputs.irs = `${selectedSupplier.isrl_sujeto_retencion * 100} %`
                this.dataInputs.iva = `${selectedSupplier.iva_sujeto_retencion * 100} %`

                // Datos que se usan para cálculos
                this.dataForCalculate.irs = selectedSupplier.isrl_sujeto_retencion
                this.dataForCalculate.dui_proveedor = selectedSupplier.dui_proveedor
                this.dataForCalculate.iva = selectedSupplier.iva_sujeto_retencion
                this.dataInputs.id_proveedor = selectedSupplier.value

            } else {
                // Limpiar los valores cuando el proveedor es null
                this.dataInputs.giro = ''
                this.dataInputs.irs = ''
                this.dataInputs.iva = ''
                this.dataInputs.id_proveedor = ''
                this.dataForCalculate.irs = ''
                this.dataForCalculate.iva = ''
                this.dataForCalculate.id_proveedor = ''
                this.dataInputs.id_det_doc_adquisicion = ''
                this.dataInputs.id_tipo_doc_adquisicion = ''
                this.dataInputs.nombre_tipo_doc_adquisicion = ''
                this.dataInputs.numero_doc_adquisicion = ''
                this.dataInputs.compromiso_ppto_det_doc_adquisicion = ''
                this.dataInputs.id_proy_financiado = ''
                this.documentoAdquisicion = [];
            }

            if (clear) {//Este if basicamente es utilizado para limpiar toda esta data cuando y solo cuando se haga la seleccion desde el MultiSelect

                this.taxesByRow(); // funciona que calcula los impuestos (mas informacion Ctrl+click)
            }
        },

        // Setea la informacion a la data necesaria al seleccionar un item
        DocumentoAdquisicionSelected(id_documentoAdquisicion, clear = false) {
            //al seleccionar contrato
            this.getAmountByDetalleDocumentoAdquisicion(id_documentoAdquisicion)
            if (id_documentoAdquisicion != null) {
                let document = JSON.parse(JSON.stringify(this.dataForSelectInRow.documentoAdquisicion.find((doc) => doc.value === id_documentoAdquisicion)))
                this.dataInputs.id_tipo_doc_adquisicion = document.id_tipo_doc_adquisicion
                this.dataInputs.nombre_tipo_doc_adquisicion = document.nombre_tipo_doc_adquisicion
                this.dataInputs.numero_doc_adquisicion = document.numero_doc_adquisicion
                this.dataInputs.compromiso_ppto_det_doc_adquisicion = document.compromiso_ppto_det_doc_adquisicion
                this.dataInputs.id_proy_financiado = document.id_proy_financiado
                this.dataForCalculate.monto_doc_adquisicion = document.monto_doc_adquisicion
                this.dataForCalculate.monto_det_doc_adquisicion = document.monto_det_doc_adquisicion
            }
            this.taxesByRow(); // funciona que calcula los impuestos (mas informacion Ctrl+click)

        },
        clearDataWhenIsDisSelectDocumentAdquisicion() {
            console.log("LIMPIAMOS TODOS")
            this.dataInputs.id_det_doc_adquisicion = ''
            this.dataInputs.id_tipo_doc_adquisicion = ''
            this.dataInputs.nombre_tipo_doc_adquisicion = ''
            this.dataInputs.numero_doc_adquisicion = ''
            this.dataInputs.compromiso_ppto_det_doc_adquisicion = ''
            this.dataInputs.id_proy_financiado = ''
            this.dataForCalculate.monto_doc_adquisicion = ''
            this.taxesByRow();
        },

        // Calcula los impuestos por fila 
        taxesByRow() {
            // Filtrar los elementos que no se han eliminado
            const rowsData = this.rowsData.filter(element => element['isDelete'] === true);

            // Calcular el total del monto
            // Calcular el total del monto
            const totalMonto = rowsData.reduce((monto, obj) => {
                // Obtener los montos de producto y servicio
                const productoMont = parseFloat(obj["monto"].producto_factura_det_quedan) || 0;
                const servicioMont = parseFloat(obj["monto"].servicio_factura_det_quedan) || 0;

                // Obtener los montos de reajuste si existen
                const ajusteProductoMont = parseFloat(obj["reajuste_monto"]?.ajuste_producto_factura_det_quedan) || 0;
                const ajusteServicioMont = parseFloat(obj["reajuste_monto"]?.ajuste_servicio_factura_det_quedan) || 0;

                // Calcular el subtotal considerando reajustes
                const subtotal = obj["reajuste"] ? ajusteProductoMont + ajusteServicioMont : productoMont + servicioMont;

                // Sumar el subtotal al monto acumulado
                return monto + (isNaN(subtotal) ? 0 : subtotal);
            }, 0);

            const totalCalculosMonto = rowsData.reduce((monto, obj) => {
                // Obtener los montos de producto y servicio
                const productoMont = parseFloat(obj["monto"].producto_factura_det_quedan) || 0;
                const servicioMont = parseFloat(obj["monto"].servicio_factura_det_quedan) || 0;

                // Calcular el subtotal sin considerar reajustes
                const subtotal = productoMont + servicioMont;

                // Sumar el subtotal al monto acumulado
                return monto + (isNaN(subtotal) ? 0 : subtotal);
            }, 0);


            // Recorre todas las filas del detalle del quedan
            rowsData.forEach((element, index) => {
                // Obtener los montos de producto y servicio dependiendo de si existe un reajuste o no
                const productoMont = element.reajuste ? parseFloat(element.reajuste_monto.ajuste_producto_factura_det_quedan) || 0 : parseFloat(element.monto.producto_factura_det_quedan) || 0;
                const servicioMont = element.reajuste ? parseFloat(element.reajuste_monto.ajuste_servicio_factura_det_quedan) || 0 : parseFloat(element.monto.servicio_factura_det_quedan) || 0;

                // Sumando los producto y servicios de todas las filas
                const amountByRow = productoMont + servicioMont;
                // Diviendo entre 1.13
                const liquido = amountByRow / 1.13;
                // Tomando el iva del monto liquido que se divio antes por el porcentaje del proveedor que se ha seleccionado
                const ivaByFila = liquido * this.dataForCalculate.iva;

                // Validando si el proveedor tiene contrato o no  
                const totalFacturas = this.dataForCalculate.monto_doc_adquisicion;
                const montoIsrQuedan = servicioMont * this.dataForCalculate.irs;
                if (this.documentoAdquisicion != "") {

                    rowsData[index]["retenciones"].iva = totalFacturas >= 113 ? ivaByFila.toFixed(2) : (0).toFixed(2);
                    rowsData[index]["retenciones"].renta = this.dataForCalculate.dui_proveedor !== null ? montoIsrQuedan.toFixed(2) : (0).toFixed(2);
                } else {
                    rowsData[index]["retenciones"].iva = totalMonto >= 113 ? ivaByFila.toFixed(2) : (0).toFixed(2);
                    rowsData[index]["retenciones"].renta = this.dataForCalculate.dui_proveedor !== null ? montoIsrQuedan.toFixed(2) : (0).toFixed(2);
                }

                // Obteniendo el iva, la renta y el monto liquido del quedan
                const totalIva = rowsData.reduce((iva, obj) => iva + parseFloat(obj["retenciones"].iva), 0);
                const totalRenta = rowsData.reduce((iva, obj) => iva + parseFloat(obj["retenciones"].renta), 0);
                const montoLiquidoQuedan = totalMonto - totalIva - totalRenta;

                // Actualizar los valores en this.dataInputs
                this.dataInputs.monto_total_quedan = totalMonto.toFixed(2);
                this.dataInputs.monto_liquido_quedan = montoLiquidoQuedan.toFixed(2);
                this.dataInputs.monto_isr_quedan = totalRenta.toFixed(2);
                this.dataInputs.monto_iva_quedan = totalIva.toFixed(2);
                this.dataForCalculate.montoLiquidoQuedan = totalCalculosMonto - totalIva.toFixed(2) - totalRenta.toFixed(2);
            });
            const sumaLiquida = (parseFloat(this.dataForCalculate.montoTotalDetalleDocumentoAdquisicion) || 0) + (parseFloat(this.dataForCalculate.montoLiquidoQuedan) || 0);
            const sumaLiquidaFixed = sumaLiquida.toFixed(2); // Limita el número de decimales a 2
            const montoDetDocAdquisicionFixed = isNaN(parseFloat(this.dataForCalculate.monto_det_doc_adquisicion)) ? 0 : parseFloat(this.dataForCalculate.monto_det_doc_adquisicion);

            /* console.log("MONTO LIQUIDO", this.dataForCalculate.montoLiquidoQuedan);
            console.log("id_det_doc_adquisicion: ", this.dataInputs.id_det_doc_adquisicion)
            console.log("SUMATORIA SEGUIMIENTO MAS EL MONTO LIQUIDO: ", parseFloat(sumaLiquidaFixed));
            console.log("MONTO A SUPERAR: ", montoDetDocAdquisicionFixed)
            console.log("----------------------------------------------------------------------");
            console.log("MONTO LIQUIDO QUEDAN ACTUAL: ", this.dataInputs.monto_liquido_quedan)
            console.log("TOTAL LIQUIDO PERO NO ES EL ORIGINAL: ", this.dataForCalculate.montoLiquidoQuedan)
            console.log("ID DOCUMENTO ADQUISCION: ", this.dataInputs.id_det_doc_adquisicion) */



            if (this.dataInputs.id_det_doc_adquisicion && parseFloat(sumaLiquidaFixed) > parseFloat(montoDetDocAdquisicionFixed)) {
                toast.error("El monto del quedan supera el monto definido en el item seleccionado", {
                    autoClose: 4000,
                    position: "top-right",
                    transition: "zoom",
                    limit: 2,
                    toastBackgroundColor: "white",
                });
                this.dataForCalculate.montoSuperador = true;
            } else {
                this.dataForCalculate.montoSuperador = false;
            }

        },

        // Obtiene el seguimiento de los montos totale de los quedan que se ha usado el item del contrato u orden de compra seleccionado
        getAmountByDetalleDocumentoAdquisicion(id_det_doc_adquisicion) {
            const documentArrays = JSON.parse(JSON.stringify(this.amountByAcquisitionDocumentsDetails.filter(
                (doc) => doc.id_det == id_det_doc_adquisicion
            )))

            if (Array.isArray(documentArrays) && documentArrays.length > 0) {
                const total = documentArrays[0].quedan.reduce((acc, quedan) => {
                    if (this.dataInputs.id_quedan !== "") {
                        return quedan.id_quedan !== this.dataInputs.id_quedan
                            ? acc + parseFloat(quedan.monto_liquido_quedan) || 0
                            : acc;
                    } else {
                        return acc + parseFloat(quedan.monto_liquido_quedan) || 0;
                    }
                }, 0);
                this.dataForCalculate.montoTotalDetalleDocumentoAdquisicion = total

            } else {
                this.dataForCalculate.montoTotalDetalleDocumentoAdquisicion = 0
            }
            console.log("SEGUIMIENTO: " + this.dataForCalculate.montoTotalDetalleDocumentoAdquisicion);
        },
        onInputDescripcionQuedan(event) {//TODO:VERIFICAR ESTA FUNCIONA PARA QUE SIRVE
            this.dataInputs.descripcion_quedan = event.target.innerText;
        },
        setValuesToInput() {
            // Asignar los valores correspondientes a los atributos de dataInputs utilizando los datos de dataForSelectInRow y dataQuedan
            this.dataInputs.giro = `${this.dataForSelectInRow.proveedor["codigo_giro"]} - ${this.dataForSelectInRow.proveedor["nombre_giro"]}`;
            this.dataInputs.irs = `${(this.dataForSelectInRow.proveedor["isrl_sujeto_retencion"] * 100)} %`;
            this.dataInputs.iva = `${(this.dataForSelectInRow.proveedor["iva_sujeto_retencion"] * 100)} %`;
            this.dataInputs.id_proveedor = this.dataQuedan.proveedor.id_proveedor;

            this.dataInputs.descripcion_quedan = this.dataQuedan.descripcion_quedan;
            this.dataInputs.monto_liquido_quedan = this.dataQuedan.monto_liquido_quedan;
            this.dataInputs.monto_iva_quedan = this.dataQuedan.monto_iva_quedan;
            this.dataInputs.monto_isr_quedan = this.dataQuedan.monto_isr_quedan;
            this.dataInputs.nombre_empleado_tesoreria = this.dataQuedan.tesorero.nombre_empleado_tesoreria;
            this.dataInputs.fecha_emision = this.dataQuedan.fecha_emision_quedan;
            this.dataInputs.id_quedan = this.dataQuedan.id_quedan;
            this.dataInputs.id_prioridad_pago = this.dataQuedan.id_prioridad_pago;
            this.dataInputs.id_proy_financiado = this.dataQuedan.id_proy_financiado;
            this.dataInputs.monto_total_quedan = this.dataQuedan.monto_total_quedan;
            this.dataInputs.numero_retencion_iva_quedan = this.dataQuedan.numero_retencion_iva_quedan;
            this.dataInputs.id_det_doc_adquisicion = this.dataQuedan.id_det_doc_adquisicion;
            this.dataInputs.nombre_tipo_doc_adquisicion = this.dataQuedan.tipo_documento_adquisicion.nombre_tipo_doc_adquisicion;


            if (this.dataQuedan.numero_acuerdo_quedan != '' && this.dataQuedan.numero_compromiso_ppto_quedan != '') {

                this.dataInputs.numero_doc_adquisicion = this.dataQuedan.numero_acuerdo_quedan;
                this.dataInputs.compromiso_ppto_det_doc_adquisicion = this.dataQuedan.numero_compromiso_ppto_quedan;
            }
        },
        resetValuesToInput() {
            //funcion para limpiar la data que la llamaremos cuando la data no traiga nada
            /* this.dataInputs.giro = ""
            this.dataInputs.irs = ""
            this.dataInputs.iva = ""
            this.dataInputs.id_proveedor = ""
            this.dataInputs.id_tipo_doc_adquisicion = ''
            this.dataInputs.nombre_tipo_doc_adquisicion = ''
            this.dataInputs.numero_doc_adquisicion = ''
            this.dataInputs.compromiso_ppto_det_doc_adquisicion = ''
            this.dataInputs.id_det_doc_adquisicion = ''
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
            this.dataInputs.numero_retencion_iva_quedan = "" */

            this.dataInputs.giro = ''
            this.dataInputs.irs = ''
            this.dataInputs.iva = ''
            this.dataInputs.id_proveedor = ''
            this.dataInputs.id_det_doc_adquisicion = ''
            this.dataInputs.id_tipo_doc_adquisicion = ''
            this.dataInputs.nombre_tipo_doc_adquisicion = ''
            this.dataInputs.numero_doc_adquisicion = ''
            this.dataInputs.compromiso_ppto_det_doc_adquisicion = ''
            this.dataInputs.numero_retencion_iva_quedan = ''
            this.dataInputs.descripcion_quedan = ''
            this.dataInputs.monto_liquido_quedan = ''
            this.dataInputs.monto_iva_quedan = ''
            this.dataInputs.monto_isr_quedan = ''
            this.dataInputs.monto_total_quedan = ''
            this.dataInputs.nombre_empleado_tesoreria = ''
            this.dataInputs.fecha_emision = ''
            this.dataInputs.id_quedan = ''
            this.dataInputs.id_prioridad_pago = ''
            this.dataInputs.id_proy_financiado = ''

            /* this.dataForCalculate.irs = ''
            this.dataForCalculate.iva = ''
            this.dataForCalculate.id_proveedor = ''
            this.dataForCalculate.monto_iva_quedan = ''
            this.dataForCalculate.monto_isr_quedan = ''
            this.dataForCalculate.monto_total_quedan = ''
            this.dataForCalculate.montoTotalDetalleDocumentoAdquisicion = ''
            this.dataForCalculate.monto_det_doc_adquisicion = ''
            this.dataForCalculate.montoSuperador = ''
            this.documentoAdquisicion = [] */

            this.dataForCalculate.giro = ''
            this.dataForCalculate.irs = ''
            this.dataForCalculate.iva = ''
            this.dataForCalculate.id_proveedor = ''
            this.dataForCalculate.dui_proveedor = ''
            this.dataForCalculate.montoLiquidoQuedan = ''
            this.dataForCalculate.monto_iva_quedan = ''
            this.dataForCalculate.monto_isr_quedan = ''
            this.dataForCalculate.monto_total_quedan = ''
            this.dataForCalculate.monto_doc_adquisicion = ''
            this.dataForCalculate.montoTotalDetalleDocumentoAdquisicion = ''
            this.dataForCalculate.monto_det_doc_adquisicion = ''
            this.dataForCalculate.montoSuperador = false

        },
        addRow() {
            // Función para agregar una nueva fila a rowsData

            //Verifica que solo tenga como maximo 7 facturas por quedan tomando las filas existentes
            if (this.rowsData.filter((e) => e['isDelete'] === true).length !== 7) {
                this.rowsData.push({
                    numberRow: 1,
                    id_det_quedan: '',
                    numero_factura_det_quedan: '',
                    fecha_factura_det_quedan: '',
                    id_dependencia: '',
                    numero_acta_det_quedan: '',
                    monto: { producto_factura_det_quedan: '', servicio_factura_det_quedan: '' },
                    retenciones: { iva: '0.00', renta: '0.00' },
                    reajuste: false,
                    justificacion_det_quedan: '',
                    reajuste_monto: { ajuste_producto_factura_det_quedan: '', ajuste_servicio_factura_det_quedan: '' },
                    isDelete: true,
                });
            } else {
                toast.error("Solo se permiten 7 facturas por quedan :(", {
                    autoClose: 4000,
                    position: "top-right",
                    transition: "zoom",
                    toastBackgroundColor: "white",
                });
            }
            // Agregar un nuevo objeto al final del array rowsData

        },
        // Metodo que pinta las celdas donde el numero de acta sea repetido
        paintPositionRepet() {
            const duplicatePositions = {}; // Objeto para almacenar las posiciones duplicadas de actas

            // Recorrer las filas de rowsData
            for (let i = 0; i < this.rowsData.length; i++) {
                const row = this.rowsData[i];
                const number = row["numero_acta_det_quedan"]; // Obtener el número de acta en la posición 4
                const dependency = row["id_dependencia"]; // Obtener la dependencia en la posición 3

                // Verificar si la fila no está marcada como eliminada
                if (row["isDelete"] !== false) {
                    // Verificar si no existe la dependencia en duplicatePositions
                    if (!duplicatePositions[dependency]) {
                        duplicatePositions[dependency] = {};
                    }

                    // Verificar si ya existe el número de acta en la dependencia
                    if (duplicatePositions[dependency][number]) {
                        duplicatePositions[dependency][number].push(i);
                    } else {
                        duplicatePositions[dependency][number] = [i];
                    }
                }
            }

            const dependencies = Object.keys(duplicatePositions);
            dependencies.forEach((dependency) => {
                // Filtrar los números de acta que tienen más de una posición duplicada
                const numbers = Object.keys(duplicatePositions[dependency]).filter(
                    (number) => duplicatePositions[dependency][number].length > 1
                );

                numbers.forEach((number) => {
                    // Agregar las posiciones duplicadas a errosrNumeroActa
                    duplicatePositions[dependency][number].forEach((position) => {
                        this.errosrNumeroActa.push(position);
                    });
                });
            });

            // Limpiar errosrNumeroActa después de 5 segundos
            setTimeout(() => {
                this.errosrNumeroActa = [];
            }, 5000);

            return this.errosrNumeroActa.length; // Devolver la cantidad de posiciones duplicadas encontradas
        },
        // Envia la informacion del quedan al backend para procesarla (Guardar)
        async createQuedan() {
            // Mostrar confirmación al usuario
            const confirmed = await this.$swal.fire({
                title: '¿Está seguro de crear un nuevo quedan?',
                icon: 'question',
                iconHtml: '❓',
                confirmButtonText: 'Si, Agregar el quedan',
                confirmButtonColor: '#141368',
                cancelButtonText: 'Cancelar',
                showCancelButton: true,
                showCloseButton: true
            });

            if (confirmed.isConfirmed) {
                // Verificar si no hay posiciones duplicadas de números de acta
                if (this.paintPositionRepet() === 0) {
                    axios.post('/add-quedan', { quedan: this.dataInputs, detalle_quedan: this.rowsData })
                        .then((response) => {
                            // Actualizar la tabla de datos
                            this.$emit("actualizar-table-data");
                            // Limpiar rowsData y restablecer valores de entrada
                            this.rowsData = [];
                            this.resetValuesToInput();
                            // Agregar una nueva fila vacía
                            this.addRow();
                            // Mostrar mensaje de éxito
                            toast.success("El quedan fue guardado con éxito", {
                                autoClose: 5000,
                                position: "top-right",
                                transition: "zoom",
                                toastBackgroundColor: "white",
                            });
                        })
                        .catch((error) => {
                            if (error.response.status === 422) {

                                let data = error.response.data.errors
                                var myData = new Object();
                                for (const errorBack in data) {
                                    myData[errorBack] = data[errorBack][0]
                                }

                                Object.keys(myData).forEach(key => {
                                    const mensaje = myData[key];


                                    toast.error(`${mensaje}`, {
                                        autoClose: 5000,
                                        position: "top-right",
                                        transition: "zoom",
                                        toastBackgroundColor: "white",
                                    });

                                })
                            } else {
                                // Mostrar mensaje de error genérico si faltan datos
                                toast.error("Al parecer te hacen falta datos por ingresar", {
                                    autoClose: 5000,
                                    position: "top-right",
                                    transition: "zoom",
                                    toastBackgroundColor: "white",
                                });
                            }
                        });
                } else {
                    // Mostrar mensaje de error si hay posiciones duplicadas de números de acta
                    toast.error("Al parecer el numero de acta ya existe en este contexto", {
                        autoClose: 5000,
                        position: "top-right",
                        transition: "zoom",
                        toastBackgroundColor: "white",
                    });
                }
            }
        },
        // Envia la informacion del quedan al backend para procesarla (Editar)
        async updateQuedan() {
            // Mostrar confirmación al usuario
            const confirmed = await this.$swal.fire({
                title: '¿Está seguro de actualizar el quedan?',
                icon: 'question',
                iconHtml: '❓',
                confirmButtonText: 'Si, Actualizar',
                confirmButtonColor: '#141368',
                cancelButtonText: 'Cancelar',
                showCancelButton: true,
                showCloseButton: true
            });

            if (confirmed.isConfirmed) {
                // Verificar si no hay posiciones duplicadas de números de acta
                if (this.paintPositionRepet() === 0) {
                    axios.post('/update-detalle-quedan', { quedan: this.dataInputs, id_quedan: this.dataQuedan.id_quedan, detalle_quedan: this.rowsData })
                        .then((response) => {
                            // Actualizar la tabla de datos
                            this.$emit("actualizar-table-data");
                            // Mostrar mensaje de éxito
                            toast.success("El quedan se ha actualizado con éxito", {
                                autoClose: 5000,
                                position: "top-right",
                                transition: "zoom",
                                toastBackgroundColor: "white",
                            });
                        })
                        .catch((error) => {
                            if (error.response.status === 422) {


                                let data = error.response.data.errors
                                var myData = new Object();
                                for (const errorBack in data) {
                                    myData[errorBack] = data[errorBack][0]
                                }


                                Object.keys(myData).forEach(key => {
                                    const mensaje = myData[key];


                                    toast.error(`${mensaje}`, {
                                        autoClose: 5000,
                                        position: "top-right",
                                        transition: "zoom",
                                        toastBackgroundColor: "white",
                                    });

                                })
                            } else {
                                // Mostrar mensaje de error genérico si faltan datos
                                toast.error("Al parecer te hacen falta datos por ingresar", {
                                    autoClose: 5000,
                                    position: "top-right",
                                    transition: "zoom",
                                    toastBackgroundColor: "white",
                                });
                            }
                        });
                } else {
                    // Mostrar mensaje de error si hay posiciones duplicadas de números de acta
                    toast.error("Al parecer el numero de acta ya existe en este contexto", {
                        autoClose: 5000,
                        position: "top-right",
                        transition: "zoom",
                        toastBackgroundColor: "white",
                    });
                }
            }
        },
        // Elimina la fila del objeto RowData que contiene todas las celdas que se muestran en la tabla
        deleteRow(rowIndex) {
            // Mostrar confirmación al usuario
            this.$swal.fire({
                title: '¿Está seguro de eliminar la fila actual?',
                icon: 'question',
                iconHtml: '❓',
                confirmButtonText: 'Si, Eliminar',
                confirmButtonColor: '#D2691E',
                cancelButtonText: 'Cancelar',
                showCancelButton: true,
                showCloseButton: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Modificar el objeto para que la fila desaparezca de la tabla (valor en posición 7 se establece como false)
                    this.rowsData[rowIndex]['isDelete'] = false;
                    this.taxesByRow()
                    // Mostrar mensaje de advertencia
                    toast.warning("La fila actual se eliminó temporalmente hasta que guarde los cambios", {
                        autoClose: 5000,
                        position: "top-right",
                        transition: "zoom",
                        toastBackgroundColor: "white",
                    });
                }
            });
        },
    },
    watch: {
        showModal() {
            if (this.showModal) {
                // Si el modal está abierto, configuramos la información correspondiente
                if (this.dataQuedan != "") {
                    // Si dataQuedan tiene valor, realizamos las siguientes acciones
                    let newDataQuedan = JSON.parse(JSON.stringify(this.dataQuedan));

                    this.setValuesToInput();
                    this.getInformationBySupplier(newDataQuedan.proveedor.id_proveedor);
                    newDataQuedan.detalle_quedan.forEach((value, index) => {
                        // Agregamos cada detalle_quedan a la matriz rowsData
                        this.rowsData.push({
                            numberRow: '',
                            id_det_quedan: value.id_det_quedan,
                            numero_factura_det_quedan: value.numero_factura_det_quedan,
                            fecha_factura_det_quedan: value.fecha_factura_det_quedan,
                            id_dependencia: value.id_dependencia,
                            numero_acta_det_quedan: value.numero_acta_det_quedan,
                            monto: { producto_factura_det_quedan: value.producto_factura_det_quedan, servicio_factura_det_quedan: value.servicio_factura_det_quedan },
                            retenciones: { iva: value.iva_factura_det_quedan, renta: value.isr_factura_det_quedan },
                            reajuste: value.justificacion_det_quedan !== null && value.justificacion_det_quedan !== "",
                            justificacion_det_quedan: value.justificacion_det_quedan,
                            reajuste_monto: { ajuste_producto_factura_det_quedan: value.ajuste_producto_factura_det_quedan, ajuste_servicio_factura_det_quedan: value.ajuste_servicio_factura_det_quedan },
                            isDelete: true,
                        });
                    });

                    if (newDataQuedan.detalle_documento_adquisicion) {

                        this.DocumentoAdquisicionSelected(newDataQuedan.detalle_documento_adquisicion.id_det_doc_adquisicion)
                    }
                    this.conditionButton = false; // Cambiamos el estado del botón para actualizar en lugar de agregar



                    // this.taxesByRow(); // funciona que calcula los impuestos (mas informacion Ctrl+click)


                    if (this.dataQuedan.monto_liquido_quedan != this.dataInputs.monto_liquido_quedan) {
                        setTimeout(() => {
                            toast.warning("Se han detectado cambios en los datos del proveedor después de haber utilizado el sujeto de retención correspondiente. Esto ha generado una inconsistencia en la información. Por favor, actualice el registro de quedan para restablecer la coherencia de los datos.", {
                                autoClose: 10000,
                                position: "top-right",
                                transition: "zoom",
                                toastBackgroundColor: "white",
                            });
                            this.incoherencia = true
                        }, 1500);
                    } else {
                        this.incoherencia = false
                    }

                } else {
                    // Si dataQuedan está vacío, agregamos una nueva fila y restablecemos los valores de entrada
                    this.addRow();
                    this.resetValuesToInput();
                }
            } else {
                // Si el modal está cerrado, reiniciamos la matriz rowsData y cambiamos el estado del botón
                this.rowsData = [];
                this.conditionButton = true; // Cambiamos el estado del botón para agregar en lugar de actualizar
            }
        },
    }
}
</script>

<style>
.encabezado input {
    border: none;
    border-bottom: 2px solid black;
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

.flatpickr-day.flatpickr-disabled,
.flatpickr-day.flatpickr-disabled:hover {
    cursor: not-allowed;
    color: rgba(72, 72, 72, 0.3);
}

input[type=number]::-webkit-inner-spin-button,
input[type=number]::-webkit-outer-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

input[type=number]:focus {
    border: none;
}

.overflow-x-auto table tbody td :disabled {
    background-color: transparent;
}
</style>