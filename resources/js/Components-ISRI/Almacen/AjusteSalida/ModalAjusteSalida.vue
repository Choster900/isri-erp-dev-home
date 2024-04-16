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

                <div class="flex items-center justify-between pt-2 pb-1 w-[96%]  pl-8">
                    <div class="flex items-center">
                        <span class="text-[14px] text-slate-700 font-medium font-[Roboto] underline">INFORMACION
                            GENERAL</span>
                    </div>
                    <div v-if="adjustment.id"
                        class="border border-slate-400 py-1 text-slate-700 px-2 rounded-xl hover:text-white hover:bg-slate-700">
                        <span class="text-[14px] font-medium text-center font-[Roboto]">{{ adjustment.number }}</span>
                    </div>
                </div>

                <div class="mb-2 mt-1 md:flex flex-row justify-items-start w-[96%] pl-8">
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

                <div class="flex items-center justify-between pt-2 pb-1.5 w-[96%] pl-8">
                    <div class="flex items-center">
                        <span class="text-[14px] text-slate-700 font-medium font-[Roboto] underline">CRITERIOS DE
                            BUSQUEDA PARA PRODUCTOS</span>
                    </div>
                </div>

                <div class="mb-4 pb-3 mt-1 md:flex flex-row justify-items-start max-w-[96%] pl-8">
                    <div class="mb-2 md:mr-2 md:mb-0 basis-1/4" :class="{ 'selected-opt': adjustment.centerId > 0, }">
                        <div class="relative font-semibold flex h-[35px] w-full">
                            <Multiselect v-model="adjustment.centerId" :options="centers" :searchable="true"
                                :noOptionsText="'Lista vacía.'" :placeholder="products.length > 0 ? '' : 'Seleccione centro'"
                                :disabled="adjustment.status != 1 || products.length > 0"
                                :classes="{ optionSelected: 'text-white bg-[#001c48] bg-opacity-80', optionSelectedPointed: 'text-white bg-[#001c48] opacity-90', noOptions: 'py-2 px-3 text-[12px] text-gray-600 bg-white text-left rtl:text-right', search: 'w-full absolute uppercase inset-0 outline-none focus:ring-0 appearance-none box-border border-0 text-base font-sans bg-white rounded pl-3.5 rtl:pl-0 rtl:pr-3.5', optionPointed: 'text-white bg-[#001c48] bg-opacity-40', }" />
                        </div>
                        <InputError v-for="(item, index) in errors.centerId" :key="index" class="mt-2"
                            :message="item" />
                    </div>
                    <div class="mb-2 md:mr-2 md:mb-0 basis-1/4"
                        :class="{ 'selected-opt': adjustment.financingSourceId > 0, }">
                        <div class="relative font-semibold flex h-[35px] w-full">
                            <Multiselect v-model="adjustment.financingSourceId" :options="financingSources"
                                :searchable="true" :noOptionsText="'Lista vacía.'" :placeholder="products.length > 0 ? '' : 'Seleccione fuente'"
                                :disabled="adjustment.status != 1 || products.length > 0"
                                :classes="{ optionSelected: 'text-white bg-[#001c48] bg-opacity-80', optionSelectedPointed: 'text-white bg-[#001c48] opacity-90', noOptions: 'py-2 px-3 text-[12px] text-gray-600 bg-white text-left rtl:text-right', search: 'w-full absolute uppercase inset-0 outline-none focus:ring-0 appearance-none box-border border-0 text-base font-sans bg-white rounded pl-3.5 rtl:pl-0 rtl:pr-3.5', optionPointed: 'text-white bg-[#001c48] bg-opacity-40', }" />
                        </div>
                        <InputError v-for="(item, index) in errors.financingSourceId" :key="index" class="mt-2"
                            :message="item" />
                    </div>
                    <div class="mb-2 md:mr-2 md:mb-0 basis-1/4" :class="{ 'selected-opt': adjustment.idLt > 0, }">
                        <div class="relative font-semibold flex h-[35px] w-full">
                            <Multiselect v-model="adjustment.idLt" :options="lts" :searchable="true"
                                :noOptionsText="'Lista vacía.'" :placeholder="products.length > 0 ? 'Desactivado' : 'Seleccione uplt'"
                                :disabled="adjustment.status != 1 || products.length > 0"
                                :classes="{ optionSelected: 'text-white bg-[#001c48] bg-opacity-80', optionSelectedPointed: 'text-white bg-[#001c48] opacity-90', noOptions: 'py-2 px-3 text-[12px] text-gray-600 bg-white text-left rtl:text-right', search: 'w-full absolute uppercase inset-0 outline-none focus:ring-0 appearance-none box-border border-0 text-base font-sans bg-white rounded pl-3.5 rtl:pl-0 rtl:pr-3.5', optionPointed: 'text-white bg-[#001c48] bg-opacity-40', }" />
                        </div>
                        <InputError v-for="(item, index) in errors.idLt" :key="index" class="mt-2" :message="item" />
                    </div>
                    <div v-if="showSearchButton"
                        class="mb-2 md:mr-2 md:mb-0 basis-1/4 flex justify-center items-center ">
                        <div class="text-indigo-600 flex hover:text-indigo-800" @click="searchProducts()">
                            <svg width="30px" height="30px" viewBox="0 0 24 24" fill="none" class="cursor-pointer"
                                xmlns="http://www.w3.org/2000/svg">
                                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                <g id="SVGRepo_iconCarrier">
                                    <path
                                        d="M3 12C3 4.5885 4.5885 3 12 3C19.4115 3 21 4.5885 21 12C21 19.4115 19.4115 21 12 21C4.5885 21 3 19.4115 3 12Z"
                                        stroke="currentColor" stroke-width="2"></path>
                                    <path d="M14 14L16 16" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round"></path>
                                    <path
                                        d="M15 11.5C15 13.433 13.433 15 11.5 15C9.567 15 8 13.433 8 11.5C8 9.567 9.567 8 11.5 8C13.433 8 15 9.567 15 11.5Z"
                                        stroke="currentColor" stroke-width="2"></path>
                                </g>
                            </svg>
                            <span
                                class="mt-1.5 pl-1 cursor-pointer font-[Roboto] text-[13px] font-semibold">BUSCAR</span>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between w-[96%] pl-8 pb-2 mb-0">
                    <div class="flex items-center">
                        <span class="text-[14px] text-slate-700 font-medium font-[Roboto] underline">PRODUCTOS</span>
                    </div>
                </div>

                <div v-if="isLoadingProds" class="flex items-center justify-center py-10">
                    <img src="../../../../img/loader-spinner.gif" alt="" class="w-8 h-8">
                    <h1 class="ml-4 font-medium text-xl text-[#001c48]">Cargando...</h1>
                </div>

                <div v-else-if="load === 0" class="flex items-center justify-center py-4">
                    <div class="bg-blue-100 border-l-4 border-blue-400 text-blue-600 p-4 rounded-md" role="alert">
                        <div class="flex">
                            <div class="py-1">
                                <svg class="fill-current h-6 w-6 mr-4" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20">
                                    <path d="M10 2a8 8 0 100 16 8 8 0 000-16zM9 12V10h2v4H9v-2zm0-4V5h2v3H9z" />
                                </svg>
                            </div>
                            <div>
                                <p class="font-normal mt-1 font-[Roboto]">Por favor, seleccione los criterios de
                                    búsqueda y presione
                                    buscar.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div v-else-if="products.length === 0" class="flex items-center justify-center py-10">
                    <div class="flex border border-gray-400 rounded-lg mt-1 mx-auto w-[50%] bg-orange-50">
                        <div class="border-r border-gray-400 w-[15%] flex items-center justify-center py-2">
                            <svg class="text-orange-400 animate-bounce-svg" fill="currentColor" width="40px"
                                height="40px" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"
                                stroke="currentColor">
                                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                <g id="SVGRepo_iconCarrier">
                                    <g>
                                        <path
                                            d="M19.79,16.72,11.06,1.61A1.19,1.19,0,0,0,9,1.61L.2,16.81C-.27,17.64.12,19,1.05,19H19C19.92,19,20.26,17.55,19.79,16.72ZM11,17H9V15h2Zm0-4H9L8.76,5h2.45Z">
                                        </path>
                                    </g>
                                </g>
                            </svg>
                        </div>
                        <div class="w-[85%] rounded-r-lg text-[14px]">
                            <h2 class="text-orange-500 text-center font-semibold font-[Roboto]">¡Sin resultados!</h2>
                            <p class="mx-2 mb-1 font-[Roboto]">No se han encontrado productos disponibles con base a los
                                criterios de
                                busqueda
                                proporcionados, por favor cambie los criterios e intente nuevamente.</p>
                        </div>
                    </div>
                </div>


                <div v-else class="w-full pl-8 max-w-full mt-0 pb-2">
                    <div class="pb-1 pt-0 md:flex flex-row justify-items-start w-[96%] pl-0">
                        <div class="mb-2 md:mr-2 md:mb-0 basis-full flex justify-end items-center ">
                            <div class="text-orange-500 flex hover:text-orange-700" @click="resetProducts()">
                                <svg width="26px" height="26px" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                    <g id="SVGRepo_iconCarrier">
                                        <path
                                            d="M2 12C2 7.28595 2 4.92893 3.46447 3.46447C4.92893 2 7.28595 2 12 2C16.714 2 19.0711 2 20.5355 3.46447C22 4.92893 22 7.28595 22 12C22 16.714 22 19.0711 20.5355 20.5355C19.0711 22 16.714 22 12 22C7.28595 22 4.92893 22 3.46447 20.5355C2 19.0711 2 16.714 2 12Z"
                                            stroke="currentColor" stroke-width="2"></path>
                                        <path
                                            d="M15.9775 8.71452L15.5355 8.2621C13.5829 6.26318 10.4171 6.26318 8.46447 8.2621C6.51184 10.261 6.51184 13.5019 8.46447 15.5008C10.4171 17.4997 13.5829 17.4997 15.5355 15.5008C16.671 14.3384 17.1462 12.7559 16.9611 11.242M15.9775 8.71452H13.3258M15.9775 8.71452V6"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round"></path>
                                    </g>
                                </svg>

                                <span class="mt-1 pl-1 cursor-pointer font-[Roboto] font-semibold text-[13px]">REINICIAR
                                    PRODUCTOS</span>
                            </div>
                        </div>
                    </div>
                    <div class="min-w-[970px]">
                        <div class="grid grid-cols-[100%] max-w-[96%]  border border-gray-500">
                            <div class="justify-center flex w-full bg-white">
                                <p class="font-[MuseoSans] text-[12px] py-1 font-bold">LISTADO DE PRODUCTOS</p>
                            </div>
                        </div>
                    </div>

                    <div class="min-w-[970px]">
                        <div
                            class="grid grid-cols-[55%_15%_15%_15%] max-w-[96%] bg-[#001c48] border-b border-x border-gray-500 bg-opacity-80 text-white">
                            <!-- Table header -->
                            <div class="border-r border-gray-500 flex items-center justify-center py-1">
                                <p class="font-[MuseoSans] text-[11px]">PRODUCTO</p>
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
                                class="grid grid-cols-[55%_15%_15%_15%] max-w-full bg-white hover:bg-gray-200 text-gray-800 border-b border-x border-gray-500">
                                <div class="border-r border-gray-500 min-h-[75px] flex items-center justify-center"
                                    :class="errors['prods.' + index + '.prodId'] ? 'bg-red-300' : ''">
                                    <div class="max-w-[98%] w-full">
                                        <!-- Select for products -->
                                        <Multiselect id="doc" v-model="prod.detId" :options="nonSelectedProds" class="h-[35px]"
                                            :disabled="adjustment.status != 1" :searchable="true"
                                            @change="selectProd($event, index)" :noOptionsText="'Sin resultados'"
                                            placeholder="Buscar producto"
                                            :classes="{ optionSelected: 'text-white bg-[#001c48] bg-opacity-80', optionSelectedPointed: 'text-white bg-[#001c48] opacity-90', noOptions: 'py-2 px-3 text-[12px] text-gray-600 bg-white text-left rtl:text-right', search: 'w-full absolute uppercase inset-0 outline-none focus:ring-0 appearance-none box-border border-0 text-base font-sans bg-white rounded pl-3.5 rtl:pl-0 rtl:pr-3.5', optionPointed: 'text-white bg-[#001c48] bg-opacity-40', }" />
                                    </div>
                                </div>
                                <div class="relative w-full flex items-center justify-center border-r border-gray-500 min-h-[75px]"
                                    :class="(errors['prods.' + index + '.qty'] || prod.avails < 0) ? 'bg-red-300' : ''">
                                    <!-- Aquí se colocará el número dinámicamente -->
                                    <span
                                        class="absolute font-[MuseoSans] text-[12px] top-1 flex items-center justify-center">STOCK:
                                        {{ prod.avails }}</span>
                                    <!-- El input -->
                                    <input v-model="prod.qty" :disabled="adjustment.status != 1"
                                        class="font-bold max-w-[95%] p-0 text-center h-[35px] rounded-[4px] font-[MuseoSans] text-[13px] border-[#d1d5db] hover:border-gray-400 transition duration-300 ease-in-out"
                                        @input="handleValidation('qty', { limit: 3, number: true }, { index: index })"
                                        type="text" name="" id="">
                                </div>
                                <div class="border-r border-gray-500 min-h-[75px] flex items-center justify-center">
                                    <p
                                        class="font-[MuseoSans] inline-block max-w-full text-ellipsis overflow-hidden text-[13px] p-1 font-bold">
                                        {{ prod.cost != '' ? '$' + prod.cost : '' }}
                                    </p>
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
                                    <svg v-if="adjustment.status == 1" @click="deleteRow(index, prod.id)"
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

                    <div id="total" class="w-full max-w-full grid grid-cols-[96%_4%] min-w-[970px] ">
                        <div
                            class="grid grid-cols-[85%_15%] w-full max-w-full border-b border-x border-gray-500 bg-white hover:bg-gray-200">
                            <div class="text-right border-r h-[30px] border-gray-500">
                                <p class="font-[MuseoSans] text-[12px] py-2 mr-2 font-bold mt-[-3px]">TOTAL AJUSTE (-)
                                </p>
                            </div>
                            <div class="text-center h-[30px] ">
                                <p
                                    class="font-[MuseoSans] text-[13px] inline-block max-w-full text-ellipsis overflow-hidden py-2 font-bold text-red-800 mt-[-3px]">
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

                    <div class="min-w-[970px] bg-white">
                        <div class="grid grid-cols-[100%] max-w-[96%] border-x border-gray-500">
                            <p class="ml-3 text-[13px] font-[Roboto] text-gray-500">Observación sobre ajuste:</p>
                        </div>
                    </div>
                    <div id="observ" class="min-w-[970px] bg-white ">
                        <div class="grid grid-cols-[100%] max-w-[96%] border-x border-b border-gray-500">
                            <div class="justify-center flex w-full bg-white">
                                <textarea v-model="adjustment.observation" placeholder=""
                                    class="w-full text-[12px] py-1 font-[Roboto] h-full  outline-none ring-0 border-transparent focus:outline-none focus:ring-0 focus:border-transparent leading-4"></textarea>
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
import { useAjusteSalida } from '@/Composables/Almacen/AjusteSalida/useAjusteSalida.js';
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
            isLoadingRequest, errors, adjustment, reasons, centers, financingSources, lts, products,
            nonSelectedProds, brands, asyncFindProduct, totalRec, isLoadingProds, load, showSearchButton,
            getInfoForModalAdjustment, selectProd, deleteRow, addNewRow, storeAdjustment, updateAdjustment,
            searchProducts, resetProducts, handleValidation
        } = useAjusteSalida(context);

        const handleSearchChange = async (query, index, prodId) => {
            await asyncFindProduct(query, index, prodId);
        }

        onMounted(
            async () => {
                await getInfoForModalAdjustment(objId.value)
            }
        )

        return {
            isLoadingRequest, errors, adjustment, reasons, centers, financingSources, lts, products,
            nonSelectedProds, brands, asyncFindProduct, totalRec, isLoadingProds, load, showSearchButton,
            selectProd, handleSearchChange, deleteRow, addNewRow, storeAdjustment, updateAdjustment,
            searchProducts, resetProducts, handleValidation
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

@keyframes bounce-svg {
    0% {
        transform: translateY(0);
    }

    100% {
        transform: translateY(-10px);
    }
}

.animate-bounce-svg {
    animation: bounce-svg 0.5s infinite alternate;
}
</style>