
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
        <ProcessModal v-else maxWidth='4xl' :show="show_modal_receipt" @close="$emit('cerrar-modal')" :rounded="true">
            <div class="flex flex-wrap">
                <!-- Columna con 25% de ancho -->
                <div class="w-full max-h-full sm:w-[25%] bg-indigo-500 p-4 rounded-lg">
                    <!-- Paso 1 -->
                    <div class="flex items-center mb-4 my-5" :class="currentPage == 1 ? '' : 'opacity-70'">
                        <div class="rounded-full font-bold w-9 h-9 flex items-center justify-center mr-4"
                            :class="currentPage == 1 ? 'bg-indigo-200 text-black' : 'bg-indigo-500 border border-indigo-200 text-indigo-200'">
                            1
                        </div>
                        <div :class="currentPage == 1 ? 'text-black' : 'text-indigo-200'">
                            <p class="font-bold text-[12px]">PASO 1</p>
                            <p class=" font-bold text-sm">INFORMACION</p>
                        </div>
                    </div>

                    <!-- Paso 2 -->
                    <div class="flex items-center my-5" :class="currentPage == 2 ? '' : 'opacity-70'">
                        <div class="rounded-full font-bold w-9 h-9 flex items-center justify-center mr-4"
                            :class="currentPage == 2 ? 'bg-indigo-200 text-black' : 'bg-indigo-500 border border-indigo-200 text-indigo-200'">
                            2
                        </div>
                        <div :class="currentPage == 2 ? 'text-black' : 'text-indigo-200'">
                            <p class="font-bold text-[12px] ">PASO 2</p>
                            <p class="font-bold text-sm">INGRESOS</p>
                        </div>
                    </div>
                </div>

                <!-- Columna con 75% de ancho -->
                <div class="w-full sm:w-[75%] bg-white p-4 h-full">
                    <div id="page1" v-if="currentPage == 1">
                        <div class="mb-4 md:flex flex-row justify-between px-2 mt-4">
                            <div class="md:w-full">
                                <span class="font-semibold text-slate-800 text-lg underline underline-offset-2">
                                    Información general.
                                </span>
                            </div>
                        </div>

                        <div class="mb-2 md:flex flex-row justify-items-start px-2">
                            <div class="mb-4 md:mr-2 md:mb-0 basis-full">
                                <TextInput id="name-client" v-model="income_receipt.client" type="text"
                                    placeholder="Nombre o Razón Social"
                                    @update:modelValue="handleValidation('client', { limit: 145 })">
                                    <LabelToInput icon="standard" forLabel="name-client" />
                                </TextInput>
                                <InputError class="mt-2" :message="errors.client" />
                            </div>
                        </div>

                        <div class="mb-2 md:flex flex-row justify-items-start px-2">
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                                <label class="block mb-2 text-xs font-light text-gray-600">
                                    Especifico <span class="text-red-600 font-extrabold">*</span>
                                </label>
                                <div class="relative font-semibold flex h-8 w-full flex-row-reverse">
                                    <Multiselect v-model="income_receipt.budget_account_id" :options="budget_accounts"
                                        @change="getFinanceSource($event)" placeholder="Seleccione Especifico"
                                        :searchable="true" />
                                    <LabelToInput icon="list" />
                                </div>
                                <InputError class="mt-2" :message="errors.budget_account_id" />
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
                                <InputError class="mt-2" :message="errors.treasury_clerk_id" />
                            </div>
                        </div>

                        <div class="mb-2 md:flex flex-row justify-items-start px-2">
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                                <label class="block mb-2 text-xs font-light text-gray-600">
                                    Fuente Financiamiento <span class="text-red-600 font-extrabold">*</span>
                                </label>
                                <div class="relative font-semibold flex h-8 w-full flex-row-reverse">
                                    <Multiselect v-model="income_receipt.financing_source_id" :options="financing_sources"
                                        :loading="isLoadingFinancingSource" :disabled="isLoadingFinancingSource"
                                        :placeholder="isLoadingFinancingSource ? 'Cargando' : 'Seleccione Financiamiento'"
                                        @change="getIncomeConcept($event, income_receipt.budget_account_id)"
                                        :searchable="true" />
                                    <LabelToInput icon="list" />
                                </div>
                                <InputError class="mt-2" :message="errors.financing_source_id" />
                            </div>
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/2">
                                <TextInput id="number" v-model="income_receipt.number" type="text"
                                    placeholder="Numero de recibo"
                                    @update:modelValue="handleValidation('number', { limit: 8 })">
                                    <LabelToInput icon="standard" forLabel="number" />
                                </TextInput>
                                <InputError
                                    v-for="(item, index2) in backend_errors.number"
                                    :key="index2" class="mt-2" :message="item" />
                                <InputError class="mt-2" :message="errors.number" />
                            </div>
                        </div>

                        <div v-if="income_receipt.budget_account_id === 252"
                            class="mb-4 md:flex flex-row justify-items-start px-2">
                            <div class="mb-4 md:mr-2 md:mb-0 basis-2/3">
                                <TextInput id="client-direccion" v-model="income_receipt.direction" type="text"
                                    placeholder="Direccion"
                                    @update:modelValue="handleValidation('direction', { limit: 250 })">
                                    <LabelToInput icon="standard" forLabel="client-direccion" />
                                </TextInput>
                                <InputError class="mt-2" :message="errors.direction" />
                            </div>
                            <div class="mb-4 md:mr-2 md:mb-0 basis-1/3">
                                <TextInput id="document-client" v-model="income_receipt.document" type="text"
                                    placeholder="Documento (DUI o NIT)"
                                    @update:modelValue="handleValidation('document', { limit: 17 })">
                                    <LabelToInput icon="standard" forLabel="document-client" />
                                </TextInput>
                                <InputError class="mt-2" :message="errors.document" />
                            </div>
                        </div>

                        <div class="mb-4 md:mr-2 md:mb-0 basis-full px-2"
                            style="border: none; background-color: transparent;">
                            <label class="block mb-2 text-xs font-light text-gray-600" for="descripcion">
                                Descripcion <span class="text-red-600 font-extrabold">*</span>
                            </label>
                            <textarea v-model="income_receipt.description" id="descripcion" name="descripcion"
                                class="resize-none w-full h-14 overflow-y-auto peer text-xs font-semibold rounded-r-md border border-slate-400 px-2 text-slate-900 transition-colors duration-300 focus:border-[#001b47] focus:outline-none"
                                @input="handleValidation('description', { limit: 290 })">
                            </textarea>
                            <InputError class="mt-2" :message="errors.description" />
                        </div>
                    </div>

                    <div id="page2" v-if="currentPage == 2">
                        <div class="mb-4 md:flex flex-row justify-between px-2 mt-4">
                            <div class="md:w-full">
                                <span class="font-semibold text-slate-800 text-lg underline underline-offset-2">
                                    Detalle del recibo.
                                </span>
                            </div>
                        </div>
                        <!-- Table -->
                        <div class="mt-0 pb-2 px-2">
                            <div class="table-header">
                                <div class="basis-[65%] text-slate-800">Concepto</div>
                                <div class="basis-[30%] text-slate-800">Monto</div>
                                <div class="basis-[5%]">
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
                            <template v-for="(row, index) in income_receipt.income_detail" :key="index">
                                <div v-if="row.deleted == false" class="table-row hover:bg-gray-200 py-2">
                                    <div class="md:mr-2 md:mb-0 basis-[65%]">
                                        <div class="relative font-semibold flex h-8 w-full flex-row-reverse">
                                            <Multiselect v-model="row.income_concept_id" :loading="isLoadingIncomeConcept"
                                                :disabled="isLoadingIncomeConcept"
                                                :placeholder="isLoadingIncomeConcept ? 'Cargando' : 'Seleccione Concepto'"
                                                :options="filteredOptions" :searchable="true"
                                                @open="openOption(row.income_concept_id)" />
                                            <LabelToInput icon="list" />
                                        </div>
                                        <InputError
                                            v-for="(item, index2) in backend_errors['income_detail.' + index + '.income_concept_id']"
                                            :key="index2" class="mt-2" :message="item" />
                                    </div>

                                    <div class="md:mr-2 md:mb-0 basis-[30%]">
                                        <TextInput id="detail-amount" v-model="row.amount" :label-input="false" type="text"
                                            placeholder="Monto"
                                            @update:modelValue="handleValidation('amount', { limit: 10, amount: true }, index)">
                                            <LabelToInput icon="money" forLabel="detail-amount" />
                                        </TextInput>
                                        <InputError
                                            v-for="(item, index2) in backend_errors['income_detail.' + index + '.amount']"
                                            :key="index2" class="mt-2" :message="item" />
                                    </div>

                                    <div class="basis-[5%]">
                                        <button @click="deleteRow(index, row.detail_id)"
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
                            </template>
                        </div>
                        <div class="mb-1 md:flex flex-row justify-items-start px-2">
                            <div class="mb-4 md:mr-0 md:mb-0 basis-[65%]">

                            </div>
                            <div class="mb-4 md:mr-2 md:mb-0 basis-[35%] text-left">
                                <span class="text-slate-800 mb-2 text-md font-semibold">
                                    Total: <span class="text-green-500 text-[15px]">${{ calculateTotal
                                    }}</span>
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Botones de navegación -->
                    <div class="flex px-2 mt-6">
                        <div class="w-1/2">
                            <button @click="$emit('cerrar-modal')"
                                class="bg-gray-700 hover:bg-gray-800 text-white font-semibold text-[12px] px-3 py-1.5 rounded mr-1.5">CERRAR</button>
                        </div>
                        <div class="w-1/2 flex justify-end">
                            <button v-if="currentPage == 2" @click="currentPage--"
                                class="hover:border-blue-700 border border-white bg-white text-[12px] font-semibold text-gray-700 px-4 py-1 rounded mr-1.5">ANTERIOR</button>
                            <button v-if="currentPage == 1" @click="goToNextPage()"
                                class="bg-blue-700 hover:bg-blue-900 text-white font-semibold text-[12px] px-3 py-1.5 rounded mr-1.5">SIGUIENTE</button>
                            <div v-if="currentPage == 2">
                                <button v-if="incomeReceiptId > 0" @click="updateReciboIngreso(income_receipt)"
                                    class="bg-orange-700 hover:bg-orange-800 text-white font-semibold text-[12px] px-3 py-1.5 rounded mr-1.5">ACTUALIZAR</button>
                                <button v-else @click="storeReciboIngreso(income_receipt)"
                                    class="bg-green-700 hover:bg-green-800 text-white font-semibold text-[12px] px-3 py-1.5 rounded mr-1.5">GUARDAR</button>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </ProcessModal>
    </div>
</template>

<script>
import { useReciboIngreso } from '@/Composables/Tesoreria/ReciboIngreso/useReciboIngreso.js';
import Modal from "@/Components-ISRI/AllModal/Modal.vue";
import ProcessModal from '@/Components-ISRI/AllModal/ProcessModal.vue'
import InputError from "@/Components/InputError.vue";
import GeneralButton from "@/Components-ISRI/ComponentsToForms/GeneralButton.vue";
import TextInput from "@/Components-ISRI/ComponentsToForms/TextInput.vue";
import LabelToInput from "@/Components-ISRI/ComponentsToForms/LabelToInput.vue";
import { ref, toRefs, onMounted, } from 'vue';

import { toast } from "vue3-toastify";
import "vue3-toastify/dist/index.css";
import axios from "axios";
import { useValidateInput } from '@/Composables/General/useValidateInput';

export default {
    components: { Modal, InputError, GeneralButton, TextInput, LabelToInput, ProcessModal },
    props: {
        incomeReceiptId: {
            type: Number,
            default: 0,
        },
        show_modal_receipt: {
            type: Boolean,
            default: false,
        },
    },
    setup(props, context) {

        const { incomeReceiptId } = toRefs(props);

        const {
            isLoadingRequest, isLoadingFinancingSource, isLoadingIncomeConcept,
            errors, income_receipt,
            backend_errors, currentPage, budget_accounts, income_concept_select,
            financing_sources, treasury_clerk, filteredOptions,
            active_details, calculateTotal,//computed
            getInfoForModalReciboIngreso, storeReciboIngreso, updateReciboIngreso, addRow, deleteRow, openOption,
            getFinanceSource, getIncomeConcept, goToNextPage
        } = useReciboIngreso(context);

        const {
            validateInput
        } = useValidateInput()

        const handleValidation = (input, validation, index) => {
            if (input === 'amount') {
                income_receipt.value.income_detail[index].amount = validateInput(income_receipt.value.income_detail[index].amount, validation)
            } else {
                income_receipt.value[input] = validateInput(income_receipt.value[input], validation)
            }
        }

        onMounted(
            async () => {
                await getInfoForModalReciboIngreso(incomeReceiptId.value)
            }
        )

        return {
            isLoadingRequest, isLoadingFinancingSource, isLoadingIncomeConcept,
            errors, income_receipt,
            backend_errors, currentPage, budget_accounts, income_concept_select,
            financing_sources, treasury_clerk,
            active_details, calculateTotal, filteredOptions,
            storeReciboIngreso, updateReciboIngreso, addRow, deleteRow,
            getFinanceSource, openOption, getIncomeConcept, handleValidation, goToNextPage
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
}
</style>
