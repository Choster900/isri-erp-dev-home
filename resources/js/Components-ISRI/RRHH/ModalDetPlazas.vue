<script setup>
import Modal from "@/Components-ISRI/AllModal/Modal.vue";
import InputError from "@/Components/InputError.vue";
import { toast } from "vue3-toastify";
import "vue3-toastify/dist/index.css";
import axios from "axios";
</script>

<template>
    <!-- <div v-if="isLoading"
        class="fixed top-0 left-0 right-0 bottom-0 flex items-center justify-center bg-gray-800 bg-opacity-50 z-50">
        <div class="loader ease-linear rounded-full border-4 border-t-4 border-gray-200 h-12 w-12"></div>
    </div> -->
    <div class="m-1.5">
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
        <Modal v-else :show="showModalJobPositionDet" @close="$emit('cerrar-modal')"
            :modal-title="'Administracion plazas. '" maxWidth="3xl">
            <div class="px-5 py-4">
                <div class="text-sm">

                    <div class="mb-2 md:flex flex-row justify-between">
                        <div class="md:w-1/2">
                            <span class="font-semibold text-slate-800 text-lg underline underline-offset-2">
                                Informacion de la plaza
                            </span>
                        </div>
                        <div v-if="this.modalData != ''" class="md:w-1/2 md:text-right">
                            <span class="font-semibold text-slate-800 text-lg">
                                Codigo plaza:
                            </span>
                            <span class="font-semibold text-slate-800 text-lg underline underline-offset-2">
                                {{ this.jobPositionDet.codigo_det_plaza }}
                            </span>
                        </div>
                    </div>

                    <!-- First row -->
                    <div class="mb-4 md:flex flex-row justify-items-start">
                        <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                            <label class="block mb-2 text-xs font-light text-gray-600">
                                Uplt <span class="text-red-600 font-extrabold">*</span>
                            </label>
                            <div class="relative font-semibold flex h-8 w-full flex-row-reverse">
                                <Multiselect v-model="jobPositionDet.actividad_institucional.linea_trabajo.id_lt"
                                    :options="selectOptions.uplt" :searchable="true"
                                    :placeholder="isLoading ? 'Cargando...' : 'Seleccione Uplt'" :disabled="isLoading"
                                    @select="filterActivities($event)" @clear="clearSelectUplt()" />
                                <LabelToInput icon="list" />
                            </div>
                            <InputError v-for="(item, index) in errors['actividad_institucional.linea_trabajo.id_lt']"
                                :key="index" class="mt-2" :message="item" />
                        </div>
                        <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                            <label class="block mb-2 text-xs font-light text-gray-600">
                                Fuente Financiamiento <span class="text-red-600 font-extrabold">*</span>
                            </label>
                            <div class="relative font-semibold flex h-8 w-full flex-row-reverse">
                                <Multiselect v-model="jobPositionDet.id_proy_financiado"
                                    :options="selectOptions.financingSources" :searchable="true"
                                    :placeholder="isLoading ? 'Cargando...' : 'Seleccione fuente'" :disabled="isLoading" />
                                <LabelToInput icon="list" />
                            </div>
                            <InputError v-for="(item, index) in errors.id_proy_financiado" :key="index" class="mt-2"
                                :message="item" />
                        </div>
                    </div>

                    <!-- Second row -->
                    <div class="mb-4 md:flex flex-row justify-items-start">
                        <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                            <label class="block mb-2 text-xs font-light text-gray-600">
                                Actividad institucional <span class="text-red-600 font-extrabold">*</span>
                            </label>
                            <div class="relative font-semibold flex h-8 w-full flex-row-reverse">
                                <Multiselect v-model="jobPositionDet.id_actividad_institucional"
                                    :options="selectOptions.activities" :searchable="true"
                                    :placeholder="isLoading ? 'Cargando...' : 'Seleccione actividad'"
                                    :disabled="isLoading" />
                                <LabelToInput icon="list" />
                            </div>
                            <InputError v-for="(item, index) in errors.id_actividad_institucional" :key="index" class="mt-2"
                                :message="item" />
                        </div>
                        <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                            <label class="block mb-2 text-xs font-light text-gray-600">
                                Plaza <span class="text-red-600 font-extrabold">*</span>
                            </label>
                            <div class="relative font-semibold flex h-8 w-full flex-row-reverse">
                                <Multiselect v-model="jobPositionDet.id_plaza" :options="selectOptions.jobPositions"
                                    :searchable="true" :placeholder="isLoading ? 'Cargando...' : 'Seleccione plaza'"
                                    :disabled="isLoading || modalData != ''" />
                                <LabelToInput icon="list" />
                            </div>
                            <InputError v-for="(item, index) in errors.id_plaza" :key="index" class="mt-2"
                                :message="item" />
                        </div>
                    </div>

                    <!-- Third row -->
                    <div class="mb-10 md:flex flex-row justify-items-start">
                        <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                            <label class="block mb-2 text-xs font-light text-gray-600">
                                Tipo contrato <span class="text-red-600 font-extrabold">*</span>
                            </label>
                            <div class="relative font-semibold flex h-8 w-full flex-row-reverse">
                                <Multiselect v-model="jobPositionDet.id_tipo_contrato"
                                    :options="selectOptions.contractTypes" :searchable="true"
                                    :placeholder="isLoading ? 'Cargando...' : 'Seleccione tipo'" :disabled="isLoading" />
                                <LabelToInput icon="list" />
                            </div>
                            <InputError v-for="(item, index) in errors.id_tipo_contrato" :key="index" class="mt-2"
                                :message="item" />
                        </div>
                        <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                            <label class="block mb-2 text-xs font-light text-gray-600">
                                Estado plaza <span class="text-red-600 font-extrabold">*</span>
                            </label>
                            <div class="relative font-semibold flex h-8 w-full flex-row-reverse">
                                <Multiselect v-model="jobPositionDet.id_estado_plaza"
                                    :options="statusOptions" :searchable="true"
                                    :placeholder="isLoading ? 'Cargando...' : 'Seleccione estado'"
                                    :disabled="true" />
                                <LabelToInput icon="list" />
                            </div>
                            <InputError v-for="(item, index) in errors.id_estado_plaza" :key="index" class="mt-2"
                                :message="item" />
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="mt-4 mb-4 md:flex flex-row justify-center">
                        <GeneralButton v-if="modalData != ''" @click="updateJobPositionDet()"
                            color="bg-orange-700  hover:bg-orange-800" text="Actualizar" icon="update" />
                        <GeneralButton v-else @click="storeJobPositionDet()" color="bg-green-700  hover:bg-green-800"
                            text="Agregar" icon="add" />
                        <div class="mb-4 md:mr-2 md:mb-0 px-1">
                            <GeneralButton text="Cancelar" icon="add" @click="$emit('cerrar-modal')" />
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
        modalData: {
            type: Array,
            default: [],
        },
        showModalJobPositionDet: {
            type: Boolean,
            default: false,
        },
    },
    created() { },
    data: function (data) {
        return {
            errors: [],
            isLoading: false,
            selectOptions: {
                uplt: [],
                financingSources: [],
                jobPositions: [],
                contractTypes: [],
                jobPositionStatus: [],
                allActivities: [],
                activities: []
            },
            jobPositionDet: {
                actividad_institucional: {
                    linea_trabajo: {
                        id_lt: ''
                    }
                },
                id_actividad_institucional: '',
                id_det_plaza: '',
                id_estado_plaza: '',
                id_plaza: '',
                id_proy_financiado: '',
                id_tipo_contrato: '',
                plaza_asignada_activa: null
            },
        };
    },
    methods: {
        storeJobPositionDet() {
            this.$swal
                .fire({
                    title: '¿Está seguro de guardar el nuevo concepto de ingreso?',
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
                        let url = '/store-job-position-det'
                        this.saveJobPositionDet(url)
                    }
                });
        },
        saveJobPositionDet(url) {
            axios.post(url, this.jobPositionDet)
                .then((response) => {
                    this.handleSuccessResponse(response.data.mensaje)
                })
                .catch((errors) => {
                    this.handleErrorResponse(errors)
                });
        },
        handleSuccessResponse(message) {
            toast.success(message, {
                autoClose: 3000,
                position: "top-right",
                transition: "zoom",
                toastBackgroundColor: "white",
            });
            this.$emit("get-table");
            this.$emit("cerrar-modal");
        },
        handleErrorResponse(errors) {
            console.log(errors);
            if (errors.response.status === 422) {
                toast.warning(
                    "Tienes algunos errores por favor verifica tus datos.",
                    {
                        autoClose: 5000,
                        position: "top-right",
                        transition: "zoom",
                        toastBackgroundColor: "white",
                    }
                );
                this.errors = errors.response.data.errors;
            } else {
                let msg = this.manageError(errors);
                this.$swal.fire({
                    title: "Operación cancelada",
                    text: msg,
                    icon: "warning",
                    timer: 5000,
                });
                this.$emit("cerrar-modal");
            }
        },
        updateJobPositionDet() {
            this.$swal
                .fire({
                    title: '¿Está seguro de actualizar la plaza?',
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
                        let url = '/update-job-position-det'
                        this.saveJobPositionDet(url)
                    }
                });
        },
        loadOptions() {
            this.selectOptions.uplt = []
            this.selectOptions.financingSources = []
            this.selectOptions.jobPositionStatus = []
            this.selectOptions.jobPositions = []
            this.selectOptions.activities = []
            this.selectOptions.allActivities = []
            this.selectOptions.contractTypes = []
            this.getSelectsJobPositionDet()
        },
        async getSelectsJobPositionDet() {
            try {
                this.isLoading = true;  // Activar el estado de carga
                const response = await axios.get("/get-selects-job-position-det");
                this.selectOptions.uplt = response.data.uplt
                this.selectOptions.jobPositionStatus = response.data.jobPositionStatus
                this.selectOptions.financingSources = response.data.financingSources
                this.selectOptions.contractTypes = response.data.contractTypes
                this.selectOptions.jobPositions = response.data.jobPositions
                this.selectOptions.allActivities = response.data.activities
                if (this.modalData != '') {
                    this.filterActivities(this.jobPositionDet.actividad_institucional.linea_trabajo.id_lt, false)
                }else{
                    this.jobPositionDet.id_estado_plaza=1
                }
            } catch (errors) {
                let msg = this.manageError(errors);
                this.$swal.fire({
                    title: 'Operación cancelada',
                    text: msg,
                    icon: 'warning',
                    timer: 5000
                });
            } finally {
                this.isLoading = false;  // Desactivar el estado de carga
            }
        },
        filterActivities(uplt_id, load = true) {
            if (load) {
                this.jobPositionDet.id_actividad_institucional = ''
            }
            this.selectOptions.activities = []
            this.selectOptions.activities = this.selectOptions.allActivities.filter(activity => activity.id_lt === uplt_id);
        },
        clearSelectUplt() {
            this.selectOptions.activities = []
            this.jobPositionDet.id_actividad_institucional = ''
        },
        cleanInputs() {
            this.jobPositionDet.actividad_institucional.linea_trabajo.id_lt = ''
            this.jobPositionDet.id_det_plaza = ''
            this.jobPositionDet.id_estado_plaza = ''
            this.jobPositionDet.id_plaza = ''
            this.jobPositionDet.id_proy_financiado = ''
            this.jobPositionDet.id_tipo_contrato = ''
            this.jobPositionDet.id_actividad_institucional = ''
            this.jobPositionDet.plaza_asignada_activa = null
        }
    },
    watch: {
        showModalJobPositionDet: function (value, oldValue) {
            if (value) {
                this.errors = [];
                if (this.modalData != '') {
                    this.jobPositionDet = JSON.parse(JSON.stringify(this.modalData));
                } else {
                    this.cleanInputs()
                }
                this.loadOptions()
            }
        },
    },
    computed: {
        statusOptions() {
            if (this.jobPositionDet.plaza_asignada_activa == null) {
                // Filter the array and return a new array without modifying the original one
                const filteredStatus = this.selectOptions.jobPositionStatus.filter((value) => {
                    return (value.value != 3);
                });
                return filteredStatus
            } else {
                return this.selectOptions.jobPositionStatus
            }
        }
    }
};
</script>

<style>
/* Customize the loader's appearance using Tailwind CSS classes or your custom styles */
.loader {
    border-top-color: #3498db;
    border-left-color: #3498db;
}
</style>
