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
        <div id="mainSection" class="mb-4  grid " v-if="listAnexos"
            :class="{ 'grid-cols-1': viewList, 'md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3': !viewList, 'gap-5': !viewList, 'gap-2': viewList }">
            <div class="relative border border-gray-200 bg-white rounded-md shadow-lg cursor-pointer hover:bg-slate-100 text-center"
                :class="{ 'flex': viewList }" v-for="(anexo, i) in listAnexos" :key="i">
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
                    <DropdownHelp>
                        <li>
                            <div class="font-medium text-sm text-indigo-500 hover:text-indigo-600 flex items-center py-1 px-3"
                                @click="$emit('redirect-for-modify', { data: anexo })">
                                <svg class="w-3 h-3 fill-current text-indigo-300 shrink-0 mr-2" viewBox="0 0 12 12">
                                    <path
                                        d="M10.5 0h-9A1.5 1.5 0 000 1.5v9A1.5 1.5 0 001.5 12h9a1.5 1.5 0 001.5-1.5v-9A1.5 1.5 0 0010.5 0zM10 7L8.207 5.207l-3 3-1.414-1.414 3-3L5 2h5v5z" />
                                </svg>
                                <span>Modificar</span>
                            </div>
                        </li>
                    </DropdownHelp>
                </div>
            </div>
        </div>


    </div>
</template>

<script>
import { computed, ref, toRefs, watch } from 'vue'
import OrderListIcon from './Icons/orderListIcon.vue'
import OrderSquareIcon from './Icons/orderSquareIcon.vue'
import SearchIcon from './Icons/searchIcon.vue'
import DropdownHelp from '@/Components-ISRI/ComponentsToForms/DropdownHelp.vue';

export default {
    emits: ['redirect-for-modify'],
    components: { SearchIcon, OrderSquareIcon, OrderListIcon, OrderListIcon, OrderSquareIcon, DropdownHelp },
    props: {
        showModal: { type: Boolean, default: false, },
        dataExpedientesPersona: { type: Object, default: () => { }, },
        tipoArchivoSelected: { type: Object, default: () => { } },
    },
    setup(props) {
        const viewList = ref(false)// Controla en como se ven la vista
        const { dataExpedientesPersona, tipoArchivoSelected } = toRefs(props);
        const listAnexos = ref(null)

        watch(tipoArchivoSelected, (newTipoArchivo) => {
            listAnexos.value = dataExpedientesPersona.value.filter(index => index.id_tipo_archivo_anexo === newTipoArchivo);
        })

        return {
            viewList,
            listAnexos,
        }
    }
}
</script>

<style></style>