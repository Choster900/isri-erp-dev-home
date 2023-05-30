<template>
    <div class=" flex w-full h-1/2   ">
        <div class="h-full w-[1.55cm]">
            <!-- Contenido del primer div -->
        </div>
        <div class="h-full w-[18.6cm]">
                    <!-- Contenido para la sección superior aquí -->
        <div class="h-[0.6cm] w-full "></div>
        <div class="h-[2.6cm] w-full ">
        </div>
        <div class="h-[0.5cm] w-full"></div>
        <div class="h-[0.5cm] w-full ">
            <div class="h-[0.5cm] flex w-full justify-between items-center">
                <div class="h-[0.5cm] flex text-left w-[10.7cm] invisible">
                    <div class="relative flex w-full flex-row ">
                        <label for="" class="flex items-center text-[10px]">2) Institucion: INSTITUTO SALVADOREÑO DE
                            REHABILITACION DE INVALIDOS</label>
                    </div>
                </div>
                <div class="h-[0.5cm] flex w-[7.9cm] text-left">
                    <div class="relative flex w-full flex-row">
                        <label for="" class="w-[3.5cm] flex items-center text-[10px] invisible">3) LUGAR Y FECHA </label>
                        <div class="w-[4.4cm] ml-1 text-center  text-[10px] font-bold ">
                            <p class="mb-[5px]"> {{ ciudad + ' ' + fecha_recibo }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="h-[0.45cm] w-full"></div>
        <div class="h-[8.5cm] flex justify-between items-center w-full">
            <table class="h-full w-full">
                <tbody class="h-full w-full">
                    <tr class="h-[1.85cm] w-full">
                        <td class="h-full w-full " colspan="2">
                            <!-- First row, first container -->
                            <div class="flex w-full h-[0.45cm] justify-between items-center">
                                <div class="h-full flex w-full text-left">
                                    <div class="h-full relative flex w-full flex-row">
                                        <label for="" class="h-full flex w-[4.2cm] items-center text-[10px] invisible ">4)
                                            Nombre o
                                            Razón Social</label>
                                        <div
                                            class="h-full ml-1 text-left w-[14.1cm] text-[10px] font-bold ">
                                            <p class="">{{ receipt_to_print.cliente_recibo_ingreso
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
                                                class="w-[1.3cm] flex items-center text-[10px] invisible ">5)
                                                Por</label>
                                            <div class="text-center w-[3.2cm]  text-[10px] font-bold ">
                                                <p class="ml-1 ">{{ formatedAmount }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="relative flex w-[14.1cm] flex-row">
                                        <label for="" class="w-[2.9cm] flex items-center text-[10px] invisible ">6)
                                            Total
                                            en
                                            Letras</label>
                                        <div class="text-left w-[11cm] text-[10px] font-bold ">
                                            <p class=" ">{{ letras1 }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="h-[0.45cm] w-full flex justify-between items-center">
                                <div class="h-full flex w-full text-left">
                                    <div class="h-full relative flex w-full flex-row">
                                        <div class="text-left w-full  text-[10px] font-bold ">
                                            <p class="">{{ letras2 ? letras2 : '' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="h-[0.5cm] w-full flex justify-between items-center">
                            </div>
                        </td>
                    </tr>
                    <tr class="h-[0.60cm] w-full invisible">
                        <td class="text-center w-[9.15cm] border border-black border-t-0 border-r-0 ">
                            <h2 class="text-[10px] ">7) CARGO EN CAJA</h2>
                        </td>
                        <td class="text-center w-[9.45cm] border border-black border-t-0">
                            <h2 class="text-[10px] ">8) CONCEPTO O MANDAMIENTO DE INGRESO</h2>
                        </td>
                    </tr>
                    <tr class="h-[5.2cm] w-full">
                        <td class="h-full w-[9.15cm] justify-center items-start ">
                            <div class="h-full w-full flex flex-col">
                                <div class="h-[4cm] w-full flex justify-center items-start mx-2">
                                    <div class="flex w-full text-left mt-0">
                                        <div class="w-full font-bold flex items-center text-[10px]">
                                            {{ receipt_to_print.descripcion_recibo_ingreso }}
                                        </div>
                                    </div>
                                </div>

                                <div class="h-[1.2cm] w-full flex justify-center items-start">
                                    <div class="w-full flex flex-col h-full">
                                        <div class="h-[0.5cm] w-max-full flex justify-center mx-4 ">
                                            <div class="ml-1 text-center w-full text-[10px] font-bold">
                                                <p class="ml-1 ">{{ empleado }}</p>
                                            </div>
                                        </div>
                                        <div
                                            class="h-[0.7cm] flex w-max-full justify-center mx-4 border-t border-black border-solid invisible ">
                                            <p class="w-max-full flex items-center text-[11px]">Tesorero,
                                                Pagador o Colector</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="h-full w-[9.45cm] ">
                            <div class="h-full w-full flex flex-col">
                                <div class="h-[0.6cm] w-full flex justify-center items-start ">
                                    <div class="font-bold flex w-full text-left text-[9px] mx-3 mt-0">
                                        {{ receipt_to_print.id_ccta_presupuestal }} {{ nombre_cuenta }}
                                    </div>
                                </div>

                                <div class="h-[4.5cm] w-full flex justify-center items-start">
                                    <div class="w-full flex flex-col h-full">
                                        <div v-for="(detail, index) in receipt_to_print.detalles" :key="index"
                                            class="relative flex w-full min-h-[0.45cm] h-auto flex-row center-vertically">
                                            <div class="font-bold flex items-center text-[9px] w-2/3 mx-3">
                                                <label class="">
                                                    {{ detail.concepto_ingreso.dependencia ?
                                                        detail.concepto_ingreso.dependencia.codigo_dependencia + ' - ' : ''
                                                    }}
                                                    {{
                                                        detail.concepto_ingreso.nombre_concepto_ingreso }}
                                                </label>
                                            </div>
                                            <div class="w-1/3 relative mr-3">
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
                                                    class="font-bold flex items-center text-[9px] w-2/3 mx-3 mt-0 h-full ">
                                                    TOTAL:
                                                </label>
                                                <div class="w-1/3 relative mr-3 h-full">
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
                    <tr class="h-[0.85cm] w-full invisible ">
                        <td class="h-full w-full border-t-0 border-r-0 border border-black">
                        </td>
                        <td class="h-full w-full border-t-0 border border-black">
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="h-[0.7cm] "></div>
        </div>
        <div class="h-full w-[1.55cm]">
            <!-- Contenido del tercer div -->
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
        letras2: {
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