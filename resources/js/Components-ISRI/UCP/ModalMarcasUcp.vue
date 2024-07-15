<template>
    <div class="m-1.5 p-10">
        <div v-if="isLoadingRequest"
            class="fixed top-0 left-0 right-0 bottom-0 flex items-center justify-center bg-gray-900 opacity-75 z-50">
            <div class="flex items-center justify-center my-4">
                <img src="../../../img/loader-spinner.gif" alt="" class="w-8 h-8" />
                <h1 class="ml-4 font-medium text-xl text-white font-[Roboto]">
                    Procesando...
                </h1>
            </div>
        </div>
        <ProcessModal v-else maxWidth='xl' :show="showModalBrand" :center="true" @close="$emit('cerrar-modal')"
            class="bg-red-400">
            <div class="flex items-center justify-between py-3 px-8 border-b border-gray-400 border-opacity-70">
                <div class="flex">
                    <span class="text-[16px] font-medium font-[Roboto] text-gray-500 text-opacity-70">Marca</span>
                    <div class="mt-[5px] text-gray-500 text-opacity-70 w-[14px] h-[14px] mx-2">
                        <IconM :iconName="'nextSvgVector'" />
                    </div>
                    <span class="text-[16px] font-medium text-black font-[Roboto]">{{ objId > 0 ? 'Editar marca' :
                        'Crear marca' }}</span>
                </div>
                <svg class="h-6 w-6 text-gray-400 hover:text-gray-600 cursor-pointer" @click="$emit('cerrar-modal')"
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </div>

            <div class="mb-2 mt-4 md:flex flex-row justify-items-start mx-8">
                <div class="mb-4 md:mr-2 md:mb-0 basis-full" :class="{ 'selected-opt': objDB.id_tipo_marca > 0, }">
                    <label class="block mb-2 text-[13px] font-medium text-gray-600 ">Tipo marca
                        <span class="text-red-600 font-extrabold">*</span>
                    </label>
                    <div class="relative font-semibold flex h-[35px] w-full">
                        <Multiselect v-model="objDB.id_tipo_marca" :options="brandTypes" :searchable="true"
                            :noOptionsText="'Lista vacía.'" placeholder="Seleccione proceso de compra" />
                    </div>
                    <InputError v-for="(item, index) in errors.id_tipo_marca" :key="index" class="mt-2"
                        :message="item" />
                </div>
            </div>

            <div class="mb-2 mt-4 md:flex flex-row justify-items-start mx-8">
                <div class="mb-4 md:mr-2 md:mb-0 basis-full">
                    <input-text label="Nombre marca" :withIcon="false" id="phone1" v-model="objDB.nombre_marca"
                        type="text" placeholder="Escriba nombre" :required="true" :addClases="'h-[35px]'"
                        :validation="{ limit: 85, upper: true }">
                    </input-text>
                    <InputError v-for="(item, index) in errors.nombre_marca" :key="index" class="mt-2"
                        :message="item" />
                </div>
            </div>

            <div class="flex flex-wrap justify-center my-6 mx-8">
                <button type="button" @click="$emit('cerrar-modal')"
                    class="mr-2 text-gray-600 hover:text-white border border-gray-600 hover:bg-gray-700 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-[12px] px-2.5 py-1.5 text-center mb-2">
                    CANCELAR
                </button>
                <button v-if="objId > 0 && objDB.estado_marca === 1" @click="updateObject(objDB)"
                    class="bg-orange-700 hover:bg-orange-800 text-white font-medium text-[12px] px-2.5 py-1.5 rounded-lg mr-1.5 mb-2">
                    ACTUALIZAR
                </button>
                <button v-else-if="objDB.estado_marca === ''" @click="storeObject(objDB)"
                    class="bg-green-700 hover:bg-green-800 text-white font-medium text-[12px] px-2.5 py-1.5 rounded-lg mr-1.5 mb-2">
                    GUARDAR
                </button>
            </div>


        </ProcessModal>
    </div>
</template>

<script>
import { useMarcaUcp } from '@/Composables/UCP/MarcaUcp/useMarcaUcp.js';
import InputError from "@/Components/InputError.vue";
import ProcessModal from '@/Components-ISRI/AllModal/ProcessModal.vue'
import InputText from "@/Components-ISRI/ComponentsToForms/InputText.vue";
import IconM from "@/Components-ISRI/ComponentsToForms/IconM.vue";
import { useValidateInput } from '@/Composables/General/useValidateInput';

import { toRefs, onMounted } from 'vue';

export default {
    emits: ["cerrar-modal", "get-table"],
    components: { ProcessModal, InputError, InputText, IconM },
    props: {
        showModalBrand: {
            type: Boolean,
            default: false,
        },
        objId: {
            type: Number,
            default: 0
        }
    },
    setup(props, context) {

        const { objId } = toRefs(props)

        const {
            isLoadingRequest, objDB, errors, brandTypes,
            showTooltipCAT, showTooltipUAT,
            getInfoForModalBrandUcp, storeObject, updateObject
        } = useMarcaUcp(context);

        const {
            validateInput
        } = useValidateInput()

        const handleValidation = (input, validation) => {
            objDB.value[input] = validateInput(objDB.value[input], validation)
        }

        onMounted(
            async () => {
                await getInfoForModalBrandUcp(objId.value)
            }
        )

        return {
            isLoadingRequest, objDB, errors, brandTypes,
            showTooltipCAT, showTooltipUAT,
            handleValidation, storeObject, updateObject
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