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
                        <div class="py-3" id="filter-section">
                            <div class="flex space-x-3" :class="{ 'hidden': sectionView != 'mainSection' }">
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
                                    @click="sectionView = 'addSection'">
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
                    <!--  {{ process.env.BASE_URL }} -->
                    <div id="mainSection" class="mb-4 px-4 grid "
                        :class="{ 'hidden': sectionView != 'mainSection', 'grid-cols-1': viewList, 'md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4': !viewList, 'gap-5': !viewList, 'gap-2': viewList }">
                        <div class="border border-gray-200 bg-white rounded-md shadow-lg cursor-pointer hover:bg-slate-100"
                            :class="{ 'flex': viewList }" v-for="(tipo, i) in tiposArchivos" :key="i"
                            @click="sectionView = 'anexoSection'">
                            <div class="" :class="viewList ? 'px-1 ' : 'w- py-2 px-5'">

                                <img :src="`/resources/imagenesTipoAnexos/${tipo.img_tipo_archivo_anexo}`" alt="" class="w-">
                            </div>
                            <div class="px-5 py-4">
                                <h1 class="font-semibold text-sm " :class="viewList ? ' ' : 'pb-2'">{{
                                    tipo.nombre_tipo_archivo_anexo }}</h1>
                                <span class="text-xs block">Modificado: hace 1 dia</span>
                                <span class="text-xs block">Total anexos: 7</span>
                            </div>
                        </div>

                    </div>

                    <add-expediente :class="{ 'hidden': sectionView != 'addSection' }" />
                    <list-expedientes :class="{ 'hidden': sectionView != 'anexoSection' }" />
                </div>


                <side-info-file class=""
                    :class="{ 'hidden': sectionView != 'anexoSection', 'w-1/3': sectionView == 'anexoSection' }"></side-info-file>
            </div>
        </Modal>
    </div>
</template>
<script>
import Modal from "@/Components-ISRI/AllModal/Modal.vue";
import { onMounted, ref } from 'vue';
import SearchIcon from './Icons/searchIcon.vue';
import OrderSquareIcon from './Icons/orderSquareIcon.vue';
import OrderListIcon from './Icons/orderListIcon.vue';
import AddExpediente from './AddExpediente.vue';
import ListExpedientes from './ListExpedientes.vue';
import SideInfoFile from './SideInfoFile.vue';
export default {
    components: { Modal, SearchIcon, OrderSquareIcon, OrderListIcon, AddExpediente, ListExpedientes, SideInfoFile },
    props: {

        showModal: {
            type: Boolean,
            default: false,
        },
        dataExpedientesPersona: {
            type: Object,
            default: () => { },
        },
    },
    setup() {
        const viewList = ref(false)// Controla en como se ven la vista
        const sectionView = ref("mainSection")
        const tiposArchivos = ref([
            {
                "id_tipo_archivo_anexo": 1,
                "nombre_tipo_archivo_anexo": "ACUERDO",
                "laboral_tipo_archivo_anexo": false,
                "estado_tipo_archivo_anexo": "Activo",
                "img_tipo_archivo_anexo": "Business deal-amico.svg"
            },
            {
                "id_tipo_archivo_anexo": 2,
                "nombre_tipo_archivo_anexo": "AMONESTACION",
                "laboral_tipo_archivo_anexo": false,
                "estado_tipo_archivo_anexo": "Activo",
                "img_tipo_archivo_anexo": "amonestacion.svg"

            },
            {
                "id_tipo_archivo_anexo": 3,
                "nombre_tipo_archivo_anexo": "CERTIFICACION DE JUNTA DE VIG",
                "laboral_tipo_archivo_anexo": true,
                "estado_tipo_archivo_anexo": "Activo"
            },
            {
                "id_tipo_archivo_anexo": 4,
                "nombre_tipo_archivo_anexo": "CERTIFICACION DE NOTAS",
                "laboral_tipo_archivo_anexo": true,
                "estado_tipo_archivo_anexo": "Activo"
            },
            {
                "id_tipo_archivo_anexo": 5,
                "nombre_tipo_archivo_anexo": "CERTIFICACION DE TITULO",
                "laboral_tipo_archivo_anexo": true,
                "estado_tipo_archivo_anexo": "Activo"
            },
            {
                "id_tipo_archivo_anexo": 6,
                "nombre_tipo_archivo_anexo": "CURRICULUM VITAE",
                "laboral_tipo_archivo_anexo": false,
                "estado_tipo_archivo_anexo": "Activo"
            },
            {
                "id_tipo_archivo_anexo": 7,
                "nombre_tipo_archivo_anexo": "DIPLOMA",
                "laboral_tipo_archivo_anexo": true,
                "estado_tipo_archivo_anexo": "Activo"
            },
            {
                "id_tipo_archivo_anexo": 8,
                "nombre_tipo_archivo_anexo": "EXAMEN MEDICO",
                "laboral_tipo_archivo_anexo": true,
                "estado_tipo_archivo_anexo": "Activo"
            },
            {
                "id_tipo_archivo_anexo": 9,
                "nombre_tipo_archivo_anexo": "EXPEDIENTE LABORAL",
                "laboral_tipo_archivo_anexo": true,
                "estado_tipo_archivo_anexo": "Activo"
            },
            {
                "id_tipo_archivo_anexo": 10,
                "nombre_tipo_archivo_anexo": "LICENCIA DE CONDUCIR",
                "laboral_tipo_archivo_anexo": false,
                "estado_tipo_archivo_anexo": "Activo"
            },
            {
                "id_tipo_archivo_anexo": 11,
                "nombre_tipo_archivo_anexo": "PARTIDA DE NACIMIENTO",
                "laboral_tipo_archivo_anexo": false,
                "estado_tipo_archivo_anexo": "Activo"
            },
            {
                "id_tipo_archivo_anexo": 12,
                "nombre_tipo_archivo_anexo": "PERMISO",
                "laboral_tipo_archivo_anexo": false,
                "estado_tipo_archivo_anexo": "Activo"
            },
            {
                "id_tipo_archivo_anexo": 13,
                "nombre_tipo_archivo_anexo": "SOLVENCIA PNC",
                "laboral_tipo_archivo_anexo": false,
                "estado_tipo_archivo_anexo": "Activo"
            },
            {
                "id_tipo_archivo_anexo": 14,
                "nombre_tipo_archivo_anexo": "TITULO",
                "laboral_tipo_archivo_anexo": true,
                "estado_tipo_archivo_anexo": "Activo"
            }
        ])

        onMounted(() => {
        })
        return {
            tiposArchivos,
            viewList,
            sectionView,
        }
    }
}
</script>

