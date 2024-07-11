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
                        Sub Almacen:
                        <span class="text-slate-400 font-medium">
                            {{ tableData.total }}
                        </span>
                    </h2>
                </div>
            </header>
            <div class="overflow-x-auto">
                <Datatable :columns="columns" :sortKey="sortKey" :sortOrders="sortOrders" :searchButton="true"
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
                                        <div @click="deleteSubAlmacen(obj.id_sub_almacen)" class="flex hover:bg-gray-100 py-1 px-2 rounded cursor-pointer">
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
                                        <a @click="getDataToShow(link.url)"
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
                                            <a @click="getDataToShow(link.url)"
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
                                    <span class="cursor-pointer mt-3" v-else @click="getDataToShow(link.url)"
                                        :class="(link.active ? 'inline-flex items-center justify-center rounded-full leading-5 px-2 py-2 bg-white border border-slate-200 text-indigo-500 shadow-sm' : 'inline-flex items-center justify-center leading-5 px-2 py-2 text-slate-600 hover:text-indigo-500 border border-transparent')"><span
                                            class=" w-5">{{ link.label }}</span>
                                    </span>
                                </li>
                            </ul>
                        </div>
                    </nav>
                </div>
            </div>

        </div>


        <ModalSubAlmacen :dataSubAlmacen="dataSendModal" @close-modal="modalIsOpen = false"
            :ModalIsOpen="modalIsOpen" @actualizar-datatable="modalIsOpen=false; getDataToShow()"/>
    </AppLayoutVue>

</template>

<script>
import { usePermissions } from '@/Composables/General/usePermissions.js';
import { toRefs, ref } from 'vue';
import { useToDataTable } from '@/Composables/General/useToDataTable.js';
import { useSubAlmacen } from '@/Composables/Almacen/Mantenimiento/useSubAlmacen.js';
import Datatable from "@/Components-ISRI/Datatable.vue";
import Swal from 'sweetalert2';
import IconM from "@/Components-ISRI/ComponentsToForms/IconM.vue";
import ModalSubAlmacen from "@/Components-ISRI/Almacen/Mantenimiento/ModalSubAlmacen.vue"
import { executeRequest } from '@/plugins/requestHelpers';
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


        const deleteSubAlmacenRequest = async (idSubAlmacen) => {
            try {
                // Realiza la búsqueda de empleados
                const response = await axios.post(
                    "/delete-sub-almacen",
                    {
                        idSubAlmacen,
                    }
                );

                // Devuelve los datos de la respuesta
                return response.data;
            } catch (error) {
                // Manejo de errores específicos
                console.error("Error al obtener empleados por nombre:", error);
                // Lanza el error para que pueda ser manejado por el componente que utiliza este composable
                throw new Error("Error en la búsqueda de empleados");
            }
        };


        const deleteSubAlmacen = async (idSubAlmacen) => {
            const confirmed = await Swal.fire({
                title: '<p class="text-[15pt]">¿Está seguro de borrar Sub Almacen?</p>',
                text: "Al confirmar esta acción, los cambios se guardarán de manera instantánea. Tenga en cuenta que serán permanentes una vez confirmada la operación.",
                icon: "question",
                iconHtml: `<lord-icon src="https://cdn.lordicon.com/enzmygww.json" trigger="loop" delay="500" colors="primary:#121331" style="width:100px;height:100px"></lord-icon>`,
                confirmButtonText: "Si, Actualizar",
                confirmButtonColor: "#001b47",
                cancelButtonText: "Cancelar",
                showCancelButton: true,
                showCloseButton: true,
            });
            if (confirmed.isConfirmed) {
                await executeRequest(
                    deleteSubAlmacenRequest(idSubAlmacen),
                    "El Sub Almacen se ha borrado correctamente!"
                );
                getDataToShow()
            }
        }

        return {
            columns,
            modalIsOpen, dataSendModal,
            links, sortKey, sortOrders,
            deleteSubAlmacen,
            isLoadinRequest, emptyObject,
            dataToShow, tableData, perPage,
            getDataToShow, handleData, sortBy
        }
    }
}
</script>

<style lang="scss" scoped></style>
