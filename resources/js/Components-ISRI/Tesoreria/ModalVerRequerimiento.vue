<script setup>
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
import moment from 'moment';
import ProcessModal from '@/Components-ISRI/AllModal/ProcessModal.vue'
import { jsPDF } from "jspdf";
import html2pdf from 'html2pdf.js'
</script>
<template>
    <ProcessModal maxWidth='4xl' :show="view_req_info" :center="true" @close="$emit('cerrar-modal')"
        :show_request="show_request">
        <div class="m-1.5 p-2 bg-white max-w-full max-h-full">
            <div class="flex justify-center items-center mt-5">
                <h2 class="font-bold">Requerimiento {{ show_request.numero_requerimiento_pago }}-{{
                    show_request.anio_requerimiento_pago }}</h2>
            </div>
            <div class="tabla-modal mt-4">
                <table class="w-full" id="tabla_modal_validacion_arranque">
                    <thead class="bg-[#1F3558] text-white">
                        <tr class="">
                            <th class="rounded-tl-lg w-10">N° QUEDAN</th>
                            <th class="w-60">PROVEEDOR</th>
                            <th class="w-10">IVA</th>
                            <th class="w-10">RENTA</th>
                            <th class="rounded-tr-lg w-10">MONTO</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm divide-y divide-slate-200">
                        <tr v-for="quedan in show_request.quedan" :key="quedan.id_quedan" class="hover:bg-[#141414]/10">
                            <td class="text-center whitespace-normal">{{ quedan.id_quedan }}</td>
                            <td class="text-center whitespace-normal">{{ quedan.proveedor.razon_social_proveedor }}</td>
                            <td class="text-center whitespace-normal text-red-600">{{ quedan.monto_iva_quedan }}</td>
                            <td class="text-center whitespace-normal text-red-600">{{ quedan.monto_isr_quedan }}</td>
                            <td class="text-center whitespace-normal text-green-600">{{ quedan.monto_liquido_quedan }}</td>
                        </tr>
                        <tr v-if="show_request.quedan != ''" class="font-bold">
                            <td class="text-center whitespace-normal"></td>
                            <td class="text-center whitespace-normal">Total</td>
                            <td class="text-center whitespace-normal">{{ total_iva_quedan }}</td>
                            <td class="text-center whitespace-normal">{{ total_isr_quedan }}</td>
                            <td class="text-center whitespace-normal">{{ total_liquido_quedan }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div v-if="show_request.quedan == ''"
                class="w-full flex justify-between items-center mt-5 rounded-md font-bold">
                <div class="flex w-full justify-center text-left text-lg">
                    <p>Sin Quedan Asignados</p>
                </div>
            </div>

        </div>
    </ProcessModal>
</template>
<script>
export default {
    props: {
        view_req_info: {
            type: Boolean,
            default: false,
        },
        show_request: {
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
        total_liquido_quedan() {
            if (this.show_request == '') {
                return '0.00'
            } else {
                let total = 0
                this.show_request.quedan.forEach((value, index) => {
                    total += parseFloat(value.monto_liquido_quedan)
                })
                return total.toFixed(2)
            }
        },
        total_iva_quedan() {
            if (this.show_request == '') {
                return '0.00'
            } else {
                let total = 0
                this.show_request.quedan.forEach((value, index) => {
                    total += parseFloat(value.monto_iva_quedan)
                })
                return total.toFixed(2)
            }
        },
        total_isr_quedan() {
            if (this.show_request == '') {
                return '0.00'
            } else {
                let total = 0
                this.show_request.quedan.forEach((value, index) => {
                    total += parseFloat(value.monto_isr_quedan)
                })
                return total.toFixed(2)
            }
        },
    }
}
</script>
