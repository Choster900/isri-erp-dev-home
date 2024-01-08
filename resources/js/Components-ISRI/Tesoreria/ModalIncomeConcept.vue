<script setup>
import Modal from "@/Components-ISRI/AllModal/Modal.vue";
import InputError from "@/Components/InputError.vue";
import { toast } from "vue3-toastify";
import "vue3-toastify/dist/index.css";
import axios from "axios";
</script>

<template>
    <div class="m-1.5">
        <div v-if="isLoadingRequest"
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
        <Modal v-else :show="showModalIncome" @close="$emit('cerrar-modal')" :modal-title="'Concepto de ingresos. '"
            maxWidth="2xl">
            <div class="px-5 py-4">
                <div class="text-sm">

                    <div class="mb-4 md:flex flex-row justify-between">
                        <span class="font-semibold text-slate-800 text-lg underline underline-offset-2">
                            Información de concepto
                        </span>
                    </div>

                    <!-- First row -->
                    <div class="mb-7 md:flex flex-row justify-items-start">
                        <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                            <label class="block mb-2 text-xs font-light text-gray-600">
                                Centro <span class="text-red-600 font-extrabold">*</span>
                            </label>
                            <div class="relative font-semibold flex h-8 w-full flex-row-reverse">
                                <Multiselect v-model="income_concept.dependency_id" :options="dependencies"
                                    placeholder="Seleccione Centro" :searchable="true" />
                                <LabelToInput icon="list" />
                            </div>
                            <InputError v-for="(item, index) in errors.dependency_id" :key="index" class="mt-2"
                                :message="item" />
                        </div>
                        <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                            <label class="block mb-2 text-xs font-light text-gray-600">
                                Especifico <span class="text-red-600 font-extrabold">*</span>
                            </label>
                            <div class="relative font-semibold flex h-8 w-full flex-row-reverse">
                                <Multiselect v-model="income_concept.budget_account_id" :options="budget_accounts"
                                    placeholder="Seleccione Especifico" :searchable="true" />
                                <LabelToInput icon="list" />
                            </div>
                            <InputError v-for="(item, index) in errors.budget_account_id" :key="index" class="mt-2"
                                :message="item" />
                        </div>
                    </div>

                    <!-- Second row -->
                    <div class="mb-7 md:flex flex-row justify-items-start">
                        <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                            <TextInput id="name-income" v-model="income_concept.name" type="text"
                                placeholder="Concepto de Ingreso"
                                @update:modelValue="validateInput('name', limit = 145, upper = true)">
                                <LabelToInput icon="standard" forLabel="name-income" />
                            </TextInput>
                            <InputError v-for="(item, index) in errors.name" :key="index" class="mt-2" :message="item" />
                        </div>
                        <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                            <label class="block mb-2 text-xs font-light text-gray-600">
                                Fuente Financiamiento <span class="text-red-600 font-extrabold">*</span>
                            </label>
                            <div class="relative font-semibold flex h-8 w-full flex-row-reverse">
                                <Multiselect v-model="income_concept.financing_source_id" :options="financing_sources"
                                    placeholder="Seleccione Financiamiento" :searchable="true" />
                                <LabelToInput icon="list" />
                            </div>
                            <InputError v-for="(item, index) in errors.financing_source_id" :key="index" class="mt-2"
                                :message="item" />
                        </div>
                    </div>

                    <!-- Third row -->
                    <div class="mb-7 md:flex flex-row justify-items-start">
                        <div class="mb-4 md:mr-2 md:mb-0 basis-full">
                            <TextInput :required="false" id="detalle-concepto" v-model="income_concept.detail" type="text"
                                placeholder="Detalle concepto ingreso"
                                @update:modelValue="validateInput('detail', limit = 145)">
                                <LabelToInput icon="standard" forLabel="detalle-concepto" />
                            </TextInput>
                            <InputError v-for="(item, index) in errors.detail" :key="index" class="mt-2" :message="item" />
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="mt-4 mb-4 md:flex flex-row justify-center">
                        <GeneralButton v-if="modalData != ''" @click="updateIncomeConcept()"
                            color="bg-orange-700  hover:bg-orange-800" text="Actualizar" icon="update" />
                        <GeneralButton v-else @click="storeIncomeConcept()" color="bg-green-700  hover:bg-green-800"
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
        showModalIncome: {
            type: Boolean,
            default: false,
        },
        budget_accounts: {
            type: Array,
            default: []
        },
        dependencies: {
            type: Array,
            default: []
        },
        financing_sources: {
            type: Array,
            default: []
        },
    },
    created() { },
    data: function (data) {
        return {
            errors: [],
            environments: [],
            isLoadingRequest: false,
            income_concept: {
                dependency_id: '',
                budget_account_id: '',
                financing_source_id: '',
                name: '',
                detail: ''
            },
        };
    },
    methods: {
        //Function to validate data entry
        validateInput(field, limit, upper_case) {
            if (this.income_concept[field] && this.income_concept[field].length > limit) {
                this.income_concept[field] = this.income_concept[field].substring(0, limit);
            }
            if (upper_case) {
                this.toUpperCase(field)
            }
        },
        toUpperCase(field) {
            //Converts field to uppercase
            this.income_concept[field] = this.income_concept[field].toUpperCase()
        },
        async storeIncomeConcept() {
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
                .then(async (result) => {
                    if (result.isConfirmed) {
                        let url = '/save-income-concept'
                        this.saveIncomeConcept(url)
                    }
                });
        },
        async updateIncomeConcept() {
            this.$swal
                .fire({
                    title: '¿Está seguro de actualizar el concepto de ingreso?',
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
                        let url = '/update-income-concept'
                        this.saveIncomeConcept(url)
                    }
                });
        },
        async saveIncomeConcept(url) {
            this.isLoadingRequest = true
            await axios.post(url, this.income_concept)
                .then((response) => {
                    this.handleSuccessResponse(response.data.message);
                })
                .catch((errors) => {
                    this.handleErrorResponse(errors);
                })
                .finally(() => {
                    // Desactiva el loader aquí, ya sea que la solicitud haya tenido éxito o haya fallado
                    this.isLoadingRequest = false;
                });
        },
        saveNewIncomeConcept() {
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
                        axios
                            .post("/save-income-concept", this.income_concept)
                            .then((response) => {
                                toast.success(response.data.mensaje, {
                                    autoClose: 3000,
                                    position: "top-right",
                                    transition: "zoom",
                                    toastBackgroundColor: "white",
                                });
                                this.$emit("get-table");
                                this.$emit("cerrar-modal");
                            })
                            .catch((errors) => {
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
                            });
                    }
                });
        },
        updateIncomeConcept2() {
            this.$swal
                .fire({
                    title: '¿Está seguro de actualizar el concepto de ingreso?',
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
                        axios
                            .post("/update-income-concept", this.income_concept)
                            .then((response) => {
                                toast.success(response.data.mensaje, {
                                    autoClose: 3000,
                                    position: "top-right",
                                    transition: "zoom",
                                    toastBackgroundColor: "white",
                                });
                                this.$emit("get-table");
                                this.$emit("cerrar-modal");
                            })
                            .catch((errors) => {
                                if (errors.response.status === 422) {
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
                                        this.$emit("get-table");
                                        this.$emit("cerrar-modal");
                                    } else {
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
                                    }
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
                            });
                    }
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
                    this.$emit("get-table");
                    this.$emit("cerrar-modal");
                } else {
                    this.showToast(toast.warning, "Tienes algunos errores por favor verifica tus datos.");
                    this.errors = errors.response.data.errors;
                }
            } else {
                this.manageError(errors, this)
                this.$emit("cerrar-modal");
            }
        },
    },
    watch: {
        showModalIncome: function (value, oldValue) {
            if (value) {
                this.errors = [];
                this.income_concept.income_concept_id = this.modalData.id_concepto_ingreso;
                this.income_concept.dependency_id = this.modalData.id_centro_atencion;
                this.income_concept.budget_account_id = this.modalData.id_ccta_presupuestal;
                this.income_concept.name = this.modalData.nombre_concepto_ingreso;
                this.income_concept.financing_source_id = this.modalData.id_proy_financiado;
                this.income_concept.detail = this.modalData.detalle_concepto_ingreso;
            }
        },
    },
};
</script>
