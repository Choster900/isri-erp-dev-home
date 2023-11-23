<template>
    <div class="m-1.5">
        <Modal :show="showModal" @close="$emit('cerrar-modal')" modal-title="Expedientes del usuario" maxWidth="5xl">
            <div class=" space-x-4" :class="{ 'flex': sectionView == 'anexoSection' }">
                <div class="px-5 py-4 " :class="{ 'w-3/4': sectionView == 'anexoSection' }">
                    <div class="px-4">
                        <div :class="{ 'hidden': sectionView != 'mainSection' }">
                            <h1 class="font-semibold text-xl">Tipos de anexos</h1>
                        </div>
                        <div :class="{ 'hidden': sectionView != 'addSection' }">
                            <h1 class="font-semibold text-xl">Formulario de archivos</h1>
                        </div>
                        <div :class="{ 'hidden': sectionView != 'anexoSection' }">
                            <h1 class="font-semibold text-xl">Todos los anexos</h1>
                        </div>
                        <div class="">
                            <ul class="inline-flex flex-wrap text-sm font-medium">
                                <li class="flex items-center">
                                    <button @click="sectionView = 'mainSection'"
                                        class="text-slate-500 hover:text-indigo-500">Tipos anexos</button>
                                    <svg class="h-4 w-4 fill-current text-slate-400 mx-2" viewBox="0 0 16 16">
                                        <path d="M6.6 13.4L5.2 12l4-4-4-4 1.4-1.4L12 8z"></path>
                                    </svg>
                                </li>
                                <li class="flex items-center " :class="{ 'hidden': sectionView != 'addSection' }">
                                    <button class="text-slate-500 hover:text-indigo-500">Agregar</button>
                                </li>
                                <li class="flex items-center " :class="{ 'hidden': sectionView != 'anexoSection' }">
                                    <button class="text-slate-500 hover:text-indigo-500">Amonestacion</button>
                                </li>
                            </ul>
                        </div>
                        <div class="py-3" id="filter-section" :class="{ 'hidden': sectionView != 'mainSection' }">
                            <div class="flex space-x-3">
                                <search-icon />
                                <order-square-icon class="cursor-pointer" @click="viewList = !viewList"
                                    :modeListSelected="viewList" />
                                <order-list-icon class="cursor-pointer" @click="viewList = !viewList"
                                    :modeListSelected="viewList" />
                                <div class="h-6 border border-slate-400/50"></div>
                                <select id="country" class="form-select h-8" placeholder="Seleccione ">
                                    <option> </option>
                                    <option>All</option>
                                    <option>Only with content</option>
                                    <option>Last modified</option>
                                </select>

                                <button class="btn-xs bg-indigo-500 hover:bg-indigo-600 text-white"
                                    @click="sectionView = 'addSection'; actionInProgress = 'add'">
                                    <svg class="w-3 h-3 fill-current opacity-50 shrink-0" viewBox="0 0 16 16">
                                        <path
                                            d="M15 7H9V1c0-.6-.4-1-1-1S7 .4 7 1v6H1c-.6 0-1 .4-1 1s.4 1 1 1h6v6c0 .6.4 1 1 1s1-.4 1-1V9h6c.6 0 1-.4 1-1s-.4-1-1-1z">
                                        </path>
                                    </svg>
                                    <span class="ml-2">Nuevo Anexo</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div v-if="!persona" class="py-10 text-center" :class="{ 'hidden': sectionView != 'mainSection'}">
                        <img src="../../../../img/bannerAnexos.svg" alt="" class="h-[350px] mx-auto">
                        <h1 class="font-medium mt-6 text-lg">Añade documentos a las personas desde este panel</h1>
                        <p class="mt-2 text-sm">Los documentos que añadas a una persona por primera vez se mostrarán en la
                            tabla principal.</p>
                    </div>

                    <div id="mainSection" class="mb-4 px-4 grid " v-else
                        :class="{ 'hidden': sectionView != 'mainSection', 'grid-cols-1': viewList, 'md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4': !viewList, 'gap-5': !viewList, 'gap-2': viewList }">
                        <div class="border border-gray-200 bg-white rounded-md shadow-lg cursor-pointer hover:bg-slate-100"
                            :class="{ 'flex': viewList }" v-for="(tipoArchivo, i) in objTipoArchivoAnexo" :key="i"
                            @click="sectionView = 'anexoSection'; datafilteredTypeAnexos = persona.archivo_anexo.filter(index => index.tipo_archivo_anexo.id_tipo_archivo_anexo === tipoArchivo.id_tipo_archivo_anexo);">
                            <div class="" :class="viewList ? 'px-1 ' : 'w- py-2 px-5'">

                                <img :src="`/resources/imagenesTipoAnexos/${tipoArchivo.img_tipo_archivo_anexo}`" alt=""
                                    class=" h-12" :class="{ 'h-20 ': viewList }">
                            </div>
                            <div class="px-5 py-2">
                                <h1 class="font-semibold text-sm " :class="viewList ? ' ' : 'pb-2'">
                                    {{ tipoArchivo.nombre_tipo_archivo_anexo }}</h1>
                                <span class="text-xs block">Modificado: hace 1 dia</span>
                                <span class="text-xs block">Total anexos: 7</span>
                            </div>
                        </div>
                    </div>

                    <list-expedientes :class="{ 'hidden': sectionView != 'anexoSection' }" v-if="persona"
                        @redirect-for-modify="getInformationForRedirectToEdit"
                        :dataExpedientesPersona="datafilteredTypeAnexos" :tipoArchivoSelected="tipoSelected"
                        @sent-preview-information="getAnexoFielForShowInSideInfo" />

                    <add-expediente v-if="objTipoArchivoAnexo != ''" :class="{ 'hidden': sectionView != 'addSection' }"
                        class="pt-1" :opcionPersona="opcionPersona" :tipoArchivoAnexo="objTipoArchivoAnexo"
                        :persona="persona" :objectAnexoFileForUpdate="objectBringsForUpdate"
                        :actionInProgress="actionInProgress" @cerrar-modal="actionWhenIsAdd"
                        @refresh-information="actionWhenIsEdit" />
                </div>


                <side-info-file :anexo-information="objectBringsForShowInSideInfo" v-if="persona"
                    :class="{ 'hidden': sectionView != 'anexoSection', 'w-1/3': sectionView == 'anexoSection' }" />
            </div>
        </Modal>
    </div>
</template>
<script>
import SideInfoFile from './SideInfoFile.vue';
import SearchIcon from './Icons/searchIcon.vue';
import AddExpediente from './AddExpediente.vue';
import { computed, ref, toRefs, watch } from 'vue';
import ListExpedientes from './ListExpedientes.vue';
import OrderListIcon from './Icons/orderListIcon.vue';
import Modal from "@/Components-ISRI/AllModal/Modal.vue";
import OrderSquareIcon from './Icons/orderSquareIcon.vue';
import { useTipoArchivoAnexo } from "@/Composables/RRHH/Expediente/useTipoArchivoAnexo";
export default {
    name: 'ModalExpedientes',
    emits: ['cerrar-modal'],
    components: { Modal, SearchIcon, OrderSquareIcon, OrderListIcon, AddExpediente, ListExpedientes, SideInfoFile },
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
        const viewList = ref(false)// Controla en como se ven la vista
        const sectionView = ref("mainSection") // Maneja la seccion que queremos ver
        const tipoSelected = ref(null) // Controla el tipo seleccionado para filtrar la informacion de expedientes
        const { persona, showModal } = toRefs(props); // Obteniendo la prop de persona
        const objectBringsForUpdate = ref({}) //Objeto que contendra la informacion del archivo para actualizar
        const actionInProgress = ref(null) // Variable que manejara la accion del formulario de anexos, ya sea agregar o editar
        const objectBringsForShowInSideInfo = ref({}) // Objeto que almacenara la informacion de un anexo para enviarlo al sidebar information
        const datafilteredTypeAnexos = ref(null) // Este objeto se enviara a ListExpedientes para mostrarlo en las listas
        const opcionPersona = computed(() => {
            return persona.value ? [{ value: persona.value.id_persona, label: persona.value.pnombre_persona }] : [];
        });

        watch(showModal, () => {
            !showModal.value ? tipoSelected.value = null : ''
            sectionView.value = "mainSection"
        })

        const actionWhenIsAdd = async () => {
            sectionView.value = 'mainSection'
            emit('actualizar-data')
        }
        const actionWhenIsEdit = async () => {
            try {
                const resp = await axios.post("/getArchivosAnexosByUser", { id_persona: persona.value.id_persona });
                sectionView.value = 'mainSection'
                tipoSelected.value = null
                persona.value.archivo_anexo = resp.data.archivo_anexo
            } catch (error) {
                console.error(error);
            }
        }

        // Objeto que retorna la lista de tipo de dato
        const { objTipoArchivoAnexo } = useTipoArchivoAnexo()

        const getInformationForRedirectToEdit = (object) => {
            actionInProgress.value = 'edit'
            objectBringsForUpdate.value = JSON.parse(JSON.stringify(object))
            sectionView.value = "addSection"

        }

        const getAnexoFielForShowInSideInfo = (object) => {
            console.log(object);
            if (object) {
                objectBringsForShowInSideInfo.value = object
            }else{
                objectBringsForShowInSideInfo.value = ''

            }
        }
        return {
            persona,
            viewList,
            sectionView,
            tipoSelected,
            opcionPersona,
            objTipoArchivoAnexo,
            getInformationForRedirectToEdit,
            getAnexoFielForShowInSideInfo,
            objectBringsForUpdate,
            actionInProgress,
            objectBringsForShowInSideInfo,
            datafilteredTypeAnexos,
            actionWhenIsAdd,
            actionWhenIsEdit,
        }
    }
}
</script>

