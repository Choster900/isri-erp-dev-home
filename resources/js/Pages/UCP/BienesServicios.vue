<template>
    <Head title="Proceso - Bienes y Servicios" />
    <AppLayoutVue nameSubModule="Compras - Bienes y Servicios" :colorSide="' bg-[#343a40] '">
        <div class="sm:flex sm:justify-end sm:items-center mb-2">
            <div class="grid grid-flow-col sm:auto-cols-max sm:justify-end gap-2">
                <GeneralButton @click="objectProductoAdquisicion = []; shoModalBienesServicios = true;"
                    v-if="permits.insertar == 1" color="bg-green-700  hover:bg-green-800" text="Generar documento"
                    icon="add" />
            </div>
        </div>
        <!-- {{ permits }} -->
        <div class="bg-white shadow-lg rounded-sm border border-slate-200 relative">
            <header class="px-5 py-4">
                <div class="mb-4 md:flex flex-row justify-items-start">
                    <div class="mb-4 md:mr-2 md:mb-0 basis-1/4">
                        <div class="relative flex h-8 w-full flex-row-reverse div-multiselect">
                            <Multiselect v-model="tableData.length" @select="getProductoAdquisicion()"
                                @deselect=" tableData.length = 5; getProductoAdquisicion()"
                                @clear="tableData.length = 5; getProductoAdquisicion()" :options="perPage"
                                :searchable="true" placeholder="Cantidad a mostrar" />
                            <LabelToInput icon="list2" />
                        </div>
                    </div>
                    <h2 class="font-semibold text-slate-800 pt-1">Documentos adquisiciones: <span
                            class="text-slate-400 font-medium">{{
                                prodAdquisicion ? pagination.total : ''
                            }}</span></h2>
                </div>
            </header>
            <!--     -->
            <div class="overflow-x-auto">
                <Datatable :columns="columns" :sortKey="sortKey" :sortOrders="sortOrders" :searchButton="true"
                    @sort="sortBy" @datos-enviados="handleData($event)" @execute-search="getProductoAdquisicion()">
                    <tbody class="text-sm divide-y divide-slate-200" v-if="!isLoadinRequest">
                        <tr v-for="(prod, i) in prodAdquisicion" :key="i" class="hover:bg-gray-200 *:text-[10pt]">
                            <td class="px-2 first:pl-5 last:pr-5 max-w-[22%]">
                                <div class="font-medium text-slate-800 text-center">
                                    {{ prod.documento_adquisicion.proveedor.razon_social_proveedor }}
                                </div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5 max-w-[1%]">
                                <div class="font-medium text-slate-800 text-center">
                                    {{ prod.documento_adquisicion.tipo_documento_adquisicion.nombre_tipo_doc_adquisicion }}
                                </div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5 max-w-[1%]">
                                <div class="font-medium text-slate-800 text-center">
                                    {{ prod.documento_adquisicion.tipo_gestion_compra.codigo_tipo_gestion_compra }}
                                </div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5 max-w-[22%]">
                                <div class="font-medium text-slate-800 text-center">
                                    {{ prod.documento_adquisicion.numero_doc_adquisicion }}
                                </div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5 max-w-[27%] ">
                                <div class="font-medium text-slate-800 text-center uppercase">
                                    {{ prod.nombre_det_doc_adquisicion }}
                                </div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5 max-w-[2%]">
                                <div class="font-medium text-slate-800 text-center">
                                    ${{ prod.monto_det_doc_adquisicion }}
                                </div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5 max-w-[2%]">
                                <div class="font-medium text-slate-800 text-center uppercase">
                                    {{
                                        moment(prod.productos_adquisiciones[0].fecha_reg_prod_adquisicion)
                                            .format("MMM DD / YYYY")
                                    }}
                                </div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5 max-w-[2%]">
                                <div class="font-medium text-slate-800 text-center">
                                    <div :class="{
                                                'text-green-600 bg-green-200': prod.id_estado_doc_adquisicion === 1,
                                                'bg-blue-300 text-blue-600': prod.id_estado_doc_adquisicion === 2,
                                                'text-red-600 bg-red-300': prod.id_estado_doc_adquisicion === 3
                                            }" class="inline-flex font-medium rounded-full text-center px-1.5 py-.5">
                                        {{ prod.estado_documento_adquisicion.nombre_estado_doc_adquisicion }}
                                    </div>
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
                                                            d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                                    </svg>

                                                </span>
                                            </div>
                                            <div class="font-semibold">Ver</div>
                                        </div>
                                        <div class="flex hover:bg-gray-100 py-1 px-2 rounded cursor-pointer"
                                            v-if="prod.id_estado_doc_adquisicion == 1"
                                            @click.stop="changeState(prod.id_det_doc_adquisicion, 2)">
                                            <div class="w-8 text-blue-900">
                                                <span class="text-xs">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                        stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5" />
                                                    </svg>

                                                </span>
                                            </div>
                                            <div class="font-semibold">Enviar Doc.</div>
                                        </div>
                                        <div class="flex hover:bg-gray-100 py-1 px-2 rounded cursor-pointer"
                                            v-if="prod.id_estado_doc_adquisicion == 2"
                                            @click.stop="changeState(prod.id_det_doc_adquisicion, 3)">
                                            <div class="w-8 text-blue-900">
                                                <span class="text-xs">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                        class="w-6 h-6 text-red-500" color="#000000" fill="none">
                                                        <path d="M15.7494 15L9.75 9M9.75064 15L15.75 9"
                                                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                                            stroke-linejoin="round" />
                                                        <path
                                                            d="M22.75 12C22.75 6.47715 18.2728 2 12.75 2C7.22715 2 2.75 6.47715 2.75 12C2.75 17.5228 7.22715 22 12.75 22C18.2728 22 22.75 17.5228 22.75 12Z"
                                                            stroke="currentColor" stroke-width="1.5" />
                                                    </svg>
                                                </span>
                                            </div>
                                            <div class="font-semibold">Cerrar Doc.</div>
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
        <div v-if="!emptyObject" class="px-6 py-4 bg-white shadow-lg rounded-sm border-slate-200 relative">
            <div>
                <nav class="flex justify-between" role="navigation" aria-label="Navigation">
                    <div class="grow text-center">
                        <ul class="inline-flex text-sm font-medium -space-x-px">
                            <li v-for="link in links" :key="link.label">
                                <span v-if="link.label === 'Anterior'"
                                    :class="(link.active ? 'inline-flex items-center justify-center rounded-full leading-5 px-2 py-2 bg-white border border-slate-200 text-indigo-500 shadow-sm' : 'inline-flex items-center justify-center leading-5 px-2 py-2 text-slate-600 hover:text-indigo-500 border border-transparent')">
                                    <a @click="getProductoAdquisicion(link.url)"
                                        class="btn bg-white border-slate-200 hover:border-slate-300 cursor-pointer text-indigo-500">
                                        <div class="flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M6.75 15.75L3 12m0 0l3.75-3.75M3 12h18" />
                                            </svg>
                                            <span class="hidden sm:inline ml-1">Anterior</span>
                                        </div>
                                    </a>
                                </span>
                                <span v-else-if="(link.label == 'Siguiente')"
                                    :class="(link.active ? 'inline-flex items-center justify-center rounded-full leading-5 px-2 py-2 bg-white border border-slate-200 text-indigo-500 shadow-sm' : 'inline-flex items-center justify-center leading-5 px-2 py-2 text-slate-600 hover:text-indigo-500 border border-transparent')">
                                    <div class="flex-1 text-right ml-2">
                                        <a @click="getProductoAdquisicion(link.url)"
                                            class=" btn bg-white border-slate-200 hover:border-slate-300 cursor-pointer text-indigo-500">

                                            <div class="flex items-center">
                                                <span class="hidden sm:inline">Siguiente&nbsp;</span> <svg
                                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                    stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M17.25 8.25L21 12m0 0l-3.75 3.75M21 12H3" />
                                                </svg>
                                            </div>
                                        </a>
                                    </div>
                                </span>
                                <span class="cursor-pointer mt-2" v-else @click="getProductoAdquisicion(link.url)"
                                    :class="(link.active ? 'inline-flex items-center justify-center rounded-full leading-5 px-2 py-2 bg-white border border-slate-200 text-indigo-500 shadow-sm' : 'inline-flex items-center justify-center leading-5 px-2 py-2 text-slate-600 hover:text-indigo-500 border border-transparent')"><span
                                        class=" w-5">{{ link.label }}</span>
                                </span>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>
        <ModalBienesServicios :propProdAdquisicion="objectProductoAdquisicion" :showModal="shoModalBienesServicios"
            @cerrar-modal="shoModalBienesServicios = false; objectProductoAdquisicion = []"
            @actualizar-datatable="getProductoAdquisicion(lastUrl)" :canEdit="permits.actualizar == 1 ? true : false" />
    </AppLayoutVue>
</template>

<script>
import { usePermissions } from '@/Composables/General/usePermissions';
import { ref, toRefs } from 'vue';
import ModalBienesServicios from '@/Components-ISRI/UCP/BienesServicios/ModalBienesServicios.vue';
import { useDatatable } from '@/Composables/UCP/BienesServicios/useDatatable';
import Datatable from '@/Components-ISRI/Datatable.vue';
import moment from 'moment';
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
            changeState,
            lastUrl,
            perPage,
            isLoadinRequest,
            sortBy,
            getProductoAdquisicion,
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
            moment,
            isLoadinRequest,
            changeState,
            sortBy,
            getProductoAdquisicion,
            handleData,
        }
    }
}
</script>

<style></style>
