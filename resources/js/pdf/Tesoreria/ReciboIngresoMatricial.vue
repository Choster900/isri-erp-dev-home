<template>
    <div class="w-[18.6] ml-[1.5cm] mr-[1.5cm] h-[12.5cm] mt-[0.80cm] mb-[0.6cm]">
        <!-- Contenido para la sección superior aquí -->
        <div class="h-[2.7cm] ">
            <div class="flex justify-between items-center">
                <!-- Encabezado izquierdo  -->
                <div class="flex-none w-[33.33%] flex flex-col justify-center items-center ml-0">
                    <img src="../../../img/escudo-nacional.png" alt="Escudo El Salvador"
                        class="w-[50px] h-[50px] object-contain">
                    <div class="text-center">
                        <div class="text-[11px] font-bold ml-0 mr-5 text-left">REPUBLICA DE EL SALVADOR</div>
                        <div class="text-[11px] font-bold ml-0 mr-5 text-left">MINISTERIO DE SALUD</div>
                    </div>
                </div>
                <!-- Encabezado centrado  -->
                <div class="title-container w-[33.33%] flex flex-col justify-center items-center">
                    <h1 class=" text-[12px] font-bold">RECIBO DE INGRESO</h1>
                    <p class=" text-[11px] font-bold mt-[20px]">ORIGINAL - INTERESADO</p>
                </div>
                <!-- Encabezado derecho -->
                <div class="flex-none w-[33.33%] flex flex-col justify-center items-center">
                    <img src="../../../img/isri-logo2.png" alt="Logo ISRI" class="w-[50px] h-[50px] object-contain ">
                    <div class="text-center text-[11px] font-bold ml-5 mr-0" style="text-align: center;">INSTITUTO
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
                <div class="flex text-left w-[10.7cm] ">
                    <div class="relative flex w-full flex-row ">
                        <label for="" class="flex items-center text-[10px]">2) Institucion: INSTITUTO SALVADOREÑO DE
                            REHABILITACION DE INVALIDOS</label>
                    </div>
                </div>
                <div class="flex w-[7.9cm] text-left">
                    <div class="relative flex w-full flex-row">
                        <label for="" class="w-[3.5cm] flex items-center text-[10px] ">3) LUGAR Y FECHA </label>
                        <div class="w-[4.4cm] ml-1 text-center  text-[10px] font-bold border-b border-black">
                            <p class="mb-[5px]"> {{ ciudad + ' ' + fecha_recibo }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="h-[0.4cm] flex w-full justify-between items-center"></div>

            <div class="h-[8.5cm] flex justify-between items-center w-full">
                <table class="h-full table-auto w-full">
                    <tbody class="h-full w-full">
                        <tr class="h-[1.85cm] w-full">
                            <td class="h-full w-full border border-black" colspan="2">
                                <!-- First row, first container -->
                                <div class="flex w-full h-[0.45cm] justify-between items-center">
                                    <div class="h-full flex w-full text-left">
                                        <div class="h-full relative flex w-full flex-row">
                                            <label for=""
                                                class="h-full flex w-[4.2cm] items-center text-[10px] mb-0.3 mt-[-2px] ">4)
                                                Nombre o
                                                Razón Social</label>
                                            <div class="h-full ml-1 text-left w-[14.4cm] text-[10px] font-bold border-b border-black">
                                                <p class="ml-1 mb-0.3 mt-[-2px]">{{ receipt_to_print.cliente_recibo_ingreso
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
                                                    class="w-[1.3cm] flex items-center text-[10px] mb-0.3 mt-[-2px] ">5)
                                                    Por</label>
                                                <div class="text-center w-[3.2cm]  text-[10px] font-bold border-b border-black">
                                                    <p class="ml-1 mb-0.3 mt-[-2px]">{{ formatedAmount }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="relative flex w-[14.1cm] flex-row">
                                            <label for=""
                                                class="w-[2.9cm] flex items-center text-[10px] mb-0.3 mt-[-2px] ">6)
                                                Total
                                                en
                                                Letras</label>
                                            <div class="text-left w-[11.2cm] text-[10px] font-bold border-b border-black">
                                                <p class="ml-1 mb-0.3 mt-[-2px]">{{ letras1 }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="h-[0.45cm] w-full flex justify-between items-center">
                                    <div class="h-full flex w-full text-left ml-1 mr-1">
                                        <div class="h-full relative flex w-full flex-row">
                                            <div class="text-left w-full  text-[10px] font-bold border-b border-black">
                                                <p class="ml-1 mb-0.3 mt-[-2px]">{{ letras2 ? letras2: '' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="h-[0.5cm] w-full flex justify-between items-center">
                                </div>
                            </td>
                        </tr>
                        <tr class="h-[0.60cm] w-full">
                            <td class="text-center w-1/2 border border-black border-t-0 border-r-0 ">
                                <h2 class="text-[10px] mb-0.5 mt-[-5px]">7) CARGO EN CAJA</h2>
                            </td>
                            <td class="text-center w-1/2 border border-black border-t-0">
                                <h2 class="text-[10px] mb-0.5 mt-[-5px]">8) CONCEPTO O MANDAMIENTO DE INGRESO</h2>
                            </td>
                        </tr>
                        <tr class="h-[5.2cm] w-full">
                            <td class="h-full w-[9.2cm] justify-center items-start border-black border-l border-b">
                                <div class="h-full w-full flex flex-col">
                                    <div class="h-[4cm] w-full flex justify-center items-start mb-2 mx-2">
                                        <div class="flex w-full text-left mt-0">
                                            <div class="w-full font-bold flex items-center text-[10px]">
                                                {{ receipt_to_print.descripcion_recibo_ingreso }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="h-[1.2cm] w-full flex justify-center items-start mb-2">
                                        <div class="w-full flex flex-col h-full">
                                            <div class="h-[0.5cm] w-max-full flex justify-center mx-4 ">
                                                <div class="ml-1 text-center w-full text-[10px] font-bold">
                                                    <p class="ml-1 mb-0.3 mt-[-2px]">{{ empleado }}</p>
                                                </div>
                                            </div>
                                            <div
                                                class="h-[0.7cm] flex w-max-full justify-center mx-4 border-t border-black border-solid ">
                                                <p class="w-max-full flex items-center text-[11px]">Tesorero,
                                                    Pagador o Colector</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="h-full w-[9.4cm] border border-black border-t-0">
                                <div class="h-full w-full flex flex-col">
                                    <div class="h-[0.6cm] w-full flex justify-center items-start ">
                                        <div class="font-bold flex w-full text-left text-[9px] mx-2 mt-0">
                                            {{ receipt_to_print.id_ccta_presupuestal }} {{ nombre_cuenta }}
                                        </div>
                                    </div>

                                    <div class="h-[4.5cm] w-full flex justify-center items-start">
                                        <div class="w-full flex flex-col h-full">
                                            <div v-for="(detail, index) in receipt_to_print.detalles" :key="index"
                                                class="relative flex w-full min-h-[0.45cm] h-auto flex-row center-vertically">
                                                <div class="font-bold flex items-center text-[9px] w-2/3 mx-2">
                                                    <label class="">
                                                        {{ detail.concepto_ingreso.dependencia ?
                                                            detail.concepto_ingreso.dependencia.codigo_dependencia + ' - ' : ''
                                                        }}
                                                        {{
                                                            detail.concepto_ingreso.nombre_concepto_ingreso }}
                                                    </label>
                                                </div>
                                                <div class="w-1/3 relative mr-2">
                                                    <span
                                                        class=" absolute left-0 top-1/2 transform text-[9px] -translate-y-1/2 font-bold">$</span>
                                                    <div class="text-right w-full text-[9px] font-bold pl-8 py-0">
                                                        <p class="">{{ detail.monto_det_recibo_ingreso }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex justify-center items-start h-[0.45cm] mt-1">
                                                <div class="relative flex w-full flex-row h-full">
                                                    <label for=""
                                                        class="font-bold flex items-center text-[9px] w-2/3 mx-2 mt-0 h-full ">
                                                        TOTAL:
                                                    </label>
                                                    <div class="w-1/3 relative mr-2 h-full">
                                                        <span
                                                            class="absolute left-0 top-1/2 transform text-[9px] -translate-y-1/2 font-bold">$</span>
                                                        <div
                                                            class="border-t border-gray-700 text-right w-full text-[9px] font-bold pl-8 py-0">
                                                            <p class="">{{ receipt_to_print.monto_recibo_ingreso }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr class="h-[0.8cm] w-full ">
                            <td class="h-full w-full border-t-0 border-r-0 border border-black">
                            </td>
                            <td class="h-full w-full border-t-0 border border-black">
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
import moment from 'moment';
export default {
    props: {
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
        letras2:{
            type: String,
            default: '',
        }
    },
    data: function () {
        return {
            ciudad: 'S.S'
        }
    },
    computed: {
    }
}
</script>
<style>
.center-vertically {
    align-items: center;
}

.left {
    flex: 0 0 50%;
    text-align: left;
    font-size: 16px;
    font-weight: bold;
    margin-left: 20px;
}

.right {
    flex: 0 0 50%;
    text-align: left;
    font-size: 16px;
    font-weight: bold;
    margin-right: 20px;
}

.title-container {
    flex: 1;
    text-align: center;
    align-self: flex-start;
}

.logo-container {
    flex: 0 0 30%;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}

.logo {
    width: 50px;
    height: 50px;
    object-fit: contain;
    margin-bottom: 10px;
}

.institution {
    text-align: center;
    font-size: 14px;
    font-weight: bold;
    margin-left: 20px;
    margin-right: 20px;
}
</style>