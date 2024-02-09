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
        <ProcessModal v-else maxWidth='3xl' :show="showModalProd" @close="$emit('cerrar-modal')" class="bg-red-400">
            <div class="flex items-center justify-between py-3 px-8 border-b border-gray-400 border-opacity-70">
                <div class="flex">
                    <span class="text-[16px] font-medium font-[Roboto] text-gray-500 text-opacity-70">Producto</span>
                    <div class="mt-[5px] text-gray-500 text-opacity-70 w-[14px] h-[14px] mx-2">
                        <icon-m :iconName="'nextSvgVector'"></icon-m>
                    </div>
                    <span class="text-[16px] font-medium text-black font-[Roboto]">{{ prodId > 0 ? 'Editar producto' : 'Crear producto' }}</span>
                </div>
                <svg class="h-6 w-6 text-gray-400 hover:text-gray-600 cursor-pointer" @click="$emit('cerrar-modal')"
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </div>

            <div class="mb-2 mt-4 md:flex flex-row justify-items-start mx-8">
                <div class="mb-4 md:mr-2 md:mb-0 basis-2/3">
                    <input-text label="Nombre producto" :withIcon="false" id="phone1" v-model="prod.name" type="text"
                        placeholder="Escriba nombre" :required="true" :addClases="'h-[35px]'"
                        :validation="{ limit: 85, upper: true }">
                    </input-text>
                    <InputError v-for="(item, index) in errors.name" :key="index" class="mt-2" :message="item" />
                </div>
                <div class="mb-4 md:mr-0 md:mb-0 basis-1/3" :class="{ 'selected-opt': prod.mUnitId > 0 }">
                    <label class="block mb-2 text-[13px] font-medium text-gray-600 dark:text-white">Unidad de medida
                        <span class="text-red-600 font-extrabold">*</span>
                    </label>
                    <div class="relative font-semibold flex h-[35px] w-full">
                        <Multiselect v-model="prod.mUnitId" :options="unitsMeasmt" :searchable="true"
                            :noOptionsText="'Lista vacía.'" placeholder="Seleccione unidad" />
                    </div>
                    <InputError v-for="(item, index) in errors.mUnitId" :key="index" class="mt-2" :message="item" />
                </div>
            </div>

            <div class="mb-2 mt-4 md:flex flex-row justify-items-start mx-8">
                <div class="mb-4 md:mr-2 md:mb-0 basis-1/3">
                    <input-text label="Precio referencia" :withIcon="false" id="price" v-model="prod.price" type="text"
                        placeholder="Escriba precio" :required="true" :addClases="'h-[35px]'" :dSign="true"
                        :validation="{ limit: 10, amount: true }">
                    </input-text>
                    <InputError v-for="(item, index) in errors.price" :key="index" class="mt-2" :message="item" />
                </div>
                <div class="mb-4 md:mr-2 md:mb-0 basis-1/3 justify-center text-center">
                    <label class="block mb-2 text-[13px] font-medium text-gray-600 dark:text-white">Perecedero
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
                <div class="mb-4 md:mr-0 md:mb-0 basis-1/3 justify-center text-center">
                    <label class="block mb-2 text-[13px] font-medium text-gray-600 dark:text-white">Cuadro bienes y
                        servicios
                        <span class="text-red-600 font-extrabold">*</span>
                    </label>
                    <label for="checbox3" class="text-sm font-semibold text-gray-600 ml-4 mr-1">SI</label>
                    <checkbox :checked="prod.gAndS == 1 ? true : false"
                        @click="(prod.gAndS == 0 || prod.gAndS == -1) ? prod.gAndS = 1 : prod.gAndS = -1" class="mr-3"
                        id="checbox3" />
                    <label for="checbox4" class="text-sm font-semibold text-gray-600 ml-4 mr-1">NO</label>
                    <checkbox :checked="prod.gAndS == 0 ? true : false"
                        @click="(prod.gAndS == 1 || prod.gAndS == -1) ? prod.gAndS = 0 : prod.gAndS = -1" class="mr-3"
                        id="checbox4" />
                    <InputError v-for="(item, index) in errors.gAndS" :key="index" class="mt-2" :message="item" />
                </div>
            </div>

            <div class="mb-2 mt-4 md:flex flex-row justify-items-start mx-8">
                <div class="mb-4 md:mr-0 md:mb-0 basis-full" style="border: none; background-color: transparent;">
                    <label class="block mb-2 text-[13px] font-medium text-gray-600 dark:text-white">Descripción
                        <span class="text-red-600 font-extrabold">*</span>
                    </label>
                    <textarea v-model="prod.description" id="descripcion" name="descripcion"
                        placeholder="Escriba descripción del producto" :class="prod.description != '' ? 'bg-gray-200' : ''"
                        class="w-full h-14 overflow-y-auto peer placeholder-gray-400 text-xs font-semibold border border-gray-300 hover:border-gray-400 px-2 text-slate-900 transition-colors duration-300 focus:ring-blue-500 focus:border-blue-500"
                        @input="handleValidation('description', { limit: 290 })" style="border-radius: 4px;">
                    </textarea>
                    <InputError v-for="(item, index) in errors.description" :key="index" class="mt-2" :message="item" />
                </div>
            </div>

            <div class="mb-2 mt-4 md:flex flex-row justify-items-start mx-8">
                <div class="mb-4 md:mr-2 md:mb-0 basis-1/2" :class="{ 'selected-opt': prod.purchaseProcedureId > 0, }">
                    <label class="block mb-2 text-[13px] font-medium text-gray-600 dark:text-white">Proceso de compra
                        <span class="text-red-600 font-extrabold">*</span>
                    </label>
                    <div class="relative font-semibold flex h-[35px] w-full">
                        <Multiselect v-model="prod.purchaseProcedureId" :options="purchaseProcedures" :searchable="true"
                            :noOptionsText="'Lista vacía.'" placeholder="Seleccione proceso de compra" />
                    </div>
                    <InputError v-for="(item, index) in errors.purchaseProcedureId" :key="index" class="mt-2" :message="item" />
                </div>
                <div class="mb-4 md:mr-0 md:mb-0 basis-1/2" :class="{ 'selected-opt': prod.unspscId > 0, }">
                    <label class="block mb-2 text-[13px] font-medium text-gray-600 dark:text-white">Catalogo Unspsc
                        <span class="text-red-600 font-extrabold">*</span>
                    </label>
                    <div class="relative font-semibold flex h-[35px] w-full">
                        <Multiselect v-model="prod.unspscId" :options="catUnspsc" :searchable="true"
                            :loading="isLoadingUnspsc" :internal-search="false" @search-change="handleSearchChange($event)"
                            :clear-on-search="true" :filter-results="false" :resolve-on-load="true"
                            :noOptionsText="'Escriba para buscar...'" ref="selectCatUn"
                            placeholder="Seleccione catalogo unspsc" />
                    </div>
                    <InputError v-for="(item, index) in errors.unspscId" :key="index" class="mt-2" :message="item" />
                </div>
            </div>

            <div class="mb-2 mt-4 md:flex flex-row justify-items-start mx-8">
                <div class="mb-4 md:mr-2 md:mb-0 basis-1/2 pr-1" :class="{ 'selected-opt': prod.budgetAccountId > 0, }">
                    <label class="block mb-2 text-[13px] font-medium text-gray-600 dark:text-white">Especifico
                        <span class="text-red-600 font-extrabold">*</span>
                    </label>
                    <div class="relative font-semibold flex h-[35px] w-full">
                        <Multiselect v-model="prod.budgetAccountId" :options="budgetAccounts" :searchable="true"
                            :noOptionsText="'Lista vacía.'" placeholder="Seleccione especifico" />
                    </div>
                    <InputError v-for="(item, index) in errors.budgetAccountId" :key="index" class="mt-2" :message="item" />
                </div>
            </div>

            <div class="md:flex my-6 flex-row justify-end mx-8">
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
import { useProducto } from '@/Composables/UCP/Producto/useProducto.js';
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
            budgetAccounts, unitsMeasmt,
            asyncFindUnspsc, getInfoForModalProd, storeProduct, updateProduct
        } = useProducto(context);

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
            budgetAccounts, unitsMeasmt,
            handleSearchChange, handleValidation, storeProduct, updateProduct
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