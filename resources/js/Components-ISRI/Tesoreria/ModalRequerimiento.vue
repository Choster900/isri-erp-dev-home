<script setup>
import { Head } from "@inertiajs/vue3";
import ModalBasicVue from "@/Components-ISRI/AllModal/ModalBasic.vue";
import { toast } from 'vue3-toastify';
import InputError from "@/Components/InputError.vue";
import 'vue3-toastify/dist/index.css';
</script>

<template>
    <ModalBasicVue title="Gestión número de requerimiento. " id="scrollbar-modal" maxWidth="2xl" :modal_data="modal_data"
        :modalOpen="requerimientoModalOpen" @close-modal="$emit('close-definitive')">
        <div class="py-5">
            <div class="flex-row justify-center items-center mb-7 mx-4">
                <div class="mb-4 md:flex flex-row justify-items-start mx-8">
                    <div class="md:mr-2 md:mb-0 w-1/2">
                        <TextInput v-model="dataRequerimiento.numero_requerimiento_pago" :label-input="true"
                            id="numero-requerimiento" type="text" placeholder="Requerimiento"
                            @update:modelValue="validateInput('numero_requerimiento_pago', limit = 11, number=true)">
                            <LabelToInput icon="objects" forLabel="numero-requerimiento" />
                        </TextInput>
                        <InputError v-for="(item, index) in errors.numero_requerimiento_pago" :key="index" class="mt-2"
                            :message="item" />
                    </div>
                    <div class="md:mr-0 md:mb-0 w-1/2">
                        <TextInput v-model="dataRequerimiento.monto_requerimiento_pago" :label-input="true"
                            id="monto-requerimiento" type="text" placeholder="Monto"
                            @update:modelValue="validateInput('monto_requerimiento_pago', limit = 11, false, monto=true)">
                            <LabelToInput icon="money" forLabel="monto-requerimiento" />
                        </TextInput>
                        <InputError v-for="(item, index) in errors.monto_requerimiento_pago" :key="index" class="mt-2"
                            :message="item" />
                    </div>
                </div>
                <div class="mb-4 mx-8 basis-full" style="border: none; background-color: transparent;">
                    <label class="block mb-2 text-xs font-light text-gray-600" for="descripcion">
                        Descripcion <span class="text-red-600 font-extrabold">*</span>
                    </label>
                    <textarea v-model="dataRequerimiento.descripcion_requerimiento_pago" id="descripcion" name="descripcion"
                        class="resize-none w-full h-16 overflow-y-auto peer text-xs font-semibold rounded-r-md border border-slate-400 px-2 text-slate-900 transition-colors duration-300 focus:border-[#001b47] focus:outline-none"
                        @input="validateInput('descripcion_requerimiento_pago', limit = 255)">
                    </textarea>
                    <InputError v-for="(item, index) in errors.description" :key="index" class="mt-2" :message="item" />
                </div>
                <!-- Buttons -->
                <div class="mt-4 mb-4 md:flex flex-row justify-center">
                    <GeneralButton v-if="dataRequerimiento.id_requerimiento_pago" @click="updateRequerimiento()"
                        color="bg-orange-700  hover:bg-orange-800" text="Actualizar" icon="update" />
                    <GeneralButton v-else @click="addRequerimiento()" color="bg-green-700  hover:bg-green-800"
                        text="Agregar" icon="add" />
                    <div class="mb-4 md:mr-2 md:mb-0 px-1">
                        <GeneralButton text="Cancelar" icon="add" @click="requerimientoModalOpen = false" />
                    </div>
                </div>
            </div>
        </div>
    </ModalBasicVue>
</template>

<script>
export default {
    props: {
        requerimientoModalOpen: {
            type: Boolean,
            default: false,
        },
        modal_data: {
            type: Array,
            default: [],
        },
    },
    data: function (props) {
        return {
            errors: [],
            dataRequerimiento: {
                id_requerimiento_pago: '',
                numero_requerimiento_pago: '',
                monto_requerimiento_pago: '',
                mes_requerimiento_pago: '',
                anio_requerimiento_pago: '',
                descripcion_requerimiento_pago: ''
            },
        }
    },
    methods: {
        //Function to validate data entry
        validateInput(field, limit, number, monto) {
            if (this.dataRequerimiento[field] && this.dataRequerimiento[field].length > limit) {
                this.dataRequerimiento[field] = this.dataRequerimiento[field].substring(0, limit);
            }
            if (number) {
                this.dataRequerimiento[field] = this.dataRequerimiento[field].replace(/[^0-9]/g, '');
            }
            if(monto){
                this.typeAmount(field)
            }
        },
        typeAmount(field) {
            let x = this.dataRequerimiento[field].replace(/^\./, '').replace(/[^0-9.]/g, '')
            this.dataRequerimiento[field] = x
            const regex = /^(\d+)?([.]?\d{0,2})?$/
            if (!regex.test(this.dataRequerimiento[field])) {
                this.dataRequerimiento[field] = this.dataRequerimiento[field].match(regex) || x.substring(0, x.length - 1)
            }
        },
        addRequerimiento() {
            this.$swal.fire({
                title: "¿Está seguro de guardar el nuevo requerimiento?",
                icon: "question",
                iconHtml: "❓",
                confirmButtonText: "Si, Guardar",
                confirmButtonColor: "#141368",
                cancelButtonText: "Cancelar",
                showCancelButton: true,
                showCloseButton: true,
            })
                .then((result) => {
                    if (result.isConfirmed) {
                        // Obtener la fecha actual
                        let currentDate = new Date();
                        this.dataRequerimiento.mes_requerimiento_pago = currentDate.getMonth() + 1;
                        this.dataRequerimiento.anio_requerimiento_pago = currentDate.getFullYear();

                        axios({
                            method: 'POST',
                            url: '/add-requerimiento',
                            data: this.dataRequerimiento
                        }).then((data) => {
                            toast.success("Requerimiento agregado con extio", {
                                autoClose: 5000,
                                position: "top-right",
                                transition: "zoom",
                                toastBackgroundColor: "white",
                            });
                            this.$emit('close-definitive')
                            this.$emit("updateTable")
                        }).catch((errors) => {
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
                        })
                    }
                });

        },
        updateRequerimiento() {
            this.$swal
                .fire({
                    title: '¿Está seguro de actualizar el requerimiento?',
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
                        axios({
                            method: 'POST',
                            url: '/update-requerimiento',
                            data: this.dataRequerimiento
                        }).then((data) => {
                            toast.success("Requerimiento actualizado con exito", {
                                autoClose: 5000,
                                position: "top-right",
                                transition: "zoom",
                                toastBackgroundColor: "white",
                            });
                            this.$emit("updateTable")
                            this.$emit('close-definitive')
                        }).catch((errors) => {
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
                        })
                    }
                });
        },
    },
    watch: {
        requerimientoModalOpen: function (value) {
            if (value) {
                this.errors = [];

                this.dataRequerimiento.id_requerimiento_pago = this.modal_data.id_requerimiento_pago
                this.dataRequerimiento.numero_requerimiento_pago = this.modal_data.numero_requerimiento_pago
                this.dataRequerimiento.monto_requerimiento_pago = this.modal_data.monto_requerimiento_pago
                this.dataRequerimiento.mes_requerimiento_pago = this.modal_data.mes_requerimiento_pago
                this.dataRequerimiento.anio_requerimiento_pago = this.modal_data.anio_requerimiento_pago
                this.dataRequerimiento.descripcion_requerimiento_pago = this.modal_data.descripcion_requerimiento_pago
            }
        },
    }
}
</script>