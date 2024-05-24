<template>
    <div class="m-1.5">
        <ProcessModal addClases=" bg-[#F9F9F9]" :show="showModal" @close="$emit('cerrar-modal')" maxWidth="5xl">
            <div class="p-10">
                <div class="flex flex-col md:flex-row">
                    <div class="bg-white rounded-md shadow-sm border w-full md:w-1/4 md:h-[600px] h-auto">
                        <ul class="pt-10">
                            <li class="font-light px-6 py-2 text-gray-600">MANAGER</li>
                            <li class="bg-[#E8E8E8] font-light flex items-center px-6 py-2 relative"
                                v-for="(item, index) in jsonData" :key="index">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                    class="size-6 text-[#8E9CB7]">
                                    <path fill-rule="evenodd"
                                        d="M5.625 1.5H9a3.75 3.75 0 0 1 3.75 3.75v1.875c0 1.036.84 1.875 1.875 1.875H16.5a3.75 3.75 0 0 1 3.75 3.75v7.875c0 1.035-.84 1.875-1.875 1.875H5.625a1.875 1.875 0 0 1-1.875-1.875V3.375c0-1.036.84-1.875 1.875-1.875ZM9.75 17.25a.75.75 0 0 0-1.5 0V18a.75.75 0 0 0 1.5 0v-.75Zm2.25-3a.75.75 0 0 1 .75.75v3a.75.75 0 0 1-1.5 0v-3a.75.75 0 0 1 .75-.75Zm3.75-1.5a.75.75 0 0 0-1.5 0V18a.75.75 0 0 0 1.5 0v-5.25Z"
                                        clip-rule="evenodd" />
                                    <path
                                        d="M14.25 5.25a5.23 5.23 0 0 0-1.279-3.434 9.768 9.768 0 0 1 6.963 6.963A5.23 5.23 0 0 0 16.5 7.5h-1.875a.375.375 0 0 1-.375-.375V5.25Z" />
                                </svg>
                                <div class="text-sm pl-4 font-medium">{{ item.nombre }}</div>
                                <div class="absolute left-0 top-0 bottom-0 w-1 bg-[#8E9CB7]"></div>
                            </li>
                        </ul>
                    </div>


                    <div class="border w-full md:w-3/4 mt-4 md:mt-0">Contenido</div>
                </div>
            </div>
        </ProcessModal>

    </div>
</template>
<script>

import SideInfoFile from './SideInfoFile.vue';
import SearchIcon from './Icons/searchIcon.vue';
import AddExpediente from './AddExpediente.vue';
import { computed, ref, toRefs, watch, h } from 'vue';
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

        const jsonData = ref([
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



        return {
            jsonData
        }
    }
}
</script>
