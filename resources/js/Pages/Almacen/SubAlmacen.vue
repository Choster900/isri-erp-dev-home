<template>

    <Head title="Kardex - Transferencias" />
    <AppLayoutVue nameSubModule="Almacen - Sub Almacen">
        <div class="sm:flex sm:justify-end sm:items-center mb-2">
            <div class="grid grid-flow-col sm:auto-cols-max sm:justify-end gap-2">
                <GeneralButton color="bg-green-700  hover:bg-green-800" text="Crear Sub Almacen" icon="add"
                    @click="modalIsOpen = true; dataSendModal = null" />
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
                    <h2 class="font-semibold text-slate-800 pt-1">
                        Transferencias:
                        <span class="text-slate-400 font-medium">
                            {{ tableData.total }}
                        </span>
                    </h2>
                </div>
            </header>
            <div class="overflow-x-auto">
                <datatable :columns="columns" :sortKey="sortKey" :sortOrders="sortOrders" :searchButton="true"
                    :sortIcons="true" :staticSelect="false" @sort="sortBy" @datos-enviados="handleData($event)"
                    @execute-search="getDataToShow()">
                    <tbody v-if="!isLoadinRequest" class="text-sm divide-y divide-slate-200">
                        <tr v-for="obj in dataToShow" :key="obj.id_sub_almacen" class="hover:bg-gray-200">
                            <td class="px-2 first:pl-5 last:pr-5">
                                <div class="font-medium text-slate-800 flex items-center justify-center min-h-[40px]">
                                    {{ obj.id_sub_almacen }}
                                </div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5">
                                <div class="font-medium text-slate-800 flex items-center justify-center min-h-[40px]">
                                    {{ obj.nombre_sub_almacen }}
                                </div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5">
                                <div class="font-medium text-slate-800 flex items-center justify-center min-h-[40px]">
                                    {{ obj.empleado.persona.nombre_apellido }}
                                </div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5  whitespace-nowrap w-px">
                                <div class="space-x-1 text-center">
                                    <DropDownOptions>
                                        <div @click="dataSendModal = obj; modalIsOpen = true"
                                            class="flex hover:bg-gray-100 py-1 px-2 rounded cursor-pointer">
                                            <div class="text-blue-800 w-[25px] h-[25px] mr-2">
                                                <icon-m :iconName="'see'"></icon-m>
                                            </div>
                                            <div class="font-semibold pt-0.5">Ver</div>
                                        </div>
                                        <div class="flex hover:bg-gray-100 py-1 px-2 rounded cursor-pointer">
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
                            <td colspan="8" class="text-center">
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
                            <td colspan="8" class="text-center">
                                <img src="../../../img/NoData.gif" alt="" class="w-60 h-60 mx-auto">
                                <h1 class="font-medium text-xl mt-4">No se encontraron resultados!</h1>
                                <p class="text-sm text-gray-600 mt-2 pb-10">Parece que no hay registros disponibles en
                                    este
                                    momento.</p>
                            </td>
                        </tr>
                    </tbody>
                </datatable>

            </div>

        </div>


        <ModalSubAlmacen :dataSubAlmacen="dataSendModal" @close-modal="modalIsOpen = false"
            :ModalIsOpen="modalIsOpen" />
    </AppLayoutVue>

</template>

<script>
import { usePermissions } from '@/Composables/General/usePermissions.js';
import { toRefs, ref } from 'vue';
import { useToDataTable } from '@/Composables/General/useToDataTable.js';
import { useSubAlmacen } from '@/Composables/Almacen/Mantenimiento/useSubAlmacen.js';
import Datatable from "@/Components-ISRI/Datatable.vue";
import IconM from "@/Components-ISRI/ComponentsToForms/IconM.vue";
import ModalSubAlmacen from "@/Components-ISRI/Almacen/Mantenimiento/ModalSubAlmacen.vue"
export default {
    components: { Datatable, IconM, ModalSubAlmacen },

    props: {
        menu: { type: Object, default: () => ({}) },
    },
    setup(props) {

        const { menu } = toRefs(props);
        const permits = usePermissions(menu.value, window.location.pathname);
        const modalIsOpen = ref(false)
        const dataSendModal = ref(null)

        const columns = [
            { width: "13%", label: "Id", name: "id_sub_almacen", type: "text" },
            { width: "13%", label: "Nombre", name: "nombre_sub_almacen", type: "text" },
            { width: "13%", label: "Responsable", name: "responsable_sub_almacen", type: "text" },
            { width: "10%", label: "Acciones", name: "Acciones" },
        ];

        const requestUrl = "/sub-almacen"
        const columntToSort = "id_sub_almacen"
        const dir = 'desc'

        const {
            links, sortKey, sortOrders,
            isLoadinRequest, emptyObject,
            dataToShow, tableData, perPage,
            getDataToShow, handleData, sortBy
        } = useToDataTable(columns, requestUrl, columntToSort, dir)


        const { } = useSubAlmacen();

        return {
            columns,
            modalIsOpen, dataSendModal,
            links, sortKey, sortOrders,
            isLoadinRequest, emptyObject,
            dataToShow, tableData, perPage,
            getDataToShow, handleData, sortBy
        }
    }
}
</script>

<style lang="scss" scoped></style>
