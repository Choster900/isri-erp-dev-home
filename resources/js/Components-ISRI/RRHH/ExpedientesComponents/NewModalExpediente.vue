<template>
    <div class="m-1.5">
        <ProcessModal addClases=" bg-[#F9F9F9]" :show="showModal" @close="$emit('cerrar-modal')" maxWidth="5xl">
            <div class="p-10">
                <div class="flex flex-col md:flex-row">
                    <div
                        class="bg-white rounded-md shadow-sm border w-full md:w-1/4 md:h-[600px] h-auto max-h-[600px] overflow-y-auto">
                        <ul class="pt-10">
                            <li class="font-light px-6 py-2 text-gray-600">MANAGER</li>
                            <li class=" font-light flex items-center px-6 py-4 relative cursor-pointer hover:bg-[#8E9CB7]/10"
                                v-for="(item, index) in jsonData" :key="index"
                                :class="index == 0 ? 'bg-[#E8E8E8]' : ''">
                                <div class="text-sm  font-medium">{{ item.nombre }}</div>
                                <div class="absolute left-0 top-0 bottom-0 w-1 bg-[#8E9CB7]" v-if="index == 0"></div>
                            </li>
                        </ul>
                    </div>


                    <div class="  w-full md:w-3/4 px-2 " v-if="stateView">
                        <div class="flex justify-between items-center">
                            <h2 class="text-xl font-semibold">Upload file</h2>
                        </div>

                        <div class="flex py-4 gap-2">
                            <span class="text-xs font-medium block text-slate-500/70">Adjunta un archivo</span>
                            <span class="text-xs font-medium block pb-3">Selecciona el archivo o arrástralo aquí</span>
                        </div>

                        <div class="flex gap-3">

                            <div
                                class="h-52 w-2/3 border-[3px] cursor-pointer border-dashed border-slate-400 rounded-lg flex flex-col items-center justify-center text-center bg-slate-200 hover:bg-slate-300">
                                <img src="../../../../img/Upload-pana.svg" class="w-36 h-24 md:w-64 md:h-64 -mt-10"
                                    alt="Icono de cargar archivo">
                                <input type="file" ref="fileInput" accept=".pdf,.jpeg,.jpg,.png" style="display: none;">
                            </div>
                            <div class="w-1/2 flex flex-col">

                                <div class="mb-4 md:mr-2 md:mb-0 basis-1/2 pb-5">
                                    <label class="block mb-1 text-[13px] font-medium text-gray-600">Busqueda de usuario
                                        <span class="text-red-600 font-extrabold">*</span></label>
                                    <div class="relative flex h-8 w-full flex-row-reverse">
                                        <Multiselect :filter-results="false" :resolve-on-load="false" :delay="1000"
                                            :searchable="true" :clear-on-search="true" :min-chars="5" :options="[]"
                                            placeholder="Busqueda de usuario..." :classes="{
            placeholder: 'flex items-center text-center h-full absolute left-0 top-0 pointer-events-none bg-transparent leading-snug pl-3.5 text-gray-400 rtl:left-auto rtl:right-0 rtl:pl-0 rtl:pr-3.5',
        }" noOptionsText="<p class='text-xs'>Sin Personas<p>"
                                            noResultsText="<p class='text-xs'>Sin resultados de personas <p>" />

                                    </div>
                                </div>
                                <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                                    <label class="block mb-1 text-[13px] font-medium text-gray-600">Seleccione tipo de
                                        anexo
                                        <span class="text-red-600 font-extrabold">*</span></label>
                                    <div class="relative flex h-8 w-full flex-row-reverse">
                                        <Multiselect placeholder="Seleccione el tipo de anexo..." :classes="{
            containerDisabled: 'cursor-not-allowed bg-gray-200 ',
            placeholder: 'flex items-center text-center h-full absolute left-0 top-0 pointer-events-none bg-transparent leading-snug pl-3.5 text-gray-400 rtl:left-auto rtl:right-0 rtl:pl-0 rtl:pr-3.5',
        }" noOptionsText="<p class='text-xs'>sin contratos<p>"
                                            noResultsText="<p class='text-xs'>contrato no encontrados<p>" :options="[]"
                                            :searchable="true" />

                                    </div>
                                </div>

                                <button
                                    class="w-full text-sm bg-gray-200 text-gray-700 rounded-lg px-4 py-2 hover:bg-gray-300 items-center">Remover
                                    archivo <span class="font-bold text-red-600">-</span></button>

                            </div>
                        </div>

                        <div class="mt-4 flex justify-between items-center bg-gray-50  rounded-lg">
                            <textarea id="description" rows="4"
                                class="block p-2.5 w-full text-xs text-gray-900 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Descripción del archivo..."></textarea>
                        </div>

                        <div class="mt-4 flex justify-between items-center space-x-2">
                            <button @click="stateView = 0"
                                class="w-full text-sm bg-gray-200 text-gray-700 rounded-lg px-4 py-2 hover:bg-gray-300">Cancel</button>
                            <button
                                class="w-full  text-sm bg-blue-500 text-white rounded-lg px-4 py-2 hover:bg-blue-600">Guardar
                                y salir</button>
                        </div>
                    </div>



                    <div class="w-full md:w-3/4 mt-4 md:mt-0 px-4" v-if="!stateView">
                        <h1 class="font-medium text-lg">File Manager</h1>

                        <div class="flex justify-between border-b pb-2">
                            <div class="flex">
                                <div class="pr-5 text-start">Last</div>
                                <div class="px-5 border-b-4 border-slate-700">All</div>
                            </div>

                            <div class="flex items-center space-x-2">
                                <div>
                                    <svg class="size-6" viewBox="0 0 24 24" fill="none">
                                        <rect x="4" y="4" width="7" height="7" rx="1" fill="#000000"></rect>
                                        <rect x="4" y="13" width="7" height="7" rx="1" fill="#000000"></rect>
                                        <rect x="13" y="4" width="7" height="7" rx="1" fill="#000000"></rect>
                                        <rect x="13" y="13" width="7" height="7" rx="1" fill="#000000"></rect>
                                    </svg>
                                </div>
                                <div>
                                    <svg class="size-4" viewBox="0 0 15 15" fill="none" stroke="#000000"
                                        stroke-width="0.81">
                                        <path
                                            d="M14 12.85H1v1.3h13v-1.3zM14 8.85H1v1.3h13v-1.3zM1 4.85h13v1.3H1v-1.3zM14 .85H1v1.3h13V.85z"
                                            fill="#000000"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 gap-4 mt-4">

                            <div @click="stateView = 1"
                                class="bg-slate-700 border rounded-lg shadow-md p-4 h-60 flex flex-col justify-center items-center cursor-pointer">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-10 text-white">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                </svg>

                                <span class="text-white  text-center">Agregar  Archivo Anexo</span>
                            </div>


                            <div class="bg-white rounded-lg shadow cursor-pointer flex flex-col justify-between hover:shadow-lg hover:-translate-y-1 transition-transform duration-300"
                                v-for="(item, index) in jsonFileData" :key="index">
                                <div class="flex justify-between items-start">
                                    <div class="text-red-500 text-2xl w-full">
                                        <iframe v-if="item.tipoArchivo == 'pdf'" class="w-full rounded-lg"
                                            :src="item.path" alt=""></iframe>
                                        <img v-else class="w-full rounded-lg " :src="item.path" alt="" />
                                    </div>
                                </div>
                                <div class="mt-4 px-2 pb-3">
                                    <h2 class="font-semibold text-sm normal-case">ORDEN DE COMPRA BIENES Y SERVICIOS.pdf
                                    </h2>
                                    <p class="text-gray-500 text-xs mt-2">EXPEDIENTE HASTA DICIEMBRE 23</p>
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

import SideInfoFile from './SideInfoFile.vue';
import SearchIcon from './Icons/searchIcon.vue';
import AddExpediente from './AddExpediente.vue';
import { computed, ref, toRefs, watch, h, reactive } from 'vue';
import ListExpedientes from './ListExpedientes.vue';
import OrderListIcon from './Icons/orderListIcon.vue';
import Modal from "@/Components-ISRI/AllModal/Modal.vue";
import OrderSquareIcon from './Icons/orderSquareIcon.vue';
import { useTipoArchivoAnexo } from "@/Composables/RRHH/Expediente/useTipoArchivoAnexo";
import { truncateString } from '@/mixins/truncateString';
import ProcessModal from '@/Components-ISRI/AllModal/ProcessModal.vue';
export default {
    mixins: [truncateString],
    name: 'ModalExpedientes',
    emits: ['cerrar-modal'],
    components: { Modal, SearchIcon, ProcessModal, OrderSquareIcon, OrderListIcon, AddExpediente, ListExpedientes, SideInfoFile },
    props: {
        showModal: {
            type: Boolean,
            default: false,
        },
    },
    setup(props, { emit }) {

        let stateView = ref(0) // Valida que este agregando o viendo los archivos del usuario
        const jsonData = ref([
            { "id": 1, "nombre": "TODOS LOS ANEXOS" },
            { "id": 1, "nombre": "ACUERDO" },
            { "id": 2, "nombre": "AMONESTACION" },
            { "id": 3, "nombre": "CERTIFICACION DE JUNTA DE VIG" },
            { "id": 4, "nombre": "CERTIFICACION DE NOTAS" },
            { "id": 5, "nombre": "CERTIFICACION DE TITULO" },
            { "id": 6, "nombre": "CURRICULUM VITAE" },
            { "id": 7, "nombre": "DIPLOMA" },
            { "id": 8, "nombre": "EXAMEN MEDICO" },
            { "id": 9, "nombre": "EXPEDIENTE LABORAL" },
            { "id": 10, "nombre": "LICENCIA DE CONDUCIR" },
            { "id": 11, "nombre": "PARTIDA DE NACIMIENTO" },
            { "id": 12, "nombre": "PERMISO" },
            { "id": 13, "nombre": "SOLVENCIA PNC" },
            { "id": 14, "nombre": "TITULO" }
        ])

        const jsonFileData = ref([
            { nombre: 'VALLADARES DE MARTINEZ  001  113.pdf', descripcion: "EXPEDIENTE HASTA DICIEMBRE 23", path: '/storage/anexos/AB002_2024-05-23 083256.pdf', tipoArchivo: 'pdf' },
            { nombre: 'LOPEZ PEREZ  002  114.pdf', descripcion: "EXPEDIENTE HASTA ENERO 24", path: '/storage/anexos/AB002_2024-05-24 114655.pdf', tipoArchivo: 'pdf' },
            { nombre: 'GARCIA RAMIREZ  003  115.pdf', descripcion: "EXPEDIENTE HASTA FEBRERO 24", path: '/storage/anexos/GR003_2024-04-05 153245.pdf', tipoArchivo: 'pdf' },
            { nombre: 'RODRIGUEZ GOMEZ  004  116.pdf', descripcion: "EXPEDIENTE HASTA MARZO 24", path: '/storage/anexos/AB002_2024-05-24 084919.png', tipoArchivo: 'png' },
            { nombre: 'MARTINEZ SANCHEZ  005  117.pdf', descripcion: "EXPEDIENTE HASTA ABRIL 24", path: '/storage/anexos/AB002_2024-05-24 084819.png', tipoArchivo: 'png' },
            { nombre: 'MARTINEZ SANCHEZ  005  117.pdf', descripcion: "EXPEDIENTE HASTA ABRIL 24", path: '/storage/anexos/AB002_2024-05-24%20084919.png', tipoArchivo: 'png' },
        ])






        return {
            jsonData, jsonFileData, stateView
        }
    }
}
</script>
