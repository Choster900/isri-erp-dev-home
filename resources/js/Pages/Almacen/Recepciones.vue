<template>
    <Head title="Producto - Recepciones" />
    <AppLayoutVue nameSubModule="Almacen - Productos">
        <div v-if="isLoadingTop"
            class="fixed top-0 left-0 right-0 bottom-0 flex items-center justify-center bg-gray-800 bg-opacity-50 z-50">
            <div role="status" class="flex items-center">
                <svg aria-hidden="true" class="w-8 h-8 mr-2 text-gray-200 animate-spin dark:text-gray-600 fill-blue-800"
                    viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                        fill="currentColor" />
                    <path
                        d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                        fill="currentFill" />
                </svg>
                <div class="bg-gray-200 rounded-lg p-1 font-semibold">
                    <span class="text-blue-800">CARGANDO...</span>
                </div>
            </div>
        </div>
        <div class="sm:flex sm:justify-end sm:items-center mb-2">
            <div class="grid grid-flow-col sm:auto-cols-max sm:justify-end gap-2">
                <GeneralButton @click="showModalRecep = true; recepId = 0;" v-if="permits.insertar == 1"
                    color="bg-green-700  hover:bg-green-800" text="Iniciar recepcion" icon="add" />
            </div>
        </div>

        <div class="bg-white shadow-lg rounded-sm border border-slate-200 relative">
            <header class="px-5 py-4">
                <div class="mb-4 md:flex flex-row justify-items-start">
                    <div class="mb-4 md:mr-2 md:mb-0 basis-1/4">
                        <div class="relative flex h-8 w-full flex-row-reverse div-multiselect">
                            <Multiselect v-model="tableData.length" @select="getDataToShow()" :options="perPage"
                                :searchable="true" placeholder="Cantidad a mostrar"
                                @deselect=" tableData.length = 5; getDataToShow()"
                                @clear="tableData.length = 5; getDataToShow()" />
                            <LabelToInput icon="list2" />
                        </div>
                    </div>
                    <h2 class="font-semibold text-slate-800 pt-1">Recepciones: <span class="text-slate-400 font-medium">{{
                        tableData.total
                    }}</span></h2>
                </div>
            </header>
            <div class="overflow-x-auto">
                <datatable :columns="columns" :sortKey="sortKey" :sortOrders="sortOrders" :searchButton="true"
                    :staticSelect="false" @sort="sortBy" @datos-enviados="handleData($event)"
                    @execute-search="getDataToShow()">
                    <tbody v-if="!isLoadinRequest" class="text-sm divide-y divide-slate-200">
                        <tr v-for="reception in dataToShow" :key="reception.id_recepcion_pedido" class="hover:bg-gray-200">
                            <td class="px-2 first:pl-5 last:pr-5">
                                <div class="font-medium text-slate-800 flex items-center justify-center min-h-[50px]">
                                    {{ reception.id_recepcion_pedido }}
                                </div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5">
                                <div class="font-medium text-slate-800 text-center">
                                    {{
                                        reception.det_doc_adquisicion.documento_adquisicion.tipo_documento_adquisicion.siglas_tipo_doc_adquisicion
                                    }}
                                    - {{ reception.det_doc_adquisicion.documento_adquisicion.numero_doc_adquisicion }}
                                </div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5  whitespace-nowrap w-px">
                                <div class="font-medium text-slate-800 text-center">
                                    {{ reception.det_doc_adquisicion.nombre_det_doc_adquisicion }}
                                </div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5  whitespace-nowrap w-px">
                                <div class="font-medium text-slate-800 text-center">
                                    {{ reception.acta_recepcion_pedido }}
                                </div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5  whitespace-nowrap w-px">
                                <div class="font-medium text-slate-800 text-center">
                                    {{ reception.fecha_recepcion_pedido }}
                                </div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5  whitespace-nowrap w-px">
                                <div class="font-medium text-slate-800 text-center">
                                    <div :class="{
                                        'bg-blue-100 text-blue-500': reception.id_estado_recepcion_pedido == 1,
                                        'bg-emerald-100 text-emerald-500': reception.id_estado_recepcion_pedido == 2,
                                        'bg-red-100 text-red-500': reception.id_estado_recepcion_pedido == 3
                                    }"
                                        class="inline-flex font-medium rounded-full text-center px-2.5 py-0.5 bg-emerald-100 text-emerald-500">
                                        {{ reception.estado_recepcion.estado_recepcion_pedido }}
                                    </div>
                                </div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5  whitespace-nowrap w-px">
                                <div class="space-x-1 text-center">
                                    <DropDownOptions>
                                        <div v-if="permits.ejecutar === 1 && reception.id_estado_recepcion_pedido != 3"
                                            class="flex hover:bg-gray-100 py-1 px-2 rounded cursor-pointer">
                                            <div class="text-lime-700 w-[24px] h-[24px] mr-1">
                                                <icon-m :iconName="'clipboard-arrow'"></icon-m>
                                            </div>
                                            <div class="font-semibold pt-0.5">Kardex</div>
                                        </div>
                                        <div @click="showModalRecep = true; recepId = reception.id_recepcion_pedido"
                                            v-if="permits.actualizar === 1 && reception.id_estado_recepcion_pedido != 3"
                                            class="flex hover:bg-gray-100 py-1 px-2 rounded cursor-pointer">
                                            <div class="text-orange-700 w-[22px] h-[22px] mr-2">
                                                <icon-m :iconName="'editM'"></icon-m>
                                            </div>
                                            <div class="font-semibold pt-0.5">Editar</div>
                                        </div>
                                        <div @click="showModalRecep = true; recepId = reception.id_recepcion_pedido"
                                            v-if="reception.id_estado_recepcion_pedido === 3"
                                            class="flex hover:bg-gray-100 py-1 px-2 rounded cursor-pointer">
                                            <div class="text-blue-800 w-[25px] h-[25px] mr-2">
                                                <icon-m :iconName="'see'"></icon-m>
                                            </div>
                                            <div class="font-semibold pt-0.5">Ver</div>
                                        </div>
                                        <div @click="changeStatusElement(reception.id_recepcion_pedido, reception.id_estado_recepcion_pedido)"
                                            v-if="permits.eliminar === 1 && reception.id_estado_recepcion_pedido != 3"
                                            class="flex hover:bg-gray-100 py-1 px-2 rounded cursor-pointer">
                                            <div class="text-red-700 w-[24px] h-[24px] mr-1">
                                                <icon-m :iconName="'trash'"></icon-m>
                                            </div>
                                            <div class="font-semibold pt-0.5">Eliminar</div>
                                        </div>
                                    </DropDownOptions>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                    <tbody v-else>
                        <tr>
                            <td colspan="6" class="text-center">
                                <img src="../../../img/IsSearching.gif" alt="" class="w-60 h-60 mx-auto">
                                <h1 class="font-medium text-xl mt-4">Cargando!!!</h1>
                                <p class="text-sm text-gray-600 mt-2 pb-10">Por favor espera un momento mientras se carga la
                                    información.</p>
                            </td>
                        </tr>
                    </tbody>
                    <tbody v-if="emptyObject && !isLoadinRequest">
                        <tr>
                            <td colspan="6" class="text-center">
                                <img src="../../../img/NoData.gif" alt="" class="w-60 h-60 mx-auto">
                                <h1 class="font-medium text-xl mt-4">No se encontraron resultados!</h1>
                                <p class="text-sm text-gray-600 mt-2 pb-10">Parece que no hay registros disponibles en este
                                    momento.</p>
                            </td>
                        </tr>
                    </tbody>
                </datatable>

            </div>

        </div>
        <div v-if="!emptyObject" class="px-6 py-8 bg-white shadow-lg rounded-sm border-slate-200 relative">
            <div>
                <nav class="flex justify-between" role="navigation" aria-label="Navigation">
                    <div class="grow text-center">
                        <ul class="inline-flex text-sm font-medium -space-x-px">
                            <li v-for="link in links" :key="link.label">
                                <span v-if="(link.label == 'Anterior')"
                                    :class="(link.active ? 'inline-flex items-center justify-center rounded-full leading-5 px-2 py-2 bg-white border border-slate-200 text-[#29303C] shadow-sm' : 'inline-flex items-center justify-center leading-5 px-2 py-2 text-slate-600 hover:text-indigo-500 border border-transparent')">

                                    <div class="flex-1 text-right ml-2">
                                        <a @click="link.url ? getDataToShow(link.url) : ''" class=" btn bg-white border-slate-200 hover:border-slate-300 cursor-pointer hover:text-indigo-500
                                  text-[#3c4557]">
                                            &lt;-<span class="hidden sm:inline">&nbsp;Anterior</span>
                                        </a>
                                    </div>
                                </span>
                                <span v-else-if="(link.label == 'Siguiente')"
                                    :class="(link.active ? 'inline-flex items-center justify-center rounded-full leading-5 px-2 py-2 bg-white border border-slate-200 text-[#29303C] shadow-sm' : 'inline-flex items-center justify-center leading-5 px-2 py-2 text-slate-600 hover:text-indigo-500 border border-transparent')">
                                    <div class="flex-1 text-right">
                                        <a @click="link.url ? getDataToShow(link.url) : ''" class=" btn bg-white border-slate-200 hover:border-slate-300 cursor-pointer hover:text-indigo-500
                                        text-[#3c4557]">
                                            <span class="hidden sm:inline">Siguiente&nbsp;</span>-&gt;
                                        </a>
                                    </div>
                                </span>
                                <span class="cursor-pointer mt-2" v-else @click="link.url ? getDataToShow(link.url) : ''"
                                    :class="(link.active ? 'inline-flex items-center justify-center rounded-full leading-5 px-2 py-2 bg-white border border-[#29303C] text-[#29303C] shadow-sm hover:text-indigo-500 hover:border-indigo-500' : 'inline-flex items-center justify-center leading-5 px-2 py-2 text-slate-600 hover:text-indigo-500 border border-transparent')"><span
                                        class="min-w-[20px]">{{ link.label }}</span>
                                </span>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>

        <modal-recepcion-vue v-if="showModalRecep" :showModalRecep="showModalRecep" :recepId="recepId"
            @cerrar-modal="showModalRecep = false" @get-table="getDataToShow(tableData.currentPage)" />

    </AppLayoutVue>
</template>

<script>
import { Head } from "@inertiajs/vue3";
import AppLayoutVue from "@/Layouts/AppLayout.vue";
import Datatable from "@/Components-ISRI/Datatable.vue";
import IconM from "@/Components-ISRI/ComponentsToForms/IconM.vue";
import ModalRecepcionVue from '@/Components-ISRI/Almacen/ModalRecepcion.vue';

import moment from 'moment';
import { ref, toRefs, inject } from 'vue';
import { usePermissions } from '@/Composables/General/usePermissions.js';
import { useToDataTable } from '@/Composables/General/useToDataTable.js';
import { useHandleError } from "@/Composables/General/useHandleError.js";
import { useShowToast } from "@/Composables/General/useShowToast.js";
import { toast } from "vue3-toastify";
import 'vue3-toastify/dist/index.css';

export default {
    components: { Head, AppLayoutVue, Datatable, IconM, ModalRecepcionVue },
    props: {
        menu: {
            type: Object,
            default: {}
        },
    },
    setup(props, context) {
        const swal = inject("$swal");
        const { menu } = toRefs(props);
        const permits = usePermissions(menu.value, window.location.pathname);

        const showModalRecep = ref(false)

        const recepId = ref(0)

        const columns = [
            { width: "10%", label: "Id", name: "id_recepcion_pedido", type: "text" },
            { width: "20%", label: "Documento", name: "documento", type: "text" },
            { width: "30%", label: "Item", name: "item", type: "text" },
            { width: "10%", label: "acta", name: "acta_recepcion_pedido", type: "text" },
            { width: "10%", label: "fecha", name: "fecha_recepcion_pedido", type: "date" },
            {
                width: "10%", label: "Estado", name: "id_estado_recepcion_pedido", type: "select",
                options: [
                    { value: "1", label: "CREADO" },
                    { value: "2", label: "PROCESADO" },
                    { value: "3", label: "ELIMINADO" }
                ]
            },
            { width: "10%", label: "Acciones", name: "Acciones" },
        ];
        const requestUrl = "/recepciones"
        const columntToSort = "id_recepcion_pedido"
        const dir = 'desc'

        const {
            dataToShow,
            tableData, perPage,
            links, sortKey,
            sortOrders,
            isLoadinRequest,
            isLoadingTop,
            emptyObject,
            getDataToShow, handleData, sortBy
        } = useToDataTable(columns, requestUrl, columntToSort, dir)

        const changeStatusElement = async (elementId, status) => {
            swal({
                title: "¿Está seguro de Eliminar esta recepcion?",
                icon: "question",
                iconHtml: "❓",
                confirmButtonText: "Si, Eliminar",
                confirmButtonColor: "#DC2626",
                cancelButtonText: "Cancelar",
                showCancelButton: true,
                showCloseButton: true,
            }).then(async (result) => {
                if (result.isConfirmed) {
                    isLoadingTop.value = true;
                    try {
                        const response = await axios.post('/change-status-reception', {
                            id: elementId,
                            status: status
                        });
                        useShowToast(toast.success, response.data.message);
                    } catch (err) {
                        if (err.response.status === 422) {
                            if (err.response.data.logical_error) {
                                useShowToast(toast.error, err.response.data.logical_error);
                            }
                        } else {
                            const { title, text, icon } = useHandleError(err);
                            swal({ title: title, text: text, icon: icon, timer: 5000 });
                        }
                    } finally {
                        isLoadingTop.value = false;
                        getDataToShow()
                    }
                }
            });
        }

        return {
            permits, dataToShow, showModalRecep, tableData, perPage, recepId,
            links, sortKey, sortOrders, isLoadinRequest, isLoadingTop, emptyObject, columns,
            getDataToShow, handleData, sortBy, changeStatusElement, moment
        };
    },
}
</script>

<style></style>