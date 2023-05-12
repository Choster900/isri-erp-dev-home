<script setup>
import Modal from "@/Components/Modal.vue";
import ModalBasicVue from "@/Components-ISRI/AllModal/ModalBasic.vue";
import InputError from "@/Components/InputError.vue";
import { toast } from "vue3-toastify";
import "vue3-toastify/dist/index.css";
import axios from "axios";
</script>

<template>
    <div class="m-1.5">
        <ModalBasicVue :modalOpen="showModalIncome" @close-modal="$emit('cerrar-modal')" :title="'Concepto de ingresos. '"
            maxWidth="3xl" >
            <div class="px-5 py-4">
                <div class="text-sm">

                    <div class="pb-4 mb-4 md:flex flex-row justify-between">
                        <span class="font-semibold text-slate-800 mb-2 text-lg underline underline-offset-2">
                            Información de Ingreso
                        </span>
                    </div>

                    <!-- First row -->
                    <div class="mb-7 md:flex flex-row justify-items-start">
                        <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                            <label class="block mb-2 text-xs font-light text-gray-600">
                                Dependencia <span class="text-red-600 font-extrabold">*</span>
                            </label>
                            <div class="relative font-semibold flex h-8 w-full flex-row-reverse">
                                <Multiselect v-model="income_concept.dependency_id" :options="dependencies"
                                    placeholder="Seleccione Dependencia" :searchable="true" />
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
                            <TextInput id="name-income" v-model="income_concept.name" :value="income_concept.name"
                                type="text" placeholder="Concepto de Ingreso" @update:modelValue="onInput()">
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
                            <TextInput id="fecha-adquisicion" v-model="income_concept.detail" :value="income_concept.detail"
                                type="text" placeholder="Detalle concepto ingreso">
                                <LabelToInput icon="standard" forLabel="fecha-adquisicion" />
                            </TextInput>
                            <InputError v-for="(item, index) in errors.detail" :key="index" class="mt-2" :message="item" />
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="mt-4 mb-4 md:flex flex-row justify-center">
                        <GeneralButton v-if="modalData != ''" @click="updateIncomeConcept()"
                            color="bg-orange-700  hover:bg-orange-800" text="Actualizar" icon="update" />
                        <GeneralButton v-else @click="saveNewIncomeConcept()" color="bg-green-700  hover:bg-green-800"
                            text="Agregar" icon="add" />
                        <div class="mb-4 md:mr-2 md:mb-0 px-1">
                            <GeneralButton text="Cancelar" icon="add" @click="$emit('cerrar-modal')" />
                        </div>
                    </div>
                    <div class="text-xs text-slate-500">ISRI2023</div>
                </div>
            </div>
        </ModalBasicVue>
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
        onInput(){
            this.income_concept.name = this.income_concept.name.toUpperCase();
        },
        saveNewIncomeConcept() {
            this.$swal
                .fire({
                    title: "¿Está seguro de guardar el nuevo concepto de ingreso?",
                    icon: "question",
                    iconHtml: "✅",
                    confirmButtonText: "Si, Guardar",
                    confirmButtonColor: "#15803D",
                    cancelButtonText: "Cancelar",
                    showCancelButton: true,
                    showCloseButton: true,
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
        updateIncomeConcept() {
            this.$swal
                .fire({
                    title: "¿Está seguro de actualizar el concepto de ingreso?",
                    icon: "question",
                    iconHtml: "❓",
                    confirmButtonText: "Si, Actualizar",
                    confirmButtonColor: "#D2691E",
                    cancelButtonText: "Cancelar",
                    showCancelButton: true,
                    showCloseButton: true,
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
    },
    watch: {
        showModalIncome: function (value, oldValue) {
            if (value) {
                this.errors = [];
                this.income_concept.income_concept_id = this.modalData.id_concepto_ingreso;
                this.income_concept.dependency_id = this.modalData.id_dependencia;
                this.income_concept.budget_account_id = this.modalData.id_ccta_presupuestal;
                this.income_concept.name = this.modalData.nombre_concepto_ingreso;
                this.income_concept.financing_source_id = this.modalData.id_proy_financiado;
                this.income_concept.detail = this.modalData.detalle_concepto_ingreso;
            }
        },
    },
};
</script>