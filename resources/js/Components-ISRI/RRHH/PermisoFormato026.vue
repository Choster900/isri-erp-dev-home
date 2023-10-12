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
        <ProcessModal maxWidth='4xl' :show="viewPermission026" @close="$emit('cerrar-modal')">
            <div class="flex justify-center items-center h-full mt-1">
                <div class="px-2">
                    <GeneralButton color="bg-green-600 hover:bg-green-700" text="Aprobar" icon="pdf" @click="printPdf()" />
                </div>
                <div class="px-2">
                    <GeneralButton color="bg-orange-600 hover:bg-orange-700" text="Denegar" icon="pdf"
                        @click="printPdf()" />
                </div>
                <div class="px-2">
                    <GeneralButton color="bg-gray-600 hover:bg-gray-700" text="Cancelar" icon="pdf" @click="printPdf()" />
                </div>
                <div class="px-2">
                    <GeneralButton color="bg-red-600 hover:bg-red-700" text="PDF" icon="pdf" @click="printPdf()" />
                </div>
            </div>

            <div class="flex flex-col justify-center h-full mx-[50px] ">
                <div class="flex flex-col text-center mt-4 relative" id="encabezado">
                    <span class="font-[Bembo] font-bold text-[15px] absolute top-[5px] right-[-150px]">F026</span>
                    <img src="../../../img/isri-gob.png" alt="Logo del instituto" class="w-[250px] mx-auto">
                    <h2 class="font-[Bembo] font-bold text-[14px]">DEPARTAMENTO DE RECURSOS HUMANOS</h2>
                    <h2 class="font-[Bembo] font-bold text-[14px]">REGISTRO DE NO MARCACIÓN DE TARJETA DE ASISTENCIA</h2>
                </div>

                <div class="flex flex-col w-full justify-between items-center mt-6 mb-2" id="fecha">
                    <div class="flex w-full text-left">
                        <div class="relative flex flex-row w-[35%]">
                            <div class="flex justify-start w-[55%]">
                                <label for="" class="font-[MuseoSans] text-[14px] mr-2">
                                    SAN SALVADOR
                                </label>
                            </div>
                            <div class="mr-5 text-center w-[45%] text-[14px] font-bold border-b border-gray-700">
                                <p class="font-[MuseoSans]">
                                    {{ moment(permissionToPrint.fecha_inicio_permiso).format('DD') }}
                                </p>
                            </div>
                        </div>
                        <div class="relative flex flex-row w-[40%]">
                            <div class="flex justify-start w-[20%]">
                                <label for="" class="font-[MuseoSans] text-[14px] ml-2">
                                    DE
                                </label>
                            </div>
                            <div class="mr-4 text-center w-[80%] text-[14px] font-bold border-b border-gray-700">
                                <p class="font-[MuseoSans]">
                                    {{ moment(permissionToPrint.fecha_inicio_permiso).format('MMMM').toUpperCase() }}
                                </p>
                            </div>
                        </div>
                        <div class="relative flex flex-row w-[25%]">
                            <div class="flex justify-start w-[30%]">
                                <label for="" class="font-[MuseoSans] text-[14px] ml-3">
                                    DE
                                </label>
                            </div>
                            <div class="ml-1 text-center w-[70%] text-[14px] font-bold border-b border-gray-700">
                                <p class="font-[MuseoSans]">
                                    {{ moment(permissionToPrint.fecha_inicio_permiso).format('YYYY') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex w-full justify-between items-center mb-2">
                    <div class="flex w-full text-left">
                        <div class="relative flex flex-row w-full">
                            <div class="flex justify-start w-[9%]">
                                <label for="" class="font-[MuseoSans] text-[14px]">
                                    NOMBRE:
                                </label>
                            </div>
                            <div class="text-left w-[91%] text-[14px] font-bold border-b border-gray-700">
                                <p class="font-[MuseoSans] ml-3">
                                    {{ permissionToPrint.empleado ? permissionToPrint.empleado.persona.pnombre_persona : '' }}
                                    {{ permissionToPrint.empleado ? permissionToPrint.empleado.persona.snombre_persona : '' }}
                                    {{ permissionToPrint.empleado ? permissionToPrint.empleado.persona.tnombre_persona : '' }}
                                    {{ permissionToPrint.empleado ? permissionToPrint.empleado.persona.papellido_persona : '' }}
                                    {{ permissionToPrint.empleado ? permissionToPrint.empleado.persona.sapellido_persona : '' }}
                                    {{ permissionToPrint.empleado ? permissionToPrint.empleado.persona.tapellido_persona : '' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex w-full justify-between items-center mb-2">
                    <div class="flex w-full text-left">
                        <div class="relative flex flex-row w-full">
                            <div class="flex justify-start w-[46%]">
                                <label for="" class="font-[MuseoSans] text-[14px]">
                                    CENTRO DE ATENCIÓN, DEPARTAMENTO O SECCIÓN:
                                </label>
                            </div>
                            <div class="text-left w-[54%] text-[14px] font-bold border-b border-gray-700">
                                <p class="font-[MuseoSans] ml-3">{{ centro1 }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex w-full justify-between items-center mb-2">
                    <div class="flex w-full text-left">
                        <div class="relative flex flex-row w-full">
                            <div class="text-left w-full text-[14px] font-bold border-b border-gray-700"
                                :class="centro2 ? '' : 'py-2'">
                                <p class="font-[MuseoSans] ml-2">{{ centro2 }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex w-full justify-between items-center mb-6">
                    <div class="flex w-full text-left">
                        <div class="relative flex flex-row w-full">
                            <div class="flex justify-start w-[9%]">
                                <label for="" class="font-[MuseoSans] text-[14px]">
                                    MOTIVO:
                                </label>
                            </div>
                            <div class="text-left w-[91%] text-[14px] font-bold border-b border-gray-700">
                                <p class="font-[MuseoSans] ml-3">
                                    {{ permissionToPrint.motivo_permiso ? permissionToPrint.motivo_permiso.nombre_motivo_permiso : '' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex w-full justify-between items-center mb-6">
                    <div class="flex w-full text-left">
                        <div class="relative flex flex-row w-full">
                            <div class="flex w-[50%]">
                                <div class="flex justify-start w-[70%] mx-auto">
                                    <div class="flex justify-start w-[50%]">
                                        <label for="" class="font-[MuseoSans] text-[14px]">
                                            HORA ENTRADA:
                                        </label>
                                    </div>
                                    <div class="text-center w-[50%] text-[14px] border-b border-gray-700">
                                        <p class="font-bold font-[MuseoSans]">
                                            {{ permissionToPrint.hora_entrada_permiso ?
                                                formatHour(permissionToPrint.hora_entrada_permiso) :
                                                '-------------' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="flex w-[50%]">
                                <div class="flex justify-start w-[70%] mx-auto">
                                    <div class="flex justify-start w-[50%]">
                                        <label for="" class="font-[MuseoSans] text-[14px]">
                                            HORA SALIDA:
                                        </label>
                                    </div>
                                    <div class="text-center w-[50%] text-[14px] border-b border-gray-700">
                                        <p class="font-bold font-[MuseoSans]">
                                            {{ permissionToPrint.hora_salida_permiso ?
                                                formatHour(permissionToPrint.hora_salida_permiso) :
                                                '-------------' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex w-full justify-between items-center mb-4 mt-4">
                    <div class="flex w-full text-left">
                        <div class="relative flex flex-row w-full">
                            <div class="flex justify-start w-[90%]">
                                <div class="flex justify-start w-[85%]">
                                    <label for="" class="font-[MuseoSans] text-[14px] mb-0.3 mt-[-2px]">
                                        CANTIDAD DE PERMISOS DE NO MARCACION (incluyendo este) EN 
                                        {{ moment(permissionToPrint.fecha_inicio_permiso).format('MMMM').toUpperCase() }}:
                                    </label>
                                </div>
                                <div class="text-center w-[15%] text-[14px] border-b border-gray-700">
                                    <p class="mb-[5px] font-bold font-[MuseoSans] ">{{ limite }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex w-full justify-between items-center mb-2">
                    <div class="flex w-full text-left">
                        <div class="relative flex flex-row w-full">
                            <div class="flex justify-start w-full">
                                <label for="" class="font-[MuseoSans] text-[14px]">
                                    Observaciones:
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex w-full justify-between items-center mb-2">
                    <div class="flex w-full text-left">
                        <div class="relative flex flex-row w-full">
                            <div class="text-left w-full text-[14px] border-b border-gray-700"
                                :class="observation1 ? '' : 'py-2'">
                                <p class="font-bold font-[MuseoSans] ml-2">{{ observation1 }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex w-full justify-between items-center mb-10">
                    <div class="flex w-full text-left">
                        <div class="relative flex flex-row w-full">
                            <div class="text-left w-full text-[14px] border-b border-gray-700"
                                :class="observation2 ? '' : 'py-2'">
                                <p class="font-bold mb-[5px] font-[MuseoSans] ml-3">{{ observation2 }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </ProcessModal>
    </div>
</template>

<script>
import IncomeReceiptPDF from '@/pdf/Tesoreria/IncomeReceiptPDF.vue';
import ReciboIngresoMatricialVue from '@/pdf/Tesoreria/ReciboIngresoMatricial.vue';
import { createApp, h } from 'vue'
export default {
    props: {
        viewPermission026: {
            type: Boolean,
            default: false,
        },
        permissionToPrint: {
            type: Array,
            default: [],
        },
        limite: {
            type: String,
            default: ''
        }
    },
    data: function () {
        return {
            centro1: '',
            centro2: '',
            observation1:'',
            observation2:''
        }
    },
    methods: {
        printPdf() {
            let fecha = moment().format('DD-MM-YYYY');
            let name = 'RECIBO ' + this.receipt_to_print.numero_recibo_ingreso + ' - ' + fecha;
            const opt = {
                margin: 0,
                filename: name,
                //pagebreak: {mode:'css',before:'#pagebreak'},
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 3, useCORS: true },
                //jsPDF: { unit: 'cm', format: [13.95,21.5], orientation: 'landscape' }
                jsPDF: { unit: 'cm', format: 'letter', orientation: 'portrait' },
            };

            const limiteCaracteres = 70;
            if (this.receipt_to_print.monto_letras.length <= limiteCaracteres) {
                this.letras1 = this.receipt_to_print.monto_letras;
                this.letras2 = ''
            } else {
                let textoTruncado = this.receipt_to_print.monto_letras.slice(0, limiteCaracteres);
                let ultimoEspacio = textoTruncado.lastIndexOf(' ');
                this.letras1 = textoTruncado.slice(0, ultimoEspacio);
                this.letras2 = this.receipt_to_print.monto_letras.slice(ultimoEspacio + 1);
            }

            const app = createApp(ReciboIngresoMatricialVue, {
                receipt_to_print: this.receipt_to_print,
                formatedAmount: this.receipt_to_print.monto_recibo_ingreso,
                empleado: this.empleado,
                nombre_cuenta: this.nombre_cuenta,
                fecha_recibo: this.fecha_recibo,
                letras1: this.letras1,
                letras2: this.letras2
            });
            const div = document.createElement('div');
            const pdfPrint = app.mount(div);
            const html = div.outerHTML;

            html2pdf().set(opt).from(html).save();
            //html2pdf().set(opt).from(html).output('dataurlnewwindow');
        },
        getCentro() {
            let limiteCaracteres = 50;
            let string = this.permissionToPrint.plaza_asignada.dependencia.nombre_dependencia;
            if (string) {
                if (string.length <= limiteCaracteres) {
                    this.centro1 = string;
                } else {
                    let textoTruncado = string.slice(0, limiteCaracteres);
                    let ultimoEspacio = textoTruncado.lastIndexOf(' ');
                    this.centro1 = textoTruncado.slice(0, ultimoEspacio);
                    this.centro2 = string.slice(ultimoEspacio + 1);
                }
            }
        },
        getObservation() {
            let limiteCaracteres = 105;
            let string = this.permissionToPrint.comentarios_permiso;
            if (string) {
                if (string.length <= limiteCaracteres) {
                    this.observation1 = string;
                } else {
                    let textoTruncado = string.slice(0, limiteCaracteres);
                    let ultimoEspacio = textoTruncado.lastIndexOf(' ');
                    this.observation1 = textoTruncado.slice(0, ultimoEspacio);
                    this.observation2 = string.slice(ultimoEspacio + 1);
                }
            }
        },
        formatHour(time) {
            const [hora, minutos] = time.split(':');
            const hora12 = (parseInt(hora) % 12).toString();
            const amPm = parseInt(hora) < 12 ? 'AM' : 'PM';

            // Añade un 0 delante si la hora tiene un solo dígito
            const horaFormateada = hora12.padStart(2, '0');
            // Añade un 0 delante si los minutos tienen un solo dígito
            const minutosFormateados = minutos.padStart(2, '0');

            return `${horaFormateada}:${minutosFormateados} ${amPm}`;
        }
    },
    watch: {
        viewPermission026: function (value, oldValue) {
            if (value) {
                this.centro1 = ''
                this.centro2 = ''
                this.observation1 = ''
                this.observation2 = ''
                this.getCentro()
                this.getObservation()
            }
        },
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
            return this.receipt_to_print.cuenta_presupuestal ? moment(this.receipt_to_print.fecha_recibo_ingreso, 'Y-M-D').format('DD/MM/Y') : ''
        },
    },
}
</script>

<style scoped>
.flex-container {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100%;
}

img {
    max-height: 100%;
    max-width: 100%;
}
</style>