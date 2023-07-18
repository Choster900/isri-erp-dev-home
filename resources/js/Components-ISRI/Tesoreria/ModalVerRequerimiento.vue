<script setup>
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
import ProcessModal from '@/Components-ISRI/AllModal/ProcessModal.vue'
import { computed, ref } from "vue"
import { BarChart } from "vue-chart-3"
import { Chart, BarController, CategoryScale, LinearScale, BarElement } from "chart.js"
Chart.register(BarController, CategoryScale, LinearScale, BarElement)

</script>
<template>
    <ProcessModal maxWidth='4xl' :show="view_req_info" :center="true" @close="$emit('cerrar-modal')">
        <div class="m-1.5 p-2 bg-white max-w-full max-h-full">

            <div class="flex justify-center items-center mt-5 mb-2 w-full">
                <div class="flex border w-full rounded-lg bg-gray-200">
                    <div class="flex items-center justify-center px-4 py-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" />
                        </svg>
                        <div class="ml-4 flex">
                            <h2 class="font-semibold text-lg">Requerimiento {{
                                show_request.numero_requerimiento_pago }}-{{ show_request.anio_requerimiento_pago }}</h2>
                            <div class="flex ml-2 mt-1">
                                <div class="flex">
                                    <p class="text-sm text-gray-600">Monto:</p>
                                    <p class="font-semibold text-gray-900">{{ show_request.monto_requerimiento_pago }}
                                    </p>
                                </div>
                                <div class="ml-4 flex">
                                    <p class="text-sm text-gray-600">Restante:</p>
                                    <p class="font-semibold text-gray-900">{{ show_request.monto_requerimiento_pago
                                        - total_liquido_quedan }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <svg class="h-7 w-7 absolute top-0 right-0 mt-2 cursor-pointer" viewBox="0 0 25 25"
                        @click="$emit('cerrar-modal')">
                        <path fill="currentColor"
                            d="M7.95 6.536l4.242-4.243a1 1 0 111.415 1.414L9.364 7.95l4.243 4.242a1 1 0 11-1.415 1.415L7.95 9.364l-4.243 4.243a1 1 0 01-1.414-1.415L6.536 7.95 2.293 3.707a1 1 0 011.414-1.414L7.95 6.536z" />
                    </svg>
                </div>
            </div>
            <div class="flex flex-col md:flex-row mt-4 md:space-y-0">
                <div class="tabla-modal flex-1 ">
                    <table class="w-full">
                        <thead class="bg-[#001c48] text-white">
                            <tr class="">
                                <th class="rounded-tl-lg w-[10%] py-2 border border-l-white">N°</th>
                                <th class="w-[40%] border border-l-white">PROVEEDOR</th>
                                <th class="w-[10%] border border-l-white">QUEDAN</th>
                                <th class="w-[20%] border border-l-white">RETENCIONES</th>
                                <!-- <th class="w-[10%] border border-l-white">RENTA</th> -->
                                <th class="rounded-tr-lg w-[10%]">LIQUIDO</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm divide-y divide-slate-200">
                            <tr v-for="quedan in show_request.quedan" :key="quedan.id_quedan" class="hover:bg-[#141414]/10">
                                <td class="text-center whitespace-normal">{{ quedan.id_quedan }}</td>
                                <td class="text-center whitespace-normal">{{ quedan.proveedor.razon_social_proveedor }}</td>
                                <td class="text-center whitespace-normal text-blue-600">${{ quedan.monto_total_quedan }}
                                </td>
                                <td class="text-center whitespace-normal ">
                                    <div class="px-1  text-sm flex justify-between">
                                        <span class="text-left">
                                            IVA:
                                        </span>
                                        <span class="text-right text-red-600">
                                            ${{ quedan.monto_iva_quedan }}
                                        </span>

                                    </div>
                                    <div class="px-1  text-sm flex justify-between">
                                        <span class="text-left">
                                            RENTA:
                                        </span>
                                        <span class="text-right text-red-600">
                                            ${{ quedan.monto_isr_quedan }}
                                        </span>
                                    </div>
                                </td>
                                <!--  <td class="text-center whitespace-normal text-red-600">-{{ quedan.monto_isr_quedan }}</td> -->
                                <td class="text-center whitespace-normal text-green-600">${{ quedan.monto_liquido_quedan }}
                                </td>
                            </tr>
                            <tr v-if="show_request.quedan != ''" class="font-bold">
                                <td class="text-center whitespace-normal"></td>
                                <td class="text-center whitespace-normal">Total</td>
                                <td class="text-center whitespace-normal">{{ total_monto_total_quedan }}</td>
                                <td class="text-center whitespace-normal">{{ parseFloat(total_iva_quedan) +
                                    parseFloat(total_isr_quedan) }}</td>
                                <!--  <td class="text-center whitespace-normal">{{ total_isr_quedan }}</td> -->
                                <td class="text-center whitespace-normal">{{ total_liquido_quedan }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="ml-2 border rounded-lg bg-gray-200" style="width: 300px;">
                    <BarChart :chart-data="data" :options="options" css-classes="chart-container" />
                </div>
            </div>
        </div>
    </ProcessModal>
</template>
<script>

export default {
    emits: ['cerrar-modal'],
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
        const data = computed(() => ({

            labels: [``],

            datasets: [
                {
                    label: "MONTO",
                    data: [this.show_request.monto_requerimiento_pago],
                    backgroundColor: ["#38c172 "]
                },
                {
                    label: "RESTANTE",
                    data: [this.show_request.monto_requerimiento_pago - this.total_liquido_quedan],
                    backgroundColor: ["#fbbf24 "]
                },
                {
                    label: "USADO",
                    data: [this.total_liquido_quedan],
                    backgroundColor: ["#6366f1 "]
                },
            ]
        }))
        return {
            data: data,
            // total : this.total_liquido_quedan
            options: {
                /*  layout: {
                     padding: {
                         top: 0
                     }
                 }, */
                plugins: {
                    title: {
                        text: "Gatos del requerimiento"
                    }
                }
            }
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
        total_monto_total_quedan() {
            if (this.show_request == '') {
                return '0.00'
            } else {
                let total = 0
                this.show_request.quedan.forEach((value, index) => {
                    total += parseFloat(value.monto_total_quedan)
                })
                return total.toFixed(2)
            }
        },
    }
}
</script>
