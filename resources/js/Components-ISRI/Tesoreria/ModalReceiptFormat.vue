<script setup>
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
import moment from 'moment';
import ProcessModal from '@/Components-ISRI/AllModal/ProcessModal.vue'
import { jsPDF } from "jspdf";
import html2pdf from 'html2pdf.js'
</script>
<template>
    <div class="m-1.5 p-10">
        <ProcessModal maxWidth='4xl' :show="view_receipt" @close="$emit('cerrar-modal')">
            <div class=" flex   justify-center pt-2 content-between">
                <div class="px-2">
                    <GeneralButton color="bg-red-700   hover:bg-red-800" text="PDF" icon="pdf" @click="printPdf()" />
                </div>
            </div>
            <div id="income-receipt-pdf">
                <!-- Contenedor 1 -->
                <div class="flex justify-between items-center mt-1">
                    <!-- Encabezado izquierdo  -->
                    <div class="flex-none w-[30%] flex flex-col justify-center items-center">
                        <img src="../../../img/escudo-nacional.png" alt="Escudo El Salvador"
                            class="w-[100px] h-[100px] object-contain mb-[10px]">
                        <div class="text-center text-[14px] font-bold mx-5">REPUBLICA DE EL SALVADOR</div>
                        <div class="text-center text-[14px] font-bold mx-5">MINISTERIO DE SALUD</div>
                    </div>
                    <!-- Encabezado centrado  -->
                    <div class="title-container flex flex-col justify-center items-center">
                        <h1 class="text-[16px] font-bold h-[117px]">RECIBO DE INGRESO</h1>
                        <div class="relative flex flex-row justify-center">
                            <label for="" class="mt-[10px] flex items-center text-[12px]">Numero</label>
                            <input type="text" readonly v-model="receipt_to_print.numero_recibo_ingreso"
                                class="font-bold text-center w-[150px] border-b border-black-600 border-opacity-50 border-solid border-0 py-0 text-[12px] text-red-500 mt-[10px]">
                        </div>
                    </div>
                    <!-- Encabezado derecho -->
                    <div class="flex-none w-[30%] flex flex-col justify-center items-center">
                        <img src="../../../img/isri-logo2.png" alt="Logo ISRI"
                            class="w-[100px] h-[100px] object-contain mb-[10px]">
                        <div class="text-center text-[14px] font-bold mx-5" style="text-align: center;">INSTITUTO
                            SALVADOREÑO DE
                            REHABILITACION INTEGRAL</div>
                    </div>
                </div>
                <!-- Contenedor 3 -->
                <div class="flex justify-between items-center mt-4 mb-2">
                    <div class="flex text-left w-2/3 ml-5">
                        <div class="relative flex w-full flex-row">
                            <label for="" class="flex items-center text-[12px]">Unidad Financiera Institucional
                                (UFI-ISRI)</label>
                        </div>
                    </div>
                    <div class="flex w-1/3 text-left mr-5">
                        <div class="relative flex w-full flex-row">
                            <label for="" class="flex items-center text-[12px]">Fecha</label>
                            <input type="text" readonly v-model="fecha_recibo"
                                class="text-[12px] font-bold text-center w-full border-b border-black-600 border-opacity-50 border-solid border-0 py-0">
                        </div>
                    </div>
                </div>

                <div class="flex justify-between items-center mt-2 mb-5">
                    <table class="table-auto w-full mx-5">
                        <tbody>
                            <tr>
                                <td class="h-auto border border-black" colspan="2">
                                    <!-- First row, first container -->
                                    <div class="flex justify-between items-center mt-1 mb-2">
                                        <div class="flex w-3/4 text-left ml-2">
                                            <div class="relative flex w-full flex-row">
                                                <label for="" class="flex items-center text-[12px] w-max-1/3">Nombre o Razón
                                                    Social</label>
                                                <input type="text" readonly
                                                    v-model="receipt_to_print.cliente_recibo_ingreso"
                                                    class="text-[12px] font-bold text-left border-b border-black-600 border-opacity-50 border-solid border-0 py-0"
                                                    style="width:485px;">
                                            </div>
                                        </div>
                                        <div class="flex w-1/4 text-left mr-2">
                                            <div class="relative flex w-full flex-row">
                                                <label for="" class="flex items-center text-[12px]">Por</label>
                                                <input type="text" readonly v-model="formatedAmount"
                                                    class="text-[12px] font-bold text-center w-full border-b border-black-600 border-opacity-50 border-solid border-0 py-0">
                                            </div>
                                        </div>
                                    </div>
                                    <!-- First row, second container -->
                                    <div class="flex justify-between items-center mt-1 mb-2">
                                        <div class="flex w-full text-left ml-2">
                                            <div class="relative flex w-full flex-row">
                                                <label for="" class="flex items-center text-[12px] w-max-1/3">Total en
                                                    Letras</label>
                                                <input type="text" readonly v-model="receipt_to_print.monto_letras"
                                                    class="text-[11px] font-bold text-left border-b border-black-600 border-opacity-50 border-solid border-0 py-0"
                                                    style="width:750px;">
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr class="h-auto">
                                <td class="text-center w-1/2 border border-black">
                                    <h2 class="text-[12px]">CARGO EN CAJA</h2>
                                </td>
                                <td class="text-center w-1/2 border border-black">
                                    <h2 class=" text-[12px]">CONCEPTO O MANDAMIENTO DE INGRESO</h2>
                                </td>
                            </tr>
                            <tr class="h-auto">
                                <td class="justify-center items-start border-black border-l border-b">
                                    <div class="h-72 flex flex-col">
                                        <div class="flex justify-center items-start mb-2 h-3/4">
                                            <div class="flex w-full text-left mx-4 mt-2">
                                                <div class="font-bold flex items-center text-[12px]">
                                                    {{ receipt_to_print.descripcion_recibo_ingreso }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="flex justify-center items-start mb-2 h-1/4">
                                            <div class="w-full flex flex-col h-auto">
                                                <div
                                                    class="flex w-max-full justify-center mx-4 h-1/2 border-t border-black border-solid">
                                                    <p class="w-max-full flex items-center text-[12px]">Tesorero,
                                                        Pagador o Colector</p>
                                                </div>
                                                <div class="w-max-full flex justify-center mx-4 h-1/2">
                                                    <input type="text" readonly v-model="empleado"
                                                        class="w-full font-bold text-center border-0 py-0 text-[12px]">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="border border-black">
                                    <div class="h-72 flex flex-col">
                                        <div class="flex justify-center items-start mb-2 h-[10%]">
                                            <div class="font-bold flex w-full text-left text-[10px] mx-4 mt-2">
                                                {{ receipt_to_print.id_ccta_presupuestal }} {{ nombre_cuenta }}
                                            </div>
                                        </div>

                                        <div class="flex justify-center items-start mb-2 h-[90%]">
                                            <div class="w-full flex flex-col h-1/8">
                                                <div v-for="(detail, index) in receipt_to_print.detalles" :key="index"
                                                    class="relative flex w-full flex-row center-vertically">
                                                    <label for=""
                                                        class="font-bold flex items-center text-[10px] w-2/3 mx-4 mt-0">
                                                        {{ detail.concepto_ingreso.dependencia ?
                                                            detail.concepto_ingreso.dependencia.codigo_dependencia + ' - ' : ''
                                                        }}
                                                        {{
                                                            detail.concepto_ingreso.nombre_concepto_ingreso }}
                                                    </label>
                                                    <div class="w-1/3 relative">
                                                        <span
                                                            class="mt-[1px] absolute left-0 top-1/2 transform text-[10px] -translate-y-1/2 font-bold">$</span>
                                                        <input type="text" readonly
                                                            v-model="detail.monto_det_recibo_ingreso"
                                                            class="w-full font-bold text-right border-0 text-[10px] pl-8 py-0">
                                                    </div>
                                                    <!-- <input type="text" readonly v-model="detail.monto_det_recibo_ingreso"
                                                    class="w-1/3 font-bold text-right border-0 py-0 text-sm"> -->
                                                </div>
                                                <div class="flex justify-center items-start mb-2 h-1/4 mt-1 center-vertically">
                                                    <div class="relative flex w-full flex-row">
                                                        <label for=""
                                                            class="font-bold flex items-center text-[10px] w-2/3 mx-4 mt-0">
                                                            TOTAL:
                                                        </label>
                                                        <div class="w-1/3 relative">
                                                            <span
                                                                class="absolute left-0 top-1/2 transform text-[10px] -translate-y-1/2 font-bold">$</span>
                                                            <input type="text" readonly
                                                                v-model="receipt_to_print.monto_recibo_ingreso"
                                                                class="w-full font-bold text-right border-0 border-t py-0 text-[10px] pl-8">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>



        </ProcessModal>
    </div>
</template>

<script>
import IncomeReceiptPDF from '@/pdf/Tesoreria/IncomeReceiptPDF.vue';
import { createApp, h } from 'vue'
export default {
    props: {
        view_receipt: {
            type: Boolean,
            default: false,
        },
        receipt_to_print: {
            type: Array,
            default: [],
        },
    },
    data: function () {
        return {

        }
    },
    methods: {
        printPdf() {
            let fecha = moment().format('DD-MM-YYYY');
            let name = 'RECIBO N° '+this.receipt_to_print.numero_recibo_ingreso+' - '+ fecha;
            const opt = {
                margin: 0.2,
                filename: name,
                pagebreak: {mode:'css',before:'#pagebreak'},
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 3, useCORS: true },
                jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' },
            };
            
            const app = createApp(IncomeReceiptPDF, {
                receipt_to_print: this.receipt_to_print,
                formatedAmount: this.formatedAmount,
                empleado: this.empleado,
                nombre_cuenta: this.nombre_cuenta,
                fecha_recibo: this.fecha_recibo
            });
            const div = document.createElement('div');
            const pdfPrint = app.mount(div);
            const html = div.outerHTML;

            html2pdf().set(opt).from(html).save();
            //html2pdf().set(opt).from(html).output('dataurlnewwindow');
        }
    },
    watch: {
    },
    computed: {
        formatedAmount() {
            return '$' + this.receipt_to_print.monto_recibo_ingreso
        },
        empleado() {
            return this.receipt_to_print.empleado_tesoreria ? this.receipt_to_print.empleado_tesoreria.nombre_empleado_tesoreria : ''
        },
        nombre_cuenta() {
            return this.receipt_to_print.cuenta_presupuestal ? this.receipt_to_print.cuenta_presupuestal.nombre_ccta_presupuestal : ''
        },
        fecha_recibo() {
            return this.receipt_to_print.cuenta_presupuestal ? moment(this.receipt_to_print.fecha_recibo_ingreso, 'Y-M-D').format('DD-MM-Y') : ''

        }
    }
}
</script>

<style>
.center-vertically {
    align-items: center;
}

.title-container {
    flex: 1;
    text-align: center;
    align-self: flex-start;
}
</style>