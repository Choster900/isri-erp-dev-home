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
                    <div class=" justify-content-center pt-2" v-if="(dataQuedan == '')">
                        <GeneralButton color="bg-green-700   hover:bg-green-800" text="AGREGAR" icon="add"
                            @click="createQuedan()" />
                    </div>
                    <div class=" justify-content-center pt-2" v-else>
                        <GeneralButton color="bg-orange-700   hover:bg-orange-800" text="MODIFICAR" icon="update"
                            @click="updateQuedan()" />
                    </div>


                    <button type="button" @click="addRow()"
                        style="float: right;margin-right:-4px;margin-top:265px; font-size: 30px; padding: 0 10px; border: 0; background-color: transparent;">
                        <span type="button" data-toggle="tooltip" data-placement="right" title="AGREGAR FACTURA">
                            +
                        </span>
                    </button>

                    <div class="border-2 border-black mx-8 mb-7" id="main-container">

                        <div class="mb-7 md:flex flex-row justify-between">
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/4 pt-8 px-16">
                            </div>
                            <div class="mb-4 md:mx-10 md:mb-0 basis-1/2 pt-7 ">
                                <div class="border-2 border-black ">
                                    <h3 style class="text-2xl px-10 py-1.5 font-semibold text-gray-600">Titulo del documento

                                    </h3>
                                </div>
                            </div>
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/4 pt-8 pl-10">

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

                        <div class="pl-20 mb-7 encabezado">
                            <div class="pl-48 mb-7 md:flex flex-row ">
                                <div class="mb-4 md:mr-2 md:mb-0  w-40 h-5">
                                    <div class="relative flex  w-full flex-row">
                                        <label for="" class="flex items-center text-[14px]">Cheque $: </label>
                                        <input type="text" style="width: 75px;" v-model="dataInputs.monto_liquido_quedan"
                                            readonly
                                            class="placeholder-slate-400 text-sm text-center py-0 transition-colors duration-300 focus:border-[#001b47] focus:outline-none border-b-0">
                                    </div>
                                </div>

                                <div class="mb-4 md:mr-2 md:mb-0 w-25 h-5">
                                    <div class="relative flex  w-full flex-row">
                                        <label for="" class="flex items-center  text-[14px] text-sm">Renta $:</label>
                                        <input type="text" style="width: 75px;" v-model="dataInputs.monto_isr_quedan"
                                            readonly
                                            class="placeholder-slate-400 text-sm py-0 text-center  transition-colors duration-300 focus:border-[#001b47] focus:outline-none border-b-0">
                                    </div>
                                </div>

                                <div class="mb-4 md:mr-2 md:mb-0 w-25 h-5">
                                    <div class="relative flex  w-full flex-row">
                                        <label for="" class="flex items-center  text-[14px]">IVA $: </label>
                                        <input type="text" style="width: 75px;" v-model="dataInputs.monto_iva_quedan"
                                            readonly
                                            class="placeholder-slate-400 text-sm text-center py-0 transition-colors duration-300 focus:border-[#001b47] focus:outline-none border-b-0">
                                    </div>
                                </div>

                                <div class="mb-4 md:mr-2 md:mb-0 w-25 h-5">
                                    <div class="relative flex  w-full flex-row">
                                        <label for="" class="flex items-center  text-[14px] text-sm">Total $:
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
                                    </tr>
                                    <tr>
                                        <td class="border-2 border-black" colspan="3" contenteditable="false">
                                            <div class="relative  flex h-8 w-full flex-row-reverse ">
                                                <Multiselect placeholder="Proveedor" v-model="dataInputs.id_proveedor"
                                                    :options="dataForSelectInRow.proveedor" :searchable="true"
                                                    @select="getInformationBySupplier($event)" />
                                            </div>
                                        </td>
                                        <td class="border-2 border-black" colspan="2" contenteditable="false">
                                            <div class="relative flex h-8 w-full flex-row-reverse ">
                                                <Multiselect placeholder="Tipo de Contratación"
                                                    v-model="dataInputs.id_acuerdo_compra"
                                                    :options="dataForSelectInRow.acuerdoCompras" :searchable="true"
                                                    @select="onCellEdit(rowIndex, cellIndex, $event)" />
                                            </div>
                                        </td>
                                        <td class="border-2 border-black" colspan="2">
                                            <input type="text" placeholder="Numero Contratacion"
                                                v-model="dataInputs.numero_acuerdo_quedan"
                                                class="peer w-full text-sm  text-center h-2 border-none px-2 text-slate-900 placeholder-slate-400 transition-colors duration-300 focus:border-none focus:outline-none">
                                        </td>
                                        <td class="border-2 border-black" colspan="2">
                                            <input type="text" placeholder="Numero Compromiso"
                                                v-model="dataInputs.numero_compromiso_ppto_quedan"
                                                class="peer w-full text-sm  text-center h-2 border-none px-2 text-slate-900 placeholder-slate-400 transition-colors duration-300 focus:border-none focus:outline-none">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="border-2 border-black  text-sm text-gray-600 ">FACTURA</th>
                                        <th class="border-2 border-black  W-64 text-sm px-10 text-gray-600" colspan="2">
                                            DEPENDENCIA</th>
                                        <th class="border-2 border-black W-60 text-sm px-5 text-gray-600" colspan="2">
                                            TIPO PRESTACIÓN</th>
                                        <th class="border-2 border-black W-60 text-sm px-8 text-gray-600">NUMERO ACTA</th>
                                        <th class="border-2 border-black W-60 text-sm px-10 text-gray-600" colspan="2">
                                            DESCRIPCION</th>
                                        <th class="border-2 border-black W-60 text-sm px-10 text-gray-600">MONTO</th>

                                    </tr>
                                </thead>
                                <tbody class="text-sm" id="content">

                                    <tr v-for="(row, rowIndex) in rowsData" :key="rowIndex" @dblclick="deleteRow(rowIndex)">
                                        <template v-for="(cell, cellIndex) in row" :key="cellIndex">
                                            <template v-if="row[8]">

                                                <template v-if="cellIndex == 2">
                                                    <td @input="onCellEdit(rowIndex, cellIndex, $event.target.innerText)"
                                                        class="border-2 border-black py-14" contenteditable="true">
                                                        {{ cell }}
                                                    </td>
                                                </template>

                                                <template v-else-if="cellIndex == 3">
                                                    <td class="border-2 border-black" colspan="2">
                                                        <div class="relative flex h-8 w-full flex-row-reverse ">
                                                            <Multiselect v-model="rowsData[rowIndex][3]"
                                                                :options="dataForSelectInRow.dependencias"
                                                                :searchable="true"
                                                                @select="onCellEdit(rowIndex, cellIndex, $event)" />
                                                        </div>
                                                    </td>
                                                </template>

                                                <template v-else-if="cellIndex == 4">
                                                    <td class="border-2 border-black" colspan="2">
                                                        <div class="grid grid-cols-2 gap-2 ml-3">
                                                            <div class="text-end">Producto</div>
                                                            <div class="px-0">
                                                                <RadioButton modelValue="1" :name="row[1]"
                                                                    :checked="cell === 1"
                                                                    @update:modelValue="onCellEdit(rowIndex, cellIndex, 1)" />
                                                            </div>
                                                            <div class="text-end ">Servicio</div>
                                                            <div>
                                                                <RadioButton modelValue="2" :name="row[1]"
                                                                    :checked="cell === 2"
                                                                    @update:modelValue="onCellEdit(rowIndex, cellIndex, 2)" />
                                                            </div>
                                                        </div>
                                                    </td>
                                                </template>

                                                <template v-else-if="cellIndex == 5">

                                                    <td class="border-2 border-black"
                                                        @input="onCellEdit(rowIndex, cellIndex, $event.target.innerText)"
                                                        contenteditable="true">{{ cell }}</td>

                                                </template>

                                                <template v-else-if="cellIndex == 6">
                                                    <td colspan="2"
                                                        @input="onCellEdit(rowIndex, cellIndex, $event.target.innerText)"
                                                        class="border-2 border-black px-1 " contenteditable="true">
                                                        {{ cell }}
                                                    </td>
                                                </template>

                                                <template v-else-if="cellIndex == 7">
                                                    <td @input="onCellEdit(rowIndex, cellIndex, $event.target.innerText)"
                                                        class="border-2 border-black px-1 " contenteditable="true"
                                                        @keypress="onlyNumberDecimal($event)">
                                                        {{ cell }}
                                                    </td>

                                                </template>

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
                                    <input type="text" class="border-2 border-solid border-gray-400 text-center w-72"
                                        value="Nombre completo del tesorero" disabled>
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
            type: [],
            required: true,
        },
        dataForSelectInRow: {//prop muestra estado del modal para abrir y cerrar
            type: [],
            required: true,
        },

    },
    data: function () {
        return {
            rowsData: [],//attr donde guardar la information
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
                total: ''
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
            dataSuppliers: null,//attr donde guardar la data de proveedores
        }
    },
    created() {
        this.getAllSuppliers()
    },
    methods: {
        onCellEdit(rowIndex, cellIndex, value) {//editando la celda RECIVE ROW, CELL Y EL VALOR A MODIFICAR
            this.rowsData[rowIndex][cellIndex] = value
            if (cellIndex == 7) {//ejecutando la accion cuando escriba en la celda [monto]
                this.calculateAmount()
            }
        },
        getInformationBySupplier(supplier) {
            this.dataSuppliers.forEach((suppliers, index) => {
                if (suppliers["id_proveedor"] == supplier) {//TODO: no funciona igual cuando se necesita agregar factura

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
        async getAllSuppliers() {
            await axios.get("/getAllSuppliers").then(res => {
                console.log(res.data);
                this.dataSuppliers = res.data
            })
        },
        calculateAmount() {//calculando montos
            let totMontoByRow = 0//TODO: NO FUNCIONA IGUAL
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
            let montoIvaQuedan = (liquido * this.dataInputs.iva).toFixed(2);
            this.dataInputs.monto_iva_quedan = montoIvaQuedan;


            //TODO: esto solo sirve cuando estamos editando
            /* liquido = (totMontoByRow / 1.13).toFixed(2);
            let montoIvaQuedan = (liquido * this.dataQuedan.proveedor.sujeto_retencion.iva_sujeto_retencion).toFixed(2);
            this.dataInputs.monto_iva_quedan = montoIvaQuedan;
            let montoIsrQuedan = (montoServicios * this.dataQuedan.proveedor.sujeto_retencion.isrl_sujeto_retencion).toFixed(2);
            this.dataInputs.monto_isr_quedan = montoIsrQuedan;
            let montoLiquidoQuedan = (totMontoByRow - montoIvaQuedan - montoIsrQuedan).toFixed(2);
            this.dataInputs.monto_liquido_quedan = montoLiquidoQuedan;
            this.dataInputs.total = totMontoByRow; */
        },
        onlyNumberDecimal(event) {
            const charCode = (event.which) ? event.which : event.keyCode;
            const value = event.target.innerText;
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
        async createQuedan() {
            await axios.post('/add-quedan', { quedan: this.dataInputs, detalle_quedan: this.rowsData })
                .then((response) => {
                    console.log(response)
                }).catch((error) => {
                    console.log(error)
                })
        },
        async updateQuedan() {
            await axios.post('/update-detalle-quedan', { quedan: this.dataInputs, id_quedan: this.dataQuedan.id_quedan, detalle_quedan: this.rowsData })
                .then((response) => {
                    console.log(response)
                }).catch((error) => {
                    console.log(error)
                })
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
                    this.setValuesToInput()

                    let newDataQuedan = JSON.parse(JSON.stringify(this.dataQuedan))
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
</style>