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
        <ModalBasicVue :modalOpen="show_modal_specific_asset" @close-modal="$emit('cerrar-modal')"
            :title="'Administración de Bienes Especificos.'" maxWidth="3xl">
            <div class="px-5 py-4">
                <div class="text-sm">
                    <!-- First row -->
                    <div class="mb-4 md:flex flex-row justify-items-start">
                        <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                            <label class="block mb-2 text-xs font-light text-gray-600">
                                Especifico <span class="text-red-600 font-extrabold">*</span>
                            </label>
                            <div class="relative font-semibold flex h-8 w-full flex-row-reverse">
                                <Multiselect v-model="specific_asset.budget_account_id" :options="budget_accounts"
                                    placeholder="Seleccione Especifico" :searchable="true" />
                                <LabelToInput icon="list" />
                            </div>
                            <InputError v-for="(item, index) in errors.budget_account_id" :key="index" class="mt-2"
                                :message="item" />
                        </div>
                        <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                            <TextInput id="name-asset" v-model="specific_asset.name" :value="specific_asset.name"
                                type="text" placeholder="Nombre del bien especifico"
                                @update:modelValue="validateInput('name', limit = 145, upper = true)">
                                <LabelToInput icon="standard" forLabel="name-asset" />
                            </TextInput>
                            <InputError v-for="(item, index) in errors.name" :key="index" class="mt-2" :message="item" />
                        </div>
                    </div>
                    <!-- Second row -->
                    <div class="mb-7 md:mr-2 md:mb-0 basis-full" style="border: none; background-color: transparent;">
                        <label class="block mb-2 text-xs font-light text-gray-600" for="descripcion">
                            Descripcion
                        </label>
                        <textarea v-model="specific_asset.description" id="descripcion" name="descripcion"
                            class="resize-none w-full h-14 overflow-y-auto peer text-xs font-semibold rounded-r-md border border-slate-400 px-2 text-slate-900 transition-colors duration-300 focus:border-[#001b47] focus:outline-none"
                            @input="validateInput('description', limit = 255)">
                            </textarea>
                        <InputError v-for="(item, index) in errors.description" :key="index" class="mt-2" :message="item" />
                    </div>

                    <!-- Buttons -->
                    <div class="mt-4 mb-4 md:flex flex-row justify-center">
                        <GeneralButton v-if="modalData != ''" @click="updateSpecificAsset()"
                            color="bg-orange-700  hover:bg-orange-800" text="Actualizar" icon="update" />
                        <GeneralButton v-else @click="saveNewSpecificAsset()" color="bg-green-700  hover:bg-green-800"
                            text="Agregar" icon="add" />
                        <div class="mb-4 md:mr-2 md:mb-0 px-1">
                            <GeneralButton text="Cancelar" icon="add" @click="$emit('cerrar-modal')" />
                        </div>
                    </div>
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
        show_modal_specific_asset: {
            type: Boolean,
            default: false,
        },
        budget_accounts: {
            type: Array,
            default: []
        },
    },
    created() { },
    data: function (data) {
        return {
            errors: [],
            specific_asset: {
                id: '',
                budget_account_id: '',
                name: '',
                description: '',
            },
        };
    },
    methods: {
        //Function to validate data entry
        validateInput(field, limit, upper_case) {
            if (this.specific_asset[field] && this.specific_asset[field].length > limit) {
                this.specific_asset[field] = this.specific_asset[field].substring(0, limit);
            }
            if (upper_case) {
                this.toUpperCase(field)
            }
        },
        toUpperCase(field) {
            //Converts field to uppercase
            this.specific_asset[field] = this.specific_asset[field].toUpperCase()
        },
        saveNewSpecificAsset() {
            this.$swal
                .fire({
                    title: '¿Está seguro de guardar el nuevo bien especifico?',
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
                            .post("/save-specific-asset", this.specific_asset)
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
        updateSpecificAsset() {
            this.$swal
                .fire({
                    title: '¿Está seguro de actualizar el bien especifico?',
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
                            .post("/update-specific-asset", this.specific_asset)
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
    },
    watch: {
        show_modal_specific_asset: function (value, oldValue) {
            if (value) {
                this.errors = [];
                this.specific_asset.id = this.modalData.id_bien_especifico;
                this.specific_asset.name = this.modalData.nombre_bien_especifico;
                this.specific_asset.description = this.modalData.descripcion_bien_especifico;
                this.specific_asset.budget_account_id = this.modalData.id_ccta_presupuestal;
            }
        },
    },
};
</script>
