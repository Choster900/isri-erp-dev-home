<script setup>
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
import InputError from "@/Components/InputError.vue";
import moment from 'moment';
import ProcessModal from '@/Components-ISRI/AllModal/ProcessModal.vue'
</script>
<template>
    <div class="m-1.5">
        <ProcessModal maxWidth='6xl' :show="showModal" @close="$emit('cerrar-modal')">

            <div class="">
                <div class="text-center">
                    <div class=" justify-content-center"
                        style="font-size: 10px;--bs-gutter-x: 0rem; padding-bottom: 1.3em;">
                        <span style="font-size: 9pt; color: black;">Estado</span>
                    </div>
                    <div class="border-2 border-black mx-8 mb-7" id="main-container">
                        <div class="mb-7 md:flex flex-row justify-between">
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/4 pt-8 px-16">
                            </div>
                            <div class="mb-4 md:mx-10 md:mb-0 basis-1/2 pt-7 ">
                                <div class="border-2 border-black ">
                                    <h3 style class="text-2xl px-10 py-1.5 font-semibold text-gray-600">Titulo del documento
                                        {{ IdQuedan }}
                                    </h3>
                                </div>
                            </div>
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/4 pt-8 pl-10">

                            </div>
                        </div>

                        <div class="mx-20 mb-3 encabezado">
                            <div class="mb-7 md:flex flex-row justify-items-center">
                                <div class="mb-4 md:mr-2 md:mb-0  w-100">
                                    <div class="relative flex  w-full flex-row">
                                        <label for="" class="flex items-center  text-[14px] text-sm">Porcentaje de
                                            ISR:</label>
                                        <input type="text" style="width: 350px;"
                                            value="113-CULTIVO DE CEREALES EXCEPTO ARROZ Y PARA FORRAJES"
                                            class="placeholder-slate-400 text-sm py-0 transition-colors duration-300 focus:border-[#001b47] focus:outline-none">
                                    </div>
                                </div>
                                <div class="mb-4 md:mr-2 md:mb-0  w-25">
                                    <div class="relative flex  w-full flex-row">
                                        <label for="" class="flex items-center  text-[14px]">Porcentaje de IVA: </label>
                                        <input type="text" style="width: 75px;" value="13%"
                                            class="placeholder-slate-400 text-sm text-center py-0 transition-colors duration-300 focus:border-[#001b47] focus:outline-none">
                                    </div>
                                </div>
                                <div class="mb-4 md:mr-2 md:mb-0  w-25">
                                    <div class="relative flex  w-full flex-row">
                                        <label for="" class="flex items-center  text-[14px] text-sm">Porcentaje de ISR:
                                        </label>
                                        <input type="text" style="width: 75px;" value="2.5%"
                                            class="placeholder-slate-400 text-sm py-0 text-center  transition-colors duration-300 focus:border-[#001b47] focus:outline-none">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="pl-20 mb-7 encabezado">
                            <div class="pl-48 mb-7 md:flex flex-row ">
                                <div class="mb-4 md:mr-2 md:mb-0  w-40 h-5">
                                    <div class="relative flex  w-full flex-row">
                                        <label for="" class="flex items-center text-[14px]">Cheque $: </label>
                                        <input type="text" style="width: 75px;" value="15000"
                                            class="placeholder-slate-400 text-sm text-center py-0 transition-colors duration-300 focus:border-[#001b47] focus:outline-none">
                                    </div>
                                </div>

                                <div class="mb-4 md:mr-2 md:mb-0 w-25 h-5">
                                    <div class="relative flex  w-full flex-row">
                                        <label for="" class="flex items-center  text-[14px] text-sm">Renta $:</label>
                                        <input type="text" style="width: 75px;" value="100"
                                            class="placeholder-slate-400 text-sm py-0 text-center  transition-colors duration-300 focus:border-[#001b47] focus:outline-none">
                                    </div>
                                </div>

                                <div class="mb-4 md:mr-2 md:mb-0 w-25 h-5">
                                    <div class="relative flex  w-full flex-row">
                                        <label for="" class="flex items-center  text-[14px]">IVA $: </label>
                                        <input type="text" style="width: 75px;" value="0.99"
                                            class="placeholder-slate-400 text-sm text-center py-0 transition-colors duration-300 focus:border-[#001b47] focus:outline-none">
                                    </div>
                                </div>

                                <div class="mb-4 md:mr-2 md:mb-0 w-25 h-5">
                                    <div class="relative flex  w-full flex-row">
                                        <label for="" class="flex items-center  text-[14px] text-sm">Total $:
                                        </label>
                                        <input type="text" style="width: 80px;" value="1250.30"
                                            class="placeholder-slate-400 text-sm py-0 text-center font-bold transition-colors duration-300 focus:border-[#001b47] focus:outline-none">
                                    </div>
                                </div>

                            </div>
                        </div>


                        <div class="overflow-x-auto">
                            <table class="ml-7 mb- " id="main-table">
                                <thead>
                                    <tr>
                                        <th class="border-2 border-black w- text-sm text-gray-600 ">Factura</th>
                                        <th class="border-2 border-black w-60 text-sm px-8 text-gray-600" colspan="2">
                                            Dependencia</th>
                                        <th class="border-2 border-black W-60 text-sm px-5 text-gray-600">Tipo Prestación
                                        </th>
                                        <th class="border-2 border-black W-60 text-sm px-8 text-gray-600">Tipo de Contratacion
                                        </th>
                                        <th class="border-2 border-black W-60 text-sm px-10 text-gray-600">Número de Contratación
                                        </th>
                                        <th class="border-2 border-black W-60 text-sm px-10 text-gray-600">Descripcin
                                            Factura</th>
                                        <th class="border-2 border-black W-60 text-sm px-10 text-gray-600">Monto</th>
                                        <th class="W-60 items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" title="Agregar Fila" @click="addRow()"
                                                class="icon icon-tabler icon-tabler-square-plus cursor-pointer" width="27"
                                                height="27" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2c3e50"
                                                fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <rect x="4" y="4" width="16" height="16" rx="2" />
                                                <line x1="9" y1="12" x2="15" y2="12" />
                                                <line x1="12" y1="9" x2="12" y2="15" />
                                        </svg>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="text-sm" id="content">

                                <!-- <tr @dblclick="deleteRow(id_fila)" v-for="(data, i) in detalleQuedan" :key="i">
                                        <td class="border-2 border-black py-14" contenteditable="true"
                                            field-name="numeroFactura" :id="data.id_quedan"
                                            @input="procesingData($event, 'numero_factura_det_quedan', data.id_det_quedan)">
                                            {{
                                                data.numero_factura_det_quedan }}</td>

                                        <td class="border-2 border-black" colspan="2">
                                            <div class="relative  flex h-8 w-full flex-row-reverse ">
                                                <Multiselect 
                                                    @select="procesingSelect($event, 'id_dependencia', data.id_det_quedan)"
                                                    :options="dependencias" :searchable="true" ref="Select" />
                                            </div>
                                        </td>

                                        <td class="border-2 border-black">
                                            <div class="grid grid-cols-2 gap-2 ml-3">
                                                <div class="text-end">Producto</div>
                                                <div class="px-0">
                                                    <RadioButton modelValue="1" :name="id_fila" fieldName="tipoPrestaciones"
                                                        @update:modelValue="procesingInput(1, 'id_tipo_prestacion', data.id_det_quedan)"
                                                        ref="id_tipo_prestacion" />
                                                </div>
                                                <div>Servicio</div>
                                                <div>
                                                    <RadioButton modelValue="2" :name="id_fila" fieldName="tipoPrestaciones"
                                                        @update:modelValue="procesingInput(2, 'id_tipo_prestacion', data.id_det_quedan)"
                                                        ref="id_tipo_prestacion" />
                                                </div>
                                            </div>
                                        </td>
                                        <td class="border-2 border-black ...">
                                            <div class="relative flex h-8 w-full flex-row-reverse ">
                                                <Multiselect class="text-center"
                                                    @select="procesingSelect($event, 'id_acuerdo_compra', data.id_det_quedan)"
                                                    :options="acuerdoCompras" :searchable="true" ref="Select" />
                                            </div>
                                        </td>

                                        <td class="border-2 border-black px-1 text-[12px]" field-name="RespaldoQuedan"
                                            @input="procesingData($event, 'numero_acuerdo_det_quedan', data.id_det_quedan)"
                                            contenteditable="true">{{ data.numero_acuerdo_det_quedan }}
                                        </td>

                                        <td class="border-2 border-black" contenteditable="true"
                                            field-name="DescripcionFactura"
                                            @input="procesingData($event, 'descripcion_det_quedan', data.id_det_quedan)">
                                            {{ data.descripcion_det_quedan }}
                                        </td>

                                                                                                        <td class="border-2 border-black px-1" field-name="Monto"
                                                                                                            @input="procesingData($event, 'total_factura_det_quedan', data.id_det_quedan)"
                                                                                                            contenteditable="true">{{ data.total_factura_det_quedan }}
                                                                                                        </td>
                                                                                                    </tr>
                                                                 -->
                                </tbody>
                                <tbody>
                                    <tr id="esconder" class="border-none">
                                        <td contenteditable="false" class="py-3 border-none"></td>
                                        <td colspan="4" contenteditable="false"></td>
                                    </tr>
                                    <tr>
                                        <td class="border-2 border-black py-4" colspan="2" contenteditable="false">
                                            Descripción
                                        </td>
                                        <td class="border-2 border-black" colspan="6" contenteditable="true">

                                        </td>
                                    </tr>

                                </tbody>
                            </table>

                            <div class="row footer mt-4 mb-5">
                                <div class="text-center">
                                    <input type="text" class="border-2 border-solid border-gray-400 text-center w-72"
                                        value="Nombre completo del tesorero" disabled>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </ProcessModal>
    </div>
</template>

<script>
import { createApp } from 'vue'
import RowsTableVue from './RowsTable.vue';
export default {

    props: {
        showModal: {//prop muestra estado del modal para abrir y cerrar
            type: Boolean,
            default: false,
        },
        IdQuedan: {
            type: Number,
            required: true,
        },
    },
    data: function () {
        return {
            detalleQuedan: [],
            dependencias: [],
            acuerdoCompras: [],
            optionSelect: {
                dependencia: '',
                acuerdoCompra: '',
            }
        }
    },
    components: {
        RowsTableVue
    },
    methods: {
        eliminarFila() {
            alert("")
        },

        async addDetalleQuedan() {
            try {
                const response = await axios.post('/add-detalle-quedan', { id_quedan: this.IdQuedan });
                return response.data.id_det_quedan;
            } catch (error) {
                console.error(error);
                throw error;
            }
        },

        async addRow() {

            const id_det_quedan = await this.addDetalleQuedan()
            const tbody = document.querySelector('#content');
            const rowComponent = createApp(RowsTableVue, {
                allDataQuedan: {
                    "id_det_quedan": id_det_quedan,
                    "id_quedan": this.IdQuedan,
                }
            }).mount(document.createElement('div'));
            tbody.appendChild(rowComponent.$el);
        },

        async getRows(data) {

            console.log(data.detalle_quedan);

            for (let i = 0; i < data.detalle_quedan.length; i++) {
                const tbody = document.querySelector('#content');
                const rowComponent = createApp(RowsTableVue, {

                    allDataQuedan: data.detalle_quedan[i]
                }).mount(document.createElement('div'));
                await tbody.appendChild(rowComponent.$el);
            }
        },

        async getQuedanById(idQuedan) {
            await axios.get('/get-quedan', { params: { id_quedan: idQuedan } }).then((response) => {
                //this.detalleQuedan = response.data.detalle_quedan
                this.getRows(response.data)
            }).catch((error) => {
                console.log(error);
            });
        },
        procesingData(event, campo, fila) {
            const value = event.target.textContent;
            const tdName = event.target.getAttribute('field-name');

            const data = {
                campos: campo,
                value: value,
                id_det_quedan: fila,
                id_quedan: this.IdQuedan,
            }

            axios.post('/update-detalle-quedan', data).then((response) => {
                console.log(response);
            }).catch((error) => {
                console.log(error);
            });

        },
        procesingInput(event, campo, fila) {

            const data = {
                campos: campo,
                value: event,
                id_det_quedan: fila,
                id_quedan: this.IdQuedan,
            }

            console.log(data);
            axios.post('/update-detalle-quedan', data).then((response) => {
                console.log(response);
            }).catch((error) => {
                console.log(error);
            });

        },
        procesingSelect(event, nombre, fila) {
            const data = {
                campos: nombre,
                value: event,
                id_det_quedan: fila,
                id_quedan: this.IdQuedan,
            }
            axios.post('/update-detalle-quedan', data).then((response) => {
                console.log(response);
            }).catch((error) => {
                console.log(error);
            });
        },
        getListForSelect() {
            axios.get('/get-list-select').then((response) => {
                this.dependencias = response.data.dependencias;
                this.acuerdoCompras = response.data.acuerdoCompras;
            }).catch((error) => {
                console.log(error);
            });
        }
    },
    watch: {
        showModal: function (value, oldvalue) {
            if (value) {
                this.getQuedanById(this.IdQuedan)
            }
        },
    },
    created() {
        this.getListForSelect()
    }

}
</script>

<style>
.encabezado input {
    border: none;
    border-bottom: 2px solid black;
}

.encabezado input[type="date"] {
    width: 50%;
}

.encabezado input[type="text"] {
    width: 60%;
}

.encabezado .row:nth-child(2) .col-6:nth-child(1) {
    margin-left: 60px;
}

.encabezado .row:nth-child(2) .col-6:nth-child(1) input {
    width: 80%;
}

.encabezado .row:nth-child(2) .col-2:nth-child(2) {
    margin-left: -25px;
}

.encabezado .row:nth-child(2) .col-6:nth-child(2) input {
    width: 20%;
}

#esconder {
    height: 30px;
}

#esconder td {
    border: none;
}
</style>