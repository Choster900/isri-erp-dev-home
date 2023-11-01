<template>
    <div class="mb-4 px-10">
        <div class="pb-3" id="filter-section">
            <div class="flex space-x-3">
                <search-icon />
                <order-square-icon class="cursor-pointer" @click="viewList = !viewList" :modeListSelected="viewList" />
                <order-list-icon class="cursor-pointer" @click="viewList = !viewList" :modeListSelected="viewList" />
                <div class="h-6 border border-slate-400/50"></div>
                <select id="country" class="form-select h-8" placeholder="Seleccione ">
                    <option> </option>
                    <option>All</option>
                    <option>Only with content</option>
                    <option>Last modified</option>
                </select>
            </div>
        </div>
        <div id="mainSection" class="mb-4  grid "
            :class="{ 'grid-cols-1': viewList, 'md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3': !viewList, 'gap-5': !viewList, 'gap-2': viewList }">
            <div class="relative border border-gray-200 bg-white rounded-md shadow-lg cursor-pointer hover:bg-slate-100 text-center"
                :class="{ 'flex': viewList }" v-for="(anexo, i) in archivoAnexos" :key="i">
                <div class="flex justify-center" :class="viewList ? 'px-1 ' : ' py-2 px-5'">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/8/87/PDF_file_icon.svg/1200px-PDF_file_icon.svg.png"
                        alt="" class="h-20">
                </div>
                <div class="px-5 py-4">
                    <h1 class="font-semibold text-sm " :class="{ 'pb-2': !viewList, 'text-start': viewList }">{{
                        anexo.nombre_archivo_anexo }}
                    </h1>
                    <span class="text-xs block text-slate-500 " :class="{ 'text-start': viewList }">Agregado: hace 1
                        dia</span>
                </div>
                <div class="absolute top-0 right-0 px-1 ">
                    <!-- <span class="text-3xl font-bold text-gray-500">&hellip;</span> -->
                    <svg class="w-8 h-8 fill-current" viewBox="0 0 32 32">
                        <circle cx="16" cy="16" r="2"></circle>
                        <circle cx="10" cy="16" r="2"></circle>
                        <circle cx="22" cy="16" r="2"></circle>
                    </svg>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
import { ref } from 'vue'
import OrderListIcon from './Icons/orderListIcon.vue'
import OrderSquareIcon from './Icons/orderSquareIcon.vue'
import SearchIcon from './Icons/searchIcon.vue'
export default {
    components: { SearchIcon, OrderSquareIcon, OrderListIcon, OrderListIcon, OrderSquareIcon },
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
        const archivoAnexos = ref([
            {
                "id_archivo_anexo": 1,
                "id_tipo_archivo_anexo": 101,
                "id_persona": 201,
                "nombre_archivo_anexo": "Senior Client Engineer (React & React Native).pdf",
                "url_archivo_anexo": "https://ejemplo.com/archivo1.pdf",
                "descripcion_archivo_anexo": "Descripción del archivo 1",
                "fecha_reg_archivo_anexo": "2023-10-30",
                "fecha_mod_archivo_anexo": "2023-10-30",
                "usuario_archivo_anexo": "usuario1",
                "ip_archivo_anexo": "192.168.1.1"
            },
            {
                "id_archivo_anexo": 2,
                "id_tipo_archivo_anexo": 102,
                "id_persona": 202,
                "nombre_archivo_anexo": "archivo2.docx",
                "url_archivo_anexo": "https://ejemplo.com/archivo2.docx",
                "descripcion_archivo_anexo": "Descripción del archivo 2",
                "fecha_reg_archivo_anexo": "2023-10-30",
                "fecha_mod_archivo_anexo": "2023-10-30",
                "usuario_archivo_anexo": "usuario2",
                "ip_archivo_anexo": "192.168.1.2"
            },
            {
                "id_archivo_anexo": 3,
                "id_tipo_archivo_anexo": 103,
                "id_persona": 203,
                "nombre_archivo_anexo": "archivo3.txt",
                "url_archivo_anexo": "https://ejemplo.com/archivo3.txt",
                "descripcion_archivo_anexo": "Descripción del archivo 3",
                "fecha_reg_archivo_anexo": "2023-10-30",
                "fecha_mod_archivo_anexo": "2023-10-30",
                "usuario_archivo_anexo": "usuario3",
                "ip_archivo_anexo": "192.168.1.3"
            },
            {
                "id_archivo_anexo": 4,
                "id_tipo_archivo_anexo": 104,
                "id_persona": 204,
                "nombre_archivo_anexo": "archivo4.png",
                "url_archivo_anexo": "https://ejemplo.com/archivo4.png",
                "descripcion_archivo_anexo": "Descripción del archivo 4",
                "fecha_reg_archivo_anexo": "2023-10-30",
                "fecha_mod_archivo_anexo": "2023-10-30",
                "usuario_archivo_anexo": "usuario4",
                "ip_archivo_anexo": "192.168.1.4"
            },
            {
                "id_archivo_anexo": 5,
                "id_tipo_archivo_anexo": 105,
                "id_persona": 205,
                "nombre_archivo_anexo": "archivo5.jpg",
                "url_archivo_anexo": "https://ejemplo.com/archivo5.jpg",
                "descripcion_archivo_anexo": "Descripción del archivo 5",
                "fecha_reg_archivo_anexo": "2023-10-30",
                "fecha_mod_archivo_anexo": "2023-10-30",
                "usuario_archivo_anexo": "usuario5",
                "ip_archivo_anexo": "192.168.1.5"
            },
            {
                "id_archivo_anexo": 6,
                "id_tipo_archivo_anexo": 106,
                "id_persona": 206,
                "nombre_archivo_anexo": "archivo6.xlsx",
                "url_archivo_anexo": "https://ejemplo.com/archivo6.xlsx",
                "descripcion_archivo_anexo": "Descripción del archivo 6",
                "fecha_reg_archivo_anexo": "2023-10-30",
                "fecha_mod_archivo_anexo": "2023-10-30",
                "usuario_archivo_anexo": "usuario6",
                "ip_archivo_anexo": "192.168.1.6"
            },
            {
                "id_archivo_anexo": 7,
                "id_tipo_archivo_anexo": 107,
                "id_persona": 207,
                "nombre_archivo_anexo": "archivo7.pdf",
                "url_archivo_anexo": "https://ejemplo.com/archivo7.pdf",
                "descripcion_archivo_anexo": "Descripción del archivo 7",
                "fecha_reg_archivo_anexo": "2023-10-30",
                "fecha_mod_archivo_anexo": "2023-10-30",
                "usuario_archivo_anexo": "usuario7",
                "ip_archivo_anexo": "192.168.1.7"
            },
            {
                "id_archivo_anexo": 8,
                "id_tipo_archivo_anexo": 108,
                "id_persona": 208,
                "nombre_archivo_anexo": "archivo8.docx",
                "url_archivo_anexo": "https://ejemplo.com/archivo8.docx",
                "descripcion_archivo_anexo": "Descripción del archivo 8",
                "fecha_reg_archivo_anexo": "2023-10-30",
                "fecha_mod_archivo_anexo": "2023-10-30",
                "usuario_archivo_anexo": "usuario8",
                "ip_archivo_anexo": "192.168.1.8"
            },
            {
                "id_archivo_anexo": 9,
                "id_tipo_archivo_anexo": 109,
                "id_persona": 209,
                "nombre_archivo_anexo": "archivo9.txt",
                "url_archivo_anexo": "https://ejemplo.com/archivo9.txt",
                "descripcion_archivo_anexo": "Descripción del archivo 9",
                "fecha_reg_archivo_anexo": "2023-10-30",
                "fecha_mod_archivo_anexo": "2023-10-30",
                "usuario_archivo_anexo": "usuario9",
                "ip_archivo_anexo": "192.168.1.9"
            },
            {
                "id_archivo_anexo": 10,
                "id_tipo_archivo_anexo": 110,
                "id_persona": 210,
                "nombre_archivo_anexo": "archivo10.png",
                "url_archivo_anexo": "https://ejemplo.com/archivo10.png",
                "descripcion_archivo_anexo": "Descripción del archivo 10",
                "fecha_reg_archivo_anexo": "2023-10-30",
                "fecha_mod_archivo_anexo": "2023-10-30",
                "usuario_archivo_anexo": "usuario10",
                "ip_archivo_anexo": "192.168.1.10"
            },
            {
                "id_archivo_anexo": 11,
                "id_tipo_archivo_anexo": 111,
                "id_persona": 211,
                "nombre_archivo_anexo": "archivo11.jpg",
                "url_archivo_anexo": "https://ejemplo.com/archivo11.jpg",
                "descripcion_archivo_anexo": "Descripción del archivo 11",
                "fecha_reg_archivo_anexo": "2023-10-30",
                "fecha_mod_archivo_anexo": "2023-10-30",
                "usuario_archivo_anexo": "usuario11",
                "ip_archivo_anexo": "192.168.1.11"
            }
        ])

        return {
            archivoAnexos,
            viewList,

        }
    }
}
</script>

<style></style>