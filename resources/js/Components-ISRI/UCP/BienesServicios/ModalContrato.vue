<template>
    <template>
        <div class="m-1.5">
            <!-- Componente del modal ProcessModal -->
            <ProcessModal maxWidth="8xl" :show="showModal" @close="$emit('cerrar-modal')">
                <div class=" flex  justify-center pt-2 content-between">
                    <svg class="h-7 w-7 absolute top-0 right-0 mt-2 cursor-pointer" viewBox="0 0 25 25"
                        @click="$emit('cerrar-modal')">
                        <path fill="currentColor"
                            d="M7.95 6.536l4.242-4.243a1 1 0 111.415 1.414L9.364 7.95l4.243 4.242a1 1 0 11-1.415 1.415L7.95 9.364l-4.243 4.243a1 1 0 01-1.414-1.415L6.536 7.95 2.293 3.707a1 1 0 011.414-1.414L7.95 6.536z" />
                    </svg>

                </div>
                <div class="mx-6 mt-2"></div>
                <div class="mx-4 overflow-y-auto h-[650px] py-3 pl-2 pr-4 mt-4">
                    <table class="w-full sheet0 " border="0" cellpadding="0" cellspacing="0">
                        <col class="col0">
                        <col class="col1">
                        <col class="col2">
                        <col class="col3">
                        <col class="col4">
                        <col class="col5">
                        <col class="col6">
                        <col class="col7">
                        <tbody>
                            <tr>
                                <td class="border border-black w-40" rowspan="2">
                                    <div class="w-40 p-1.5">
                                        <img src="../../../../img/isri-gob.png" alt="logotipo-isri" draggable="false">
                                    </div>
                                </td>
                                <td class="border text-sm border-black text-center font-medium uppercase" colspan="3">

                                    <div class="flex items-center justify-center">
                                        <div class="relative text-center text-sm">
                                            UCP DEL INSITUTO SALVADOREÑO DE REHABILITACION INTEGRAL
                                        </div>
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" @click="printPdf"
                                            class="w-5 h-5 ml-2 stroke-slate-400 hover:stroke-slate-500 cursor-pointer">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5zm-3 0h.008v.008H15V10.5z" />
                                        </svg>

                                        <svg class="w-5 h-5 ml-2 stroke-slate-400 hover:stroke-slate-500 cursor-pointer"
                                            viewBox="0 0 24 24" @click="exportDocumentToExcel" fill="none" xmlns="http://www.w3.org/2000/svg"
                                            stroke="currentColor" stroke-width="0.6">
                                            <path
                                                d="M9.29289 1.29289C9.48043 1.10536 9.73478 1 10 1H18C19.6569 1 21 2.34315 21 4V9C21 9.55228 20.5523 10 20 10C19.4477 10 19 9.55228 19 9V4C19 3.44772 18.5523 3 18 3H11V8C11 8.55228 10.5523 9 10 9H5V20C5 20.5523 5.44772 21 6 21H7C7.55228 21 8 21.4477 8 22C8 22.5523 7.55228 23 7 23H6C4.34315 23 3 21.6569 3 20V8C3 7.73478 3.10536 7.48043 3.29289 7.29289L9.29289 1.29289ZM6.41421 7H9V4.41421L6.41421 7ZM19 12C19.5523 12 20 12.4477 20 13V19H23C23.5523 19 24 19.4477 24 20C24 20.5523 23.5523 21 23 21H19C18.4477 21 18 20.5523 18 20V13C18 12.4477 18.4477 12 19 12ZM11.8137 12.4188C11.4927 11.9693 10.8682 11.8653 10.4188 12.1863C9.96935 12.5073 9.86526 13.1318 10.1863 13.5812L12.2711 16.5L10.1863 19.4188C9.86526 19.8682 9.96935 20.4927 10.4188 20.8137C10.8682 21.1347 11.4927 21.0307 11.8137 20.5812L13.5 18.2205L15.1863 20.5812C15.5073 21.0307 16.1318 21.1347 16.5812 20.8137C17.0307 20.4927 17.1347 19.8682 16.8137 19.4188L14.7289 16.5L16.8137 13.5812C17.1347 13.1318 17.0307 12.5073 16.5812 12.1863C16.1318 11.8653 15.5073 11.9693 15.1863 12.4188L13.5 14.7795L11.8137 12.4188Z"
                                                fill="#94a3b8"></path>
                                        </svg>
                                    </div>
                                </td>
                            </tr>


                            <tr>
                                <td class="border text-sm border-black text-center font-medium px-8" colspan="3">
                                    <div class="flex items-center justify-center gap-1">
                                        <div class="relative text-center text-sm">
                                            ORDEN DE COMPRA DE BIENES Y SERVICIOS
                                        </div>
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td class="border border-black  bg-black h-5 border-r-white text-white text-center text-[9pt] "
                                    colspan="3">
                                    RAZON SOCIAL DEL SUMINISTRANTE
                                </td>
                                <td class="border border-black bg-black h-5 text-white text-center text-[9pt]">
                                    {{ documentType }}
                                </td>
                            </tr>
                            <tr>
                                <td class="border border-black h-5" colspan="3">
                                    <h1 class="text-xs font-medium text-center">
                                        {{ providerBusinessName }}
                                    </h1>
                                </td>
                                <td class="border border-black  h-5  text-center text-[9pt] ">
                                    <h1 class="text-xs font-medium text-center">
                                        {{ documentNumber }}
                                    </h1>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div id="especificaciones" class="mb-4">
                        <div class="flex items-center justify-start gap-4 pt-4 ">
                            <div class="flex items-center" v-if="propProdAdquisicion == ''">
                                <label for="" class="text-[7pt] font-semibold mr-2">LUGAR Y FECHA: </label>
                                <input type="text" :value="`SAN SALVADOR ${moment().format('D [de] MMMM [de] YYYY')}`"
                                    class="w-96 text-[7pt] font-medium uppercase h-5 border-x-0 border-t-0">
                            </div>
                            <div class="flex items-center" v-else>
                                <label for="" class="text-[7pt] font-semibold mr-2">LUGAR Y FECHA: </label>
                                <input type="text"
                                    :value="`SAN SALVADOR ${moment(propProdAdquisicion.fecha_reg_det_doc_adquisicion).format('D [de] MMMM [de] YYYY')}`"
                                    class="w-96 text-[7pt] font-medium uppercase h-5 border-x-0 border-t-0">

                            </div>
                            <div class="flex items-center">
                                <label for="" class="text-[7pt] font-semibold mr-2">N°.ORDEN:</label>
                                <input type="text"
                                    :value="((arrayDocAdquisicion.find(index => index.value == idDetDocAdquisicion) || {}).dataDoc || {}).documento_adquisicion?.numero_doc_adquisicion || '-'"
                                    class="w-40 text-left text-[7pt] font-medium capitalize h-5 border-x-0 border-t-0">
                            </div>
                        </div>
                    </div>
                    <table class="w-full" border="0" cellpadding="0" cellspacing="0">
                        <template v-for="(docAdq, i) in arrayProductoAdquisicion" :key="i">

                            <thead>

                                <tr
                                    class="*:text-[8pt] *:bg-black *:text-white *:px-2 *:py-0.5 *:font-normal *:border--white">
                                    <th class="border border-black border-r-white border-b-white h-9 w-[300px]">PRODUCTO
                                    </th>
                                    <th class="border border-black border-r-white border-b-white w-[20px]">MARCA</th>
                                    <th class="border border-black border-r-white  border-b-white w-72">
                                        DESCRIPCIÓN</th>
                                    <th class="border border-black border-r-white border-b-white">CENTRO</th>
                                    <th class="border border-black border-r-white border-b-white">CANTIDAD</th>
                                    <th class="border border-black border-r-white border-b-white">PRECIO UNITARIO</th>
                                    <th class="border border-black border-b-white border-r-white">VALOR TOTAL</th>
                                    <th class="border border-black border-l-white border-b-white h-9 w-[400px]"
                                        rowspan="1">
                                        DISTRIBUCION MENSUAL DEL PRODUCTO ( SOLO NUMEROS )
                                    </th>
                                </tr>

                                <tr class="*:border-black cursor-pointer"
                                    :class="{ 'custom-pulse': docAdq.hoverToDelete }">
                                    <td colspan="3" @click="docAdq.vShowLt = !docAdq.vShowLt"
                                        @mouseover=" docAdq.hoverToDelete = true"
                                        @mouseout=" docAdq.hoverToDelete = false"
                                        @contextmenu.prevent="estadoDocAdq !== 1 ? '' : deletLineaTrabajo(i)"
                                        class="uppercase border bg-black text-[8pt] text-white border-black border-t-white border-r-white text-center text-selection-disable">

                                        Linea de trabajo:</td>
                                    <td colspan="5" @mouseover="docAdq.hoverToDelete = true"
                                        @click="docAdq.vShowLt = !docAdq.vShowLt"
                                        @mouseout="docAdq.hoverToDelete = false"
                                        @contextmenu.prevent="deletLineaTrabajo(i)"
                                        class="uppercase border bg-black text-[8pt] text-white border-black border-l-white text-center text-selection-disable">
                                        Documento de adquiicion: </td>
                                </tr>
                                <tr class="*:border-black cursor-pointer"
                                    :class="{ 'custom-pulse': docAdq.hoverToDelete }">
                                    <td colspan="3" class="border border-t-black ">
                                        <Multiselect :filter-results="false" :searchable="true" :clear-on-search="true"
                                            :canClear="false" :canDeselect="true" :disabled="estadoDocAdq !== 1"
                                            @select="disableLt($event)" v-model="docAdq.idLt" :min-chars="1"
                                            placeholder="-" noOptionsText="<p class='text-xs'>Lista vacía</p>"
                                            noResultsText="<p class='text-xs'>Sin resultados de personas</p>"
                                            :options="arrayLineaTrabajo"
                                            :classes="{ containerDisabled: ' bg-gray-200 text-text-slate-400', optionSelectedDisabled: 'text-white bg-[#001c48] bg-opacity-50 cursor-not-allowed', optionPointed: 'text-gray-800 bg-gray-100', container: `relative mx-auto w-full h-6 flex items-center justify-end box-border   border border-gray-300 rounded bg-white text-base leading-snug outline-none ${[errorsValidation[`productAdq.${i}.idLt`] ? 'bg-red-500' : ''], [estadoDocAdq !== 1 ? 'bg-slate-500/20' : '']}`, placeholder: `flex items-center text-center h-full absolute left-0 top-0 pointer-events-none leading-snug pl-3.5 text-gray-400 rtl:left-auto rtl:right-0 rtl:pl-0 rtl:pr-3.5 ${errorsValidation[`productAdq.${i}.idLt`] ? 'bg-red-500' : ''}`, search: `w-full absolute text-start inset-0 outline-none focus:ring-0 appearance-none box-border border-0 text-base font-sans rounded pl-3.5 rtl:pl-0 rtl:pr-3.5  ${errorsValidation[`productAdq.${i}.idLt`] ? 'bg-red-500' : ''}`, singleLabel: `pr-14 text-[8pt] flex items-center h-full max-w-full absolute left-0 top-0 pointer-events-none leading-snug pl-3.5 box-border rtl:left-auto rtl:right-0 rtl:pl-0 rtl:pr-3.5 ${errorsValidation[`productAdq.${i}.idLt`] ? 'bg-red-500' : ''}`, option: 'flex items-center justify-start box-border text-left  text-[7.5pt] leading-snug py-2 px-3', optionSelected: 'text-white bg-[#001c48]', optionDisabled: 'text-gray-300 cursor-not-allowed', wrapper: `relative mx-auto w-full flex items-center justify-end box-border  outline-none ${estadoDocAdq !== 1 ? 'cursor-not-allowed' : ''}`, }" />
                                    </td>
                                    <td colspan="5" class="border border-black">
                                        <Multiselect :filter-results="true" :searchable="true" :clear-on-search="true"
                                            @select="onSelectDocAdquisicion($event)" v-model="idDetDocAdquisicion"
                                            placeholder="-" :min-chars="1"
                                            :classes="{ container: `relative mx-auto w-full h-6 flex items-center justify-end box-border cursor-pointer border border-gray-300 rounded bg-white text-base leading-snug outline-none ${errorsValidation.idDetDocAdquisicion ? 'bg-red-500' : ''}`, placeholder: `flex items-center text-center h-full absolute left-0 top-0 pointer-events-none leading-snug pl-3.5 text-gray-400 rtl:left-auto rtl:right-0 rtl:pl-0 rtl:pr-3.5 ${errorsValidation.idDetDocAdquisicion ? 'bg-red-500' : ''}`, search: `w-full absolute text-start inset-0 outline-none focus:ring-0 appearance-none box-border border-0 text-base font-sans rounded pl-3.5 rtl:pl-0 rtl:pr-3.5 ${errorsValidation.idDetDocAdquisicion ? 'bg-red-500' : ''}`, singleLabel: `pr-14 text-[8pt] flex items-center h-full max-w-full absolute left-0 top-0 pointer-events-none leading-snug pl-3.5 box-border rtl:left-auto rtl:right-0 rtl:pl-0 rtl:pr-3.5 ${errorsValidation.idDetDocAdquisicion ? 'bg-red-500' : ''}`, option: 'flex items-center justify-start box-border text-left cursor-pointer text-[7.5pt] leading-snug py-2 px-3', optionSelected: 'text-white bg-[#001c48]', optionPointed: 'text-gray-800 bg-gray-100', }"
                                            noOptionsText="<p class='text-xs'>Lista vacia</p>"
                                            noResultsText="<p class='text-xs'>Sin resultados de personas</p>"
                                            :options="arrayDocAdquisicion" />
                                    </td>
                                </tr>
                            </thead>
                            <template v-if="docAdq.vShowLt">
                                <tr class="*:text-[8pt]  *:px-2 *:py-0.5 *:font-normal *:border *:border-black  cursor-pointer"
                                    :class="{ '*:bg-emerald-100 -400/80 animate-pulse animate-infinite': docAdq.hoverToDelete && estadoDocAdq === 1, '*:bg-slate-300 -400/80 animate-pulse animate-infinite': docAdq.hoverToDelete && estadoDocAdq !== 1, 'bg-slate-200': estadoDocAdq !== 1, 'hover:bg-slate-200': estadoDocAdq === 1 }"
                                    @contextmenu.prevent="estadoDocAdq !== 1 ? '' : deleteProductAdq(docAdq.idLt, j)"
                                    v-for="(detalle, j) in docAdq.detalleDoc" :key="j"
                                    v-show="(detalle.estadoProdAdquisicion != 0)">
                                    <td class=" relative  w-28"
                                        :class="{ 'bg-red-500': errorsValidation[`productAdq.${i}.detalleDoc.${j}.idProducto`] }"
                                        style="padding-left: 0 !important; padding-right: 0 !important; ">
                                        <div class="absolute top-0 w-full flex flex-col items-center">

                                            <Multiselect :filter-results="true" :canClear="false"
                                                v-model="detalle.idProducto" :loading="loader" :searchable="true"
                                                :clear-on-search="false" @select="setInformacionProduct($event, i, j)"
                                                :classes="{ placeholder: 'flex items-center text-center h-full absolute left-0 top-0 pointer-events-none bg-transparent leading-snug pl-3.5 text-gray-400 rtl:left-auto rtl:right-0 rtl:pl-0 rtl:pr-3.5', search: 'w-full absolute text-start inset-0 outline-none focus:ring-0 appearance-none box-border border-0 text-base font-sans bg-white rounded pl-3.5 rtl:pl-0 rtl:pr-3.5 pr-3.5', container: 'relative mx-auto w-full h-7  flex items-center justify-end box-border cursor-pointer border border-gray-300 rounded bg-white text-base leading-snug outline-none', singleLabel: 'pr-16 text-[8pt] flex items-center h-full max-w-full absolute left-0 top-0 pointer-events-none bg-transparent leading-snug pl-3.5  box-border rtl:left-auto rtl:right-0 rtl:pl-0 rtl:pr-3.5', singleLabelText: 'text-center overflow-ellipsis overflow-hidden block whitespace-nowrap max-w-full', option: 'flex items-center justify-start box-border text-left cursor-pointer text-[7.5pt] leading-snug py-2 px-3', optionSelected: 'text-white bg-[#001c48]', optionPointed: 'text-gray-800 bg-gray-100', }"
                                                noOptionsText="<p class='text-xs'>Lista vacia<p>"
                                                noResultsText="<p class='text-xs'>Sin resultados de producto <p>"
                                                :options="productDataSearched" />
                                            <div class="overflow-auto text-center">
                                                <span class="">{{ detalle.detalleProducto }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class=" relative  w-32"
                                        :class="{ 'bg-red-500': errorsValidation[`productAdq.${i}.detalleDoc.${j}.idMarca`] }"
                                        style="padding-left: 0 !important; padding-right: 0 !important; ">
                                        <div class="absolute top-0 w-full flex flex-col items-center">
                                            <Multiselect :filter-results="true" :canClear="false"
                                                v-model="detalle.idMarca" :disabled="estadoDocAdq !== 1"
                                                :searchable="true" :clear-on-search="true" :min-chars="3"
                                                :classes="{ placeholder: 'flex items-center text-center h-full absolute left-0 top-0 pointer-events-none bg-transparent leading-snug pl-3.5 text-gray-400 rtl:left-auto rtl:right-0 rtl:pl-0 rtl:pr-3.5', search: 'w-full absolute text-start inset-0 outline-none focus:ring-0 appearance-none box-border border-0 text-base font-sans bg-white rounded pl-3.5 rtl:pl-0 rtl:pr-3.5 pr-3.5', container: 'relative mx-auto w-full h-7  flex items-center justify-end box-border cursor-pointer border border-gray-300 rounded bg-white text-base leading-snug outline-none', singleLabel: 'pr-16 text-[8pt] flex items-center h-full max-w-full absolute left-0 top-0 pointer-events-none bg-transparent leading-snug pl-3.5  box-border rtl:left-auto rtl:right-0 rtl:pl-0 rtl:pr-3.5', singleLabelText: 'text-center overflow-ellipsis overflow-hidden block whitespace-nowrap max-w-full', option: 'flex items-center justify-start box-border text-left cursor-pointer text-[7.5pt] leading-snug py-2 px-3', optionSelected: 'text-white bg-[#001c48]', wrapper: `relative mx-auto w-full flex items-center justify-end box-border  outline-none ${estadoDocAdq !== 1 ? 'cursor-not-allowed' : ''}`, optionPointed: 'text-gray-800 bg-gray-100', }"
                                                noOptionsText="<p class='text-xs'>Lista vacia<p>"
                                                noResultsText="<p class='text-xs'>Sin resultados de producto <p>"
                                                :options="arrayMarca" />
                                            <span class="text-center ">
                                                {{ getBrandName(detalle.idMarca) }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class=" p-0 uppercase align-top" colspan="1">
                                        <textarea v-model="detalle.descripcionProdAdquisicion" rows="9" maxlength="255"
                                            :disabled="estadoDocAdq !== 1"
                                            :class="estadoDocAdq !== 1 ? 'cursor-not-allowed' : 'cursor-pointer '"
                                            class=" uppercase text-[8pt] p-0 border-none bg-transparent outline-none focus:outline-none focus:ring focus:ring-transparent leading-tight w-full"></textarea>
                                    </td>

                                    <td class=" relative  w-32"
                                        :class="{ 'bg-red-500': errorsValidation[`productAdq.${i}.detalleDoc.${j}.idCentroAtencion`] }"
                                        style="padding-left: 0 !important; padding-right: 0 !important; ">
                                        <div class="absolute top-0 w-full flex flex-col items-center">
                                            <Multiselect :filter-results="false" :searchable="false" :canClear="false"
                                                :disabled="estadoDocAdq !== 1" v-model="detalle.idCentroAtencion"
                                                :clear-on-search="true" :min-chars="1"
                                                :classes="{ wrapper: `relative mx-auto w-full flex items-center justify-end box-border  outline-none ${estadoDocAdq !== 1 ? 'cursor-not-allowed' : ''}`, placeholder: 'flex items-center text-center h-full absolute left-0 top-0 pointer-events-none bg-transparent leading-snug pl-3.5 text-gray-400 rtl:left-auto rtl:right-0 rtl:pl-0 rtl:pr-3.5', search: 'w-full absolute text-center inset-0 outline-none focus:ring-0 appearance-none box-border border-0 text-base font-sans bg-white rounded pl-3.5 rtl:pl-0 rtl:pr-3.5', container: 'relative mx-auto w-full h-7  flex items-center justify-end box-border cursor-pointer border border-gray-300 rounded bg-white text-base leading-snug outline-none', singleLabel: 'pr-6 text-[8pt] flex items-center h-full max-w-full absolute left-0 top-0 pointer-events-none bg-transparent leading-snug pl-3.5  box-border rtl:left-auto rtl:right-0 rtl:pl-0 rtl:pr-3.5', option: 'flex items-center justify-start box-border text-left cursor-pointer text-[7.5pt] leading-snug py-2 px-3', optionSelected: 'text-white bg-[#001c48]', optionPointed: 'text-gray-800 bg-gray-100', }"
                                                noOptionsText="<p class='text-xs'>Lista vacia<p>"
                                                noResultsText="<p class='text-xs'>Sin resultados de personas <p>"
                                                :options="arrayCentroAtencion" />
                                            <span class="text-center">
                                                {{ getCenterName(detalle.idCentroAtencion) }}
                                            </span>
                                        </div>
                                    </td>

                                    <td class=" w-12 relative bg-slate-200 cursor-not-allowed"
                                        :class="{ 'bg-red-500': errorsValidation[`productAdq.${i}.detalleDoc.${j}.cantProdAdquisicion`] }">
                                        <span class="absolute top-1 left-0 w-full flex flex-col items-center ">
                                            {{ detalle.cantProdAdquisicion }}
                                        </span>
                                    </td>
                                    <td class=" relative text-center "
                                        :class="{ 'bg-red-500': errorsValidation[`productAdq.${i}.detalleDoc.${j}.costoProdAdquisicion`] }">
                                        <div class="flex justify-center space-x-1 absolute top-0 left-0 ">
                                            <input type="text" v-model="detalle.costoProdAdquisicion"
                                                :disabled="estadoDocAdq !== 1"
                                                :class="estadoDocAdq !== 1 ? 'cursor-not-allowed' : 'cursor-pointer '"
                                                @input="handleInput(i, j); detalle.costoProdAdquisicion = detalle.costoProdAdquisicion.replace(/[^\d.]/g, '').replace(/(\.\d{2})\d+$/, '$1')"
                                                class="w-full bg-transparent border-none  text-center text-[8pt] p-0 outline-none focus:outline-none focus:ring focus:ring-transparent"
                                                placeholder="0.00" min="0" max="1000" />
                                        </div>

                                    </td>
                                    <td class="relative text-center w-20 bg-slate-200 cursor-not-allowed">
                                        <span class="absolute top-1 left-0 w-full flex flex-col items-center ">
                                            {{ detalle.valorTotalProduct !== undefined ?
                detalle.valorTotalProduct.toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }) : '00.00' }}
                                        </span>
                                    </td>
                                    <!-- //!NUEVO -->
                                    <td class="relative p-0 bg-white"
                                        style="padding-left: 0 !important; padding-right: 0 !important; ">
                                        <CalendarAcuerdoComponent v-if="showModal"
                                            :dataInserted="detalle.amountsPerMonthList"
                                            @update:dataCalendar="handleDataCalendarUpdate($event, i, j)" />
                                    </td>
                                </tr>
                            </template>
                            <template v-else>
                                <tr>
                                    <td colspan="11"
                                        class="text-center border border-black cursor-pointer hover:bg-slate-200"
                                        @click="docAdq.vShowLt = true">
                                        <div class="flex flex-col items-center justify-center">
                                            <div class="text-xs py-3">Ocultando linea:
                                                <span class="font-medium">
                                                    {{ arrayLineaTrabajo.find(index => index.value ==
                docAdq.idLt)?.label || '' }}
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </template>


                            <tr v-if="estadoDocAdq == 1">
                                <td colspan="11" class="">
                                    <button style="outline: none;" @click="addingRows(i); docAdq.vShowLt = true;"
                                        class="w-full  border-4 border-dashed border-slate-300 hover:border-slate-400  my-1 py-1">
                                        <div class="flex justify-center">
                                            <span class="text-xs pt-0.5 text-slate-600">
                                                NUEVO PRODUCTO ADQUISICION EN:
                                                <br>
                                                {{ arrayLineaTrabajo.find(index => index.value == docAdq.idLt)?.label ||
                '' }}
                                            </span>
                                        </div>
                                    </button>
                                </td>
                            </tr>
                        </template>
                    </table>
                    <div v-if="estadoDocAdq == 1">
                        <button style="outline: none;" @click="addinDocAdquisicion"
                            class="w-full  border-4 border-dashed border-slate-500 hover:border-slate-800  my-3 py-10 ">
                            <div class="flex justify-center">
                                <svg fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                    class="w-6 h-6 text-slate-900">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                </svg>
                                <span class="text-sm font-medium pt-0.5 text-slate-900 ">
                                    NUEVA LINEA DE TRABAJO
                                </span>
                            </div>
                        </button>
                    </div>


                    <table class="w-full my-4 ">
                        <col style="width: 550pt;">
                        <tbody>
                            <tr class="*:border *:border-black *:hover:bg-slate-200">
                                <td class="px-1 uppercase border py-1  text-[8pt]   text-start ">
                                    <div>total</div>
                                </td>
                                <td class="uppercase border py-1 text-[8pt] font-bold text-center ">
                                    <div>$ {{ totProductos || '00.00' }}</div>
                                </td>
                            </tr>
                            <tr class="*:border *:border-black *:hover:bg-slate-200">
                                <td colspan="2" class="uppercase border py-1  text-[8pt] font-bold text-center ">
                                    <!-- <div> SON: SIETE MIL OCHENTA DOS 48/100 DOLARES</div> -->
                                    <div v-if="!loadingNumberLetter"> SON: {{ letterNumber || 'CERO 00/00 DOLAR' }}
                                    </div>
                                    <div v-else class="flex items-center justify-center h-full">
                                        <div aria-label="Loading..." role="status" class="loader">
                                            <svg class="icon" viewBox="0 0 256 256">
                                                <line x1="128" y1="32" x2="128" y2="64" stroke-linecap="round"
                                                    stroke-linejoin="round" stroke-width="24"></line>
                                                <line x1="195.9" y1="60.1" x2="173.3" y2="82.7" stroke-linecap="round"
                                                    stroke-linejoin="round" stroke-width="24"></line>
                                                <line x1="224" y1="128" x2="192" y2="128" stroke-linecap="round"
                                                    stroke-linejoin="round" stroke-width="24"></line>
                                                <line x1="195.9" y1="195.9" x2="173.3" y2="173.3" stroke-linecap="round"
                                                    stroke-linejoin="round" stroke-width="24"></line>
                                                <line x1="128" y1="224" x2="128" y2="192" stroke-linecap="round"
                                                    stroke-linejoin="round" stroke-width="24"></line>
                                                <line x1="60.1" y1="195.9" x2="82.7" y2="173.3" stroke-linecap="round"
                                                    stroke-linejoin="round" stroke-width="24"></line>
                                                <line x1="32" y1="128" x2="64" y2="128" stroke-linecap="round"
                                                    stroke-linejoin="round" stroke-width="24"></line>
                                                <line x1="60.1" y1="60.1" x2="82.7" y2="82.7" stroke-linecap="round"
                                                    stroke-linejoin="round" stroke-width="24"></line>
                                            </svg>
                                            <span class="loading-text">Cargando...</span>
                                        </div>
                                    </div>
                                </td>

                            </tr>
                            <tr class="*:border *:border-black *:hover:bg-slate-200">
                                <td class="px-1 h-auto uppercase border py-1  text-[8pt]   text-start " colspan="2">
                                    <textarea rows="8" placeholder="OBSERVACION: "
                                        v-model="observacionDetDocAdquisicion"
                                        class=" uppercase text-[8pt] p-0 border-none bg-transparent outline-none focus:outline-none focus:ring focus:ring-transparent leading-tight w-full"></textarea>
                                </td>
                            </tr>
                            <tr class="*:border *:border-black *:hover:bg-slate-200">
                                <td class="px-1 uppercase border py-1  text-[8pt]   text-start " colspan="2">
                                    <textarea rows="1" placeholder="LUGAR DE ENTREGA: "
                                        v-model="recepcionDetDocAdquisicion"
                                        class=" uppercase text-[8pt] p-0 border-none bg-transparent outline-none focus:outline-none focus:ring focus:ring-transparent leading-tight w-full"></textarea>
                                </td>
                            </tr>
                            <tr class="*:border *:border-black *:hover:bg-slate-200">
                                <td class="px-1 uppercase border py-1  text-[8pt]   text-start " colspan="2">
                                    <textarea rows="4" placeholder="LUGAR DE NOTIFICACION: "
                                        v-model="notificacionDetDocAdquisicion"
                                        class="uppercase text-[8pt] p-0 border-none bg-transparent outline-none focus:outline-none focus:ring focus:ring-transparent leading-tight w-full"></textarea>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <div v-if="propProdAdquisicion == ''">
                        <button class=" w-96 btn bg-indigo-500 hover:bg-indigo-600 text-white"
                            @click="saveProductAdquisicion">
                            <svg class="w-4 h-4 fill-current opacity-50 shrink-0" viewBox="0 0 16 16">
                                <path
                                    d="M15 7H9V1c0-.6-.4-1-1-1S7 .4 7 1v6H1c-.6 0-1 .4-1 1s.4 1 1 1h6v6c0 .6.4 1 1 1s1-.4 1-1V9h6c.6 0 1-.4 1-1s-.4-1-1-1z">
                                </path>
                            </svg>
                            <span class="ml-2">Terminar documento de adquisicion </span>
                        </button>
                    </div>

                    <div v-else-if="estadoDocAdq === 1 && canEdit">
                        <button class=" w-96 btn bg-orange-500 hover:bg-orange-600 text-white"
                            @click="updateProductAdquisicion">
                            <svg class="w-4 h-4 fill-current opacity-50 shrink-0" viewBox="0 0 16 16">
                                <path
                                    d="M15 7H9V1c0-.6-.4-1-1-1S7 .4 7 1v6H1c-.6 0-1 .4-1 1s.4 1 1 1h6v6c0 .6.4 1 1 1s1-.4 1-1V9h6c.6 0 1-.4 1-1s-.4-1-1-1z">
                                </path>
                            </svg>
                            <span class="ml-2">Terminar edicion documento de adquisicion </span>
                        </button>
                    </div>
                </div>
            </ProcessModal>
        </div>
    </template>
</template>

<!-- Este es un componente Vue.js que importa el componente ProcessModal -->
<script>
// Importa el componente ProcessModal desde la ruta relativa '@/Components-ISRI/AllModal/ProcessModal.vue'
import ProcessModal from '@/Components-ISRI/AllModal/ProcessModal.vue';
import { toRefs } from 'vue';
import { useBienesServicios } from '@/Composables/UCP/BienesServicios/useBienesServicios.js';
import Swal from 'sweetalert2';
import Tooltip from '@/Components-ISRI/Tooltip.vue';
import moment from 'moment';
import { executeRequest } from '@/plugins/requestHelpers';
import { useConfigPdf } from '@/Composables/UCP/BienesServicios/useConfigPdf';
import CalendarAcuerdoComponent from './CalendarAcuerdoComponent.vue';


export default {
    // Indica que este componente utiliza el componente ProcessModal
    components: { ProcessModal, Tooltip, CalendarAcuerdoComponent },

    // Define un array de eventos personalizados que puede emitir este componente
    emit: ["cerrar-modal", "actualizar-datatable"],

    // Define las propiedades que este componente puede recibir
    props: {
        // La propiedad showModal es de tipo Booleano utilizado para manejar estado del modal y su valor predeterminado es falso
        showModal: {
            type: Boolean,
            default: false,
        },
        propProdAdquisicion: {
            type: Object,
            default: () => { },
        },
        canEdit: {
            type: Boolean,
            default: false,
        },
    },
    // Configuración del componente utilizando el nuevo sistema de configuración de Vue 3
    setup(props, { emit }) {

        const { propProdAdquisicion, showModal } = toRefs(props)
        const {
            idLt,
            loader,
            documentNumber,
            disableLt,
            arrayMarca,
            documentType,
            handleDataCalendarUpdate,
            addingRows,
            totProductos,
            letterNumber,
            estadoDocAdq,
            calculateTotal,
            brandsUsedInDoc,
            deleteProductAdq,
            errorsValidation,
            arrayLineaTrabajo,
            objectGetFromProp,
            arrayUnidadMedida,
            deletLineaTrabajo,
            loadingNumberLetter,
            idDetDocAdquisicion,
            arrayDocAdquisicion,
            getCenterName,
            getBrandName,
            addinDocAdquisicion,
            arrayCentroAtencion,
            productDataSearched,
            ArrayProductFiltered,
            setInformacionProduct,
            providerBusinessName,
            onSelectDocAdquisicion,
            arrayWhenIsEditingDocAdq,
            arrayProductoAdquisicion,
            recepcionDetDocAdquisicion,
            arrayProductsWhenIsEditable,
            observacionDetDocAdquisicion,
            notificacionDetDocAdquisicion,
            saveProductAdquisicionRequest,
            updateProductAdquisicionRequest,
            handleInput,
        } = useBienesServicios(propProdAdquisicion, showModal, "contrato")

        /**
          * Guarda productos adquisicion
          *
          * @param {string} productCode - codigo del producto a buscar.
          * @returns {Promise<object>} - Objeto con los datos de la respuesta.
        */
        const saveProductAdquisicion = async () => {
            const confirmed = await Swal.fire({
                title: '<p class="text-[18pt] text-center">¿Esta seguro de guardar el anexo?</p>',
                icon: "question",
                iconHtml: `<lord-icon src="https://cdn.lordicon.com/enzmygww.json" trigger="loop" delay="500" colors="primary:#121331" style="width:100px;height:100px"></lord-icon>`,
                confirmButtonText: "Si, Editar",
                confirmButtonColor: "#001b47",
                cancelButtonText: "Cancelar",
                showCancelButton: true,
                showCloseButton: true,
            });
            if (confirmed.isConfirmed) {
                await executeRequest(
                    saveProductAdquisicionRequest(),
                    "¡El documento de adquisicion se ha guardado correctamente!"
                );
                emit("actualizar-datatable");
            }
        };

        /**
         * Edita productos adquisicion
         *
         * @param {string} productCode - codigo del producto a buscar.
         * @returns {Promise<object>} - Objeto con los datos de la respuesta.
         * @throws {Error} - Error al obtener empleados por nombre.
         */
        const updateProductAdquisicion = async () => {
            const confirmed = await Swal.fire({
                title: '<p class="text-[18pt] text-center">¿Esta seguro de editar?</p>',
                icon: "question",
                iconHtml: `<lord-icon src="https://cdn.lordicon.com/enzmygww.json" trigger="loop" delay="500" colors="primary:#121331" style="width:100px;height:100px"></lord-icon>`,
                confirmButtonText: "Si, Editar",
                confirmButtonColor: "#001b47",
                cancelButtonText: "Cancelar",
                showCancelButton: true,
                showCloseButton: true,
            });
            if (confirmed.isConfirmed) {
                await executeRequest(
                    updateProductAdquisicionRequest(),
                    "¡El documento de adquisicion se ha actualizado correctamente!"
                );
                emit("actualizar-datatable");
            }
        }

        const { printPdf,exportDocumentToExcel } = useConfigPdf(
            arrayDocAdquisicion,
            idDetDocAdquisicion,
            objectGetFromProp,
            notificacionDetDocAdquisicion,
            recepcionDetDocAdquisicion,
            observacionDetDocAdquisicion,
            arrayLineaTrabajo,
            arrayProductoAdquisicion,
            brandsUsedInDoc,
            arrayUnidadMedida,
            arrayCentroAtencion,
            letterNumber,
            totProductos
        )

        return {
            idLt,
            loader,
            handleInput,
            moment,
            printPdf,
            exportDocumentToExcel,
            disableLt,
            getBrandName,
            arrayMarca,
            addingRows,
            letterNumber,
            estadoDocAdq,
            documentNumber,
            totProductos,
            letterNumber,
            getCenterName,
            handleDataCalendarUpdate,
            calculateTotal,
            deleteProductAdq,
            errorsValidation,
            providerBusinessName,
            deletLineaTrabajo,
            objectGetFromProp,
            arrayLineaTrabajo,
            arrayUnidadMedida,
            addinDocAdquisicion,
            productDataSearched,
            arrayCentroAtencion,
            arrayDocAdquisicion,
            idDetDocAdquisicion,
            loadingNumberLetter,
            ArrayProductFiltered,
            setInformacionProduct,
            onSelectDocAdquisicion,
            saveProductAdquisicion,
            arrayWhenIsEditingDocAdq,
            updateProductAdquisicion,
            arrayProductoAdquisicion,
            documentType,
            recepcionDetDocAdquisicion,
            arrayProductsWhenIsEditable,
            observacionDetDocAdquisicion,
            notificacionDetDocAdquisicion,
        };
    },
}
</script>

<style>
table.sheet0 col.col0 {
    width: 116.37777658pt
}

table.sheet0 col.col1 {
    width: 281.06666592pt
}

/* Define las clases de animación */
@keyframes slideOutRight {
    0% {
        opacity: 1;
        transform: translateX(0);
    }

    100% {
        opacity: 0;
        transform: translateX(100%);
    }
}

@keyframes slideInLeft {
    0% {
        opacity: 0;
        transform: translateX(-100%);
    }

    100% {
        opacity: 1;
        transform: translateX(0);
    }
}

/* Agrega estas clases a tu elemento */
.animate-slide-out-right {
    animation: slideOutRight 0.5s ease both;
}

.animate-slide-in-left {
    animation: slideInLeft 0.5s ease both;
}

/* Estilos para la barra de desplazamiento en Webkit (Chrome, Safari) */
::-webkit-scrollbar {
    width: 10px;
    /* Ancho de la barra de desplazamiento */
}

::-webkit-scrollbar-thumb {
    background-color: #c1c2c5;
    /* Color de la manija de desplazamiento */
    border-radius: 5px;
    /* Radio de la manija de desplazamiento */
}

::-webkit-scrollbar-track {
    background-color: #f3f4f6;
    /* Color del fondo de la barra de desplazamiento */
}

td {
    outline: none;
    /* Desactiva el marco de enfoque */
}

@keyframes pulse {
    0% {
        transform: scale(1);
    }

    50% {
        transform: scale(1.001);
    }

    100% {
        transform: scale(1);
    }
}

.custom-pulse {
    animation: pulse 1s infinite;
}

.loader {
    display: flex;
    align-items: center;
}

.icon {
    height: 1.1rem;
    width: 1.1rem;
    animation: spin 1s linear infinite;
    stroke: rgba(107, 114, 128, 1);
}

.loading-text {
    font-size: 0.65rem;
    line-height: 1rem;
    font-weight: 500;
    color: rgba(107, 114, 128, 1);
}

@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}
</style>
