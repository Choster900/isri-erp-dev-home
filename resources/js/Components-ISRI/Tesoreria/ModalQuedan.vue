<script setup>
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
import moment from 'moment';
import ProcessModal from '@/Components-ISRI/AllModal/ProcessModal.vue'
</script>
<template>
    <div class="m-1.5">
        <ProcessModal maxWidth='6xl' :show="showModal" @close="$emit('cerrar-modal')">

            <div class="">
                <div class="text-center">
                    <div class=" justify-content-center pt-2" v-if="dataQuedan == ''">
                        <GeneralButton color="bg-green-700   hover:bg-green-800" text="AGREGAR" icon="add"
                            @click="createQuedan()" />
                    </div>
                    <div class=" justify-content-center pt-2" v-else>
                        <GeneralButton color="bg-orange-700   hover:bg-orange-800" text="MODIFICAR" icon="update"
                            @click="updateQuedan()" />
                    </div>


                    <button type="button" @click="addRow()"
                        style="float: right;margin-right:-4px;margin-top:399px; font-size: 30px; padding: 0 10px; border: 0; background-color: transparent;">
                        <span type="button" data-toggle="tooltip" data-placement="right" title="AGREGAR FACTURA">
                            +
                        </span>
                    </button>

                    <div class="border-2 border-black mx-8 mb-7" id="main-container">

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
                                        <input type="date" style="width: 150px;"  v-model="dataInputs.fecha_emision "
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
                                        <input type="text" style="width: 80px;" readonly v-model="dataInputs.total"
                                            class="placeholder-slate-400 text-sm py-0 text-center font-bold transition-colors duration-300 focus:border-[#001b47] focus:outline-none border-b-0">
                                    </div>
                                </div>


                            </div>
                        </div>

                        <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8 overflow-x-auto">
                            <table class="table-auto mx-auto">
                                <thead>

                                    <tr id="esconder" class="border-none">
                                        <td contenteditable="false" class=""></td>
                                        <td contenteditable="false" class=""></td>
                                        <td contenteditable="false" class=""></td>
                                        <td contenteditable="false" class=""></td>
                                        <td contenteditable="false" class=""></td>
                                        <td contenteditable="false" class=""></td>
                                        <td contenteditable="false" class=""></td>
                                        <td contenteditable="false" class=""></td>
                                        <td contenteditable="false" class=""></td>
                                    </tr>
                                    <tr>
                                        <th class="border-2 border-black  ">
                                            <p class="px-[55px] text-sm text-gray-600">PROVEEDOR</p>
                                        </th>
                                        <th class="border-2 border-black text-sm text-gray-600" colspan="8"
                                            contenteditable="false">
                                            DATOS DEL QUEDAN
                                        </th>
                                    </tr>
                                    <tr>
                                        <td class="border-2 border-black" colspan="1" contenteditable="false">
                                            <div class="relative flex h-8 w-full flex-row-reverse "
                                                :class="{ 'condition-select': dataInputs.id_proveedor == '' }">
                                                <Multiselect v-model="dataInputs.id_proveedor"
                                                    :options="dataForSelectInRow.proveedor" :searchable="true"
                                                    @select="getInformationBySupplier($event)" />
                                            </div>
                                        </td>
                                        <th class="border-2 border-black text-sm text-gray-600" colspan="3">
                                            NUMERO CONTRATACION
                                        </th>
                                        <th class="border-2 border-black text-sm text-gray-600" colspan="8">
                                            NUMERO COMPROMISO
                                        </th>

                                    </tr>

                                    <tr>
                                        <th class="border-2 border-black h-20 text-sm text-gray-600">
                                            ACUERDO CONTRATACION
                                        </th>
                                        <td class="border-2 border-black"
                                            :class="dataInputs.numero_acuerdo_quedan == '' ? 'bg-[#fdfd96]' : ''"
                                            colspan="3" contenteditable="false">
                                            <input type="text" v-model="dataInputs.numero_acuerdo_quedan"
                                                class="peer w-full text-sm bg-transparent text-center h-20 border-none px-2 text-slate-900 placeholder-slate-400 transition-colors duration-300 focus:border-none focus:outline-none">
                                        </td>
                                        <td class="border-2 border-black"
                                            :class="dataInputs.numero_compromiso_ppto_quedan == '' ? 'bg-[#fdfd96]' : ''"
                                            colspan="8" contenteditable="false">
                                            <input type="text" v-model="dataInputs.numero_compromiso_ppto_quedan"
                                                class="peer w-full text-sm bg-transparent text-center h-20 border-none px-2 text-slate-900 placeholder-slate-400 transition-colors duration-300 focus:border-none focus:outline-none">
                                        </td>

                                    </tr>

                                    <tr>
                                        <td class="border-2 border-black" colspan="1" contenteditable="false">
                                            <div class="relative flex h-8 w-full flex-row-reverse"
                                                :class="{ 'condition-select': dataInputs.id_acuerdo_compra == '' }">
                                                <Multiselect v-model="dataInputs.id_acuerdo_compra"
                                                    :options="dataForSelectInRow.acuerdoCompras" :searchable="true" />
                                            </div>
                                        </td>
                                        <th class="border-2 border-black text-sm text-gray-600" colspan="8"
                                            contenteditable="false">
                                            DETALLE QUEDAN
                                        </th>
                                    </tr>
                                    <tr>
                                        <th class="border-2 border-black  text-sm text-gray-600 ">FACTURA</th>
                                        <th class="border-2 border-black  W-64 text-sm px-10 text-gray-600" colspan="2">
                                            DEPENDENCIA</th>
                                        <th class="border-2 border-black W-60 text-sm px-5 text-gray-600" colspan="2">
                                            TIPO PRESTACIÓN</th>
                                        <th class="border-2 border-black w-60 text-sm px-8 text-gray-600">NUMERO ACTA</th>
                                        <th class="border-2 border-black W-72 text-sm px-10 text-gray-600" colspan="2">
                                            CONCEPTO</th>
                                        <th class="border-2 border-black W-60 text-sm px-10 text-gray-600">MONTO</th>

                                    </tr>
                                </thead>
                                <tbody class="text-sm" id="content">

                                    <tr v-for="(row, rowIndex) in rowsData" :key="rowIndex" @dblclick="deleteRow(rowIndex)">
                                        <template v-for="(cell, cellIndex) in row" :key="cellIndex">
                                            <template v-if="row[8]">
                                                <td v-if="cellIndex == 2"
                                                    @input="onCellEdit(rowIndex, cellIndex, $event.target.innerText)"
                                                    class="border-2 border-black py-14 "
                                                    :class="cell == '' ? 'bg-[#fdfd96]' : ''" contenteditable="true">
                                                    {{ cell }}
                                                </td>
                                                <td v-else-if="cellIndex == 3" class="border-2 border-black" colspan="2"
                                                    :class="{ 'condition-select': rowsData[rowIndex][3] == '' }">
                                                    <div class="relative flex h-8 w-full flex-row-reverse ">
                                                        <Multiselect v-model="rowsData[rowIndex][3]"
                                                            :options="dataForSelectInRow.dependencias" :searchable="true"
                                                            @select="onCellEdit(rowIndex, cellIndex, $event)" />
                                                    </div>
                                                </td>
                                                <td v-else-if="cellIndex == 4" class="border-2 border-black" colspan="2"
                                                    :class="cell == '' ? 'bg-[#fdfd96]' : ''">
                                                    <div class="grid grid-cols-2 gap-2 ml-3">
                                                        <div class="text-end">Producto</div>
                                                        <div class="px-0">
                                                            <RadioButton modelValue="1" :name="row[1]" :checked="cell === 1"
                                                                @update:modelValue="onCellEdit(rowIndex, cellIndex, 1)" />
                                                        </div>
                                                        <div class="text-end ">Servicio</div>
                                                        <div>
                                                            <RadioButton modelValue="2" :name="row[1]" :checked="cell === 2"
                                                                @update:modelValue="onCellEdit(rowIndex, cellIndex, 2)" />
                                                        </div>
                                                    </div>
                                                </td>
                                                <td v-else-if="cellIndex == 5" class="border-2 border-black"
                                                    :class="cell == '' ? 'bg-[#fdfd96]' : ''"
                                                    @input="onCellEdit(rowIndex, cellIndex, $event.target.innerText)"
                                                    contenteditable="true">{{ cell }}</td>
                                                <td v-else-if="cellIndex == 6" colspan="2"
                                                    @input="onCellEdit(rowIndex, cellIndex, $event.target.innerText)"
                                                    class="border-2 border-black px-1 " contenteditable="true">
                                                    {{ cell }}
                                                </td>
                                                <td v-else-if="cellIndex == 7"
                                                    @input="onCellEdit(rowIndex, cellIndex, $event.target.innerText)"
                                                    class="border-2 border-black px-1 " contenteditable="true"
                                                    :class="cell == '' ? 'bg-[#fdfd96]' : ''"
                                                    @keypress="onlyNumberDecimal($event)">
                                                    {{ cell }}
                                                </td>
                                            </template>
                                        </template>
                                    </tr>
                                </tbody>
                                <tbody>
                                    <tr id="esconder" class="border-none">
                                        <td contenteditable="false" class="py-3 border-none"></td>
                                        <td colspan="8" contenteditable="false"></td>
                                    </tr>
                                    <tr>
                                        <td class="border-2 border-black py-4" colspan="2" contenteditable="false">
                                            Descripción
                                        </td>
                                        <td class="border-2 border-black" colspan="7" contenteditable="true"
                                            @input="onInputDescripcionQuedan">
                                            {{ dataInputs.descripcion_quedan }}
                                        </td>
                                    </tr>

                                </tbody>
                            </table>

                            <div class="row footer mt-4 mb-5">
                                <div class="text-center">
                                    <input type="text"
                                        class="border-2 border-solid border-gray-400 text-center w-80 text-[15px]"
                                        :value="dataInputs.nombre_tesorero" disabled>
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
            type: [],
            required: true,
        },
        dataSuppliers: {//prop muestra array de suppliers para imprimir en select
            type: [],
            required: true,
        },

    },
    data: function () {
        return {
            rowsData: [],//attr donde guardar la information
            errosQuedan: {},
            dataInputs: {
                giro: '',
                irs: '',
                iva: '',
                id_proveedor: '',
                id_acuerdo_compra: '',
                numero_acuerdo_quedan: '',
                numero_compromiso_ppto_quedan: '',
                descripcion_quedan: '',
                monto_liquido_quedan: '',
                monto_iva_quedan: '',
                monto_isr_quedan: '',
                total: '',
                nombre_tesorero: '',
                fecha_emision: '',
                id_quedan: '',
            },
            dataForCalculate: {
                giro: '',
                irs: '',
                iva: '',
                id_proveedor: '',
                monto_liquido_quedan: '',
                monto_iva_quedan: '',
                monto_isr_quedan: '',
                total: ''
            },

        }
    },
    methods: {
        onCellEdit(rowIndex, cellIndex, value) {//editando la celda RECIVE ROW, CELL Y EL VALOR A MODIFICAR
            this.rowsData[rowIndex][cellIndex] = value
            if (["4", "7"].includes(cellIndex)) {//ejecutando la accion cuando escriba en la celda [monto,tipo_prestacion]
                this.calculateAmount()
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
            this.calculateAmount()
        },
        calculateAmount() {//calculando montos
            let totMontoByRow = 0
            let liquido = 0;
            let montoServicios = 0
            this.rowsData.forEach((valores, index) => {
                totMontoByRow = parseFloat(totMontoByRow) + parseFloat(valores[7])
            })
            this.rowsData.forEach((valores, index) => {
                if (valores[4] == 2) {
                    montoServicios = parseFloat(montoServicios) + parseFloat(valores[7])
                }
            })

            liquido = (totMontoByRow / 1.13).toFixed(2);
            let montoIvaQuedan = (liquido * this.dataForCalculate.iva).toFixed(2);
            this.dataInputs.monto_iva_quedan = montoIvaQuedan;
            let montoIsrQuedan = (montoServicios * this.dataForCalculate.irs).toFixed(2);
            this.dataInputs.monto_isr_quedan = montoIsrQuedan;
            let montoLiquidoQuedan = (totMontoByRow - montoIvaQuedan - montoIsrQuedan).toFixed(2);
            this.dataInputs.monto_liquido_quedan = montoLiquidoQuedan;
            this.dataInputs.total = totMontoByRow;
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
            this.dataInputs.nombre_tesorero = this.dataQuedan.tesorero.nombre_tesorero
            this.dataInputs.fecha_emision = this.dataQuedan.fecha_emision_quedan
            this.dataInputs.id_quedan = this.dataQuedan.id_quedan
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
            this.dataInputs.nombre_tesorero = ""
            this.dataInputs.fecha_emision = ""
            this.dataInputs.id_quedan = ""

            this.dataForCalculate.irs = ''
            this.dataForCalculate.iva = ''
            this.dataForCalculate.id_proveedor = ''
            this.dataForCalculate.monto_liquido_quedan = ''
            this.dataForCalculate.monto_iva_quedan = ''
            this.dataForCalculate.monto_isr_quedan = ''
            this.dataForCalculate.total = ''

        },
        addRow() {//agregando fila
            this.rowsData.push({
                0: 1,//numberRow,
                1: '',//Id__detalle_quedan,
                2: '',//factura_detalle_quedan,
                3: '',//dependencia_detalle_quedan,
                4: '',//Tipo prestacion_detalle_quedan,
                5: '',//Numero Acta_detalle_quedan,
                6: '',//DescripcionFactura_detalle_quedan,
                7: '',//Monto_detalle_quedan,
                8: true,//eliminado_logico,
            })
        },

        async createQuedanAsynchronously() {
            try {
                const response = await axios.post('/add-quedan', {
                    quedan: this.dataInputs,
                    detalle_quedan: this.rowsData
                })
                return true; // indicate success
            } catch (error) {

                let data = error.response.data.errors
                var myData = new Object();
                for (const errorBack in data) {
                    let split = errorBack.split(".")
                    let newIndexSplit = split[1]
                    myData[newIndexSplit] = data[errorBack][0]
                }
                this.errosQuedan = myData;
                console.error(this.errosQuedan);

                setTimeout(() => {
                    this.errosQuedan = {}
                }, 2000);
                return false; // indicate failure
            }
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
                const successAdd = await this.createQuedanAsynchronously();
                if (successAdd) {
                    toast.success("El quedan fue agregado correctamente", {
                        autoClose: 5000,
                        position: "top-right",
                        transition: "zoom",
                        toastBackgroundColor: "white",
                    });
                    this.$emit("actualizar-table-data")
                    this.rowsData = []
                    this.resetValuesToInput()
                    this.addRow()
                } else {
                    toast.error("Error, al parecer tiene datos invalidos", {
                        autoClose: 5000,
                        position: "top-right",
                        transition: "zoom",
                        toastBackgroundColor: "white",
                    });
                }
            }
        },

        async updateQuedanAsynchronously() {
            try {
                const response = await axios.post('/update-detalle-quedan', {
                    quedan: this.dataInputs,
                    id_quedan: this.dataQuedan.id_quedan,
                    detalle_quedan: this.rowsData
                });
                console.log(response);
                return true; // indicate success
            } catch (error) {
                console.error(error);
                return false; // indicate failure
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
                const successUpdate = await this.updateQuedanAsynchronously();
                if (successUpdate) {
                    toast.success("El quedan fue modificado correctamente", {
                        autoClose: 5000,
                        position: "top-right",
                        transition: "zoom",
                        toastBackgroundColor: "white",
                    });
                    this.$emit("actualizar-table-data")

                } else {
                    toast.error("Error, al parecer tiene datos invalidos", {
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
                    this.rowsData[rowIndex][8] = false;
                    toast.warning("La fila actual se elimino temporalmente hasta que guarde los cambios", {
                        autoClose: 5000,
                        position: "top-right",
                        transition: "zoom",
                        toastBackgroundColor: "white",
                    });
                }
            })
        }
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
                            2: value.numero_factura_det_quedan,//factura_detalle_quedan,
                            3: value.id_dependencia,//dependencia_detalle_quedan,
                            4: value.id_tipo_prestacion,//Tipo prestacion_detalle_quedan,
                            5: value.numero_acta_det_quedan,//Numero Acta_detalle_quedan,
                            6: value.descripcion_det_quedan,//DescripcionFactura_detalle_quedan,
                            7: value.total_factura_det_quedan,//Monto_detalle_quedan,
                            8: true,//eliminado_logico
                        })
                    })
                    this.calculateAmount()
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

input[type="date"]:focus::-webkit-calendar-picker-indicator {
    -webkit-animation-name: none !important;
    animation-name: none !important;
}


</style>