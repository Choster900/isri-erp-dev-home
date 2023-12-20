<script setup>
import { Head } from "@inertiajs/vue3";
import AppLayoutVue from "@/Layouts/AppLayout.vue";
import Datatable from "@/Components-ISRI/Datatable.vue";
import ModalPermisosVue from '@/Components-ISRI/RRHH/ModalPermisos.vue';
import PermisoFormato026Vue from '@/Components-ISRI/RRHH/PermisoFormato026.vue';
import PermisoFormato012InternoVue from '@/Components-ISRI/RRHH/PermisoFormato012Interno.vue';
import PermisoFormato012Vue from '@/Components-ISRI/RRHH/PermisoFormato012.vue';

import moment from 'moment';

import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';

import axios from 'axios';

</script>

<template>
    <Head title="Proceso - Permisos" />
    <AppLayoutVue nameSubModule="RRHH - Permisos">
        <div v-if="isLoading"
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
                    :inputsToValidate="inputsToValidate" @sort="sortBy" @datos-enviados="handleData($event)"
                    @execute-search="getJobPermissions()">
                    <tbody class="text-sm divide-y divide-slate-200">
                        <tr v-for="permission in jobPermissions" :key="permission.id_permiso">
                            <td class="px-2 first:pl-5 last:pr-5">
                                <div class="font-medium text-slate-800 flex items-center justify-center min-h-[50px]">
                                    {{ permission.id_permiso }}
                                </div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5">
                                <div class="font-medium text-slate-800 text-center">
                                    {{ permission.codigo_tipo_permiso }}
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
                                        class="font-medium text-cyan-600">Creado</span>
                                    <span v-else-if="permission.id_estado_permiso === 2"
                                        class="font-medium text-blue-700">En proceso</span>
                                    <span v-else-if="permission.id_estado_permiso === 3"
                                        class="font-medium text-green-500">Aprobado</span>
                                    <span v-else-if="permission.id_estado_permiso === 4"
                                        class="font-medium text-red-500">Denegado</span>
                                    <span v-else-if="permission.id_estado_permiso === 5"
                                        class="font-medium text-red-500">Eliminado</span>
                                </div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5  whitespace-nowrap w-px">
                                <div class="space-x-1 text-center">
                                    <DropDownOptions>
                                        <div class="flex hover:bg-gray-100 py-1 px-2 rounded cursor-pointer"
                                            v-if="permission.estado_permiso == 1" @click="viewPermissionFormat(permission)">
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
                                            v-if="permits.actualizar == 1 && permission.id_estado_permiso == 1"
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
                                            v-if="permits.actualizar == 1 && permission.id_estado_permiso == 1"
                                            @click="sendPermission(permission)">
                                            <div class="w-8 text-blue-900">
                                                <span class="text-xs">
                                                    <svg width="24" height="24" xmlns="http://www.w3.org/2000/svg"
                                                        fill="none" stroke="currentColor" stroke-width="1.5">
                                                        <path d="M20 4L3 11L10 14L13 21L20 4Z" stroke-linejoin="round" />
                                                    </svg>
                                                </span>
                                            </div>
                                            <div class="font-semibold">Enviar</div>
                                        </div>
                                        <div class="flex hover:bg-gray-100 py-1 px-2 rounded cursor-pointer"
                                            @click="deletePermission(permission)"
                                            v-if="(permits.eliminar == 1 && permission.id_estado_permiso === 1)">
                                            <div class="w-8 text-red-900">
                                                <svg viewBox="0 0 24 24" fill="none" class="w-6 h-6"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                                        d="M17 5V4C17 2.89543 16.1046 2 15 2H9C7.89543 2 7 2.89543 7 4V5H4C3.44772 5 3 5.44772 3 6C3 6.55228 3.44772 7 4 7H5V18C5 19.6569 6.34315 21 8 21H16C17.6569 21 19 19.6569 19 18V7H20C20.5523 7 21 6.55228 21 6C21 5.44772 20.5523 5 20 5H17ZM15 4H9V5H15V4ZM17 7H7V18C7 18.5523 7.44772 19 8 19H16C16.5523 19 17 18.5523 17 18V7Z"
                                                        fill="currentColor" />
                                                    <path d="M9 9H11V17H9V9Z" fill="currentColor" />
                                                    <path d="M13 9H15V17H13V9Z" fill="currentColor" />
                                                </svg>
                                            </div>
                                            <div class="font-semibold">
                                                Cancelar
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
                                        <a @click="link.url ? getJobPermissions(link.url) : ''" class=" btn bg-white border-slate-200 hover:border-slate-300 cursor-pointer
                                  text-indigo-500">
                                            &lt;-<span class="hidden sm:inline">&nbsp;Anterior</span>
                                        </a>
                                    </div>
                                </span>
                                <span v-else-if="(link.label == 'Siguiente')"
                                    :class="(link.active ? 'inline-flex items-center justify-center rounded-full leading-5 px-2 py-2 bg-white border border-slate-200 text-indigo-500 shadow-sm' : 'inline-flex items-center justify-center leading-5 px-2 py-2 text-slate-600 hover:text-indigo-500 border border-transparent')">
                                    <div class="flex-1 text-right ml-2">
                                        <a @click="link.url ? getJobPermissions(link.url) : ''" class=" btn bg-white border-slate-200 hover:border-slate-300 cursor-pointer
                                  text-indigo-500">
                                            <span class="hidden sm:inline">Siguiente&nbsp;</span>-&gt;
                                        </a>
                                    </div>
                                </span>
                                <span class="cursor-pointer" v-else
                                    :class="(link.active ? 'inline-flex items-center justify-center rounded-full leading-5 px-2 py-2 bg-white border border-slate-200 text-indigo-500 shadow-sm' : 'inline-flex items-center justify-center leading-5 px-2 py-2 text-slate-600 hover:text-indigo-500 border border-transparent')"><span
                                        class=" w-5" @click="link.url ? getJobPermissions(link.url) : ''">{{ link.label }}</span>
                                </span>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>

        <ModalPermisosVue :showModalJobPermissions="showModalJobPermissions" :modalData="modalData" :permits="permits"
            @cerrar-modal="showModalJobPermissions = false" @get-table="getJobPermissions(tableData.currentPage)" />
        <PermisoFormato026Vue :viewPermission026="viewPermission026" :permissionToPrint="permissionToPrint" :limite="limite"
            :stages="stages" :permits="permits" @cerrar-modal="viewPermission026 = false" :showOptions="false"
            @get-table="getJobPermissions(tableData.currentPage)" />
        <PermisoFormato012InternoVue v-if="viewPermission012I" :viewPermission012I="viewPermission012I" :permissionToPrint="permissionToPrint"
            :stages="stages" :permits="permits" @cerrar-modal="viewPermission012I = false" :showOptions="false"
            @get-table="getJobPermissions(tableData.currentPage)" />
        <PermisoFormato012Vue v-if="viewPermission012" :viewPermission012="viewPermission012" :permissionToPrint="permissionToPrint" :stages="stages"
            :permits="permits" @cerrar-modal="viewPermission012 = false" :showOptions="false"
            @get-table="getPermissionRequests(tableData.currentPage)" />

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
            { width: "10%", label: "Tiempo", name: "horas", type: "text" },
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
            stages: [],
            viewPermission026: false,
            viewPermission012: false,
            viewPermission012I: false,
            limite: '',
            permissionToPrint: [],
            isLoading: false,
            emptyObject: false,
            //Data for datatable
            jobPermissions: [],
            inputsToValidate: [
                { inputName: 'horas', number: true, limit: 3 },
                { inputName: 'id_permiso', number: true, limit: 4 },
                { inputName: 'nombre_tipo_permiso', number: false, limit: 14 },
                { inputName: 'pnombre_persona', number: false, limit: 20 },
            ],
            //Data for modal
            showModalJobPermissions: false,
            modalData: [],
            permits: [],
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
        async sendPermission(permission) {
            const res = await this.getPermissionDataById(permission);
            const updatedPermission = res.permiso;
            this.$swal.fire({
                title: "Enviar permiso para aprobación.",
                text: "Una vez enviado no podrás modificarlo. ¿Estas seguro?",
                icon: "question",
                iconHtml: "❓",
                confirmButtonText: 'Si, enviar.',
                confirmButtonColor: "#001b47",
                cancelButtonText: "Cancelar",
                showCancelButton: true,
                showCloseButton: true
            }).then(async (result) => {
                if (result.isConfirmed) {
                    const format = this.getFormatToPrint(updatedPermission);
                    let id_tipo_flujo;
                    if (format === 1 || format === 2) {
                        id_tipo_flujo = updatedPermission.plaza_asignada.dependencia.dep_id_dependencia === 1 ? 1 : 3;
                    } else if (format === 3) {
                        id_tipo_flujo = updatedPermission.plaza_asignada.dependencia.dep_id_dependencia === 1 ? 2 : 4;
                    }

                    try {
                        this.isLoading = true;  // Activar el estado de carga
                        const response = await axios.post("/send-permission", {
                            id: updatedPermission.id_permiso,
                            tipo_flujo: id_tipo_flujo
                        });
                        this.$swal.fire({
                            text: response.data.mensaje,
                            icon: 'success',
                            timer: 5000
                        })
                        this.getJobPermissions(this.tableData.currentPage);
                        return response
                    } catch (errors) {
                        if (errors.response.data.logical_error) {
                            this.showToast(toast.error, errors.response.data.logical_error);
                            this.getJobPermissions(this.tableData.currentPage);
                        } else {
                            this.manageError(errors, this)
                        }
                    } finally {
                        this.isLoading = false;  // Desactivar el estado de carga
                    }
                }
            })
        },
        async viewPermissionFormat(permission) {
            const res = await this.getPermissionDataById(permission);
            const updatedPermission = res.permiso;
            this.stages = res.etapas
            this.permissionToPrint = updatedPermission
            const format = this.getFormatToPrint(updatedPermission);
            switch (format) {
                //No marcacion
                case 1:
                    this.viewPermission026 = true
                    this.limite = res.limite
                    break;
                //F012 Control interno
                case 2:
                    this.viewPermission012I = true
                    break;
                //F012
                case 3:
                    this.viewPermission012 = true
                    break;
                default:
                    console.log('Another action');
            }
        },
        getFormatToPrint(permission) {
            //Personal con goce 
            if (permission.id_tipo_permiso === 1) {
                return 2;
            }
            //No marcacion
            if (permission.id_tipo_permiso === 6) {
                return 1;
            }
            //Todos los permisos por horas
            if (!permission.fecha_fin_permiso) {
                return 2;
            }
            //Personal sin goce mayor o igual a un día
            if (permission.id_tipo_permiso === 2) {
                return 3;
            }
            //Cantidad de dias
            const days = this.workDaysBetween(permission.fecha_inicio_permiso, permission.fecha_fin_permiso);
            //Enfermedad con o sin goce mayor a 3 días
            if (permission.id_tipo_permiso === 3 || permission.id_tipo_permiso === 4) {
                return days > 3 ? 3 : 2;
            }
            //Mision oficial mayor a 5 dias
            if (permission.id_tipo_permiso === 5) {
                return days > 5 ? 3 : 2;
            }
            return -1; // Valor por defecto si ninguno de los casos anteriores se cumple
        },
        workDaysBetween(date1, date2) {
            const startDateFormated = moment(date1, 'YYYY/MM/DD').toDate();
            const endDateFormated = moment(date2, 'YYYY/MM/DD').toDate();

            let currentDate = new Date(startDateFormated);
            let daysDifference = 0;

            while (currentDate <= endDateFormated) {
                daysDifference++;
                currentDate.setDate(currentDate.getDate() + 1);
            }

            return daysDifference;
        },
        async getPermissionDataById(permiso) {
            try {
                this.isLoading = true;  // Activar el estado de carga
                const response = await axios.post('/get-permission-info-by-id', permiso);
                return response.data
            } catch (errors) {
                if (errors.response.status === 422) {
                    if (errors.response.data.logical_error) {
                        this.showToast(toast.error, errors.response.data.logical_error);
                    }
                } else {
                    this.manageError(errors, this)
                }
            } finally {
                this.isLoading = false;  // Desactivar el estado de carga
                this.getJobPermissions(this.tableData.currentPage)
            }
        },
        showDate(startDate, endDate) {
            if (endDate) {
                return moment(startDate).format('DD/MM/YYYY') + ' al ' + moment(endDate).format('DD/MM/YYYY')
            } else {
                return moment(startDate).format('DD/MM/YYYY')
            }
        },
        showTime(permission) {
            if (permission.id_tipo_permiso === 6 || (permission.id_tipo_permiso === 5 && !permission.fecha_fin_permiso)) {
                return 'N/A'
            } else {
                if (permission.fecha_fin_permiso) {
                    const startDateFormated = moment(permission.fecha_inicio_permiso, 'YYYY/MM/DD').toDate();
                    const endDateFormated = moment(permission.fecha_fin_permiso, 'YYYY/MM/DD').toDate();

                    let currentDate = new Date(startDateFormated);
                    let daysDifference = 0;

                    while (currentDate <= endDateFormated) {
                        daysDifference++;
                        currentDate.setDate(currentDate.getDate() + 1);
                    }

                    const resultInMinutes = daysDifference * 8 * 60

                    const hours = Math.floor(resultInMinutes / 60);
                    const minutes = resultInMinutes % 60;
                    const formatResult = `${hours}:${minutes}`
                    return this.formatTime(formatResult)

                } else {
                    if (permission.hora_salida_permiso) {
                        const [endHours, endMinutes] = permission.hora_salida_permiso.split(':').map(Number);
                        const [startHours, startMinutes] = permission.hora_entrada_permiso.split(':').map(Number);
                        const result = ((endHours * 60) + endMinutes) - ((startHours * 60) + startMinutes)

                        const hours = Math.floor(result / 60);
                        const minutes = result % 60;
                        const formatResult = `${hours}:${minutes}`
                        return this.formatTime(formatResult)
                    } else {
                        return '0'
                    }
                }
            }
        },
        formatTime(time) {
            if (!time) {
                return '0'
            } else {
                const [hours, minutes] = time.split(':').map(Number);
                return `${hours > 0 ? hours + ' H. ' : ''} ${minutes > 0 ? minutes + ' min.' : ''}`
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
                    this.links = data.data.links;
                    this.tableData.total = data.data.total;
                    this.links[0].label = "Anterior";
                    this.links[this.links.length - 1].label = "Siguiente";
                    this.jobPermissions = data.data.data;
                    this.jobPermissions.length > 0 ? this.emptyObject = false : this.emptyObject = true
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
            const data = Object.values(this.tableData.search);
            if (data.every(error => error === '')) {
                this.getJobPermissions()
            }
        },
        deletePermission(permission) {
            this.$swal.fire({
                title: 'Eliminar ' + permission.nombre_tipo_permiso,
                text: "¿Estas seguro?",
                icon: "question",
                iconHtml: "❓",
                confirmButtonText: 'Si, Aceptar.',
                confirmButtonColor: "#DC2626",
                cancelButtonText: "Cancelar",
                showCancelButton: true,
                showCloseButton: true
            }).then((result) => {
                if (result.isConfirmed) {
                    this.isLoading = true;
                    axios.post("/delete-permission", {
                        id: permission.id_permiso,
                        status: permission.id_estado_permiso
                    })
                        .then((response) => {
                            this.$swal.fire({
                                text: response.data.mensaje,
                                icon: 'success',
                                timer: 5000
                            })
                            this.getJobPermissions(this.tableData.currentPage);
                        })
                        .catch((errors) => {
                            if (errors.response.data.logical_error) {
                                this.showToast(toast.error, errors.response.data.logical_error);
                                this.getJobPermissions(this.tableData.currentPage);
                            } else {
                                this.manageError(errors, this)
                            }
                        })
                        .finally(() => {
                            this.isLoading = false;
                        });
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
}
</style>