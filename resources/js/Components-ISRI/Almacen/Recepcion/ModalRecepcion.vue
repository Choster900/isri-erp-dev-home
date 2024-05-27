<template>
    <div class="m-1.5 p-10">
        <div v-if="isLoadingRequest"
            class="fixed top-0 left-0 right-0 bottom-0 flex items-center justify-center bg-gray-900 opacity-75 z-50">
            <div class="flex items-center justify-center my-4">
                <img src="../../../../img/loader-spinner.gif" alt="" class="w-8 h-8">
                <h1 class="ml-4 font-medium text-xl text-white font-[Roboto]">Procesando...</h1>
            </div>
        </div>
        <ProcessModal v-else :maxWidth="(recepId <= 0 && !startRec) ? 'xl' : '5xl'" :show="showModalRecep"
            @close="$emit('cerrar-modal')" :addClases="' bg-gray-100'">

            <div class="flex items-center justify-between py-3 px-4 border-b border-gray-400 border-opacity-70">
                <div class="flex">
                    <span class="text-[16px] font-medium font-[Roboto] text-gray-500 text-opacity-70">Recepcion</span>
                    <div class="mt-[5px] text-gray-500 text-opacity-70 w-[14px] h-[14px] mx-2">
                        <IconM :iconName="'nextSvgVector'"></IconM>
                    </div>
                    <span class="text-[16px] font-medium text-black font-[Roboto]">{{ recepId > 0 ? (infoToShow.status
                        != 1 ? 'Ver recepción' : 'Editar recepción') : 'Crear recepción' }}</span>
                </div>
                <svg class="h-6 w-6 text-gray-400 hover:text-gray-600 cursor-pointer" @click="$emit('cerrar-modal')"
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
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
                                @click="docSelected = 1; infoToShow.docId = ''; infoToShow.detDocId = '';">
                                <div class="mb-2">
                                    <p class="font-[Roboto] ">CONTRATOS</p>
                                </div>
                                <div>
                                    <p class="font-[Roboto] ">Pendientes: {{ contrato.length }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="w-[50%] mt-4 mb-4 flex items-center justify-center">
                            <div class="mx-auto w-[70%] h-[125px] border rounded-md shadow-lg  flex flex-col items-center justify-center
                            cursor-pointer hover:shadow-blue-200 hover:text-blue-700 transition duration-300 ease-in-out bg-white"
                                :class="docSelected === 2 ? ' text-blue-600 border-blue-600 border-2' : 'border-gray-300'"
                                @click="docSelected = 2; infoToShow.docId = ''; infoToShow.detDocId = '';">
                                <div class="mb-2">
                                    <p class="font-[Roboto]">ORDENES DE COMPRA</p>
                                </div>
                                <div>
                                    <p class="font-[Roboto]">Pendientes: {{ ordenC.length }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <p class="text-center my-4 font-[Roboto] text-slate-800 font-semibold">SELECCIONE NUMERO DOCUMENTO E
                    ITEM
                </p>
                <div class="flex flex-col md:flex-row items-center mb-4 h-10 mx-8 max-w-full">
                    <div class="w-full md:w-[40%] mb-2 md:mb-0 md:mr-2">
                        <label for="doc" class="font-[Roboto]">Numero documento:</label>
                    </div>
                    <div class="relative font-semibold flex h-[35px] w-full md:w-[60%]">
                        <Multiselect id="doc" v-model="infoToShow.docId" :options="filteredDoc" :searchable="true"
                            :noOptionsText="'Lista vacía.'" placeholder="Seleccione documento"
                            @clear="infoToShow.detDocId = ''" @change="infoToShow.detDocId = ''"/>
                    </div>
                </div>
                <div class="flex flex-col md:flex-row items-center mb-4 h-10 mx-8 max-w-full">
                    <div class="w-full md:w-[40%] mb-2 md:mb-0 md:mr-2">
                        <label for="det-doc" class="font-[Roboto]">Item:</label>
                    </div>
                    <div class="relative font-semibold flex h-[35px] w-full md:w-[60%]">
                        <Multiselect id="det-doc" v-model="infoToShow.detDocId" :options="filteredItems"
                            :searchable="true" :noOptionsText="'Lista vacía.'"
                            placeholder="Seleccione item" />
                    </div>
                </div>
                <div v-if="docSelected === 1" class="flex flex-col md:flex-row items-center mb-4 h-10 mx-8 max-w-full">
                    <div class="w-full md:w-[40%] mb-2 md:mb-0 md:mr-2">
                        <label for="det-doc" class="font-[Roboto]">Mes:</label>
                    </div>
                    <div class="relative font-semibold flex h-[35px] w-full md:w-[60%]">
                        <Multiselect id="det-doc" v-model="infoToShow.monthId" :options="months"
                            :searchable="true" :noOptionsText="'Lista vacía.'"
                            placeholder="Seleccione mes" />
                    </div>
                </div>

                <div class="md:flex mb-6 mt-[60px] flex-row justify-end mx-8">
                    <button type="button" :disabled="infoToShow.docId == '' || infoToShow.detDocId == ''"
                        :class="(infoToShow.docId == '' || infoToShow.detDocId == '') ? 'cursor-not-allowed opacity-50' : ''"
                        :title="(infoToShow.docId == '' || infoToShow.detDocId == '') ? 'Información incompleta' : 'Iniciar proceso de recepción'"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                        @click="startReception(recepId)">
                        Iniciar recepcion
                        <svg class="rtl:rotate-180 w-3.5 h-3.5 ms-2" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M1 5h12m0 0L9 1m4 4L9 9" />
                        </svg>
                    </button>
                </div>
            </div>

            <div v-else>
                <div class="pl-8 mr-0 pb-1 max-w-full overflow-x-auto mt-4 max-h-[450px] overflow-y-auto">
                    <div class="min-w-[970px] bg-white" id="headerFormat">
                        <div class="grid grid-cols-[23%_77%] max-w-[96%] border border-gray-500">
                            <!-- Columna 1 -->
                            <div class="w-full bg-white border-r border-gray-500 p-2 flex items-center justify-center">
                                <img src="../../../../img/isri-gob.png" alt="Logo del instituto"
                                    class="w-full max-w-full">
                            </div>
                            <!-- Columna 2 -->
                            <div class="w-full bg-white p-2">
                                <p class="font-[Bembo] text-center text-[14px] font-bold">ALMACEN GENERAL</p>
                                <p class="font-[Bembo] text-center text-[14px] font-bold">
                                    {{ infoToShow.docName }}
                                </p>
                                <p class="font-[Bembo] text-center text-[14px] font-bold">
                                    {{ infoToShow.itemName }}
                                </p>
                                <p class="font-[Bembo] text-center text-[14px] font-bold">ACTA DE RECEPCION {{
                                    recDocument.acta ?? '' }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="min-w-[970px] bg-white">
                        <div class="grid grid-cols-[40%_35%_25%] max-w-[96%]">
                            <div class="w-full justify-start flex items-center border-l border-gray-500 bg-white">
                                <p class="font-[MuseoSans] text-gray-700 text-[12px] py-1 ml-2">Fecha y hora de recepción:
                                    <span class="ml-1 underline font-bold font-[MuseoSans] text-[12px]">{{
                                        infoToShow.dateTime }}</span>
                                </p>
                            </div>
                            <div class=" w-full justify-start flex items-center bg-white">
                                <p class="font-[MuseoSans] text-gray-700 text-[12px] py-1">Financiamiento:
                                    <span class="ml-1 underline font-bold font-[MuseoSans] text-[12px]">{{
                                        infoToShow.financingSource }}</span>
                                </p>
                            </div>
                            <div class="w-full justify-start flex items-center border-r border-gray-500 bg-white">
                                <p class="font-[MuseoSans] text-gray-700 text-[12px] py-1">Compromiso:
                                    <span class="ml-1 underline font-bold font-[MuseoSans] text-[12px]">{{
                                        infoToShow.commitment }}</span>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="min-w-[970px]">
                        <div class="grid grid-cols-[75%_25%] max-w-[96%]">
                            <div class="w-full justify-start flex items-center border-l border-gray-500 bg-white">
                                <p class="font-[MuseoSans] text-gray-700 text-[12px] py-1 ml-2">Proveedor:
                                    <span class="ml-1 underline font-bold font-[MuseoSans] text-[12px]">{{
                                        infoToShow.supplier }}</span>
                                </p>
                            </div>
                            <div class="w-full justify-start flex items-center border-r border-gray-500 bg-white">
                                <p class="font-[MuseoSans] text-gray-700 text-[12px] py-1"> {{ infoToShow.dui ? 'DUI:' : 'NIT:' }}
                                    <span class="ml-1 underline font-bold font-[MuseoSans] text-[12px]">{{
                                        infoToShow.dui ? infoToShow.dui : infoToShow.nit
                                    }}</span>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="min-w-[970px]">
                        <div class="grid grid-cols-[100%] max-w-[96%] bg-white border-b border-gray-500">
                            <div class="w-full justify-start flex items-center border-x border-gray-500 bg-white">
                                <p class="font-[MuseoSans] text-gray-700 text-[12px] py-1 ml-2">Fecha referencia documento de compra:
                                    <span class="ml-1 underline font-bold font-[MuseoSans] text-[12px]">{{
                                        infoToShow.acqDocDate }}</span>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="min-w-[970px]">
                        <div v-if="infoToShow.status == 1"
                            class="grid grid-cols-[100%] max-w-[96%] border-x border-b border-gray-500">
                            <div class="justify-center flex w-full bg-white py-2">
                                <p class="font-[MuseoSans] text-[12px] py-1.5 font-bold mr-2">PRODUCTOS: </p>
                                <div class="w-[50%] flex items-center justify-center mr-2"
                                    :class="errors['prods.' + index + '.prodId'] ? 'bg-red-300' : ''">
                                    <Multiselect id="doc" v-model="recDocument.detStockId" :options="products"
                                        class="h-[30px]" :disabled="infoToShow.status != 1" :searchable="true"
                                        :noOptionsText="'Lista vacía.'" placeholder="Seleccione"
                                        :classes="{ optionSelected: 'text-white bg-[#001c48] bg-opacity-80', optionSelectedPointed: 'text-white bg-[#001c48] opacity-90', noOptions: 'py-2 px-3 text-[12px] text-gray-600 bg-white text-left rtl:text-right', search: 'w-full absolute uppercase inset-0 outline-none focus:ring-0 appearance-none box-border border-0 text-base font-sans bg-white rounded pl-3.5 rtl:pl-0 rtl:pr-3.5', optionPointed: 'text-white bg-[#001c48] bg-opacity-40', }" />
                                </div>
                                <button @click="setProdItem(recDocument.detStockId)"
                                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-0.5 px-4 rounded">
                                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    <span class="font-[Roboto] text-[12px]">AGREGAR</span>
                                </button>

                            </div>
                        </div>
                        <div v-else class="grid grid-cols-[100%] max-w-[96%] border-x border-b border-gray-500">
                            <div class="justify-center flex w-full bg-white">
                                <p class="font-[MuseoSans] text-[12px] py-1 font-bold">LISTADO DE PRODUCTOS</p>
                            </div>
                        </div>
                    </div>
                    <div class="min-w-[970px]">
                        <div
                            class="grid grid-cols-[37%_14%_13%_12%_12%_12%] max-w-[96%] w-full bg-[#001c48] border-b border-x border-gray-500 bg-opacity-80 min-w-[800px] text-white">
                            <!-- Table header -->
                            <div class="w-full flex items-center justify-center border-r border-gray-500 h-[30px]">
                                <p class="text-center font-[MuseoSans] text-[11px]">PRODUCTO
                                </p>
                            </div>
                            <div class="w-full flex items-center justify-center border-r border-gray-500 h-[30px]">
                                <p class="text-center font-[MuseoSans] text-[11px]">MARCA</p>
                            </div>
                            <div class="w-full flex items-center justify-center border-r border-gray-500 h-[30px]">
                                <p class="text-center font-[MuseoSans] text-[11px]">VCTO.</p>
                            </div>
                            <div class="w-full flex items-center justify-center border-r border-gray-500 h-[30px]">
                                <p class="text-center font-[MuseoSans] text-[11px]">CANTIDAD</p>
                            </div>
                            <div class="w-full flex items-center justify-center border-r border-gray-500 h-[30px]">
                                <p class="text-center font-[MuseoSans] text-[11px]">C. UNITARIO</p>
                            </div>
                            <div class="w-full flex items-center justify-center border-gray-500 h-[30px]">
                                <p class="text-center font-[MuseoSans] text-[11px]">TOTAL</p>
                            </div>
                        </div>
                    </div>

                    <template v-for="(lts, indexLt) in recDocument.prods" :key="indexLt"> <!-- Lineas de trabajo -->
                        <div v-if="hasActiveProds(indexLt)" class="min-w-[970px]">
                            <div @click="lts.isOpen = !lts.isOpen" @mouseover="lts.hover = true"
                            @mouseleave="lts.hover = false"
                                class="max-w-[96%] bg-white border-x border-b flex border-gray-500 py-2 cursor-pointer hover:bg-gray-300">
                                <!-- Icono de flecha a la izquierda -->
                                <div class="w-1/3 flex items-center justify-start">
                                    <svg class="w-5 h-5 text-green-800 ml-2"
                                        :class="{ 'rotate-180': !lts.isOpen }" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7">
                                        </path>
                                    </svg>
                                </div>
                                <div class="w-1/3 flex items-center justify-center">
                                    <p class="font-[MuseoSans] text-[13px] text-green-800 font-semibold mr-1">UP/LT = {{ lts.codigo_up_lt }}
                                </p>
                                </div>
                                <div class="w-1/3 flex items-center justify-end">
                                    <p class="font-[MuseoSans] text-[13px] text-green-800 font-semibold mr-1">${{ calculateLtTotal(indexLt) }}
                                </p>
                                </div>
                            </div>
                        </div>

                        <template v-for="(prod, index) in lts.productos" :key="index"> <!-- Productos -->
                            <div class="min-w-[970px] grid grid-cols-[96%_4%] max-w-full"
                                v-if="prod.deleted == false && lts.isOpen">
                                <div :id="'lt-' + indexLt + 'prod-' + index"
                                    class="grid grid-cols-[37%_14%_13%_12%_12%_12%] max-w-full border-b border-x border-gray-500 hover:bg-gray-200"
                                    :class="(lts.hover && lts.isOpen && !checkBlinkingClass(indexLt,index)) ? 'bg-gray-200' : 'bg-white'">
                                    <div
                                        class="w-full flex items-center justify-center border-r border-gray-500 min-h-[75px] max-h-[100px]">
                                        <div class="overflow-y-auto h-full flex items-center justify-center">
                                            <p class="font-[MuseoSans] text-[12px] p-1">{{ prod.desc }}</p>
                                        </div>
                                    </div>
                                    <div class="w-full border-r border-gray-500 min-h-[75px] flex items-center justify-center"
                                        :class="errors['prods.' + indexLt + '.productos.'+ index +'.brandId'] && !prod.brandId ? 'bg-red-300' : ''">
                                        <Multiselect v-if="infoToShow.status == 1" id="doc" v-model="prod.brandId"
                                            :options="brands" class="h-[35px] max-w-[95%]"
                                            :disabled="infoToShow.status != 1" :searchable="true"
                                            :noOptionsText="'Lista vacía.'" placeholder="Marca"
                                            :classes="{ optionSelected: 'text-white bg-[#001c48] bg-opacity-80', optionSelectedPointed: 'text-white bg-[#001c48] opacity-90', noOptions: 'py-2 px-3 text-[12px] text-gray-600 bg-white text-left rtl:text-right', search: 'w-full absolute uppercase inset-0 outline-none focus:ring-0 appearance-none box-border border-0 text-base font-sans bg-white rounded pl-3.5 rtl:pl-0 rtl:pr-3.5', optionPointed: 'text-white bg-[#001c48] bg-opacity-40', }" />
                                        <p class="font-[MuseoSans] text-[12px] p-1 " v-else>{{ prod.brandLabel }}</p>
                                    </div>

                                    <div class="w-full flex items-center justify-center border-r border-gray-500 min-h-[75px]"
                                        :class="(errors['prods.' + indexLt + '.productos.'+ index + '.expiryDate'] && !prod.expiryDate) ? 'bg-red-300' : ''">
                                        <div class="max-w-[95%]">
                                            <DateTimePickerM v-if="prod.perishable === 1" v-model="prod.expiryDate"
                                                :showIcon="false" :placeholder="'Seleccione'"
                                                :disabled="infoToShow.status != 1" />
                                            <p v-else class="font-[MuseoSans] text-[12px] p-1 ">N/A</p>
                                        </div>
                                    </div>

                                    <div class="relative w-full flex items-center justify-center border-r border-gray-500 min-h-[75px]"
                                        :class="((errors['prods.' + indexLt + '.productos.'+ index + '.qty'] && (!prod.qty || prod.qty <= 0)) || (showAvails(prod.prodId, indexLt, index, recDocument.isGas) < 0 && !recDocument.isGas)) ? 'bg-red-300' : ''">
                                        <!-- Aquí se colocará el número dinámicamente -->
                                        <span v-if="infoToShow.status == 1 && !(recDocument.isGas)"
                                            class="absolute font-[MuseoSans] text-[12px] top-1 flex items-center justify-center">REST:
                                            {{ showAvails(prod.prodId, indexLt, index, recDocument.isGas) }}</span>

                                        <!-- El input -->
                                        <input v-model="prod.qty" :disabled="infoToShow.status != 1"
                                            class="font-bold max-w-[95%] p-0 text-center h-[35px] rounded-[4px] font-[MuseoSans] text-[13px] border-[#d1d5db] hover:border-gray-400 transition duration-300 ease-in-out"
                                            type="text" name="" id=""
                                            @input="handleValidation('qty', prod.fractionated ? { limit: 7, amount: true } : { limit: 4, number: true }, { indexLt: indexLt, index: index })">
                                    </div>

                                    <div
                                        class="w-full flex items-center justify-center border-r border-gray-500 min-h-[75px]">
                                        <p class="font-[MuseoSans] text-[13px] p-1 ">
                                            ${{ prod.cost }}
                                        </p>
                                    </div>

                                    <!-- Gas -->
                                    <div v-if="recDocument.isGas" class="relative w-full flex items-center justify-center min-h-[75px]"
                                        :class="(((errors['prods.' + indexLt + '.productos.'+ index +'.total'] && !prod.total) || showAvails(prod.prodId, indexLt, index, recDocument.isGas) < 0)) ? 'bg-red-300' : ''">
                                        <!-- Aquí se colocará el número dinámicamente -->
                                        <span v-if="infoToShow.status == 1"
                                            class="absolute font-[MuseoSans] text-[12px] top-1 flex items-center justify-center">
                                            ${{ showAvails(prod.prodId, indexLt, index, recDocument.isGas) }}</span>

                                        <!-- Input -->
                                        <input v-model="prod.total" :disabled="infoToShow.status != 1"
                                            class="font-bold max-w-[95%] p-0 text-center h-[35px] rounded-[4px] font-[MuseoSans] text-[13px] border-[#d1d5db] hover:border-gray-400 transition duration-300 ease-in-out"
                                            type="text" name="" id=""
                                            @input="handleValidation('total', { limit: 8, amount: true }, { indexLt: indexLt, index: index })">
                                    </div>

                                    <div v-else class="w-full flex items-center justify-end min-h-[75px]">
                                        <p class="font-[MuseoSans] text-[13px] p-1 font-bold">
                                            {{ prod.total != '' ? '$' + prod.total : '' }}
                                        </p>
                                    </div>
                                </div>
                                <div class="w-full flex items-center justify-center">
                                    <div class="max-w-full h-[30px]">
                                        <svg v-if="infoToShow.status == 1" @click="deleteRow(indexLt, index, prod.detRecId)"
                                            class="text-red-600 cursor-pointer ml-1 hover:text-red-800" width="20px"
                                            height="20px" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round"
                                                stroke-linejoin="round">
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
                    </template>
                    <div v-if="recDocument.prods.length == 0" class="min-w-[970px]">
                        <div
                            class="flex items-center bg-white justify-center max-w-[96%] border-x border-b border-gray-500 py-5">
                            <p class="font-[MuseoSans] text-[12px] text-red-500 font-semibold"> SIN PRODUCTOS
                                SELECCIONADOS</p>
                        </div>
                    </div>
                    <div id="total" class="w-full max-w-full grid grid-cols-[96%_4%] min-w-[970px] bg-white">
                        <div class="grid grid-cols-[88%_12%] w-full max-w-full border-b border-x border-gray-500">
                            <div class="flex items-center justify-end border-r h-[30px]  border-gray-500">
                                <p class="font-[MuseoSans] text-[12px] py-2 mr-2 font-bold">TOTAL ACTA</p>
                            </div>
                            <div class="flex items-center justify-end h-[30px] ">
                                <p class="font-[MuseoSans] text-[13px] py-2 font-bold text-green-800 mr-1">${{ totalRec
                                    }}
                                </p>
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="w-full h-[30px]">
                                <svg @click="returnToTop" xmlns="http://www.w3.org/2000/svg"
                                    class="w-6 h-6 text-blue-700 inline rotate-180 cursor-pointer" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M10 3a1 1 0 0 1 1 1v10.586l2.293-2.293a1 1 0 1 1 1.414 1.414l-4 4a1 1 0 0 1-1.414 0l-4-4a1 1 0 0 1 1.414-1.414L9 14.586V4a1 1 0 0 1 1-1z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="min-w-[970px]">
                        <div class="grid grid-cols-[100%] max-w-[96%] border-x border-gray-500 bg-white">
                            <p class="ml-3 text-[13px] font-[Roboto] text-gray-500">Observación sobre recepción:</p>
                        </div>
                    </div>
                    <div id="observ" class="min-w-[970px]">
                        <div class="grid grid-cols-[100%] max-w-[96%] border-x border-b border-gray-500">
                            <div class="justify-center flex w-full bg-white">
                                <textarea v-model="recDocument.observation" placeholder=""
                                    :disabled="infoToShow.status != 1"
                                    class="w-full text-[12px] py-1 font-[Roboto] h-full outline-none ring-0 border-transparent focus:outline-none focus:ring-0 focus:border-transparent leading-4"
                                    @input="handleValidation('observation', { limit: 255 })"></textarea>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="md:flex flex md:items-center my-6 sticky flex-row justify-center mx-8">
                    <button type="button" @click="$emit('cerrar-modal')"
                        class="mr-2 text-gray-600 hover:text-white border border-gray-600 hover:bg-gray-700 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-[12px] px-2.5 py-1.5 text-center mb-2 dark:border-gray-500 dark:text-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-800">CANCELAR</button>
                    <div class="" v-if="infoToShow.status == 1 && activeDetails.length > 0">
                        <button v-if="recepId > 0" @click="updateReception(recDocument)"
                            class="bg-orange-700 hover:bg-orange-800 text-white font-medium text-[12px] px-2.5 py-1.5 rounded-lg mr-1.5 mb-2">ACTUALIZAR</button>
                        <button v-else @click="storeReception(recDocument)"
                            class="bg-green-700 hover:bg-green-800 text-white font-medium text-[12px] px-2.5 py-1.5 rounded-lg mr-1.5 mb-2">GUARDAR</button>
                    </div>
                </div>
            </div>
        </ProcessModal>
    </div>
</template>

<script>
import { useRecepcion } from '@/Composables/Almacen/Recepcion/useRecepcion.js';
import InputError from "@/Components/InputError.vue";
import ProcessModal from '@/Components-ISRI/AllModal/ProcessModal.vue'
import IconM from "@/Components-ISRI/ComponentsToForms/IconM.vue";
import DateTimePickerM from "@/Components-ISRI/ComponentsToForms/DateTimePickerM.vue";

import { toRefs, onMounted } from 'vue';

export default {
    emits: ["cerrar-modal", "get-table"],
    components: { ProcessModal, InputError, IconM, DateTimePickerM },
    props: {
        showModalRecep: {
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
            isLoadingRequest, recDocument, errors, activeDetails,
            documents, ordenC, contrato, docSelected, products, brands, months,
            filteredDoc, filteredItems, startRec, filteredProds, totalRec, infoToShow,
            getInfoForModalRecep, startReception, setProdItem, calculateLtTotal, checkBlinkingClass,
            deleteRow, handleValidation, storeReception, updateReception, showAvails, returnToTop, 
            hasActiveProds
        } = useRecepcion(context);

        onMounted(
            async () => {
                await getInfoForModalRecep(recepId.value)
            }
        )

        return {
            isLoadingRequest, recDocument, errors, activeDetails, months,
            documents, ordenC, contrato, docSelected, totalRec, products, brands,
            filteredDoc, filteredItems, startRec, filteredProds, infoToShow,
            handleValidation, startReception, setProdItem, calculateLtTotal, checkBlinkingClass,
            deleteRow, storeReception, updateReception, showAvails, returnToTop, 
            hasActiveProds
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

@keyframes blink {
    0% {
        background-color: transparent;
    }

    50% {
        background-color: rgb(251 146 60);
    }

    100% {
        background-color: transparent;
    }
}

.blinking {
    animation: blink 1s infinite;
}

.dp__input_wrap {
    height: 40px;
}

.dp__theme_light {
    /* I've edited this */
    --dp-primary-color: rgba(0, 28, 72, 0.8);
    --dp-cell-size: 25px;
    --dp-font-size: 0.8rem;
}
</style>