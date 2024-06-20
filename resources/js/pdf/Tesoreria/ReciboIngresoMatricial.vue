<script setup>
import moment from 'moment';
</script>
<template>
    <div class="w-[18.5cm] h-[13.9cm] ml-[0.6cm]">
        <!-- Contenido para la sección superior aquí -->
        <div class="h-[0.8cm]"></div>
        <div class="h-[2.7cm] invisible">
            <div class="flex justify-between items-center">
                <!-- Encabezado izquierdo  -->
                <div class="flex-none w-[33.33%] flex flex-col justify-center items-center ml-0">
                    <img src="../../../img/escudo-nacional.png" alt="Escudo El Salvador"
                        class="w-[50px] h-[50px] object-contain">
                    <div class="text-center">
                        <div class="text-[11px] font-[Roboto] font-bold ml-0  text-left">REPUBLICA DE EL SALVADOR</div>
                        <div class="text-[11px] font-[Roboto] font-bold ml-0 text-left">MINISTERIO DE SALUD</div>
                    </div>
                </div>
                <!-- Encabezado centrado  -->
                <div class="title-container w-[33.33%] flex flex-col justify-center items-center">
                    <h1 class=" text-[12px] font-[Roboto] font-bold">RECIBO DE INGRESO</h1>
                    <p class=" text-[11px] font-[Roboto] font-bold mt-[20px]">ORIGINAL - INTERESADO</p>
                </div>
                <!-- Encabezado derecho -->
                <div class="flex-none w-[33.33%] flex flex-col justify-center items-center">
                    <img src="../../../img/isri-logo2.png" alt="Logo ISRI" class="w-[50px] h-[50px] object-contain ">
                    <div class="text-center text-[11px] font-[Roboto] font-bold ml-0 mr-0" style="text-align: center;">INSTITUTO
                        SALVADOREÑO DE
                        REHABILITACION INTEGRAL</div>
                </div>
            </div>
            <!-- Contenedor 3 -->
            <div class="flex justify-between items-center">
                <div class="flex text-left w-full">
                    <div class="relative flex w-full flex-row">
                        <label for="" class="flex items-center text-[10px]">1) UNIDAD FINANCIERA INSTITUCIONAL
                            (UFI-ISRI)</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="h-[9.8cm] w-full">
            <div class="h-[0.9cm] flex w-full justify-between items-center">
                <div class="flex text-left w-[10.5cm] invisible">
                    <div class="relative flex w-full flex-row">
                        <label for="" class="flex items-center text-[10px]">2) Institucion: INSTITUTO SALVADOREÑO DE
                            REHABILITACION DE INVALIDOS</label>
                    </div>
                </div>
                <div class="flex w-[8cm] text-left ">
                    <div class="relative flex w-full flex-row">
                        <label for="" class="w-[3.5cm] flex items-center text-[10px] invisible">3) LUGAR Y FECHA </label>
                        <div class="w-[4.5cm] ml-0 text-center">
                            <p class="mb-0 text-[10px] font-[Roboto] font-bold"> {{ ciudad + ' ' + fecha_recibo }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="h-[0.2cm] flex w-full justify-between items-center"></div>

            <div class="h-[8.7cm] flex justify-between items-center w-full">
                <table class="h-full table-auto w-full">
                    <tbody class="h-full w-full">
                        <tr class="h-[1.85cm] w-full ">
                            <td class="h-full w-full " colspan="2">
                                <!-- First row, first container -->
                                <div class="flex w-full h-[0.45cm] justify-between items-center">
                                    <div class="h-full flex w-full text-left">
                                        <div class="h-full relative flex w-full flex-row">
                                            <label for=""
                                                class="invisible h-full flex w-[4.5cm] items-center text-[10px] mb-0 mt-0">4)
                                                Nombre o
                                                Razón Social</label>
                                            <div class="h-full ml-0 text-left w-[14.0cm]">
                                                <p class="ml-0 text-[10px] font-[Roboto] font-bold">{{ receipt_to_print.cliente_recibo_ingreso
                                                }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- First row, second container -->
                                <div class="h-[0.45cm] w-full flex justify-between items-center">
                                    <div class="h-full flex w-full text-left">
                                        <div class="flex w-[4.5cm] text-left">
                                            <div class="relative flex w-full flex-row">
                                                <label for=""
                                                    class="invisible w-[1.1cm] flex items-center text-[10px] mb-0 mt-0">5)
                                                    Por</label>
                                                <div class="text-center w-[3.4cm]">
                                                    <p class="ml-0 text-[10px] font-[Roboto] font-bold">{{ formatedAmount }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="relative flex w-[14cm] flex-row">
                                            <label for=""
                                                class="invisible w-[3.1cm] flex items-center text-[10px] mb-0 mt-0">6)
                                                Total
                                                en
                                                Letras</label>
                                            <div class="text-left w-[10.9cm]">
                                                <p class="ml-0 text-[9px] font-[Roboto] font-bold">{{ letras1 }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="h-[0.45cm] w-full flex justify-between items-center">
                                    <div class="h-full flex w-full text-left">
                                        <div class="h-full relative flex w-full flex-row">
                                            <div class="text-left w-full">
                                                <p class="ml-0 text-[9px] font-[Roboto] font-bold">{{ letras2 ? letras2 : '' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="h-[0.5cm] w-full flex justify-between items-center">
                                </div>
                            </td>
                        </tr>
                        <tr class="h-[0.60cm] invisible">
                            <td class="text-center w-[9.1cm] border border-black border-t-0 border-r-0 ">
                                <h2 class="text-[10px] mb-0 mt-0">CARGO EN CAJA</h2>
                            </td>
                            <td class="text-center w-[9.4cm] border border-black border-t-0">
                                <h2 class="text-[10px] mb-0 mt-0">CONCEPTO O MANDAMIENTO DE INGRESO</h2>
                            </td>
                        </tr>
                        <tr class="h-[5.2cm]">
                            <td class="h-full w-[9.1cm] justify-center items-start">
                                <div class="h-full flex flex-col ">
                                    <div class="flex justify-center items-start mb-0 h-[4cm]">
                                        <div class="flex w-full text-left mx-4 mt-0">
                                            <div class="font-[Roboto] font-bold flex items-center text-[10px]">
                                                {{ receipt_to_print.descripcion_recibo_ingreso }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex justify-center items-start mb-0 h-[1.2cm]">
                                        <div class="w-full flex flex-col h-full">
                                            <div class="w-max-full flex justify-center mx-4 h-[0.5cm]">
                                                <div class="ml-0 text-center w-full">
                                                    <p class="ml-0 mb-0 mt-0 font-[Roboto] font-bold text-[9px]">{{ empleado }}</p>
                                                </div>
                                            </div>
                                            <div
                                                class="invisible flex w-max-full justify-center mx-4 h-[0.7cm] border-t border-black border-solid">
                                                <p class="w-max-full flex items-center text-[11px]">Tesorero,
                                                    Pagador o Colector</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="h-full w-[9.4cm]">
                                <div class="h-full flex flex-col">
                                    <div class="flex justify-center items-start h-[0.7cm]">
                                        <div class="font-[Roboto] font-bold flex w-full text-left text-[9px] mx-4 mt-0">
                                            {{ receipt_to_print.cuenta_presupuestal.codigo_ccta_presupuestal }} {{ nombre_cuenta }}
                                        </div>
                                    </div>

                                    <div class="flex justify-center items-start h-[4.5cm]">
                                        <div class="w-full flex flex-col h-full">
                                            <div v-for="(detail, index) in receipt_to_print.detalles" :key="index"
                                                class="relative flex w-full h-[0.5cm] flex-row center-vertically mt-0">
                                                <div class="font-[Roboto] font-bold flex items-center text-[9px] w-2/3 mx-4">
                                                    <label class="font-[Roboto] font-bold text-[9px]">
                                                        {{ detail.concepto_ingreso.centro_atencion ?
                                                            detail.concepto_ingreso.centro_atencion.codigo_centro_atencion + ' - ' : ''
                                                        }}
                                                        {{
                                                            detail.concepto_ingreso.nombre_concepto_ingreso }}
                                                    </label>
                                                </div>
                                                <div class="w-1/3 relative mr-2">
                                                    <span
                                                        class=" absolute left-0 top-1/2 transform text-[9px] -translate-y-1/2 font-[Roboto] font-bold">$</span>
                                                    <div class="text-right w-full pl-8 py-0">
                                                        <p class="text-[9px] font-[Roboto] font-bold">{{ detail.monto_det_recibo_ingreso }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex justify-center items-start h-[0.5cm] mt-1">
                                                <div class="relative flex w-full flex-row h-full">
                                                    <label for=""
                                                        class="font-[Roboto] font-bold flex items-center text-[9px] w-2/3 mx-4 mt-0 h-full">
                                                        TOTAL:
                                                    </label>
                                                    <div class="w-1/3 relative mr-2 h-full">
                                                        <span
                                                            class="absolute left-0 top-1/2 transform text-[9px] -translate-y-1/2 font-[Roboto] font-bold">$</span>
                                                        <div
                                                            class="border-t border-gray-700 text-right w-full pl-8 py-0">
                                                            <p class="text-[9px] font-[Roboto] font-bold">{{ receipt_to_print.monto_recibo_ingreso }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr class="h-[0.8cm] w-full invisible">
                            <td class="h-full w-[9.1cm]  border-t-0 border-r-0 border border-black">
                            </td>
                            <td class="h-full w-[9.4cm]  border-t-0 border border-black">
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="h-[0.6cm]"></div>
    </div>
</template>

<script>
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
        formatedAmount: {
            type: String,
            default: '',
        },
        empleado: {
            type: String,
            default: '',
        },
        nombre_cuenta: {
            type: String,
            default: '',
        },
        fecha_recibo: {
            type: String,
            default: '',
        },
        letras1: {
            type: String,
            default: '',
        },
        letras2: {
            type: String,
            default: '',
        }
    },
    data: function () {
        return {
            ciudad: 'S.S '
        }
    },
    methods: {
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
            return this.receipt_to_print.cuenta_presupuestal ? moment(this.receipt_to_print.fecha_recibo_ingreso, 'Y-M-D').format('DD/MM/Y') : ''
        },
    },
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
