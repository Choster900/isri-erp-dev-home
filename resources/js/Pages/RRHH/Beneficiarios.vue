<script setup>
import { Head } from "@inertiajs/vue3";
import AppLayoutVue from "@/Layouts/AppLayout.vue";
import Datatable from "@/Components-ISRI/Datatable.vue";
import axios from 'axios';
import ModalBeneficiarios from '@/Components-ISRI/RRHH/BeneficiariosComponents/ModalBeneficiarios.vue';
import ModalCertificadoDeVida from '@/Components-ISRI/RRHH/BeneficiariosComponents/ModalCertificadoDeVida.vue';

</script>

<template>
    <Head title="RRHH - Gestion Empleados" />
    <AppLayoutVue nameSubModule="RRHH - Empleados">
        <div class="sm:flex sm:justify-end sm:items-center mb-2">
            <div class="grid grid-flow-col sm:auto-cols-max sm:justify-end gap-2">
                <GeneralButton @click="dataBeneficiariosToSendModal = []; showModalBeneficiario = !showModalBeneficiario"
                    color="bg-green-700  hover:bg-green-800" text="Agregar Beneficiarios" icon="add" />
            </div>
        </div>
        <div class="bg-white shadow-lg rounded-sm border border-slate-200 relative">
            <header class="px-5 py-4">
                <div class="mb-4 md:flex flex-row justify-items-start">
                    <div class="mb-4 md:mr-2 md:mb-0 basis-1/4">
                        <div class="relative flex h-8 w-full flex-row-reverse div-multiselect">
                            <Multiselect v-model="tableData.length" @select="getBeneficiarios()"
                                @deselect=" tableData.length = 5; getBeneficiarios()"
                                @clear="tableData.length = 5; getBeneficiarios()" :options="perPage" :searchable="true"
                                placeholder="Cantidad a mostrar" />
                            <LabelToInput icon="list2" />
                        </div>
                    </div>
                    <h2 class="font-semibold text-slate-800 pt-1">Empleados: <span class="text-slate-400 font-medium">{{
                        tableData.total
                    }}</span></h2>
                </div>
            </header>

            <div class="overflow-x-auto">
                <datatable :columns="columns" :sortKey="sortKey" :sortOrders="sortOrders" :searchButton="true"
                    @sort="sortBy" @datos-enviados="handleData($event)" @execute-search="getBeneficiarios()">
                    <tbody class="text-sm divide-y divide-slate-200" v-if="!isLoadinRequest">
                        <tr v-for="beneficiario in beneficiarios" :key="beneficiario.id_persona" class="content-body">
                            <td class="px-2 first:pl-5 last:pr-5  whitespace-nowrap w-px">
                                <div class="font-medium text-slate-800 text-center ">{{ beneficiario.id_persona }}</div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5  whitespace-nowrap w-px">
                                <div class="font-medium text-slate-800 text-center">
                                    {{ `${beneficiario.pnombre_persona ? beneficiario.pnombre_persona : ''}
                                                                        ${beneficiario.snombre_persona ? beneficiario.snombre_persona : ''}
                                                                        ${beneficiario.tnombre_persona ? beneficiario.tnombre_persona : ''}` }}
                                </div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5  whitespace-nowrap w-px">
                                <div class="font-medium text-slate-800 text-center">
                                    {{ `${beneficiario.papellido_persona ? beneficiario.papellido_persona : ''}
                                                                        ${beneficiario.sapellido_persona ? beneficiario.sapellido_persona : ''}
                                                                        ${beneficiario.tapellido_persona ? beneficiario.tapellido_persona : ''}` }}
                                </div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5  whitespace-nowrap w-px">
                                <div class="max-h-[165px] overflow-y-auto scrollbar">
                                    <template v-for="(familiar, i) in beneficiario.familiar" :key="i">
                                        <div class="mb-2 text-center">
                                            <p class="text-[10pt]">
                                                <span class="font-medium"> </span>{{ familiar.nombre_familiar }}
                                            </p>
                                        </div>
                                        <template v-if="i < beneficiario.familiar.length - 1">
                                            <hr class="my-2 border-t border-gray-300">
                                        </template>
                                    </template>
                                </div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5  whitespace-nowrap w-px">
                                <div class="max-h-[165px] overflow-y-auto scrollbar">
                                    <template v-for="(familiar, i) in beneficiario.familiar" :key="i">
                                        <div class="mb-2 text-center">
                                            <p class="text-[10pt]">
                                                <span class="font-medium"></span>{{ familiar.parentesco.nombre_parentesco }}
                                            </p>
                                        </div>
                                        <template v-if="i < beneficiario.familiar.length - 1">
                                            <hr class="my-2 border-t border-gray-300">
                                        </template>
                                    </template>
                                </div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5  whitespace-nowrap w-px">
                                <div class="max-h-[165px] overflow-y-auto scrollbar">
                                    <template v-for="(familiar, i) in beneficiario.familiar" :key="i">
                                        <div class="mb-2 text-center">
                                            <p class="text-[10pt] text-emerald-500">
                                                <span class="font-medium"></span>{{ familiar.porcentaje_familiar }} %
                                            </p>
                                        </div>
                                        <template v-if="i < beneficiario.familiar.length - 1">
                                            <hr class="my-2 border-t border-gray-300">
                                        </template>
                                    </template>
                                </div>
                            </td>
                            <td class="first:pl-5 last:pr-5">
                                <div class="space-x-1">
                                    <DropDownOptions>
                                        <div class="flex hover:bg-gray-100 py-1 px-2 rounded cursor-pointer"
                                            @click.stop="dataBeneficiariosToSendModal = beneficiario; showModalBeneficiario = !showModalBeneficiario">
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
                                        <div class="flex hover:bg-gray-100 py-1 px-2 rounded cursor-pointer"
                                            @click.stop="dataBeneficiariosToSendModal = beneficiario; showModalCertificado = !showModalCertificado">
                                            <div class="w-8 text-blue-900">
                                                <span class="text-xs">
                                                    <svg class="w-6 h-6" viewBox="0 0 24.00 24.00" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        transform="matrix(-1, 0, 0, 1, 0, 0)">
                                                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round"
                                                            stroke-linejoin="round"></g>
                                                        <g id="SVGRepo_iconCarrier">
                                                            <path
                                                                d="M3 15H21M3 19H15M3 7H11M3 11H11M19.4 11H16.6C16.0399 11 15.7599 11 15.546 10.891C15.3578 10.7951 15.2049 10.6422 15.109 10.454C15 10.2401 15 9.96005 15 9.4V6.6C15 6.03995 15 5.75992 15.109 5.54601C15.2049 5.35785 15.3578 5.20487 15.546 5.10899C15.7599 5 16.0399 5 16.6 5H19.4C19.9601 5 20.2401 5 20.454 5.10899C20.6422 5.20487 20.7951 5.35785 20.891 5.54601C21 5.75992 21 6.03995 21 6.6V9.4C21 9.96005 21 10.2401 20.891 10.454C20.7951 10.6422 20.6422 10.7951 20.454 10.891C20.2401 11 19.9601 11 19.4 11Z"
                                                                stroke="#000000" stroke-width="1.008" stroke-linecap="round"
                                                                stroke-linejoin="round"></path>
                                                        </g>
                                                    </svg>
                                                </span>
                                            </div>
                                            <div class="font-semibold text-xs pl-2">Certificado de seguro coletivo de vida
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
                                    <a @click="getBeneficiarios(link.url)"
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
                                        <a @click="getBeneficiarios(link.url)"
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
                                <span class="cursor-pointer mt-2" v-else @click="getBeneficiarios(link.url)"
                                    :class="(link.active ? 'inline-flex items-center justify-center rounded-full leading-5 px-2 py-2 bg-white border border-slate-200 text-indigo-500 shadow-sm' : 'inline-flex items-center justify-center leading-5 px-2 py-2 text-slate-600 hover:text-indigo-500 border border-transparent')"><span
                                        class=" w-5">{{ link.label }}</span>
                                </span>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>

        <ModalBeneficiarios :showModal="showModalBeneficiario" :data-beneficiarios="dataBeneficiariosToSendModal"
            @cerrar-modal="showModalBeneficiario = false" :data-for-select="optionsParentescos"
            @actualizar-table-data="getBeneficiarios(lastUrl); showModalBeneficiario = false" />

        <ModalCertificadoDeVida :showModal="showModalCertificado" @cerrar-modal="showModalCertificado = false"
            :data-beneficiarios="dataBeneficiariosToSendModal" />
    </AppLayoutVue>
</template>

<script>
export default {

    data() {
        let sortOrders = {};
        let columns = [
            { width: "10%", label: "ID", name: "id_persona", type: "text" },
            { width: "20%", label: "Nombres", name: "collecNombre", type: "text" },
            { width: "20%", label: "Apellidos", name: "collecApellido", type: "text" },
            { width: "35%", label: "Nombre del familiar", name: "nombre_familiar", type: "text" },
            {
                width: "10%", label: "Parentesco", name: "id_parentesco", type: "select",
                options: [
                    { value: "1", label: "MADRE", "unico_parentesco": 1, "grado_parentesco": 1, "id_tipo_parentesco": 1, "estado_parentesco": 1 },
                    { value: "2", label: "PADRE", "unico_parentesco": 1, "grado_parentesco": 1, "id_tipo_parentesco": 1, "estado_parentesco": 1 },
                    { value: "3", label: "HIJA", "unico_parentesco": 0, "grado_parentesco": 1, "id_tipo_parentesco": 1, "estado_parentesco": 1 },
                    { value: "4", label: "HIJO", "unico_parentesco": 0, "grado_parentesco": 1, "id_tipo_parentesco": 1, "estado_parentesco": 1 },
                    { value: "5", label: "CONYUGE", "unico_parentesco": 1, "grado_parentesco": 1, "id_tipo_parentesco": 2, "estado_parentesco": 1 },
                    { value: "6", label: "ABUELA", "unico_parentesco": 0, "grado_parentesco": 2, "id_tipo_parentesco": 1, "estado_parentesco": 1 },
                    { value: "7", label: "ABUELO", "unico_parentesco": 0, "grado_parentesco": 2, "id_tipo_parentesco": 1, "estado_parentesco": 1 },
                    { value: "8", label: "BISABUELA", "unico_parentesco": 0, "grado_parentesco": 3, "id_tipo_parentesco": 1, "estado_parentesco": 1 },
                    { value: "9", label: "BISABUELO", "unico_parentesco": 0, "grado_parentesco": 3, "id_tipo_parentesco": 1, "estado_parentesco": 1 },
                    { value: "10", label: "HERMANA", "unico_parentesco": 0, "grado_parentesco": 2, "id_tipo_parentesco": 1, "estado_parentesco": 1 },
                    { value: "11", label: "HERMANO", "unico_parentesco": 0, "grado_parentesco": 2, "id_tipo_parentesco": 1, "estado_parentesco": 1 },
                    { value: "12", label: "NIETA", "unico_parentesco": 0, "grado_parentesco": 2, "id_tipo_parentesco": 1, "estado_parentesco": 1 },
                    { value: "13", label: "NIETO", "unico_parentesco": 0, "grado_parentesco": 2, "id_tipo_parentesco": 1, "estado_parentesco": 1 },
                    { value: "14", label: "BISNIETA", "unico_parentesco": 0, "grado_parentesco": 4, "id_tipo_parentesco": 1, "estado_parentesco": 1 },
                    { value: "15", label: "BISNIETO", "unico_parentesco": 0, "grado_parentesco": 4, "id_tipo_parentesco": 1, "estado_parentesco": 1 },
                    { value: "16", label: "TIA", "unico_parentesco": 0, "grado_parentesco": 3, "id_tipo_parentesco": 1, "estado_parentesco": 1 },
                    { value: "17", label: "TIO", "unico_parentesco": 0, "grado_parentesco": 3, "id_tipo_parentesco": 1, "estado_parentesco": 1 },
                    { value: "18", label: "PRIMA", "unico_parentesco": 0, "grado_parentesco": 4, "id_tipo_parentesco": 1, "estado_parentesco": 1 },
                    { value: "19", label: "PRIMO", "unico_parentesco": 0, "grado_parentesco": 4, "id_tipo_parentesco": 1, "estado_parentesco": 1 },
                    { value: "20", label: "SOBRINA", "unico_parentesco": 0, "grado_parentesco": 3, "id_tipo_parentesco": 1, "estado_parentesco": 1 },
                    { value: "21", label: "SOBRINO", "unico_parentesco": 0, "grado_parentesco": 3, "id_tipo_parentesco": 1, "estado_parentesco": 1 },
                    { value: "22", label: "SUEGRA", "unico_parentesco": 0, "grado_parentesco": 1, "id_tipo_parentesco": 2, "estado_parentesco": 1 },
                    { value: "23", label: "SUEGRO", "unico_parentesco": 0, "grado_parentesco": 1, "id_tipo_parentesco": 2, "estado_parentesco": 1 },
                    { value: "24", label: "NUERA", "unico_parentesco": 0, "grado_parentesco": 2, "id_tipo_parentesco": 2, "estado_parentesco": 1 },
                    { value: "25", label: "YERNO", "unico_parentesco": 0, "grado_parentesco": 2, "id_tipo_parentesco": 2, "estado_parentesco": 1 },
                    { value: "26", label: "CUÑADA", "unico_parentesco": 0, "grado_parentesco": 2, "id_tipo_parentesco": 2, "estado_parentesco": 1 },
                    { value: "27", label: "CUÑADO", "unico_parentesco": 0, "grado_parentesco": 2, "id_tipo_parentesco": 2, "estado_parentesco": 1 },
                    { value: "28", label: "MADASTRA", "unico_parentesco": 0, "grado_parentesco": 0, "id_tipo_parentesco": 1, "estado_parentesco": 1 },
                    { value: "29", label: "PADRASTRO", "unico_parentesco": 0, "grado_parentesco": 0, "id_tipo_parentesco": 1, "estado_parentesco": 1 },
                    { value: "30", label: "HIJASTRA", "unico_parentesco": 0, "grado_parentesco": 1, "id_tipo_parentesco": 2, "estado_parentesco": 1 },
                    { value: "31", label: "HIJASTRO", "unico_parentesco": 0, "grado_parentesco": 1, "id_tipo_parentesco": 2, "estado_parentesco": 1 },
                    { value: "32", label: "AMIGA", "unico_parentesco": 0, "grado_parentesco": 0, "id_tipo_parentesco": 1, "estado_parentesco": 1 },
                    { value: "33", label: "AMIGO", "unico_parentesco": 0, "grado_parentesco": 0, "id_tipo_parentesco": 1, "estado_parentesco": 1 },
                    { value: "34", label: "COMADRE", "unico_parentesco": 0, "grado_parentesco": 0, "id_tipo_parentesco": 1, "estado_parentesco": 1 },
                    { value: "35", label: "COMPADRE", "unico_parentesco": 0, "grado_parentesco": 0, "id_tipo_parentesco": 1, "estado_parentesco": 1 }
                ]
            },
            { width: "35%", label: "Porcentaje", name: "porcentaje_familiar", type: "text" },
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
            stateModal: false,
            beneficiarios: [],
            optionsParentescos: [],
            dataBeneficiariosToSendModal: [],
            showModalBeneficiario: false,
            showModalCertificado: false,
            links: [],
            lastUrl: "/beneficiarios",
            columns: columns,
            sortKey: "id_persona",
            sortOrders: sortOrders,
            perPage: ["10", "20", "30"],
            isLoadinRequest: false,
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
        async getBeneficiarios(url = "/beneficiarios") {
            this.lastUrl = url;
            this.tableData.draw++;
            this.isLoadinRequest = true;
            await axios.post(url, this.tableData).then((response) => {
                let data = response.data;
                if (this.tableData.draw == data.draw) {
                    this.links = data.data.links;
                    this.tableData.total = data.data.total;
                    this.pagination.total = data.data.total;
                    this.links[0].label = "Anterior";
                    this.links[this.links.length - 1].label = "Siguiente";

                    this.isLoadinRequest = false;
                    this.beneficiarios = data.data.data;
                    this.optionsParentescos = data.dataForSelect.parentesco
                    this.stateModal = false
                    this.beneficiarios.length > 0 ? this.empty_object = false : this.empty_object = true

                    //console.log(data.data.data);
                    console.log(this.optionsParentescos);

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
                this.getBeneficiarios();
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
                this.getBeneficiarios();
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
        this.getBeneficiarios()
    },
}
</script>

