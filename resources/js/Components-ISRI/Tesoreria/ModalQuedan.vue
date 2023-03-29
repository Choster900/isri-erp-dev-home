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
                            <table class="ml-20 mb-" id="main-table">
                                <thead>
                                    <tr>
                                        <th class="border-2 border-black  text-sm text-gray-600 ">Factura</th>
                                        <th class="border-2 border-black  text-sm px-5 text-gray-600" colspan="2">
                                            Dependencia</th>
                                        <th class="border-2 border-black W-60 text-sm px-5 text-gray-600">Tipo Prestación
                                        </th>
                                        <th class="border-2 border-black W-60 text-sm px-8 text-gray-600">Numero Acta
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

                                    <tr v-for="(row, rowIndex) in rowsData" :key="rowIndex" @dblclick="deleteRow(row[0])">

                                        <template v-for="(cell, cellIndex) in row" :key="cellIndex">


                                            <template v-if="cellIndex == 1">
                                                <td @input="onCellEdit(rowIndex, cellIndex, $event.target.innerText)"
                                                    class="border-2 border-black py-14" contenteditable="true">
                                                </td>
                                            </template>


                                            <template v-else-if="cellIndex == 2">
                                                <td class="border-2 border-black" colspan="2">
                                                    <div class="relative flex h-8 w-full flex-row-reverse ">
                                                        <Multiselect :options="opcionesSelect2" :searchable="true" />
                                                    </div>
                                                </td>
                                            </template>
                                            <template v-else-if="cellIndex == 3">

                                                <td class="border-2 border-black">
                                                    <div class="grid grid-cols-2 gap-2 ml-3">
                                                        <div class="text-end">Producto</div>
                                                        <div class="px-0">
                                                            <RadioButton modelValue="1" :name="cellIndex"
                                                                ref="id_tipo_prestacion" />
                                                        </div>
                                                        <div class="text-end ">Servicio</div>
                                                        <div>
                                                            <RadioButton modelValue="2" :name="cellIndex"
                                                                ref="id_tipo_prestacion" />
                                                        </div>
                                                    </div>
                                                </td>
                                            </template>

                                            <template v-else-if="cellIndex == 4">

                                                <td class="border-2 border-black"
                                                    @input="onCellEdit(rowIndex, cellIndex, $event.target.innerText)"
                                                    contenteditable="true"></td>

                                            </template>

                                            <template v-else-if="cellIndex == 5">
                                                <td @input="onCellEdit(rowIndex, cellIndex, $event.target.innerText)"
                                                    class="border-2 border-black px-1 " contenteditable="true">
                                                    {{ cell }}
                                                </td>

                                            </template>

                                            <template v-else-if="cellIndex == 6">
                                                <td @input="onCellEdit(rowIndex, cellIndex, $event.target.innerText)"
                                                    class="border-2 border-black px-1 " contenteditable="true">

                                                </td>

                                            </template>

                                            <template v-else-if="cellIndex == 7">

                                                <td @input="onCellEdit(rowIndex, cellIndex, $event.target.innerText)"
                                                    class="border-2 border-black px-1 " contenteditable="true">

                                                </td>

                                            </template>



                                        </template>

                                    </tr>

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
                                        <td class="border-2 border-black" colspan="5" contenteditable="true">

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

export default {

    props: {
        showModal: {//prop muestra estado del modal para abrir y cerrar
            type: Boolean,
            default: false,
        },
    },
    data: function () {
        return {
            rowsData:
                [
                    {
                        0: 1,//numberRow,
                        1: '',//factura,
                        2: '',//dependencia,
                        3: '',//Tipo prestacion,
                        4: '',//Numero Acta,
                        6: '',//DescripcionFactura,
                        7: '',//Monto,
                    },
                ],

            opcionesSelect2: [
                { value: 'batman', label: 'Batman' },
                { value: 'robin', label: 'Robin', disabled: true },
                { value: 'joker', label: 'Joker' },
            ],
        }
    },

    methods: {
        onCellEdit(rowIndex, cellIndex, value) {
            this.rowsData[rowIndex][cellIndex] = value;
            console.log(this.rowsData[rowIndex]);
        },
        addRow() {
            let flag = this.rowsData.length;
            console.log(this.rowsData[flag - 1][0]);
            this.rowsData.push({
                0: (this.rowsData[flag - 1][0] + 1),//numberRow,
                1: '',//factura,
                2: '',//dependencia,
                3: '',//Tipo prestacion,
                4: '',//Numero Acta,
                6: '',//DescripcionFactura,
                7: '',//Monto,

            })
        },
        deleteRow(rowIndex) {
            alert(rowIndex)
            for (let i = 0; i < this.rowsData.length; i++) {
                if (this.rowsData[i][0] === rowIndex) {
                    this.rowsData.splice(i, 1);
                    break;   // Salimos del bucle una vez que eliminamos el objeto
                }
            }

            let data = {}
            for (let i = 0; i < this.rowsData.length; i++) {
                data.push(this.rowsData)
            }

            this.rowsData = []
            this.rowsData.push(data)

        }
    },


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