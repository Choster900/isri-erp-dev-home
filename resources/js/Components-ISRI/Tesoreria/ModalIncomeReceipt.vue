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
        <ModalBasicVue :modalOpen="show_modal_receipt" :title="'Administración de recibos de ingreso. '" maxWidth="4xl"
            @close-modal="$emit('cerrar-modal')">
            <div class="px-5 py-8">
                <div class="space-y-2">
                    <div class="mb-2" id="app">
                        <div class="mb-2 md:flex flex-row justify-items-start">
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                                <TextInput id="name-client" v-model="income_receipt.client" :value="income_receipt.client"
                                    type="text" placeholder="Nombre o Razón Social">
                                    <LabelToInput icon="standard" forLabel="name-client" />
                                </TextInput>
                                <InputError v-for="(item, index) in errors.client" :key="index" class="mt-2"
                                    :message="item" />
                            </div>
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                                <label class="block mb-2 text-xs font-light text-gray-600">
                                    Especifico <span class="text-red-600 font-extrabold">*</span>
                                </label>
                                <div class="relative font-semibold flex h-8 w-full flex-row-reverse">
                                    <Multiselect v-model="income_receipt.budget_account_id" :options="budget_accounts"
                                        :disabled="budget_select"
                                        @input="selectBudgetAccount($event, income_receipt.budget_account_id)"
                                        placeholder="Seleccione Especifico" :searchable="true" />
                                    <LabelToInput icon="list" />
                                </div>
                                <InputError v-for="(item, index) in errors.budget_account_id" :key="index" class="mt-2"
                                    :message="item" />
                            </div>
                        </div>
                        <div class="mb-2 md:flex flex-row justify-items-start">
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                                <label class="block mb-2 text-xs font-light text-gray-600">
                                    Fuente Financiamiento <span class="text-red-600 font-extrabold">*</span>
                                </label>
                                <div class="relative font-semibold flex h-8 w-full flex-row-reverse">
                                    <Multiselect v-model="income_receipt.financing_source_id" :options="financing_sources"
                                        :disabled="financing_select" @change="selectFinancingSource($event)"
                                        placeholder="Seleccione Financiamiento" :searchable="true" />
                                    <LabelToInput icon="list" />
                                </div>
                                <InputError v-for="(item, index) in errors.financing_source_id" :key="index" class="mt-2"
                                    :message="item" />
                            </div>
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                                <label class="block mb-2 text-xs font-light text-gray-600">
                                    Tesorero, Pagador o Colector <span class="text-red-600 font-extrabold">*</span>
                                </label>
                                <div class="relative font-semibold flex h-8 w-full flex-row-reverse">
                                    <Multiselect v-model="income_receipt.treasury_clerk_id" :options="treasury_clerk"
                                        placeholder="Seleccione Tesorero" :searchable="true" />
                                    <LabelToInput icon="list" />
                                </div>
                                <InputError v-for="(item, index) in errors.treasury_clerk_id" :key="index" class="mt-2"
                                    :message="item" />
                            </div>
                        </div>

                        <div v-if="income_receipt.budget_account_id === 16304"
                            class="mb-4 md:flex flex-row justify-items-start">
                            <div class="mb-4 md:mr-2 md:mb-0 basis-2/3">
                                <TextInput id="client-direccion" v-model="income_receipt.direction"
                                    :value="income_receipt.direction" type="text" placeholder="Direccion">
                                    <LabelToInput icon="standard" forLabel="client-direccion" />
                                </TextInput>
                                <InputError v-for="(item, index) in errors.direction" :key="index" class="mt-2"
                                    :message="item" />
                            </div>
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/3">
                                <TextInput id="document-client" v-model="income_receipt.document"
                                    :value="income_receipt.document" type="text" placeholder="Documento">
                                    <LabelToInput icon="standard" forLabel="document-client" />
                                </TextInput>
                                <InputError v-for="(item, index) in errors.document" :key="index" class="mt-2"
                                    :message="item" />
                            </div>
                        </div>

                        <div class="mb-4 md:mr-2 md:mb-0 basis-full" style="border: none; background-color: transparent;">
                            <label class="block mb-2 text-xs font-light text-gray-600" for="descripcion">
                                Descripcion <span class="text-red-600 font-extrabold">*</span>
                            </label>
                            <textarea v-model="income_receipt.description" id="descripcion" name="descripcion"
                                class="resize-none w-full h-10 overflow-y-auto peer text-xs font-semibold rounded-r-md border border-slate-400 px-2 text-slate-900 transition-colors duration-300 focus:border-[#001b47] focus:outline-none"></textarea>
                            <InputError v-for="(item, index) in errors.description" :key="index" class="mt-2"
                                :message="item" />
                        </div>

                        <div class="mb-1 mt-0 md:flex flex-row justify-items-start">
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                                <span class="font-semibold text-slate-800 mb-2 text-lg underline underline-offset-2">
                                    Detalle del recibo.
                                </span>
                            </div>
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/2 text-right">
                                <span class="font-semibold text-slate-800 mb-2 text-lg">
                                    Total: ${{ income_receipt.total }}
                                </span>
                            </div>
                        </div>
                        <!-- Table -->
                        <div class="mt-0 pb-6">
                            <div class="table-header">
                                <div class="basis-2/3">Concepto</div>
                                <div class="basis-1/3">Monto</div>
                                <div>
                                    <button :disabled="active_details.length > 6"
                                        class="text-green-500 hover:text-green-600 rounded-full" @click="addRow()">
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
                                </div>
                            </div>
                            <div v-for="(row, index) in active_details" :key="index" class="table-row">
                                <div class="mb-2 md:mr-2 md:mb-0 basis-2/3">
                                    <div class="relative font-semibold flex h-8 w-full flex-row-reverse">
                                        <Multiselect v-model="row.income_concept_id" :value="row.income_concept_id"
                                            @input="selectConcept($event, row.income_concept_id)"
                                            :options="income_concept_select" placeholder="Seleccione Concepto"
                                            :searchable="true" />
                                        <LabelToInput icon="list" />
                                    </div>
                                    <InputError
                                        v-for="(item, index2) in errors['income_detail.' + index + '.income_concept_id']"
                                        :key="index2" class="mt-2" :message="item" />
                                </div>

                                <div class="mb-2 md:mr-2 md:mb-0 basis-1/3">
                                    <TextInput id="detail-amount" v-model="row.amount" :value="row.amount"
                                        :label-input="false" type="text" placeholder="Monto"
                                        @update:modelValue="typeAmountIncome(index)">
                                        <LabelToInput icon="money" forLabel="detail-amount" />
                                    </TextInput>
                                    <InputError v-for="(item, index2) in errors['income_detail.' + index + '.amount']"
                                        :key="index2" class="mt-2" :message="item" />
                                </div>

                                <div>
                                    <button :disabled="available_details <= 1"
                                        @click="deleteRow(index, row.income_concept_id, row.detail_id)"
                                        class="text-red-500 hover:text-red-600 rounded-full">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M17 5V4C17 2.89543 16.1046 2 15 2H9C7.89543 2 7 2.89543 7 4V5H4C3.44772 5 3 5.44772 3 6C3 6.55228 3.44772 7 4 7H5V18C5 19.6569 6.34315 21 8 21H16C17.6569 21 19 19.6569 19 18V7H20C20.5523 7 21 6.55228 21 6C21 5.44772 20.5523 5 20 5H17ZM15 4H9V5H15V4ZM17 7H7V18C7 18.5523 7.44772 19 8 19H16C16.5523 19 17 18.5523 17 18V7Z"
                                                fill="currentColor" />
                                            <path d="M9 9H11V17H9V9Z" fill="currentColor" />
                                            <path d="M13 9H15V17H13V9Z" fill="currentColor" />
                                        </svg>
                                    </button>
                                </div>

                            </div>
                        </div>

                    </div>

                    <div class="mt-4 mb-4 md:flex flex-row justify-center">
                        <GeneralButton v-if="modal_data != ''" @click="updateIncomeReceipt()"
                            color="bg-orange-700  hover:bg-orange-800" text="Actualizar" icon="update" />
                        <GeneralButton v-else @click="saveIncomeReceipt()" color="bg-green-700  hover:bg-green-800"
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
        show_modal_receipt: {
            type: Boolean,
            default: false,
        },
        budget_accounts: {
            type: Array,
            default: [],
        },
        modal_data: {
            type: Array,
            default: [],
        },
        income_concepts: {
            type: Array,
            default: [],
        },
        treasury_clerk: {
            type: Array,
            default: []
        },
        // financing_sources: {
        //     type: Array,
        //     default: []
        // }
    },
    created() { },
    data: function (data) {
        return {
            financing_sources: [],
            receipt_id: '',
            income_concept_select: [],
            budget_account_id: '',
            errors: [],
            budget_select: false,
            financing_select: false,
            income_receipt: {
                financing_source_id: '',
                treasury_clerk_id: '',
                income_receipt_id: '',
                total: '',
                budget_account_id: '',
                direction: '',
                document: '',
                client: '',
                description: '',
                income_detail: [],
            },
        };
    },
    methods: {
        addRow() {
            this.income_receipt.income_detail.push({ detail_id: '', income_concept_id: '', amount: '', deleted: false });
        },
        clearDetails() {
            //console.log('antes de deleted = true',this.income_receipt.income_detail);
            // this.income_receipt.income_detail.forEach((value,index) => {
            //     value.deleted=true
            // })
            //console.log('despues de deleted = true',this.income_receipt.income_detail);
            //this.income_receipt.income_detail = []
            var array = { detail_id: '', income_concept_id: '', amount: '', deleted: false }
            this.income_receipt.income_detail.push(array)
        },
        clearDetailSelectionsAndTotal() {
            //limpiando valores de detalles
            this.clearDetails()
            this.updateTotal()
        },
        getFinanceSource(new_selection) {
            axios.get("/get-select-financing-source", { params: { budget_account_id: new_selection } })
                .then((response) => {
                    this.financing_sources = response.data.financing_sources
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
        selectBudgetAccount(new_selection, old_selection) {
            //Nueva seleccion
            console.log(new_selection, old_selection);
            if (new_selection != null && old_selection == null) {
                this.clearDetailSelectionsAndTotal()
                //Limpiando select de concept de ingresos
                this.income_concept_select = []
                this.getFinanceSource(new_selection)
            } else {
                //Cambio de seleccion
                if (new_selection != null && old_selection != null) {
                    this.clearDetailSelectionsAndTotal()
                    //Limpiando select de concepto de ingresos
                    this.income_concept_select = []
                    //Limpiando select de fuente de financiamiento y seleccion
                    this.financing_sources = []
                    this.income_receipt.financing_source_id = ''
                    this.getFinanceSource(new_selection)
                } else {
                    this.financing_sources = []
                    this.income_receipt.financing_source_id = ''
                }
            }
        },
        selectFinancingSource(new_selection) {
            if (this.modal_data == '') {
                this.clearDetailSelectionsAndTotal()
                this.getIncomeConcept(new_selection)
            }else{
                this.income_concept_select = []
                this.getIncomeConcept(new_selection)
            }
        },
        deleteRow(index, concept_id, detail_id) {
            this.$swal.fire({
                title: 'Eliminar concepto de ingreso.',
                text: "¿Estas seguro?",
                icon: 'warning',
                showCancelButton: true,
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#DC2626',
                cancelButtonColor: '#4B5563',
                confirmButtonText: 'Si, Eliminar.'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.income_concept_select.forEach((value, index) => {
                        if (value.value == concept_id) {
                            this.income_concept_select[index].disabled = false
                        }
                    })
                    if (detail_id == "") {
                        this.income_receipt.income_detail.splice(index, 1);
                    } else {
                        this.income_receipt.income_detail[index].deleted = true;
                    }
                    this.updateTotal()
                }
            })

        },
        typeAmountIncome(index) {
            let x = this.income_receipt.income_detail[index].amount.replace(/^\./, '').replace(/[^0-9.]/g, '')
            this.income_receipt.income_detail[index].amount = x
            const regex = /^(\d+)?([.]?\d{0,2})?$/
            if (!regex.test(this.income_receipt.income_detail[index].amount)) {
                this.income_receipt.income_detail[index].amount = this.income_receipt.income_detail[index].amount.match(regex) || x.substring(0, x.length - 1)
            }
            this.updateTotal()
        },
        updateTotal() {
            var sum = 0;
            for (var i = 0; i < this.income_receipt.income_detail.length; i++) {
                if (this.income_receipt.income_detail[i].deleted == false) {
                    var amount = parseFloat(this.income_receipt.income_detail[i].amount);
                    if (!isNaN(amount)) {
                        sum += amount;
                    }
                }
            }
            this.income_receipt.total = sum.toFixed(2);
        },
        selectConcept(newSelection, oldSelection) {
            if (newSelection == null) {
                this.income_concept_select.forEach((value, index) => {
                    if (value.value == oldSelection) {
                        this.income_concept_select[index].disabled = false
                    }
                })
            } else {
                if (oldSelection == null || oldSelection == '') {
                    this.income_concept_select.forEach((value, index) => {
                        if (value.value == newSelection) {
                            this.income_concept_select[index].disabled = true
                        }
                    })
                } else {
                    this.income_concept_select.forEach((value, index) => {
                        if (value.value == oldSelection) {
                            this.income_concept_select[index].disabled = false
                        }
                        if (value.value == newSelection) {
                            this.income_concept_select[index].disabled = true
                        }
                    })
                }
            }
        },
        saveIncomeReceipt() {
            this.$swal.fire({
                title: "¿Está seguro de guardar el nuevo recibo de ingreso?",
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
                        axios.post("/save-income-receipt", this.income_receipt)
                            .then((response) => {
                                console.log(response.data.letras);
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
                                    console.log(errors.response.data);
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
        updateIncomeReceipt() {
            this.$swal
                .fire({
                    title: "¿Está seguro de actualizar el recibo de ingreso?",
                    icon: "question",
                    iconHtml: '❓',
                    confirmButtonText: "Si, Actualizar",
                    confirmButtonColor: "#D2691E",
                    cancelButtonText: "Cancelar",
                    showCancelButton: true,
                    showCloseButton: true,
                })
                .then((result) => {
                    if (result.isConfirmed) {
                        axios
                            .post("/update-income-receipt", this.income_receipt)
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
        getAndSetDetails(details) {
            console.log('sfew');
            if (details) {
                this.income_concept_select.forEach((value, index) => {
                    details.forEach((value2, index2) => {
                        if (value.value == value2.income_concept_id && value2.deleted == true) {
                            value.disabled = false
                        }
                    })
                })
            }
        },
        getIncomeConcept(new_selection){
            // console.log('si entra');
            axios.get("/get-select-income-concept", { params: { financing_source_id: new_selection, budget_account_id: this.income_receipt.budget_account_id } })
                .then((response) => {
                    this.income_concept_select = response.data.income_concept_select
                    this.getAndSetDetails(this.income_receipt.income_detail)
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
        }
    },
    watch: {
        show_modal_receipt: function (value, oldValue) {
            if (value) {
                this.income_concept_select = []
                this.errors = []
                this.income_receipt.income_receipt_id = this.modal_data.id_recibo_ingreso;
                this.income_receipt.total = '';
                this.income_receipt.financing_source_id = '';
                this.income_receipt.budget_account_id = this.modal_data.id_ccta_presupuestal
                this.income_receipt.financing_source_id = this.modal_data.id_proy_financiado
                this.income_receipt.treasury_clerk_id = this.modal_data.id_empleado_tesoreria
                this.income_receipt.direction = this.modal_data.direccion_cliente_recibo_ingreso
                this.income_receipt.document = this.modal_data.doc_identidad_recibo_ingreso
                this.income_receipt.client = this.modal_data.cliente_recibo_ingreso
                this.income_receipt.description = this.modal_data.descripcion_recibo_ingreso
                this.income_receipt.income_detail = []

                if (this.modal_data != '') {
                    console.log('entra al modal data');
                    //Define default values for financing source select
                    this.financing_sources = []
                    var sources_array = { value: this.modal_data.proyecto_financiado.id_proy_financiado, label: this.modal_data.proyecto_financiado.nombre_proy_financiado, selected: true }
                    this.financing_sources.push(sources_array)
                    this.selectFinancingSource(this.modal_data.proyecto_financiado.id_proy_financiado)

                    this.modal_data.detalles.forEach((value, index) => {
                        if(value.estado_det_recibo_ingreso==1){
                            var array = { nombre_concepto: value.concepto_ingreso.nombre_concepto_ingreso,detail_id: value.id_det_recibo_ingreso, income_concept_id: value.id_concepto_ingreso, amount: value.monto_det_recibo_ingreso, deleted: false }
                            this.income_receipt.income_detail.push(array)
                        }else{
                            var array = { nombre_concepto: value.concepto_ingreso.nombre_concepto_ingreso,detail_id: value.id_det_recibo_ingreso, income_concept_id: value.id_concepto_ingreso, amount: value.monto_det_recibo_ingreso, deleted: true }
                            this.income_receipt.income_detail.push(array)
                        }
                    })
                }else{
                    this.income_receipt.income_detail = []
                    console.log(this.income_receipt.income_detail);
                    this.clearDetails()
                }
                // if (this.modal_data.detalles) {
                //     this.modal_data.detalles.forEach((value, index) => {
                //         var array = { detail_id: value.id_det_recibo_ingreso, income_concept_id: value.id_concepto_ingreso, amount: value.monto_det_recibo_ingreso, deleted: false }
                //         this.income_receipt.income_detail.push(array)
                //     })
                // } else {
                //     this.clearDetails()
                // }
                this.updateTotal()
            }
        },
    },
    computed: {
        atributoWatch() {
            if (this.income_receipt.budget_account_id === "") {
                this.financing_sources = []; // Establecer el array de opciones en vacío
                this.income_receipt.financing_source_id = ""; // Establecer el valor en vacío
            }
            return this.income_receipt.financing_source_id;
        },
        active_details() {
            return this.income_receipt.income_detail.filter((detail) => detail.deleted == false)
        },
        available_details() {
            if (this.income_receipt.income_detail) {
                let count = 0
                this.income_receipt.income_detail.forEach((value, index) => {
                    if (value.deleted == false) {
                        count = count + 1
                    }
                })
                return count
            }
        }
    }
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
    margin-bottom: 0.5rem;
}
</style>
