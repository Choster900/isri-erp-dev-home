<template>
    <div class="m-1.5 p-10">
        <div v-if="isLoadingRequest"
            class="fixed top-0 left-0 right-0 bottom-0 flex items-center justify-center bg-gray-900 opacity-75 z-50">
            <div class="flex items-center justify-center my-4">
                <img src="../../../../img/loader-spinner.gif" alt="" class="w-8 h-8">
                <h1 class="ml-4 font-medium text-xl text-white font-[Roboto]">Procesando...</h1>
            </div>
        </div>
        <ProcessModal v-else :maxWidth="(recepId <= 0 && !startRec) ? 'xl' : '2xl'" :show="showModalKardex"
            @close="$emit('cerrar-modal')" >

            <div class="flex items-center justify-between py-3 px-4 border-b border-gray-400 border-opacity-70">
                <div class="flex">
                    <span class="text-[16px] font-medium font-[Roboto] text-gray-500 text-opacity-70">Recepcion</span>
                    <div class="mt-[5px] text-gray-500 text-opacity-70 w-[14px] h-[14px] mx-2">
                        <icon-m :iconName="'nextSvgVector'"></icon-m>
                    </div>
                    <span class="text-[16px] font-medium text-black font-[Roboto]">Enviar Kardex</span>
                </div>
                <svg class="h-6 w-6 text-gray-400 hover:text-gray-600 cursor-pointer" @click="$emit('cerrar-modal')"
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </div>

            <div class="my-2 md:flex flex-row justify-between mx-8">
                <div class="md:w-full">
                    <span class="font-semibold text-slate-800 text-lg underline underline-offset-2">
                        Informacion complementaria
                    </span>
                </div>
            </div>

            <div class="mb-2 mt-4 md:flex flex-row justify-items-start mx-8">
                <div class="mb-4 md:mr-0 md:mb-0 basis-2/3" :class="{ 'selected-opt': infoToSend.conctManagerId > 0 }">
                    <label class="block mb-2 text-[13px] font-medium text-gray-600 dark:text-white">Administrador de
                        contrato
                        <span class="text-red-600 font-extrabold">*</span>
                    </label>
                    <div class="relative font-semibold flex h-[35px] w-full">
                        <Multiselect v-model="infoToSend.conctManagerId" :options="empOptions" :searchable="true"
                            :noOptionsText="'Lista vacía.'" placeholder="Seleccione administrador" />
                    </div>
                    <InputError v-for="(item, index) in errors.conctManagerId" :key="index" class="mt-2" :message="item" />
                </div>
                <div class="mb-4 md:mr-2 md:mb-0 basis-1/3 justify-center text-center">
                    <label class="block mb-3 text-[13px] font-medium text-gray-600 dark:text-white">Incumple recepcion
                        <span class="text-red-600 font-extrabold">*</span>
                    </label>
                    <label for="checbox1" class="text-sm font-semibold text-gray-600 ml-4 mr-1">SI</label>
                    <checkbox :checked="infoToSend.nonCompliant == 1 ? true : false"
                        @click="(infoToSend.nonCompliant == 0 || infoToSend.nonCompliant == -1) ? infoToSend.nonCompliant = 1 : infoToSend.nonCompliant = -1"
                        class="mr-3" id="checbox1" />
                    <label for="checbox2" class="text-sm font-semibold text-gray-600 ml-4 mr-1">NO</label>
                    <checkbox :checked="infoToSend.nonCompliant == 0 ? true : false"
                        @click="(infoToSend.nonCompliant == 1 || infoToSend.nonCompliant == -1) ? infoToSend.nonCompliant = 0 : infoToSend.nonCompliant = -1"
                        class="mr-3" id="checbox2" />
                    <InputError v-for="(item, index) in errors.nonCompliant" :key="index" class="mt-2" :message="item" />
                </div>
            </div>

            <div v-if="infoToSend.nonCompliant == 1" class="mb-2 mt-4 md:flex flex-row justify-items-start mx-8">
                <div class="mb-4 md:mr-0 md:mb-0 basis-full" style="border: none; background-color: transparent;">
                    <label class="block mb-2 text-[13px] font-medium text-gray-600 dark:text-white">Observacion sobre
                        incumplimiento
                        <span class="text-red-600 font-extrabold">*</span>
                    </label>
                    <textarea v-model="infoToSend.observation" id="descripcion" name="descripcion"
                        placeholder="Escriba observacion sobre incumplimiento"
                        :class="infoToSend.observation != '' ? 'bg-gray-200' : ''"
                        class="w-full h-16 overflow-y-auto peer placeholder-gray-400 text-xs font-semibold border border-gray-300 hover:border-gray-400 px-2 text-slate-900 transition-colors duration-300 focus:ring-blue-500 focus:border-blue-500"
                        @input="handleValidation('observation', { limit: 290 })" style="border-radius: 4px;">
                    </textarea>
                    <InputError v-for="(item, index) in errors.observation" :key="index" class="mt-2" :message="item" />
                </div>
            </div>

            <div class="mb-2 mt-2 md:flex flex-row justify-items-start mx-8">
                <div class="mb-4 md:mr-0 md:mb-0 basis-full">
                    <input-text label="Representante proveedor" :withIcon="false" id="price" v-model="infoToSend.suppRep" type="text"
                        placeholder="Escriba nombre representante" :required="true" :addClases="'h-[35px]'" :dSign="true"
                        :validation="{ limit: 100, upper : true }">
                    </input-text>
                    <InputError v-for="(item, index) in errors.suppRep" :key="index" class="mt-2" :message="item" />
                </div>
            </div>

            <div class="md:flex flex md:items-center py-4 sticky flex-row justify-center mx-8">
                <button type="button" @click="$emit('cerrar-modal')"
                    class="mr-2 text-gray-600 hover:text-white border border-gray-600 hover:bg-gray-700 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-[12px] px-2.5 py-1.5 text-center dark:border-gray-500 dark:text-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-800">CANCELAR</button>
                <button type="button"
                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-3 py-1 text-center inline-flex items-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                    @click="sendReception(infoToSend)">
                    Enviar Kardex
                    <svg class="w-[24px] h-[24px] ml-2" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                        <g id="SVGRepo_iconCarrier">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M5.00003 14.9968C5.41424 14.9968 5.75003 15.3326 5.75003 15.7468V18C5.75003 18.1381 5.86196 18.25 6.00003 18.25H18C18.1381 18.25 18.25 18.1381 18.25 18V15.7468C18.25 15.3326 18.5858 14.9968 19 14.9968C19.4142 14.9968 19.75 15.3326 19.75 15.7468V18C19.75 18.9665 18.9665 19.75 18 19.75H6.00003C5.03353 19.75 4.25003 18.9665 4.25003 18V15.7468C4.25003 15.3326 4.58582 14.9968 5.00003 14.9968Z"
                                fill="currentColor"></path>
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M12.2023 5.58088C12.6165 5.58088 12.9523 5.91667 12.9523 6.33088V14.4165C12.9523 14.8307 12.6165 15.1665 12.2023 15.1665C11.7881 15.1665 11.4523 14.8307 11.4523 14.4165V6.33088C11.4523 5.91667 11.7881 5.58088 12.2023 5.58088Z"
                                fill="currentColor"></path>
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M11.678 4.46356C11.9694 4.17885 12.4348 4.17885 12.7262 4.46356L16.0701 7.73038C16.3663 8.01984 16.3719 8.49468 16.0824 8.79097C15.7929 9.08725 15.3181 9.09278 15.0218 8.80332L12.2021 6.04855L9.38239 8.80332C9.08611 9.09278 8.61127 9.08725 8.32181 8.79097C8.03234 8.49468 8.03787 8.01984 8.33416 7.73038L11.678 4.46356Z"
                                fill="currentColor"></path>
                        </g>
                    </svg>
                </button>
            </div>

        </ProcessModal>
    </div>
</template>

<script>
import { useEnviarKardex } from '@/Composables/Almacen/Recepcion/useEnviarKardex.js';
import InputError from "@/Components/InputError.vue";
import ProcessModal from '@/Components-ISRI/AllModal/ProcessModal.vue'
import InputText from "@/Components-ISRI/ComponentsToForms/InputText.vue";
import IconM from "@/Components-ISRI/ComponentsToForms/IconM.vue";
import DateTimePickerM from "@/Components-ISRI/ComponentsToForms/DateTimePickerM.vue";
import { useValidateInput } from '@/Composables/General/useValidateInput';

import { toRefs, onMounted } from 'vue';

export default {
    emits: ["cerrar-modal", "get-table"],
    components: { ProcessModal, InputError, InputText, IconM, DateTimePickerM },
    props: {
        showModalKardex: {
            type: Boolean,
            default: false,
        },
        recepId: {
            type: Number,
            default: 0
        },
    },
    setup(props, context) {

        const { recepId } = toRefs(props)

        const {
            isLoadingRequest, recInfo, errors, empOptions, infoToSend,
            getInfoForModalSendKardex, sendReception
        } = useEnviarKardex(context);

        const {
            validateInput
        } = useValidateInput()

        const handleValidation = (input, validation) => {
            infoToSend.value[input] = validateInput(infoToSend.value[input], validation)
        }

        onMounted(
            async () => {
                await getInfoForModalSendKardex(recepId.value)
            }
        )

        return {
            isLoadingRequest, recInfo, errors, empOptions, infoToSend,
            getInfoForModalSendKardex, handleValidation, sendReception
        }
    }
}
</script>

<style>
.dp__input_wrap {
    height: 35px;
}

.dp__theme_light {
    /* I've edited this */
    --dp-primary-color: rgba(0, 28, 72, 0.8);
    --dp-cell-size: 25px;
    --dp-font-size: 0.8rem;
}

.selected-opt .multiselect {
    background: var(--ms-bg, #E5E7EB);
}

.selected-opt .multiselect-wrapper input {
    background-color: #E5E7EB;
}
</style>