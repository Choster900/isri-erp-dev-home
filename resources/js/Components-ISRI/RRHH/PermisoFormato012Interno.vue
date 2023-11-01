<script setup>
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
import ProcessModal from '@/Components-ISRI/AllModal/ProcessModal.vue'
import moment from 'moment';

</script>
<template>
    <div class="m-1.5 p-10">
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
        <ProcessModal v-else maxWidth='5xl' :show="viewPermission012I" @close="$emit('cerrar-modal')">
            <div v-if="!showDenialOptions && validPermit" class="flex justify-center items-center h-full mt-1">
                <div v-if="showButtons" class="px-2">
                    <GeneralButton color="bg-green-600 hover:bg-green-700" text="Aprobar" icon="check"
                        @click="approvePermission()" />
                </div>
                <div v-if="showButtons" class="px-2">
                    <GeneralButton color="bg-orange-600 hover:bg-orange-700" text="Denegar" icon="pdf"
                        @click="showDenialOptions = true" />
                </div>
                <div class="px-2">
                    <GeneralButton color="bg-red-600 hover:bg-red-700" text="PDF" icon="pdf" @click="printPdf()" />
                </div>
            </div>
            <div v-else class="py-3"></div>

            <div class="flex">
                <div class="w-[32%] border-gray-600 border-r relative">
                    <h2 class="text-center underline text-[14px] mb-2 mx-4">APROBACIONES</h2>
                    <div v-if="stages.length > 0" class="max-h-[500px] overflow-y-auto">
                        <div v-for="(stage, index) in  stages " :key="index"
                            class="bg-gray-50 rounded-lg mx-4 max-w-full relative mb-3 mt-1">
                            <div class="absolute inset-0 bg-gray-600 rounded-lg blur opacity-50 z-0"></div>
                            <div class="relative z-10 px-4 py-2 bg-white ring-1 ring-gray-900/5 rounded-lg leading-none"
                                :class="{ 'px-4': showStage(index), 'py-2': !showStage(index), 'bg-green-100 hover:bg-green-200': setGreenBg(index), 'bg-red-100 hover:bg-red-200': !setGreenBg(index) }">
                                <!-- header -->
                                <div class="w-full">
                                    <div class="w-full flex justify-between cursor-pointer" @click="setTarget(index)">
                                        <p class="text-[13px] font-medium text-navy-700 dark:text-white mb-0.5">ETAPA
                                            {{ stage.persona_etapa.persona_etapa }}</p>
                                        <svg class="w-4 h-4 mr-1 transform origin-center transition-transform" fill="none"
                                            stroke="#000000" viewBox="0 0 24 24"
                                            :class="showStage(index) ? 'rotate-180' : ''">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7">
                                            </path>
                                        </svg>
                                    </div>
                                </div>
                                <!-- body -->
                                <div v-if="showStage(index)" class="w-full">
                                    <div class="flex-grow h-px bg-gray-400 mb-2 mt-2">
                                    </div>
                                    <div class="w-full mb-1">
                                        <p class="text-[13px] text-gray-600 mb-0.5">Empleado</p>
                                        <p class="text-[13px] font-medium text-navy-700 dark:text-white">
                                            {{ stage.empleado.persona.pnombre_persona }}
                                            {{ stage.empleado.persona.snombre_persona }}
                                            {{ stage.empleado.persona.tnombre_persona }}
                                            {{ stage.empleado.persona.papellido_persona }}
                                            {{ stage.empleado.persona.sapellido_persona }}
                                            {{ stage.empleado.persona.tapellido_persona }}
                                        </p>
                                    </div>
                                    <div class="w-full mb-1">
                                        <p class="text-[13px] text-gray-600 mb-0.5">Estado</p>
                                        <p class="text-[13px] font-medium text-navy-700 dark:text-white">
                                            {{ stage.estado_etapa_permiso.nombre_estado_etapa_permiso }}
                                        </p>
                                    </div>
                                    <div class="w-full mb-1">
                                        <p class="text-[13px] text-gray-600 mb-0.5">Fecha y hora</p>
                                        <p class="text-[13px] font-medium text-navy-700 dark:text-white">
                                            {{ moment(stage.fecha_reg_etapa_permiso).format('DD/MM/YYYY HH:mm:ss') }}
                                        </p>
                                    </div>
                                    <div v-if="stage.observacion_etapa_permiso" class="w-full mb-1">
                                        <p class="text-[13px] text-gray-600 mb-0.5">Observaciones</p>
                                        <p class="text-[13px] font-medium text-navy-700 dark:text-white">
                                            {{ stage.observacion_etapa_permiso }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-else-if="permissionToPrint.id_estado_permiso === 1" class="mx-4">
                        <p class="text-[13px] font-medium text-red-500">DEBES ENVIAR EL PERMISO PARA INICIAR EL FLUJO DE
                            APROBACIONES.</p>
                    </div>
                    <div v-else-if="permissionToPrint.id_estado_permiso === 4" class="mx-4">
                        <p class="text-[13px] font-medium text-red-500">PERMISO DENEGADO.</p>
                    </div>
                    <div v-else-if="permissionToPrint.id_estado_permiso === 5" class="mx-4">
                        <p class="text-[13px] font-medium text-red-500">PERMISO ELIMINADO.</p>
                    </div>
                </div>
                <div class="w-[68%] max-h-[550px] overflow-y-auto">
                    <div v-if="showDenialOptions" class="mb-4 md:mr-4 md:mb-0 basis-full mx-4"
                        style="border: none; background-color: transparent;">
                        <label class="block mb-2 text-xs text-black" for="descripcion">
                            Comentario sobre denegacion de permiso<span class="text-red-600 font-extrabold">*</span>
                        </label>
                        <textarea v-model="denialComment" id="description" name="description"
                            class="resize-none w-full h-12 overflow-y-auto peer text-xs font-semibold rounded-md border border-slate-400 px-2 text-slate-900 transition-colors duration-300 focus:border-[#001b47] focus:outline-none">
                        </textarea>
                        <InputError class="mt-1" :message="messageError" />
                    </div>
                    <div v-if="showDenialOptions" class="flex justify-center mt-2">
                        <GeneralButton class="mr-1" text="Cancelar" icon="delete" @click="showDenialOptions = false" />
                        <GeneralButton color="bg-red-700 hover:bg-red-800" text="Denegar" icon="delete" class=""
                            @click="rejectPermission()" />
                    </div>
                    <div class="flex flex-col justify-start h-full mx-2 ">
                        <div class="flex flex-col text-center mt-4 relative " id="encabezado">
                            <!-- <span class="font-[Bembo] font-bold text-[15px] absolute top-[5px] right-[-150px]">F026</span> -->
                            <img src="../../../img/isri-gob.png" alt="Logo del instituto" class="w-[200px] mx-auto">
                            <h2 class="font-[Bembo] font-bold text-[13px]">DEPARTAMENTO DE RECURSOS HUMANOS</h2>
                            <h2 class="font-[Bembo] font-bold text-[13px]">SOLICITUD DE PERMISOS</h2>
                            <h2 class="font-[Bembo] font-bold text-[13px]">(Control Interno)</h2>
                        </div>
                        <!-- Second row -->
                        <div class="flex w-full justify-between items-center mt-3 mb-2">
                            <div class="flex w-full text-left">
                                <div class="relative flex flex-row w-full">
                                    <div class="flex justify-start w-[51%]">
                                        <label for="" class="font-[MuseoSans] text-[13px]">
                                            CENTRO DE ATENCIÓN, DEPARTAMENTO O SECCIÓN:
                                        </label>
                                    </div>
                                    <div class="text-left w-[49%] text-[13px] font-bold border-b border-gray-700"
                                        :class="centro1 ? '' : 'py-2'">
                                        <p class="font-[MuseoSans] ml-3">{{ centro1 }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Third row -->
                        <div class="flex w-full justify-between items-center mb-2">
                            <div class="flex w-full text-left">
                                <div class="relative flex flex-row w-full">
                                    <div class="text-left w-full text-[13px] font-bold border-b border-gray-700"
                                        :class="centro2 ? '' : 'py-2'">
                                        <p class="font-[MuseoSans] ml-3">{{ centro2 }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Fourth row -->
                        <div class="flex w-full justify-between items-center mb-2">
                            <div class="flex w-full text-left">
                                <div class="relative flex flex-row w-full">
                                    <div class="flex justify-start w-[49%]">
                                        <label for="" class="font-[MuseoSans] text-[13px]">
                                            DENOMINACION PLAZA (Ley de salarios o contrato):
                                        </label>
                                    </div>
                                    <div class="text-left w-[51%] text-[13px] font-bold border-b border-gray-700"
                                        :class="role1 ? '' : 'py-2'">
                                        <p class="font-[MuseoSans] ml-3">{{ role1 }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Fifth row -->
                        <div class="flex w-full justify-between items-center mb-2">
                            <div class="flex w-full text-left">
                                <div class="relative flex flex-row w-full">
                                    <div class="text-left w-full text-[13px] font-bold border-b border-gray-700"
                                        :class="role2 ? '' : 'py-2'">
                                        <p class="font-[MuseoSans] ">{{ role2 }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Sixth row -->
                        <div class="flex w-full justify-between items-center mb-2">
                            <div class="flex w-full text-left">
                                <div class="relative flex flex-row w-[21%]">
                                    <div class="flex justify-start w-[50%]">
                                        <label for="" class="font-[MuseoSans] text-[13px] mr-2">
                                            CÓDIGO:
                                        </label>
                                    </div>
                                    <div class="text-center w-[50%] text-[13px] font-bold border-b border-gray-700 mr-1">
                                        <p class="font-[MuseoSans]">{{
                                            permissionToPrint.empleado ? permissionToPrint.empleado.codigo_empleado : '' }}
                                        </p>
                                    </div>
                                </div>
                                <div class="relative flex flex-row w-[21%]">
                                    <div class="flex justify-start w-[50%]">
                                        <label for="" class="font-[MuseoSans] text-[13px] ml-1">
                                            PARTIDA:
                                        </label>
                                    </div>
                                    <div class="text-center w-[50%] text-[13px] font-bold border-b border-gray-700">
                                        <p class="font-[MuseoSans]">{{
                                            permissionToPrint.plaza_asignada ?
                                            permissionToPrint.plaza_asignada.partida_plaza_asignada : '' }}
                                        </p>
                                    </div>
                                </div>
                                <div class="relative flex flex-row w-[29%]">
                                    <div class="flex justify-start w-[55%]">
                                        <label for="" class="font-[MuseoSans] text-[13px] ml-2">
                                            SUB PARTIDA:
                                        </label>
                                    </div>
                                    <div class="mr-1 text-center w-[45%] text-[13px] font-bold border-b border-gray-700">
                                        <p class="font-[MuseoSans]">{{
                                            permissionToPrint.plaza_asignada ?
                                            permissionToPrint.plaza_asignada.subpartida_plaza_asignada : ''
                                        }}</p>
                                    </div>
                                </div>
                                <div class="relative flex flex-row w-[29%]">
                                    <div class="flex justify-start w-[50%]">
                                        <label for="" class="font-[MuseoSans] text-[13px] ml-3">
                                            CONTRATO:
                                        </label>
                                    </div>
                                    <div class="ml-1 text-center w-[50%] text-[13px] font-bold border-b border-gray-700">
                                        <p class="font-[MuseoSans]">{{ contract }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Seventh row -->
                        <div class="flex w-full justify-between items-center mb-2">
                            <div class="flex w-full text-left">
                                <div class="relative flex flex-row w-full">
                                    <div class="flex justify-start w-full">
                                        <label for="" class="font-[MuseoSans] text-[13px]">
                                            MOTIVO:
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Eighth row -->
                        <div class="flex w-full justify-between items-center mb-2">
                            <div class="flex w-full text-left">
                                <div class="flex justify-start w-[10%]"></div>
                                <div class="relative flex flex-row w-[30%]">
                                    <div class="flex justify-start w-full"
                                        :class="permissionToPrint.id_tipo_permiso === 3 || permissionToPrint.id_tipo_permiso === 3 ? 'font-bold' : ''">
                                        <label for="" class="font-[MuseoSans] text-[12px]">
                                            ENFERMEDAD
                                        </label>
                                    </div>
                                </div>
                                <div class="relative flex flex-row w-[30%]">
                                    <div class="flex justify-start w-full"
                                        :class="permissionToPrint.id_tipo_permiso === 1 || permissionToPrint.id_tipo_permiso === 2 ? 'font-bold' : ''">
                                        <label for="" class="font-[MuseoSans] text-[12px]">
                                            PERSONAL
                                        </label>
                                    </div>
                                </div>
                                <div class="relative flex flex-row w-[30%]"
                                    :class="permissionToPrint.id_tipo_permiso === 5 ? 'font-bold' : ''">
                                    <div class="flex justify-start w-full">
                                        <label for="" class="font-[MuseoSans] text-[12px]">
                                            OTROS
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Ninth row -->
                        <div class="flex w-full justify-between items-center mb-2">
                            <div class="flex w-full text-left">
                                <div class="relative flex flex-row w-full">
                                    <div class="flex justify-start w-[10%]"></div>
                                    <div class="flex justify-start w-[30%]">
                                        <div class="flex justify-start w-[70%]"
                                            :class="permissionToPrint.id_tipo_permiso === 3 ? 'font-bold' : ''">
                                            <label class="font-[MuseoSans] text-[12px]">
                                                CON GOCE DE SUELDO
                                            </label>
                                        </div>
                                        <div class="text-left w-[30%] text-[12px]">
                                            <div class="w-6 h-4 border border-black mr-2">
                                                <div v-if="permissionToPrint.id_tipo_permiso === 3"
                                                    class="w-full h-full bg-black">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex justify-start w-[30%]">
                                        <div class="flex justify-start w-[70%]"
                                            :class="permissionToPrint.id_tipo_permiso === 1 ? 'font-bold' : ''">
                                            <label class="font-[MuseoSans] text-[12px]">
                                                CON GOCE DE SUELDO
                                            </label>
                                        </div>
                                        <div class="text-left w-[30%] text-[12px]">
                                            <div class="w-6 h-4 border border-black mr-2">
                                                <div v-if="permissionToPrint.id_tipo_permiso === 1"
                                                    class="w-full h-full bg-black">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex justify-start w-[30%]">
                                        <div class="flex justify-start w-[70%]"
                                            :class="permissionToPrint.id_tipo_permiso === 5 ? 'font-bold' : ''">
                                            <label class="font-[MuseoSans] text-[12px]">
                                                MISION OFICIAL
                                            </label>
                                        </div>
                                        <div class="text-left w-[30%] text-[12px]">
                                            <div class="w-6 h-4 border border-black mr-2">
                                                <div v-if="permissionToPrint.id_tipo_permiso === 5"
                                                    class="w-full h-full bg-black">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Tenth row -->
                        <div class="flex w-full justify-between items-center mb-3">
                            <div class="flex w-full text-left">
                                <div class="relative flex flex-row w-full">
                                    <div class="flex justify-start w-[10%]"></div>
                                    <div class="flex justify-start w-[30%]">
                                        <div class="flex justify-start w-[70%]"
                                            :class="permissionToPrint.id_tipo_permiso === 4 ? 'font-bold' : ''">
                                            <label class="font-[MuseoSans] text-[12px]">
                                                SIN GOCE DE SUELDO
                                            </label>
                                        </div>
                                        <div class="text-left w-[30%] text-[12px]">
                                            <div class="w-6 h-4 border border-black mr-2">
                                                <div v-if="permissionToPrint.id_tipo_permiso === 4"
                                                    class="w-full h-full bg-black">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex justify-start w-[30%]">
                                        <div class="flex justify-start w-[70%]"
                                            :class="permissionToPrint.id_tipo_permiso === 2 ? 'font-bold' : ''">
                                            <label class="font-[MuseoSans] text-[12px]">
                                                SIN GOCE DE SUELDO
                                            </label>
                                        </div>
                                        <div class="text-left w-[30%] text-[12px]">
                                            <div class="w-6 h-4 border border-black mr-2">
                                                <div v-if="permissionToPrint.id_tipo_permiso === 2"
                                                    class="w-full h-full bg-black">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex justify-start w-[30%]"></div>
                                </div>
                            </div>
                        </div>
                        <!-- Eleventh -->
                        <div class="flex w-full justify-between items-center mb-3">
                            <div class="flex w-full text-left">
                                <div class="relative flex flex-row w-full">
                                    <div class="flex justify-start w-[12%]">
                                        <label for="" class="font-[MuseoSans] text-[13px]">
                                            Observación:
                                        </label>
                                    </div>
                                    <div class="text-left w-[88%] font-bold text-[13px] border-b border-gray-700">
                                        <p class="font-[MuseoSans] ml-3">
                                            {{
                                                permissionToPrint.id_tipo_permiso != 6 && permissionToPrint.id_tipo_permiso != 5
                                                ?
                                                observation1 : ''
                                            }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Twelfth row -->
                        <div class="flex w-full justify-between items-center mb-2">
                            <div class="flex w-full text-left">
                                <div class="relative justify-center flex flex-row w-[38%] mx-auto">
                                    <div class="flex w-full">
                                        <label for=""
                                            class="font-[MuseoSans] font-bold text-[12px] mx-auto underline underline-offset-2">
                                            MISION OFICIAL
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Thirteenth row -->
                        <div class="flex w-full justify-start items-center mb-2">
                            <div class="flex w-full text-left">
                                <div class="relative flex flex-row w-[75%]">
                                    <div class="flex justify-start w-[10%]">
                                        <label for="" class="font-[MuseoSans] text-[12px]">
                                            Destino:
                                        </label>
                                    </div>
                                    <div class="text-left w-[90%] text-[12px] font-bold border-b border-gray-700">
                                        <p class="font-[MuseoSans] ml-1">
                                            {{ permissionToPrint.id_tipo_permiso === 5 ? destination1 : '' }}
                                        </p>
                                    </div>
                                </div>
                                <div class="relative flex flex-row w-[25%]">
                                    <div class="flex justify-start w-[40%]">
                                        <label for="" class="font-[MuseoSans] text-[12px] ml-1">
                                            Regresará:
                                        </label>
                                    </div>
                                    <div class="flex flex-row text-left w-[60%] text-[13px] ">
                                        <div class="flex justify-start w-[50%]">
                                            <div class="flex justify-start w-[50%]">
                                                <label class="font-[MuseoSans] text-[11px] ml-2">
                                                    SI
                                                </label>
                                            </div>
                                            <div class="text-left w-[50%] text-[11px]">
                                                <div class="w-3 h-3 border border-black">
                                                    <div v-if="permissionToPrint.retornar_empleado_permiso === 1"
                                                        class="w-full h-full bg-black">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex justify-start w-[50%]">
                                            <div class="flex justify-start w-[50%]">
                                                <label class="font-[MuseoSans] text-[11px]">
                                                    NO
                                                </label>
                                            </div>
                                            <div class="text-left w-[50%] text-[11px]">
                                                <div class="w-3 h-3 border border-black">
                                                    <div v-if="permissionToPrint.retornar_empleado_permiso === 0"
                                                        class="w-full h-full bg-black">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Fourteenth row -->
                        <div class="flex w-full justify-start items-center mb-2">
                            <div class="flex w-full text-left">
                                <div class="relative flex flex-row w-full">
                                    <div class="flex justify-start w-[11%]">
                                        <label for="" class="font-[MuseoSans] text-[12px]">
                                            Descripción:
                                        </label>
                                    </div>
                                    <div class="text-left w-[89%] text-[12px] font-bold border-b border-gray-700">
                                        <p class="font-[MuseoSans] ml-1">
                                            {{
                                                permissionToPrint.id_tipo_permiso == 5 ? observation1 : ''
                                            }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Fifteenth row -->
                        <div class="flex w-full justify-between items-center mt-4 mb-2">
                            <div class="flex w-full text-left">
                                <div class="relative flex flex-row w-[5%]">
                                </div>
                                <div class="relative flex flex-row w-[14%]">
                                    <div class="flex justify-start w-full">
                                        <label for="" class="font-[MuseoSans] text-[12px]">
                                            FECHA:
                                        </label>
                                    </div>
                                </div>
                                <div class="relative flex flex-row w-[32%]">
                                    <div class="flex justify-start w-[40%]">
                                        <label for="" class="font-[MuseoSans] text-[11px]">
                                            DESDE
                                        </label>
                                    </div>
                                    <div
                                        class="text-center w-[60%] text-[11px] font-bold border-b border-gray-700 mr-[40px]">
                                        <p class="font-[MuseoSans]">
                                            {{ permissionToPrint.fecha_inicio_permiso ?
                                                formatDate(permissionToPrint.fecha_inicio_permiso)
                                                :
                                                '-------------' }}
                                        </p>
                                    </div>
                                </div>
                                <div class="relative flex flex-row w-[14%]">
                                    <div class="flex justify-start w-full">
                                        <label for="" class="font-[MuseoSans] text-[12px]">
                                            HORA:
                                        </label>
                                    </div>
                                </div>
                                <div class="relative flex flex-row w-[32%]">
                                    <div class="flex justify-start w-[50%]">
                                        <label for="" class="font-[MuseoSans] text-[11px]">
                                            INICIO / SALIDA
                                        </label>
                                    </div>
                                    <div class="text-center w-[50%] text-[11px] font-bold border-b border-gray-700">
                                        <p class="font-[MuseoSans]">
                                            {{ permissionToPrint.hora_entrada_permiso ?
                                                formatHour(permissionToPrint.hora_entrada_permiso)
                                                :
                                                '-------------' }}
                                        </p>
                                    </div>
                                </div>
                                <div class="relative flex flex-row w-[5%]">
                                </div>
                            </div>
                        </div>
                        <!-- Fifteenth row -->
                        <div class="flex w-full justify-between items-center mb-3">
                            <div class="flex w-full text-left">
                                <div class="relative flex flex-row w-[19%]">
                                </div>
                                <div class="relative flex flex-row w-[32%]">
                                    <div class="flex justify-start w-[40%]">
                                        <label for="" class="font-[MuseoSans] text-[11px]">
                                            HASTA
                                        </label>
                                    </div>
                                    <div
                                        class="text-center w-[60%] text-[11px] font-bold border-b border-gray-700 mr-[40px]">
                                        <p class="font-[MuseoSans]">
                                            {{ permissionToPrint.fecha_fin_permiso ?
                                                formatDate(permissionToPrint.fecha_fin_permiso) :
                                                '-------------' }}
                                        </p>
                                    </div>
                                </div>
                                <div class="relative flex flex-row w-[14%]"></div>
                                <div class="relative flex flex-row w-[32%]">
                                    <div class="flex justify-start w-[50%]">
                                        <label for="" class="font-[MuseoSans] text-[11px]">
                                            FIN / REGRESO
                                        </label>
                                    </div>
                                    <div class="text-center w-[50%] text-[11px] font-bold border-b border-gray-700">
                                        <p class="font-[MuseoSans]">
                                            {{ permissionToPrint.hora_salida_permiso ?
                                                formatHour(permissionToPrint.hora_salida_permiso) :
                                                '-------------' }}
                                        </p>
                                    </div>
                                </div>
                                <div class="relative flex flex-row w-[5%]"></div>
                            </div>
                        </div>
                        <!-- Sixteenth row -->
                        <div class="flex w-full justify-between items-center pb-4">
                            <div class="flex w-full text-left">
                                <div class="relative justify-center flex flex-row w-[38%] mx-auto" id="container">
                                    <div class="flex w-[60%]">
                                        <label for="" class="font-[MuseoSans] text-[13px]">
                                            TIEMPO UTILIZADO:
                                        </label>
                                    </div>
                                    <div
                                        class="text-center w-[40%] text-[13px] font-bold border-b border-gray-700 mr-[40px]">
                                        <p class="font-[MuseoSans]">
                                            {{ timeUsed }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </ProcessModal>
    </div>
</template>

<script>
import PermisoF012ControlInternoPDFVue from '@/pdf/RRHH/PermisoF012ControlInternoPDF.vue';
import { createApp, h } from 'vue'
import html2pdf from 'html2pdf.js'
import { jsPDF } from "jspdf";
export default {
    props: {
        viewPermission012I: {
            type: Boolean,
            default: false,
        },
        permissionToPrint: {
            type: Array,
            default: [],
        },
        stages: {
            type: Array,
            default: []
        }
    },
    data: function () {
        return {
            contract: '49/2021',
            denialComment: '',
            messageError: '',
            showDenialOptions: false,
            showButtons: true,
            isLoading: false,
            centro1: '',
            centro2: '',
            observation1: '',
            observation2: '',
            role1: '',
            role2: '',
            destination1: '',
            destination2: ''
        }
    },
    methods: {
        setApprobalRejectButtons(permission) {
            if (permission) {
                const rolId = this.$page.props.menu.id_rol
                const range = [15, 16, 17, 18]
                if (range.includes(rolId)) {
                    if (rolId === 15) {
                        let stage = permission.etapa_permiso.find((element) => element.id_estado_etapa_permiso === 2 || element.id_estado_etapa_permiso === 3)
                        if (stage) {
                            this.showButtons = false
                        }
                    }
                    if (rolId === 16) {
                        let stage = permission.etapa_permiso.find((element) => element.id_estado_etapa_permiso === 4 || element.id_estado_etapa_permiso === 5)
                        if (stage) {
                            this.showButtons = false
                        }
                    }
                    if (rolId === 17) {
                        let stage = permission.etapa_permiso.find((element) => element.id_estado_etapa_permiso === 6 || element.id_estado_etapa_permiso === 7)
                        if (stage) {
                            this.showButtons = false
                        }
                    }
                    if (rolId === 18) {
                        let stage = permission.etapa_permiso.find((element) => element.id_estado_etapa_permiso === 8 || element.id_estado_etapa_permiso === 9)
                        if (stage) {
                            this.showButtons = false
                        }
                    }
                } else {
                    this.showButtons = false
                }
            } else {
                this.showButtons = true
            }
        },
        async rejectPermission() {
            if (this.denialComment === '') {
                this.messageError = 'Debes agregar un comentario.'
                this.showToast(toast.warning, "Tienes algunos errores por favor completa la información requerida.");
            } else {
                this.messageError = ''

                const idRol = this.$page.props.menu.id_rol;

                const urlMap = {
                    15: '/supervisor-denial',
                    16: '/director-denial',
                    17: '/medical-management-denial',
                    18: '/general-management-denial'
                };

                const url = urlMap[idRol] || '';

                this.$swal.fire({
                    title: "Denegar permiso.",
                    text: "¿Estas seguro?",
                    icon: "question",
                    iconHtml: "❓",
                    confirmButtonText: 'Si, denegar.',
                    confirmButtonColor: "#001b47",
                    cancelButtonText: "Cancelar",
                    showCancelButton: true,
                    showCloseButton: true
                }).then(async (result) => {
                    if (result.isConfirmed) {
                        try {
                            this.isLoading = true;  // Activar el estado de carga
                            const response = await axios.post(url, {
                                id: this.permissionToPrint.id_permiso,
                                id_rol: idRol,
                                comment: this.denialComment
                            });
                            this.$swal.fire({
                                text: response.data.mensaje,
                                icon: 'success',
                                timer: 5000
                            })
                            this.$emit('get-table')
                            this.$emit('cerrar-modal')
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
            }
        },
        //Falta hacer la aprobacion desde jefe inmediato
        async approvePermission() {
            const idRol = this.$page.props.menu.id_rol;

            const urlMap = {
                15: '/supervisor-approval',
                16: '/director-approval',
                17: '/medical-management-approval',
                18: '/general-management-approval'
            };

            const url = urlMap[idRol] || '';

            this.$swal.fire({
                title: "Aprobar permiso.",
                text: "¿Estas seguro?",
                icon: "question",
                iconHtml: "❓",
                confirmButtonText: 'Si, aprobar.',
                confirmButtonColor: "#001b47",
                cancelButtonText: "Cancelar",
                showCancelButton: true,
                showCloseButton: true
            }).then(async (result) => {
                if (result.isConfirmed) {
                    try {
                        this.isLoading = true;  // Activar el estado de carga
                        const response = await axios.post(url, {
                            id: this.permissionToPrint.id_permiso,
                            id_rol: idRol
                        });
                        this.$swal.fire({
                            text: response.data.mensaje,
                            icon: 'success',
                            timer: 5000
                        })
                        this.$emit('get-table')
                        this.$emit('cerrar-modal')
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
        showStage(index) {
            if (!this.stages[index].visible) {
                this.stages[index].visible = false
            }
            return this.stages[index].visible
        },
        formatTime(time) {
            if (!time) {
                return '0'
            } else {
                const [hours, minutes] = time.split(':').map(Number);
                return `${hours > 0 ? hours + ' H. ' : ''} ${minutes > 0 ? minutes + ' min.' : ''}`
            }
        },
        setGreenBg(index) {
            const green = [1, 2, 4, 6, 8];
            if (green.includes(this.stages[index].id_estado_etapa_permiso)) {
                return true
            } else {
                return false
            }
        },
        setTarget(index) {
            if (!this.stages[index].visible) {
                this.stages[index].visible = true
            } else {
                this.stages[index].visible = false
            }
        },
        formatDate(date) {
            // Parsea la fecha desde el backend
            const fechaParseada = moment(date, 'YYYY-MM-DD');

            // Formatea la fecha en el nuevo formato 'DD/MM/YYYY'
            return fechaParseada.format('DD/MM/YYYY') ?? '-------------';
        },

        printPdf() {
            let fecha = moment().format('DD-MM-YYYY');
            let name = 'RECIBO ' + this.receipt_to_print.numero_recibo_ingreso + ' - ' + fecha;
            const opt = {
                margin: 0,
                filename: name,
                //pagebreak: {mode:'css',before:'#pagebreak'},
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 3, useCORS: true },
                //jsPDF: { unit: 'cm', format: [13.95,21.5], orientation: 'landscape' }
                jsPDF: { unit: 'cm', format: 'letter', orientation: 'portrait' },
            };

            const limiteCaracteres = 70;
            if (this.receipt_to_print.monto_letras.length <= limiteCaracteres) {
                this.letras1 = this.receipt_to_print.monto_letras;
                this.letras2 = ''
            } else {
                let textoTruncado = this.receipt_to_print.monto_letras.slice(0, limiteCaracteres);
                let ultimoEspacio = textoTruncado.lastIndexOf(' ');
                this.letras1 = textoTruncado.slice(0, ultimoEspacio);
                this.letras2 = this.receipt_to_print.monto_letras.slice(ultimoEspacio + 1);
            }

            const app = createApp(ReciboIngresoMatricialVue, {
                receipt_to_print: this.receipt_to_print,
                formatedAmount: this.receipt_to_print.monto_recibo_ingreso,
                empleado: this.empleado,
                nombre_cuenta: this.nombre_cuenta,
                fecha_recibo: this.fecha_recibo,
                letras1: this.letras1,
                letras2: this.letras2
            });
            const div = document.createElement('div');
            const pdfPrint = app.mount(div);
            const html = div.outerHTML;

            html2pdf().set(opt).from(html).save();
            //html2pdf().set(opt).from(html).output('dataurlnewwindow');
        },
        getCentro() {
            let limiteCaracteres = 42;
            let string = this.permissionToPrint.plaza_asignada.dependencia.nombre_dependencia;
            if (string) {
                if (string.length <= limiteCaracteres) {
                    this.centro1 = string;
                } else {
                    let textoTruncado = string.slice(0, limiteCaracteres);
                    let ultimoEspacio = textoTruncado.lastIndexOf(' ');
                    this.centro1 = textoTruncado.slice(0, ultimoEspacio);
                    this.centro2 = string.slice(ultimoEspacio + 1);
                }
            }
        },
        getObservation() {
            let limiteCaracteres = 96;
            let string = this.permissionToPrint.comentarios_permiso;
            if (string) {
                if (string.length <= limiteCaracteres) {
                    this.observation1 = string;
                } else {
                    let textoTruncado = string.slice(0, limiteCaracteres);
                    let ultimoEspacio = textoTruncado.lastIndexOf(' ');
                    this.observation1 = textoTruncado.slice(0, ultimoEspacio);
                    this.observation2 = string.slice(ultimoEspacio + 1);
                }
            }
        },
        getRole() {
            const role = this.permissionToPrint.plaza_asignada.detalle_plaza.plaza.nombre_plaza;
            this.role1 = '';
            this.role2 = '';
            if (role) {
                const limiteCaracteres3 = 42;
                if (role.length <= limiteCaracteres3) {
                    this.role1 = role;
                } else {
                    const textoTruncado3 = role.slice(0, limiteCaracteres3);
                    const ultimoEspacio3 = textoTruncado3.lastIndexOf(' ');
                    this.role1 = textoTruncado3.slice(0, ultimoEspacio3);
                    this.role2 = role.slice(ultimoEspacio3 + 1);
                }
            }
        },
        getDestination() {
            let limiteCaracteres = 79;
            let string = this.permissionToPrint.destino_permiso;
            if (string) {
                if (string.length <= limiteCaracteres) {
                    this.destination1 = string;
                } else {
                    let textoTruncado = string.slice(0, limiteCaracteres);
                    let ultimoEspacio = textoTruncado.lastIndexOf(' ');
                    this.destination1 = textoTruncado.slice(0, ultimoEspacio);
                    this.destination2 = string.slice(ultimoEspacio + 1);
                }
            }
        },
        formatHour(time) {
            const [hora, minutos] = time.split(':');
            const hora12 = (parseInt(hora) % 12).toString();
            const amPm = parseInt(hora) < 12 ? 'AM' : 'PM';

            // Añade un 0 delante si la hora tiene un solo dígito
            const horaFormateada = hora12.padStart(2, '0');
            // Añade un 0 delante si los minutos tienen un solo dígito
            const minutosFormateados = minutos.padStart(2, '0');

            return `${horaFormateada}:${minutosFormateados} ${amPm}`;
        },
        printPermission() {
            const s1Name = this.getName(this.stages[0])
            const s2Name = this.getName(this.stages[1])
            const s3Name = this.getName(this.stages[2])
            const permission = this.permissionToPrint
            const currentDateTime = moment().format('DD/MM/YYYY, HH:mm:ss');
            const name = 'PERMISO ' + permission.tipo_permiso.codigo_tipo_permiso + ' - ' + permission.empleado.codigo_empleado;
            const opt = {
                margin: 0.2,
                filename: name,
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 3, useCORS: true },
                jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' },
            };

            const fechaParseada = moment(permission.fecha_inicio_permiso);

            const app = createApp(PermisoF012ControlInternoPDFVue, {
                permission,
                centro1: this.centro1,
                centro2: this.centro2,
                observation1: this.observation1,
                observation2: this.observation2,
                dia: fechaParseada.format('DD'),
                mes: fechaParseada.format('MMMM').toUpperCase(),
                anio: fechaParseada.format('YYYY'),
                limite: this.limite,
                s1Name,
                s2Name,
                s3Name
            });
            const div = document.createElement('div');
            const pdfPrint = app.mount(div);
            const html = div.outerHTML;

            html2pdf().set(opt).from(html)
                .toPdf().get('pdf').then(function (pdf) {
                    pdf.setFontSize(10);
                    const date_text = 'SIGI - Generado: ' + currentDateTime;
                    const textWidth = pdf.getStringUnitWidth(date_text) * pdf.internal.getFontSize() / pdf.internal.scaleFactor;
                    pdf.text(pdf.internal.pageSize.getWidth() - textWidth - 0.2, pdf.internal.pageSize.getHeight() - 0.4, date_text);
                })
                .save()
                .catch(err => console.log(err));
        },
        getName(stage) {
            const persona = stage.empleado.persona;
            const nombres = [persona.pnombre_persona, persona.snombre_persona, persona.tnombre_persona].filter(Boolean).join(' ');
            const apellidos = [persona.papellido_persona, persona.sapellido_persona, persona.tapellido_persona].filter(Boolean).join(' ');

            return stage.empleado.titulo_profesional.codigo_titulo_profesional + ' ' + nombres + ' ' + apellidos;
        }
    },
    watch: {
        viewPermission012I: function (value, oldValue) {
            if (value) {
                this.showButtons = true
                this.setApprobalRejectButtons(this.permissionToPrint)
                this.showDenialOptions = false
                this.messageError = ''

                this.centro1 = ''
                this.centro2 = ''
                this.observation1 = ''
                this.observation2 = ''
                this.getCentro()
                this.getObservation()
                this.getRole()
                this.getDestination()
            }
        },
    },
    computed: {
        validPermit() {
            if (this.permissionToPrint.id_estado_permiso === 2) {
                return true
            } else {
                return false
            }
        },
        timeUsed: function () {
            if (this.permissionToPrint.fecha_fin_permiso) {
                const startDateFormated = moment(this.permissionToPrint.fecha_inicio_permiso, 'YYYY/MM/DD').toDate()
                const endDateFormated = moment(this.permissionToPrint.fecha_fin_permiso, 'YYYY/MM/DD').toDate()

                let currentDate = new Date(startDateFormated);
                let daysDifference = 0;

                while (currentDate <= endDateFormated) {
                    const dayOfWeek = currentDate.getDay(); // 0 (domingo) a 6 (sábado)
                    if (dayOfWeek !== 0 && dayOfWeek !== 6) {
                        daysDifference++;
                    }
                    currentDate.setDate(currentDate.getDate() + 1);
                }
                const resultInMinutes = daysDifference * 8 * 60

                const hours = Math.floor(resultInMinutes / 60);
                const minutes = resultInMinutes % 60;
                const formatResult = `${hours}:${minutes}`
                return this.formatTime(formatResult)

            } else {
                if (this.permissionToPrint.hora_salida_permiso) {
                    const [endHours, endMinutes] = this.permissionToPrint.hora_salida_permiso.split(':').map(Number);
                    const [startHours, startMinutes] = this.permissionToPrint.hora_entrada_permiso.split(':').map(Number);
                    const result = ((endHours * 60) + endMinutes) - ((startHours * 60) + startMinutes)

                    const hours = Math.floor(result / 60);
                    const minutes = result % 60;
                    const formatResult = `${hours}:${minutes}`
                    return this.formatTime(formatResult)
                } else {
                    return '0'
                }
            }
        },
    },
}
</script>

<style scoped>
.flex-container {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100%;
}

img {
    max-height: 100%;
    max-width: 100%;
}
</style>