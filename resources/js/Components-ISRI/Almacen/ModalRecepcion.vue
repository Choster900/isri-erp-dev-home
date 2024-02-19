<template>
    <div class="m-1.5 p-10">
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
        <ProcessModal v-else :maxWidth="recepId > 0 ? '3xl' : 'lg'" :show="showModalRecep" @close="$emit('cerrar-modal')"
            :addClases="' bg-gray-200'">

            <div class="flex items-center justify-between py-3 px-4 border-b border-gray-400 border-opacity-70">
                <div class="flex">
                    <span class="text-[16px] font-medium font-[Roboto] text-gray-500 text-opacity-70">Recepcion</span>
                    <div class="mt-[5px] text-gray-500 text-opacity-70 w-[14px] h-[14px] mx-2">
                        <icon-m :iconName="'nextSvgVector'"></icon-m>
                    </div>
                    <span class="text-[16px] font-medium text-black font-[Roboto]">{{ recepId > 0 ? 'Editar recepcion' :
                        'Crear recepcion' }}</span>
                </div>
                <svg class="h-6 w-6 text-gray-400 hover:text-gray-600 cursor-pointer" @click="$emit('cerrar-modal')"
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </div>
            <div v-if="recepId <= 0" class="w-full">
                <div>
                    <p class="text-center mt-4 font-[Roboto] text-slate-800 font-semibold">SELECCIONE TIPO DOCUMENTO</p>
                    <div class="w-full flex justify-center">
                        <div class="w-[50%] mt-4 mb-10 flex items-center justify-center">
                            <div
                                class="mx-auto w-[70%] h-[125px] border rounded-md shadow-lg border-gray-300 flex flex-col items-center justify-center
                            hover:border-blue-700 cursor-pointer hover:shadow-blue-200 hover:text-blue-700 transition duration-300 ease-in-out"
                            @click="docSelected = 1">
                                <div class="mb-2">
                                    <p class="font-[Roboto]">CONTRATOS</p>
                                </div>
                                <div>
                                    <p class="font-[Roboto]">{{ contrato.length }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="w-[50%] mt-4 mb-10 flex items-center justify-center">
                            <div
                                class="mx-auto w-[70%] h-[125px] border rounded-md shadow-lg border-gray-300 flex flex-col items-center justify-center
                            hover:border-blue-700 cursor-pointer hover:shadow-blue-200 hover:text-blue-700 transition duration-300 ease-in-out"
                            @click="docSelected=2">
                                <div class="mb-2">
                                    <p class="font-[Roboto]">ORDENES DE COMPRA</p>
                                </div>
                                <div>
                                    <p class="font-[Roboto]">{{ ordenC.length }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <pre>{{ filteredDoc }}</pre>
            </div>



            <!-- <div class="md:flex my-6 flex-row justify-end mx-8">
                <button type="button" @click="$emit('cerrar-modal')"
                    class="mr-2 text-gray-600 hover:text-white border border-gray-600 hover:bg-gray-700 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-[12px] px-2.5 py-1.5 text-center mb-2 dark:border-gray-500 dark:text-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-800">CANCELAR</button>
                <button v-if="prodId > 0" @click="updateProduct(prod)"
                    class="bg-orange-700 hover:bg-orange-800 text-white font-medium text-[12px] px-2.5 py-1.5 rounded-lg mr-1.5 mb-2">ACTUALIZAR</button>
                <button v-else @click="storeProduct(prod)"
                    class="bg-green-700 hover:bg-green-800 text-white font-medium text-[12px] px-2.5 py-1.5 rounded-lg mr-1.5 mb-2">GUARDAR</button>
            </div> -->


        </ProcessModal>
    </div>
</template>

<script>
import { useRecepcion } from '@/Composables/Almacen/Recepcion/useRecepcion.js';
import InputError from "@/Components/InputError.vue";
import ProcessModal from '@/Components-ISRI/AllModal/ProcessModal.vue'
import InputText from "@/Components-ISRI/ComponentsToForms/InputText.vue";
import IconM from "@/Components-ISRI/ComponentsToForms/IconM.vue";
// import DateTimePickerM from "@/Components-ISRI/ComponentsToForms/DateTimePickerM.vue";
// import TimePickerM from "@/Components-ISRI/ComponentsToForms/TimePickerM.vue";
import { useValidateInput } from '@/Composables/General/useValidateInput';

import { toRefs, onMounted, ref, watch } from 'vue';

export default {
    emits: ["cerrar-modal", "get-table"],
    components: { ProcessModal, InputError, InputText, IconM },
    props: {
        showModalRecep: {
            type: Boolean,
            default: false,
        },
        recepId: {
            type: Number,
            default: 0
        }
    },
    setup(props, context) {

        const { recepId } = toRefs(props)

        const {
            isLoadingRequest, reception, errors, purchaseProcedures, catUnspsc,
            budgetAccounts, unitsMeasmt, products, documents, ordenC, contrato, docSelected,
            filteredDoc,
            getInfoForModalRecep
        } = useRecepcion(context);

        const {
            validateInput
        } = useValidateInput()

        const handleValidation = (input, validation) => {
            prod.value[input] = validateInput(prod.value[input], validation)
        }

        const handleSearchChange = async (query) => {
            await asyncFindUnspsc(query);
        }

        onMounted(
            async () => {
                await getInfoForModalRecep(recepId.value)
            }
        )

        return {
            isLoadingRequest, reception, errors, purchaseProcedures, catUnspsc,
            budgetAccounts, unitsMeasmt, products, documents, ordenC, contrato, docSelected,
            filteredDoc,
            handleSearchChange, handleValidation
        }
    }
}
</script>

<style>
.table-row {
    display: flex;
    justify-content: space-between;
}

.selected-opt .multiselect {
    background: var(--ms-bg, #E5E7EB);
}

.selected-opt .multiselect-wrapper input {
    background-color: #E5E7EB;
}


/* .select-err .multiselect-wrapper input {
    background-color: #FECACA;
} */
</style>