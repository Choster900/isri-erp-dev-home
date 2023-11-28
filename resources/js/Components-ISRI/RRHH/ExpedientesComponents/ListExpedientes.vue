<template>
    <div class="mb-4">
        <div class="pb-3  px-5" id="filter-section">
            <div class="flex space-x-3">
                <search-icon @update:onSearch="filterTipoAnexoByName" />
                <order-square-icon class="cursor-pointer" @click="viewList = !viewList" :modeListSelected="viewList" />
                <order-list-icon class="cursor-pointer" @click="viewList = !viewList" :modeListSelected="viewList" />
                <div class="h-6 border border-slate-400/50"></div>
                <!--     <select id="country" class="form-select h-8" placeholder="Seleccione " @input="filterTipoAnexoInSelect($event.target.value)">
                    <option value="1">All</option>
                    <option value="2">Last modified</option>
                    <option value="3">Por peso</option>
                    <option value="4">A - Z</option>
                </select> -->
            </div>
        </div>
        <div class="text-center" v-if="originalDataExpedientePersona == ''">
            <img src="../../../../img/emptyAnexos.svg" alt="" class="h-[350px] mx-auto">
            <h1 class="font-medium mt-6 text-lg">No hay documentos en esta sección de anexos</h1>
            <p class="mt-2 text-sm">Para visualizar documentos en esta área, primero agrega un documento aquí o modifica uno
                existente y muévelo a esta sección.</p>
        </div>
        <!--     <pre>
            {{ originalDataExpedientePersona }}
        </pre> -->
        <div id="mainSection" class="mb-4 h-[500px]  px-5 overflow-y-auto  grid " v-if="originalDataExpedientePersona != ''"
            :class="{ 'grid-cols-1': viewList, 'md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3': !viewList, 'gap-5': !viewList, 'gap-2': viewList }">
            <div class="relative border border-gray-200 bg-white rounded-md shadow-lg cursor-pointer hover:bg-slate-100 text-center"
                :class="{ 'flex': viewList }" v-for="(anexo, i) in originalDataExpedientePersona" :key="i"
                @click="$emit('sent-preview-information', anexo)">
                <div class="flex justify-center" :class="viewList ? 'px-1 ' : ' py-2 px-5'">
                    <img  src="https://upload.wikimedia.org/wikipedia/commons/thumb/8/87/PDF_file_icon.svg/1200px-PDF_file_icon.svg.png"
                        v-if="anexo.id_tipo_mime == 1" alt="" class="h-20 m-2">
                    <img src="https://icons-for-free.com/iconfiles/png/512/gallery+image+landscape+mobile+museum+open+line+icon-1320183049020185924.png"
                        v-else alt="" class="h-20">
                </div>
                <div class="px-5 py-4">
                    <h1 class="font-semibold text-sm " :class="{ 'pb-2': !viewList, 'text-start': viewList }">
                    <!--     {{ anexo.nombre_archivo_anexo }} -->
                        {{ $options.filters.truncate(anexo.nombre_archivo_anexo, 25, '...') }}

                    </h1>
                    <span class="text-xs block text-slate-500 " :class="{ 'text-start': viewList }">
                        Agregado: hace 1 dia
                    </span>
                </div>
                <div class="absolute top-0 right-0 py-1 px-2">
                    <DropdownHelp>
                        <li>
                            <div class="font-medium text-sm text-indigo-500 hover:text-indigo-600 flex items-center py-1 px-3"
                                @click="$emit('redirect-for-modify', anexo)">
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
import { truncateString } from '@/mixins/truncateString'
export default {
    mixins: [truncateString],
    emits: ['redirect-for-modify', 'sent-preview-information'],
    components: { SearchIcon, OrderSquareIcon, OrderListIcon, OrderListIcon, OrderSquareIcon, DropdownHelp },
    props: {
        dataExpedientesPersona: { type: Object, default: () => { }, },
        tipoArchivoSelected: { type: Object, default: () => { } },
    },
    setup(props, { emit }) {
        const viewList = ref(false)// Controla en como se ven la vista
        const { dataExpedientesPersona, tipoArchivoSelected } = toRefs(props);
        const copyDataExpedientesPersona = ref([])
        const originalDataExpedientePersona = ref([])



        const listAnexos = ref(null)

        watch(dataExpedientesPersona, (newTipoArchivo) => {
            if (newTipoArchivo != '' || newTipoArchivo != null) {
                copyDataExpedientesPersona.value = [...JSON.parse(JSON.stringify(dataExpedientesPersona.value))]
                originalDataExpedientePersona.value = [...JSON.parse(JSON.stringify(dataExpedientesPersona.value))]
                emit('sent-preview-information', originalDataExpedientePersona.value[0]) // <-- type check / auto-completion
            }
        })

        const filterTipoAnexoByName = (name) => {
            if (name === "") {

                originalDataExpedientePersona.value = copyDataExpedientesPersona.value;

            } else {
                let parseData = JSON.parse(JSON.stringify(copyDataExpedientesPersona.value))
                // Si hay un nombre, filtra por nombre ignorando mayúsculas y minúsculas
                const filteredContracts = parseData.filter(
                    (index) =>
                        index.nombre_archivo_anexo
                            .toLowerCase()
                            .includes(name.toLowerCase())
                );
                console.log(filteredContracts);

                originalDataExpedientePersona.value = filteredContracts;
            }
        };

        const filterTipoAnexoInSelect = (tipoFilter) => {
            // Crea una copia de los datos originales
            const originalData = JSON.parse(JSON.stringify(copyDataExpedientesPersona.value))

            if (tipoFilter == 1) {
                originalDataExpedientePersona.value = copyDataExpedientesPersona.value;

            } else if (tipoFilter == 2) {
                const filteredContracts = originalData.sort((a, b) => b.fecha_reg_archivo_anexo - a.fecha_reg_archivo_anexo);
                console.log(filteredContracts);
                //objTipoArchivoAnexo.value = filteredContracts;
                // Activa el filtro en la selección
            }
        };

        return {
            filterTipoAnexoInSelect,
            viewList,
            listAnexos,
            filterTipoAnexoByName,
            originalDataExpedientePersona,
        }
    }
}
</script>

<style></style>