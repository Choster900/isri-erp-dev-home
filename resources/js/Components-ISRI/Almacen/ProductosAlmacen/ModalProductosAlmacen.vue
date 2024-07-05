<template>
    <div class="m-1.5 p-10">
        <div v-if="isLoadingRequest"
            class="fixed top-0 left-0 right-0 bottom-0 flex items-center justify-center bg-gray-900 opacity-75 z-50">
            <div class="flex items-center justify-center my-4">
                <img src="../../../../img/loader-spinner.gif" alt="" class="w-8 h-8" />
                <h1 class="ml-4 font-medium text-xl text-white font-[Roboto]">
                    Procesando...
                </h1>
            </div>
        </div>
        <ProcessModal v-else maxWidth='3xl' :show="showModalProd" @close="$emit('cerrar-modal')" class="bg-red-400">
            <div class="flex items-center justify-between py-3 px-8 border-b border-gray-400 border-opacity-70">
                <div class="flex">
                    <span class="text-[16px] font-medium font-[Roboto] text-gray-500 text-opacity-70">Producto</span>
                    <div class="mt-[5px] text-gray-500 text-opacity-70 w-[14px] h-[14px] mx-2">
                        <icon-m :iconName="'nextSvgVector'"></icon-m>
                    </div>
                    <span class="text-[16px] font-medium text-black font-[Roboto]">{{ prodId > 0 ? 'Editar producto' :
                        'Crear producto' }}</span>
                </div>
                <svg class="h-6 w-6 text-gray-400 hover:text-gray-600 cursor-pointer" @click="$emit('cerrar-modal')"
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </div>

            <div class="mb-2 mt-4 md:flex flex-row justify-items-start mx-8">
                <div class="mb-4 md:mr-2 md:mb-0 basis-1/2" :class="{ 'selected-opt': prod.catPercId > 0, }">
                    <label class="block mb-2 text-[13px] font-medium text-gray-600 ">Código perc
                        <span class="text-red-600 font-extrabold">*</span>
                    </label>
                    <div class="relative font-semibold flex h-[35px] w-full">
                        <Multiselect v-model="prod.catPercId" :options="catPerc" :searchable="true"
                            :noOptionsText="'Lista vacía.'" placeholder="Seleccione perc" />
                    </div>
                    <InputError v-for="(item, index) in errors.catPercId" :key="index" class="mt-2" :message="item" />
                </div>

                <div class="mb-4 md:mr-2 md:mb-0 basis-1/2" :class="{ 'selected-opt': prod.catPercId > 0, }">
                    <label class="block mb-2 text-[13px] font-medium text-gray-600 ">Sub Almacen
                        <span class="text-red-600 font-extrabold">*</span>
                    </label>
                    <div class="relative font-semibold flex h-[35px] w-full">
                        <Multiselect v-model="prod.subWarehouseId" :options="subWarehouses" :searchable="true"
                            :noOptionsText="'Lista vacía.'" placeholder="Seleccione Sub Almacen" />
                    </div>
                    <InputError v-for="(item, index) in errors.subWarehouseId" :key="index" class="mt-2"
                        :message="item" />
                </div>
            </div>

            <div class="mb-2 mt-4 md:flex flex-row justify-items-start mx-8">
                <div class="mb-4 md:mr-2 md:mb-0 basis-1/2 justify-center text-center">
                    <label class="block mb-2 text-[13px] font-medium text-gray-600 ">Perecedero
                        <span class="text-red-600 font-extrabold">*</span>
                    </label>
                    <label for="checbox1" class="text-sm font-semibold text-gray-600 ml-4 mr-1">SI</label>
                    <checkbox :checked="prod.perishable == 1 ? true : false"
                        @click="(prod.perishable == 0 || prod.perishable == -1) ? prod.perishable = 1 : prod.perishable = -1"
                        class="mr-3" id="checbox1" />
                    <label for="checbox2" class="text-sm font-semibold text-gray-600 ml-4 mr-1">NO</label>
                    <checkbox :checked="prod.perishable == 0 ? true : false"
                        @click="(prod.perishable == 1 || prod.perishable == -1) ? prod.perishable = 0 : prod.perishable = -1"
                        class="mr-3" id="checbox2" />
                    <InputError v-for="(item, index) in errors.perishable" :key="index" class="mt-2" :message="item" />
                </div>
            </div>

            <div class="md:flex my-6 flex-row justify-center mx-8">
                <button type="button" @click="$emit('cerrar-modal')"
                    class="mr-2 text-gray-600 hover:text-white border border-gray-600 hover:bg-gray-700 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-[12px] px-2.5 py-1.5 text-center mb-2 dark:border-gray-500 dark:text-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-800">CANCELAR</button>
                <button v-if="prodId > 0" @click="updateProduct(prod)"
                    class="bg-orange-700 hover:bg-orange-800 text-white font-medium text-[12px] px-2.5 py-1.5 rounded-lg mr-1.5 mb-2">ACTUALIZAR</button>
                <button v-else @click="storeProduct(prod)"
                    class="bg-green-700 hover:bg-green-800 text-white font-medium text-[12px] px-2.5 py-1.5 rounded-lg mr-1.5 mb-2">GUARDAR</button>
            </div>


        </ProcessModal>
    </div>
</template>

<script>
import { useProductoAlmacen } from '@/Composables/Almacen/ProductoAlmacen/useProductoAlmacen.js';
import InputError from "@/Components/InputError.vue";
import ProcessModal from '@/Components-ISRI/AllModal/ProcessModal.vue'
import InputText from "@/Components-ISRI/ComponentsToForms/InputText.vue";
import IconM from "@/Components-ISRI/ComponentsToForms/IconM.vue";
import DateTimePickerM from "@/Components-ISRI/ComponentsToForms/DateTimePickerM.vue";
import TimePickerM from "@/Components-ISRI/ComponentsToForms/TimePickerM.vue";
import { useValidateInput } from '@/Composables/General/useValidateInput';

import { toRefs, onMounted, ref, watch } from 'vue';

export default {
    emits: ["cerrar-modal", "get-table"],
    components: { ProcessModal, InputError, InputText, IconM, DateTimePickerM, TimePickerM },
    props: {
        showModalProd: {
            type: Boolean,
            default: false,
        },
        prodId: {
            type: Number,
            default: 0
        }
    },
    setup(props, context) {

        const { prodId } = toRefs(props)

        const {
            isLoadingRequest, prod, errors, purchaseProcedures, catUnspsc, isLoadingUnspsc,
            budgetAccounts, unitsMeasmt, catPerc, subWarehouses, catNicsp, baseOption,
            asyncFindUnspsc, getInfoForModalProd, storeProduct, updateProduct, openUnspsc, selectUnspsc
        } = useProductoAlmacen(context);

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
                await getInfoForModalProd(prodId.value)
            }
        )

        return {
            isLoadingRequest, prod, errors, purchaseProcedures, catUnspsc, isLoadingUnspsc,
            budgetAccounts, unitsMeasmt, catPerc, catNicsp, baseOption, subWarehouses,
            handleSearchChange, handleValidation, storeProduct, updateProduct, openUnspsc, selectUnspsc
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