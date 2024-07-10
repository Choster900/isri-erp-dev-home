<template>
    <Head title="Catalogo - Marcas" />
    <AppLayoutVue nameSubModule="UCP - Marcas">
        <div v-if="isLoadingTop"
            class="fixed top-0 left-0 right-0 bottom-0 flex items-center justify-center bg-gray-900 opacity-75 z-50">
            <div class="flex items-center justify-center my-4">
                <img src="../../../img/loader-spinner.gif" alt="" class="w-8 h-8">
                <h1 class="ml-4 font-medium text-xl text-white font-[Roboto]">Procesando...</h1>
            </div>
        </div>
        <div class="sm:flex sm:justify-end sm:items-center mb-2">
            <div class="grid grid-flow-col sm:auto-cols-max sm:justify-end gap-2">
                <GeneralButton @click="showModalBrand = true; objId = 0;" v-if="permits.insertar == 1"
                    color="bg-green-700  hover:bg-green-800" text="Crear marca" icon="add" />
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
                    <h2 class="font-semibold text-slate-800 pt-1">Marcas: <span class="text-slate-400 font-medium">{{
                        tableData.total
                    }}</span></h2>
                </div>
            </header>
            <div class="overflow-x-auto">
                <datatable :columns="columns" :sortKey="sortKey" :sortOrders="sortOrders" :searchButton="true" :sortIcons="true"
                    :staticSelect="false" @sort="sortBy" @datos-enviados="handleData($event)"
                    :inputsToValidate="inputsToValidate" @execute-search="getDataToShow()">
                    <tbody v-if="!isLoadinRequest" class="text-sm divide-y divide-slate-200">
                        <tr v-for="brand in dataToShow" :key="brand.id_producto" class="hover:bg-gray-200">
                            <td class="px-2 first:pl-5 last:pr-5">
                                <div class="font-medium text-slate-800 flex items-center justify-center min-h-[50px]">
                                    {{ brand.id_marca }}
                                </div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5 max-w-[27%]">
                                <div class="font-medium text-slate-800 text-center">
                                    {{ brand.nombre_marca }}
                                </div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5 max-w-[20%]">
                                <div class="font-medium text-slate-800 text-center max-h-[125px] overflow-y-auto">
                                    {{ moment(brand.fecha_reg_marca).format("DD/MM/YYYY") }}
                                </div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5">
                                <div class="font-medium text-slate-800 text-center">
                                    {{ brand.fecha_mod_marca ? moment(brand.fecha_mod_marca).format("DD/MM/YYYY") : 'N/A' }}
                                </div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5  whitespace-nowrap w-px">
                                <div class="font-medium text-slate-800 text-center">
                                    <div v-if="(brand.estado_marca == 1)"
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
                                        <div @click="showModalBrand = true; objId = brand.id_marca"
                                            v-if="permits.actualizar === 1 && brand.estado_marca == 1"
                                            class="flex hover:bg-gray-100 py-1 px-2 rounded cursor-pointer">
                                            <div class="text-orange-600 w-[22px] h-[22px] mr-2">
                                                <icon-m :iconName="'editM'"></icon-m>
                                            </div>
                                            <div class="font-semibold pt-0.5">Editar</div>
                                        </div>
                                        <div @click="changeStatus(brand.id_marca, brand.estado_marca)"
                                            v-if="permits.eliminar == 1"
                                            class="flex hover:bg-gray-100 py-1 px-2 rounded cursor-pointer">
                                            <div class="ml-0.5 mr-2 w-5 h-5"
                                                :class="brand.estado_marca == 1 ? 'text-red-800' : 'text-green-800'">
                                                <span class="text-xs ">
                                                    <icon-m
                                                        :iconName="brand.estado_marca == 1 ? 'desactivate' : 'activate'">
                                                    </icon-m>
                                                </span>
                                            </div>
                                            <div class="font-semibold">
                                                {{ brand.estado_marca == 1 ? 'Desactivar' : 'Activar' }}
                                            </div>
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
        <Pagination :emptyObject="emptyObject" :links="links" @get-data="getDataToShow" />

        <ModalMarcasUcpVue v-if="showModalBrand" :showModalBrand="showModalBrand" :objId="objId"
            @cerrar-modal="showModalBrand = false" @get-table="getDataToShow(tableData.currentPage)" />

    </AppLayoutVue>
</template>

<script>
import { Head } from "@inertiajs/vue3";
import AppLayoutVue from "@/Layouts/AppLayout.vue";
import Datatable from "@/Components-ISRI/Datatable.vue";
import Pagination from "@/Components-ISRI/Pagination.vue";
import IconM from "@/Components-ISRI/ComponentsToForms/IconM.vue";
import ModalMarcasUcpVue from '@/Components-ISRI/UCP/ModalMarcasUcp.vue';
import moment from 'moment';

import { ref, toRefs } from 'vue';
import { usePermissions } from '@/Composables/General/usePermissions.js';
import { useToDataTable } from '@/Composables/General/useToDataTable.js';

export default {
    components: { Head, AppLayoutVue, Datatable, IconM, ModalMarcasUcpVue, Pagination },
    props: {
        menu: {
            type: Object,
            default: {}
        },
    },
    setup(props, context) {
        const { menu } = toRefs(props);
        const permits = usePermissions(menu.value, window.location.pathname);

        const showModalBrand = ref(false)

        const objId = ref(0)

        const columns = [
            { width: "11%", label: "ID", name: "id_marca", type: "text" },
            { width: "35%", label: "Nombre", name: "nombre_marca", type: "text" },
            { width: "17%", label: "Fecha registro", name: "fecha_reg_marca", type: "date" },
            { width: "17%", label: "Fecha modificación", name: "fecha_mod_marca", type: "date" },
            {
                width: "10%", label: "Estado", name: "estado_marca", type: "select",
                options: [
                    { value: "1", label: "Activo" },
                    { value: "0", label: "Inactivo" }
                ]
            },
            { width: "10%", label: "Acciones", name: "Acciones" },
        ];
        const requestUrl = "/marcas-ucp"
        const columntToSort = "id_marca"
        const dir = 'desc'

        const inputsToValidate = ref([
            { inputName: 'id_marca', number:true, limit: 6 },
            { inputName: 'nombre_marca', limit: 50 },
        ])

        const changeStatus = async (id, status) => {
            await changeStatusElement(id, status, "/change-status-brand-ucp")
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
            permits, dataToShow, showModalBrand, tableData, perPage, objId, inputsToValidate,
            links, sortKey, sortOrders, isLoadinRequest, isLoadingTop, emptyObject, columns, moment,
            getDataToShow, handleData, sortBy, changeStatusElement, changeStatus
        };
    },
}
</script>

<style></style>