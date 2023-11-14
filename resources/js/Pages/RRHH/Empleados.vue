<script setup>
import { Head } from "@inertiajs/vue3";
import AppLayoutVue from "@/Layouts/AppLayout.vue";
import Datatable from "@/Components-ISRI/Datatable.vue";
import ModalEmployeesVue from '@/Components-ISRI/RRHH/ModalEmployees.vue';
import ModalFotografiaVue from '@/Components-ISRI/RRHH/ModalFotografia.vue';
import ModalPlazasVue from '@/Components-ISRI/RRHH/ModalPlazas.vue';
import ModalDesvinculacionEmpleadoVue from '@/Components-ISRI/RRHH/ModalDesvinculacionEmpleado.vue';
import moment from 'moment';

import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';

import axios from 'axios';

</script>

<template>
    <Head title="Proceso - Empleados" />
    <AppLayoutVue nameSubModule="RRHH - Empleados">
        <div class="sm:flex sm:justify-end sm:items-center mb-2">
            <div class="grid grid-flow-col sm:auto-cols-max sm:justify-end gap-2">
                <GeneralButton @click="addEmployee()" v-if="permits.insertar == 1" color="bg-green-700  hover:bg-green-800"
                    text="Agregar Empleado" icon="add" />
            </div>
        </div>
        <div class="bg-white shadow-lg rounded-sm border border-slate-200 relative">
            <header class="px-5 py-4">
                <div class="mb-4 md:flex flex-row justify-items-start">
                    <div class="mb-4 md:mr-2 md:mb-0 basis-1/4">
                        <div class="relative flex h-8 w-full flex-row-reverse div-multiselect">
                            <Multiselect v-model="tableData.length" @select="getEmployees()"
                                @deselect=" tableData.length = 5; getEmployees()"
                                @clear="tableData.length = 5; getEmployees()" :options="perPage" :searchable="true"
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
                <datatable :columns="columns" :sortKey="sortKey" :inputsToValidate="inputsToValidate"
                    :sortOrders="sortOrders" @sort="sortBy" :searchButton="true" @datos-enviados="handleData($event)"
                    @execute-search="getEmployees()">
                    <tbody class="text-sm divide-y divide-slate-200" v-if="!isLoadinRequest">
                        <tr v-for="employee in employees" :key="employee.id_empleado" class="content-body">
                            <td class="px-2 first:pl-5 last:pr-5  whitespace-nowrap w-px">
                                <div class="font-medium text-slate-800 text-center">{{ employee.id_empleado }}</div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5 td-data-table">
                                <div class="font-medium text-slate-800 ellipsis text-center">
                                    {{ employee.codigo_empleado }}
                                </div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5  whitespace-nowrap w-px">
                                <div class="font-medium text-slate-800 text-center">
                                    {{ employee.persona.pnombre_persona }}
                                    {{ employee.persona.snombre_persona }}
                                    {{ employee.persona.tnombre_persona }}
                                    {{ employee.persona.papellido_persona }}
                                    {{ employee.persona.sapellido_persona }}
                                    {{ employee.persona.tapellido_persona }}
                                </div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5 td-data-table">
                                <div class="font-medium text-slate-800 ellipsis text-center">{{ employee.persona.dui_persona
                                }}</div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5  whitespace-nowrap w-px">
                                <div :class="showDependencies(employee.plazas_asignadas) == 'N/Asign.' ? 'text-red-600' : ''"
                                    class="font-medium text-center">
                                    {{ showDependencies(employee.plazas_asignadas) }}
                                </div>
                            </td>
                            <td class="px-2 first:pl-5 last:pr-5  whitespace-nowrap w-px">
                                <div class="font-medium text-slate-800 text-center">
                                    <div v-if="(employee.id_estado_empleado == 1)"
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
                                        <div class="flex hover:bg-gray-100 py-1 px-2 rounded cursor-pointer"
                                            v-if="permits.actualizar == 1 && employee.id_estado_empleado == 1"
                                            @click="editEmployee(employee)">
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
                                            v-if="permits.actualizar == 1 && employee.id_estado_empleado == 1"
                                            @click="manageFiles(employee)">
                                            <div class="w-8 text-blue-900">
                                                <span class="text-xs">
                                                    <svg width="24px" height="24px" viewBox="0 0 24 24" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <g id="SVGRepo_iconCarrier">
                                                            <path
                                                                d="M12 16C13.6569 16 15 14.6569 15 13C15 11.3431 13.6569 10 12 10C10.3431 10 9 11.3431 9 13C9 14.6569 10.3431 16 12 16Z"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"></path>
                                                            <path
                                                                d="M3 16.8V9.2C3 8.0799 3 7.51984 3.21799 7.09202C3.40973 6.71569 3.71569 6.40973 4.09202 6.21799C4.51984 6 5.0799 6 6.2 6H7.25464C7.37758 6 7.43905 6 7.49576 5.9935C7.79166 5.95961 8.05705 5.79559 8.21969 5.54609C8.25086 5.49827 8.27836 5.44328 8.33333 5.33333C8.44329 5.11342 8.49827 5.00346 8.56062 4.90782C8.8859 4.40882 9.41668 4.08078 10.0085 4.01299C10.1219 4 10.2448 4 10.4907 4H13.5093C13.7552 4 13.8781 4 13.9915 4.01299C14.5833 4.08078 15.1141 4.40882 15.4394 4.90782C15.5017 5.00345 15.5567 5.11345 15.6667 5.33333C15.7216 5.44329 15.7491 5.49827 15.7803 5.54609C15.943 5.79559 16.2083 5.95961 16.5042 5.9935C16.561 6 16.6224 6 16.7454 6H17.8C18.9201 6 19.4802 6 19.908 6.21799C20.2843 6.40973 20.5903 6.71569 20.782 7.09202C21 7.51984 21 8.0799 21 9.2V16.8C21 17.9201 21 18.4802 20.782 18.908C20.5903 19.2843 20.2843 19.5903 19.908 19.782C19.4802 20 18.9201 20 17.8 20H6.2C5.0799 20 4.51984 20 4.09202 19.782C3.71569 19.5903 3.40973 19.2843 3.21799 18.908C3 18.4802 3 17.9201 3 16.8Z"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"></path>
                                                        </g>
                                                    </svg>
                                                </span>
                                            </div>
                                            <div class="font-semibold">Fotografia</div>
                                        </div>
                                        <div class="flex hover:bg-gray-100 py-1 px-2 rounded cursor-pointer"
                                            v-if="permits.actualizar == 1" @click="manageJobPositions(employee)">
                                            <div class="w-8 text-cyan-600">
                                                <span class="text-xs">
                                                    <svg width="25px" height="25px" viewBox="0 0 512 512" class="ml-0.5"
                                                        xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                                        stroke="currentColor">
                                                        <path
                                                            d="M277.33,0L298.67,21.33V64h128v298.67H0V64h128V21.33L149.33,0H277.33zM42.67,220.94L42.67,320H384v-99.06C341.38,233.13,298.7,240.76,256,243.81V277.33H170.67v-33.52C127.97,240.76,85.29,233.13,42.67,220.94zM384,106.67H42.67V176.43C99.64,193.93,156.51,202.67,213.33,202.67c56.82,0,113.7-8.74,170.67-26.26V106.67zM256,42.67H170.67V64H256V42.67z" />
                                                    </svg>
                                                </span>
                                            </div>
                                            <div class="font-semibold">Plaza</div>
                                        </div>
                                        <div class="flex hover:bg-gray-100 py-1 px-2 rounded cursor-pointer"
                                            @click="manageEmpTermination(employee)" v-if="permits.eliminar == 1">
                                            <div v-if="employee.id_estado_empleado === 1" class="w-8 text-red-700">
                                                <span class="text-xs">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                        class="h-5 w-5 ml-0.5" fill="currentColor">
                                                        <path
                                                            d="M13.6,23.9c-7.8,1-14.5-5.6-13.5-13.5c0.7-5.3,5-9.7,10.3-10.3c7.8-1,14.5,5.6,13.5,13.5C23.2,18.9,18.9,23.2,13.6,23.9z M13.7,2.1C6.9,1,1,6.9,2.1,13.7c0.7,4.1,4,7.5,8.2,8.2C17.1,23,23,17.1,21.9,10.3C21.2,6.2,17.8,2.8,13.7,2.1z" />
                                                        <polyline points="5.6,4.2 19.8,18.3 18.4,19.8 4.2,5.6" />
                                                    </svg>
                                                </span>
                                            </div>
                                            <div v-else class="w-8 text-green-800">
                                                <svg fill="currentColor" class="ml-0.5 w-5 h-5" viewBox="0 0 16 16"
                                                    xmlns="http://www.w3.org/2000/svg" stroke="currentColor">
                                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round"
                                                        stroke-linejoin="round"></g>
                                                    <g id="SVGRepo_iconCarrier">
                                                        <g>
                                                            <path
                                                                d="M15,6V1.76l-1.7,1.7A7,7,0,1,0,14.92,9H13.51a5.63,5.63,0,1,1-1.2-4.55L10.76,6Z">
                                                            </path>
                                                        </g>
                                                    </g>
                                                </svg>
                                            </div>
                                            <div class="font-semibold">
                                                {{ employee.id_estado_empleado === 1 ? 'Inhabilitar' : 'Activar' }}
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
                    encontraron
                    registros.</p>
            </div>

        </div>

        <div v-if="!empty_object" class="px-6 py-8 bg-white shadow-lg rounded-sm border-slate-200 relative">
            <div>
                <nav class="flex justify-between" role="navigation" aria-label="Navigation">
                    <div class="grow text-center">
                        <ul class="inline-flex text-sm font-medium -space-x-px">
                            <li v-for="link in links" :key="link.label">
                                <span v-if="(link.label == 'Anterior')"
                                    :class="(link.active ? 'inline-flex items-center justify-center rounded-full leading-5 px-2 py-2 bg-white border border-slate-200 text-indigo-500 shadow-sm' : 'inline-flex items-center justify-center leading-5 px-2 py-2 text-slate-600 hover:text-indigo-500 border border-transparent')">

                                    <div class="flex-1 text-right ml-2">
                                        <a @click="page != 1 ? getEmployees(link.url) : ''" class=" btn bg-white border-slate-200 hover:border-slate-300 cursor-pointer
                                  text-indigo-500">
                                            &lt;-<span class="hidden sm:inline">&nbsp;Anterior</span>
                                        </a>
                                    </div>
                                </span>
                                <span v-else-if="(link.label == 'Siguiente')"
                                    :class="(link.active ? 'inline-flex items-center justify-center rounded-full leading-5 px-2 py-2 bg-white border border-slate-200 text-indigo-500 shadow-sm' : 'inline-flex items-center justify-center leading-5 px-2 py-2 text-slate-600 hover:text-indigo-500 border border-transparent')">
                                    <div class="flex-1 text-right ml-2">
                                        <a @click="hasNext ? getEmployees(link.url) : ''" class=" btn bg-white border-slate-200 hover:border-slate-300 cursor-pointer
                                  text-indigo-500">
                                            <span class="hidden sm:inline">Siguiente&nbsp;</span>-&gt;
                                        </a>
                                    </div>
                                </span>
                                <span class="cursor-pointer mt-2" v-else @click="getEmployees(link.url)"
                                    :class="(link.active ? 'inline-flex items-center justify-center rounded-full leading-5 px-2 py-2 bg-white border border-slate-200 text-indigo-500 shadow-sm' : 'inline-flex items-center justify-center leading-5 px-2 py-2 text-slate-600 hover:text-indigo-500 border border-transparent')"><span
                                        class=" w-5">{{ link.label }}</span>
                                </span>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>

        <ModalEmployeesVue :show_modal_employee="show_modal_employee" :modalData="modalData"
            @cerrar-modal="show_modal_employee = false" @get-table="getEmployees(tableData.currentPage)" />

        <ModalFotografiaVue :showModalFlag="showModalFlag" :modalData="modalData" @cerrar-modal="showModalFlag = false"
            @get-table="getEmployees(tableData.currentPage)" />

        <ModalPlazasVue v-if="showModalJobPosition" :showModalJobPosition="showModalJobPosition" :modalData="modalData"
            @cerrar-modal="showModalJobPosition = false" @get-table="getEmployees(tableData.currentPage)" />

        <ModalDesvinculacionEmpleadoVue v-if="showEmpTermination" :showEmpTermination="showEmpTermination"
            :modalData="modalData" @cerrar-modal="showEmpTermination = false"
            @get-table="getEmployees(tableData.currentPage)" />

    </AppLayoutVue>
</template>

<script>
export default {
    created() {
        this.getEmployees()
        this.getPermissions(this)
    },
    data() {
        let sortOrders = {};
        let columns = [
            { width: "10%", label: "ID", name: "id_empleado", type: "text" },
            { width: "10%", label: "Codigo", name: "codigo_empleado", type: "text" },
            { width: "30%", label: "Nombre", name: "nombre_persona", type: "text" },
            { width: "15%", label: "Dui", name: "dui_persona", type: "text" },
            { width: "20%", label: "Dependencia", name: "dependencia", type: "text" },
            {
                width: "10%", label: "Estado", name: "estado_empleado", type: "select",
                options: [
                    { value: "1", label: "Activo" },
                    { value: "0", label: "Inactivo" }
                ]
            },
            { width: "5%", label: "Acciones", name: "Acciones" },
        ];
        columns.forEach((column) => {
            if (column.name === 'id_empleado')
                sortOrders[column.name] = 1;
            else
                sortOrders[column.name] = -1;
        });
        return {
            showModalJobPosition: false, //Plaza
            showModalFlag: false, //Fotografia
            show_modal_employee: false, //Create and edit employee
            showEmpTermination: false, //Manage employee termination
            empty_object: false,
            isLoadinRequest: false,
            //Data for datatable
            employees: [],
            //Data for modal
            modalData: [],
            inputsToValidate: [
                { inputName: 'id_empleado', number: true, limit: 4 },
                { inputName: 'codigo_empleado', number: false, limit: 5 },
                { inputName: 'nombre_persona', number: false, limit: 20 },
                { inputName: 'dui_persona', number: false, limit: 10 },
                { inputName: 'dependencia', number: false, limit: 5 },
            ],
            hasNext: false,
            page: '',

            //Permissions
            permits: [],
            links: [],
            columns: columns,
            sortKey: "id_empleado",
            sortOrders: sortOrders,
            perPage: ["10", "20", "30"],
            tableData: {
                currentPage: '',
                draw: 0,
                length: 5,
                search: "",
                column: 0,
                dir: "desc",
                total: ""
            },
        }
    },
    methods: {
        manageEmpTermination(employee) {
            employee.id_estado_empleado === 1 ? this.showEmpTermination = true : this.show_modal_employee = true
            this.modalData = employee
        },
        manageJobPositions(employee) {
            this.showModalJobPosition = true
            this.modalData = employee
        },
        manageFiles(employee) {
            this.showModalFlag = true
            this.modalData = employee
        },
        editEmployee(employee) {
            this.modalData = employee
            this.show_modal_employee = true
        },
        addEmployee() {
            this.modalData = []
            this.show_modal_employee = true
        },
        changeStatusEmployee(employee) {
            let msg
            employee.estado_empleado == 1 ? msg = "Desactivar" : msg = "Activar"
            this.$swal.fire({
                title: msg + ' empleado codigo: ' + employee.codigo_empleado + '.',
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
                    axios.post("/change-state-acq-doc", {
                        id: employee.id_empleado,
                        status: employee.estado_empleado
                    })
                        .then((response) => {
                            this.$swal.fire({
                                text: response.data.mensaje,
                                icon: 'success',
                                timer: 5000
                            })
                            this.getEmployees(this.tableData.currentPage);
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
                                let msg = this.manageError(errors)
                                this.$swal.fire({
                                    title: 'Operación cancelada',
                                    text: msg,
                                    icon: 'warning',
                                    timer: 5000
                                })
                            }
                        })
                }
            })
        },
        async getEmployees(url = "/employees") {
            this.tableData.draw++;
            this.tableData.currentPage = url
            this.isLoadinRequest = true
            await axios.post(url, this.tableData).then((response) => {
                let data = response.data;
                if (this.tableData.draw == data.draw) {
                    this.links = data.data.links;
                    this.page = data.data.current_page
                    this.tableData.total = data.data.total;
                    this.links[0].label = "Anterior";
                    this.links[this.links.length - 1].label = "Siguiente";
                    this.employees = data.data.data;
                    this.hasNext = data.data.current_page !== data.data.last_page;
                    this.employees.length > 0 ? this.empty_object = false : this.empty_object = true
                    this.isLoadinRequest = false

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
                this.getEmployees();
            }
        },
        getIndex(array, key, value) {
            return array.findIndex((i) => i[key] == value);
        },
        handleData(myEventData) {
            this.tableData.search = myEventData;
            const data = Object.values(myEventData);
            if (data.every(error => error === '')) {
                this.getEmployees()
            }
        },
        showDependencies(plazasAsignadas) {
            // Crear un objeto para mantener un recuento de las dependencias
            const dependencyCounts = {};

            // Iterar sobre las plazas asignadas y contar las dependencias
            plazasAsignadas.forEach(value => {
                const dependency = value.dependencia.parent_dependency || value.dependencia;
                const codigoDependencia = dependency.codigo_dependencia;

                if (dependencyCounts[codigoDependencia]) {
                    // Incrementar el recuento si la dependencia ya existe en el objeto
                    dependencyCounts[codigoDependencia]++;
                } else {
                    // Inicializar el recuento si es la primera vez que se encuentra la dependencia
                    dependencyCounts[codigoDependencia] = 1;
                }
            });

            // Crear una matriz de dependencias formateadas con el recuento (si es mayor que 1)
            const formattedDependencies = Object.keys(dependencyCounts).map(codigoDependencia => {
                const count = dependencyCounts[codigoDependencia];
                return count > 1 ? `${codigoDependencia}(${count})` : codigoDependencia;
            });

            // Unir las dependencias formateadas en una cadena separada por comas
            return formattedDependencies.join(', ') || 'N/Asign.';
        }
    },
    computed: {
    }
}
</script>

<style>.td-data-table {
    max-width: 100px;
    white-space: nowrap;
    height: 50px;
}

.ellipsis {
    overflow: hidden;
    text-overflow: ellipsis;
}</style>
