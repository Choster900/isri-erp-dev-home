<template>
    <div class="m-1.5 p-10">
        <div v-if="isLoadingRequest"
            class="fixed top-0 left-0 right-0 bottom-0 flex items-center justify-center bg-gray-900 opacity-75 z-50">
            <div class="flex items-center justify-center my-4">
                <img src="../../../../img/loader-spinner.gif" alt="" class="w-8 h-8">
                <h1 class="ml-4 font-medium text-xl text-white font-[Roboto]">Procesando...</h1>
            </div>
        </div>
        <ProcessModal v-else :maxWidth="'5xl'" :show="showModalShortageAdjustment" @close="$emit('cerrar-modal')">

            <div class="flex items-center justify-between py-3 px-4 border-b border-gray-400 border-opacity-70">
                <div class="flex">
                    <span class="text-[16px] font-medium font-[Roboto] text-gray-500 text-opacity-70">Ajuste de entrada</span>
                    <div class="mt-[5px] text-gray-500 text-opacity-70 w-[14px] h-[14px] mx-2">
                        <icon-m :iconName="'nextSvgVector'"></icon-m>
                    </div>
                    <span class="text-[16px] font-medium text-black font-[Roboto]">{{ objId > 0 ? 'Editar ajuste' : 'Crear ajuste'}}</span>
                </div>
                <svg class="h-6 w-6 text-gray-400 hover:text-gray-600 cursor-pointer" @click="$emit('cerrar-modal')"
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </div>

            <div class="max-h-[450px] overflow-x-auto overflow-y-auto">
                <div class="flex items-center justify-between pt-2 pb-1 w-[96%]  pl-8">
                    <div class="flex items-center">
                        <span class="text-[14px] text-slate-700 font-medium font-[Roboto] underline">INFORMACION
                            GENERAL</span>
                    </div>
                    <div v-if="adjustment.id"
                        class="border border-slate-400 py-1 text-slate-700 px-2 rounded-xl hover:text-white hover:bg-slate-700 cursor-pointer">
                        <span class="text-[14px] font-medium text-center font-[Roboto]">{{ adjustment.number }}</span>
                    </div>
                </div>

                <div class="mb-2 mt-1 md:flex flex-row justify-items-start w-[96%] pl-8">
                    <div class="mb-2 md:mr-2 md:mb-0 basis-1/4" :class="{ 'selected-opt': adjustment.centerId > 0, }">
                        <label class="block mb-2 text-[13px] font-medium text-gray-600 ">Centro
                            <span class="text-red-600 font-extrabold">*</span>
                        </label>
                        <div class="relative font-semibold flex h-[35px] w-full">
                            <Multiselect v-model="adjustment.centerId" :options="centers" :searchable="true"
                                :noOptionsText="'Lista vacía.'" placeholder="Seleccione centro"
                                :disabled="adjustment.status != 1"
                                :classes="{ optionSelected: 'text-white bg-[#001c48] bg-opacity-80', optionSelectedPointed: 'text-white bg-[#001c48] opacity-90', noOptions: 'py-2 px-3 text-[12px] text-gray-600 bg-white text-left rtl:text-right', search: 'w-full absolute uppercase inset-0 outline-none focus:ring-0 appearance-none box-border border-0 text-base font-sans bg-white rounded pl-3.5 rtl:pl-0 rtl:pr-3.5', optionPointed: 'text-white bg-[#001c48] bg-opacity-40', }" />
                        </div>
                        <InputError v-for="(item, index) in errors.centerId" :key="index" class="mt-2"
                            :message="item" />
                    </div>
                    <div class="mb-2 md:mr-2 md:mb-0 basis-1/4" :class="{ 'selected-opt': adjustment.idLt > 0, }">
                        <label class="block mb-2 text-[13px] font-medium text-gray-600 ">Linea de trabajo
                            <span class="text-red-600 font-extrabold">*</span>
                        </label>
                        <div class="relative font-semibold flex h-[35px] w-full">
                            <Multiselect v-model="adjustment.idLt" :options="lts" :searchable="true"
                                :noOptionsText="'Lista vacía.'" placeholder="Seleccione uplt"
                                :disabled="adjustment.status != 1"
                                :classes="{ optionSelected: 'text-white bg-[#001c48] bg-opacity-80', optionSelectedPointed: 'text-white bg-[#001c48] opacity-90', noOptions: 'py-2 px-3 text-[12px] text-gray-600 bg-white text-left rtl:text-right', search: 'w-full absolute uppercase inset-0 outline-none focus:ring-0 appearance-none box-border border-0 text-base font-sans bg-white rounded pl-3.5 rtl:pl-0 rtl:pr-3.5', optionPointed: 'text-white bg-[#001c48] bg-opacity-40', }" />
                        </div>
                        <InputError v-for="(item, index) in errors.idLt" :key="index" class="mt-2" :message="item" />
                    </div>
                    <div class="mb-2 md:mr-2 md:mb-0 basis-1/4"
                        :class="{ 'selected-opt': adjustment.financingSourceId > 0, }">
                        <label class="block mb-2 text-[13px] font-medium text-gray-600 ">Fuente financiamiento
                            <span class="text-red-600 font-extrabold">*</span>
                        </label>
                        <div class="relative font-semibold flex h-[35px] w-full">
                            <Multiselect v-model="adjustment.financingSourceId" :options="financingSources"
                                :searchable="true" :noOptionsText="'Lista vacía.'" placeholder="Seleccione fuente"
                                :disabled="adjustment.status != 1"
                                :classes="{ optionSelected: 'text-white bg-[#001c48] bg-opacity-80', optionSelectedPointed: 'text-white bg-[#001c48] opacity-90', noOptions: 'py-2 px-3 text-[12px] text-gray-600 bg-white text-left rtl:text-right', search: 'w-full absolute uppercase inset-0 outline-none focus:ring-0 appearance-none box-border border-0 text-base font-sans bg-white rounded pl-3.5 rtl:pl-0 rtl:pr-3.5', optionPointed: 'text-white bg-[#001c48] bg-opacity-40', }" />
                        </div>
                        <InputError v-for="(item, index) in errors.financingSourceId" :key="index" class="mt-2"
                            :message="item" />
                    </div>
                    <div class="mb-2 md:mr-0 md:mb-0 basis-1/4" :class="{ 'selected-opt': adjustment.reasonId > 0, }">
                        <label class="block mb-2 text-[13px] font-medium text-gray-600 ">Motivo
                            <span class="text-red-600 font-extrabold">*</span>
                        </label>
                        <div class="relative font-semibold flex h-[35px] w-full">
                            <Multiselect v-model="adjustment.reasonId" :options="reasons" :searchable="true"
                                :noOptionsText="'Lista vacía.'" placeholder="Seleccione motivo"
                                :disabled="adjustment.status != 1"
                                :classes="{ optionSelected: 'text-white bg-[#001c48] bg-opacity-80', optionSelectedPointed: 'text-white bg-[#001c48] opacity-90', noOptions: 'py-2 px-3 text-[12px] text-gray-600 bg-white text-left rtl:text-right', search: 'w-full absolute uppercase inset-0 outline-none focus:ring-0 appearance-none box-border border-0 text-base font-sans bg-white rounded pl-3.5 rtl:pl-0 rtl:pr-3.5', optionPointed: 'text-white bg-[#001c48] bg-opacity-40', }" />
                        </div>
                        <InputError v-for="(item, index) in errors.reasonId" :key="index" class="mt-2"
                            :message="item" />
                    </div>
                </div>

                <div class="mb-0 mt-4 md:flex flex-row justify-items-start w-[96%] pl-8">
                    <div class="mb-2 md:mr-2 md:mb-0 basis-1/2" style="border: none; background-color: transparent;">
                        <label class="block mb-2 text-[13px] font-medium text-gray-600 ">Observación
                            <!-- <span class="text-red-600 font-extrabold">*</span> -->
                        </label>
                        <textarea v-model="adjustment.observation" id="descripcion" name="descripcion"
                            :disabled="adjustment.status != 1" placeholder="Escriba observación"
                            :class="adjustment.observation != '' ? 'bg-gray-200' : ''"
                            class="w-full h-12 overflow-y-auto peer placeholder-gray-400 text-xs font-semibold border border-gray-300 hover:border-gray-400 px-2 text-slate-900 transition-colors duration-300 focus:ring-blue-500 focus:border-blue-500"
                            style="border-radius: 4px;"
                            @input="handleValidation('observation', { limit: 255 })">
                    </textarea>
                        <InputError v-for="(item, index) in errors.observation" :key="index" class="mt-2"
                            :message="item" />
                    </div>

                </div>

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
                    <div class="min-w-[970px]">
                        <div
                            class="grid grid-cols-[18%_25%_14%_13%_10%_10%_10%] max-w-[96%] bg-[#001c48] border-b border-x border-gray-500 bg-opacity-80 text-white">
                            <!-- Table header -->
                            <div class="border-r border-gray-500 flex items-center justify-center py-1">
                                <p class="font-[MuseoSans] text-[11px]">PRODUCTO</p>
                            </div>
                            <div class="border-r border-gray-500  flex items-center justify-center py-1">
                                <p class="font-[MuseoSans] text-[11px]">INFO. PRODUCTO</p>
                            </div>
                            <div class="border-r border-gray-500  flex items-center justify-center py-1">
                                <p class="font-[MuseoSans] text-[11px]">MARCA</p>
                            </div>
                            <div class="border-r border-gray-500  flex items-center justify-center py-1">
                                <p class="font-[MuseoSans] text-[11px]">VCTO.</p>
                            </div>
                            <div class="border-r border-gray-500  flex items-center justify-center py-1">
                                <p class="font-[MuseoSans] text-[11px]">CANTIDAD</p>
                            </div>
                            <div class="border-r border-gray-500  flex items-center justify-center py-1">
                                <p class="font-[MuseoSans] text-[11px]">C. UNITARIO</p>
                            </div>
                            <div class="flex items-center justify-center py-1">
                                <p class="font-[MuseoSans] text-[11px]">TOTAL</p>
                            </div>
                        </div>
                    </div>

                    <template v-for="(prod, index) in adjustment.prods" :key="index">
                        <div class="min-w-[970px] grid grid-cols-[96%_4%]" v-if="prod.deleted == false">
                            <div :id="'row-' + index"
                                class="grid grid-cols-[18%_25%_14%_13%_10%_10%_10%] max-w-full bg-white hover:bg-gray-200 text-gray-800 border-b border-x border-gray-500">
                                <div class="border-r border-gray-500 min-h-[75px] flex items-center justify-center"
                                    :class="errors['prods.' + index + '.prodId'] ? 'bg-red-300' : ''">
                                    <!-- Select for products -->
                                    <Multiselect id="doc" v-model="prod.prodId" :options="products" class="h-[35px]"
                                        :disabled="adjustment.status != 1" :searchable="true" :internal-search="false"
                                        @search-change="handleSearchChange($event, index, prod.prodId)"
                                        @change="selectProd($event, index)" :loading="prod.isLoadingProd"
                                        :clear-on-search="true" :filter-results="false" :resolve-on-load="true"
                                        :noOptionsText="'Sin resultados'" placeholder="Buscar producto"
                                        @open="products = []"
                                        :classes="{ optionSelected: 'text-white bg-[#001c48] bg-opacity-80', optionSelectedPointed: 'text-white bg-[#001c48] opacity-90', noOptions: 'py-2 px-3 text-[12px] text-gray-600 bg-white text-left rtl:text-right', search: 'w-full absolute uppercase inset-0 outline-none focus:ring-0 appearance-none box-border border-0 text-base font-sans bg-white rounded pl-3.5 rtl:pl-0 rtl:pr-3.5', optionPointed: 'text-white bg-[#001c48] bg-opacity-40', }" />
                                </div>
                                <div
                                    class="border-r border-gray-500 min-h-[75px] max-h-[100px] flex items-center justify-center">
                                    <div class="overflow-y-auto h-full">
                                        <p class="font-[MuseoSans] text-[12px] p-1">{{ prod.desc }}</p>
                                    </div>
                                </div>
                                <div class="border-r border-gray-500 min-h-[75px] flex items-center justify-center"
                                    :class="errors['prods.' + index + '.brandId'] ? 'bg-red-300' : ''">
                                    <Multiselect v-if="adjustment.status == 1" id="doc" v-model="prod.brandId" :options="brands"
                                        class="h-[35px] max-w-[95%]" :disabled="adjustment.status != 1"
                                        :searchable="true" :noOptionsText="'Lista vacía.'" placeholder="Marca"
                                        :classes="{ optionSelected: 'text-white bg-[#001c48] bg-opacity-80', optionSelectedPointed: 'text-white bg-[#001c48] opacity-90', optionPointed: 'text-white bg-[#001c48] bg-opacity-40' }" />
                                        <p class="font-[MuseoSans] text-[12px] p-1 " v-else>{{ prod.brandLabel }}</p>
                                </div>
                                <div class="flex items-center justify-center border-r border-gray-500 min-h-[75px]"
                                    :class="errors['prods.' + index + '.expDate'] ? 'bg-red-300' : ''">
                                    <div v-if="prod.perishable === 1" class="max-w-[95%]">
                                        <date-time-picker-m v-model="prod.expDate" :showIcon="false"
                                            :placeholder="'Fecha'" :disabled="adjustment.status != 1" />
                                    </div>
                                    <p v-else class="font-[MuseoSans] text-[12px] p-1 ">N/A</p>
                                </div>
                                <div class="border-r border-gray-500 min-h-[75px] flex items-center justify-center"
                                    :class="errors['prods.' + index + '.qty'] ? 'bg-red-300' : ''">
                                    <input v-model="prod.qty" :disabled="adjustment.status != 1"
                                        class="font-bold max-w-[95%] p-0 text-center h-[35px] rounded-[4px] font-[MuseoSans] text-[13px] border-[#d1d5db]"
                                        type="text" name="" id=""
                                        @input="handleValidation('qty', { limit: 3, number: true }, { index: index })">
                                </div>
                                <div class="border-r border-gray-500 min-h-[75px] flex items-center justify-center"
                                    :class="errors['prods.' + index + '.cost'] ? 'bg-red-300' : ''">
                                    <input v-model="prod.cost" :disabled="adjustment.status != 1"
                                        class="font-bold max-w-[95%] p-0 text-center h-[35px] rounded-[4px] font-[MuseoSans] text-[13px] border-[#d1d5db]"
                                        type="text" name="" id=""
                                        @input="handleValidation('cost', { limit: 8, amount: true }, { index: index })">
                                </div>
                                <div class="min-h-[75px] flex items-center justify-center">
                                    <p
                                        class="font-[MuseoSans] inline-block max-w-full text-ellipsis overflow-hidden text-[13px] p-1 font-bold">
                                        {{ prod.total != '' ? '$' + prod.total : '' }}
                                    </p>
                                </div>
                            </div>
                            <div class="max-w-full flex items-center justify-center">
                                <div class="w-full h-[30px] ">
                                    <svg v-if="adjustment.status == 1" @click="deleteRow(index, prod.detId)"
                                        class="text-red-600 cursor-pointer ml-1 hover:text-red-800" width="20px"
                                        height="20px" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round">
                                        </g>
                                        <g id="SVGRepo_iconCarrier">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M6.30958 3.54424C7.06741 2.56989 8.23263 2 9.46699 2H20.9997C21.8359 2 22.6103 2.37473 23.1614 2.99465C23.709 3.61073 23.9997 4.42358 23.9997 5.25V18.75C23.9997 19.5764 23.709 20.3893 23.1614 21.0054C22.6103 21.6253 21.8359 22 20.9997 22H9.46699C8.23263 22 7.06741 21.4301 6.30958 20.4558L0.687897 13.2279C0.126171 12.5057 0.126169 11.4943 0.687897 10.7721L6.30958 3.54424ZM10.2498 7.04289C10.6403 6.65237 11.2734 6.65237 11.664 7.04289L14.4924 9.87132L17.3208 7.04289C17.7113 6.65237 18.3445 6.65237 18.735 7.04289L19.4421 7.75C19.8327 8.14052 19.8327 8.77369 19.4421 9.16421L16.6137 11.9926L19.4421 14.8211C19.8327 15.2116 19.8327 15.8448 19.4421 16.2353L18.735 16.9424C18.3445 17.3329 17.7113 17.3329 17.3208 16.9424L14.4924 14.114L11.664 16.9424C11.2734 17.3329 10.6403 17.3329 10.2498 16.9424L9.54265 16.2353C9.15212 15.8448 9.15212 15.2116 9.54265 14.8211L12.3711 11.9926L9.54265 9.16421C9.15212 8.77369 9.15212 8.14052 9.54265 7.75L10.2498 7.04289Z"
                                                fill="currentColor"></path>
                                        </g>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </template>
                    <div id="total" class="w-full max-w-full grid grid-cols-[96%_4%] min-w-[970px]">
                        <div class="grid grid-cols-[90%_10%] w-full max-w-full border-b border-x border-gray-500">
                            <div class="text-right border-r h-[30px] border-gray-500">
                                <p class="font-[MuseoSans] text-[12px] py-2 mr-2 font-bold mt-[-3px]">TOTAL AJUSTE (+)
                                </p>
                            </div>
                            <div class="text-center h-[30px] ">
                                <p
                                    class="font-[MuseoSans] text-[13px] inline-block max-w-full text-ellipsis overflow-hidden py-2 font-bold text-green-800 mt-[-3px]">
                                    ${{ totalRec
                                    }}
                                </p>
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="w-full h-[30px]">
                                <svg v-if="adjustment.status == 1" @click="addNewRow()"
                                    class="text-green-600 cursor-pointer hover:text-green-800" width="28px"
                                    height="28px" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12 6V18M6 12H18" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                        </div>
                    </div>


                </div>

            </div>


            <div id="buttons" class="md:flex flex md:items-center my-6 sticky flex-row justify-center mx-8">
                <button type="button" @click="$emit('cerrar-modal')"
                    class="mr-2 text-gray-600 hover:text-white border border-gray-600 hover:bg-gray-700 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-[12px] px-2.5 py-1.5 text-center mb-2 dark:border-gray-500 dark:text-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-800">CANCELAR</button>
                <div class="" v-if="adjustment.status == 1">
                    <button v-if="objId > 0" @click="updateAdjustment(adjustment)"
                        class="bg-orange-700 hover:bg-orange-800 text-white font-medium text-[12px] px-2.5 py-1.5 rounded-lg mr-1.5 mb-2">ACTUALIZAR</button>
                    <button v-else @click="storeAdjustment(adjustment)"
                        class="bg-green-700 hover:bg-green-800 text-white font-medium text-[12px] px-2.5 py-1.5 rounded-lg mr-1.5 mb-2">GUARDAR</button>
                </div>
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

import { toRefs, onMounted } from 'vue';

export default {
    emits: ["cerrar-modal", "get-table"],
    components: { ProcessModal, InputError, InputText, IconM, DateTimePickerM },
    props: {
        showModalShortageAdjustment: {
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
            getInfoForModalAdjustment, selectProd, deleteRow, addNewRow, storeAdjustment, updateAdjustment, handleValidation
        } = useAjusteEntrada(context);

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