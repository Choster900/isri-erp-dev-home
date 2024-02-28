<template>
    <div class="m-1.5 p-10">
        <div v-if="isLoadingRequest"
            class="fixed top-0 left-0 right-0 bottom-0 flex items-center justify-center bg-gray-900 opacity-75 z-50">
            <div class="flex items-center justify-center my-4">
                <img src="../../../../img/loader-spinner.gif" alt="" class="w-8 h-8">
                <h1 class="ml-4 font-medium text-xl text-white font-[Roboto]">Procesando...</h1>
            </div>
        </div>
        <ProcessModal v-else :maxWidth="(recepId <= 0 && !startRec) ? 'xl' : '3xl'" :show="showModalKardex"
            @close="$emit('cerrar-modal')" :addClases="' bg-gray-100'">

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
            <pre>
                {{ empOptions }}
            </pre>
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
            isLoadingRequest, recInfo, errors, empOptions,
            getInfoForModalSendKardex
        } = useEnviarKardex(context);

        onMounted(
            async () => {
                await getInfoForModalSendKardex(recepId.value)
            }
        )

        return {
            isLoadingRequest, recInfo, errors, empOptions,
            getInfoForModalSendKardex
        }
    }
}
</script>

<style>
.input-border-bottom {
    border-width: 0 0 1px 0;
    /* Establece el ancho del borde solo en la parte inferior */
    border-color: #6B7280;
    /* Color del borde */
    border-style: solid;
    /* Establece el estilo del borde como una línea sólida */
    outline: none;
    /* Elimina el contorno del input */
}

.dp__input_wrap {
    height: 35px;
}

.dp__theme_light {
    /* I've edited this */
    --dp-primary-color: rgba(0, 28, 72, 0.8);
    --dp-cell-size: 25px;
    --dp-font-size: 0.8rem;
}
</style>