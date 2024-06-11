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
        <ProcessModal v-else maxWidth='2xl' :show="showModalSettlementEmp" @close="$emit('cerrar-modal')" :rounded="true">
            <div class="flex items-center justify-between p-4 border-b border-blue-900/30 shadow-blue-900/10 shadow-lg">
                <svg transform="rotate(270)" class="h-6 w-6 text-blue-900" fill="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                    <g id="SVGRepo_iconCarrier">
                        <path
                            d="M5,9.25A2.75,2.75,0,1,0,7.75,12,2.75,2.75,0,0,0,5,9.25Zm0,4A1.25,1.25,0,1,1,6.25,12,1.25,1.25,0,0,1,5,13.25Zm7-4A2.75,2.75,0,1,0,14.75,12,2.75,2.75,0,0,0,12,9.25Zm0,4A1.25,1.25,0,1,1,13.25,12,1.25,1.25,0,0,1,12,13.25Zm7-5.5A2.75,2.75,0,1,0,16.25,5,2.75,2.75,0,0,0,19,7.75Zm0-4A1.25,1.25,0,1,1,17.75,5,1.25,1.25,0,0,1,19,3.75Zm0,5.5A2.75,2.75,0,1,0,21.75,12,2.75,2.75,0,0,0,19,9.25Zm0,4A1.25,1.25,0,1,1,20.25,12,1.25,1.25,0,0,1,19,13.25ZM5,2.25A2.75,2.75,0,1,0,7.75,5,2.75,2.75,0,0,0,5,2.25Zm0,4A1.25,1.25,0,1,1,6.25,5,1.25,1.25,0,0,1,5,6.25Zm7,10A2.75,2.75,0,1,0,14.75,19,2.75,2.75,0,0,0,12,16.25Zm0,4A1.25,1.25,0,1,1,13.25,19,1.25,1.25,0,0,1,12,20.25Zm0-18A2.75,2.75,0,1,0,14.75,5,2.75,2.75,0,0,0,12,2.25Zm0,4A1.25,1.25,0,1,1,13.25,5,1.25,1.25,0,0,1,12,6.25Z">
                        </path>
                    </g>
                </svg>
                <span class="text-[16px] font-semibold text-blue-900">MODIFICACION DE FINIQUITO</span>
                <svg class="h-6 w-6 text-gray-400 hover:text-gray-600 cursor-pointer" @click="$emit('cerrar-modal')"
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </div>

            <div class="flex flex-col space-y-1 md:flex-row md:space-x-2 mt-5 mx-10">
                <div class="w-full md:w-[70%]">
                    <p class="text-sm text-gray-500">Empleado</p>
                    <p class="text-base font-medium text-navy-700 ">
                        {{ finiquitoEmp.empleado }}
                    </p>
                </div>
                <div class="w-full md:w-[30%]">
                    <p class="text-sm text-gray-500">Codigo</p>
                    <p class="text-base font-medium text-navy-700 ">
                        {{ finiquitoEmp.codigo }}
                    </p>
                </div>
            </div>

            <div class="mb-2 mt-4 md:flex flex-row justify-items-start mx-10">
                <div class="md:mr-2 md:mb-0 basis-1/2">
                    <date-time-picker-m v-model="finiquitoEmp.signatureDate" :placeholder="'Fecha firma'"
                        :label="'Fecha firma'" :required="true" :inputWrapHeight="'40px'" />
                    <InputError v-for="(item, index2) in errors.signatureDate" :key="index2"
                        class="mt-2" :message="item" />
                </div>
                <div class="md:mr-2 md:mb-0 basis-1/2">
                    <time-picker-m :height="200" v-model="finiquitoEmp.signatureTime" :placeholder="'Hora firma'"
                        :label="'Hora firma'" :required="true" :timeHeight="'40px'" />
                        <InputError v-for="(item, index2) in errors.signatureTime" :key="index2"
                        class="mt-2" :message="item" />
                </div>
            </div>

            <div class="mb-4 md:flex flex-row justify-items-start mx-10">
                <div class="md:mr-2 md:mb-0 basis-1/2 pr-2">
                    <input-text iconName="money" id="amount" v-model="finiquitoEmp.amount" type="text" placeholder="Monto"
                        :validation="{ limit: 8, amount: true }" :addClasses="'text-gray-500'" label="Monto"
                        :required="true">
                    </input-text>
                    <InputError v-for="(item, index2) in errors.amount" :key="index2"
                        class="mt-2" :message="item" />
                </div>
            </div>

            <div class="md:flex flex-row justify-center py-6">
                <button type="button" @click="$emit('cerrar-modal')"
                    class="mr-2 text-gray-600 hover:text-white border border-gray-600 hover:bg-gray-700 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-2.5 py-1.5 text-center mb-2 dark:border-gray-500 dark:text-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-800">CANCELAR</button>
                <button @click="updateFiniquitoEmp(finiquitoEmp)"
                    class="bg-orange-700 hover:bg-orange-800 text-white font-medium text-sm px-2.5 py-1.5 rounded-lg mr-1.5 mb-2">ACTUALIZAR</button>
            </div>


        </ProcessModal>
    </div>
</template>

<script>
import { useFiniquitoEmp } from '@/Composables/Juridico/Finiquito/useFiniquitoEmp.js';
import InputError from "@/Components/InputError.vue";
import ProcessModal from '@/Components-ISRI/AllModal/ProcessModal.vue'
import InputText from "@/Components-ISRI/ComponentsToForms/InputText.vue";
import IconM from "@/Components-ISRI/ComponentsToForms/IconM.vue";
import DateTimePickerM from "@/Components-ISRI/ComponentsToForms/DateTimePickerM.vue";
import TimePickerM from "@/Components-ISRI/ComponentsToForms/TimePickerM.vue";

import { toRefs, onMounted, } from 'vue';

export default {
    emits: ["cerrar-modal", "get-table"],
    components: { ProcessModal, InputError, InputText, IconM, DateTimePickerM, TimePickerM },
    props: {
        showModalSettlementEmp: {
            type: Boolean,
            default: false,
        },
        finiquitoId: {
            type: Number,
            default: 0
        }
    },
    setup(props, context) {

        const { finiquitoId } = toRefs(props)

        const {
            isLoadingRequest, finiquitoEmp, errors,
            getInfoForModalFiniquitoEmp, updateFiniquitoEmp,
        } = useFiniquitoEmp(context);

        onMounted(
            async () => {
                await getInfoForModalFiniquitoEmp(finiquitoId.value)
            }
        )

        return {
            isLoadingRequest, finiquitoEmp, errors,
            updateFiniquitoEmp,
        }
    }
}
</script>

<style>
.table-row {
    display: flex;
    justify-content: space-between;
}
</style>