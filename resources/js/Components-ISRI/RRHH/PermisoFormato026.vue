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
        <ProcessModal v-else maxWidth='5xl' :show="viewPermission026" @close="$emit('cerrar-modal')">
            <div v-if="!showDenialOptions" class="flex justify-center items-center h-full mt-1">
                <div v-if="showButtons" class="px-2">
                    <GeneralButton color="bg-green-600 hover:bg-green-700" text="Aprobar" icon="check"
                        @click="approvePermission()" />
                </div>
                <div v-if="showButtons" class="px-2">
                    <GeneralButton color="bg-orange-600 hover:bg-orange-700" text="Denegar" icon="pdf"
                        @click="showDenialOptions = true" />
                </div>
                <div v-else-if="permissionToPrint.id_estado_permiso === 3" class="px-2">
                    <GeneralButton color="bg-red-600 hover:bg-red-700" text="PDF" icon="pdf" @click="printPermission()" />
                </div>
                <div v-else class="py-3"></div>
            </div>
            <div v-else class="py-3"></div>

            <div class="flex">
                <div class="w-[32%] border-gray-600 border-r relative">
                    <h2 class="text-center text-[14px] mb-2 mx-4 underline">APROBACIONES</h2>
                    <div v-if="stages.length > 0" class="max-h-[500px] overflow-y-auto">
                        <div v-for="(stage, index) in  stages " :key="index"
                            class="bg-gray-50 rounded-lg mx-4 max-w-full relative mb-3 mt-1">
                            <div class="absolute inset-0 bg-gray-600 rounded-lg blur opacity-50 z-0"></div>
                            <div class="relative z-10 px-4 py-2 bg-white ring-1 ring-gray-900/5 rounded-lg leading-none"
                                :class="{ 'px-4': showStage(index), 'py-2': !showStage(index), 'bg-green-100 hover:bg-green-200': setGreenBg(index), 'bg-red-100 hover:bg-red-200': !setGreenBg(index) }">
                                <!-- header -->
                                <div class="w-full">
                                    <div class="w-full flex justify-between cursor-pointer" @click="setTarget(index)">
                                        <p class="text-[13px] font-medium text-navy-700  mb-0.5">ETAPA
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
                                        <p class="text-[13px] font-medium text-navy-700 ">
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
                                        <p class="text-[13px] font-medium text-navy-700 ">
                                            {{ stage.estado_etapa_permiso_rel.nombre_estado_etapa_permiso }}
                                        </p>
                                    </div>
                                    <div class="w-full mb-1">
                                        <p class="text-[13px] text-gray-600 mb-0.5">Fecha y hora</p>
                                        <p class="text-[13px] font-medium text-navy-700 ">
                                            {{ moment(stage.fecha_reg_etapa_permiso).format('DD/MM/YYYY HH:mm:ss') }}
                                        </p>
                                    </div>
                                    <div v-if="stage.observacion_etapa_permiso" class="w-full mb-1">
                                        <p class="text-[13px] text-gray-600 mb-0.5">Observaciones</p>
                                        <p class="text-[13px] font-medium text-navy-700 ">
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
                <div class="w-[68%]" :class="showDenialOptions ? 'max-h-[550px] overflow-y-auto' : ''">
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
                    <div class="flex flex-col justify-start h-full mx-4">
                        <div class="flex flex-col text-center relative mt-4" id="encabezado">
                            <!-- <span class="font-[Bembo] font-bold text-[15px] absolute top-[5px] right-[-150px]">F026</span> -->
                            <img src="../../../img/isri-gob.png" alt="Logo del instituto" class="w-[200px] mx-auto">
                            <h2 class="font-[Bembo] font-bold text-[13px]">DEPARTAMENTO DE RECURSOS HUMANOS</h2>
                            <h2 class="font-[Bembo] font-bold text-[13px]">REGISTRO DE NO MARCACIÓN DE TARJETA DE ASISTENCIA
                            </h2>
                        </div>

                        <div class="flex flex-col w-full justify-between items-center mt-6 mb-2" id="fecha">
                            <div class="flex w-full text-left">
                                <div class="relative flex flex-row w-[35%]">
                                    <div class="flex justify-start w-[55%]">
                                        <label for="" class="font-[MuseoSans] text-[13px] mr-2">
                                            SAN SALVADOR
                                        </label>
                                    </div>
                                    <div class="mr-5 text-center w-[45%] text-[13px] font-bold border-b border-gray-700">
                                        <p class="font-[MuseoSans]">
                                            {{ moment(permissionToPrint.fecha_inicio_permiso).format('DD') }}
                                        </p>
                                    </div>
                                </div>
                                <div class="relative flex flex-row w-[40%]">
                                    <div class="flex justify-start w-[20%]">
                                        <label for="" class="font-[MuseoSans] text-[13px] ml-2">
                                            DE
                                        </label>
                                    </div>
                                    <div class="mr-4 text-center w-[80%] text-[13px] font-bold border-b border-gray-700">
                                        <p class="font-[MuseoSans]">
                                            {{ moment(permissionToPrint.fecha_inicio_permiso).format('MMMM').toUpperCase()
                                            }}
                                        </p>
                                    </div>
                                </div>
                                <div class="relative flex flex-row w-[25%]">
                                    <div class="flex justify-start w-[30%]">
                                        <label for="" class="font-[MuseoSans] text-[13px] ml-3">
                                            DE
                                        </label>
                                    </div>
                                    <div class="ml-1 text-center w-[70%] text-[13px] font-bold border-b border-gray-700">
                                        <p class="font-[MuseoSans]">
                                            {{ moment(permissionToPrint.fecha_inicio_permiso).format('YYYY') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="flex w-full justify-between items-center mb-2">
                            <div class="flex w-full text-left">
                                <div class="relative flex flex-row w-full">
                                    <div class="flex justify-start w-[10%]">
                                        <label for="" class="font-[MuseoSans] text-[13px]">
                                            NOMBRE:
                                        </label>
                                    </div>
                                    <div class="text-left w-[90%] text-[13px] font-bold border-b border-gray-700">
                                        <p class="font-[MuseoSans] ml-3">
                                            {{ permissionToPrint.empleado ?
                                                permissionToPrint.empleado.titulo_profesional.codigo_titulo_profesional : '' }}
                                            {{ permissionToPrint.empleado ?
                                                permissionToPrint.empleado.persona.pnombre_persona : '' }}
                                            {{ permissionToPrint.empleado ?
                                                permissionToPrint.empleado.persona.snombre_persona : '' }}
                                            {{ permissionToPrint.empleado ?
                                                permissionToPrint.empleado.persona.tnombre_persona : '' }}
                                            {{ permissionToPrint.empleado ?
                                                permissionToPrint.empleado.persona.papellido_persona : '' }}
                                            {{ permissionToPrint.empleado ?
                                                permissionToPrint.empleado.persona.sapellido_persona : '' }}
                                            {{ permissionToPrint.empleado ?
                                                permissionToPrint.empleado.persona.tapellido_persona : '' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="flex w-full justify-between items-center mb-2">
                            <div class="flex w-full text-left">
                                <div class="relative flex flex-row w-full">
                                    <div class="flex justify-start w-[51%]">
                                        <label for="" class="font-[MuseoSans] text-[13px]">
                                            CENTRO DE ATENCIÓN, DEPARTAMENTO O SECCIÓN:
                                        </label>
                                    </div>
                                    <div class="text-left w-[49%] text-[13px] font-bold border-b border-gray-700">
                                        <p class="font-[MuseoSans] ml-3">{{ centro1 }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="flex w-full justify-between items-center mb-2">
                            <div class="flex w-full text-left">
                                <div class="relative flex flex-row w-full">
                                    <div class="text-left w-full text-[13px] font-bold border-b border-gray-700"
                                        :class="centro2 ? '' : 'py-2'">
                                        <p class="font-[MuseoSans] ml-2">{{ centro2 }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="flex w-full justify-between items-center mb-6">
                            <div class="flex w-full text-left">
                                <div class="relative flex flex-row w-full">
                                    <div class="flex justify-start w-[9%]">
                                        <label for="" class="font-[MuseoSans] text-[13px]">
                                            MOTIVO:
                                        </label>
                                    </div>
                                    <div class="text-left w-[91%] text-[13px] font-bold border-b border-gray-700">
                                        <p class="font-[MuseoSans] ml-3">
                                            {{ permissionToPrint.motivo_permiso ?
                                                permissionToPrint.motivo_permiso.nombre_motivo_permiso : '' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="flex w-full justify-between items-center mb-6">
                            <div class="flex w-full text-left">
                                <div class="relative flex flex-row w-full">
                                    <div class="flex w-[50%]">
                                        <div class="flex justify-start w-[70%] mx-auto">
                                            <div class="flex justify-start w-[50%]">
                                                <label for="" class="font-[MuseoSans] text-[13px]">
                                                    HORA ENTRADA:
                                                </label>
                                            </div>
                                            <div class="text-center w-[50%] text-[13px] border-b border-gray-700">
                                                <p class="font-bold font-[MuseoSans]">
                                                    {{ permissionToPrint.hora_entrada_permiso ?
                                                        formatHour(permissionToPrint.hora_entrada_permiso) :
                                                        '-------------' }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex w-[50%]">
                                        <div class="flex justify-start w-[70%] mx-auto">
                                            <div class="flex justify-start w-[50%]">
                                                <label for="" class="font-[MuseoSans] text-[13px]">
                                                    HORA SALIDA:
                                                </label>
                                            </div>
                                            <div class="text-center w-[50%] text-[13px] border-b border-gray-700">
                                                <p class="font-bold font-[MuseoSans]">
                                                    {{ permissionToPrint.hora_salida_permiso ?
                                                        formatHour(permissionToPrint.hora_salida_permiso) :
                                                        '-------------' }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div v-if="permissionToPrint.id_estado_permiso != 4 && permissionToPrint.id_estado_permiso != 5"
                            class="flex w-full justify-between items-center mb-4 mt-4">
                            <div class="flex w-full text-left">
                                <div class="relative flex flex-row w-full">
                                    <div class="flex justify-start w-[90%]">
                                        <div class="flex justify-start w-[85%]">
                                            <label for="" class="font-[MuseoSans] text-[13px]">
                                                CANTIDAD DE PERMISOS DE NO MARCACION (incluyendo este) EN
                                                {{
                                                    moment(permissionToPrint.fecha_inicio_permiso).format('MMMM').toUpperCase()
                                                }}:
                                            </label>
                                        </div>
                                        <div class="text-center w-[15%] text-[13px] border-b border-gray-700">
                                            <p class="font-bold font-[MuseoSans] ">{{ limite }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex w-full justify-between items-center mb-2">
                            <div class="flex w-full text-left">
                                <div class="relative flex flex-row w-full">
                                    <div class="flex justify-start w-full">
                                        <label for="" class="font-[MuseoSans] text-[13px]">
                                            Observaciones:
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="flex w-full justify-between items-center mb-2">
                            <div class="flex w-full text-left">
                                <div class="relative flex flex-row w-full">
                                    <div class="text-left w-full text-[13px] border-b border-gray-700"
                                        :class="observation1 ? '' : 'py-2'">
                                        <p class="font-bold font-[MuseoSans] ml-2">{{ observation1 }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="flex w-full justify-between items-center pb-10">
                            <div class="flex w-full text-left">
                                <div class="relative flex flex-row w-full">
                                    <div class="text-left w-full text-[13px] border-b border-gray-700"
                                        :class="observation2 ? '' : 'py-2'">
                                        <p class="font-bold mb-[5px] font-[MuseoSans] ml-2">{{ observation2 }}</p>
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
import PermisoF026PDFVue from '@/pdf/RRHH/PermisoF026PDF.vue';
import { createApp, h } from 'vue'
import html2pdf from 'html2pdf.js'
export default {
    props: {
        viewPermission026: {
            type: Boolean,
            default: false,
        },
        permissionToPrint: {
            type: Array,
            default: [],
        },
        limite: {
            type: String,
            default: ''
        },
        stages: {
            type: Array,
            default: []
        },
        showOptions: {
            type: Boolean,
            default: true,
        }
    },
    data: function () {
        return {
            isLoading: false,
            messageError: '',
            denialComment: '',
            showButtons: true,
            showDenialOptions: false,
            centro1: '',
            centro2: '',
            observation1: '',
            observation2: ''
        }
    },
    methods: {
        setApprobalRejectButtons(permission) {
            if (this.showOptions && permission.id_estado_permiso != 3 && permission.id_estado_permiso != 4) {
                const rolId = this.$page.props.menu.id_rol
                const range = [14, 15, 16, 17]
                if (range.includes(rolId)) {
                    if (rolId === 14) {
                        let stage = permission.etapa_permiso.find((element) => element.id_estado_etapa_permiso === 2 || element.id_estado_etapa_permiso === 3)
                        if (stage) {
                            this.showButtons = false
                        }
                    }
                    if (rolId === 15) {
                        let stage = permission.etapa_permiso.find((element) => element.id_estado_etapa_permiso === 4 || element.id_estado_etapa_permiso === 5)
                        if (stage) {
                            this.showButtons = false
                        }
                    }
                    if (rolId === 16) {
                        let stage = permission.etapa_permiso.find((element) => element.id_estado_etapa_permiso === 6 || element.id_estado_etapa_permiso === 7)
                        if (stage) {
                            this.showButtons = false
                        }
                    }
                    if (rolId === 17) {
                        let stage = permission.etapa_permiso.find((element) => element.id_estado_etapa_permiso === 8 || element.id_estado_etapa_permiso === 9)
                        if (stage) {
                            this.showButtons = false
                        }
                    }
                } else {
                    this.showButtons = false
                }
            } else {
                this.showButtons = false
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
                    14: '/supervisor-denial',
                    15: '/director-denial',
                    16: '/medical-management-denial',
                    17: '/general-management-denial'
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
        async approvePermission() {
            const idRol = this.$page.props.menu.id_rol;

            const urlMap = {
                14: '/supervisor-approval',
                15: '/director-approval',
                16: '/medical-management-approval',
                17: '/general-management-approval'
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
        getCentro() {
            let limiteCaracteres = 40;
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
            let limiteCaracteres = 108;
            let string = this.permissionToPrint.comentarios_permiso;
            if (string) {
                if (string.length <= limiteCaracteres) {
                    this.observation1 = string;
                } else {
                    let textoTruncado = string.slice(0, limiteCaracteres);
                    let ultimoEspacio = textoTruncado.lastIndexOf(' ');
                    let primeraLetraMayuscula = textoTruncado.charAt(0).toUpperCase() + textoTruncado.slice(1).toLowerCase();
                    this.observation1 = primeraLetraMayuscula.slice(0, ultimoEspacio);

                    // Convierte observacion2 a minúsculas
                    this.observation2 = string.slice(ultimoEspacio + 1).toLowerCase();
                }
            }
        },
        formatHour(time) {
            let [hora, minutos] = time.split(':');
            let amPm = parseInt(hora) < 12 ? 'AM' : 'PM';

            // Si la hora es 12, cambia 'AM' a 'PM' y ajusta la hora a 12
            if (parseInt(hora) === 12) {
                amPm = 'MD';
            } else if (parseInt(hora) === 0) {
                // Si la hora es 00, ajusta la hora a 12
                hora = '12';
            } else {
                hora = (parseInt(hora) % 12).toString();
            }

            // Añade un 0 delante si los minutos tienen un solo dígito
            minutos = minutos.padStart(2, '0');

            return `${hora}:${minutos} ${amPm}`;
        },
        printPermission() {
            const lastStage = this.stages[this.stages.length - 1]
            const supervisor = this.getSupervisorName(lastStage)
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

            const app = createApp(
                PermisoF026PDFVue, {
                permission,
                centro1: this.centro1,
                centro2: this.centro2,
                observation1: this.observation1,
                observation2: this.observation2,
                dia: fechaParseada.format('DD'),
                mes: fechaParseada.format('MMMM').toUpperCase(),
                anio: fechaParseada.format('YYYY'),
                limite: this.limite,
                supervisor
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
        getSupervisorName(stage) {
            const persona = stage.empleado.persona;
            const nombres = [persona.pnombre_persona, persona.snombre_persona, persona.tnombre_persona].filter(Boolean).join(' ');
            const apellidos = [persona.papellido_persona, persona.sapellido_persona, persona.tapellido_persona].filter(Boolean).join(' ');

            return stage.empleado.titulo_profesional.codigo_titulo_profesional + ' ' + nombres + ' ' + apellidos;
        }
    },
    watch: {
        viewPermission026: function (value, oldValue) {
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
            }
        },
    },
    computed: {
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