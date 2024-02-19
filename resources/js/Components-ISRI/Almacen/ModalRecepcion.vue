<template>
    <div class="m-1.5 p-10">
        <div v-if="isLoadingRequest"
            class="fixed top-0 left-0 right-0 bottom-0 flex items-center justify-center bg-gray-900 opacity-75 z-50">
            <div class="flex items-center justify-center my-4">
                <img src="../../../img/loader-spinner.gif" alt="" class="w-8 h-8">
                <h1 class="ml-4 font-medium text-xl text-white font-[Roboto]">Procesando...</h1>
            </div>
        </div>
        <ProcessModal v-else :maxWidth="(recepId <= 0 && !startRec) ? 'xl' : '5xl'" :show="showModalRecep"
            @close="$emit('cerrar-modal')" :addClases="' bg-gray-100'">

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
            <div v-if="recepId <= 0 && !startRec" class="w-full">
                <div>
                    <p class="text-center mt-4 font-[Roboto] text-slate-800 font-semibold">SELECCIONE TIPO DOCUMENTO</p>
                    <div class="w-full flex justify-center">
                        <div class="w-[50%] mt-4 mb-4 flex items-center justify-center">
                            <div class="mx-auto w-[70%] h-[125px] border rounded-md shadow-lg  flex flex-col items-center justify-center
                             cursor-pointer hover:shadow-blue-200 hover:text-blue-700 transition duration-300 ease-in-out bg-white"
                                :class="docSelected === 1 ? ' text-blue-600 border-blue-600 border-2' : 'border-gray-300'"
                                @click="docSelected = 1; recDocument.docId = ''; recDocument.detDocId = '';">
                                <div class="mb-2">
                                    <p class="font-[Roboto] ">CONTRATOS</p>
                                </div>
                                <div>
                                    <p class="font-[Roboto] ">Disponibles: {{ contrato.length }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="w-[50%] mt-4 mb-4 flex items-center justify-center">
                            <div class="mx-auto w-[70%] h-[125px] border rounded-md shadow-lg  flex flex-col items-center justify-center
                            cursor-pointer hover:shadow-blue-200 hover:text-blue-700 transition duration-300 ease-in-out bg-white"
                                :class="docSelected === 2 ? ' text-blue-600 border-blue-600 border-2' : 'border-gray-300'"
                                @click="docSelected = 2; recDocument.docId = ''; recDocument.detDocId = '';">
                                <div class="mb-2">
                                    <p class="font-[Roboto]">ORDENES DE COMPRA</p>
                                </div>
                                <div>
                                    <p class="font-[Roboto]">Disponibles: {{ ordenC.length }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <p class="text-center my-4 font-[Roboto] text-slate-800 font-semibold">SELECCIONE NUMERO DOCUMENTO E ITEM
                </p>
                <div class="flex flex-col md:flex-row items-center mb-4 h-10 mx-8 max-w-full">
                    <div class="w-full md:w-[40%] mb-2 md:mb-0 md:mr-2">
                        <label for="doc" class="font-[Roboto]">Numero documento:</label>
                    </div>
                    <div class="relative font-semibold flex h-[35px] w-full md:w-[60%]">
                        <Multiselect id="doc" v-model="recDocument.docId" :options="filteredDoc" :searchable="true"
                            :noOptionsText="'Lista vacía.'" placeholder="Seleccione documento"
                            @clear="recDocument.detDocId = ''" />
                    </div>
                </div>
                <div class="flex flex-col md:flex-row items-center mb-[50px] h-10 mx-8 max-w-full">
                    <div class="w-full md:w-[40%] mb-2 md:mb-0 md:mr-2">
                        <label for="det-doc" class="font-[Roboto]">Item:</label>
                    </div>
                    <div class="relative font-semibold flex h-[35px] w-full md:w-[60%]">
                        <Multiselect id="det-doc" v-model="recDocument.detDocId" :options="filteredItems" :searchable="true"
                            @clear="recDocument.detDocId = ''" :noOptionsText="'Lista vacía.'"
                            placeholder="Seleccione item" />
                    </div>
                </div>

                <div class="md:flex my-6 flex-row justify-end mx-8">
                    <button type="button" :disabled="recDocument.docId == '' || recDocument.detDocId == ''"
                        :class="(recDocument.docId == '' || recDocument.detDocId == '') ? 'cursor-not-allowed opacity-50' : ''"
                        :title="(recDocument.docId == '' || recDocument.detDocId == '') ? 'Información incompleta' : 'Iniciar proceso de recepción'"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                        @click="startReception(recepId)">
                        Iniciar recepcion
                        <svg class="rtl:rotate-180 w-3.5 h-3.5 ms-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 14 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M1 5h12m0 0L9 1m4 4L9 9" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- <div>
                <pre>{{ products }}</pre>
            </div> -->
            <div v-else>
                <transition name="fade" mode="out-in">
                    <div class="ml-8 mr-0 overflow-x-auto mt-4">
                        <div class="max-w-[97%] min-w-[540px] flex">
                            <div class="flex w-full">
                                <!-- Columna 1 -->
                                <div class="w-[23%] min-w-[100px] bg-white border border-black p-2">
                                    <img src="../../../img/isri-gob.png" alt="Logo del instituto" class="w-full">
                                </div>
                                <!-- Columna 2 -->
                                <div class="w-[77%] min-w-[440px] bg-white border-y border-r border-black p-2">
                                    <p class="font-[Bembo] text-center text-[15px] font-bold">ALMACEN CENTRAL</p>
                                    <p class="font-[Bembo] text-center text-[15px] font-bold">CONTRATO LG01/2024</p>
                                    <p class="font-[Bembo] text-center text-[15px] font-bold">ACTA DE RECEPCION 001/2024
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="max-w-[97%] min-w-[540px] flex">
                            <div class="flex w-full">
                                <div class="justify-center flex border-x w-full bg-white border-black">
                                    <p class="font-[MuseoSans] text-[12px] py-2 font-bold">LISTADO DE PRODUCTOS</p>
                                </div>
                            </div>
                        </div>
                        <div class="max-w-full flex">
                            <div class="flex w-full">
                                <!-- Cabecera de la tabla -->
                                <div class="w-[23%] min-w-[100px] border  border-gray-500 h-[30px]">
                                    <p class="text-center font-[MuseoSans] text-[12px] font-bold mt-[5px] w-full">PRODUCTO
                                    </p>
                                </div>
                                <div class="w-[23%] min-w-[100px] border-y border-r  border-gray-500 h-[30px]">
                                    <p class="text-center font-[MuseoSans] text-[12px] font-bold mt-[5px] w-full">
                                        DESCRIPCION</p>
                                </div>
                                <div class="w-[10%] min-w-[80px] border-y border-r  border-gray-500 h-[30px]">
                                    <p class="text-center font-[MuseoSans] text-[12px] font-bold mt-[5px]">DISPONIBLES</p>
                                </div>
                                <div class="w-[10%] min-w-[60px] border-y border-r  border-gray-500 h-[30px]">
                                    <p class="text-center font-[MuseoSans] text-[12px] font-bold mt-[5px]">UNIDAD</p>
                                </div>
                                <div class="w-[10%] min-w-[80px] border-y border-r  border-gray-500 h-[30px]">
                                    <p class="text-center font-[MuseoSans] text-[12px] font-bold mt-[5px]">CANTIDAD</p>
                                </div>
                                <div class="w-[12%] min-w-[60px] border-y border-r  border-gray-500 h-[30px]">
                                    <p class="text-center font-[MuseoSans] text-[12px] font-bold mt-[5px]">COSTO</p>
                                </div>
                                <div class="w-[9%] min-w-[60px] border-y border-r border-gray-500 h-[30px]">
                                    <p class="text-center font-[MuseoSans] text-[12px] font-bold mt-[5px]">TOTAL</p>
                                </div>
                                <div class="w-[3%] min-w-[10px] h-[30px]">
                                    <svg class="text-green-600 cursor-pointer" width="24" height="24" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path d="M12 6V18M6 12H18" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                                
                            </div>
                        </div>
                        <div v-for="(prod, index) in recDocument.prods" :key="index" class="max-w-full flex">
                            <div class="flex w-full">
                                <!-- Cabecera de la tabla -->
                                <div class="w-[23%] min-w-[100px] border-x border-b bg-white border-gray-500 min-h-[75px]">
                                    <Multiselect id="doc" v-model="prod.prodId" :options="products" class="h-[35px]"
                                        :searchable="true" :noOptionsText="'Lista vacía.'" placeholder="Seleccione"
                                        @input="setProdItem(index, $event)" />
                                </div>
                                <div class="w-[23%] min-w-[100px] border-r border-b bg-white border-gray-500 min-h-[75px]">
                                    <p class="font-[MuseoSans] text-sm p-1 text-gray-600">{{ prod.desc }}</p>
                                </div>
                                <div class="w-[10%] min-w-[80px] border-r border-b bg-white border-gray-500 min-h-[75px]">
                                    <p class="font-[MuseoSans] text-sm p-1 text-gray-600">{{ prod.avails }}</p>
                                </div>
                                <div class="w-[10%] min-w-[60px] border-r border-b bg-white border-gray-500 min-h-[75px]">
                                    <p class="font-[MuseoSans] text-sm p-1 text-gray-600">{{ prod.unit }}</p>
                                </div>
                                <div class="w-[10%] min-w-[80px] border-r border-b bg-white border-gray-500 min-h-[75px]">
                                    <input v-model="prod.qty" class="font-bold max-w-full font-[MuseoSans] text-sm"
                                        type="text" name="" id="" @input="updateItemTotal(index, prod.qty, prod.prodId)">
                                </div>
                                <div class="w-[12%] min-w-[60px] border-r border-b bg-white border-gray-500 min-h-[75px]">
                                    <p class="font-[MuseoSans] text-sm p-1 text-gray-600">
                                        {{ prod.cost != '' ? '$' + prod.cost : '' }}
                                    </p>
                                </div>
                                <div class="w-[9%] min-w-[60px] border-r border-b bg-white border-gray-500 min-h-[75px]">
                                    <p class="font-[MuseoSans] text-sm p-1 font-bold">
                                        {{ prod.total != '' ? '$' + prod.total : '' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="md:flex my-6 flex-row justify-end mx-8">
                            <button type="button" @click="$emit('cerrar-modal')"
                                class="mr-2 text-gray-600 hover:text-white border border-gray-600 hover:bg-gray-700 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-[12px] px-2.5 py-1.5 text-center mb-2 dark:border-gray-500 dark:text-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-800">CANCELAR</button>
                            <button
                                class="bg-orange-700 hover:bg-orange-800 text-white font-medium text-[12px] px-2.5 py-1.5 rounded-lg mr-1.5 mb-2">ACTUALIZAR</button>
                            <button
                                class="bg-green-700 hover:bg-green-800 text-white font-medium text-[12px] px-2.5 py-1.5 rounded-lg mr-1.5 mb-2">GUARDAR</button>
                        </div>
                    </div>

                </transition>
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
            isLoadingRequest, recDocument, errors, purchaseProcedures, catUnspsc,
            budgetAccounts, unitsMeasmt, products, documents, ordenC, contrato, docSelected,
            filteredDoc, filteredItems, startRec,
            getInfoForModalRecep, startReception, setProdItem, updateItemTotal
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
            isLoadingRequest, recDocument, errors, purchaseProcedures, catUnspsc,
            budgetAccounts, unitsMeasmt, products, documents, ordenC, contrato, docSelected,
            filteredDoc, filteredItems, startRec,
            handleSearchChange, handleValidation, startReception, setProdItem, updateItemTotal
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

.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.5s;
}

.fade-enter,
.fade-leave-to {
    opacity: 0;
}

/* .select-err .multiselect-wrapper input {
    background-color: #FECACA;
} */</style>