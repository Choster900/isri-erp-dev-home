<template>
    <div class="m-1.5 p-10">
        <div v-if="isLoadingRequest"
            class="fixed top-0 left-0 right-0 bottom-0 flex items-center justify-center bg-gray-900 opacity-75 z-50">
            <div class="flex items-center justify-center my-4">
                <img src="../../../../img/loader-spinner.gif" alt="" class="w-8 h-8">
                <h1 class="ml-4 font-medium text-xl text-white font-[Roboto]">Procesando...</h1>
            </div>
        </div>
        <ProcessModal v-else :maxWidth="'4xl'" :show="showModalSurplusAdjustment" @close="$emit('cerrar-modal')">

            <div class="flex items-center justify-between py-3 px-4 border-b border-gray-400 border-opacity-70">
                <div class="flex">
                    <span class="text-[16px] font-medium font-[Roboto] text-gray-500 text-opacity-70">Ajuste entrada</span>
                    <div class="mt-[5px] text-gray-500 text-opacity-70 w-[14px] h-[14px] mx-2">
                        <icon-m :iconName="'nextSvgVector'"></icon-m>
                    </div>
                    <span class="text-[16px] font-medium text-black font-[Roboto]">Crear ajuste</span>
                </div>
                <svg class="h-6 w-6 text-gray-400 hover:text-gray-600 cursor-pointer" @click="$emit('cerrar-modal')"
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </div>

            <pre>{{ centers }}</pre>

            

        </ProcessModal>
    </div>
</template>

<script>
import { useAjusteEntrada } from '@/Composables/Almacen/AjusteEntrada/useAjusteEntrada.js';
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
        showModalSurplusAdjustment: {
            type: Boolean,
            default: false,
        },
        objId: {
            type: Number,
            default: 0
        },
    },
    setup(props, context) {

        const { objId } = toRefs(props)

        const {
            isLoadingRequest, errors, adjustment, reasons, centers, financingSources, lts,
            getInfoForModalAdjustment
        } = useAjusteEntrada(context);

        const {
            validateInput
        } = useValidateInput()

        const handleValidation = (input, validation) => {
            adjustment.value[input] = validateInput(adjustment.value[input], validation)
        }

        onMounted(
            async () => {
                await getInfoForModalAdjustment(objId.value)
            }
        )

        return {
            isLoadingRequest, errors, adjustment, reasons, centers, financingSources, lts,
            handleValidation
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