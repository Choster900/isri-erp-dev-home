<script setup>
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
import moment from 'moment';
import ProcessModal from '@/Components-ISRI/AllModal/ProcessModal.vue'
</script>
<template>
    <div class="m-1.5 p-10">
        <ProcessModal maxWidth='4xl' :show="view_receipt" @close="$emit('cerrar-modal')">
            <!-- Contenedor 1 -->
            <div class="flex justify-between items-center mt-10">
                <!-- Encabezado izquierdo  -->
                <div class="logo-container">
                    <img src="https://via.placeholder.com/150" alt="Logo del Ministerio de tierras" class="logo">
                    <div class="text-center text-base font-bold mx-5">REPUBLICA DE EL SALVADOR</div>
                    <div class="text-center text-base font-bold mx-5">MINISTERIO DE SALUD</div>
                </div>
                <!-- Encabezado centrado  -->
                <div class="title-container">
                    <h1 class="text-2xl font-bold">RECIBO DE INGRESO</h1>
                </div>
                <!-- Encabezado derecho -->
                <div class="logo-container">
                    <img src="https://via.placeholder.com/150" alt="Logo del Instituto de Rios" class="logo">
                    <div class="text-center text-base font-bold mx-5" style="text-align: center;">INSTITUTO SALVADOREÑO DE
                        REHABILITACION INTEGRAL
                    </div>
                </div>
            </div>
            <!-- Contenedor 2 -->
            <div class="flex justify-between items-center mt-5">
                <div class="flex w-2/3 text-left text-lg ml-5">

                </div>
                <div class="flex w-1/3 text-left text-lg mr-5">
                    <div class="relative flex w-full flex-row">
                        <label for="" class="flex items-center text-[14px] text-sm">Numero</label>
                        <input type="text" readonly v-model="receipt_to_print.numero_recibo_ingreso"
                            class="font-bold text-center w-full border-b border-black-600 border-opacity-50 border-solid border-0 py-0 text-sm text-red-500">
                    </div>
                </div>
            </div>
            <!-- Contenedor 3 -->
            <div class="flex justify-between items-center mt-5 mb-2">
                <div class="flex text-left w-2/3 ml-5">
                    <div class="relative flex w-full flex-row">
                        <label for="" class="flex items-center text-[14px] text-sm">Unidad Financiera Institucional
                            (UFI-ISRI)</label>
                    </div>
                </div>
                <div class="flex w-1/3 text-left text-lg mr-5">
                    <div class="relative flex w-full flex-row">
                        <label for="" class="flex items-center text-[14px] text-sm">Fecha</label>
                        <input type="text" readonly v-model="receipt_to_print.fecha_recibo_ingreso"
                            class="font-bold text-center w-full border-b border-black-600 border-opacity-50 border-solid border-0 py-0 text-sm">
                    </div>
                </div>
            </div>

            <div class="flex justify-between items-center mt-5 mb-5">
                <table class="table-auto w-full mx-5">
                    <tbody>
                        <tr>
                            <td class="h-auto border border-black" colspan="2">
                                <!-- First row, first container -->
                                <div class="flex justify-between items-center mt-1 mb-2">
                                    <div class="flex w-3/4 text-left text-lg ml-2">
                                        <div class="relative flex w-full flex-row">
                                            <label for="" class="flex items-center text-[14px] w-max-1/3">Nombre o Razón
                                                Social</label>
                                            <input type="text" readonly v-model="receipt_to_print.cliente_recibo_ingreso"
                                                class="font-bold text-left border-b border-black-600 border-opacity-50 border-solid border-0 py-0 text-sm"
                                                style="width:460px;">
                                        </div>
                                    </div>
                                    <div class="flex w-1/4 text-left text-lg mr-2">
                                        <div class="relative flex w-full flex-row">
                                            <label for="" class="flex items-center text-[14px]">Por</label>
                                            <input type="text" readonly v-model="formatedAmount"
                                                class="font-bold text-center w-full border-b border-black-600 border-opacity-50 border-solid border-0 py-0 text-sm">
                                        </div>
                                    </div>
                                </div>
                                <!-- First row, second container -->
                                <div class="flex justify-between items-center mt-1 mb-2">
                                    <div class="flex w-full text-left text-lg ml-2">
                                        <div class="relative flex w-full flex-row">
                                            <label for="" class="flex items-center text-[14px] w-max-1/3">Total en
                                                Letras</label>
                                            <input type="text" readonly
                                                class="font-bold text-left border-b border-black-600 border-opacity-50 border-solid border-0 py-0 text-sm"
                                                style="width:735px;">
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr class="h-auto">
                            <td class="text-center w-1/2 border border-black">
                                <h2 class="text-[14px] text-sm">CARGO EN CAJA</h2>
                            </td>
                            <td class="text-center w-1/2 border border-black">
                                <h2 class=" text-[14px] text-sm">CONCEPTO O MANDAMIENTO DE DE INGRESO</h2>
                            </td>
                        </tr>
                        <tr class="h-auto">
                            <td class="justify-center items-start border-black border-l border-b">
                                <div class="h-52 flex flex-col">
                                    <div class="flex justify-center items-start mb-2 h-3/4">
                                        <div class="flex w-full text-left mx-4 mt-2">
                                            <div class="flex items-center text-[14px] text-sm">
                                                {{ receipt_to_print.descripcion_recibo_ingreso }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex justify-center items-start mb-2 h-1/4">
                                        <div class="w-full flex flex-col h-auto">
                                            <div
                                                class="flex w-max-full justify-center text-lg mx-4 h-1/2 border-t border-black border-solid">
                                                <p class="w-max-full flex items-center text-[14px] text-sm">Tesorero,
                                                    Pagador o Colector</p>
                                            </div>
                                            <div class="w-max-full flex justify-center text-lg mx-4 h-1/2">
                                                <input type="text" readonly v-model="empleado"
                                                    class="w-full font-bold text-center border-0 py-0 text-sm">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="border border-black">
                                <div class="h-52 flex flex-col">
                                    <div class="flex justify-center items-start mb-2 h-1/4">
                                        <div class="flex w-full text-left text-[14px] text-sm mx-4 mt-2">
                                            {{ receipt_to_print.id_ccta_presupuestal }} {{ nombre_cuenta }}
                                        </div>
                                    </div>

                                    <div class="flex justify-center items-start mb-2 h-3/4">
                                        <div class="w-full flex flex-col h-auto">
                                            <div v-for="(detail, index) in receipt_to_print.detalles" :key="index"
                                                class="relative flex w-full flex-row">
                                                <label for="" class="flex items-center text-[14px] w-2/3 mx-4 mt-1">
                                                    {{ detail.concepto_ingreso.nombre_concepto_ingreso }}
                                                </label>
                                                <div class="w-1/3 relative">
                                                    <span
                                                        class="absolute left-0 top-1/2 transform -translate-y-1/2 font-bold">$</span>
                                                    <input type="text" readonly
                                                    v-model="detail.monto_det_recibo_ingreso"
                                                        class="w-full font-bold text-right border-0 py-0 text-sm pl-8">
                                                </div>
                                                <!-- <input type="text" readonly v-model="detail.monto_det_recibo_ingreso"
                                                    class="w-1/3 font-bold text-right border-0 py-0 text-sm"> -->
                                            </div>
                                            <div class="flex justify-center items-start mb-2 h-1/4 mt-2">
                                                <div class="relative flex w-full flex-row">
                                                    <label for="" class="flex items-center text-[14px] w-2/3 mx-4 mt-2">
                                                        TOTAL:
                                                    </label>
                                                    <div class="w-1/3 relative">
                                                        <span
                                                            class="absolute left-0 top-1/2 transform -translate-y-1/2 font-bold">$</span>
                                                        <input type="text" readonly
                                                            v-model="receipt_to_print.monto_recibo_ingreso"
                                                            class="w-full font-bold text-right border-0 border-t py-0 text-sm pl-8">
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


        </ProcessModal>
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
    },
    data: function () {
        return {

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
    }
}
</script>

<style>
/* .container {
    display: flex;
    justify-content: space-between;
    align-items: center;
} */

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
    width: 100px;
    height: 100px;
    object-fit: cover;
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