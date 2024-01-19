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
        <ProcessModal v-else maxWidth='4xl' :show="showModalSettlement" @close="$emit('cerrar-modal')">
            <div class="justify-end flex mx-4 mt-4">
                <button @click="storeFiniquitos(finiquito)"
                class="bg-cyan-600 hover:bg-cyan-800 text-white text-[14px] font-semiBold py-2 px-3 rounded-md">
                    PROGRAMAR FINIQUITOS
                </button>
            </div>
            <div class="py-7 mt-5">
                <div class="mb-4 md:flex flex-row justify-items-start mx-4">
                    <div class="mb-4 md:mr-2 md:mb-0 basis-2/3">
                        <label class="block mb-2 text-[13px] font-medium text-gray-600 dark:text-white">Notario
                            Encargado<span class="text-red-600 font-extrabold">*</span>
                        </label>
                        <div class="relative font-semibold flex h-10 w-full flex-row-reverse">
                            <Multiselect placeholder="Escriba para buscar persona" v-model="finiquito.personId" :options="persons"
                                :searchable="true" :loading="isLoadingPerson" :internal-search="false"
                                @search-change="handleSearchChange" :clear-on-search="true"
                                :noResultsText="'Sin resultados'" :noOptionsText="'Sin resultados'"
                                :classes="{
                                    optionSelected: 'text-white bg-teal-800',
                                    optionSelectedPointed: 'text-white bg-teal-800 opacity-90',}" 
                                />
                            <div class="flex items-center px-2 pointer-events-none border rounded-l-md border-gray-300">
                                <icon-m :iconName="'personInfoMulti'"></icon-m>
                            </div>
                        </div>
                        <InputError v-for="(item, index) in errors.personId" :key="index" class="mt-2"
                                :message="item" />
                    </div>
                    <div class="mb-4 md:mr-0 md:mb-0 basis-1/3">
                        <input-text label="Monto de finiquito" iconName="money" id="amount" v-model="finiquito.amount"
                            :required="true" type="text" placeholder="Monto" :validation="{ limit: 8, amount: true }">
                        </input-text>
                        <InputError v-for="(item, index) in errors.amount" :key="index" class="mt-2"
                                :message="item" />
                    </div>
                </div>
                <div class="mx-4 overflow-x-auto">
                    <div class="bg-white shadow-md rounded mt-2">
                        <div class="flex justify-between bg-teal-800 px-2 py-2 text-white text-[13px] rounded-md">
                            <div class="w-[20%] text-center">CENTRO</div>
                            <div class="w-[20%] text-center">FECHA*</div>
                            <div class="w-[20%] text-center">HORA INICIO*</div>
                            <div class="w-[20%] text-center">HORA FIN*</div>
                            <div class="w-[20%] text-center">INTERVALO*</div>
                        </div>
                        <div v-for="(center, index) in finiquito.centros" :key="index"
                        :class="checkIfHasError(center.id) ? 'bg-red-200 hover:bg-red-300' : ''"
                            class="flex justify-between border-b border-gray-100 text-[14px] px-2 py-2 hover:bg-gray-300">
                            <div class="w-[20%] text-center overflow-wrap flex items-center justify-center">
                                {{
                                    `${center.center + '(' + center.empleados.length + ')'}`
                                }}
                            </div>
                            <div class="w-[20%] text-center overflow-wrap flex items-center justify-center">
                                <div class="mb-5 md:mr-2 md:mb-0 basis-full justify-start text-left">
                                    <date-time-picker-m v-model="center.date" />
                                    <!-- <InputError v-for="(item, index) in errors.startDate" :key="index" class="mt-2"
                                        :message="item" /> -->
                                </div>
                            </div>
                            <div class="w-[20%] text-center overflow-wrap flex items-center justify-center">
                                <div class="md:mr-2 md:mb-0 basis-full">
                                    <time-picker-m :height="200" v-model="center.startTime" :placeholder="'Hora inicio'" />
                                    <!-- <InputError v-for="(item, index) in errors.amount" :key="index" class="mt-2"
                                :message="item" /> -->
                                </div>
                            </div>
                            <div class="w-[20%] text-center overflow-wrap flex items-center justify-center">
                                <div class="md:mr-2 md:mb-0 basis-full">
                                    <time-picker-m :height="200" v-model="center.endTime" :placeholder="'Hora fin'" />
                                    <!-- <InputError v-for="(item, index) in errors.amount" :key="index" class="mt-2"
                                :message="item" /> -->
                                </div>
                            </div>
                            <div class="w-[20%] text-center overflow-wrap flex items-center justify-center">
                                <div class="md:mr-2 md:mb-0 basis-full">
                                    <input-text iconName="number" id="amount" v-model="center.interval" type="text"
                                        placeholder="Minutos" :validation="{ limit: 2, number: true }"
                                        :addClasses="'text-gray-500'">
                                    </input-text>
                                    <!-- <InputError v-for="(item, index) in errors.amount" :key="index" class="mt-2"
                                :message="item" /> -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </ProcessModal>
    </div>
</template>

<script>
import { useFiniquitos } from '@/Composables/Juridico/Finiquito/useFiniquitos.js';
import InputError from "@/Components/InputError.vue";
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
import ProcessModal from '@/Components-ISRI/AllModal/ProcessModal.vue'
import InputText from "@/Components-ISRI/ComponentsToForms/InputText.vue";
import IconM from "@/Components-ISRI/ComponentsToForms/IconM.vue";
import DateTimePickerM from "@/Components-ISRI/ComponentsToForms/DateTimePickerM.vue";
import TimePickerM from "@/Components-ISRI/ComponentsToForms/TimePickerM.vue";

import { ref, toRefs, onMounted, } from 'vue';

export default {
    emits: ["cerrar-modal", "get-table"],
    components: { ProcessModal, InputError, InputText, IconM, DateTimePickerM, TimePickerM },
    props: {
        showModalSettlement: {
            type: Boolean,
            default: false,
        },
    },
    setup(props, context) {
        const {
            isLoadingRequest, finiquito, 
            isLoadingPerson, persons, errors,
            getInfoForModalFiniquitos, asyncFindPerson, storeFiniquitos,
            checkIfHasError
        } = useFiniquitos(context);

        const handleSearchChange = async (query) => {
            if (query != '') {
                await asyncFindPerson(query);
            }
        }

        onMounted(
            async () => {
                await getInfoForModalFiniquitos()
            }
        )

        return {
            isLoadingRequest, finiquito, isLoadingPerson, persons, errors,
            getInfoForModalFiniquitos, handleSearchChange, storeFiniquitos, checkIfHasError
        }
    }
}
</script>

<style>
/* .multiselect-option.is-selected {
    background: var(--ms-option-bg-selected, #81146a);
    color: var(--ms-option-color-selected, #fff);
} */
</style>