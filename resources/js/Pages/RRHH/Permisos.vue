<script setup>
import Modal from "@/Components/Modal.vue";
import { Head } from "@inertiajs/vue3";
import AppLayoutVue from "@/Layouts/AppLayout.vue";
import Datatable from "@/Components-ISRI/Datatable.vue";
import ModalVue from "@/Components-ISRI/AllModal/BasicModal.vue";
import ModalPermisosVue from '@/Components-ISRI/RRHH/ModalPermisos.vue';
import moment from 'moment';

import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';

import axios from 'axios';

</script>

<template>
    <Head title="Proceso - Permisos" />
    <AppLayoutVue nameSubModule="RRHH - Permisos">
        <div class="sm:flex sm:justify-end sm:items-center mb-2">
            <div class="grid grid-flow-col sm:auto-cols-max sm:justify-end gap-2">
                <GeneralButton @click="addJobPermission()" v-if="permits.insertar == 1"
                    color="bg-green-700  hover:bg-green-800" text="Agregar Permiso" icon="add" />
            </div>
        </div>
        <div class="bg-white shadow-lg rounded-sm border border-slate-200 relative">
            <header class="px-5 py-4">
                <div class="mb-4 md:flex flex-row justify-items-start">
                    <div class="mb-4 md:mr-2 md:mb-0 basis-1/4">
                        <div class="relative flex h-8 w-full flex-row-reverse div-multiselect">
                            <Multiselect v-model="tableData.length" @select="getJobPermissions()" :options="perPage"
                                :searchable="true" placeholder="Cantidad a mostrar" />
                            <LabelToInput icon="list2" />
                        </div>
                    </div>
                    <h2 class="font-semibold text-slate-800 pt-1">Permisos: <span class="text-slate-400 font-medium">{{
                        tableData.total
                    }}</span></h2>
                </div>
            </header>

            <div class="overflow-x-auto">
                <datatable :columns="columns" :sortKey="sortKey" :sortOrders="sortOrders" :searchButton="true"
                    @sort="sortBy" @datos-enviados="handleData($event)" @execute-search="getJobPermissions()">
                    <tbody class="text-sm divide-y divide-slate-200">
                        <tr v-for="permission in jobPermissions" :key="permission.id_permiso">
                            <td class="px-2 first:pl-5 last:pr-5">
                                <div class="font-medium text-slate-800 flex items-center justify-center min-h-[50px]">
                                    {{ permission.id_permiso }}
                                </div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5">
                                <div class="font-medium text-slate-800 text-center">
                                    {{ permission.nombre_tipo_permiso }}
                                </div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5">
                                <div class="font-medium text-slate-800 text-center">
                                    {{ permission.pnombre_persona }} {{ permission.snombre_persona }}
                                    {{ permission.tnombre_persona }} {{ permission.papellido_persona }}
                                    {{ permission.sapellido_persona }} {{ permission.tapellido_persona }}
                                </div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5">
                                <div class="font-medium text-slate-800 text-center">
                                    {{ showDate(permission.fecha_inicio_permiso, permission.fecha_fin_permiso) }}
                                </div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5">
                                <div class="font-medium text-slate-800 text-center">
                                    {{ showTime(permission) }}
                                </div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5  whitespace-nowrap w-px">
                                <div class="font-medium text-center">
                                    <span v-if="permission.id_estado_permiso === 1"
                                        class="font-medium text-cyan-500">Creado</span>
                                    <span v-else-if="permission.id_estado_permiso === 2"
                                        class="font-medium text-green-500">Aprobado</span>
                                    <span v-else-if="permission.id_estado_permiso === 3"
                                        class="font-medium text-orange-500">Denegado</span>
                                    <span v-else-if="permission.id_estado_permiso === 4"
                                        class="font-medium text-red-500">Eliminado</span>
                                </div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5  whitespace-nowrap w-px">
                                <div class="space-x-1 text-center">
                                    <DropDownOptions>
                                        <div class="flex hover:bg-gray-100 py-1 px-2 rounded cursor-pointer"
                                            v-if="permission.estado_permiso == 1" @click="viewPermission(permission)">
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
                                            v-if="permits.actualizar == 1 && permission.estado_permiso == 1 && permission.id_estado_permiso == 1"
                                            @click="editJobPermission(permission)">
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
                                            @click="changeStatusJobPosition(permission)" v-if="permits.eliminar == 1">
                                            <div class="w-8 text-red-900">
                                                <svg class="text-xs" width="20" height="20" viewBox="0 0 97.994 97.994"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path :fill="permission.estado_permiso == 1 ? '#991B1B' : '#166534'"
                                                        d="M97.155,9.939c-0.582-0.416-1.341-0.49-1.991-0.193l-10.848,4.935C74.08,5.29,60.815,0.118,46.966,0.118c-15.632,0-30.602,6.666-41.07,18.289c-0.359,0.399-0.543,0.926-0.51,1.461c0.033,0.536,0.28,1.036,0.686,1.388l11.301,9.801c0.818,0.711,2.055,0.639,2.787-0.162c6.866-7.512,16.636-11.821,26.806-11.821c6.135,0,12.229,1.584,17.622,4.583l-7.826,3.561c-0.65,0.296-1.095,0.916-1.163,1.627c-0.069,0.711,0.247,1.405,0.828,1.82l34.329,24.52c0.346,0.246,0.753,0.373,1.163,0.373c0.281,0,0.563-0.06,0.828-0.181c0.65-0.296,1.095-0.916,1.163-1.627l4.075-41.989C98.053,11.049,97.737,10.355,97.155,9.939z" />
                                                    <path :fill="permission.estado_permiso == 1 ? '#991B1B' : '#166534'"
                                                        d="M80.619,66.937c-0.819-0.709-2.055-0.639-2.787,0.162c-6.866,7.514-16.638,11.822-26.806,11.822c-6.135,0-12.229-1.584-17.622-4.583l7.827-3.561c0.65-0.296,1.094-0.916,1.163-1.628c0.069-0.711-0.247-1.404-0.828-1.819L7.237,42.811c-0.583-0.416-1.341-0.49-1.991-0.193c-0.65,0.296-1.094,0.916-1.163,1.627L0.009,86.233c-0.069,0.712,0.247,1.406,0.828,1.822c0.583,0.416,1.341,0.488,1.991,0.192l10.848-4.935c10.237,9.391,23.502,14.562,37.351,14.562c15.632,0,30.602-6.666,41.07-18.289c0.358-0.398,0.543-0.926,0.51-1.461c-0.033-0.536-0.28-1.036-0.687-1.388L80.619,66.937z" />
                                                </svg>
                                            </div>
                                            <div class="font-semibold">
                                                {{ permission.estado_permiso ? 'Desactivar' : 'Activar' }}
                                            </div>
                                        </div>
                                    </DropDownOptions>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </datatable>

            </div>
            <div v-if="emptyObject" class="flex text-center py-2">
                <p class="font-semibold text-red-500 text-[16px]" style="margin: 0 auto; text-align: center;">No se
                    encontraron registros.</p>
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
                                        <a @click="page != 1 ? getJobPermissions(link.url) : ''" class=" btn bg-white border-slate-200 hover:border-slate-300 cursor-pointer
                                  text-indigo-500">
                                            &lt;-<span class="hidden sm:inline">&nbsp;Anterior</span>
                                        </a>
                                    </div>
                                </span>
                                <span v-else-if="(link.label == 'Siguiente')"
                                    :class="(link.active ? 'inline-flex items-center justify-center rounded-full leading-5 px-2 py-2 bg-white border border-slate-200 text-indigo-500 shadow-sm' : 'inline-flex items-center justify-center leading-5 px-2 py-2 text-slate-600 hover:text-indigo-500 border border-transparent')">
                                    <div class="flex-1 text-right ml-2">
                                        <a @click="hasNext ? getJobPermissions(link.url) : ''" class=" btn bg-white border-slate-200 hover:border-slate-300 cursor-pointer
                                  text-indigo-500">
                                            <span class="hidden sm:inline">Siguiente&nbsp;</span>-&gt;
                                        </a>
                                    </div>
                                </span>
                                <span class="cursor-pointer" v-else
                                    :class="(link.active ? 'inline-flex items-center justify-center rounded-full leading-5 px-2 py-2 bg-white border border-slate-200 text-indigo-500 shadow-sm' : 'inline-flex items-center justify-center leading-5 px-2 py-2 text-slate-600 hover:text-indigo-500 border border-transparent')"><span
                                        class=" w-5" @click="getJobPermissions(link.url)">{{ link.label }}</span>
                                </span>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>

        <ModalPermisosVue :showModalJobPermissions="showModalJobPermissions" :modalData="modalData" :permits="permits"
            @cerrar-modal="showModalJobPermissions = false" @get-table="getJobPermissions(tableData.currentPage)" />

    </AppLayoutVue>
</template>

<script>
export default {
    created() {
        this.getPermissions(this)
        this.getJobPermissions()
    },
    data() {
        let sortOrders = {};
        let columns = [
            { width: "10%", label: "Id", name: "id_permiso", type: "text" },
            { width: "25%", label: "Tipo permiso", name: "nombre_tipo_permiso", type: "text" },
            { width: "30%", label: "Empleado", name: "pnombre_persona", type: "text" },
            { width: "15%", label: "Fechas", name: "fecha_inicio_permiso", type: "date" },
            { width: "10%", label: "Tiempo", name: "fecha_fin_permiso", type: "text" },
            {
                width: "10%", label: "Estado", name: "id_estado_permiso", type: "select",
                options: [
                    { value: "1", label: "Creado" },
                    { value: "2", label: "Aprobado" },
                    { value: "3", label: "Denegado" },
                    { value: "4", label: "Eliminado" },
                ]
            },
            { width: "10%", label: "Acciones", name: "Acciones" },
        ];
        columns.forEach((column) => {
            if (column.name === 'id_permiso')
                sortOrders[column.name] = 1;
            else
                sortOrders[column.name] = -1;
        });
        return {
            emptyObject: false,
            //Data for datatable
            jobPermissions: [],
            //Data for modal
            showModalJobPermissions: false,
            modalData: [],
            permits: [],
            budget_accounts: [],
            dependencies: [],
            financing_sources: [],
            //vars to validate pages
            hasNext: false,
            page: '',
            //Until here 
            links: [],
            columns: columns,
            sortKey: "id_permiso",
            sortOrders: sortOrders,
            perPage: ["10", "20", "30"],
            tableData: {
                currentPage: '',
                draw: 0,
                length: 5,
                search: "",
                column: 0,
                dir: "desc",
                total: "",
                execute: ''
            },
        }
    },
    methods: {
        showDate(startDate, endDate) {
            if (endDate) {
                return moment(startDate).format('DD/MM/YYYY') + ' al ' + moment(endDate).format('DD/MM/YYYY')
            } else {
                return moment(startDate).format('DD/MM/YYYY')
            }
        },
        showTime(permission) {
            if (permission.id_tipo_permiso == 6 || permission.id_tipo_permiso == 5) {
                return 'N/A'
            } else {
                if (permission.fecha_fin_permiso && permission.fecha_inicio_permiso) {
                    const fechaInicio = moment(permission.fecha_inicio_permiso);
                    const fechaFin = moment(permission.fecha_fin_permiso);

                    const diferenciaEnDias = fechaFin.diff(fechaInicio, 'days');

                    return (diferenciaEnDias + 1) * 8 + ' H.';
                } else if (permission.hora_entrada_permiso && permission.hora_salida_permiso) {
                    const horaEntrada = moment(permission.hora_entrada_permiso, 'HH:mm');
                    const horaSalida = moment(permission.hora_salida_permiso, 'HH:mm');

                    const diferenciaEnHoras = horaSalida.diff(horaEntrada, 'hours');
                    const diferenciaEnMinutos = horaSalida.diff(horaEntrada, 'minutes') % 60;

                    if (diferenciaEnHoras === 0) {
                        return `${diferenciaEnMinutos} min.`;
                    } else if (diferenciaEnMinutos === 0) {
                        return `${diferenciaEnHoras} H.`;
                    } else {
                        return `${diferenciaEnHoras} H. ${diferenciaEnMinutos} min.`;
                    }
                } else {
                    return 'N/A.';
                }
            }
        },
        editJobPermission(permission) {
            this.modalData = permission
            this.showModalJobPermissions = true
        },
        addJobPermission() {
            this.modalData = []
            this.showModalJobPermissions = true
        },
        async getJobPermissions(url = "/job-permissions") {
            this.tableData.draw++;
            this.tableData.currentPage = url
            this.tableData.execute = this.permits.ejecutar
            await axios.post(url, this.tableData).then((response) => {
                let data = response.data;
                if (this.tableData.draw == data.draw) {
                    this.page = data.data.current_page
                    this.hasNext = data.data.current_page !== data.data.last_page;
                    this.links = data.data.links;
                    this.tableData.total = data.data.total;
                    this.links[0].label = "Anterior";
                    this.links[this.links.length - 1].label = "Siguiente";
                    this.jobPermissions = data.data.data;
                    this.jobPermissions.length > 0 ? this.emptyObject = false : this.emptyObject = true
                    console.log(this.jobPermissions);
                }
            }).catch((errors) => {
                this.manageError(errors, this)
            })
        },
        sortBy(key) {
            if (key != "Acciones") {
                this.sortKey = key;
                this.sortOrders[key] = this.sortOrders[key] * -1;
                this.tableData.column = this.getIndex(this.columns, "name", key);
                this.tableData.dir = this.sortOrders[key] === 1 ? "asc" : "desc";
                this.getJobPermissions();
            }
        },
        getIndex(array, key, value) {
            return array.findIndex((i) => i[key] == value);
        },
        handleData(myEventData) {
            this.tableData.search = myEventData;
            const data = Object.values(myEventData);
            if (data.every(error => error === '')) {
                this.getJobPermissions()
            }
        },
        getDependencieCode(jobPosition) {
            if (jobPosition.plaza_asignada_activa) {
                return jobPosition.plaza_asignada_activa.dependencia.codigo_dependencia
            } else {
                return 'N/Asign.'
            }
        },
        changeStatusJobPosition(position) {
            let msg
            position.estado_det_plaza == 1 ? msg = "Desactivar" : msg = "Activar"
            this.$swal.fire({
                title: msg + ' plaza codigo: <br>' + position.codigo_det_plaza + '.',
                text: "¿Estas seguro?",
                icon: "question",
                iconHtml: "❓",
                confirmButtonText: 'Si, ' + msg,
                confirmButtonColor: "#001b47",
                cancelButtonText: "Cancelar",
                showCancelButton: true,
                showCloseButton: true
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.post("/change-status-job-position-det", {
                        id: position.id_det_plaza,
                        status: position.estado_det_plaza
                    })
                        .then((response) => {
                            this.$swal.fire({
                                text: response.data.mensaje,
                                icon: 'success',
                                timer: 5000
                            })
                            this.getJobPositions(this.tableData.currentPage);
                        })
                        .catch((errors) => {
                            if (errors.response.data.logical_error) {
                                toast.error(
                                    errors.response.data.logical_error,
                                    {
                                        autoClose: 5000,
                                        position: "top-right",
                                        transition: "zoom",
                                        toastBackgroundColor: "white",
                                    }
                                );
                            } else {
                                this.manageError(errors, this)
                            }
                        })
                }
            })
        }

    },
    computed: {
    }
}
</script>

<style>
.td-data-table {
    max-width: 100px;
    white-space: nowrap;
    height: 50px;
}

.ellipsis {
    overflow: hidden;
    text-overflow: ellipsis;
}</style>