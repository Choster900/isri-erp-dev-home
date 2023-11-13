<script setup>
import Modal from "@/Components-ISRI/AllModal/Modal.vue";
import { toast } from "vue3-toastify";
import "vue3-toastify/dist/index.css";
import axios from "axios";
import moment from 'moment';

</script>

<template>
    <div class="m-1.5">
        <Modal :show="showModalJobPosition" @close="$emit('cerrar-modal')" :modal-title="'Gestion puesto de trabajo. '"
            maxWidth="2xl">

            <div class="px-5 py-3">

                <div v-if="isLoading" class="flex items-center justify-center h-[100px]">
                    <div role="status" class="flex items-center">
                        <svg aria-hidden="true"
                            class="w-8 h-8 mr-2 text-gray-200 animate-spin dark:text-gray-600 fill-blue-800"
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

                <transition v-else name="fade" mode="out-in">
                    <!-- Show job positions by employee -->
                    <div v-if="showJobPositions && deleting === false">
                        <div class="mx-4 sm:flex sm:justify-between sm:items-center mb-1">
                            <p class="text-base font-medium text-navy-700 underline underline-offset-2">
                                {{ getEmployeeName }}
                            </p>
                            <div v-if="employee.id_estado_empleado === 1" class="grid grid-flow-col sm:auto-cols-max sm:justify-end gap-2">
                                <GeneralButton color="bg-green-700  hover:bg-green-800" text="Agregar" icon="add"
                                    @click="showInputsForNewRole()" />
                            </div>
                        </div>
                        <h1 v-if="jobPositions.length > 0"
                            class="text-center mb-1 font-semibold text-slate-800 text-medium">
                            PUESTOS
                            ASIGNADOS</h1>
                        <h1 v-else class="text-center mb-1 font-semibold text-slate-800 text-medium">
                            SIN PUESTOS ASIGNADOS</h1>

                        <div v-if="jobPositions.length > 0"
                            class="justify-center space-between flex text-center items-center py-3 border-b border-t border-slate-300 mt-3 mb-1 flex-row bg-gray-100">
                            <div class="mx-4 flex cursor-pointer justify-center" title="Muestra puestos vigentes."
                                :class="activeSelected ? 'text-green-500 hover:text-green-700 border-b-2 border-green-500 hover:border-green-700' : 'text-green-400 hover:text-green-600'"
                                @click="activeSelected = true">
                                <p class="pl-3">VIGENTES</p>
                                <div class="w-8 ml-2">
                                    <svg width="24px" height="24px" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg" stroke="currentColor">
                                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                        <g id="SVGRepo_iconCarrier">
                                            <path d="M4 12.6111L8.92308 17.5L20 6.5" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"></path>
                                        </g>
                                    </svg>
                                </div>
                            </div>
                            <div class="mx-4 cursor-pointer flex" title="Muestra puestos inhabilitados."
                                :class="!activeSelected ? 'border-b-2 border-red-500 hover:border-red-700 text-red-500 hover:text-red-700' : 'text-red-400 hover:text-red-600'"
                                @click="activeSelected = false">
                                <p class="pl-3">INHABILITADOS</p>
                                <div class="w-8 ml-2">
                                    <svg fill="currentColor" height="18px" width="18px" version="1.1" class="mt-0.5"
                                        xmlns:sketch="http://www.bohemiancoding.com/sketch/ns"
                                        xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                        viewBox="0 0 24 24" xml:space="preserve" stroke="currentColor">
                                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                        <g id="SVGRepo_iconCarrier">
                                            <g id="inactive">
                                                <path
                                                    d="M13.6,23.9c-7.8,1-14.5-5.6-13.5-13.5c0.7-5.3,5-9.7,10.3-10.3c7.8-1,14.5,5.6,13.5,13.5C23.2,18.9,18.9,23.2,13.6,23.9z M13.7,2.1C6.9,1,1,6.9,2.1,13.7c0.7,4.1,4,7.5,8.2,8.2C17.1,23,23,17.1,21.9,10.3C21.2,6.2,17.8,2.8,13.7,2.1z">
                                                </path>
                                                <polyline points="5.6,4.2 19.8,18.3 18.4,19.8 4.2,5.6 "></polyline>
                                            </g>
                                        </g>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="overflow-y-auto max-h-[340px]">
                            <div v-for="(jobPosition, index) in (activeSelected ? activeRoles : inactiveRoles)" :key="index"
                                class="w-full bg-gray-50 flex flex-col justify-start relative sm:py-2 mb-1 mt-1">
                                <div class="max-w-full mx-4 relative">
                                    <div class="absolute inset-0 bg-gray-600 rounded-lg blur opacity-50 z-0"></div>
                                    <div
                                        class="relative z-10 px-5 py-2 bg-white ring-1 ring-gray-900/5 rounded-lg leading-none">
                                        <!-- Dependencia -->
                                        <div class="w-full">
                                            <p class="text-sm text-gray-600">Dependencia</p>
                                            <p class="text-base font-medium text-navy-700 dark:text-white">
                                                {{ formattedDependency(jobPosition.dependencia) }}
                                            </p>
                                            <button v-if="jobPosition.estado_plaza_asignada === 1 && activeRoles.length > 1"
                                                class="mt-1 text-red-500 absolute top-0 right-0 mr-2 cursor-pointer"
                                                title="Inhabilitar puesto." @click="setSpecificRole(index)">
                                                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                                                    class="w-6 h-6">
                                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                                        d="M17 5V4C17 2.89543 16.1046 2 15 2H9C7.89543 2 7 2.89543 7 4V5H4C3.44772 5 3 5.44772 3 6C3 6.55228 3.44772 7 4 7H5V18C5 19.6569 6.34315 21 8 21H16C17.6569 21 19 19.6569 19 18V7H20C20.5523 7 21 6.55228 21 6C21 5.44772 20.5523 5 20 5H17ZM15 4H9V5H15V4ZM17 7H7V18C7 18.5523 7.44772 19 8 19H16C16.5523 19 17 18.5523 17 18V7Z"
                                                        fill="currentColor" />
                                                    <path d="M9 9H11V17H9V9Z" fill="currentColor" />
                                                    <path d="M13 9H15V17H13V9Z" fill="currentColor" />
                                                </svg>
                                            </button>
                                            <button v-if="jobPosition.estado_plaza_asignada === 1"
                                                title="Modificar información del puesto."
                                                class="mt-1 text-yellow-600 absolute top-0 right-0 cursor-pointer"
                                                :class="activeRoles.length > 1 ? 'mr-9' : 'mr-2'"
                                                @click="editRoleAssigned(jobPosition)">
                                                <svg width="24px" height="24px" viewBox="0 0 1024 1024"
                                                    xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                                    stroke="currentColor">
                                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round"
                                                        stroke-linejoin="round"></g>
                                                    <g id="SVGRepo_iconCarrier">
                                                        <path fill="currentColor"
                                                            d="M832 512a32 32 0 1 1 64 0v352a32 32 0 0 1-32 32H160a32 32 0 0 1-32-32V160a32 32 0 0 1 32-32h352a32 32 0 0 1 0 64H192v640h640V512z">
                                                        </path>
                                                        <path fill="currentColor"
                                                            d="m469.952 554.24 52.8-7.552L847.104 222.4a32 32 0 1 0-45.248-45.248L477.44 501.44l-7.552 52.8zm422.4-422.4a96 96 0 0 1 0 135.808l-331.84 331.84a32 32 0 0 1-18.112 9.088L436.8 623.68a32 32 0 0 1-36.224-36.224l15.104-105.6a32 32 0 0 1 9.024-18.112l331.904-331.84a96 96 0 0 1 135.744 0z">
                                                        </path>
                                                    </g>
                                                </svg>
                                            </button>
                                        </div>
                                        <!-- Plaza - Codigo -->
                                        <div class="flex flex-col space-y-1 md:flex-row md:space-x-2">
                                            <div class="w-full md:w-[85%]">
                                                <p class="text-sm text-gray-600">Plaza</p>
                                                <p class="text-base font-medium text-navy-700 dark:text-white">
                                                    {{ jobPosition.detalle_plaza.plaza.nombre_plaza }}
                                                </p>
                                            </div>
                                            <div class="w-full md:w-[15%]">
                                                <p class="text-sm text-gray-600">ID SIRHI</p>
                                                <p class="text-base font-medium text-navy-700 dark:text-white">
                                                    {{ jobPosition.detalle_plaza.id_puesto_sirhi_det_plaza }}
                                                </p>
                                            </div>
                                        </div>
                                        <!-- Tipo Contrato - Fecha Asignacion -->
                                        <div class="flex flex-col space-y-1 md:flex-row md:space-x-2">
                                            <div class="w-full md:w-[50%]">
                                                <p class="text-sm text-gray-600">Tipo Contratación</p>
                                                <p class="text-base font-medium text-navy-700 dark:text-white">
                                                    {{ jobPosition.detalle_plaza.tipo_contrato.nombre_tipo_contrato }}
                                                </p>
                                            </div>
                                            <div class="w-full md:w-[35%]">
                                                <p class="text-sm text-gray-600">Fecha Nombramiento</p>
                                                <p class="text-base font-medium text-navy-700 dark:text-white">
                                                    {{ moment(jobPosition.fecha_plaza_asignada).format('DD/MM/YYYY') }}
                                                </p>
                                            </div>
                                            <div class="w-full md:w-[15%]">
                                                <p class="text-sm text-gray-600">Salario</p>
                                                <p class="text-base font-medium text-navy-700 dark:text-white">
                                                    ${{ jobPosition.salario_plaza_asignada }}
                                                </p>
                                            </div>
                                        </div>
                                        <!-- Fecha desvinculacion - Motivo desvinculacion -->
                                        <div v-if="jobPosition.id_motivo_desvinculo_laboral"
                                            class="flex flex-col space-y-1 md:flex-row md:space-x-2">
                                            <div class="w-full md:w-[49%]">
                                                <p class="text-sm text-gray-600">Motivo Desvinculación</p>
                                                <p class="text-base font-medium text-red-600 dark:text-white">
                                                    {{
                                                        jobPosition.motivo_desvinculo_laboral.nombre_motivo_desvinculo_laboral
                                                    }}
                                                </p>
                                            </div>
                                            <div class="w-full md:w-[51%]">
                                                <p class="text-sm text-gray-600">Fecha Desvinculación</p>
                                                <p class="text-base font-medium text-red-600 dark:text-white">
                                                    {{
                                                        moment(jobPosition.fecha_renuncia_plaza_asignada).format('DD/MM/YYYY')
                                                    }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <h1 v-if="activeSelected && activeRoles.length === 0"
                                    class="text-center mb-1 font-semibold text-slate-800 text-medium py-4">
                                    SIN PUESTOS VIGENTES</h1>
                                <h1 v-if="!activeSelected && inactiveRoles.length === 0"
                                    class="text-center mb-1 font-semibold text-slate-800 text-medium py-4">
                                    SIN PUESTOS INHABILITADOS</h1>
                            </div>
                        </div>

                        <div class="flex justify-center mt-1 border-t border-slate-300 bg-gray-100">
                            <GeneralButton class="mr-1 mt-2" text="Cerrar" icon="delete" @click="$emit('cerrar-modal')" />
                        </div>
                    </div>
                    <!-- Show form to add a new job position -->
                    <div v-else-if="deleting === false">
                        <div>
                            <div class="mb-2 md:flex flex-row justify-between">
                                <div class="md:w-1/2">
                                    <span class="font-semibold text-slate-800 text-lg underline underline-offset-2">
                                        Informacion de contratacion
                                    </span>
                                </div>
                            </div>
                            <div class="mb-5 md:flex flex-row justify-items-start">
                                <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                                    <label class="block mb-2 text-xs font-light text-gray-600">
                                        Dependencia <span class="text-red-600 font-extrabold">*</span>
                                    </label>
                                    <div class="font-semibold relative flex h-8 w-full flex-row-reverse ">
                                        <Multiselect placeholder="Seleccione dependencia" v-model="jobPosition.dependencyId"
                                            :options="dependencies" :searchable="true" />
                                        <LabelToInput icon="list" />
                                    </div>
                                    <InputError v-for="(item, index) in errors['jobPosition.dependencyId']" :key="index"
                                        class="mt-2" :message="item" />
                                </div>
                                <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                                    <label class="block mb-2 text-xs font-light text-gray-600">
                                        Plaza <span class="text-red-600 font-extrabold">*</span>
                                    </label>
                                    <div class="font-semibold relative flex h-8 w-full flex-row-reverse ">
                                        <Multiselect placeholder="Seleccione Plaza" v-model="jobPosition.jobPositionId"
                                            :options="jobPositionsToSelect" :searchable="true" :disabled="edit"
                                            @change="setSalaryLimits($event)" />
                                        <LabelToInput icon="list" />
                                    </div>
                                    <InputError v-for="(item, index) in errors['jobPosition.jobPositionId']" :key="index"
                                        class="mt-2" :message="item" />
                                </div>
                            </div>
                            <div class="mb-5 md:flex flex-row justify-items-start">
                                <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                                    <label class="block mb-2 text-xs font-light text-gray-600" for="fecha_nacimiento">
                                        Fecha de nombramiento <span class="text-red-600 font-extrabold">*</span>
                                    </label>
                                    <div class="relative flex">
                                        <LabelToInput icon="date" />
                                        <flat-pickr
                                            class="peer font-semibold text-xs cursor-pointer rounded-r-md border h-8 border-slate-400 px-2 text-slate-900 placeholder-slate-400 transition-colors duration-300 focus:border-[#001b47] focus:outline-none w-full"
                                            :config="config" v-model="jobPosition.dateOfHired"
                                            :placeholder="'Seleccione fecha'" />
                                    </div>
                                    <InputError v-for="(item, index) in errors['jobPosition.dateOfHired']" :key="index"
                                        class="mt-2" :message="item" />
                                </div>
                                <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                                    <TextInput id="salary" v-model="jobPosition.salary" type="text"
                                        :placeholder="selectedJobPosition ? 'Salario ($' + lowerSalaryLimit + ' - $' + upperSalaryLimit + ')' : 'Salario (Seleccione plaza)'"
                                        :disabled="!selectedJobPosition"
                                        @update:modelValue="validateEmployeeInputs('salary', 7, false, true)">
                                        <LabelToInput icon="money" forLabel="salary" />
                                    </TextInput>
                                    <InputError v-for="(item, index) in errors['jobPosition.salary']" :key="index"
                                        class="mt-2" :message="item" />
                                </div>
                            </div>
                            <div class="mb-10 md:flex flex-row justify-items-start">
                                <div class="mb-4 md:mr-2 md:mb-0 basis-1/3">
                                    <TextInput id="contrato_plaza" v-model="jobPosition.contrato_plaza" type="text" placeholder="Numero contrato"
                                        @update:modelValue="validateEmployeeInputs('contrato_plaza', 10, false, false)" :required="false">
                                        <LabelToInput icon="standard" forLabel="contrato_plaza" />
                                    </TextInput>
                                    <InputError v-for="(item, index) in errors['jobPosition.contrato_plaza']" :key="index"
                                        class="mt-2" :message="item" />
                                </div>
                                <div class="mb-4 md:mr-2 md:mb-0 basis-1/3">
                                    <TextInput id="account" v-model="jobPosition.account" type="text" placeholder="Partida"
                                        @update:modelValue="validateEmployeeInputs('account', 3, true, false)">
                                        <LabelToInput icon="standard" forLabel="account" />
                                    </TextInput>
                                    <InputError v-for="(item, index) in errors['jobPosition.account']" :key="index"
                                        class="mt-2" :message="item" />
                                </div>
                                <div class="mb-4 md:mr-2 md:mb-0 basis-1/3">
                                    <TextInput id="subaccount" v-model="jobPosition.subaccount" type="text"
                                        placeholder="Subpartida"
                                        @update:modelValue="validateEmployeeInputs('subaccount', 3, true, false)">
                                        <LabelToInput icon="standard" forLabel="subaccount" />
                                    </TextInput>
                                    <InputError v-for="(item, index) in errors['jobPosition.subaccount']" :key="index"
                                        class="mt-2" :message="item" />
                                </div>
                            </div>
                        </div>
                        <div class="flex justify-center mt-4">
                            <GeneralButton v-if="!edit" class="mr-1" color="bg-green-700 hover:bg-green-800" text="Guardar"
                                icon="add" @click="storeJobPosition()" />
                            <GeneralButton v-else class="mr-1" color="bg-orange-700 hover:bg-orange-800" text="Actualizar"
                                icon="update" @click="updateJobPosition()" />
                            <GeneralButton text="Cancelar" icon="delete"
                                @click="showJobPositions = true; cleanJobPositionInputs(); edit = false" />
                        </div>
                    </div>
                    <div v-else> <!-- Inhabilitacion de puesto -->
                        <div class="mb-2 md:flex flex-row justify-between">
                            <div class="md:w-full text-center">
                                <span class="font-semibold text-slate-800 text-lg underline underline-offset-2">
                                    Inhabilitación de puesto
                                </span>
                            </div>
                        </div>
                        <div class="w-full bg-gray-50 flex flex-col justify-start relative sm:py-2 mb-2 mt-1">
                            <div class="max-w-full mx-4 relative">
                                <div class="absolute inset-0 bg-gray-600 rounded-lg blur opacity-50 z-0"></div>
                                <div
                                    class="relative z-10 px-5 py-2 bg-white ring-1 ring-gray-900/5 rounded-lg leading-none">
                                    <!-- Dependencia -->
                                    <div class="w-full">
                                        <p class="text-sm text-gray-600">Dependencia</p>
                                        <p class="text-base font-medium text-navy-700 dark:text-white">
                                            {{ formattedDependency(activeRoles[indexToDelete].dependencia) }}
                                        </p>
                                    </div>
                                    <!-- Plaza - Codigo -->
                                    <div class="flex flex-col space-y-1 md:flex-row md:space-x-2">
                                        <div class="w-full md:w-[85%]">
                                            <p class="text-sm text-gray-600">Plaza</p>
                                            <p class="text-base font-medium text-navy-700 dark:text-white">
                                                {{ activeRoles[indexToDelete].detalle_plaza.plaza.nombre_plaza }}
                                            </p>
                                        </div>
                                        <div class="w-full md:w-[15%]">
                                            <p class="text-sm text-gray-600">ID SIRHI</p>
                                            <p class="text-base font-medium text-navy-700 dark:text-white">
                                                {{ activeRoles[indexToDelete].detalle_plaza.id_puesto_sirhi_det_plaza }}
                                            </p>
                                        </div>
                                    </div>
                                    <!-- Tipo Contrato - Fecha Asignacion -->
                                    <div class="flex flex-col space-y-1 md:flex-row md:space-x-2">
                                        <div class="w-full md:w-[50%]">
                                            <p class="text-sm text-gray-600">Tipo Contratación</p>
                                            <p class="text-base font-medium text-navy-700 dark:text-white">
                                                {{
                                                    activeRoles[indexToDelete].detalle_plaza.tipo_contrato.nombre_tipo_contrato
                                                }}
                                            </p>
                                        </div>
                                        <div class="w-full md:w-[35%]">
                                            <p class="text-sm text-gray-600">Fecha Nombramiento</p>
                                            <p class="text-base font-medium text-navy-700 dark:text-white">
                                                {{
                                                    moment(activeRoles[indexToDelete].fecha_plaza_asignada).format('DD/MM/YYYY')
                                                }}
                                            </p>
                                        </div>
                                        <div class="w-full md:w-[15%]">
                                            <p class="text-sm text-gray-600">Salario</p>
                                            <p class="text-base font-medium text-navy-700 dark:text-white">
                                                ${{ activeRoles[indexToDelete].salario_plaza_asignada }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-5 md:flex flex-row justify-items-start">
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                                <label class="block mb-2 text-xs font-light text-gray-600">
                                    Motivo desvinculacion <span class="text-red-600 font-extrabold">*</span>
                                </label>
                                <div class="font-semibold relative flex h-8 w-full flex-row-reverse ">
                                    <Multiselect placeholder="Seleccione Motivo" v-model="jobPosition.idDissociate"
                                        :options="reasonsForDissociate" :searchable="true" />
                                    <LabelToInput icon="list" />
                                </div>
                                <InputError v-for="(item, index) in errors['jobPosition.idDissociate']" :key="index"
                                    class="mt-2" :message="item" />
                            </div>
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                                <label class="block mb-2 text-xs font-light text-gray-600">
                                    Fecha desvinculacion <span class="text-red-600 font-extrabold">*</span>
                                </label>
                                <div class="relative flex">
                                    <LabelToInput icon="date" />
                                    <flat-pickr
                                        class="peer font-semibold text-xs cursor-pointer rounded-r-md border h-8 border-slate-400 px-2 text-slate-900 placeholder-slate-400 transition-colors duration-300 focus:border-[#001b47] focus:outline-none w-full"
                                        :config="config" v-model="jobPosition.dateOfDissociate"
                                        :placeholder="'Seleccione fecha'" />
                                </div>
                                <InputError v-for="(item, index) in errors['jobPosition.dateOfDissociate']" :key="index"
                                    class="mt-2" :message="item" />
                            </div>
                        </div>
                        <div class="flex justify-center mt-4 pb-4">
                            <GeneralButton class="mr-1" color="bg-red-700 hover:bg-red-800" text="Inhabilitar" icon="delete"
                                @click="desactiveRole()" />
                            <GeneralButton text="Cancelar" icon="delete" @click=" deleting = false" />
                        </div>
                    </div>
                </transition>
            </div>
        </Modal>
    </div>
</template>

<script>
export default {
    props: {
        showModalJobPosition: {
            type: Boolean,
            default: false,
        },
        modalData: {
            type: Array,
            default: []
        }
    },
    created() {
        this.setInitialInformation()
    },
    data: function (data) {
        return {
            activeRoles: [],
            inactiveRoles: [],
            edit: false,
            deleting: false,
            indexToDelete: '',
            activeSelected: true,
            errors: [],
            showJobPositions: true,
            jobPositionsToSelect: [],
            dependencies: [],
            employee: [],
            reasonsForDissociate: [],
            isLoading: false,
            loadingCount: 0, // Inicialmente no hay solicitudes en curso
            selectedJobPosition: '',
            lowerSalaryLimit: '',
            upperSalaryLimit: '',
            jobPositions: [],
            jobPosition: {
                id: '',
                dependencyId: '',
                jobPositionId: '',
                salary: '',
                account: '',
                subaccount: '',
                workAreaId: '',
                dateOfHired: '',
                idDissociate: '',
                dateOfDissociate: '',
                contrato_plaza:''
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
        setInitialInformation() {
            this.showJobPositions = true
            this.loadingCount = 0 //Conteo para el loader
            this.errors = []
            this.activeSelected = true
            this.edit = false
            this.deleting = false
            this.indexToDelete = ''
            this.employee = JSON.parse(JSON.stringify(this.modalData));
            this.getJobPositions()
        },
        validateEmployeeInputs(field, limit, number, amount) {
            if (this.jobPosition[field].length > limit) {
                this.jobPosition[field] = this.jobPosition[field].substring(0, limit);
            }
            if (number) {
                this.jobPosition[field] = this.jobPosition[field].replace(/[^0-9]/g, '');
            }
            if (amount) {
                let x = this.jobPosition[field].replace(/^\./, '').replace(/[^0-9.]/g, '')
                this.jobPosition[field] = x
                const regex = /^(\d+)?([.]?\d{0,2})?$/
                if (!regex.test(this.jobPosition[field])) {
                    this.jobPosition[field] = this.jobPosition[field].match(regex) || x.substring(0, x.length - 1)
                }
            }
        },
        async getJobPositions() {
            this.loadingCount++; // Incrementa el contador antes de la solicitud
            this.isLoading = true;
            await axios.get("/get-job-positions-by-employee", {
                params: {
                    id_empleado: this.employee.id_empleado
                }
            })
                .then((response) => {
                    this.jobPositions = response.data.jobPositions.plazas_asignadas
                    this.jobPositionsToSelect = response.data.jobPositionsToSelect
                    this.dependencies = response.data.dependencies
                    this.reasonsForDissociate = response.data.reasonsForDissociate
                    this.setStatusForRoles(this.jobPositions)
                })
                .catch((errors) => {
                    this.manageError(errors, this)
                })
                .finally(() => {
                    this.loadingCount--; // Disminuye el contador después de la solicitud
                    if (this.loadingCount === 0) {
                        this.isLoading = false; // Desactiva el loader si no hay solicitudes en curso
                    }
                });
        },
        setSalaryLimits(jobPositionId) {
            const selectedJob = this.jobPositionsToSelect.find(value => value.value === jobPositionId);
            selectedJob ? this.lowerSalaryLimit = selectedJob.salario_base_plaza : this.lowerSalaryLimit = ''
            selectedJob ? this.upperSalaryLimit = selectedJob.salario_tope_plaza : this.upperSalaryLimit = ''
            selectedJob ? this.jobPosition.workAreaId = selectedJob.id_lt : this.jobPosition.workAreaId = ''
            selectedJob ? this.selectedJobPosition = true : this.selectedJobPosition = false
            !selectedJob ? this.jobPosition.salary = '' : ''
        },
        async storeJobPosition() {
            this.$swal
                .fire({
                    title: '¿Está seguro de asignar el nuevo puesto?',
                    icon: 'question',
                    iconHtml: '❓',
                    confirmButtonText: 'Si, Guardar',
                    confirmButtonColor: '#141368',
                    cancelButtonText: 'Cancelar',
                    showCancelButton: true,
                    showCloseButton: true
                })
                .then(async (result) => {
                    if (result.isConfirmed) {
                        let url = '/store-job-position'
                        this.saveJobPosition(url)
                    }
                });
        },
        async saveJobPosition(url) {
            this.loadingCount++; // Incrementa el contador antes de la solicitud
            this.isLoading = true;
            await axios.post(url, {
                jobPosition: this.jobPosition,
                employeeId: this.employee.id_empleado,
                upperSalaryLimit: this.upperSalaryLimit,
                lowerSalaryLimit: this.lowerSalaryLimit
            })
                .then(async (response) => {
                    this.handleSuccessResponse(response.data.mensaje);
                })
                .catch((errors) => {
                    this.handleErrorResponse(errors);
                })
                .finally(() => {
                    this.loadingCount--; // Disminuye el contador después de la solicitud
                    if (this.loadingCount === 0) {
                        this.isLoading = false; // Desactiva el loader si no hay solicitudes en curso
                    }
                });
        },
        async updateJobPosition() {
            this.$swal
                .fire({
                    title: '¿Está seguro de actualizar el puesto asignado?',
                    icon: 'question',
                    iconHtml: '❓',
                    confirmButtonText: 'Si, Actualizar',
                    confirmButtonColor: '#141368',
                    cancelButtonText: 'Cancelar',
                    showCancelButton: true,
                    showCloseButton: true
                })
                .then(async (result) => {
                    if (result.isConfirmed) {
                        let url = '/update-job-position'
                        this.saveJobPosition(url)
                    }
                });
        }, //Falta resolver orden de las peticiones
        handleSuccessResponse(message) {
            this.showToast(toast.success, message);
            this.cleanJobPositionInputs()
            this.getJobPositions()
            this.showJobPositions = true
            this.edit = false
            this.deleting = false
            this.$emit("get-table");
        },
        handleErrorResponse(errors) {
            if (errors.response.status === 422) {
                if (errors.response.data.logical_error) {
                    this.showToast(toast.error, errors.response.data.logical_error);
                } else {
                    this.errors = errors.response.data.errors;
                    this.showToast(toast.warning, "Tienes algunos errores por favor verifica tus datos.");
                }
            } else {
                this.manageError(errors, this)
            }
        },
        formattedDependency(dependencie) {
            const dependency = dependencie.parent_dependency || dependencie;
            return dependency.codigo_dependencia + ' - ' + dependencie.nombre_dependencia;
        },
        cleanJobPositionInputs() {
            Object.keys(this.jobPosition).forEach(key => {
                this.jobPosition[key] = '';
            });
        },
        async editRoleAssigned(role) {
            const idsPuesto = this.activeRoles.map(element => element.id_det_plaza);
            try {
                this.isLoading = true;  // Activar el estado de carga
                const response = await axios.post("/get-available-job-positions",
                    {
                        rolesExtraToInclude: idsPuesto
                    }
                );
                this.jobPositionsToSelect = response.data.jobPositionsToSelect
                this.jobPosition.dependencyId = role.dependencia.id_dependencia
                this.jobPosition.jobPositionId = role.id_det_plaza
                this.jobPosition.dateOfHired = role.fecha_plaza_asignada
                this.jobPosition.salary = role.salario_plaza_asignada
                this.jobPosition.account = role.partida_plaza_asignada
                this.jobPosition.subaccount = role.subpartida_plaza_asignada
                this.jobPosition.contrato_plaza = role.contrato_plaza_asignada
                this.jobPosition.id = role.id_plaza_asignada
                this.setSalaryLimits(role.id_det_plaza)
                this.errors = []
                this.showJobPositions = false
                this.edit = true
            } catch (errors) {
                this.manageError(errors, this)
            } finally {
                this.isLoading = false;  // Desactivar el estado de carga
            }
        },
        async showInputsForNewRole() {
            try {
                this.isLoading = true;  // Activar el estado de carga
                const response = await axios.post("/get-available-job-positions",
                    {
                        rolesExtraToInclude: ''
                    }
                );
                this.jobPositionsToSelect = response.data.jobPositionsToSelect
                this.showJobPositions = false
                this.errors = []
            } catch (errors) {
                this.manageError(errors, this)
            } finally {
                this.isLoading = false;  // Desactivar el estado de carga
            }
        },
        setSpecificRole(index) {
            this.deleting = true
            if (this.activeSelected) {
                this.indexToDelete = index
            }
            this.errors = []
            this.jobPosition.dateOfDissociate = ''
            this.jobPosition.idDissociate = ''
        },
        async desactiveRole() {
            this.jobPosition.id = this.activeRoles[this.indexToDelete].id_plaza_asignada
            this.$swal.fire({
                title: '¿Está seguro de inhabilitar el puesto?',
                icon: 'question',
                iconHtml: '❓',
                confirmButtonText: 'Si, inhabilitar',
                confirmButtonColor: '#DC2626',
                cancelButtonText: 'Cancelar',
                showCancelButton: true,
                showCloseButton: true
            })
                .then(async (result) => {
                    if (result.isConfirmed) {
                        this.loadingCount++; // Incrementa el contador antes de la solicitud
                        this.isLoading = true;
                        await axios.post('/desactive-job-position', { jobPosition: this.jobPosition, })
                            .then((response) => {
                                this.handleSuccessResponse(response.data.mensaje);
                            })
                            .catch((errors) => {
                                this.handleErrorResponse(errors);
                            })
                            .finally(() => {
                                this.loadingCount--; // Disminuye el contador después de la solicitud
                                if (this.loadingCount === 0) {
                                    this.isLoading = false; // Desactiva el loader si no hay solicitudes en curso
                                }
                            });
                    }
                });
        },
        setStatusForRoles(role) {
            this.activeRoles = role.filter((element) => element.estado_plaza_asignada === 1)
            this.inactiveRoles = role.filter((element) => element.estado_plaza_asignada === 0)
        }
    },
    watch: {
    },
    computed: {
        getEmployeeName: function () {
            if (this.employee.persona) {
                const persona = this.employee.persona;

                const pnombre = persona.pnombre_persona;
                const snombre = persona.snombre_persona;
                const tnombre = persona.tnombre_persona;
                const papellido = persona.papellido_persona;
                const sapellido = persona.sapellido_persona;
                const tapellido = persona.tapellido_persona;

                let employeeName = pnombre;

                if (snombre) employeeName += ' ' + snombre;
                if (tnombre) employeeName += ' ' + tnombre;
                if (papellido) employeeName += ' ' + papellido;
                if (sapellido) employeeName += ' ' + sapellido;
                if (tapellido) employeeName += ' ' + tapellido;

                return employeeName;
            } else {
                return '';
            }
        },
    },
};
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.5s;
}

.fade-enter,
.fade-leave-to {
    opacity: 0;
}
</style>