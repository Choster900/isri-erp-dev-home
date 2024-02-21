<template>
    <Head title="Proceso - Bienes y Servicios" />
    <AppLayoutVue nameSubModule="Compras - Bienes y Servicios" :colorSide="' bg-[#343a40] '">
        <div class="sm:flex sm:justify-end sm:items-center mb-2">
            <div class="grid grid-flow-col sm:auto-cols-max sm:justify-end gap-2">
                <GeneralButton @click="shoModalBienesServicios = true" v-if="permits.insertar == 1"
                    color="bg-green-700  hover:bg-green-800" text="Generar documento" icon="add" />
            </div>
        </div>
        <div class="bg-white shadow-lg rounded-sm border border-slate-200 relative">
            <header class="px-5 py-4">
                <div class="mb-4 md:flex flex-row justify-items-start">
                    <div class="mb-4 md:mr-2 md:mb-0 basis-1/4">
                        <div class="relative flex h-8 w-full flex-row-reverse div-multiselect">
                            <Multiselect v-model="tableData.length" @select="getEvaluaciones()"
                                @deselect=" tableData.length = 5; getEvaluaciones()"
                                @clear="tableData.length = 5; getEvaluaciones()" :options="perPage" :searchable="true"
                                placeholder="Cantidad a mostrar" />
                            <LabelToInput icon="list2" />
                        </div>
                    </div>
                    <h2 class="font-semibold text-slate-800 pt-1">Empleados: <span class="text-slate-400 font-medium">{{
                        evaluaciones ? evaluaciones.length : ''
                    }}</span></h2>
                </div>
            </header>
            <!--     -->
            <div class="overflow-x-auto">
                <Datatable :columns="columns" :sortKey="sortKey" :sortOrders="sortOrders" :searchButton="true"
                    @sort="sortBy" @datos-enviados="handleData($event)" @execute-search="getEvaluaciones()">
                    <tbody class="text-sm divide-y divide-slate-200" v-if="!isLoadinRequest">
                        <tr v-for="(prod, i) in prodAdquisicion" :key="i">

                            <td class="px-2 first:pl-5 last:pr-5  whitespace-nowrap w-px">
                                <div class="font-medium text-slate-800 text-center text-[8.5pt]">
                                    {{ prod.documento_adquisicion.numero_gestion_doc_adquisicion }}
                                </div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5  whitespace-nowrap w-px">
                                <div class="font-medium text-slate-800 text-center text-[8.5pt]">
                                    {{ prod.documento_adquisicion.numero_doc_adquisicion }}
                                </div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5  whitespace-nowrap w-px">
                                <div class="font-medium text-slate-800 text-center text-[8.5pt]">
                                    {{ prod.documento_adquisicion.proveedor.razon_social_proveedor }}
                                </div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5  whitespace-nowrap w-px">
                                <div class="font-medium text-slate-800 text-center text-[8.5pt]">
                                    {{ prod.nombre_det_doc_adquisicion }}
                                </div>
                            </td>


                            <td class="first:pl-5 last:pr-5">
                                <div class="space-x-1">
                                    <DropDownOptions>
                                        <div class="flex hover:bg-gray-100 py-1 px-2 rounded cursor-pointer"
                                            @click.stop="objectProductoAdquisicion = prod; shoModalBienesServicios = !shoModalBienesServicios">
                                            <div class="w-8 text-blue-900">
                                                <span class="text-xs">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                        stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    </svg>
                                                </span>
                                            </div>
                                            <div class="font-semibold">VER</div>
                                        </div>
                                    </DropDownOptions>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                    <tbody v-else>
                        <tr>
                            <td colspan="9" class="text-center">
                                <img src="../../../img/IsSearching.gif" alt="" class="w-60 h-60 mx-auto">
                                <h1 class="font-medium text-xl mt-4">Cargando!!!</h1>
                                <p class="text-sm text-gray-600 mt-2 pb-10">Por favor espera un momento mientras se carga la
                                    información.</p>
                            </td>
                        </tr>
                    </tbody>
                    <tbody v-if="emptyObject && !isLoadinRequest">
                        <tr>
                            <td colspan="9" class="text-center">
                                <img src="../../../img/NoData.gif" alt="" class="w-60 h-60 mx-auto">
                                <h1 class="font-medium text-xl mt-4">No se encontraron resultados!</h1>
                                <p class="text-sm text-gray-600 mt-2 pb-10">Parece que no hay registros disponibles en este
                                    momento.</p>
                            </td>
                        </tr>
                    </tbody>
                </Datatable>

            </div>
        </div>
        <ModalBienesServicios :showModal="shoModalBienesServicios" @cerrar-modal="shoModalBienesServicios = false;"
            :propProdAdquisicion="objectProductoAdquisicion" />
    </AppLayoutVue>
</template>

<script>
import { usePermissions } from '@/Composables/General/usePermissions';
import { ref, toRefs } from 'vue';
import ModalBienesServicios from '@/Components-ISRI/UCP/BienesServicios/ModalBienesServicios.vue';
import { useDatatable } from '@/Composables/UCP/BienesServicios/useDatatable';
import Datatable from '@/Components-ISRI/Datatable.vue';
export default {
    components: { ModalBienesServicios, Datatable },
    props: {
        menu: {
            type: Object,
            default: () => { },
        },
    },
    setup(props) {
        const { columns,
            sortKey,
            sortOrders,
            objectProductoAdquisicion,
            tableData,
            pagination,
            listDependencias,
            prodAdquisicion,
            emptyObject,
            stateModal,
            showModalEvaluacion,
            links,
            lastUrl,
            perPage,
            isLoadinRequest,
            sortBy,
            getEvaluaciones,
            handleData, } = useDatatable()
        const { menu } = toRefs(props);
        const shoModalBienesServicios = ref(false);
        const permits = usePermissions(menu.value, window.location.pathname);

        return {
            columns,
            shoModalBienesServicios,
            permits, sortKey,
            sortOrders,
            objectProductoAdquisicion,
            tableData,
            pagination,
            listDependencias,
            prodAdquisicion,
            emptyObject,
            stateModal,
            showModalEvaluacion,
            links,
            lastUrl,
            perPage,
            isLoadinRequest,
            sortBy,
            getEvaluaciones,
            handleData,
        }
    }
}
</script>

<style></style>
