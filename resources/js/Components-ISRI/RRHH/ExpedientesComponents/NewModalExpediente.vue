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
                            <!-- File Upload Section -->
                            <div v-if="!nameArchivoAnexo" @dragover.prevent="handleDragOver" @drop="handleDrop"
                                @click="openFileInput"
                                class="h-56 md:w-2/3 border-[3px] cursor-pointer border-dashed border-slate-400 rounded-lg flex items-center justify-center text-center bg-slate-200 hover:bg-slate-300">
                                <img src="../../../../img/Upload-pana.svg" class="w-36 h-24 md:w-64 md:h-64 -mt-10"
                                    alt="Icono de cargar archivo">
                                <input @change="handleFileChange" type="file" ref="fileInput"
                                    accept=".pdf,.jpeg,.jpg,.png" style="display: none;">
                            </div>

                            <!-- File Display Section -->
                            <div class="relative h-56 md:w-2/3" v-else>
                                <template v-if="/\.(jpg|jpeg|png)$/.test(nameArchivoAnexo)">
                                    <!-- Image Display -->
                                    <div class="flex items-center justify-center h-full">
                                        <img :src="urlArchivoAnexo" alt="Archivo Anexo"
                                            class="max-h-full max-w-full object-contain rounded-lg border shadow-md shadow-black">
                                    </div>
                                </template>
                                <template v-else>
                                    <!-- PDF Display -->
                                    <embed :src="urlArchivoAnexo" type="application/pdf" height="100%"
                                        class="rounded-lg w-full border border-gray-300 shadow-md shadow-black">
                                </template>
                            </div>
                            <div class="w-full md:w-1/2 flex flex-col">
                                <div v-if="(persona !== null && persona !== '') || (Array.isArray(personaWhoWasSelected) && personaWhoWasSelected.length > 0)"
                                    class="relative flex h-8 w-full flex-row-reverse">
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
                                    <Multiselect v-model="idPersona" :filter-results="false" :resolve-on-load="false"
                                        :delay="1000" :searchable="true" :clear-on-search="true" :min-chars="5"
                                        placeholder="buscar por nombre..." :options="async function (query) {
            return await getPeopleByName(query)
        }" :classes="{
            placeholder: 'flex items-center text-center h-full absolute left-0 top-0 pointer-events-none bg-transparent leading-snug pl-3.5 text-gray-400 rtl:left-auto rtl:right-0 rtl:pl-0 rtl:pr-3.5',
        }" noOptionsText="<p class='text-xs'>Sin Resultado de personas<p>"
                                        noResultsText="<p class='text-xs'>Sin resultados de personas <p>" />
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
                            <button @click="updateArchivoAnexo()" v-if="actionOption == 2"
                                class="w-full text-sm flex items-center justify-center bg-blue-500 text-white rounded-lg px-4 py-2 hover:bg-blue-600 transition-colors duration-300 shadow-md">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" class="h-5 w-5 mr-1">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                Modificar y salir
                            </button>
                            <button @click="createArchivoAnexo()" v-else-if="actionOption == 1"
                                class="w-full text-sm flex items-center justify-center bg-green-500 text-white rounded-lg px-4 py-2 hover:bg-green-600 transition-colors duration-300 shadow-md">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" class="h-5 w-5 mr-1">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                Guardar y salir
                            </button>
                            <button @click="stateView = 0"
                                class="w-full text-sm flex items-center justify-center bg-gray-500 text-white rounded-lg px-4 py-2 hover:bg-gray-600 transition-colors duration-300 shadow-md">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" class="h-5 w-5 mr-1">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Cancelar
                            </button>
                        </div>
                        <div class="mt-4" v-if="actionOption == 2">
                            <button @click="delteArchivoAnexo()"
                                class="w-full text-sm flex items-center justify-center bg-red-500 text-white rounded-lg px-4 py-2 hover:bg-red-600 transition-colors duration-300 shadow-md">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" class="h-5 w-5 mr-1">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Eliminar
                            </button>
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

                            <div @click="cleanData(), stateView = 1"
                                class="bg-slate-700 border rounded-lg shadow-md p-4 flex flex-col justify-center items-center cursor-pointer hover:bg-slate-800 transition-colors duration-300">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" class="h-12 w-12 text-white mb-2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                </svg>
                                <span class="text-white text-lg font-semibold text-center">Agregar anexo al
                                    empleado</span>
                            </div>


                            <div class="bg-white border rounded-lg shadow cursor-pointer flex flex-col justify-between hover:shadow-lg hover:-translate-y-1 transition-transform duration-300"
                                v-for="(item, index) in objectPersona" :key="index"
                                @click.self="onClickForEditFile(item)">
                                <div class="flex justify-between items-start">
                                    <div class="text-red-500 text-2xl w-full">
                                        <template v-if="item.id_tipo_mime == 1">
                                            <!-- PDF Display -->
                                            <iframe class="w-full h-64 rounded-lg border  shadow-black"
                                                :src="item.url_archivo_anexo" frameborder="0"></iframe>
                                        </template>
                                        <template v-else>
                                            <!-- Image Display -->
                                            <img @click.self="onClickForEditFile(item)"
                                                class="w-full h-64 object-contain rounded-lg border shadow-black"
                                                :src="item.url_archivo_anexo" alt="Archivo Anexo" />
                                        </template>
                                    </div>
                                </div>
                                <div class="mt-4 px-2 pb-3">
                                    <!-- Contenedor del archivo -->
                                    <div class="flex items-center justify-between gap-1">
                                        <!-- Título del archivo -->
                                        <h2 @click.self="onClickForEditFile(item)"
                                            class="font-semibold text-sm normal-case mr-1">
                                            {{ $options.filters.truncate(item.nombre_archivo_anexo, 14, '...') }}
                                        </h2>
                                        <!-- Iconos de acciones -->
                                        <div class="flex items-center space-x-2">
                                            <!-- Abrir en nueva pestaña -->
                                            <svg @click="openInNewTab(item.url_archivo_anexo)"
                                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                fill="currentColor"
                                                class="w-5 h-5 hover:shadow-lg hover:-translate-y-1 transition-transform duration-300">
                                                <path fill-rule="evenodd"
                                                    d="M4.25 5.5a.75.75 0 0 0-.75.75v8.5c0 .414.336.75.75.75h8.5a.75.75 0 0 0 .75-.75v-4a.75.75 0 0 1 1.5 0v4A2.25 2.25 0 0 1 12.75 17h-8.5A2.25 2.25 0 0 1 2 14.75v-8.5A2.25 2.25 0 0 1 4.25 4h5a.75.75 0 0 1 0 1.5h-5Z"
                                                    clip-rule="evenodd" />
                                                <path fill-rule="evenodd"
                                                    d="M6.194 12.753a.75.75 0 0 0 1.06.053L16.5 4.44v2.81a.75.75 0 0 0 1.5 0v-4.5a.75.75 0 0 0-.75-.75h-4.5a.75.75 0 0 0 0 1.5h2.553l-9.056 8.194a.75.75 0 0 0-.053 1.06Z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            <!-- Descargar archivo -->
                                            <svg @click="downloadFile(item.url_archivo_anexo)"
                                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                fill="currentColor"
                                                class="w-5 h-5 hover:shadow-lg hover:-translate-y-1 transition-transform duration-300">
                                                <path
                                                    d="M10.75 2.75a.75.75 0 0 0-1.5 0v8.614L6.295 8.235a.75.75 0 1 0-1.09 1.03l4.25 4.5a.75.75 0 0 0 1.09 0l4.25-4.5a.75.75 0 0 0-1.09-1.03l-2.955 3.129V2.75Z" />
                                                <path
                                                    d="M3.5 12.75a.75.75 0 0 0-1.5 0v2.5A2.75 2.75 0 0 0 4.75 18h10.5A2.75 2.75 0 0 0 18 15.25v-2.5a.75.75 0 0 0-1.5 0v2.5c0 .69-.56 1.25-1.25 1.25H4.75c-.69 0-1.25-.56-1.25-1.25v-2.5Z" />
                                            </svg>
                                        </div>
                                    </div>
                                    <!-- Descripción del archivo -->
                                    <p @click.self="onClickForEditFile(item)" class="text-gray-500 text-xs mt-2">
                                        {{ $options.filters.truncate(item.descripcion_archivo_anexo, 55, '...') }}
                                    </p>
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

        const { file,
            fileInput,
            handleDrop,
            deleteFile,
            openFileInput,
            handleDragOver,
            urlArchivoAnexo,
            tipoMine, objectPersona,
            sizeArchivo,
            handleFileChange,
            downloadFile, actionOption,
            openInNewTab, personaWhoWasSelected,
            nameArchivoAnexo, idPersona, idTipoMine, sizeArchivoAnexo, idArchivoAnexo, idTipoArchivoAnexo, fileArchivoAnexo, nombreArchivoAnexo, descripcionArchivoAnexo, stateView, errorsData, updateArchivoAnexoRequest, delteArchivoAnexoRequest, getPersonasById, dataArrayPersona, isLoadingRequestPersona, createArchivoAnexoRequest } = useArchivoAnexo();


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


        // Observa los cambios en la variable 'persona'
        watch(persona, (newValue, oldValue) => {
            // Verifica que 'persona' no sea nulo, indefinido y no esté vacío
            if (newValue !== null && newValue !== undefined && (Array.isArray(newValue) ? newValue.length > 0 : newValue !== '')) {
                objectPersona.value = newValue.archivo_anexo; // Actualiza 'objectPersona' con el nuevo valor de 'persona'
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

                stateView.value = 0
                idArchivoAnexo.value = null
                urlArchivoAnexo.value = null
                idTipoArchivoAnexo.value = null
                nombreArchivoAnexo.value = null
                nameArchivoAnexo.value = null
                descripcionArchivoAnexo.value = null


            }
        });

        watch(file, () => {
            idTipoMine.value = tipoMine.value == 'application/pdf' ? '1' : '2';
            fileArchivoAnexo.value = file.value
            sizeArchivoAnexo.value = sizeArchivo.value
        }) // Verficia que se seleccione una archivo para asignarlo a fileArchivoAnexo


        const delteArchivoAnexo = async () => {
            const confirmed = await Swal.fire({
                title: '<p class="text-[17pt] text-center">¿Esta seguro de eliminar el anexo?. los cambios no podran revertirse y perdera el archivo</p>',
                icon: "question",
                iconHtml: `<lord-icon src="https://cdn.lordicon.com/enzmygww.json" trigger="loop" delay="500" colors="primary:#121331" style="width:100px;height:100px"></lord-icon>`,
                confirmButtonText: "Si, Editar",
                confirmButtonColor: "#F34541",
                cancelButtonText: "Cancelar",
                showCancelButton: true,
                showCloseButton: true,
            });
            if (confirmed.isConfirmed) {
                executeRequest(
                    delteArchivoAnexoRequest(),
                    "¡El anexo se ha eliminado correctamente! Espere mientras se redirecciona al inicio"
                );
            }
        }

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

        const updateArchivoAnexo = async () => {
            const confirmed = await Swal.fire({
                title: '<p class="text-[18pt] text-center">¿Esta seguro de editar el anexo?</p>',
                icon: "question",
                iconHtml: `<lord-icon src="https://cdn.lordicon.com/enzmygww.json" trigger="loop" delay="500" colors="primary:#121331" style="width:100px;height:100px"></lord-icon>`,
                confirmButtonText: "Si, Editar",
                confirmButtonColor: "#001b47",
                cancelButtonText: "Cancelar",
                showCancelButton: true,
                showCloseButton: true,
            });
            if (confirmed.isConfirmed) {
                executeRequest(
                    updateArchivoAnexoRequest(),
                    "¡El anexo se ha acutalizado correctamente! Espere mientras se redirecciona al inicio"
                );
            }
        };

        const onClickForEditFile = (idFile) => {

            console.log(idFile);
            stateView.value = 1
            actionOption.value = 2
            idArchivoAnexo.value = idFile.id_archivo_anexo
            urlArchivoAnexo.value = idFile.url_archivo_anexo
            idTipoArchivoAnexo.value = idFile.id_tipo_archivo_anexo
            nombreArchivoAnexo.value = idFile.nombre_archivo_anexo
            nameArchivoAnexo.value = idFile.nombre_archivo_anexo
            descripcionArchivoAnexo.value = idFile.descripcion_archivo_anexo
        }


        const cleanData = () => {

            idArchivoAnexo.value = ''
            urlArchivoAnexo.value = ''
            idTipoArchivoAnexo.value = ''
            nombreArchivoAnexo.value = ''
            nameArchivoAnexo.value = ''
            descripcionArchivoAnexo.value = ''

            actionOption.value = 1
        }


        return {
            downloadFile, actionOption,
            openInNewTab,
            onClickForEditFile, cleanData, updateArchivoAnexo,
            createArchivoAnexoRequest, objectPersona, personaWhoWasSelected,
            annexTypeData, stateView, annexType, getPeopleByName, file, fileInput, handleDrop, deleteFile, openFileInput, urlArchivoAnexo, handleDragOver, handleFileChange, nameArchivoAnexo, sizeArchivo, tipoMine,
            idPersona, idTipoMine, sizeArchivoAnexo, idArchivoAnexo, idTipoArchivoAnexo, fileArchivoAnexo, nombreArchivoAnexo, descripcionArchivoAnexo, createArchivoAnexo, errorsData, delteArchivoAnexoRequest, delteArchivoAnexo, getPersonasById, dataArrayPersona, isLoadingRequestPersona
        }
    }
}
</script>
