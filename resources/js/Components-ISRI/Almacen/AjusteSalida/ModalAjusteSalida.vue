<template>
    <div class="m-1.5 p-10">
        <div v-if="isLoadingRequest"
            class="fixed top-0 left-0 right-0 bottom-0 flex items-center justify-center bg-gray-900 opacity-75 z-50">
            <div class="flex items-center justify-center my-4">
                <img src="../../../../img/loader-spinner.gif" alt="" class="w-8 h-8">
                <h1 class="ml-4 font-medium text-xl text-white font-[Roboto]">Procesando...</h1>
            </div>
        </div>
        <ProcessModal v-else :maxWidth="'5xl'" :show="showModalOutgoingAdjustment" @close="$emit('cerrar-modal')">

            <div class="flex items-center justify-between py-3 px-4 border-b border-gray-400 border-opacity-70">
                <div class="flex">
                    <span class="text-[16px] font-medium font-[Roboto] text-gray-500 text-opacity-70">Ajuste de
                        salida</span>
                    <div class="mt-[5px] text-gray-500 text-opacity-70 w-[14px] h-[14px] mx-2">
                        <icon-m :iconName="'nextSvgVector'"></icon-m>
                    </div>
                    <span class="text-[16px] font-medium text-black font-[Roboto]">{{ objId > 0 ? 'Editar ajuste' :
            'Crear ajuste' }}</span>
                </div>
                <svg class="h-6 w-6 text-gray-400 hover:text-gray-600 cursor-pointer" @click="$emit('cerrar-modal')"
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </div>

            <div class="max-h-[450px] overflow-x-auto overflow-y-auto">






                <div class="flex items-center justify-between pt-1.5 pb-1.5 w-[96%] pl-8">
                    <div class="flex items-center">
                        <span class="text-[14px] text-slate-700 font-medium font-[Roboto] underline">PRODUCTOS</span>
                    </div>
                </div>

                <div class="w-full pl-8 max-w-full mt-4 pb-2">
                    <div class="min-w-[970px]">
                        <div class="grid grid-cols-[100%] max-w-[96%]  border border-gray-500">
                            <div class="justify-center flex w-full bg-white">
                                <p class="font-[MuseoSans] text-[12px] py-1 font-bold">LISTADO DE PRODUCTOS</p>
                            </div>
                        </div>
                    </div>


                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <h2 class="text-2xl font-bold mb-4">Búsqueda Avanzada</h2>
                        <form class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="nombre" class="block font-medium mb-2">Nombre</label>
                                <input type="text" id="nombre"
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    placeholder="Buscar por nombre">
                            </div>
                            <div>
                                <label for="categoria" class="block font-medium mb-2">Categoría</label>
                                <select id="categoria"
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Seleccionar categoría</option>
                                    <option value="categoria1">Categoría 1</option>
                                    <option value="categoria2">Categoría 2</option>
                                    <option value="categoria3">Categoría 3</option>
                                </select>
                            </div>
                            <div>
                                <label for="precio" class="block font-medium mb-2">Precio</label>
                                <div class="flex items-center">
                                    <input type="number" id="precio-min"
                                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        placeholder="Precio mínimo">
                                    <span class="mx-2">-</span>
                                    <input type="number" id="precio-max"
                                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        placeholder="Precio máximo">
                                </div>
                            </div>
                            <button type="submit"
                                class="col-span-1 md:col-span-3 bg-indigo-500 text-white font-medium py-2 px-4 rounded-md hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">Buscar</button>
                        </form>
                    </div>


                </div>


            </div>


            <div id="buttons" class="md:flex flex md:items-center my-6 sticky flex-row justify-center mx-8">
                <button type="button" @click="$emit('cerrar-modal')"
                    class="mr-2 text-gray-600 hover:text-white border border-gray-600 hover:bg-gray-700 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-[12px] px-2.5 py-1.5 text-center mb-2 dark:border-gray-500 dark:text-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-800">CANCELAR</button>
                <!-- <div class="" v-if="adjustment.status == 1">
                    <button v-if="objId > 0" @click="updateAdjustment(adjustment)"
                        class="bg-orange-700 hover:bg-orange-800 text-white font-medium text-[12px] px-2.5 py-1.5 rounded-lg mr-1.5 mb-2">ACTUALIZAR</button>
                    <button v-else @click="storeAdjustment(adjustment)"
                        class="bg-green-700 hover:bg-green-800 text-white font-medium text-[12px] px-2.5 py-1.5 rounded-lg mr-1.5 mb-2">GUARDAR</button>
                </div> -->
            </div>

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
        showModalOutgoingAdjustment: {
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
            products, brands, asyncFindProduct, totalRec, asyncProds, selectedProducts,
            getInfoForModalAdjustment, selectProd, deleteRow, addNewRow, storeAdjustment, updateAdjustment
        } = useAjusteEntrada(context);

        const {
            validateInput
        } = useValidateInput()

        const handleValidation = (input, validation) => {
            adjustment.value[input] = validateInput(adjustment.value[input], validation)
        }

        const handleSearchChange = async (query, index, prodId) => {
            await asyncFindProduct(query, index, prodId);
        }

        onMounted(
            async () => {
                await getInfoForModalAdjustment(objId.value)
            }
        )

        return {
            isLoadingRequest, errors, adjustment, reasons, centers, financingSources, lts,
            products, brands, asyncFindProduct, totalRec, asyncProds, selectedProducts,
            handleValidation, selectProd, handleSearchChange, deleteRow, addNewRow, storeAdjustment, updateAdjustment
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