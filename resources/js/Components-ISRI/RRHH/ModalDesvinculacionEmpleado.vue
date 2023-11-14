<script setup>
import Modal from "@/Components-ISRI/AllModal/Modal.vue";
import InputError from "@/Components/InputError.vue";
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
        <Modal v-else :show="showEmpTermination" @close="$emit('cerrar-modal')" :modal-title="'Desvinculación de empleado.'"
            maxWidth="3xl">
            <div class="px-5 py-4">
                <div class="text-sm">
                    <div class="flex border border-gray-400 rounded-lg mt-1 mx-6">
                        <div class="border-r border-gray-400 w-[15%] flex items-center justify-center py-2">
                            <svg class="text-orange-400" fill="currentColor" width="48px" height="48px" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg" stroke="currentColor">
                                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                <g id="SVGRepo_iconCarrier">
                                    <g>
                                        <path
                                            d="M19.79,16.72,11.06,1.61A1.19,1.19,0,0,0,9,1.61L.2,16.81C-.27,17.64.12,19,1.05,19H19C19.92,19,20.26,17.55,19.79,16.72ZM11,17H9V15h2Zm0-4H9L8.76,5h2.45Z">
                                        </path>
                                    </g>
                                </g>
                            </svg>
                        </div>
                        <div class="w-[85%] rounded-r-lg">
                            <h2 class="text-orange-500 text-center font-semibold">¡Aviso!</h2>
                            <p class="mx-2 mb-1 font-semibold">Estas a punto de desvincular al empleado del ISRI, ten en
                                cuenta que este proceso generaría
                                la desvinculación con el puesto (o los puestos), que tenga asociados.
                            </p>
                        </div>
                    </div>

                    <div class="mb-2 mt-2 md:flex flex-row items-center justify-center">
                        <div class="">
                            <span class="font-semibold text-slate-800 text-lg underline underline-offset-2">
                                Informacion de empleado
                            </span>
                        </div>
                    </div>

                    <div
                        class="rounded-lg leading-none px-5 py-2 border hover:bg-red-100 border-gray-400 mx-6 mt-2 shadow-lg hover:shadow-xl">
                        <div class="w-full mb-1">
                            <p class="text-sm text-gray-600">Nombre empleado</p>
                            <p class="text-base font-medium text-navy-700 dark:text-white">
                                {{ employee.persona.pnombre_persona }}
                                {{ employee.persona.snombre_persona }}
                                {{ employee.persona.tnombre_persona }}
                                {{ employee.persona.papellido_persona }}
                                {{ employee.persona.sapellido_persona }}
                                {{ employee.persona.tapellido_persona }}
                            </p>
                        </div>
                        <div class="flex flex-col space-y-1 md:flex-row md:space-x-2 mb-1">
                            <div class="w-full md:w-[30%]">
                                <p class="text-sm text-gray-600">Fecha contratación</p>
                                <p class="text-base font-medium text-navy-700 dark:text-white">
                                    {{ moment(employee.fecha_contratacion_empleado).format('DD/MM/YYYY') }}
                                </p>
                            </div>
                            <div class="w-full md:w-[30%]">
                                <p class="text-sm text-gray-600">Codigo empleado</p>
                                <p class="text-base font-medium text-navy-700 dark:text-white">
                                    {{ employee.codigo_empleado }}
                                </p>
                            </div>
                            <div class="w-full md:w-[40%]">
                                <p class="text-sm text-gray-600">Dependencia(s)</p>
                                <p class="text-base font-medium text-navy-700 dark:text-white">
                                    {{ showDependencies(employee.plazas_asignadas) }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="mb-2 mt-2 md:flex flex-row items-center justify-center">
                        <div class="">
                            <span class="font-semibold text-slate-800 text-lg underline underline-offset-2">
                                Informacion de desvinculacion
                            </span>
                        </div>
                    </div>

                    <div class="mx-6 mt-2 md:flex flex-row justify-items-start">
                        <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                            <label class="block mb-2 text-xs font-light text-gray-600">
                                Motivo desvinculacion <span class="text-red-600 font-extrabold">*</span>
                            </label>
                            <div class="font-semibold relative flex h-8 w-full flex-row-reverse ">
                                <Multiselect placeholder="Seleccione Motivo" v-model="emp.idDissociate"
                                    :options="reasonsForDissociate" :searchable="true" />
                                <LabelToInput icon="list" />
                            </div>
                            <InputError v-for="(item, index) in errors.idDissociate" :key="index" class="mt-2"
                                :message="item" />
                        </div>
                        <div class="mb-4 md:mr-0 md:mb-0 basis-1/2">
                            <label class="block mb-2 text-xs font-light text-gray-600">
                                Fecha desvinculacion <span class="text-red-600 font-extrabold">*</span>
                            </label>
                            <div class="relative flex">
                                <LabelToInput icon="date" />
                                <flat-pickr
                                    class="peer font-semibold text-xs cursor-pointer rounded-r-md border h-8 border-slate-400 px-2 text-slate-900 placeholder-slate-400 transition-colors duration-300 focus:border-[#001b47] focus:outline-none w-full"
                                    :config="config" v-model="emp.dateOfDissociate" :placeholder="'Seleccione fecha'" />
                            </div>
                            <InputError v-for="(item, index) in errors.dateOfDissociate" :key="index" class="mt-2"
                                :message="item" />
                        </div>
                    </div>
                    <div class="mx-6 mt-4 md:flex flex-row justify-items-start">
                        <div class="mb-4 md:mr-2 md:mb-0 basis-1/4">
                            <label class="block mb-5 text-xs font-light text-gray-600">
                                Recibio compensación <span class="text-red-600 font-extrabold">*</span>
                            </label>
                            <label for="checbox1" class="text-sm font-bold text-gray-700 ml-4 mr-1">SI
                            </label>
                            <checkbox v-model="emp.financialCompensationY" :checked="emp.financialCompensationY"
                                class="mr-3" ref="check1" id="checbox1" @click="setFinancialCompensation(true)" />
                            <label for="checbox2" class="text-sm font-bold text-gray-700 ml-4 mr-1">NO
                            </label>
                            <checkbox v-model="emp.financialCompensationN" :checked="emp.financialCompensationN"
                                class="mr-3" ref="check2" id="checbox2" @click="setFinancialCompensation(false)" />
                            <InputError v-for="(item, index) in errors.fncCompensation" :key="index" class="mt-7"
                                :message="item" />
                        </div>
                        <div class="mb-4 md:mr-0 md:mb-0 basis-3/4">
                            <label class="block mb-2 text-xs font-light text-gray-600">
                                Comentarios <span class="text-red-600 font-extrabold">*</span>
                            </label>
                            <textarea v-model="emp.observation" id="observation" name="observation"
                                class="resize-none w-full h-12 overflow-y-auto peer text-xs font-semibold rounded-md border border-slate-400 px-2 text-slate-900 transition-colors duration-300 focus:border-[#001b47] focus:outline-none">
                            </textarea>
                            <InputError v-for="(item, index) in errors.observation" :key="index" class="mt-2"
                                :message="item" />
                        </div>
                    </div>

                    <div class="flex justify-center mt-4 pb-4">
                        <GeneralButton class="mr-1" color="bg-red-700 hover:bg-red-800" text="Inhabilitar" icon="delete"
                            @click="desactiveEmployee()" />
                        <GeneralButton text="Cancelar" icon="delete" @click="$emit('cerrar-modal')" />
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
        showEmpTermination: {
            type: Boolean,
            default: false,
        },
    },
    created() {
        this.setInitialInformation()
    },
    data: function (data) {
        return {
            errors: [],
            isLoading: false,
            reasonsForDissociate: [],
            employee: [],
            emp: {
                id: '',
                idDissociate: '',
                dateOfDissociate: '',
                fncCompensation: '',
                financialCompensationY: false,
                financialCompensationN: false,
            },
            config: {
                altInput: true,
                monthSelectorType: 'static',
                altFormat: "d/m/Y",
                dateFormat: "Y-m-d",
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
        async setInitialInformation() {
            this.employee = JSON.parse(JSON.stringify(this.modalData));
            this.emp.id = this.employee.id_empleado
            //Get data to select
            this.isLoading = true;
            await axios.get("/get-data-emp-termination")
                .then((response) => {
                    this.reasonsForDissociate = response.data.reasonsForDissociate
                })
                .catch((errors) => {
                    this.manageError(errors, this)
                })
                .finally(() => {
                    this.isLoading = false;
                });
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
        },
        async desactiveEmployee() {
            this.$swal
                .fire({
                    title: '¿Está seguro de inhabilitar al empleado?',
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
                        this.isLoading = true
                        await axios.post('/desactive-employee', this.emp)
                            .then((response) => {
                                this.showToast(toast.success, response.data.mensaje);
                                this.$emit("get-table");
                                this.$emit("cerrar-modal")
                            })
                            .catch((errors) => {
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
                            })
                            .finally(() => {
                                this.isLoading = false
                            });
                    }
                });
        },
        setFinancialCompensation(value) {
            if (value) {
                this.emp.financialCompensationY ? this.emp.financialCompensationY = false : this.emp.financialCompensationY = true
                this.emp.financialCompensationN = false
                this.emp.fncCompensation = this.emp.financialCompensationY ? 1 : ''
            } else {
                this.emp.financialCompensationN ? this.emp.financialCompensationN = false : this.emp.financialCompensationN = true
                this.emp.financialCompensationY = false
                this.emp.fncCompensation = this.emp.financialCompensationN ? 0 : ''
            }
        }
    },
    watch: {
    },
    computed: {
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
