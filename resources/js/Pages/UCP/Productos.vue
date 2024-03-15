<template>
    <Head title="Catalogo - Productos" />
    <AppLayoutVue nameSubModule="UCP - Productos" :colorSide="' bg-[#343a40] '">
        <div v-if="isLoadingTop"
            class="fixed top-0 left-0 right-0 bottom-0 flex items-center justify-center bg-gray-900 opacity-75 z-50">
            <div class="flex items-center justify-center my-4">
                <img src="../../../img/loader-spinner.gif" alt="" class="w-8 h-8">
                <h1 class="ml-4 font-medium text-xl text-white font-[Roboto]">Procesando...</h1>
            </div>
        </div>
        <div class="sm:flex sm:justify-end sm:items-center mb-2">
            <div class="grid grid-flow-col sm:auto-cols-max sm:justify-end gap-2">
                <GeneralButton @click="showModalProd = true; prodId = 0;" v-if="permits.insertar == 1"
                    color="bg-green-700  hover:bg-green-800" text="Crear producto" icon="add" />
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
                    <h2 class="font-semibold text-slate-800 pt-1">Productos: <span class="text-slate-400 font-medium">{{
                        tableData.total
                    }}</span></h2>
                </div>
            </header>
            <div class="overflow-x-auto">
                <datatable :columns="columns" :sortKey="sortKey" :sortOrders="sortOrders" :searchButton="true" :sortIcons="true"
                    :staticSelect="false" @sort="sortBy" @datos-enviados="handleData($event)"
                    :inputsToValidate="inputsToValidate" @execute-search="getDataToShow()">
                    <tbody v-if="!isLoadinRequest" class="text-sm divide-y divide-slate-200">
                        <tr v-for="prod in dataToShow" :key="prod.id_producto" class="hover:bg-gray-200">
                            <td class="px-2 first:pl-5 last:pr-5">
                                <div class="font-medium text-slate-800 flex items-center justify-center min-h-[50px]">
                                    {{ prod.id_producto }}
                                </div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5 max-w-[27%]">
                                <div class="font-medium text-slate-800 text-center">
                                    {{ prod.nombre_producto }}
                                </div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5 max-w-[20%]">
                                <div class="font-medium text-slate-800 text-center max-h-[125px] overflow-y-auto">
                                    {{ prod.descripcion_producto }}
                                </div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5">
                                <div class="font-medium text-slate-800 text-center">
                                    {{ prod.id_ccta_presupuestal }}
                                </div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5 max-w-[13%]">
                                <div class="font-medium text-slate-800 text-center">
                                    {{ prod.unidad_medida.nombre_unidad_medida }}
                                </div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5">
                                <div class="font-medium text-slate-800 text-center">
                                    ${{ prod.precio_producto }}
                                </div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5  whitespace-nowrap w-px">
                                <div class="font-medium text-slate-800 text-center">
                                    <div v-if="(prod.estado_producto == 1)"
                                        class="inline-flex font-medium rounded-full text-center px-2.5 py-0.5 bg-emerald-100 text-emerald-500">
                                        Activo
                                    </div>
                                    <div v-else
                                        class="inline-flex font-medium rounded-full text-center px-2.5 py-0.5 bg-rose-100 text-rose-600">
                                        Inactivo
                                    </div>
                                </div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5">
                                <div class="space-x-1 text-center">
                                    <DropDownOptions>
                                        <div @click="showModalProd = true; prodId = prod.id_producto"
                                            v-if="permits.actualizar === 1 && prod.estado_producto == 1"
                                            class="flex hover:bg-gray-100 py-1 px-2 rounded cursor-pointer">
                                            <div class="text-orange-600 w-[22px] h-[22px] mr-2">
                                                <icon-m :iconName="'editM'"></icon-m>
                                            </div>
                                            <div class="font-semibold pt-0.5">Editar</div>
                                        </div>
                                        <div @click="changeStatus(prod.id_producto, prod.estado_producto)"
                                            v-if="permits.eliminar == 1"
                                            class="flex hover:bg-gray-100 py-1 px-2 rounded cursor-pointer">
                                            <div class="ml-0.5 mr-2 w-5 h-5"
                                                :class="prod.estado_producto == 1 ? 'text-red-800' : 'text-green-800'">
                                                <span class="text-xs ">
                                                    <icon-m
                                                        :iconName="prod.estado_producto == 1 ? 'desactivate' : 'activate'">
                                                    </icon-m>
                                                </span>
                                            </div>
                                            <div class="font-semibold">
                                                {{ prod.estado_producto == 1 ? 'Desactivar' : 'Activar' }}
                                            </div>
                                        </div>
                                    </DropDownOptions>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                    <tbody v-else>
                        <tr>
                            <td colspan="8" class="text-center">
                                <img src="../../../img/IsSearching.gif" alt="" class="w-60 h-60 mx-auto">
                                <h1 class="font-medium text-xl mt-4">Cargando!!!</h1>
                                <p class="text-sm text-gray-600 mt-2 pb-10">Por favor espera un momento mientras se carga la
                                    información.</p>
                            </td>
                        </tr>
                    </tbody>
                    <tbody v-if="emptyObject && !isLoadinRequest">
                        <tr>
                            <td colspan="8" class="text-center">
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

        <modal-productos-vue v-if="showModalProd" :showModalProd="showModalProd" :prodId="prodId"
            @cerrar-modal="showModalProd = false" @get-table="getDataToShow(tableData.currentPage)" />

    </AppLayoutVue>
</template>

<script>
import { Head } from "@inertiajs/vue3";
import AppLayoutVue from "@/Layouts/AppLayout.vue";
import Datatable from "@/Components-ISRI/Datatable.vue";
import IconM from "@/Components-ISRI/ComponentsToForms/IconM.vue";
import ModalProductosVue from '@/Components-ISRI/UCP/ModalProductos.vue';

import { ref, toRefs } from 'vue';
import { usePermissions } from '@/Composables/General/usePermissions.js';
import { useToDataTable } from '@/Composables/General/useToDataTable.js';

export default {
    components: { Head, AppLayoutVue, Datatable, IconM, ModalProductosVue },
    props: {
        menu: {
            type: Object,
            default: {}
        },
    },
    setup(props, context) {
        const { menu } = toRefs(props);
        const permits = usePermissions(menu.value, window.location.pathname);

        const showModalProd = ref(false)

        const prodId = ref(0)

        const columns = [
            { width: "8%", label: "ID", name: "id_producto", type: "text" },
            { width: "24%", label: "Nombre", name: "nombre_producto", type: "text" },
            { width: "20%", label: "Descripcion", name: "descripcion_producto", type: "text" },
            { width: "8%", label: "Especifico", name: "id_ccta_presupuestal", type: "text" },
            { width: "13%", label: "Medida", name: "unidad_medida", type: "text" },
            { width: "10%", label: "Precio", name: "precio_producto", type: "text" },
            {
                width: "9%", label: "Estado", name: "estado_producto", type: "select",
                options: [
                    { value: "1", label: "Activo" },
                    { value: "0", label: "Inactivo" }
                ]
            },
            { width: "8%", label: "Acciones", name: "Acciones" },
        ];
        const requestUrl = "/productos"
        const columntToSort = "id_producto"
        const dir = 'desc'

        const inputsToValidate = ref([
            { inputName: 'id_producto', limit: 6 },
            { inputName: 'nombre_producto', limit: 50 },
            { inputName: 'descripcion_producto', limit: 50 },
            { inputName: 'id_ccta_presupuestal', number: true, limit: 5 },
            { inputName: 'unidad_medida', limit: 10 },
            { inputName: 'precio_producto', amount: true, limit: 8 },
        ])

        const changeStatus = async (id, status) => {
            await changeStatusElement(id, status, "/change-status-product")
        }

        const {
            dataToShow,
            tableData, perPage,
            links, sortKey,
            sortOrders,
            isLoadinRequest,
            isLoadingTop,
            emptyObject,
            getDataToShow, handleData, sortBy, changeStatusElement
        } = useToDataTable(columns, requestUrl, columntToSort, dir)

        return {
            permits, dataToShow, showModalProd, tableData, perPage, prodId, inputsToValidate,
            links, sortKey, sortOrders, isLoadinRequest, isLoadingTop, emptyObject, columns,
            getDataToShow, handleData, sortBy, changeStatusElement, changeStatus
        };
    },
}
</script>

<style></style>