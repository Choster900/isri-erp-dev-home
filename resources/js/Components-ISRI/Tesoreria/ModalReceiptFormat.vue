<template>
    <div class="m-1.5 p-10">
        <div v-if="isLoadingRequest"
            class="fixed top-0 left-0 right-0 bottom-0 flex items-center justify-center bg-gray-800 bg-opacity-50 z-50">
            <div role="status" class="flex items-center">
                <svg aria-hidden="true" class="w-8 h-8 mr-2 text-gray-200 animate-spin dark:text-gray-600 fill-blue-800"
                    viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                        fill="currentColor" />
                    <path
                        d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                        fill="currentFill" />
                </svg>
                <div class="bg-gray-200 rounded-lg p-1 font-semibold">
                    <span class="text-blue-800">CARGANDO...</span>
                </div>
            </div>
        </div>
        <ProcessModal v-else maxWidth='4xl' :show="view_receipt" @close="$emit('cerrar-modal')">
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
                                                {{ receipt_to_print.cuenta_presupuestal ? receipt_to_print.cuenta_presupuestal.codigo_ccta_presupuestal : '' }} {{ nombre_cuenta }}
                                            </div>
                                        </div>

                                        <div class="flex justify-center items-start mb-2 h-[90%]">
                                            <div class="w-full flex flex-col h-full">
                                                <div v-for="(detail, index) in receipt_to_print.detalles" :key="index"
                                                    class="relative flex w-full flex-row center-vertically h-1/11">
                                                    <label for=""
                                                        class="font-bold flex items-center text-[10px] w-2/3 mx-4 mt-0">
                                                        {{ detail.concepto_ingreso.centro_atencion ?
                                                            detail.concepto_ingreso.centro_atencion.codigo_centro_atencion + ' - ' : ''
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
                                                <div
                                                    class="flex justify-center items-start mb-2 h-1/11 mt-1 center-vertically">
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
import { useReciboFormat } from '@/Composables/Tesoreria/ReciboIngreso/useReciboFormat.js';
import moment from 'moment';
import ProcessModal from '@/Components-ISRI/AllModal/ProcessModal.vue'
import { toRefs, onMounted, } from 'vue';

export default {
    components: { ProcessModal },
    props: {
        incomeReceiptId: {
            type: Number,
            default: 0,
        },
        view_receipt: {
            type: Boolean,
            default: false,
        },
    },
    setup(props, context) {

        const { incomeReceiptId } = toRefs(props);

        const {
            isLoadingRequest, receipt_to_print,
            formatedAmount, empleado, nombre_cuenta, fecha_recibo,
            getInfoForModalReciboFormat, printPdf
        } = useReciboFormat(context);

        onMounted(
            async () => {
                await getInfoForModalReciboFormat(incomeReceiptId.value)
            }
        )

        return {
            isLoadingRequest, receipt_to_print,
            formatedAmount, empleado, nombre_cuenta, fecha_recibo,
            getInfoForModalReciboFormat, printPdf, moment
        }
    }
};
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