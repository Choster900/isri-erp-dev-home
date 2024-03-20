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
        <ProcessModal v-else maxWidth="5xl" :show="showModalDonation" @close="$emit('cerrar-modal')"
            :addClases="' bg-gray-100'">
            <div class="flex items-center justify-between py-3 px-4 border-b border-gray-400 border-opacity-70">
                <div class="flex">
                    <span class="text-[16px] font-medium font-[Roboto] text-gray-500 text-opacity-70">Recepcion</span>
                    <div class="mt-[5px] text-gray-500 text-opacity-70 w-[14px] h-[14px] mx-2">
                        <icon-m :iconName="'nextSvgVector'"></icon-m>
                    </div>
                    <span class="text-[16px] font-medium text-black font-[Roboto]">{{
            recepId > 0 ? "Editar donacion" : "Crear donacion"
        }}</span>
                </div>
                <svg class="h-6 w-6 text-gray-400 hover:text-gray-600 cursor-pointer" @click="$emit('cerrar-modal')"
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </div>
            <div class="ml-8 mr-0 overflow-x-auto mt-4 max-h-[400px] overflow-y-auto">
                <div class="max-w-[97%] min-w-[800px] flex">
                    <div class="flex w-full">
                        <!-- Columna 1 -->
                        <div class="w-[23%] min-w-[225px] bg-white border border-gray-500 p-2">
                            <img src="../../../../img/isri-gob.png" alt="Logo del instituto" class="w-full" />
                        </div>
                        <!-- Columna 2 -->
                        <div
                            class="w-[77%] min-w-[575px] bg-white border-y border-r border-gray-500 p-2 flex items-center justify-center h-full">
                            <div class="flex flex-col items-center">
                                <p class="font-[Bembo] text-center text-[14px] font-bold">
                                    ALMACEN CENTRAL
                                </p>
                                <p class="font-[Bembo] text-center text-[14px] font-bold">
                                    DONACIONES
                                </p>
                                <p class="font-[Bembo] text-center text-[14px] font-bold">
                                    ACTA DE RECEPCION {{ donInfo.acta ?? "" }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="max-w-[97%] min-w-[800px] border-gray-500 border-x flex py-1 bg-white">
                    <div class="flex w-full">
                        <div class="justify-start flex items-center min-w-[345px] w-[50%] bg-white">
                            <p class="font-[MuseoSans] text-gray-700 text-[12px] py-1 ml-2">
                                FECHA Y HORA DE RECEPCION:
                                <span class="ml-1 underline font-bold font-[MuseoSans] text-[12px]">{{ donInfo.dateTime
                                    }}</span>
                            </p>
                        </div>
                        <div class="justify-start flex items-center min-w-[172.5px] w-[25%] bg-white">
                            <p class="font-[MuseoSans] text-gray-700 text-[12px] py-1">
                                FINANCIAMIENTO:
                                <span class="ml-1 underline font-bold font-[MuseoSans] text-[12px]">DN</span>
                            </p>
                        </div>
                        <div class="justify-start flex items-center min-w-[172.5px] w-[25%] bg-white">
                            <p class="font-[MuseoSans] text-gray-700 text-[12px] py-1">
                                COMPROMISO:
                                <span class="ml-1 underline font-bold font-[MuseoSans] text-[12px]"></span>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="max-w-[97%] min-w-[800px] border-b border-x border-gray-500 flex py-1 bg-white">
                    <div class="flex w-full">
                        <div :class="{ 'select-err': errors.supplierId }"
                            class="justify-start flex-row flex items-center min-w-[517.5px] w-[75%]">
                            <p class="font-[MuseoSans] text-gray-700 text-[12px] py-1 ml-2">
                                DONANTE:
                            </p>
                            <div class="flex items-center w-[53%] ml-2">
                                <Multiselect id="doc" v-model="donInfo.supplierId" :options="suppliers" class="h-[30px]"
                                    :class="{ 'bg-red-200': errors.supplierId }" :disabled="donInfo.status != 1"
                                    :searchable="true" :noOptionsText="'Lista vacía.'" placeholder="Seleccione"
                                    @input="selectProv($event)"
                                    :classes="{ optionSelected: 'text-white bg-[#001c48] bg-opacity-80', optionSelectedPointed: 'text-white bg-[#001c48] opacity-90', optionPointed: 'text-white bg-[#001c48] bg-opacity-40' }" />
                            </div>
                        </div>
                        <div class="justify-start flex items-center min-w-[172.5px] w-[25%] bg-white">
                            <p class="font-[MuseoSans] text-gray-700 text-[12px] py-1">
                                NIT:
                                <span class="ml-1 underline font-bold font-[MuseoSans] text-[12px]">{{ donInfo.nit
                                    }}</span>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="max-w-[97%] min-w-[800px] flex">
                    <div class="flex w-full border-x border-b border-gray-500">
                        <div class="justify-center flex w-full bg-white">
                            <p class="font-[MuseoSans] text-[12px] py-1.5 font-bold">LISTADO DE PRODUCTOS</p>
                        </div>
                    </div>
                </div>
                <div class="max-w-[97%] min-w-[800px] flex">
                    <div
                        class="flex w-full bg-[#001c48] border-b border-x border-gray-500 bg-opacity-80 min-w-[800px] text-white">
                        <!-- Table header -->
                        <div class="w-[20%] min-w-[125px] border-r border-gray-500 h-[30px]">
                            <p class="text-center font-[MuseoSans] text-[12px]  mt-[5px]">PRODUCTO
                            </p>
                        </div>
                        <div class="w-[25%] min-w-[200px] border-r border-gray-500 h-[30px]">
                            <p class="text-center font-[MuseoSans] text-[12px]  mt-[5px]">
                                INFO. PRODUCTO</p>
                        </div>
                        <div class="w-[13%] min-w-[125px] border-r border-gray-500 h-[30px]">
                            <p class="text-center font-[MuseoSans] text-[12px]  mt-[5px]">CENTRO</p>
                        </div>
                        <div class="w-[12%] min-w-[100px] border-r border-gray-500 h-[30px]">
                            <p class="text-center font-[MuseoSans] text-[12px]  mt-[5px]">CANTIDAD</p>
                        </div>
                        <div class="w-[15%] min-w-[125px] border-r border-gray-500 h-[30px]">
                            <p class="text-center font-[MuseoSans] text-[12px]  mt-[5px]">C. UNITARIO</p>
                        </div>
                        <div class="w-[15%] min-w-[125px] border-gray-500 h-[30px]">
                            <p class="text-center font-[MuseoSans] text-[12px]  mt-[5px]">TOTAL</p>
                        </div>
                    </div>
                </div>
                <template v-for="(prod, index) in donInfo.prods" :key="index">
                    <div :id="'row-' + index" v-if="prod.deleted == false" class="max-w-full flex min-w-[815px]">
                        <div
                            class="flex w-[97%] min-w-[800px] bg-white hover:bg-gray-200 text-gray-800 border-b border-x border-gray-500">
                            <div class="w-[20%] min-w-[125px] flex items-center justify-center border-r border-gray-500 min-h-[75px]"
                                :class="errors['prods.' + index + '.prodId'] ? 'bg-red-300' : ''">
                                <!-- Select for products -->
                                <Multiselect id="doc" v-model="prod.prodId" :options="products" class="h-[35px]"
                                    :disabled="donInfo.status != 1" :searchable="true" :internal-search="false"
                                    @search-change="handleSearchChange($event, index, prod.prodId)"
                                    @change="selectProd($event, index)" :loading="prod.isLoadingProd"
                                    :clear-on-search="true" :filter-results="false" :resolve-on-load="true"
                                    :noOptionsText="'Escriba para buscar...'" placeholder="Seleccione"
                                    @open="openAnySelect(prod.prodId)"
                                    :classes="{ optionSelected: 'text-white bg-[#001c48] bg-opacity-80', optionSelectedPointed: 'text-white bg-[#001c48] opacity-90', noOptions: 'py-2 px-3 text-[12px] text-gray-600 bg-white text-left rtl:text-right', search: 'w-full absolute uppercase inset-0 outline-none focus:ring-0 appearance-none box-border border-0 text-base font-sans bg-white rounded pl-3.5 rtl:pl-0 rtl:pr-3.5', optionPointed: 'text-white bg-[#001c48] bg-opacity-40', }" />
                            </div>

                            <div
                                class="w-[25%] min-w-[200px] flex items-center justify-center border-r border-gray-500 min-h-[75px] max-h-[100px]">
                                <div class="overflow-y-auto h-full">
                                    <p class="font-[MuseoSans] text-sm p-1">{{ prod.desc }}</p>
                                </div>
                            </div>

                            <div class="w-[13%] min-w-[125px] flex items-center justify-center border-r border-gray-500 min-h-[75px]"
                                :class="errors['prods.' + index + '.centerId'] ? 'bg-red-300' : ''">
                                <Multiselect id="doc" v-model="prod.centerId" :options="centers" class="h-[35px]"
                                    :disabled="donInfo.status != 1" :searchable="true" :noOptionsText="'Lista vacía.'"
                                    placeholder="Centro"
                                    :classes="{ optionSelected: 'text-white bg-[#001c48] bg-opacity-80', optionSelectedPointed: 'text-white bg-[#001c48] opacity-90', optionPointed: 'text-white bg-[#001c48] bg-opacity-40' }" />
                            </div>
                            <div class="w-[12%] min-w-[100px] flex items-center justify-center border-r border-gray-500 min-h-[75px]"
                                :class="errors['prods.' + index + '.qty'] ? 'bg-red-300' : ''">
                                <input v-model="prod.qty" :disabled="donInfo.status != 1"
                                    class="font-bold max-w-[75%] p-0 text-center h-[35px] rounded-[4px] font-[MuseoSans] text-sm border-[#d1d5db]"
                                    type="text" name="" id="">
                            </div>
                            <div class="w-[15%] min-w-[125px] flex items-center justify-center border-r border-gray-500 min-h-[75px]"
                                :class="errors['prods.' + index + '.cost'] ? 'bg-red-300' : ''">
                                <input v-model="prod.cost" :disabled="donInfo.status != 1"
                                    class="font-bold max-w-[75%] p-0 text-center h-[35px] rounded-[4px] font-[MuseoSans] text-sm border-[#d1d5db]"
                                    type="text" name="" id="">
                            </div>
                            <div class="w-[15%] min-w-[125px] flex items-center justify-center min-h-[75px]">
                                <p class="font-[MuseoSans] text-sm p-1 font-bold">
                                    {{ prod.total != '' ? '$' + prod.total : '' }}
                                </p>
                            </div>
                        </div>
                        <div class="w-[3%] flex items-center justify-center">
                            <div class="w-full min-w-[15px] h-[30px]">
                                <svg v-if="donInfo.status != 3" @click="deleteRow(index, prod.detRecId)"
                                    class="text-red-600 cursor-pointer ml-1 hover:text-red-800" width="20px"
                                    height="20px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
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
                <div id="total" class="max-w-full min-w-[815px] flex">
                    <div class="flex w-[97%] min-w-[800px] border-b border-x border-gray-500">
                        <div class="justify-end flex border-r w-[85%] min-w-[675px] h-[30px]  border-gray-500">
                            <p class="font-[MuseoSans] text-sm py-2 mr-2 font-bold mt-[-4px]">TOTAL ACTA</p>
                        </div>
                        <div class="justify-center flex w-[15%] min-w-[125px] h-[30px] ">
                            <p class="font-[MuseoSans] text-sm py-2 font-bold text-green-800 mt-[-4px]">${{ totalRec
                                }}
                            </p>
                        </div>
                    </div>
                    <div class="flex w-[3%]">
                        <div class="w-full min-w-[15px] h-[30px]">
                            <svg v-if="donInfo.status != 3" @click="addNewRow()"
                                class="text-green-600 cursor-pointer hover:text-green-800" width="28px" height="28px"
                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 6V18M6 12H18" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
            <div id="buttons" class="md:flex flex md:items-center my-6 sticky flex-row justify-center mx-8">
                <button type="button" @click="$emit('cerrar-modal')"
                    class="mr-2 text-gray-600 hover:text-white border border-gray-600 hover:bg-gray-700 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-[12px] px-2.5 py-1.5 text-center mb-2 dark:border-gray-500 dark:text-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-800">CANCELAR</button>
                <div class="" v-if="donInfo.status == 1">
                    <button v-if="recepId > 0" @click="updateReception(donInfo)"
                        class="bg-orange-700 hover:bg-orange-800 text-white font-medium text-[12px] px-2.5 py-1.5 rounded-lg mr-1.5 mb-2">ACTUALIZAR</button>
                    <button v-else @click="storeReception(donInfo)"
                        class="bg-green-700 hover:bg-green-800 text-white font-medium text-[12px] px-2.5 py-1.5 rounded-lg mr-1.5 mb-2">GUARDAR</button>
                </div>
            </div>
        </ProcessModal>
    </div>
</template>

<script>
import { useDonacion } from "@/Composables/Almacen/Donacion/useDonacion.js";
import InputError from "@/Components/InputError.vue";
import ProcessModal from "@/Components-ISRI/AllModal/ProcessModal.vue";
import InputText from "@/Components-ISRI/ComponentsToForms/InputText.vue";
import IconM from "@/Components-ISRI/ComponentsToForms/IconM.vue";
//import DateTimePickerM from "@/Components-ISRI/ComponentsToForms/DateTimePickerM.vue";

import { toRefs, onMounted } from "vue";

export default {
    emits: ["cerrar-modal", "get-table"],
    components: { ProcessModal, InputError, InputText, IconM },
    props: {
        showModalDonation: {
            type: Boolean,
            default: false,
        },
        recepId: {
            type: Number,
            default: 0,
        },
    },
    setup(props, context) {
        const { recepId } = toRefs(props);

        const {
            isLoadingRequest, donInfo, errors, suppliers, products, centers,
            asyncFindProduct, isLoadingProduct, totalRec,
            getInfoForModalDonation, selectProv, openAnySelect, selectProd, addNewRow,
            deleteRow, storeReception, updateReception
        } = useDonacion(context);

        const handleSearchChange = async (query, index, prodId) => {
            await asyncFindProduct(query, index, prodId);
        }

        onMounted(async () => {
            await getInfoForModalDonation(recepId.value);
        });

        return {
            isLoadingRequest, donInfo, errors, suppliers, products, centers,
            isLoadingProduct, totalRec,
            selectProv, handleSearchChange, openAnySelect, selectProd, addNewRow,
            deleteRow, storeReception, updateReception
        };
    },
};
</script>

<style>
.input-border-bottom {
    border-width: 0 0 1px 0;
    /* Establece el ancho del borde solo en la parte inferior */
    border-color: #6b7280;
    /* Color del borde */
    border-style: solid;
    /* Establece el estilo del borde como una línea sólida */
    outline: none;
    /* Elimina el contorno del input */
}

.dp__input_wrap {
    height: 35px;
}

.select-err .multiselect-wrapper input {
    background-color: #FECACA;
}

.dp__theme_light {
    /* I've edited this */
    --dp-primary-color: rgba(0, 28, 72, 0.8);
    --dp-cell-size: 25px;
    --dp-font-size: 0.8rem;
}
</style>
