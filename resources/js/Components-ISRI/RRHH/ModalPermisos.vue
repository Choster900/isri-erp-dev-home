<script setup>
import Modal from "@/Components-ISRI/AllModal/Modal.vue";
import { toast } from "vue3-toastify";
import "vue3-toastify/dist/index.css";
import axios from "axios";
import moment from 'moment';

</script>

<template>
    <div class="m-1.5">
        <Modal :show="showModalJobPermissions" @close="$emit('cerrar-modal')" :modal-title="'Solicitud de permiso. '"
            maxWidth="3xl">
            <div v-if="isLoading" class="flex items-center justify-center h-[100px]">
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
            <div v-else class="px-5 py-3">
                <div class="mb-4 md:flex flex-row justify-center">
                    <div class="mb-4 md:mr-2 md:mb-0 basis-1/3">
                        <label class="block mb-2 text-xs font-light text-gray-600">
                            Empleado <span class="text-red-600 font-extrabold">*</span>
                        </label>
                        <div class="font-semibold relative flex h-8 w-full flex-row-reverse ">
                            <Multiselect placeholder="Seleccione empleado" v-model="permission.employeeId" ref="multi2"
                                :options="employees" :searchable="true" @change="changeEmployee($event)"
                                :disabled="permits.ejecutar === 0 ? true : false" />
                            <LabelToInput icon="list" />
                        </div>
                    </div>
                    <div class="mb-4 md:mr-2 md:mb-0 basis-1/3">
                        <label class="block mb-2 text-xs font-light text-gray-600">
                            Plaza <span class="text-red-600 font-extrabold">*</span>
                        </label>
                        <div class="font-semibold relative flex h-8 w-full flex-row-reverse ">
                            <Multiselect placeholder="Seleccione plaza" v-model="permission.jobPositionId"
                                :options="jobPositions" :searchable="true" />
                            <LabelToInput icon="list" />
                        </div>
                    </div>
                    <div class="mb-4 md:mr-0 md:mb-0 basis-1/3">
                        <label class="block mb-2 text-xs font-light text-gray-600">
                            Permiso <span class="text-red-600 font-extrabold">*</span>
                        </label>
                        <div class="font-semibold relative flex h-8 w-full flex-row-reverse ">
                            <Multiselect placeholder="Seleccione permiso" v-model="permission.permissionId"
                                :options="typesOfPermissions" :searchable="true" @change="changeTypeOfPermission($event)" />
                            <LabelToInput icon="list" />
                        </div>
                    </div>
                </div>
                <div class="rounded-lg border border-gray-400 shadow p-1 mb-4 mx-10 text-sm">
                    <div class="flex">
                        <div class="flex-1 text-center p-1 border-r border-gray-400 ">
                            <p>Tiempo permitido</p>
                            <p class="font-semibold text-blue-500">
                                {{ formatTime(showPermissionInfo.totalTime) }}
                            </p>
                        </div>
                        <div class="flex-1 text-center p-1 border-r border-gray-400 ">
                            <p>Tiempo utilizado</p>
                            <p class="font-semibold text-orange-500">
                                {{ formatTime(showPermissionInfo.acumulatedTime) }}
                            </p>
                        </div>
                        <div class="flex-1 text-center p-1">
                            <p>Tiempo restante</p>
                            <p class="font-semibold text-green-500">
                                {{ formatTime(showPermissionInfo.remainingTime) }}
                            </p>
                        </div>
                    </div>
                    <div class="flex border-t border-gray-400">
                        <div class="flex-1 text-center p-1">
                            <span>Permiso creado:</span> <span class="font-semibold">{{ moment().format('DD-MM-YYYY')
                            }}</span>
                        </div>
                    </div>
                </div>
                <div class="mb-2 md:flex flex-row justify-center">
                    <div class="md:w-1/2 flex justify-center">
                        <span class="font-semibold text-slate-800 text-lg underline underline-offset-2">
                            Informacion del permiso
                        </span>
                    </div>
                </div>
                <div class="w-full flex justify-start">
                    <div class="md:w-1/3 flex justify-center">
                        <div :class="selectedOption === 'hours' ? 'bg-gray-300 rounded-tr-lg' : ''"
                            class="md:w-1/2 cursor-pointer rounded-tl-lg" @click="handleOptionClick('hours')">
                            <!-- <label class="inline-flex items-center mr-6">
                                <input type="radio" v-model="selectedOption" value="diario"
                                    class="form-radio text-indigo-500">
                                <span class="ml-2">Diario</span>
                            </label> -->
                            <p class="ml-2">Por horas</p>
                        </div>
                        <div :class="selectedOption === 'daily' ? 'bg-gray-300 rounded-t-lg' : ''"
                            class="md:w-1/2 cursor-pointer" @click="handleOptionClick('daily')">
                            <!-- <label class="inline-flex items-center">
                                <input type="radio" v-model="selectedOption" value="porHoras"
                                    class="form-radio text-indigo-500">
                                <span class="ml-2">Por horas</span>
                            </label> -->
                            <p class="ml-2">Diario</p>
                        </div>
                    </div>
                    <div class="w-1/3"></div>
                    <div class="w-1/3"></div>
                </div>
                <div :class="selectedOption === 'daily' ? 'rounded-tl-lg' : ''"
                    class="mb-7 rounded-tr-lg rounded-b-lg bg-gray-300">
                    <div class="mb-5 md:flex flex-row justify-items-start ml-1">
                        <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                            <label class="block mb-2 text-xs font-light text-gray-600" for="fecha_nacimiento">
                                Fecha Inicio <span class="text-red-600 font-extrabold">*</span>
                            </label>
                            <div class="relative flex">
                                <LabelToInput icon="date" />
                                <flat-pickr
                                    class="peer font-semibold text-xs cursor-pointer rounded-r-md border h-8 border-slate-400 px-2 text-slate-900 placeholder-slate-400 transition-colors duration-300 focus:border-[#001b47] focus:outline-none w-full"
                                    :config="config" v-model="permission.startDate"
                                    :placeholder="'Seleccione fecha inicio'" />
                            </div>
                        </div>
                        <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                            <label class="block mb-2 text-xs font-light text-gray-600"
                                for="fecha_nacimiento">
                                Fecha Fin <span 
                                    class="text-red-600 font-extrabold">*</span>
                            </label>
                            <div class="relative flex">
                                <LabelToInput icon="date" />
                                <flat-pickr
                                    class="peer font-semibold text-xs cursor-pointer rounded-r-md border h-8 border-slate-400 px-2 text-slate-900 placeholder-slate-400 transition-colors duration-300 focus:border-[#001b47] focus:outline-none w-full"
                                    :config="config" v-model="permission.endDate" 
                                    :placeholder="selectedOption === 'hours' ? 'Deshabilitado' : 'Seleccione fecha fin' " 
                                    :disabled="selectedOption === 'hours'" />
                            </div>
                        </div>
                    </div>
                    <div class="mb-5 md:flex flex-row justify-items-start ml-1 pb-2">
                        <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                            <label class="block mb-2 text-xs font-light text-gray-600" for="fecha_nacimiento">
                                Hora Inicio <span class="text-red-600 font-extrabold">*</span>
                            </label>
                            <div class="relative flex">
                                <LabelToInput icon="date" />
                                <flat-pickr
                                    class="peer font-semibold text-xs cursor-pointer rounded-r-md border h-8 border-slate-400 px-2 text-slate-900 placeholder-slate-400 transition-colors duration-300 focus:border-[#001b47] focus:outline-none w-full"
                                    :config="configTime" v-model="permission.startTime"
                                    :placeholder="selectedOption === 'daily' ? 'Deshabilitado' : 'Seleccione hora inicio'" :disabled="selectedOption === 'daily'" />
                            </div>
                        </div>
                        <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                            <label class="block mb-2 text-xs font-light text-gray-600" for="fecha_nacimiento">
                                Hora Inicio <span class="text-red-600 font-extrabold">*</span>
                            </label>
                            <div class="relative flex">
                                <LabelToInput icon="date" />
                                <flat-pickr
                                    class="peer font-semibold text-xs cursor-pointer rounded-r-md border h-8 border-slate-400 px-2 text-slate-900 placeholder-slate-400 transition-colors duration-300 focus:border-[#001b47] focus:outline-none w-full"
                                    :config="configTime" v-model="permission.endTime"
                                    :placeholder="selectedOption === 'daily' ? 'Deshabilitado' : 'Seleccione hora fin'" 
                                    :disabled="selectedOption === 'daily'" />
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </Modal>
    </div>
</template>

<script>
export default {
    props: {
        showModalJobPermissions: {
            type: Boolean,
            default: false,
        },
        modalData: {
            type: Array,
            default: []
        },
        permits: {
            type: Array,
            default: []
        }
    },
    created() { },
    data: function (data) {
        return {
            configTime: {
                enableTime: true,
                noCalendar: true,
                dateFormat: "H:i",
                time_24hr: true
            },
            errors: [],

            employees: [],
            isLoadingEmployee: false,

            jobPositionsToSelect: [],
            isLoading: false,
            jobPositions: [],
            typesOfPermissions: [],
            selectedOption: 'hours',
            showPermissionInfo: {
                totalTime: '',
                acumulatedTime: '',
                remainingTime: ''
            },
            permission: {
                jobPositionId: '',
                permissionId: '',
                startDate: '',
                endDate: '',
                startTime: '',
                endTime: ''
            },
            config: {
                altInput: true,
                //static: true,
                monthSelectorType: 'static',
                altFormat: "d/m/Y",
                dateFormat: "Y-m-d",
                maxDate: new Date(),
                locale: {
                    firstDayOfWeek: 1,
                    weekdays: {
                        shorthand: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
                        longhand: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
                    },
                    months: {
                        shorthand: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
                        longhand: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                    },
                },
            },
        };
    },
    methods: {
        handleOptionClick(option) {
            this.selectedOption = option;
            this.permission.startDate=''
            this.permission.endDate=''
            this.permission.startTime=''
            this.permission.endTime=''
        },
        formatTime(time) {
            if (this.permission.permissionId == 5 || this.permission.permissionId == 6) {
                return 'NO APLICA'
            } else {
                if (!time) {
                    return '0'
                } else {
                    let formatedTime
                    const [hours, minutes] = time.split(':');
                    let hoursFormat = parseInt(hours)
                    let minutesFormat = parseInt(minutes)
                    if (minutesFormat > 0) {
                        formatedTime = `${hoursFormat} hora${hoursFormat === 1 ? '' : 's'} y ${minutesFormat} minuto${minutesFormat === 1 ? '' : 's'}`
                    } else {
                        formatedTime = `${hoursFormat} hora${hoursFormat === 1 ? '' : 's'}`
                    }
                    return formatedTime
                }
            }
        },
        changeTypeOfPermission(typeOfPermissionId) {
            const selectedPermission = this.typesOfPermissions.find(value => value.value === typeOfPermissionId);
            selectedPermission ? this.showPermissionInfo.totalTime = selectedPermission.limite_permiso : this.showPermissionInfo.totalTime = ''
            selectedPermission ? this.showPermissionInfo.acumulatedTime = selectedPermission.total_acumulado : this.showPermissionInfo.acumulatedTime = ''
            selectedPermission ? this.showPermissionInfo.remainingTime = selectedPermission.restante : this.showPermissionInfo.remainingTime = ''
        },
        async getPermissionData() {
            try {
                this.isLoading = true;  // Activar el estado de carga
                const response = await axios.get("/get-data-permission-modal", {
                    params: {
                        ejecutar: this.permits.ejecutar
                    }
                });
                this.jobPositions = response.data.jobPositions
                this.typesOfPermissions = response.data.typesOfPermissions
                this.employees = response.data.employees;
                if (this.permits.ejecutar == 0) {
                    this.permission.employeeId = this.employees[0].value
                }
            } catch (errors) {
                this.manageError(errors, this)
            } finally {
                this.isLoading = false;  // Desactivar el estado de carga
            }
        },
        changeEmployee(id_empleado) {
            this.typesOfPermissions=[]
            this.jobPositions=[]
            this.permission.jobPositionId=''
            this.permission.permissionId=''
            axios.get(`/get-permission-data/${id_empleado}`)
                .then((response) => {
                    this.typesOfPermissions = response.data.typesOfPermissions
                    this.jobPositions = response.data.jobPositions
                })
                .catch((errors) => {
                    console.log(errors);
                });
        }
    },
    watch: {
        showModalJobPermissions: function (value, oldValue) {
            if (value) {
                this.errors = []
                this.getPermissionData()
                this.selectedOption = 'hours'
            }
        },
    },
    computed: {
    },
};
</script>

<style scoped></style>