<script setup>
import { Head } from "@inertiajs/vue3";
import AppLayoutVue from "@/Layouts/AppLayout.vue";
import Datatable from "@/Components-ISRI/Datatable.vue";;
import axios from 'axios';
import ModalBeneficiarios from '@/Components-ISRI/RRHH/ModalBeneficiarios.vue';

</script>

<template>
    <Head title="RRHH - Gestion Empleados" />
    <AppLayoutVue nameSubModule="RRHH - Empleados">
        <div class="sm:flex sm:justify-end sm:items-center mb-2">
            <div class="grid grid-flow-col sm:auto-cols-max sm:justify-end gap-2">
                <GeneralButton
                    @click="dataBeneficiariosToSendModal = []; showModalBeneficiario = !showModalBeneficiario"
                   color="bg-green-700  hover:bg-green-800" text="Agregar Empleado"
                    icon="add" />
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
                        beneficiarios.length
                    }}</span></h2>
                </div>
            </header>

            <div class="overflow-x-auto">
                <datatable :columns="columns" :sortKey="sortKey" :sortOrders="sortOrders" :searchButton="true"
                    @sort="sortBy" @datos-enviados="handleData($event)" @execute-search="getBeneficiarios()">
                    <tbody class="text-sm divide-y divide-slate-200">
                        <tr v-for="beneficiario in beneficiarios" :key="beneficiario.id_persona">
                            <td class="px-2 first:pl-5 last:pr-5  whitespace-nowrap w-px">
                                <div class="font-medium text-slate-800 text-center ">{{ beneficiario.id_persona }}</div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5  whitespace-nowrap w-px">
                                <div class="font-medium text-slate-800 text-center">
                                    {{ `${beneficiario.pnombre_persona ? beneficiario.pnombre_persona : ''}
                                                                        ${beneficiario.snombre_persona ? beneficiario.snombre_persona : ''}
                                                                        ${beneficiario.tapellido_persona ? beneficiario.tapellido_persona : ''}` }}
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
                                    </DropDownOptions>
                                </div>
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
                                <span class="cursor-pointer" v-else
                                    :class="(link.active ? 'inline-flex items-center justify-center rounded-full leading-5 px-2 py-2 bg-white border border-slate-200 text-indigo-500 shadow-sm' : 'inline-flex items-center justify-center leading-5 px-2 py-2 text-slate-600 hover:text-indigo-500 border border-transparent')"><span
                                        class=" w-5" @click="getBeneficiarios(link.url)">{{ link.label }}</span>
                                </span>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>

        <ModalBeneficiarios :showModal="showModalBeneficiario" :data-beneficiarios="dataBeneficiariosToSendModal"
            @cerrar-modal="showModalBeneficiario = false"
            @actualizar-table-data="getBeneficiarios(lastUrl); showModalBeneficiario = false" />

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
                    { value: '1', label: 'MADRE', unico_parentesco: 1 },
                    { value: '2', label: 'PADRE', unico_parentesco: 1 },
                    { value: '3', label: 'ABUELO/A', unico_parentesco: 0 },
                    { value: '4', label: 'BISABUELO/A', unico_parentesco: 0 },
                    { value: '5', label: 'HERMANO/A', unico_parentesco: 0 },
                    { value: '6', label: 'CONYUGE', unico_parentesco: 0 },
                    { value: '7', label: 'HIJO/A', unico_parentesco: 0 },
                    { value: '8', label: 'NIETO/A', unico_parentesco: 0 },
                    { value: '9', label: 'BISNIETO/A', unico_parentesco: 0 },
                    { value: '10', label: 'TIO/A', unico_parentesco: 0 },
                    { value: '11', label: 'PRIMO/A', unico_parentesco: 0 },
                    { value: '12', label: 'SOBRINO/A', unico_parentesco: 0 },
                    { value: '13', label: 'SUEGRO/A', unico_parentesco: 0 },
                    { value: '14', label: 'NUERA', unico_parentesco: 0 },
                    { value: '15', label: 'YERNO', unico_parentesco: 0 },
                    { value: '16', label: 'CUÑADO/A', unico_parentesco: 0 },
                    { value: '17', label: 'PADRASTRO', unico_parentesco: 0 },
                    { value: '18', label: 'MADASTRA', unico_parentesco: 0 },
                    { value: '19', label: 'HIJASTRO/A', unico_parentesco: 0 },
                    { value: '20', label: 'AMIGO/A', unico_parentesco: 0 },
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
            dataBeneficiariosToSendModal: [],
            showModalBeneficiario: false,
            links: [],
            lastUrl: "/beneficiarios",
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
        async getBeneficiarios(url = "/beneficiarios") {
            this.lastUrl = url;
            this.tableData.draw++;
            await axios.post(url, this.tableData).then((response) => {
                let data = response.data;
                if (this.tableData.draw == data.draw) {
                    this.links = data.data.links;
                    this.pagination.total = data.data.total;
                    this.links[0].label = "Anterior";
                    this.links[this.links.length - 1].label = "Siguiente";

                    // Como eloquent no me filtra los familiares que estan desactivados si no que me trae todos por igual
                    const filteredData = data.data.data.map((obj) => {
                        // Tenemos que hacer esto
                        const filteredFamiliar = obj.familiar.filter(
                            (familiar) => familiar.estado_familiar === 1
                        );
                        return { ...obj, familiar: filteredFamiliar };
                    });

                    console.log(filteredData);

                    this.beneficiarios = filteredData;
                    this.stateModal = false
                    this.beneficiarios.length > 0 ? this.empty_object = false : this.empty_object = true



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

