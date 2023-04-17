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
        <ModalBasicVue :modalOpen="showModalIncome" @close-modal="$emit('cerrar-modal')"
            :title="'Administración de recibos de ingreso. '" maxWidth="4xl" @close-modal-persona="$emit('cerrar-modal')">
            <div class="px-5 py-8">
                <div class="space-y-2">
                    <div class="mb-4" id="app">
                        <!-- Form Header -->
                        <div class="pb-2 mb-4 md:flex flex-row justify-between">
                            <span class="font-semibold text-slate-800 mb-2 text-lg underline underline-offset-2">
                                Información general del recibo.
                            </span>
                        </div>
                        <div class="mb-7 md:flex flex-row justify-items-start">
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/3">
                                <label class="block mb-2 text-xs font-light text-gray-600">
                                    Especifico <span class="text-red-600 font-extrabold">*</span>
                                </label>
                                <div class="relative font-semibold flex h-8 w-full flex-row-reverse">
                                    <Multiselect v-model="income_receipt.budget_account_id" :options="budget_accounts"
                                        @select="getIncomeConcept($event)" placeholder="Seleccione Especifico"
                                        :searchable="true" />
                                    <LabelToInput icon="list" />
                                </div>
                            <InputError v-for="(item, index) in errors.budget_account_id" :key="index" class="mt-2"
                                    :message="item" />
                            </div>
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/3">
                                <!-- <TextInput id="detail-amount" v-model="total" :value="total" :label-input="false"
                                    type="number">
                                    
                                </TextInput> -->
                                <input type="text" v-model="total" :value="total" class="border-none pointer-events-none bg-gray-100 p-2 rounded-md">
                                <!-- <InputError v-for="(item, index) in errors.name" :key="index" class="mt-2"
                                                            :message="item" /> -->
                            </div>
                        </div>

                        <div class="pb-4 mb-2 mt-7 md:flex flex-row justify-between">
                            <span class="font-semibold text-slate-800 mb-2 text-lg underline underline-offset-2">
                                Detalle del recibo.
                            </span>
                        </div>
                        <!-- Table -->
                        <div>
                            <div class="table-header">
                                <div>Concepto</div>
                                <div>Monto</div>
                                <div>
                                    <button class="text-green-500 hover:text-green-600 rounded-full" @click="addRow()">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M12 6C12.5523 6 13 6.44772 13 7V11H17C17.5523 11 18 11.4477 18 12C18 12.5523 17.5523 13 17 13H13V17C13 17.5523 12.5523 18 12 18C11.4477 18 11 17.5523 11 17V13H7C6.44772 13 6 12.5523 6 12C6 11.4477 6.44772 11 7 11H11V7C11 6.44772 11.4477 6 12 6Z"
                                                fill="currentColor" />
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M5 22C3.34315 22 2 20.6569 2 19V5C2 3.34315 3.34315 2 5 2H19C20.6569 2 22 3.34315 22 5V19C22 20.6569 20.6569 22 19 22H5ZM4 19C4 19.5523 4.44772 20 5 20H19C19.5523 20 20 19.5523 20 19V5C20 4.44772 19.5523 4 19 4H5C4.44772 4 4 4.44772 4 5V19Z"
                                                fill="currentColor" />
                                        </svg>
                                    </button>
                                    <!-- <button @click="addRow">Agregar Fila</button> -->
                                </div>
                            </div>
                            <div v-for="(row, index) in income_detail" :key="index" class="table-row">

                                <div class="mb-4 md:mr-2 md:mb-0 basis-2/3">
                                    <div class="relative font-semibold flex h-8 w-full flex-row-reverse">
                                        <Multiselect v-model="income_detail.income_concept_id" :value="income_detail.income_concept_id"
                                            :options="income_concept" placeholder="Seleccione Concepto"
                                            :searchable="true" />
                                        <LabelToInput icon="list" />
                                    </div>
                                    <!-- <InputError v-for="(item, index) in errors.dependency_id" :key="index" class="mt-2"
                                                :message="item" /> -->
                                </div>

                                <div class="mb-4 md:mr-2 md:mb-0 basis-1/3">
                                    <TextInput id="detail-amount" v-model="income_detail.amount" :value="income_detail.amount"
                                        :label-input="false" type="number" placeholder="Monto">
                                        <LabelToInput icon="money" forLabel="detail-amount" />
                                    </TextInput>
                                    <!-- <InputError v-for="(item, index) in errors.name" :key="index" class="mt-2"
                                                            :message="item" /> -->
                                </div>


                                <div>
                                    <button @click="deleteRow(index)" class="text-red-500 hover:text-red-600 rounded-full">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M17 5V4C17 2.89543 16.1046 2 15 2H9C7.89543 2 7 2.89543 7 4V5H4C3.44772 5 3 5.44772 3 6C3 6.55228 3.44772 7 4 7H5V18C5 19.6569 6.34315 21 8 21H16C17.6569 21 19 19.6569 19 18V7H20C20.5523 7 21 6.55228 21 6C21 5.44772 20.5523 5 20 5H17ZM15 4H9V5H15V4ZM17 7H7V18C7 18.5523 7.44772 19 8 19H16C16.5523 19 17 18.5523 17 18V7Z"
                                                fill="currentColor" />
                                            <path d="M9 9H11V17H9V9Z" fill="currentColor" />
                                            <path d="M13 9H15V17H13V9Z" fill="currentColor" />
                                        </svg>
                                    </button>
                                    <!-- <button @click="deleteRow(index)">Eliminar Fila</button> -->
                                </div>
                        </div>
                    </div>



                    </div>

                    <div class="mt-4 mb-4 md:flex flex-row justify-center">
                        <!-- <div class="mb-4 md:mr-2 md:mb-0 px-1">
                                                                <GeneralButton @click="saveNewIncome()" color="bg-green-700  hover:bg-green-800" text="Agregar"
                                                                icon="add" />
                                                            </div> -->
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
        showModalIncome: {
            type: Boolean,
            default: false,
        },
        budget_accounts: {
            type: Array,
            default: [],
        },
    },
    created() { },
    data: function (data) {
        return {
            rows: [
                { dependency: '', concept: '', amount: '' }
            ],
            income_concept: [],
            budget_account_id: '',
            errors: [],
            environments: [],
            budget_codes2: [
                { value: 61101, label: "61101 MOBILIARIO" },
                { value: 61102, label: "61102 MAQUINARIAS Y EQUIPO" },
                { value: 61103, label: "61103 EQUIPOS MEDICOS Y DE LABORATORIOS" },
                { value: 61104, label: "61104 EQUIPOS INFORMATICOS" },
                { value: 61108, label: "61108 HERRAMIENTAS Y REPUESTOS PRINCIPALES" },
                { value: 61110, label: "61110 MAQUINARIA Y EQUIPO PARA APOYO INSTITUCIONAL", },
                { value: 61199, label: "61199 BIENES MUEBLES DIVERSOS" },
            ],
            income_receipt: {
                budget_account_id:'',
                total:'',
                client:'',
                description:''
            },
            income_detail: {
                income_concept_id: "",
                amount: "",
            },
        };
    },
    methods: {
        addRow() {
            this.rows.push({ dependency: '', concept: '', amount: '' });
        },
        deleteRow(index) {
            this.rows.splice(index, 1);
        },
        getIncomeConcept(budget_account_id) {
            axios.get("/get-income-concept", {
                budget_account_id: budget_account_id
            })
                .then((response) => {
                    this.income_concept = response.data.income_concept
                })
                .catch((errors) => {
                    let msg = this.manageError(errors);
                    this.$swal.fire({
                        title: "Operación cancelada",
                        text: msg,
                        icon: "warning",
                        timer: 5000,
                    });
                    this.$emit("cerrar-modal");
                });
        },
        borrarFila: function (index) {
            this.dataValidation.filas.splice(index, 1);
            for (let i = index; i < this.dataValidation.filas.length; i++) {
                this.dataValidation.filas[i].index = i;
            }
        },
        saveNewIncome() {
            let filas = JSON.parse(JSON.stringify(this.dataValidation.filas))
            console.log(filas);
            axios
                .post("/save-income", this.dataValidation)
                .then((response) => {
                    console.log(response);
                    toast.success(response.data.mensaje, {
                        autoClose: 3000,
                        position: "top-right",
                        transition: "zoom",
                        toastBackgroundColor: "white",
                    });
                })
                .catch((errors) => {
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
                });
        },

        async getEnvironments(dependency_id) {
            await axios
                .get("/get-environments", {
                    params: {
                        dependency_id: dependency_id,
                    },
                })
                .then((response) => {
                    this.environments = response.data.environments;
                })
                .catch((errors) => {
                    let msg = this.manageError(errors);
                    this.$swal.fire({
                        title: "Operación cancelada",
                        text: msg,
                        icon: "warning",
                        timer: 5000,
                    });
                });
        },
        async getModels(brand_id) {
            await axios
                .get("/get-models", {
                    params: {
                        brand_id: brand_id,
                    },
                })
                .then((response) => {
                    this.models = response.data.models;
                })
                .catch((errors) => {
                    let msg = this.manageError(errors);
                    this.$swal.fire({
                        title: "Operación cancelada",
                        text: msg,
                        icon: "warning",
                        timer: 5000,
                    });
                });
        },
        typeSeats() {
            var x = this.asset.vehicle_seats.replace(/\D/g, "").match(/(\d{0,2})/);
            this.asset.vehicle_seats = !x[2] ? x[1] : "";
        },
        typeQuantity() {
            var x = this.asset.quantity.replace(/\D/g, "").match(/(\d{0,2})/);
            this.asset.quantity = !x[2] ? x[1] : "";
        },
        typeValAdquisicion() {
            let x = this.asset.acquisition_value
                .replace(/^\./, "")
                .replace(/[^0-9.]/g, "");
            this.asset.acquisition_value = x;
            const regex = /^(\d+)?([.]?\d{0,2})?$/;
            if (!regex.test(this.asset.acquisition_value)) {
                this.asset.acquisition_value =
                    this.asset.acquisition_value.match(regex) ||
                    x.substring(0, x.length - 1);
            }
            if (!this.asset.acquisition_value == "") {
                this.asset.acquisition_value = "$" + this.asset.acquisition_value;
            }
        },
    },
    watch: {
        showModalIncome: function (value, oldValue) {
            if (value) {
                this.errors = [];
                // this.asset.id_dependencia = this.modalData.id_modelo;
                // this.asset.id_codigo_presupuestario = this.modalData.nombre_modelo;
                // this.asset.id_fuente_financiamiento = this.modalData.id_marca;
            }
        },
    },
};
</script>


<style>
.table-header {
    display: flex;
    justify-content: space-between;
    font-weight: bold;
}

.table-row {
    display: flex;
    justify-content: space-between;
    margin-top: 0.5rem;
    margin-bottom: 1rem;
}
</style>
