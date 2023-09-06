<script setup>
import Modal from "@/Components-ISRI/AllModal/Modal.vue";
import { toast } from "vue3-toastify";
import "vue3-toastify/dist/index.css";
import axios from "axios";
import moment from 'moment';
</script>

<template>
    <div class="m-1.5">
        <Modal :show="showModalJobPermissions" @close="$emit('cerrar-modal')" :modal-title="'Solicitud de permiso.'"
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
                    <div class="md:mr-2 md:mb-0 basis-1/3">
                        <label class="block mb-2 text-xs font-light text-gray-600">
                            Empleado <span class="text-red-600 font-extrabold">*</span>
                        </label>
                        <div class="font-semibold relative flex h-8 w-full flex-row-reverse ">
                            <Multiselect placeholder="Seleccione empleado" v-model="permission.employeeId"
                                :options="employees" :searchable="true" @change="changeEmployee($event)"
                                :disabled="permits.ejecutar === 0 ? true : false" />
                            <LabelToInput icon="list" />
                        </div>
                        <InputError v-for="(item, index) in errors.employeeId" :key="index" class="mt-2" :message="item" />
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
                        <InputError v-for="(item, index) in errors.jobPositionId" :key="index" class="mt-2"
                            :message="item" />
                    </div>
                    <div class="mb-4 md:mr-0 md:mb-0 basis-1/3">
                        <label class="block mb-2 text-xs font-light text-gray-600">
                            Permiso <span class="text-red-600 font-extrabold">*</span>
                        </label>
                        <div class="font-semibold relative flex h-8 w-full flex-row-reverse">
                            <Multiselect placeholder="Seleccione permiso" v-model="permission.typeOfPermissionId"
                                ref="selectPerm" :options="typesOfPermissions" :searchable="true"
                                @change="changeTypeOfPermission($event)" :class="{ 'bg-red-300': noTime }" />
                            <LabelToInput icon="list" />
                        </div>
                        <InputError v-for="(item, index) in errors.typeOfPermissionId" :key="index" class="mt-2"
                            :message="item" />
                    </div>
                </div>
                <div class="rounded-lg border border-gray-400 shadow p-1 mb-4 mx-10 text-sm">
                    <div class="flex">
                        <div class="flex-1 text-center border-r border-gray-400 ">
                            <p>Tiempo permitido</p>
                            <p class="font-semibold text-blue-500">
                                {{ formatTime(showPermissionInfo.totalTime) }}
                            </p>
                        </div>
                        <div class="flex-1 text-center border-r border-gray-400 ">
                            <p>Tiempo utilizado</p>
                            <p class="font-semibold text-orange-500">
                                {{ formatTime(showPermissionInfo.acumulatedTime) }}
                            </p>
                        </div>
                        <div class="flex-1 text-center">
                            <p>Tiempo restante</p>
                            <p class="font-semibold" :class="noTime ? 'text-red-500' : 'text-green-500'">
                                {{ formatTime(showPermissionInfo.remainingTime) }}
                            </p>
                        </div>
                    </div>
                    <div class="flex border-t border-gray-400">
                        <div class="flex-1 text-center p-0.5">
                            <span>Permiso creado:</span> <span class="font-semibold">{{ moment().format('DD-MM-YYYY')
                            }}</span>
                        </div>
                    </div>
                </div>

                <div class="mb-2 md:flex flex-row justify-center">
                    <div class="md:w-1/2 flex justify-center">
                        <span class="font-semibold text-slate-800 text-lg">
                            Informacion del permiso
                        </span>
                    </div>
                </div>

                <div class="max-h-auto w-full  rounded-md mb-2"
                    :class="permission.typeOfPermissionId == 6 ? '' : 'overflow-y-auto'">

                    <div class=" rounded-md mb-2 mx-2" :class="expandedDetails ? 'border border-gray-400' : 'py-0.5'">
                        <div class="max-w-full mx-2" :class="expandedDetails ? 'mb-2' : ''">
                            <div class="flex items-center cursor-pointer w-full border-gray-400"
                                @click="toggleAccordion('expandedDetails')">
                                <p class="mr-2 ml-4"
                                    :class="expandedDetails ? 'text-slate-800 font-semibold' : 'text-black'">Detalles
                                </p>
                                <div class="flex-grow h-px " :class="expandedDetails ? 'bg-black' : 'bg-gray-500'"></div>
                                <svg class="w-4 h-4 ml-2 mr-4 transform origin-center transition-transform"
                                    :class="{ 'rotate-180': expandedDetails }" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7">
                                    </path>
                                </svg>
                            </div>
                        </div>

                        <div v-if="expandedDetails" class="rounded-b-md mx-2">
                            <div class="mb-3 md:flex md:flex-row justify-start items-center mx-4">
                                <div class="basis-1/2">
                                    <label for="regresara" class="text-left mr-4 block text-xs text-black md:w-auto">Tipo
                                        permiso<span class="text-red-600 font-extrabold">*</span></label>
                                    <div class="mt-1 flex md:flex-row items-center">
                                        <!-- Cambio a radio buttons para permitir solo una selección -->
                                        <label class="inline-flex items-center">
                                            <span class="mr-2 text-xs">Por horas</span>
                                            <input type="radio" class="form-radio" ref="radio1"
                                                :checked="permission.periodOfTime === 1" @change="periodOfTimeSelected(1)"
                                                :disabled="disableForChecks">
                                        </label>
                                        <label class="inline-flex items-center ml-4">
                                            <span class="mr-2 text-xs">Diario</span>
                                            <input type="radio" class="form-radio" ref="radio2"
                                                :checked="permission.periodOfTime === 2" @change="periodOfTimeSelected(2)"
                                                :disabled="disableForChecks">
                                        </label>
                                    </div>
                                </div>
                                <div class="mb-4 md:mr-2 md:mb-0 basis-1/2 text-right">
                                    <span class="font-semibold text-slate-800 mb-2 text-sm">Total:
                                        <span :class="noTime || negativeTime ? 'text-red-600' : 'text-green-600'">{{
                                            negativeTime ? 'ERROR' : totalHours }}</span>
                                    </span>
                                </div>
                            </div>


                            <div class="mb-3 md:flex flex-row justify-items-start mx-4">
                                <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                                    <label class="block mb-2 text-xs text-black" for="fecha_nacimiento">
                                        Fecha Inicio <span class="text-red-600 font-extrabold">*</span>
                                    </label>
                                    <div class="relative flex">
                                        <LabelToInput icon="date" />
                                        <flat-pickr
                                            class="peer font-semibold text-xs cursor-pointer rounded-r-md border h-8 border-slate-400 px-2 text-slate-900 placeholder-slate-400 transition-colors duration-300 focus:border-[#001b47] focus:outline-none w-full"
                                            :config="config" v-model="permission.startDate"
                                            :placeholder="'Seleccione fecha inicio'" ref="datePick"
                                            @change="updateValueTime($event, 'fecha_inicio')" />
                                    </div>
                                    <InputError v-for="(item, index) in errors.startDate" :key="index" class="mt-2"
                                        :message="item" />
                                </div>
                                <div v-if="this.permission.periodOfTime === 2" class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                                    <label class="block mb-2 text-xs text-black" for="fecha_nacimiento">
                                        Fecha Fin <span class="text-red-600 font-extrabold">*</span>
                                    </label>
                                    <div class="relative flex">
                                        <LabelToInput icon="date" />
                                        <flat-pickr
                                            class="peer font-semibold text-xs cursor-pointer rounded-r-md border h-8 border-slate-400 px-2 text-slate-900 placeholder-slate-400 transition-colors duration-300 focus:border-[#001b47] focus:outline-none w-full"
                                            :config="config" v-model="permission.endDate" ref="datePick2"
                                            :placeholder="permission.periodOfTime === 1 ? 'Deshabilitado' : 'Seleccione fecha fin'"
                                            :disabled="validateEndDate" :class="validateEndDate ? 'bg-gray-200' : ''"
                                            @change="updateValueTime($event, 'fecha_fin')" />
                                    </div>
                                    <InputError v-for="(item, index) in errors.endDate" :key="index" class="mt-2"
                                        :message="item" />
                                </div>
                            </div>
                            <div class="mb-3 md:flex flex-row justify-items-start mx-4">
                                <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                                    <label class="block mb-2 text-xs text-black" for="fecha_nacimiento">
                                        {{ labelHoraInicio }}
                                        <span class="text-red-600 font-extrabold">*</span>
                                    </label>
                                    <div class="relative flex">
                                        <LabelToInput icon="date" />
                                        <flat-pickr
                                            class="peer font-semibold text-xs cursor-pointer rounded-r-md border h-8 border-slate-400 px-2 text-slate-900 placeholder-slate-400 transition-colors duration-300 focus:border-[#001b47] focus:outline-none w-full"
                                            :config="configTime" v-model="permission.startTime"
                                            :placeholder="permission.periodOfTime === 2 ? 'Deshabilitado' : 'Seleccione ' + labelHoraInicio"
                                            :disabled="permission.periodOfTime === 2"
                                            :class="permission.periodOfTime === 2 ? 'bg-gray-200' : ''"
                                            @change="updateValueTime($event, 'startTime')" />
                                    </div>
                                    <InputError v-for="(item, index) in errors.startTime" :key="index" class="mt-2"
                                        :message="item" />
                                </div>
                                <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                                    <label class="block mb-2 text-xs text-black" for="fecha_nacimiento">
                                        {{ labelHoraFin }}
                                        <span class="text-red-600 font-extrabold">*</span>
                                    </label>
                                    <div class="relative flex">
                                        <LabelToInput icon="date" />
                                        <flat-pickr
                                            class="peer font-semibold text-xs cursor-pointer rounded-r-md border h-8 border-slate-400 px-2 text-slate-900 placeholder-slate-400 transition-colors duration-300 focus:border-[#001b47] focus:outline-none w-full"
                                            :config="configTime" v-model="permission.endTime"
                                            :placeholder="permission.periodOfTime === 2 || permission.typeOfPermissionId === 5 ? 'Deshabilitado' : 'Seleccione ' + labelHoraFin"
                                            :disabled="permission.periodOfTime === 2 || permission.typeOfPermissionId === 5"
                                            :class="permission.periodOfTime === 2 || permission.typeOfPermissionId === 5 ? 'bg-gray-200' : ''"
                                            @input="updateValueTime($event, 'endTime')" />
                                    </div>
                                    <InputError v-for="(item, index) in errors.endTime" :key="index" class="mt-2"
                                        :message="item" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div v-if="permission.typeOfPermissionId == 5" class=" rounded-md mb-2 mx-2"
                        :class="expanded ? 'border border-gray-400' : 'py-0.5'">
                        <div class="max-w-full mx-2" :class="expanded ? 'mb-2' : ''">
                            <div class="flex items-center cursor-pointer w-full mx-auto"
                                @click="toggleAccordion('expanded')"
                                :class="[emptyObjectMision && !expanded ? 'expand-animation' : '',]">
                                <p class="mr-2 ml-4" :class="expanded ? 'text-slate-800 font-semibold' :
                                    emptyObjectMision ? 'text-red-500' : 'text-black'
                                    ">
                                    Misión Oficial</p>
                                <div class="flex-grow h-px " :class="expanded ? 'bg-black' :
                                    emptyObjectMision ? 'bg-red-500' : 'bg-gray-500'
                                    "></div>
                                <svg class="w-4 h-4 ml-2 mr-4 transform origin-center transition-transform"
                                    :class="{ 'rotate-180': expanded }" fill="none"
                                    :stroke="emptyObjectMision ? '#EF4444' : '#000000'" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7">
                                    </path>
                                </svg>
                            </div>
                        </div>
                        <div v-if="expanded && permission.typeOfPermissionId == 5" class="mt-2 mx-2">
                            <div class="mb-3 md:flex flex-row justify-items-start mx-4 items-center">
                                <div class="mb-4 md:mr-2 md:mb-0 basis-2/3">
                                    <TextInput id="destination" v-model="permission.destination" type="text"
                                        placeholder="Destino" @update:modelValue="validateInput('destination', 100)">
                                        <LabelToInput icon="standard" forLabel="destination" />
                                    </TextInput>
                                    <InputError v-for="(item, index) in errors.destination" :key="index" class="mt-2"
                                        :message="item" />
                                </div>
                                <div class="mb-4 md:mr-2 md:mb-0 basis-1/3 grid grid-cols-2 items-center md:self-center">
                                    <label for="regresara" class="text-right mr-4 block text-xs text-black">Regresará 
                                        <span v-if="permission.periodOfTime==1" class="text-red-600 font-extrabold">*</span></label>
                                    <div class="mt-1">
                                        <label class="inline-flex items-center">
                                            <span class="mr-2 text-xs">Si</span>
                                            <input type="radio" class="form-radio" v-model="permission.comingBack"
                                                :value="1" name="returnOption">
                                        </label>
                                        <label class="inline-flex items-center ml-2">
                                            <span class="mr-2 text-xs">No</span>
                                            <input type="radio" class="form-radio" v-model="permission.comingBack"
                                                :value="0" name="returnOption">
                                        </label>
                                    </div>
                                    <InputError v-for="(item, index) in errors.comingBack" :key="index" class="mt-2"
                                        :message="item" />
                                </div>
                            </div>
                            <div class="mb-4 md:mr-4 md:mb-0 basis-full mx-4"
                                style="border: none; background-color: transparent;">
                                <label class="block mb-2 text-xs text-black" for="descripcion">
                                    Descripción
                                </label>
                                <textarea v-model="permission.observation" id="description" name="description"
                                    class="resize-none w-full h-12 overflow-y-auto peer text-xs font-semibold rounded-md border border-slate-400 px-2 text-slate-900 transition-colors duration-300 focus:border-[#001b47] focus:outline-none"
                                    @input="validateInput('observation', limit = 250)">
                                </textarea>
                                <InputError v-for="(item, index) in errors.description" :key="index" class="mt-2"
                                    :message="item" />
                            </div>
                        </div>
                    </div>

                    <div v-if="permission.typeOfPermissionId == 6" class="rounded-md mb-2 mx-2"
                        :class="expandedClockingIn ? 'border border-gray-400' : 'py-0.5'">
                        <div class="max-w-full mx-2" :class="expandedClockingIn ? 'mb-2' : ''">
                            <div class="flex items-center cursor-pointer w-full "
                                @click="toggleAccordion('expandedClockingIn')"
                                :class="[emptyObjectClockingIn && !expandedClockingIn ? 'expand-animation' : '',]">
                                <p class="mr-2 ml-4"
                                    :class="expandedClockingIn ? 'text-slate-800 font-semibold' : emptyObjectClockingIn ? 'text-red-500' : 'text-black'">
                                    No
                                    marcación de asistencia</p>
                                <div class="flex-grow h-px "
                                    :class="expandedClockingIn ? 'bg-black' : emptyObjectClockingIn ? 'bg-red-500' : 'bg-gray-500'">
                                </div>
                                <svg class="w-4 h-4 ml-2 mr-4 transform origin-center transition-transform"
                                    :class="{ 'rotate-180': expandedClockingIn }" fill="none"
                                    :stroke="emptyObjectClockingIn ? '#EF4444' : '#000000'" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7">
                                    </path>
                                </svg>
                            </div>
                        </div>
                        <div v-if="expandedClockingIn && permission.typeOfPermissionId == 6" class="rounded-b-md">
                            <div class="mb-3 md:flex flex-row justify-items-start mx-4 items-center">
                                <div class="mb-4 md:mr-0 md:mb-0 basis-1/2">
                                    <label class="block mb-2 text-xs text-black">
                                        Motivo permiso <span class="text-red-600 font-extrabold">*</span>
                                    </label>
                                    <div class="font-semibold relative flex h-8 w-full flex-row-reverse ">
                                        <Multiselect placeholder="Seleccione permiso"
                                            v-model="permission.permissionReasonId" :options="permissionReasons"
                                            :searchable="true" />
                                        <LabelToInput icon="list" />
                                    </div>
                                    <InputError v-for="(item, index) in errors.permissionReasonId" :key="index" class="mt-2"
                                        :message="item" />
                                </div>
                                <div class="mb-4 md:mr-2 md:mb-0 basis-1/2 mx-4"
                                    style="border: none; background-color: transparent;">
                                    <label class="block mb-2 text-xs text-black" for="observation">
                                        Observacion
                                    </label>
                                    <textarea v-model="permission.observation" id="observation" name="observation"
                                        class="resize-none w-full h-12 overflow-y-auto peer text-xs font-semibold rounded-md border border-slate-400 px-2 text-slate-900 transition-colors duration-300 focus:border-[#001b47] focus:outline-none"
                                        @input="validateInput('observation', limit = 250)">
                                    </textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- End of the 'div' that wraps the form -->

                <div class="flex justify-center mb-2 mt-1">
                    <div class="flex items-center">
                        <GeneralButton class="mr-1" text="Cancelar" icon="delete" @click="$emit('cerrar-modal')" />
                        <GeneralButton v-if="!noTime && !negativeTime && modalData == ''" class="ml-1"
                            color="bg-green-700 hover:bg-green-800" text="Guardar" icon="add" @click="storePermission()" />
                        <GeneralButton v-if="!noTime && !negativeTime && modalData != ''" class="ml-1"
                            color="bg-orange-700 hover:bg-orange-800" text="Actualizar" icon="add"
                            @click="updatePermission()" />
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
        },
    },
    created() { },
    data: function (data) {
        return {
            expanded: false,
            expandedDetails: true,
            expandedClockingIn: false,
            noTime: false,
            negativeTime: false,
            refresh: false,
            loads: 0,
            totalHours: '',
            totalHoursFormat: '',
            configTime: {
                enableTime: true,
                noCalendar: true,
                dateFormat: "H:i",
                time_24hr: true
            },
            errors: [],
            employees: [],
            permissionReasons: [],
            isLoading: false,
            jobPositions: [],
            typesOfPermissions: [],
            showPermissionInfo: {
                totalTime: '',
                acumulatedTime: '',
                remainingTime: '',
                initialRemaining: ''
            },
            permission: {
                permissionId: '',
                employeeId: '',
                jobPositionId: '',
                typeOfPermissionId: '',
                startDate: '',
                endDate: '',
                startTime: '',
                endTime: '',
                comingBack: '',
                destination: '',
                description: '',
                periodOfTime: '',
                permissionReasonId: ''
            },
            config: {
                monthSelectorType: 'static',
                altFormat: "d/m/Y",
                dateFormat: "Y/m/d",
                altInput: true,
                onOpen: (selectedDates, dateStr, instance) => {
                    if (this.permission.typeOfPermissionId === 6) {
                        instance.set("maxDate", new Date()); // Actualizamos maxDate si es necesario
                    } else {
                        instance.set("maxDate", null); // Restablecemos maxDate si no es necesario
                    }
                },
                locale: {
                    firstDayOfWeek: 0,
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
        validateInput(field, limit) {
            if (this.permission[field].length > limit) {
                this.permission[field] = this.permission[field].substring(0, limit);
            }
        },
        savePermission(url) {
            axios.post(url, this.permission)
                .then((response) => {
                    this.handleSuccessResponse(response.data.mensaje)
                })
                .catch((errors) => {
                    this.handleErrorResponse(errors)
                });
        },
        handleSuccessResponse(message) {
            this.showToast(toast.success, message);
            this.$emit("get-table");
            this.$emit("cerrar-modal");
        },
        handleErrorResponse(errors) {
            if (errors.response.status === 422) {
                if (errors.response.data.logical_error) {
                    this.showToast(toast.error, errors.response.data.logical_error);
                    if (errors.response.data.refresh) {
                        this.refresh = errors.response.data.refresh
                        this.getPermissionData()
                    }
                    if (errors.response.data.close) {
                        this.$emit("get-table");
                        this.$emit("cerrar-modal");
                    }
                } else {
                    this.showToast(toast.warning, "Tienes algunos errores por favor verifica tus datos.");
                    this.errors = errors.response.data.errors;
                    console.log(this.errors);
                    if (this.errors.startDate || this.errors.endDate || this.errors.startTime || this.errors.endTime) {
                        this.expandedDetails ? '' : this.toggleAccordion('expandedDetails')
                    } else {
                        if (this.errors.destination || this.errors.comingBack || this.errors.description) {
                            this.expanded ? '' : this.toggleAccordion('expanded')
                        } else {
                            this.expandedClockingIn ? '' : this.toggleAccordion('expandedClockingIn')
                        }
                    }
                }
            } else {
                this.manageError(errors, this)
            }
        },
        storePermission() {
            console.log(this.permission);
            this.$swal
                .fire({
                    title: '¿Está seguro de guardar el permiso?',
                    icon: 'question',
                    iconHtml: '❓',
                    confirmButtonText: 'Si, Guardar',
                    confirmButtonColor: '#141368',
                    cancelButtonText: 'Cancelar',
                    showCancelButton: true,
                    showCloseButton: true
                })
                .then((result) => {
                    if (result.isConfirmed) {
                        let url = '/store-employee-permission'
                        this.savePermission(url)
                    }
                });
        },
        updatePermission() {
            console.log(this.permission);
            this.$swal
                .fire({
                    title: '¿Está seguro de actualizar el permiso?',
                    icon: 'question',
                    iconHtml: '❓',
                    confirmButtonText: 'Si, Actualizar',
                    confirmButtonColor: '#141368',
                    cancelButtonText: 'Cancelar',
                    showCancelButton: true,
                    showCloseButton: true
                })
                .then((result) => {
                    if (result.isConfirmed) {
                        let url = '/update-employee-permission'
                        this.savePermission(url)
                    }
                });
        },
        periodOfTimeSelected(option) {
            this.permission.periodOfTime = option
            this.resetRemainingValue()
            this.cleanPermissionDetails()
            this.updateTotalHours()
        },
        toggleAccordion(section) {
            switch (section) {
                case 'expanded':
                    this.expanded = !this.expanded;
                    this.expandedDetails = false;
                    this.expandedClockingIn = false;
                    break;
                case 'expandedDetails':
                    this.expandedDetails = !this.expandedDetails;
                    this.expanded = false;
                    this.expandedClockingIn = false;
                    break;
                case 'expandedClockingIn':
                    this.expandedClockingIn = !this.expandedClockingIn;
                    this.expanded = false;
                    this.expandedDetails = false;
                    break;
            }
        },
        formatTime(time) {
            if (this.permission.typeOfPermissionId == 5 || this.permission.typeOfPermissionId == 6) {
                return 'NO APLICA'
            } else {
                if (!time) {
                    return '0'
                } else {
                    const [hours, minutes] = time.split(':').map(Number);;
                    return `${hours > 0 ? hours + ' H. ' : ''} ${minutes > 0 ? minutes + ' min.' : ''}`
                }
            }
        },
        changeTypeOfPermission(selectedId) {
            this.errors = []
            const selectedPermission = this.typesOfPermissions.find(value => value.value === selectedId);
            //we change de values for this permission
            this.showPermissionInfo.totalTime = selectedPermission ? selectedPermission.limite_permiso : '';
            this.showPermissionInfo.acumulatedTime = selectedPermission ? selectedPermission.total_acumulado : '';
            this.showPermissionInfo.initialRemaining = selectedPermission ? selectedPermission.restante : '';
            this.showPermissionInfo.remainingTime = selectedPermission ? selectedPermission.restante : '';

            //If the Id is 6, the user can't select a "Diario" permission
            selectedId === 6 ? this.permission.periodOfTime = 1 : ''

            //validate if there is remaining time
            if (this.loads !== 0 || this.modalData.length === 0) {
                this.validateRemainingTime(this.showPermissionInfo.initialRemaining);
            }
            this.loads++
            //Refresh is a prop that I send from the back-end
            this.refresh ? this.updateTotalHours() : this.cleanPermissionDetails()
        },
        async getPermissionData() {
            try {
                let id_empleado = ''
                this.modalData != '' ? id_empleado = this.modalData.id_empleado : id_empleado=0
                this.isLoading = true;  // Activar el estado de carga
                const response = await axios.get(`/get-data-permission-modal/${id_empleado}`, {
                    params: {
                        ejecutar: this.permits.ejecutar
                    }
                });
                this.jobPositions = response.data.jobPositions
                this.typesOfPermissions = response.data.typesOfPermissions
                this.employees = response.data.employees;
                this.permissionReasons = response.data.permissionReasons
                if (this.permits.ejecutar == 0) {
                    this.permission.employeeId = this.employees[0].value
                }
            } catch (errors) {
                this.manageError(errors, this)
            } finally {
                this.isLoading = false;  // Desactivar el estado de carga
                if (this.refresh) {
                    this.refreshValues()
                } else {
                    this.modalData.length === 0 ? '' : this.setPermissionValues()
                }
            }
        },
        changeEmployee(id_empleado) {
            this.typesOfPermissions = []
            this.jobPositions = []
            this.permission.jobPositionId = ''
            this.permission.typeOfPermissionId = ''
            axios.get(`/get-permission-data/${id_empleado}`)
                .then((response) => {
                    this.typesOfPermissions = response.data.typesOfPermissions
                    this.jobPositions = response.data.jobPositions
                    this.isLoading = false;
                })
                .catch((errors) => {
                    console.log(errors);
                });
        },
        validateRemainingTime(time) {
            if (time) {
                const [hours, minutes] = time.split(':');
                if (parseInt(hours) == 0 && parseInt(minutes) == 0) {
                    this.showToast(toast.error, "No cuentas con horas disponibles para este tipo de permiso. Por favor, elige otro tipo de permiso o elimina uno del mismo tipo.");
                    this.noTime = true
                } else {
                    this.noTime = false
                }
            } else {
                this.noTime = false
            }
            this.showPermissionTypeAlert()
        },
        updateValueTime(event, field) {
            this.permission[field] = event.target.value
            this.updateTotalHours()
        },
        updateTotalHours() {
            this.errors = []
            //Antes tenia esto en los dos if && !this.noTime
            if (this.permission.periodOfTime === 1 && this.permission.typeOfPermissionId && !([5, 6].includes(this.permission.typeOfPermissionId))) {
                if (this.flagToUpdateTimeByHours) {
                    //console.log('primer if')
                    //We subtract end time minus start time.
                    const totalMinutes = this.substractTime(this.permission.endTime, this.permission.startTime)

                    if (totalMinutes>0 && !(this.permission.startTime === this.permission.endTime)) {
                        this.negativeTime = false
                        const totalRemainingMinutes = this.timeFormatToMinutes(this.showPermissionInfo.initialRemaining)

                        if (totalMinutes > totalRemainingMinutes) {
                            this.showToast(toast.error, `Has excedido la cantidad de tiempo, solo tienes ${this.formatTime(this.showPermissionInfo.initialRemaining)} disponible, debes ajustar los detalles del permiso.`);
                            this.noTime = true
                        } else {
                            this.noTime = false
                        }

                        const newTotal = this.minutesToTimeFormat(totalMinutes)
                        this.totalHours = this.formatTime(newTotal)
                        this.totalHoursFormat = newTotal

                        this.updateRemainingTime()
                    } else {
                        this.negativeTime = true
                        this.showToast(toast.error, 'La hora salida/fin debe ser mayor que la hora entrada/inicio.');
                    }
                } else {
                    this.resetTotalHours()
                }
            } else {
                if (this.permission.periodOfTime === 2 && this.permission.typeOfPermissionId && !([5, 6].includes(this.permission.typeOfPermissionId))) {
                    if (this.permission.startDate && this.permission.endDate) {

                        if (moment(this.permission.endDate).isBefore(this.permission.startDate)) {
                            this.negativeTime = true
                            this.showToast(toast.error, 'La fecha inicio no puede ser mayor que la fecha fin.');
                        } else {
                            this.negativeTime = false
                            let daysDifference = this.workDaysBetween(this.permission.startDate, this.permission.endDate)
                            const totalRemainingMinutes = this.timeFormatToMinutes(this.showPermissionInfo.initialRemaining)

                            if (daysDifference * 8 * 60 > totalRemainingMinutes) {
                                this.showToast(toast.error, `Has excedido la cantidad de tiempo, solo tienes ${this.formatTime(this.showPermissionInfo.initialRemaining)} disponible, debes ajustar los detalles del permiso.`);
                                this.noTime = true
                            } else {
                                this.noTime = false
                            }

                            this.totalHours = daysDifference * 8 + ' H.'

                            const timeToSubstract = this.minutesToTimeFormat(daysDifference * 8 * 60)
                            this.totalHoursFormat = timeToSubstract
                            const result = this.substractTime(this.showPermissionInfo.initialRemaining, timeToSubstract)
                            this.showPermissionInfo.remainingTime = this.minutesToTimeFormat(result)
                            //Method to update the time used
                            this.updateTimeUsed()
                        }
                    } else {
                        this.resetTotalHours()
                    }
                }
            }
            this.showPermissionTypeAlert()
        },
        showPermissionTypeAlert() {
            if (this.$refs.selectPerm) {
                const input = this.$refs.selectPerm.$el.querySelector('.multiselect-search');
                if (input) {
                    this.noTime ? input.classList.add('bg-red-300') : input.classList.remove('bg-red-300');
                }
            }
        },
        setPermissionValues() {
            if (this.modalData != '') {
                this.permission.permissionId = this.modalData.id_permiso
                this.permission.employeeId = this.modalData.id_empleado
                this.permission.jobPositionId = this.modalData.id_plaza_asignada
                this.permission.typeOfPermissionId = this.modalData.id_tipo_permiso
                this.changeTypeOfPermission(this.permission.typeOfPermissionId)
                this.permission.periodOfTime = this.modalData.fecha_fin_permiso ? 2 : 1;
                this.permission.startDate = this.modalData.fecha_inicio_permiso
                this.permission.endDate = this.modalData.fecha_fin_permiso ? this.modalData.fecha_fin_permiso : ''
                this.permission.startTime = this.modalData.hora_entrada_permiso ? this.modalData.hora_entrada_permiso : ''
                this.permission.endTime = this.modalData.hora_salida_permiso ? this.modalData.hora_salida_permiso : ''
                if (this.permission.typeOfPermissionId != 5 && this.permission.typeOfPermissionId != 6) {
                    //Restamos los valores final con inicial de tiempo, luego le sumamos el valor restante inicial
                    let timeInMinutes = ''
                    if (this.permission.periodOfTime === 1) {
                        timeInMinutes = this.substractTime(this.permission.endTime, this.permission.startTime)
                    } else {
                        timeInMinutes = (this.workDaysBetween(this.permission.startDate, this.permission.endDate)) * 8 * 60
                    }
                    const suma = this.addTime(this.showPermissionInfo.initialRemaining, this.minutesToTimeFormat(timeInMinutes))
                    this.showPermissionInfo.initialRemaining = this.minutesToTimeFormat(suma)
                    this.updateTotalHours()
                }
                this.permission.permissionReasonId = this.modalData.id_motivo_permiso ? this.modalData.id_motivo_permiso : ''
                this.permission.observation = this.modalData.comentarios_permiso ? this.modalData.comentarios_permiso : ''
                this.permission.destination = this.modalData.destino_permiso ? this.modalData.destino_permiso : ''
                this.permission.comingBack = this.modalData.retornar_empleado_permiso
            }
        },
        updateRemainingTime() {
            if (this.permission.periodOfTime == 1) {
                const formatStart = moment(this.permission.startTime, 'HH:mm')
                const formatEnd = moment(this.permission.endTime, 'HH:mm')
                var duration = moment.duration(formatEnd.diff(formatStart))

                const durationFormatted = `${Math.floor(duration.asHours())}:${duration.minutes()}`;

                const result = this.substractTime(this.showPermissionInfo.initialRemaining, durationFormatted)
                this.showPermissionInfo.remainingTime = this.minutesToTimeFormat(result)
                //Method to update the time used
                this.updateTimeUsed()
            }
        },
        substractTime(hour1, hour2) { //must be format = 'HH:mm' or 'HH:mm:ss'
            const [endHours, endMinutes] = hour1.split(':').map(Number);
            const [startHours, startMinutes] = hour2.split(':').map(Number);
            const result = ((endHours * 60) + endMinutes) - ((startHours * 60) + startMinutes)
            if (result < 0) {
                return false
            } else {
                return result
            }
        },
        minutesToTimeFormat(min) { //return format 'HH:mm'
            if (min > 0) {
                const hours = Math.floor(min / 60);
                const minutes = min % 60;
                return `${hours}:${minutes}`;
            } else {
                return false;
            }
        },
        timeFormatToMinutes(timeFormat) {
            if (timeFormat) {
                const [hours, minutes] = timeFormat.split(':').map(Number);
                return hours * 60 + minutes;
            } else {
                return false
            }
        },
        cleanPermissionDetails() {
            this.negativeTime = false
            this.permission.startDate = ''
            this.permission.endDate = ''
            this.permission.startTime = ''
            this.permission.endTime = ''
            this.totalHours = ''
        },
        addTime(hour1, hour2) {
            const [endHours, endMinutes] = hour1.split(':').map(Number);
            const [startHours, startMinutes] = hour2.split(':').map(Number);
            const result = ((endHours * 60) + endMinutes) + ((startHours * 60) + startMinutes)
            return result ? result : false
        },
        workDaysBetween(date1, date2) {
            const startDateFormated = moment(date1, 'YYYY/MM/DD').toDate()
            const endDateFormated = moment(date2, 'YYYY/MM/DD').toDate()

            let currentDate = new Date(startDateFormated);
            let daysDifference = 0;

            while (currentDate <= endDateFormated) {
                const dayOfWeek = currentDate.getDay(); // 0 (domingo) a 6 (sábado)
                if (dayOfWeek !== 0 && dayOfWeek !== 6) {
                    daysDifference++;
                }
                currentDate.setDate(currentDate.getDate() + 1);
            }
            return daysDifference ?? false
        },
        resetRemainingValue() {
            if (this.totalHoursFormat) {
                if (this.showPermissionInfo.remainingTime) {
                    const remainingInMinutes = this.addTime(this.showPermissionInfo.remainingTime, this.totalHoursFormat);
                    this.showPermissionInfo.remainingTime = this.minutesToTimeFormat(remainingInMinutes)
                } else {
                    this.showPermissionInfo.remainingTime = this.totalHoursFormat
                }
                //Method to update the time used
                this.updateTimeUsed()
            }
        },
        updateTimeUsed() {
            const remainingTime = this.showPermissionInfo.remainingTime || '00:00';
            const result = this.substractTime(this.showPermissionInfo.totalTime, remainingTime);
            this.showPermissionInfo.acumulatedTime = this.minutesToTimeFormat(result);
        },
        resetTotalHours() {
            this.totalHoursFormat = ''
            this.totalHours = '0 H.'
            this.noTime = false
        },
        refreshValues() {
            this.changeTypeOfPermission(this.permission.typeOfPermissionId)
            this.refresh = false
        }
    },
    watch: {
        showModalJobPermissions: function (value, oldValue) {
            if (value) {
                Object.keys(this.showPermissionInfo).forEach(key => {
                    this.showPermissionInfo[key] = '';
                });
                Object.keys(this.permission).forEach(key => {
                    this.permission[key] = '';
                });
                this.errors = []
                this.loads = 0
                this.getPermissionData()
                this.noTime = false
                this.totalHours = ''
                this.permission.periodOfTime = 1
                this.expandedDetails ? '' : this.toggleAccordion('expandedDetails')
            }
        },
    },
    computed: {
        validateEndDate: function () {
            if (this.permission.periodOfTime === 1 || this.permission.typeOfPermissionId == 6) {
                return true
            } else {
                return false
            }
        },
        emptyObjectMision: function () {
            if (!this.permission.destination || (this.permission.periodOfTime===1 && this.permission.comingBack === '')) {
                return true
            } else {
                return false
            }
        },
        emptyObjectClockingIn: function () {
            if (!this.permission.permissionReasonId) {
                return true
            } else {
                return false
            }
        },
        labelHoraInicio: function () {
            let msg = 'Hora Inicio'
            this.permission.typeOfPermissionId == 5 ? msg = 'Hora Salida' : ''
            this.permission.typeOfPermissionId == 6 ? msg = 'Hora Entrada' : ''
            return msg
        },
        labelHoraFin: function () {
            let msg = 'Hora Fin'
            this.permission.typeOfPermissionId == 6 ? msg = 'Hora Salida' : ''
            return msg
        },
        flagToUpdateTimeByHours: function () {
            if (this.permission.startTime && this.permission.endTime && this.permission.typeOfPermissionId != 6) {
                return true
            } else {
                return false
            }
        },
        disableForChecks : function () {
            if(this.permission.typeOfPermissionId == 6){
                this.permission.periodOfTime=1
                return true //disabled
            }else{
                if(this.timeFormatToMinutes(this.showPermissionInfo.initialRemaining) < 480 && this.permission.typeOfPermissionId != 5){
                    this.permission.periodOfTime=1
                    return true
                }else{
                    return false
                }
            }
        },  
    },
};
</script>

<style scoped>
.rotate-180 {
    transform: rotate(180deg);
}

.expand-animation {
    animation-name: expand-pulse;
    animation-duration: 1.4s;
    /* Ajusta la duración */
    animation-timing-function: ease-in-out;
    animation-iteration-count: infinite;
}

@keyframes expand-pulse {

    0%,
    100% {
        transform: scale(1);
    }

    50% {
        transform: scale(1.02);
        /* Ajusta el valor de escala */
    }
}
</style>