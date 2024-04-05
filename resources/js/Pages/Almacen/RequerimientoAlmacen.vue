<script lang="ts">
import { useDatatableReqAlm } from '@/Composables/Almacen/RequerimientoAlmacen/useDatatableReqAlm';
import { defineComponent } from 'vue'
import Datatable from '@/Components-ISRI/Datatable.vue';
import ModalRequerimientoAlmacen from '@/Components-ISRI/Almacen/RequerimientosAlmacen/ModalRequerimientoAlmacen.vue';
import axios from 'axios';
import Swal from 'sweetalert2';
import { executeRequest } from '@/plugins/requestHelpers';

export default defineComponent({
    components: { Datatable, ModalRequerimientoAlmacen },
    setup() {
        const {
            links,
            sortBy,
            columns,
            sortKey,
            permits,
            lastUrl,
            perPage,
            tableData,
            sortOrders,
            pagination,
            handleData,
            numeroRequerimientoSiguiente,
            emptyObject,
            isLoadinRequest,
            objectRequerimientos,
            canSaveReq,
            getRequerimientosAlmacen,
            showModalRequerimientoAlmacen, objectRequerimientoToSendModal,
        } = useDatatableReqAlm();


        const changeState = async (idRequerimiento, idProyectoFinanciado, idEstado) => {
            try {
                // Hace una solicitud POST a la ruta "/save-prod-adquicicion" con los datos necesarios
                const resp = await axios.post("/update-state-requerimiento", {
                    idRequerimiento: idRequerimiento,
                    idProyectoFinanciado: idProyectoFinanciado,
                    idEstado: idEstado,
                });
                /* console.log({ idRequerimiento,idProyectoFinanciado,idEstado  }); */
                console.log(resp);
                getRequerimientosAlmacen()
                // Se resuelve la promesa con la respuesta exitosa de la solicitud
            } catch (error) {
                console.log(error);

            }
        }

        /**
         * Guarda productos adquisicion
         *
         * @param {string} productCode - codigo del producto a buscar.
         * @returns {Promise<object>} - Objeto con los datos de la respuesta.
       */
        const changeStateReqAlert = async (idRequerimiento, idProyectoFinanciado, idEstado) => {
            const confirmed = await Swal.fire({
                title: '<p class="text-[18pt] text-center">¿Está seguro que desea cambiar de estado?</p>',
                icon: "question",
                iconHtml: `<lord-icon src="https://cdn.lordicon.com/enzmygww.json" trigger="loop" delay="500" colors="primary:#121331" style="width:100px;height:100px"></lord-icon>`,
                confirmButtonText: "Si, Cambiar",
                confirmButtonColor: "#001b47",
                cancelButtonText: "Cancelar",
                showCancelButton: true,
                showCloseButton: true,
            });
            if (confirmed.isConfirmed) {
                await executeRequest(
                    changeState(idRequerimiento, idProyectoFinanciado, idEstado),
                    "¡El requerimiento ha cambiado de estado correctamente!"
                );
            }
        };


        return {
            changeState,
            links,
            sortBy,
            columns,
            sortKey,
            permits,
            lastUrl,
            numeroRequerimientoSiguiente,
            perPage,
            tableData,
            canSaveReq,
            changeStateReqAlert,
            sortOrders,
            pagination,
            handleData,
            emptyObject,
            isLoadinRequest,
            objectRequerimientos,
            useDatatableReqAlm,
            getRequerimientosAlmacen,
            showModalRequerimientoAlmacen, objectRequerimientoToSendModal,
        }
    }
})
</script>
<template>

    <Head title="Producto - Requerimiento" />
    <AppLayoutVue nameSubModule="Almacen - Requerimiento">
    {{ isLoadinRequest }}
        <div class="sm:flex sm:justify-end sm:items-center mb-2" v-if="!canSaveReq " >
            <div class="grid grid-flow-col sm:auto-cols-max sm:justify-end gap-2" v-if="!isLoadinRequest">
                <GeneralButton @click="showModalRequerimientoAlmacen = true; objectRequerimientoToSendModal = []"
                    color="bg-green-700  hover:bg-green-800" text="Agregar requerimiento" icon="add" />
            </div>
        </div>

        <div class="bg-white shadow-lg rounded-sm border border-slate-200 relative">
            <header class="px-5 py-4">
                <div class="mb-4 md:flex flex-row justify-items-start">
                    <div class="mb-4 md:mr-2 md:mb-0 basis-1/4">
                        <div class="relative flex h-8 w-full flex-row-reverse div-multiselect">
                            <Multiselect placeholder="Cantidad a mostrar" />
                            <LabelToInput icon="list2" />
                        </div>
                    </div>
                    <h2 class="font-semibold text-slate-800 pt-1">Documentos adquisiciones: <span
                            class="text-slate-400 font-medium"></span></h2>
                </div>
            </header>
            <div class="overflow-x-auto">
                <Datatable :columns="columns" :sortKey="sortKey" :sortOrders="sortOrders" :searchButton="true"
                    @sort="sortBy" @datos-enviados="handleData($event)" @execute-search="getRequerimientosAlmacen()">
                    <tbody class="text-sm divide-y divide-slate-200" v-if="!isLoadinRequest">
                        <tr v-for="(requ, i) in objectRequerimientos" :key="i" class="hover:bg-gray-200 *:text-[10pt]">
                            <td class="px-2 first:pl-5 last:pr-5 max-w-[22%]">
                                <div class="font-medium text-slate-800 text-center">
                                    {{ requ.id_requerimiento }}
                                </div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5 max-w-[22%]">
                                <div class="font-medium text-slate-800 text-center">
                                    {{ requ.centro_atencion.nombre_centro_atencion }}
                                </div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5 max-w-[22%]">
                                <div class="font-medium text-slate-800 text-center">
                                    {{ requ.proyecto_financiado.nombre_proy_financiado }}
                                </div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5 max-w-[22%]">
                                <div class="font-medium text-slate-800 text-center">
                                    {{ requ.num_requerimiento }}
                                </div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5 max-w-[22%]">
                                <div class="font-medium text-slate-800 text-center">
                                    <template v-for="(detalle, i) in requ.detalles_requerimiento" :key="i">
                                        <div class="mb-2 text-center">
                                            <p class="text-[10pt]">
                                                <span class="font-medium">codigo producto: </span>{{
                    detalle.producto.codigo_producto
                }}<br>
                                                <span class="font-medium">cantidad: </span> {{
                        detalle.cant_det_requerimiento
                    }}<br>
                                            </p>
                                        </div>
                                        <template v-if="i < requ.detalles_requerimiento.length - 1">
                                            <hr class="my-2 border-t border-gray-300">
                                        </template>
                                    </template>

                                </div>
                            </td>

                            <td class="px-2 first:pl-5 last:pr-5 max-w-[22%]">
                                <div class="font-medium text-slate-800 text-center">
                                    {{ requ.estado_requerimiento.nombre_estado_req }}
                                </div>
                            </td>
                            <td class="first:pl-5 last:pr-5">
                                <div class="space-x-1">
                                    <DropDownOptions>
                                        <div class="flex hover:bg-gray-100 py-1 px-2 rounded cursor-pointer"
                                            @click.stop="objectRequerimientoToSendModal = requ; showModalRequerimientoAlmacen = !showModalRequerimientoAlmacen">
                                            <div class="w-8 text-blue-900">
                                                <span class="text-xs">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        class="w-6 h-6">
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
                                            v-if="requ.id_estado_req == 1"
                                            @click="changeStateReqAlert(requ.id_requerimiento, requ.id_proy_financiado, 2)">
                                            <div class="w-8 text-blue-900">
                                                <span class="text-xs">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        class="w-6 h-6">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                                    </svg>
                                                </span>
                                            </div>
                                            <div class="font-semibold">Aprobar</div>
                                        </div>


                                        <div class="flex hover:bg-gray-100 py-1 px-2 rounded cursor-pointer"
                                            v-if="requ.id_estado_req == 2"
                                            @click="changeStateReqAlert(requ.id_requerimiento, requ.id_proy_financiado, 3)">
                                            <div class="w-8 text-blue-900">
                                                <span class="text-xs">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        class="w-6 h-6">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                                    </svg>

                                                </span>
                                            </div>
                                            <div class="font-semibold">Despachar</div>
                                        </div>

                                        <div class="flex hover:bg-gray-100 py-1 px-2 rounded cursor-pointer"  v-if="requ.id_estado_req == 1 || requ.id_estado_req == 2"
                                            @click="changeStateReqAlert(requ.id_requerimiento, requ.id_proy_financiado, 4)">
                                            <div class="w-8 text-blue-900">
                                                <span class="text-xs">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        class="w-6 h-6">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                                    </svg>

                                                </span>
                                            </div>
                                            <div class="font-semibold">Eliminar</div>
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
                                <p class="text-sm text-gray-600 mt-2 pb-10">Por favor espera un momento mientras se
                                    carga la
                                    información.</p>
                            </td>
                        </tr>
                    </tbody>
                    <tbody v-if="emptyObject && !isLoadinRequest">
                        <tr>
                            <td colspan="9" class="text-center">
                                <img src="../../../img/NoData.gif" alt="" class="w-60 h-60 mx-auto">
                                <h1 class="font-medium text-xl mt-4">No se encontraron resultados!</h1>
                                <p class="text-sm text-gray-600 mt-2 pb-10">Parece que no hay registros disponibles en
                                    este
                                    momento.</p>
                            </td>
                        </tr>
                    </tbody>
                </Datatable>

            </div>
            <div v-if="!emptyObject" class="px-6 py-4 bg-white shadow-lg rounded-sm border-slate-200 relative">
                <div>
                    <nav class="flex justify-between" role="navigation" aria-label="Navigation">
                        <div class="grow text-center">
                            <ul class="inline-flex text-sm font-medium -space-x-px">
                                <li v-for="link in links" :key="link.label">
                                    <span v-if="link.label === 'Anterior'"
                                        :class="(link.active ? 'inline-flex items-center justify-center rounded-full leading-5 px-2 py-2 bg-white border border-slate-200 text-indigo-500 shadow-sm' : 'inline-flex items-center justify-center leading-5 px-2 py-2 text-slate-600 hover:text-indigo-500 border border-transparent')">
                                        <a @click="getRequerimientosAlmacen(link.url)"
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
                                            <a @click="getRequerimientosAlmacen(link.url)"
                                                class=" btn bg-white border-slate-200 hover:border-slate-300 cursor-pointer text-indigo-500">

                                                <div class="flex items-center">
                                                    <span class="hidden sm:inline">Siguiente&nbsp;</span> <svg
                                                        xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        class="w-6 h-6">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M17.25 8.25L21 12m0 0l-3.75 3.75M21 12H3" />
                                                    </svg>
                                                </div>
                                            </a>
                                        </div>
                                    </span>
                                    <span class="cursor-pointer mt-3" v-else @click="getRequerimientosAlmacen(link.url)"
                                        :class="(link.active ? 'inline-flex items-center justify-center rounded-full leading-5 px-2 py-2 bg-white border border-slate-200 text-indigo-500 shadow-sm' : 'inline-flex items-center justify-center leading-5 px-2 py-2 text-slate-600 hover:text-indigo-500 border border-transparent')"><span
                                            class=" w-5">{{ link.label }}</span>
                                    </span>
                                </li>
                            </ul>
                        </div>
                    </nav>
                </div>
            </div>
            <!-- {{ objectRequerimientos }} -->
        </div>
        <ModalRequerimientoAlmacen :showModal="showModalRequerimientoAlmacen"
        :numeroRequerimientoSiguiente="numeroRequerimientoSiguiente"
            :objectRequerimientoToSendModal="objectRequerimientoToSendModal"
            @cerrar-modal="showModalRequerimientoAlmacen = false;"
            @actualizar-datatable="getRequerimientosAlmacen(); showModalRequerimientoAlmacen = false">
        </ModalRequerimientoAlmacen>
    </AppLayoutVue>


</template>

<style scoped></style>
