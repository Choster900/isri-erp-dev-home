<script setup>
import { Head } from "@inertiajs/vue3";
import AppLayoutVue from "@/Layouts/AppLayout.vue";
import Datatable from "@/Components-ISRI/Datatable.vue";;
import axios from 'axios';
//import ModalEvalueacionesVue from '@/Components-ISRI/RRHH/ModalEvaluaciones.vue';
</script>

<template>
    <Head title="RRHH - Evaluacion del personal" />
    <AppLayoutVue nameSubModule="RRHH - Evaluacion del personal">
        <div class="sm:flex sm:justify-end sm:items-center mb-2">
            <div class="grid grid-flow-col sm:auto-cols-max sm:justify-end gap-2">
                <GeneralButton @click="dataEvaluacionToSendModal = []; showModalEvaluacion = !showModalEvaluacion"
                    color="bg-green-700  hover:bg-green-800" text="Agregar Acuerdos" icon="add" />
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
                        evaluaciones.length
                    }}</span></h2>
                </div>
            </header>

            <div class="overflow-x-auto">
                <datatable :columns="columns" :sortKey="sortKey" :sortOrders="sortOrders" :searchButton="true"
                    @sort="sortBy" @datos-enviados="handleData($event)" @execute-search="getEvaluaciones()">
                    <tbody class="text-sm divide-y divide-slate-200" v-if="!isLoadinRequest">
                        <tr v-for="(evaluacion, i) in evaluaciones" :key="i">
                            <td class="px-2 first:pl-5 last:pr-5  whitespace-nowrap w-px">
                                <div class="font-medium text-slate-800 text-center ">{{ evaluacion.id_empleado }}</div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5  whitespace-nowrap w-px">
                                <div class="font-medium text-slate-800 text-center ">{{ evaluacion.codigo_empleado }}</div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5  whitespace-nowrap w-px">
                                <div class="font-medium text-slate-800 text-center">
                                    {{ `${evaluacion.persona.pnombre_persona ? evaluacion.persona.pnombre_persona : ''}
                                                                        ${evaluacion.persona.snombre_persona ? evaluacion.persona.snombre_persona : ''}
                                                                        ${evaluacion.persona.tapellido_persona ? evaluacion.persona.tapellido_persona : ''}` }}
                                </div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5  whitespace-nowrap w-px">
                                <div class="font-medium text-slate-800 text-center">
                                    {{ `${evaluacion.persona.papellido_persona ? evaluacion.persona.papellido_persona : ''}
                                                                        ${evaluacion.persona.sapellido_persona ? evaluacion.persona.sapellido_persona : ''}
                                                                        ${evaluacion.persona.tapellido_persona ? evaluacion.persona.tapellido_persona : ''}` }}
                                </div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5  whitespace-nowrap w-px">
                                <div class="font-medium text-slate-800 text-center">
                                    {{ evaluacion.email_institucional_empleado }}
                                </div>
                            </td>

                            <td class="first:pl-5 last:pr-5">
                                <div class="space-x-1">
                                    <DropDownOptions>
                                        <div class="flex hover:bg-gray-100 py-1 px-2 rounded cursor-pointer"
                                            @click.stop="dataEvaluacionToSendModal = evaluacion; showModalEvaluacion = !showModalEvaluacion">
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
                            <td colspan="6" class="text-center">
                                <img src="../../../img/IsSearching.gif" alt="" class="w-60 h-60 mx-auto">
                                <h1 class="font-medium text-xl mt-4">Cargando!!!</h1>
                                <p class="text-sm text-gray-600 mt-2 pb-10">Por favor espera un momento mientras se carga la
                                    información.</p>
                            </td>
                        </tr>
                    </tbody>
                    <tbody v-if="empty_object && !isLoadinRequest">
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
            <div v-if="empty_object" class="flex text-center py-2">
                <p class="text-red-500 font-semibold text-[16px]" style="margin: 0 auto; text-align: center;">No se
                    encontraron registros.</p>
            </div>

        </div>

        <div v-if="!empty_object" class="px-6 py-4 bg-white shadow-lg rounded-sm border-slate-200 relative">
            <div>
                <nav class="flex justify-between" role="navigation" aria-label="Navigation">
                    <div class="grow text-center">
                        <ul class="inline-flex text-sm font-medium -space-x-px">
                            <li v-for="link in links" :key="link.label">
                                <span v-if="link.label === 'Anterior'"
                                    :class="(link.active ? 'inline-flex items-center justify-center rounded-full leading-5 px-2 py-2 bg-white border border-slate-200 text-indigo-500 shadow-sm' : 'inline-flex items-center justify-center leading-5 px-2 py-2 text-slate-600 hover:text-indigo-500 border border-transparent')">
                                    <a @click="getEvaluaciones(link.url)"
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
                                        <a @click="getEvaluaciones(link.url)"
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
                                <span class="cursor-pointer mt-2" v-else @click="getEvaluaciones(link.url)"
                                    :class="(link.active ? 'inline-flex items-center justify-center rounded-full leading-5 px-2 py-2 bg-white border border-slate-200 text-indigo-500 shadow-sm' : 'inline-flex items-center justify-center leading-5 px-2 py-2 text-slate-600 hover:text-indigo-500 border border-transparent')"><span
                                        class=" w-5" >{{ link.label }}</span>
                                </span>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>
      <!--   <ModalEvalueacionesVue 
            :showModal="showModalEvaluacion"
            @cerrar-modal="showModalEvaluacion = false"
            :evaluacionEmpleadoDBData="dataEvaluacionToSendModal"
            @reload-table="getEvaluaciones(lastUrl)" 
            /> -->
    </AppLayoutVue>
</template>

<script>
export default {

    data() {
        let sortOrders = {};
        let columns = [
            { width: "10%", label: "ID", name: "id_empleado", type: "text" },
            { width: "10%", label: "Codigo", name: "codigo_empleado", type: "text" },
            { width: "20%", label: "Nombres", name: "collecNombre", type: "text" },
            { width: "20%", label: "Apellidos", name: "collecApellido", type: "text" },
            { width: "20%", label: "Correo institucional", name: "email_institucional_empleado", type: "text" },
            { width: "1%", label: "", name: "Acciones" },
        ];
        columns.forEach((column) => {
            if (column.name === 'id_persona')
                sortOrders[column.name] = 1;
            else
                sortOrders[column.name] = -1;
        });
        return {
            empty_object: false,
            permits: [],
            evaluaciones: [],
            dataEvaluacionToSendModal: [],
            showModalEvaluacion: false,
            isLoadinRequest:false,
            links: [],
            lastUrl: "/evaluaciones",
            columns: columns,
            sortKey: "id_persona",
            sortOrders: sortOrders,
            perPage: ["10", "20", "30"],
            tableData: {
                draw: 0,
                length: 5,
                column: 0,
                dir: "desc",
                search: {},
            },
            pagination: {
                lastPage: "",
                currentPage: "",
                total: "",
                lastPageUrl: "",
                nextPageUrl: "",
                prevPageUrl: "",
                from: "",
                to: "",
            },
        }
    },
    methods: {
        async getEvaluaciones(url = "/evaluaciones") {
            this.lastUrl = url;
            this.tableData.draw++;
            this.isLoadinRequest = true;
            await axios.post(url, this.tableData).then((response) => {
                let data = response.data;
                if (this.tableData.draw == data.draw) {
                    this.links = data.data.links;
                    this.pagination.total = data.data.total;
                    this.links[0].label = "Anterior";
                    this.links[this.links.length - 1].label = "Siguiente";
                    this.evaluaciones = response.data.data.data;
                    this.evaluaciones.length > 0 ? this.empty_object = false : this.empty_object = true
                    this.isLoadinRequest = false;

                }
            }).catch((errors) => {

            });
        },
        sortBy(key) {
            if (key != "Acciones") {
                this.sortKey = key;
                this.sortOrders[key] = this.sortOrders[key] * -1;
                this.tableData.column = this.getIndex(this.columns, "name", key);
                this.tableData.dir = this.sortOrders[key] === 1 ? "asc" : "desc";
                this.getEvaluaciones();
            }
        },
        getIndex(array, key, value) {
            return array.findIndex((i) => i[key] == value);
        },

        validarCamposVacios(objeto) {
            for (var propiedad in objeto) {
                if (objeto[propiedad] !== "") {
                    return false;
                }
            }
            return true;
        },
        handleData(myEventData) {
            if (this.validarCamposVacios(myEventData)) {
                this.tableData.search = {};
                this.getEvaluaciones();
            }
            else {
                this.tableData.search = myEventData;
                //this.getSuppilers();
            }
        },
        getPermits() {
            var URLactual = window.location.pathname;
            let data = this.$page.props.menu;
            let menu = JSON.parse(JSON.stringify(data["urls"]));
            menu.forEach((value, index) => {
                value.submenu.forEach((value2, index2) => {
                    if (value2.url === URLactual) {
                        var array = { "insertar": value2.insertar, "actualizar": value2.actualizar, "eliminar": value2.eliminar, "ejecutar": value2.ejecutar };
                        this.permits = array;
                    }
                });
            });
        },

    },
    created() {
        this.getEvaluaciones()
    },
}
</script>

