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
import { computed, ref, toRefs, watch } from 'vue'
import OrderListIcon from './Icons/orderListIcon.vue'
import OrderSquareIcon from './Icons/orderSquareIcon.vue'
import SearchIcon from './Icons/searchIcon.vue'
export default {
    components: { SearchIcon, OrderSquareIcon, OrderListIcon, OrderListIcon, OrderSquareIcon },
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