<script setup>
import Modal from "@/Components-ISRI/AllModal/Modal.vue";
import { toast } from "vue3-toastify";
import "vue3-toastify/dist/index.css";
import axios from "axios";
import moment from 'moment';

</script>

<template>
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
        <Modal v-else :show="showModalJobPosition" @close="$emit('cerrar-modal')" :modal-title="'Agregar Plaza. '"
            maxWidth="2xl">
            <div class="px-5 py-4">

                <transition name="fade" mode="out-in">
                    <div v-if="showJobPositions">
                        <div class="mx-4 sm:flex sm:justify-end sm:items-center mb-2">
                            <div class="grid grid-flow-col sm:auto-cols-max sm:justify-end gap-2">
                                <GeneralButton color="bg-green-700  hover:bg-green-800" text="Agregar" icon="add"
                                    @click="showJobPositions = false" />
                            </div>
                        </div>
                        <h1 class="text-center mb-2 font-semibold text-slate-800 text-lg underline underline-offset-2">
                            PLAZAS
                            ASIGNADAS</h1>
                        <div v-for="jobPosition in jobPositions" :key="jobPosition.id_plaza_asignada"
                            class="w-full bg-gray-50 flex flex-col justify-start relative sm:py-2 mb-1">
                            <div class="max-w-full mx-4 relative">
                                <div class="absolute inset-0 bg-gray-600 rounded-lg blur opacity-50 z-0"></div>
                                <div
                                    class="relative z-10 px-5 py-4 bg-white ring-1 ring-gray-900/5 rounded-lg leading-none">
                                    <!-- Dependencia -->
                                    <div class="w-full mb-2">
                                        <p class="text-sm text-gray-600">Dependencia</p>
                                        <p class="text-base font-medium text-navy-700 dark:text-white">
                                            {{ jobPosition.dependencia.codigo_dependencia }} - {{
                                                jobPosition.dependencia.nombre_dependencia }}
                                        </p>
                                    </div>
                                    <!-- Plaza - Codigo -->
                                    <div class="flex flex-col space-y-1 md:flex-row md:space-x-6">
                                        <div class="w-full md:w-[75%]">
                                            <p class="text-sm text-gray-600">Plaza</p>
                                            <p class="text-base font-medium text-navy-700 dark:text-white">
                                                {{ jobPosition.detalle_plaza.plaza.nombre_plaza }}
                                            </p>
                                        </div>
                                        <div class="w-full md:w-[25%]">
                                            <p class="text-sm text-gray-600">Codigo</p>
                                            <p class="text-base font-medium text-navy-700 dark:text-white">
                                                {{ jobPosition.detalle_plaza.codigo_det_plaza }}
                                            </p>
                                        </div>
                                    </div>
                                    <!-- Tipo Contrato - Fecha Asignacion -->
                                    <div class="flex flex-col space-y-1 md:flex-row md:space-x-6">
                                        <div class="w-full md:w-[75%]">
                                            <p class="text-sm text-gray-600">Tipo Contratación</p>
                                            <p class="text-base font-medium text-navy-700 dark:text-white">
                                                {{ jobPosition.detalle_plaza.tipo_contrato.nombre_tipo_contrato }}
                                            </p>
                                        </div>
                                        <div class="w-full md:w-[25%]">
                                            <p class="text-sm text-gray-600">Fecha Asignación</p>
                                            <p class="text-base font-medium text-navy-700 dark:text-white">
                                                {{ moment(jobPosition.fecha_plaza_asignada).format('DD/MM/YYYY') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="flex justify-center mt-4">
                            <GeneralButton class="mr-1" text="Cerrar" icon="delete" @click="$emit('cerrar-modal')" />
                        </div>
                    </div>

                    <div v-else>
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
                                    <InputError class="mt-2" :message="errors.dependencyId" />
                                </div>
                                <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                                    <label class="block mb-2 text-xs font-light text-gray-600">
                                        Plaza <span class="text-red-600 font-extrabold">*</span>
                                    </label>
                                    <div class="font-semibold relative flex h-8 w-full flex-row-reverse ">
                                        <Multiselect placeholder="Seleccione Plaza" v-model="jobPosition.jobPositionId"
                                            :options="jobPositionsToSelect" :searchable="true"
                                            @change="setSalaryLimits($event)" />
                                        <LabelToInput icon="list" />
                                    </div>
                                    <InputError class="mt-2" :message="errors.jobPositionId" />
                                </div>
                            </div>
                            <div class="mb-10 md:flex flex-row justify-items-start">
                                <div class="mb-4 md:mr-2 md:mb-0 w-1/3">
                                    <TextInput id="salary" v-model="jobPosition.salary" type="text"
                                        :placeholder="selectedJobPosition ? 'Salario ($' + lowerSalaryLimit + ' - $' + upperSalaryLimit + ')' : 'Salario (Seleccione plaza)'"
                                        :disabled="!selectedJobPosition"
                                        @update:modelValue="validateEmployeeInputs('salary', 7, false, true)">
                                        <LabelToInput icon="money" forLabel="salary" />
                                    </TextInput>
                                    <InputError class="mt-2" :message="errors.salary" />
                                </div>
                                <div class="mb-4 md:mr-2 md:mb-0 w-1/3">
                                    <TextInput id="account" v-model="jobPosition.account" type="text" placeholder="Partida"
                                        @update:modelValue="validateEmployeeInputs('account', 6, true, false)">
                                        <LabelToInput icon="standard" forLabel="account" />
                                    </TextInput>
                                    <InputError class="mt-2" :message="errors.account" />
                                </div>
                                <div class="mb-4 md:mr-2 md:mb-0 w-1/3">
                                    <TextInput id="subaccount" v-model="jobPosition.subaccount" type="text"
                                        placeholder="Subpartida"
                                        @update:modelValue="validateEmployeeInputs('subaccount', 6, true, false)">
                                        <LabelToInput icon="standard" forLabel="subaccount" />
                                    </TextInput>
                                    <InputError class="mt-2" :message="errors.subaccount" />
                                </div>
                            </div>
                            <!-- End second row Page4 -->
                        </div>
                        <div class="flex justify-center mt-4">
                            <GeneralButton class="mr-1" text="Cancelar" icon="delete" @click="showJobPositions = true" />
                            <GeneralButton color="bg-green-700 hover:bg-green-800" text="Guardar" icon="add" />
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
    created() { },
    data: function (data) {
        return {
            errors: [],
            showJobPositions: true,
            jobPositionsToSelect: [],
            dependencies: [],
            employee: [],
            isLoading: false,
            selectedJobPosition: '',
            lowerSalaryLimit: '',
            upperSalaryLimit: '',
            jobPositions: [],
            jobPosition: {
                dependencyId: '',
                jobPositionId: '',
                salary: '',
                account: '',
                subaccount: ''
            }
        };
    },
    methods: {
        async getJobPositions() {
            try {
                this.isLoading = true;  // Activar el estado de carga
                const response = await axios.get("/get-job-positios-by-employee", {
                    params: {
                        id_empleado: this.employee.id_empleado
                    }
                });
                this.jobPositions = response.data.jobPositions.plazas_asignadas
                this.jobPositionsToSelect = response.data.jobPositionsToSelect
                this.dependencies = response.data.dependencies
            } catch (errors) {
                this.manageError(errors, this)
            } finally {
                this.isLoading = false;  // Desactivar el estado de carga
            }
        },
        setSalaryLimits(jobPositionId) {
            const selectedJob = this.jobPositionsToSelect.find(value => value.value === jobPositionId);
            selectedJob ? this.lowerSalaryLimit = selectedJob.salario_base_plaza : this.lowerSalaryLimit = ''
            selectedJob ? this.upperSalaryLimit = selectedJob.salario_tope_plaza : this.upperSalaryLimit = ''
            selectedJob ? this.selectedJobPosition = true : this.selectedJobPosition = false
            !selectedJob ? this.jobPosition.salary = '' : ''
        },
    },
    watch: {
        showModalJobPosition: function (value, oldValue) {
            if (value) {
                this.showJobPositions = true
                this.errors = []
                this.employee = JSON.parse(JSON.stringify(this.modalData));
                this.getJobPositions()
            }
        },
    },
    computed: {
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
}</style>