<script setup>
import ModalBasicVue from '@/Components-ISRI/AllModal/ModalBasic.vue';
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
import moment from 'moment';
import axios from 'axios';
</script>
<template>
    <ModalBasicVue title="Requerimiento - N° 1" id="scrollbar-modal" maxWidth="3xl" :modalOpen="scrollbarModalOpen"
        @close-modal-persona="this.$emit('close-definitive')">

        <div class=" px-10 py-5 ">
            <table class="table-auto">
                <thead class="text-xs font-semibold uppercase text-white bg-[#001b47] ">
                    <tr>
                        <th class="px-4 py-2 first:pl-5 last:pr-5 whitespace-nowrap rounded-tl-2xl border-r-2">
                            <div class="font-semibold text-center text-[12px] tracking-wider"> TOTAL:
                                {{ sumTotalDeuda }}
                            </div>
                        </th>
                        <th class="px-4 py-2 first:pl-5 last:pr-5  whitespace-nowrap rounded-tr-2xl">
                            <div class="font-semibold text-center text-[12px] "
                                :class="restanteIngreso < 0 ? 'text-red-600' : ''">
                                RESTANTE: {{ restanteIngreso }}
                            </div>
                        </th>
                    </tr>
                </thead>
                <thead
                    class="text-xs font-semibold uppercase text-white bg-[#001b47] border-t border-b border-slate-200 sticky top-0">
                    <tr>
                        <th class="px-4 py-2 first:pl-5 last:pr-5 whitespace-nowrap">
                            <div class="font-semibold text-center text-[12px] tracking-wider">Q U E D A N</div>
                        </th>

                        <th class="px-4 py-2 first:pl-5 last:pr-5  whitespace-nowrap">
                            <div class="font-semibold text-center text-[12px]">D E S C R I P C I O N </div>
                        </th>
                        <th class="px-4 py-2 first:pl-5 last:pr-5  whitespace-nowrap">
                            <div class="font-semibold text-center text-[12px]">S A L D O</div>
                        </th>
                        <th class="px-4 py-2 first:pl-5 last:pr-5  whitespace-nowrap">
                            <div class="font-semibold text-right text-[12px]">R E S T A N T E</div>
                        </th>

                        <th class="px-4 py-2 first:pl-5 last:pr-5  whitespace-nowrap">
                            <div class="font-semibold text-right text-[12px]">L I Q U I D A C I O N</div>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(data, i ) in  amountRowsData " :key="i">
                        <td class="pb-1 pt-1 
                        focus:bg-white text-xs font-semibold h-12 rounded-md px-3 bg-[#1f355833] bg-opacity-20 text-center border-2 border-white"
                            contenteditable="false">
                            {{ data.id_quedan }}
                        </td>
                        <td class="pb-1 pt-1 text-xs font-semibold w-full rounded-md px-3 bg-[#1f355833] bg-opacity-20 text-center border-2 border-white"
                            contenteditable="true"
                            @input="liquidarMonto(data.id_quedan, $event, 'notifica_liquidacion_quedan')">
                            {{ data.notifica_liquidacion_quedan }}
                        </td>
                        <td class="pb-1 pt-1 focusbg-white text-xs font-semibold w-full  rounded-md px-3 bg-[#1f355833] bg-opacity-20 text-center border-2 border-white"
                            contenteditable="false">
                            {{ data.monto_liquido_quedan }}
                        </td>

                        <td :class="data.restante < 0 ? 'error2' : ''"
                            class="pb-1 pt-1 focus:bg-white text-xs font-semibold w-full rounded-md px-3 bg-[#1f355833] bg-opacity-20 text-center border-2 border-white"
                            contenteditable="false">
                            {{ data.restante }}
                        </td>

                        <td :class="data.restante < 0 ? 'error' : ''"
                            class="pb-1 pt-1 text-xs font-semibold w-full rounded-md px-3 bg-[#1f355833] bg-opacity-20 text-center border-2 border-white"
                            contenteditable="true"
                            @input="liquidarMonto(data.id_quedan, $event, 'monto_liquidacion_quedan')">
                            {{ data.monto_liquidacion_quedan }}

                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="flex justify-center items-center">

                <div class="px-2 pt-3">
                    <GeneralButton @click="enviarTodaLaInformacion()" color="bg-orange-700  hover:bg-orange-800"
                        text="Liquidar" icon="add" />
                </div>

                <div class="px-2 pt-3">
                    <GeneralButton @click="this.$emit('close-definitive')" text="Cancelar" icon="delete" />
                </div>

            </div>

        </div>
    </ModalBasicVue>
</template>
  
<script>

export default {
    props: {
        scrollbarModalOpen: {
            type: Boolean,
            default: false,
        },
        dataQuedan: {
            type: Object,
            default: false,
        },
    },
    data: (props) => ({
        sumTotalDeuda: 0,
        restanteIngreso: 0,
        amountRowsData: [],
    }),

    methods: {

        sumTotQuedan() {
            this.dataQuedan.quedan.forEach(element => {
                this.amountRowsData.push({
                    id_quedan: element.id_quedan,
                    notifica_liquidacion_quedan: element.liquidacion_quedan != null ? element.liquidacion_quedan.notifica_liquidacion_quedan : '',
                    monto_liquido_quedan: element.monto_liquido_quedan,
                    restante: element.liquidacion_quedan != null ? (element.monto_liquido_quedan - element.liquidacion_quedan.monto_liquidacion_quedan).toFixed(2) : element.monto_liquido_quedan,
                    monto_liquidacion_quedan: element.liquidacion_quedan != null ? element.liquidacion_quedan.monto_liquidacion_quedan : 0.00,
                })
                this.sumTotalLiquido()
            });
        },
        liquidarMonto(id_quedan, event, campo) {
            const index = this.amountRowsData.findIndex(quedan => quedan.id_quedan === id_quedan);
            if (index !== -1) {
                this.amountRowsData[index][campo] = event.target.textContent;
                if (campo === "monto_liquidacion_quedan") {
                    this.restByRow(id_quedan, event);
                    this.sumRestTotal()
                }
            }
        },
        restByRow(id_quedan, event) {
            const index = this.amountRowsData.findIndex(row => row.id_quedan === id_quedan);
            if (index !== -1) {
                const { monto_liquido_quedan } = this.amountRowsData[index];
                const restante = (monto_liquido_quedan - event.target.textContent).toFixed(2);
                this.amountRowsData[index] = { ...this.amountRowsData[index], restante };
            }
        },
        sumTotalLiquido() {
            let montoTotal = this.amountRowsData.reduce((total, element) =>
                (parseFloat(total) + parseFloat(element.monto_liquido_quedan)).toFixed(2), 0);
            this.sumTotalDeuda = montoTotal;
        },
        sumRestTotal() {
            let resTOtal = this.amountRowsData.reduce((total, element) =>
                (parseFloat(total) + parseFloat(element.restante)).toFixed(2), 0);
            this.restanteIngreso = resTOtal;
        },

        passOrNot() {
            //validando que todos los datos ingresados sean correctos y ningun valor sea mayor a los solicitado
            let isValid = true;
            this.amountRowsData.forEach(function (quedan) {
                if (quedan.restante < 0) {
                    isValid = false;
                    return;
                }
            });
            return isValid;
        },
        async setMontosLiquidacion() {
            try {//envia la informacion al backend
                if (this.passOrNot()) {
                    let res = await axios.post('/liquidar-quedan', {
                        params: this.amountRowsData
                    })
                    console.log(res);
                    return res; // indicate success
                } else {
                    return false;
                }
            } catch (error) {
                return false; // indicate failure
            }
        },
        async enviarTodaLaInformacion() {
            const confirmed = await this.$swal.fire({
                title: '¿Esta seguro agregar liquidaciones a estos quedan?',
                icon: 'question',
                iconHtml: '❓',
                confirmButtonText: 'Si',
                confirmButtonColor: '#141368',
                cancelButtonText: 'Cancelar',
                showCancelButton: true,
                showCloseButton: true
            });
            if (confirmed.isConfirmed) {

                const successUpdate = await this.setMontosLiquidacion();
                if (successUpdate) {
                    toast.success("Las liquidaciones se han agregado correctamente", {
                        autoClose: 5000,
                        position: "top-right",
                        transition: "zoom",
                        toastBackgroundColor: "white",
                    });
                    this.$emit("actualizar-table-data")

                } else {
                    toast.error("Error, al parecer tiene datos invalidos", {
                        autoClose: 5000,
                        position: "top-right",
                        transition: "zoom",
                        toastBackgroundColor: "white",
                    });
                }
            }
        },
    },
    watch: {
        scrollbarModalOpen: function (newValue, oldValue) {
            if (this.scrollbarModalOpen) {
                this.sumTotQuedan()
                this.sumRestTotal()
            } else {
                this.amountRowsData = []
            }
        }
    },

}
</script>
<style scoped>
td {
    outline: none;
    /* Desactiva el marco de enfoque */
}

.error {
    background-color: rgb(203, 56, 56) !important;
}

.error2 {
    background-color: rgb(191, 141, 15) !important;
}
</style>

