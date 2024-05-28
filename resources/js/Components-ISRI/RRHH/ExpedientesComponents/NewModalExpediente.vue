<template>
    <div class="m-1.5">
        <ProcessModal addClases=" bg-[#F9F9F9]" :show="showModal" @close="$emit('cerrar-modal')" maxWidth="5xl">
            <div class="p-10">
                <div class="flex flex-col md:flex-row">
                    <div
                        class="bg-white rounded-md shadow-sm border w-full md:w-1/4 md:h-[600px] h-auto max-h-[600px] overflow-y-auto">
                        <ul class="pt-10">
                            <li class="font-light px-6 py-2 text-gray-600">MANAGER</li>
                            <li class=" font-light flex items-center px-6 py-4 relative cursor-pointer "
                                v-for="(item, index) in annexTypeData" :key="index" @click="annexType = index"
                                :class="{ 'bg-[#E8E8E8]': annexType == index, 'hover:bg-[#8E9CB7]/10': annexType != index }">
                                <div class="text-sm  font-medium">{{ item.label }}</div>
                                <div class="absolute left-0 top-0 bottom-0 w-1 bg-[#8E9CB7]" v-if="annexType == index">
                                </div>
                            </li>
                        </ul>
                    </div>

                    <div class="w-full md:w-3/4 px-2" v-if="stateView">
                        <h2 class="text-xl font-semibold">Upload file</h2>

                        <div class="flex flex-col md:flex-row md:gap-3 py-4">
                            <span class="text-xs font-medium text-slate-500/70">Adjunta un archivo</span>
                            <span class="text-xs font-medium">Selecciona el archivo o arrástralo aquí</span>
                        </div>

                        <div class="flex flex-col md:flex-row gap-3">
                            <div v-if="!nameArchivoAnexo" @dragover.prevent="handleDragOver" @drop="handleDrop"
                                @click="openFileInput"
                                class="h-56 md:w-2/3 border-[3px] cursor-pointer border-dashed border-slate-400 rounded-lg flex items-center justify-center text-center bg-slate-200 hover:bg-slate-300">
                                <img src="../../../../img/Upload-pana.svg" class="w-36 h-24 md:w-64 md:h-64 -mt-10"
                                    alt="Icono de cargar archivo">
                                <input @change="handleFileChange" type="file" ref="fileInput"
                                    accept=".pdf,.jpeg,.jpg,.png" style="display: none;">
                            </div>

                            <div class="relative h-56 md:w-2/3" v-else>
                                <img v-if="/.(jpg|jpeg|png)$/.test(nameArchivoAnexo)" :src="urlArchivoAnexo" alt=""
                                    class="rounded-lg border shadow-md shadow-black max-h-full w-full">
                                <embed v-else :src="urlArchivoAnexo" type="application/pdf" height="220"
                                    class="rounded-lg w-full border border-gray-300 shadow-md shadow-black">

                            </div>

                            <div class="w-full md:w-1/2 flex flex-col">
                                <div class="mb-4 md:mb-0 ">
                                    <label for="searchUser"
                                        class="block mb-1 text-[13px] font-medium text-gray-600">Busqueda de usuario
                                        <span class="text-red-600 font-extrabold">*</span></label>
                                    <div v-if="persona != ''" class="relative flex h-8 w-full flex-row-reverse">
                                        <Multiselect v-model="idPersona" :filter-results="false" :disabled="true"
                                            :resolve-on-load="false" :delay="1000" :searchable="true"
                                            :clear-on-search="true" :min-chars="5" placeholder="buscar por nombre..."
                                            :options="personaWhoWasSelected" :classes="{
                                                containerDisabled: 'cursor-not-allowed bg-gray-300',
                                                wrapper: 'cursor-not-allowed text-xs relative mx-auto w-full flex items-center justify-end box-border  outline-none',
                                                placeholder: 'flex items-center text-center h-full absolute left-0 top-0 pointer-events-none bg-transparent leading-snug pl-3.5 text-gray-400 rtl:left-auto rtl:right-0 rtl:pl-0 rtl:pr-3.5',
                                            }" noOptionsText="<p class='text-xs'>Sin Resultado de personas<p>"
                                            noResultsText="<p class='text-xs'>Sin resultados de personas <p>" />
                                    </div>
                                    <div v-else class="relative flex h-8 w-full flex-row-reverse">
                                        <Multiselect v-model="idPersona" :filter-results="false"
                                            :resolve-on-load="false" :delay="1000" :searchable="true"
                                            :clear-on-search="true" :min-chars="5" placeholder="buscar por nombre..."
                                            :options="async function (query) {
                                                return await getPeopleByName(query)
                                            }" :classes="{
                                                placeholder: 'flex items-center text-center h-full absolute left-0 top-0 pointer-events-none bg-transparent leading-snug pl-3.5 text-gray-400 rtl:left-auto rtl:right-0 rtl:pl-0 rtl:pr-3.5',
                                            }" noOptionsText="<p class='text-xs'>Sin Resultado de personas<p>"
                                            noResultsText="<p class='text-xs'>Sin resultados de personas <p>" />
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label for="selectAnexo"
                                        class="block mb-1 text-[13px] font-medium text-gray-600">Seleccione tipo de
                                        anexo <span class="text-red-600 font-extrabold">*</span></label>
                                    <div class="relative flex h-8 w-full flex-row-reverse">
                                        <Multiselect v-model="idTipoArchivoAnexo" :filter-results="true"
                                            :searchable="true" :clear-on-search="true" :options="annexTypeData"
                                            placeholder="tipos de anexos..." :classes="{
                                                placeholder: 'flex items-center text-center h-full absolute left-0 top-0 pointer-events-none bg-transparent leading-snug pl-3.5 text-gray-400 rtl:left-auto rtl:right-0 rtl:pl-0 rtl:pr-3.5',
                                            }" noOptionsText="<p class='text-xs'><p>"
                                            noResultsText="<p class='text-xs'>no hay resultado en tu busqueda <p>" />
                                    </div>
                                </div>

                                <button v-if="nameArchivoAnexo" @click="deleteFile"
                                    class="w-full text-sm bg-gray-200 text-gray-700 rounded-lg px-4 py-2 hover:bg-gray-300">Remover
                                    archivo <span class="font-bold text-red-600">-</span></button>
                            </div>
                        </div>

                        <div class="mt-4 bg-gray-50 rounded-lg">
                            <textarea id="description" rows="4" v-model="descripcionArchivoAnexo"
                                class="block p-2.5 w-full text-xs text-gray-900 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Descripción del archivo..."></textarea>
                        </div>

                        <div class="mt-4 flex justify-between items-center space-x-2">
                            <button @click="stateView = 0"
                                class="w-full text-sm bg-gray-200 text-gray-700 rounded-lg px-4 py-2 hover:bg-gray-300">Cancel</button>
                            <button @click="createArchivoAnexo()"
                                class="w-full text-sm bg-blue-500 text-white rounded-lg px-4 py-2 hover:bg-blue-600">Guardar
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

                                <span class="text-white  text-center">Agregar Archivo Anexo</span>
                            </div>


                            <div class="bg-white rounded-lg shadow cursor-pointer flex flex-col justify-between hover:shadow-lg hover:-translate-y-1 transition-transform duration-300"
                                v-for="(item, index) in objectPersona?.archivo_anexo" :key="index">
                                <div class="flex justify-between items-start">
                                    <div class="text-red-500 text-2xl w-full">
                                        <iframe v-if="item.id_tipo_mime == 1" class="w-full rounded-lg"
                                            :src="item.url_archivo_anexo" alt=""></iframe>
                                        <img v-else class="w-full rounded-lg " :src="item.url_archivo_anexo" alt="" />
                                    </div>
                                </div>
                                <div class="mt-4 px-2 pb-3">
                                    <h2 class="font-semibold text-sm normal-case">{{ item.nombre_archivo_anexo }}</h2>
                                    <p class="text-gray-500 text-xs mt-2">{{ item.descripcion_archivo_anexo }}</p>
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
import Swal from "sweetalert2";
import { computed, ref, toRefs, watch, h, reactive } from 'vue';
import ListExpedientes from './ListExpedientes.vue';
import OrderListIcon from './Icons/orderListIcon.vue';
import Modal from "@/Components-ISRI/AllModal/Modal.vue";
import OrderSquareIcon from './Icons/orderSquareIcon.vue';
import { useTipoArchivoAnexo } from "@/Composables/RRHH/Expediente/useTipoArchivoAnexo";
import { truncateString } from '@/mixins/truncateString';
import ProcessModal from '@/Components-ISRI/AllModal/ProcessModal.vue';
import { usePersona } from '@/Composables/RRHH/Persona/usePersona';
import { useFileHandling } from '@/Composables/RRHH/Expediente/useFileHandling';
import { useArchivoAnexo } from '@/Composables/RRHH/Expediente/useArchivoAnexo';
import { executeRequest } from '@/plugins/requestHelpers';
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
        persona: {
            type: Object,
            default: () => { },
        },
    },
    setup(props, { emit }) {

        const { persona, showModal } = toRefs(props);


        const { getPeopleByName } = usePersona();

        const { file, fileInput, handleDrop, deleteFile, openFileInput, urlArchivoAnexo, handleDragOver, handleFileChange, nameArchivoAnexo, sizeArchivo, tipoMine } = useFileHandling();

        const { idPersona, idTipoMine, sizeArchivoAnexo, idArchivoAnexo, idTipoArchivoAnexo, fileArchivoAnexo, nombreArchivoAnexo, descripcionArchivoAnexo, updateArchivoAnexo, errorsData, delteArchivoAnexoRequest, delteArchivoAnexo, getPersonasById, dataArrayPersona, isLoadingRequestPersona, createArchivoAnexoRequest } = useArchivoAnexo();


        const stateView = ref(0) // Valida que este agregando o viendo los archivos del usuario
        const annexType = ref(0) // variable que maneja el index del tipo de anexo que se selecciono
        const annexTypeData = ref([ // Objeto con todos los tipos de anexos
            { "value": 0, "label": "TODOS LOS ANEXOS" },
            { "value": 1, "label": "ACUERDO" },
            { "value": 2, "label": "AMONESTACION" },
            { "value": 3, "label": "CERTIFICACION DE JUNTA DE VIG" },
            { "value": 4, "label": "CERTIFICACION DE NOTAS" },
            { "value": 5, "label": "CERTIFICACION DE TITULO" },
            { "value": 6, "label": "CURRICULUM VITAE" },
            { "value": 7, "label": "DIPLOMA" },
            { "value": 8, "label": "EXAMEN MEDICO" },
            { "value": 9, "label": "EXPEDIENTE LABORAL" },
            { "value": 10, "label": "LICENCIA DE CONDUCIR" },
            { "value": 11, "label": "PARTIDA DE NACIMIENTO" },
            { "value": 12, "label": "PERMISO" },
            { "value": 13, "label": "SOLVENCIA PNC" },
            { "value": 14, "label": "TITULO" }
        ])

        const objectPersona = ref(null)
        const personaWhoWasSelected = ref([])// Contiene el array de la persona a quien se esta editando

        // Observa los cambios en la variable 'persona'
        watch(persona, (newValue, oldValue) => {
            // Verifica que 'persona' no sea nulo, indefinido y no esté vacío
            if (newValue !== null && newValue !== undefined && (Array.isArray(newValue) ? newValue.length > 0 : newValue !== '')) {
                objectPersona.value = newValue; // Actualiza 'objectPersona' con el nuevo valor de 'persona'
                console.log(objectPersona.value);
                console.log("Inserting persona");
                // Agrega un objeto con los datos del usuario seleccionado a 'personaWhoWasSelected'
                personaWhoWasSelected.value.push({ value: newValue.id_persona, label: newValue.nombre_apellido });
                idPersona.value = newValue.id_persona
            }
        });

        // Observa los cambios en la variable 'showModal'
        watch(showModal, (currentValue, oldValue) => {
            // Verifica si 'showModal' se ha establecido en falso (se cerró el modal)
            if (!currentValue) {
                console.log("Cleaning up");
                // Limpia el array que contiene el usuario que se está editando
                personaWhoWasSelected.value = [];
                // Limpia el array que contiene los archivos anexos del usuario editado
                objectPersona.value = {};
            }
        });

        watch(file, () => {
            idTipoMine.value = tipoMine.value == 'application/pdf' ? '1' : '2';
            fileArchivoAnexo.value = file.value
            sizeArchivoAnexo.value = sizeArchivo.value
        }) // Verficia que se seleccione una archivo para asignarlo a fileArchivoAnexo

        const createArchivoAnexo = async (thereIsIdPersona) => {
            const confirmed = await Swal.fire({
                title: '<p class="text-[18pt] text-center">¿Esta seguro de agregar un nuevo Anexo?</p>',
                icon: "question",
                iconHtml: `<lord-icon src="https://cdn.lordicon.com/enzmygww.json" trigger="loop" delay="500" colors="primary:#121331" style="width:100px;height:100px"></lord-icon>`,
                confirmButtonText: "Si, Agregar",
                confirmButtonColor: "#001b47",
                cancelButtonText: "Cancelar",
                showCancelButton: true,
                showCloseButton: true,
            });
            if (confirmed.isConfirmed) {
                executeRequest(
                    createArchivoAnexoRequest(thereIsIdPersona),
                    "¡El anexo se ha agregado correctamente! Espere mientras se redirecciona al inicio"
                );
            }
        };




        return {
            createArchivoAnexoRequest, objectPersona, personaWhoWasSelected,
            annexTypeData, stateView, annexType, getPeopleByName, file, fileInput, handleDrop, deleteFile, openFileInput, urlArchivoAnexo, handleDragOver, handleFileChange, nameArchivoAnexo, sizeArchivo, tipoMine,
            idPersona, idTipoMine, sizeArchivoAnexo, idArchivoAnexo, idTipoArchivoAnexo, fileArchivoAnexo, nombreArchivoAnexo, descripcionArchivoAnexo, createArchivoAnexo, updateArchivoAnexo, errorsData, delteArchivoAnexoRequest, delteArchivoAnexo, getPersonasById, dataArrayPersona, isLoadingRequestPersona
        }
    }
}
</script>
