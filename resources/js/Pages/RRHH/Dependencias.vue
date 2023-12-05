
<template>
    <Head title="Catalogo - Dependencias" />
    <AppLayoutVue nameSubModule="RRHH - Dependencias">
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
                <GeneralButton @click="showModalDependencies = true; dependencyId = 0" v-if="permits.insertar == 1"
                    color="bg-green-700  hover:bg-green-800" text="Agregar Dependencia" icon="add" />
            </div>
        </div>
        <div class="bg-white shadow-lg rounded-sm border border-slate-200 relative">
            
            <header class="px-5 py-4">
                <div class="mb-4 md:flex flex-row justify-items-start">
                    <div class="mb-4 md:mr-2 md:mb-0 basis-1/4">
                        <div class="relative flex h-8 w-full flex-row-reverse div-multiselect">
                            <Multiselect v-model="tableData.length" @select="getDataToShow()" :options="perPage"
                                :searchable="true" placeholder="Cantidad a mostrar" />
                            <LabelToInput icon="list2" />
                        </div>
                    </div>
                    <h2 class="font-semibold text-slate-800 pt-1">Dependencias: <span class="text-slate-400 font-medium">{{
                        tableData.total
                    }}</span></h2>
                </div>
            </header>
            <div class="overflow-x-auto">
                <datatable :columns="columns" :sortKey="sortKey" :sortOrders="sortOrders" :searchButton="true"
                    @sort="sortBy" @datos-enviados="handleData($event)" @execute-search="getDataToShow()">
                    <tbody v-if="!isLoadinRequest" class="text-sm divide-y divide-slate-200">
                        <tr v-for="dependencia in dataToShow" :key="dependencia.id_dependencia">
                            <td class="px-2 first:pl-5 last:pr-5">
                                <div class="font-medium text-slate-800 flex items-center justify-center min-h-[50px]">
                                    {{ dependencia.id_dependencia }}
                                </div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5">
                                <div class="font-medium text-slate-800 text-center">
                                    {{ dependencia.nombre_dependencia }}
                                </div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5  whitespace-nowrap w-px">
                                <div class="font-medium text-slate-800 text-center">
                                    {{ dependencia.codigo_dependencia }}
                                </div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5  whitespace-nowrap w-px">
                                <div v-if="dependencia.jefatura" class="font-medium text-slate-800 text-center">
                                    {{ dependencia.jefatura ? dependencia.jefatura.pnombre_persona : '' }}
                                    {{ dependencia.jefatura ? dependencia.jefatura.snombre_persona : '' }}
                                    {{ dependencia.jefatura ? dependencia.jefatura.tnombre_persona : '' }}
                                    {{ dependencia.jefatura ? dependencia.jefatura.papellido_persona : '' }}
                                    {{ dependencia.jefatura ? dependencia.jefatura.sapellido_persona : '' }}
                                    {{ dependencia.jefatura ? dependencia.jefatura.tapellido_persona : '' }}
                                </div>
                                <div v-else class="font-medium text-red-500 text-center">
                                    N/Asign.
                                </div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5  whitespace-nowrap w-px">
                                <div class="font-medium text-slate-800 text-center">
                                    <div v-if="(dependencia.estado_dependencia == 1)"
                                        class="inline-flex font-medium rounded-full text-center px-2.5 py-0.5 bg-emerald-100 text-emerald-500">
                                        Activo
                                    </div>
                                    <div v-else
                                        class="inline-flex font-medium rounded-full text-center px-2.5 py-0.5 bg-rose-100 text-rose-600">
                                        Inactivo
                                    </div>
                                </div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5  whitespace-nowrap w-px">
                                <div class="space-x-1 text-center">
                                    <DropDownOptions>
                                        <div class="flex hover:bg-gray-100 py-1 px-2 rounded cursor-pointer"
                                            v-if="dependencia.estado_dependencia == 1"
                                            @click="showModalDetail = true; dependencyId = dependencia.id_dependencia">
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
                                            <div class="font-semibold">Ver</div>
                                        </div>
                                        <div class="flex hover:bg-gray-100 py-1 px-2 rounded cursor-pointer"
                                            v-if="permits.actualizar == 1 && dependencia.estado_dependencia == 1"
                                            @click="showModalDependencies = true; dependencyId = dependencia.id_dependencia">
                                            <div class="w-8 text-green-900">
                                                <span class="text-xs">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                        stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M13.5 16.875h3.375m0 0h3.375m-3.375 0V13.5m0 3.375v3.375M6 10.5h2.25a2.25 2.25 0 002.25-2.25V6a2.25 2.25 0 00-2.25-2.25H6A2.25 2.25 0 003.75 6v2.25A2.25 2.25 0 006 10.5zm0 9.75h2.25A2.25 2.25 0 0010.5 18v-2.25a2.25 2.25 0 00-2.25-2.25H6a2.25 2.25 0 00-2.25 2.25V18A2.25 2.25 0 006 20.25zm9.75-9.75H18a2.25 2.25 0 002.25-2.25V6A2.25 2.25 0 0018 3.75h-2.25A2.25 2.25 0 0013.5 6v2.25a2.25 2.25 0 002.25 2.25z" />
                                                    </svg>
                                                </span>
                                            </div>
                                            <div class="font-semibold">Editar</div>
                                        </div>
                                        <div class="flex hover:bg-gray-100 py-1 px-2 rounded cursor-pointer"
                                            @click="changeStatus(dependencia.id_dependencia, dependencia.estado_dependencia)"
                                            v-if="permits.eliminar === 1">
                                            <div class="w-8">
                                                <span class="text-xs">
                                                    <svg :fill="dependencia.estado_dependencia == 1 ? '#991B1B' : '#166534'"
                                                        version="1.1" xmlns="http://www.w3.org/2000/svg" width="20px"
                                                        height="20px" xmlns:xlink="http://www.w3.org/1999/xlink"
                                                        viewBox="0 0 97.994 97.994" xml:space="preserve"
                                                        :stroke="dependencia.estado_dependencia == 1 ? '#991B1B' : '#166534'">
                                                        <path
                                                            d="M97.155,9.939c-0.582-0.416-1.341-0.49-1.991-0.193l-10.848,4.935C74.08,5.29,60.815,0.118,46.966,0.118c-15.632,0-30.602,6.666-41.07,18.289c-0.359,0.399-0.543,0.926-0.51,1.461c0.033,0.536,0.28,1.036,0.686,1.388l11.301,9.801c0.818,0.711,2.055,0.639,2.787-0.162c6.866-7.512,16.636-11.821,26.806-11.821c6.135,0,12.229,1.584,17.622,4.583l-7.826,3.561c-0.65,0.296-1.095,0.916-1.163,1.627c-0.069,0.711,0.247,1.405,0.828,1.82l34.329,24.52c0.346,0.246,0.753,0.373,1.163,0.373c0.281,0,0.563-0.06,0.828-0.181c0.65-0.296,1.095-0.916,1.163-1.627l4.075-41.989C98.053,11.049,97.737,10.355,97.155,9.939z">
                                                        </path>
                                                        <path
                                                            d="M80.619,66.937c-0.819-0.709-2.055-0.639-2.787,0.162c-6.866,7.514-16.638,11.822-26.806,11.822c-6.135,0-12.229-1.584-17.622-4.583l7.827-3.561c0.65-0.296,1.094-0.916,1.163-1.628c0.069-0.711-0.247-1.404-0.828-1.819L7.237,42.811c-0.583-0.416-1.341-0.49-1.991-0.193c-0.65,0.296-1.094,0.916-1.163,1.627L0.009,86.233c-0.069,0.712,0.247,1.406,0.828,1.822c0.583,0.416,1.341,0.488,1.991,0.192l10.848-4.935c10.237,9.391,23.502,14.562,37.351,14.562c15.632,0,30.602-6.666,41.07-18.289c0.358-0.398,0.543-0.926,0.51-1.461c-0.033-0.536-0.28-1.036-0.687-1.388L80.619,66.937z">
                                                        </path>
                                                    </svg>
                                                </span>
                                            </div>
                                            <div class="font-semibold">
                                                {{ dependencia.estado_dependencia ? 'Desactivar' : 'Activar' }}
                                            </div>
                                        </div>
                                        <div class="flex hover:bg-gray-100 py-1 px-2 rounded cursor-pointer"
                                            @click="showModalBoss = true; dependencyId = dependencia.id_dependencia" v-if="permits.ejecutar === 1 && dependencia.estado_dependencia == 1">
                                            <div class="w-8">
                                                <span class="text-xs">
                                                    <svg class="w-6 h-6 text-cyan-700" viewBox="0 0 24 24" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round"
                                                            stroke-linejoin="round"></g>
                                                        <g id="SVGRepo_iconCarrier">
                                                            <path
                                                                d="M4 17H20M20 17L16 13M20 17L16 21M20 7H4M4 7L8 3M4 7L8 11"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"></path>
                                                        </g>
                                                    </svg>
                                                </span>
                                            </div>
                                            <div class="font-semibold">
                                                Jefatura
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
        <div v-if="!emptyObject" class="px-6 py-8 bg-white shadow-lg rounded-sm border-slate-200 relative">
            <div>
                <nav class="flex justify-between" role="navigation" aria-label="Navigation">
                    <div class="grow text-center">
                        <ul class="inline-flex text-sm font-medium -space-x-px">
                            <li v-for="link in links" :key="link.label">
                                <span v-if="(link.label == 'Anterior')"
                                    :class="(link.active ? 'inline-flex items-center justify-center rounded-full leading-5 px-2 py-2 bg-white border border-slate-200 text-indigo-500 shadow-sm' : 'inline-flex items-center justify-center leading-5 px-2 py-2 text-slate-600 hover:text-indigo-500 border border-transparent')">

                                    <div class="flex-1 text-right ml-2">
                                        <a @click="link.url ? getDataToShow(link.url) : ''" class=" btn bg-white border-slate-200 hover:border-slate-300 cursor-pointer
                                  text-indigo-500">
                                            &lt;-<span class="hidden sm:inline">&nbsp;Anterior</span>
                                        </a>
                                    </div>
                                </span>
                                <span v-else-if="(link.label == 'Siguiente')"
                                    :class="(link.active ? 'inline-flex items-center justify-center rounded-full leading-5 px-2 py-2 bg-white border border-slate-200 text-indigo-500 shadow-sm' : 'inline-flex items-center justify-center leading-5 px-2 py-2 text-slate-600 hover:text-indigo-500 border border-transparent')">
                                    <div class="flex-1 text-right ml-2">
                                        <a @click="link.url ? getDataToShow(link.url) : ''" class=" btn bg-white border-slate-200 hover:border-slate-300 cursor-pointer
                                  text-indigo-500">
                                            <span class="hidden sm:inline">Siguiente&nbsp;</span>-&gt;
                                        </a>
                                    </div>
                                </span>
                                <span class="cursor-pointer mt-2" v-else @click="link.url ? getDataToShow(link.url) : ''"
                                    :class="(link.active ? 'inline-flex items-center justify-center rounded-full leading-5 px-2 py-2 bg-white border border-slate-200 text-indigo-500 shadow-sm' : 'inline-flex items-center justify-center leading-5 px-2 py-2 text-slate-600 hover:text-indigo-500 border border-transparent')"><span
                                        class=" w-5">{{ link.label }}</span>
                                </span>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>

        <modal-dependencias-vue v-if="showModalDependencies" :showModalDependencies="showModalDependencies"
            :dependencyId="dependencyId" @cerrar-modal="showModalDependencies = false"
            @get-table="getDataToShow(tableData.currentPage)" />

        <modal-show-dependencia-vue v-if="showModalDetail" :showModalDetail="showModalDetail" :dependencyId="dependencyId"
            @cerrar-modal="showModalDetail = false" @get-table="getDataToShow(tableData.currentPage)" />

        <modal-dep-jefatura-vue v-if="showModalBoss" :showModalBoss="showModalBoss" :dependencyId="dependencyId"
            @cerrar-modal="showModalBoss = false" @get-table="getDataToShow(tableData.currentPage)" />

    </AppLayoutVue>
</template>

<script>
import { Head } from "@inertiajs/vue3";
import AppLayoutVue from "@/Layouts/AppLayout.vue";
import Datatable from "@/Components-ISRI/Datatable.vue";
import ModalDependenciasVue from '@/Components-ISRI/RRHH/ModalDependencias.vue';
import ModalShowDependenciaVue from '@/Components-ISRI/RRHH/ModalShowDependencia.vue';
import ModalDepJefaturaVue from '@/Components-ISRI/RRHH/ModalDepJefatura.vue';

import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';

import { usePermissions } from '@/Composables/General/usePermissions.js';
import { useToDataTable } from '@/Composables/General/useToDataTable.js';
import { ref, toRefs } from 'vue';


export default {
    components: { Head, AppLayoutVue, Datatable, ModalDependenciasVue, ModalShowDependenciaVue, ModalDepJefaturaVue },
    props: {
        menu: {
            type: Object,
            default: {}
        },
    },
    setup(props, context) {
        const { menu } = toRefs(props);
        const permits = usePermissions(menu.value, window.location.pathname);

        const dependencyId = ref(0)
        const showModalDependencies = ref(false)
        const showModalDetail = ref(false)
        const showModalBoss = ref(false)
        const columns = [
            { width: "8%", label: "ID", name: "id_dependencia", type: "text" },
            { width: "30%", label: "Nombre", name: "nombre_dependencia", type: "text" },
            { width: "12%", label: "Codigo", name: "codigo_dependencia", type: "text" },
            { width: "30%", label: "Jefatura", name: "jefatura", type: "text" },
            {
                width: "10%", label: "Estado", name: "estado_dependencia", type: "select",
                options: [
                    { value: "1", label: "Activo" },
                    { value: "0", label: "Inactivo" }
                ]
            },
            { width: "10%", label: "Acciones", name: "Acciones" },
        ];
        const requestUrl = "/dependencias"
        const columntToSort = "id_dependencia"
        const dir = 'desc'
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

        const changeStatus = async (id, status) => {
            await changeStatusElement(id, status, "/change-status-dependency")
        }

        return {
            permits, dataToShow, tableData,
            perPage, links, sortKey, isLoadingTop,
            sortOrders, sortBy, dependencyId,
            handleData, isLoadinRequest,
            getDataToShow, changeStatus, showModalDetail,
            emptyObject, columns, showModalDependencies, showModalBoss
        };
    }
}


</script>