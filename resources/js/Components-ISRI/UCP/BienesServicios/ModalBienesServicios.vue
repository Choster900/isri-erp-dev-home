<template>
    <template>
        <div class="m-1.5">
            <!-- Componente del modal ProcessModal -->
            <ProcessModal maxWidth="4xl" :show="showModal" @close="$emit('cerrar-modal')">
                <div class=" flex  justify-center pt-2 content-between">
                    <svg class="h-7 w-7 absolute top-0 right-0 mt-2 cursor-pointer" viewBox="0 0 25 25"
                        @click="$emit('cerrar-modal')">
                        <path fill="currentColor"
                            d="M7.95 6.536l4.242-4.243a1 1 0 111.415 1.414L9.364 7.95l4.243 4.242a1 1 0 11-1.415 1.415L7.95 9.364l-4.243 4.243a1 1 0 01-1.414-1.415L6.536 7.95 2.293 3.707a1 1 0 011.414-1.414L7.95 6.536z" />
                    </svg>

                </div>
                <div class="mx-6 mt-2"></div>
                <div class="mx-4 overflow-y-auto h-[650px] py-3 pl-2 pr-4 mt-4">
                    <table class="w-[830px] sheet0 " border="0" cellpadding="0" cellspacing="0">
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
                                            stroke-width="1.5" stroke="currentColor"
                                            class="w-5 h-5 ml-2 stroke-slate-400 hover:stroke-slate-500 cursor-pointer">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5zm-3 0h.008v.008H15V10.5z" />
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

                                        <Tooltip bg="dark" position="right" :key="weekIndex" class="-mt-1">
                                            <template v-slot:contenido>
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                    stroke-width="1.5" stroke="currentColor"
                                                    class="w-5 h-5 stroke-slate-400 hover:stroke-slate-500 cursor-pointer">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
                                                </svg>
                                            </template>
                                            <template v-slot:message>
                                                <div class="text-xs text-start text-slate-200 w-44">
                                                    1. Click derecho sobre el producto para ELIMINAR
                                                    <div class="py-1"></div>
                                                    2. Click izquierdo sobre linea de trabajo para esconder lista completa
                                                </div>
                                            </template>
                                        </Tooltip>

                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td class="border border-black  bg-black h-5 border-r-white text-white text-center text-[9pt] "
                                    colspan="3">
                                    RAZON SOCIAL DEL SUMINISTRANTE
                                </td>
                                <td class="border border-black bg-black h-5 text-white text-center  text-[9pt] ">
                                    NIT
                                </td>
                            </tr>

                            <tr>
                                <td class="border border-black h-5" colspan="3">
                                    <h1 class="text-xs font-medium text-center" v-if="propProdAdquisicion == ''">
                                        {{
                                            arrayDocAdquisicion != '' && idDetDocAdquisicion != null &&
                                            arrayDocAdquisicion.find(index => index.value == idDetDocAdquisicion) &&
                                            arrayDocAdquisicion.find(index => index.value == idDetDocAdquisicion).dataDoc &&
                                            arrayDocAdquisicion.find(index => index.value ==
                                                idDetDocAdquisicion).dataDoc.documento_adquisicion.proveedor.razon_social_proveedor
                                            ? arrayDocAdquisicion.find(index => index.value ==
                                                idDetDocAdquisicion).dataDoc.documento_adquisicion.proveedor.razon_social_proveedor
                                            : ''
                                        }}
                                    </h1>
                                    <h1 class="text-xs font-medium text-center" v-else>
                                        {{
                                            arrayWhenIsEditingDocAdq != '' && idDetDocAdquisicion != null &&
                                            arrayWhenIsEditingDocAdq.find(index => index.value == idDetDocAdquisicion) &&
                                            arrayWhenIsEditingDocAdq.find(index => index.value == idDetDocAdquisicion).dataDoc
                                            &&
                                            arrayWhenIsEditingDocAdq.find(index => index.value ==
                                                idDetDocAdquisicion).dataDoc.documento_adquisicion.proveedor.razon_social_proveedor
                                            ? arrayWhenIsEditingDocAdq.find(index => index.value ==
                                                idDetDocAdquisicion).dataDoc.documento_adquisicion.proveedor.razon_social_proveedor
                                            : ''
                                        }}
                                    </h1>
                                </td>

                                <td class="border border-black  h-5  text-center text-[9pt] ">
                                    0614-071017-102-3
                                </td>
                            </tr>
                        </tbody>
                    </table>


                    <div id="especificaciones" class="mb-4">
                        <div class="flex items-center justify-between pt-4 ">
                            <div class="flex items-center">
                                <label for="" class="text-[7pt] font-semibold mr-2">LUGAR Y FECHA: </label>
                                <input type="text" value="SAN SALVADOR 20 DE NOVIEMBRE DEL 2023"
                                    class="w-96 text-[7pt]  font-medium capitalize h-5 border-x-0 border-t-0">
                            </div>
                            <div class="flex items-center">
                                <label for="" class="text-[7pt] font-semibold mr-2">N°.ORDEN:</label>

                                <input type="text" value="187/2023"
                                    class="w-40 text-left text-[7pt]  font-medium capitalize h-5 border-x-0 border-t-0">
                            </div>
                        </div>

                    </div>
                    <!--  <pre class="text-[8pt]">
                        {{ errorsValidation }}
                    </pre> -->
                    <table class="w-[830px]" border="0" cellpadding="0" cellspacing="0">

                        <thead>
                            <tr class="*:text-[8pt] *:bg-black *:text-white *:px-2 *:py-0.5 *:font-normal *:border--white">
                                <th class="border border-black border-r-white border-b-white">PRODUCTO</th>
                                <th class="border border-black border-r-white border-b-white">MARCA</th>
                                <th class="border border-black border-r-white w-40 border-b-white" colspan="2">DESCRIPCION
                                </th>
                                <th class="border border-black border-r-white border-b-white" colspan="2">U/MEDIDAS</th>
                                <th class="border border-black border-r-white border-b-white">ESPECIFICO</th>
                                <th class="border border-black border-r-white border-b-white">DEPENDENCIA</th>
                                <th class="border border-black border-r-white border-b-white">CANTIDAD</th>
                                <th class="border border-black border-r-white border-b-white">PRECIO UNITARIO</th>
                                <th class="border border-black border-b-white">VALOR TOTAL</th>
                            </tr>
                        </thead>
                        <tbody v-for="(docAdq, i) in arrayProductoAdquisicion" :key="i" v-show="docAdq.estadoLt != 0">
                            <tr class="*:border-black cursor-pointer" :class="{ 'custom-pulse': docAdq.hoverToDelete }">
                                <!--     {{ errorsValidation }} -->
                                <td colspan="2" @mouseover="docAdq.hoverToDelete = true"
                                    @click="docAdq.vShowLt = !docAdq.vShowLt" @mouseout="docAdq.hoverToDelete = false"
                                    @contextmenu.prevent="eliminarLinea(i)"
                                    class="uppercase border bg-black text-[8pt] text-white border-black border-t-white text-center text-selection-disable">

                                    Linea de trabajo:</td>


                                <td colspan="3" class="border border-t-black ">
                                    <Multiselect :filter-results="false" :searchable="true" :clear-on-search="true"
                                        @select="disableLt($event)" v-model="docAdq.idLt" :min-chars="1" placeholder="-"
                                        :classes="{
                                            optionSelectedDisabled: 'text-white bg-[#001c48] bg-opacity-50 cursor-not-allowed',
                                            optionPointed: 'text-gray-800 bg-gray-100',
                                            container: `relative mx-auto w-full h-6 flex items-center justify-end box-border cursor-pointer border border-gray-300 rounded bg-white text-base leading-snug outline-none ${errorsValidation[`productAdq.${i}.idLt`] ? 'bg-red-500' : ''}`,
                                            placeholder: `flex items-center text-center h-full absolute left-0 top-0 pointer-events-none leading-snug pl-3.5 text-gray-400 rtl:left-auto rtl:right-0 rtl:pl-0 rtl:pr-3.5 ${errorsValidation[`productAdq.${i}.idLt`] ? 'bg-red-500' : ''}`,
                                            search: `w-full absolute text-start inset-0 outline-none focus:ring-0 appearance-none box-border border-0 text-base font-sans rounded pl-3.5 rtl:pl-0 rtl:pr-3.5 ${errorsValidation[`productAdq.${i}.idLt`] ? 'bg-red-500' : ''}`,
                                            singleLabel: `pr-14 text-[8pt] flex items-center h-full max-w-full absolute left-0 top-0 pointer-events-none leading-snug pl-3.5 box-border rtl:left-auto rtl:right-0 rtl:pl-0 rtl:pr-3.5 ${errorsValidation[`productAdq.${i}.idLt`] ? 'bg-red-500' : ''}`,
                                            option: 'flex items-center justify-start box-border text-left  text-[7.5pt] leading-snug py-2 px-3',
                                            optionSelected: 'text-white bg-[#001c48]',
                                            optionDisabled: 'text-gray-300 cursor-not-allowed',

                                        }" noOptionsText="<p class='text-xs'>Lista vacía</p>"
                                        noResultsText="<p class='text-xs'>Sin resultados de personas</p>"
                                        :options="arrayLineaTrabajo" />


                                </td>
                                <td colspan="3" @mouseover="docAdq.hoverToDelete = true"
                                    @click="docAdq.vShowLt = !docAdq.vShowLt" @mouseout="docAdq.hoverToDelete = false"
                                    @contextmenu.prevent="eliminarLinea(i)"
                                    class="uppercase border bg-black text-[8pt] text-white border-black text-center text-selection-disable">
                                    Doc. Adquisición: </td>
                                <td colspan="4" class="border border-black">
                                    <Multiselect :filter-results="false" :searchable="true" :clear-on-search="true"
                                        v-if="propProdAdquisicion == ''" v-model="idDetDocAdquisicion" placeholder="-"
                                        :min-chars="1" :classes="{
                                            container: `relative mx-auto w-full h-6 flex items-center justify-end box-border cursor-pointer border border-gray-300 rounded bg-white text-base leading-snug outline-none ${errorsValidation.idDetDocAdquisicion ? 'bg-red-500' : ''}`,
                                            placeholder: `flex items-center text-center h-full absolute left-0 top-0 pointer-events-none leading-snug pl-3.5 text-gray-400 rtl:left-auto rtl:right-0 rtl:pl-0 rtl:pr-3.5 ${errorsValidation.idDetDocAdquisicion ? 'bg-red-500' : ''}`,
                                            search: `w-full absolute text-start inset-0 outline-none focus:ring-0 appearance-none box-border border-0 text-base font-sans rounded pl-3.5 rtl:pl-0 rtl:pr-3.5 ${errorsValidation.idDetDocAdquisicion ? 'bg-red-500' : ''}`,
                                            singleLabel: `pr-14 text-[8pt] flex items-center h-full max-w-full absolute left-0 top-0 pointer-events-none leading-snug pl-3.5 box-border rtl:left-auto rtl:right-0 rtl:pl-0 rtl:pr-3.5 ${errorsValidation.idDetDocAdquisicion ? 'bg-red-500' : ''}`,
                                            option: 'flex items-center justify-start box-border text-left cursor-pointer text-[7.5pt] leading-snug py-2 px-3',
                                            optionSelected: 'text-white bg-[#001c48]',
                                            optionPointed: 'text-gray-800 bg-gray-100',
                                        }" noOptionsText="<p class='text-xs'>Lista vacia</p>"
                                        noResultsText="<p class='text-xs'>Sin resultados de personas</p>"
                                        :options="arrayDocAdquisicion" />

                                    <Multiselect :filter-results="false" :searchable="true" :clear-on-search="true" v-else
                                        v-model="idDetDocAdquisicion" placeholder="-" :min-chars="1" :classes="{
                                            container: `relative mx-auto w-full h-6 flex items-center justify-end box-border cursor-pointer border border-gray-300 rounded bg-white text-base leading-snug outline-none ${errorsValidation.idDetDocAdquisicion ? 'bg-red-500' : ''}`,
                                            placeholder: `flex items-center text-center h-full absolute left-0 top-0 pointer-events-none leading-snug pl-3.5 text-gray-400 rtl:left-auto rtl:right-0 rtl:pl-0 rtl:pr-3.5 ${errorsValidation.idDetDocAdquisicion ? 'bg-red-500' : ''}`,
                                            search: `w-full absolute text-start inset-0 outline-none focus:ring-0 appearance-none box-border border-0 text-base font-sans rounded pl-3.5 rtl:pl-0 rtl:pr-3.5 ${errorsValidation.idDetDocAdquisicion ? 'bg-red-500' : ''}`,
                                            singleLabel: `pr-14 text-[8pt] flex items-center h-full max-w-full absolute left-0 top-0 pointer-events-none leading-snug pl-3.5 box-border rtl:left-auto rtl:right-0 rtl:pl-0 rtl:pr-3.5 ${errorsValidation.idDetDocAdquisicion ? 'bg-red-500' : ''}`,
                                            option: 'flex items-center justify-start box-border text-left cursor-pointer text-[7.5pt] leading-snug py-2 px-3',
                                            optionSelected: 'text-white bg-[#001c48]',
                                            optionPointed: 'text-gray-800 bg-gray-100',
                                        }" noOptionsText="<p class='text-xs'>Lista vacia</p>"
                                        noResultsText="<p class='text-xs'>Sin resultados de personas</p>"
                                        :options="arrayWhenIsEditingDocAdq" />

                                </td>
                            </tr>

                            <template v-if="docAdq.vShowLt">
                                <tr class="*:text-[8pt]  *:px-2 *:py-0.5 *:font-normal *:border *:border-black hover:bg-slate-200 cursor-pointer"
                                    :class="{ '*:bg-emerald-100 -400/80 animate-pulse animate-infinite': docAdq.hoverToDelete }"
                                    @contextmenu.prevent="eliminar(docAdq.idLt, j)"
                                    v-for="(detalle, j) in docAdq.detalleDoc" :key="j"
                                    v-show="(detalle.estadoProdAdquisicion != 0)">
                                    <td class=" relative h-20 w-28"
                                        :class="{ 'bg-red-500': errorsValidation[`productAdq.${i}.detalleDoc.${j}.idProducto`] }"
                                        style="padding-left: 0 !important; padding-right: 0 !important; ">
                                        <div class="absolute top-0 w-full flex flex-col items-center">
                                            <Multiselect :filter-results="false" :canClear="false" :resolve-on-load="false"
                                                v-if="detalle.estadoProdAdquisicion == 1" v-model="detalle.idProducto"
                                                :delay="1000" :searchable="true" :clear-on-search="true" :min-chars="3"
                                                @select="setInformacionProduct($event, i, j)" :classes="{
                                                    placeholder: 'flex items-center text-center h-full absolute left-0 top-0 pointer-events-none bg-transparent leading-snug pl-3.5 text-gray-400 rtl:left-auto rtl:right-0 rtl:pl-0 rtl:pr-3.5',
                                                    search: 'w-full absolute text-start inset-0 outline-none focus:ring-0 appearance-none box-border border-0 text-base font-sans bg-white rounded pl-3.5 rtl:pl-0 rtl:pr-3.5 pr-3.5',
                                                    container: 'relative mx-auto w-full h-7  flex items-center justify-end box-border cursor-pointer border border-gray-300 rounded bg-white text-base leading-snug outline-none',
                                                    singleLabel: 'pr-16 text-[8pt] flex items-center h-full max-w-full absolute left-0 top-0 pointer-events-none bg-transparent leading-snug pl-3.5  box-border rtl:left-auto rtl:right-0 rtl:pl-0 rtl:pr-3.5',
                                                    singleLabelText: 'text-center overflow-ellipsis overflow-hidden block whitespace-nowrap max-w-full',
                                                    option: 'flex items-center justify-start box-border text-left cursor-pointer text-[7.5pt] leading-snug py-2 px-3',
                                                    optionSelected: 'text-white bg-[#001c48]',
                                                    optionPointed: 'text-gray-800 bg-gray-100',
                                                }" noOptionsText="<p class='text-xs'>Lista vacia<p>"
                                                noResultsText="<p class='text-xs'>Sin resultados de producto <p>" :options="async function (query) {
                                                    return await handleProductoSearchByCodigo(query)
                                                }" />

                                            <Multiselect :filter-results="false" :canClear="false" :resolve-on-load="false"
                                                v-else v-model="detalle.idProducto" :classes="{
                                                    placeholder: 'flex items-center text-center h-full absolute left-0 top-0 pointer-events-none bg-transparent leading-snug pl-3.5 text-gray-400 rtl:left-auto rtl:right-0 rtl:pl-0 rtl:pr-3.5',
                                                    search: 'w-full absolute text-start inset-0 outline-none focus:ring-0 appearance-none box-border border-0 text-base font-sans bg-white rounded pl-3.5 rtl:pl-0 rtl:pr-3.5 pr-3.5',
                                                    container: 'relative mx-auto w-full h-7  flex items-center justify-end box-border cursor-pointer border border-gray-300 rounded bg-white text-base leading-snug outline-none',
                                                    singleLabel: 'pr-16 text-[8pt] flex items-center h-full max-w-full absolute left-0 top-0 pointer-events-none bg-transparent leading-snug pl-3.5  box-border rtl:left-auto rtl:right-0 rtl:pl-0 rtl:pr-3.5',
                                                    singleLabelText: 'text-center overflow-ellipsis overflow-hidden block whitespace-nowrap max-w-full',
                                                    option: 'flex items-center justify-start box-border text-left cursor-pointer text-[7.5pt] leading-snug py-2 px-3',
                                                    optionSelected: 'text-white bg-[#001c48]',
                                                    optionPointed: 'text-gray-800 bg-gray-100',
                                                }" noOptionsText="<p class='text-xs'>Lista vacia<p>"
                                                noResultsText="<p class='text-xs'>Sin resultados de producto <p>"
                                                :options="arrayProductsWhenIsEditable" />
                                            <span class="text-center ">{{ detalle.detalleProducto }}</span>
                                        </div>
                                    </td>
                                    <td class=" relative h-20 w-28"
                                        :class="{ 'bg-red-500': errorsValidation[`productAdq.${i}.detalleDoc.${j}.idMarca`] }"
                                        style="padding-left: 0 !important; padding-right: 0 !important; ">
                                        <div class="absolute top-0 w-full flex flex-col items-center">
                                            <Multiselect :filter-results="true" :canClear="false" v-model="detalle.idMarca"
                                                :searchable="true" :clear-on-search="true" :min-chars="3" :classes="{
                                                    placeholder: 'flex items-center text-center h-full absolute left-0 top-0 pointer-events-none bg-transparent leading-snug pl-3.5 text-gray-400 rtl:left-auto rtl:right-0 rtl:pl-0 rtl:pr-3.5',
                                                    search: 'w-full absolute text-start inset-0 outline-none focus:ring-0 appearance-none box-border border-0 text-base font-sans bg-white rounded pl-3.5 rtl:pl-0 rtl:pr-3.5 pr-3.5',
                                                    container: 'relative mx-auto w-full h-7  flex items-center justify-end box-border cursor-pointer border border-gray-300 rounded bg-white text-base leading-snug outline-none',
                                                    singleLabel: 'pr-16 text-[8pt] flex items-center h-full max-w-full absolute left-0 top-0 pointer-events-none bg-transparent leading-snug pl-3.5  box-border rtl:left-auto rtl:right-0 rtl:pl-0 rtl:pr-3.5',
                                                    singleLabelText: 'text-center overflow-ellipsis overflow-hidden block whitespace-nowrap max-w-full',
                                                    option: 'flex items-center justify-start box-border text-left cursor-pointer text-[7.5pt] leading-snug py-2 px-3',
                                                    optionSelected: 'text-white bg-[#001c48]',
                                                    optionPointed: 'text-gray-800 bg-gray-100',
                                                }" noOptionsText="<p class='text-xs'>Lista vacia<p>"
                                                noResultsText="<p class='text-xs'>Sin resultados de producto <p>"
                                                :options="arrayMarca" />
                                            <span class="text-center ">

                                                {{
                                                    // Comprueba si arrayUnidadMedida no
                                                    // está vacío y si tiene la propiedad dataUnidad
                                                    arrayMarca.length > 0 &&
                                                    arrayMarca.find(index => index.value == detalle.idMarca)?.dataMarca
                                                    ?
                                                    // Si la condición es verdadera, muestra el nombre de la unidad de medida
                                                    arrayMarca.find(index => index.value ==
                                                        detalle.idMarca).dataMarca.nombre_marca :
                                                    // Si no, muestra un guion
                                                    '-'
                                                }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="w-72 p-0 uppercase align-top" colspan="2">
                                        <textarea v-model="detalle.descripcionProdAdquisicion" rows="9" maxlength="255"
                                            class="cursor-pointer  uppercase text-[8pt] p-0 border-none bg-transparent outline-none focus:outline-none focus:ring focus:ring-transparent leading-tight w-full"></textarea>
                                    </td>

                                    <td class=" relative h-28"
                                        style="padding-left: 0 !important; padding-right: 0 !important; " colspan="2">
                                        <div class="absolute top-0 w-full flex flex-col items-center">
                                            <Multiselect :filter-results="false" :searchable="false" :canClear="false"
                                                v-model="detalle.pesoProducto" :clear-on-search="true" :min-chars="1"
                                                :classes="{
                                                    placeholder: 'flex items-center text-center h-full absolute left-0 top-0 pointer-events-none bg-transparent leading-snug pl-3.5 text-gray-400 rtl:left-auto rtl:right-0 rtl:pl-0 rtl:pr-3.5',
                                                    search: 'w-full absolute text-center inset-0 outline-none focus:ring-0 appearance-none box-border border-0 text-base font-sans bg-white rounded pl-3.5 rtl:pl-0 rtl:pr-3.5',
                                                    container: 'relative mx-auto w-full h-7  flex items-center justify-end box-border cursor-pointer border border-gray-300 rounded bg-white text-base leading-snug outline-none',
                                                    singleLabel: ' text-[8pt] flex items-center h-full max-w-full absolute left-0 top-0 pointer-events-none bg-transparent leading-snug pl-3.5  box-border rtl:left-auto rtl:right-0 rtl:pl-0 rtl:pr-3.5',
                                                    option: 'flex items-center justify-start box-border text-left cursor-pointer text-[7.5pt] leading-snug py-2 px-3',
                                                    optionSelected: 'text-white bg-[#001c48]',
                                                    optionPointed: 'text-gray-800 bg-gray-100',
                                                }" noOptionsText="<p class='text-xs'>Lista vacia<p>"
                                                noResultsText="<p class='text-xs'>Sin resultados de personas <p>"
                                                :options="arrayUnidadMedida" />
                                            <span class="text-center">
                                                {{
                                                    // Comprueba si arrayUnidadMedida no
                                                    // está vacío y si tiene la propiedad dataUnidad
                                                    arrayUnidadMedida.length > 0 &&
                                                    arrayUnidadMedida.find(index => index.value ==
                                                        detalle.pesoProducto)?.dataUnidad
                                                    ?
                                                    // Si la condición es verdadera, muestra el nombre de la unidad de medida
                                                    arrayUnidadMedida.find(index => index.value ==
                                                        detalle.pesoProducto).dataUnidad.nombre_unidad_medida :
                                                    // Si no, muestra un guion
                                                    '-'
                                                }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class=" relative text-center">
                                        <span class="absolute top-1 left-0 w-full flex flex-col items-center">
                                            {{ detalle.especifico }}
                                        </span>
                                    </td>
                                    <td class=" relative h-20 w-24"
                                        :class="{ 'bg-red-500': errorsValidation[`productAdq.${i}.detalleDoc.${j}.idCentroAtencion`] }"
                                        style="padding-left: 0 !important; padding-right: 0 !important; ">
                                        <div class="absolute top-0 w-full flex flex-col items-center">
                                            <Multiselect :filter-results="false" :searchable="false" :canClear="false"
                                                v-model="detalle.idCentroAtencion" :clear-on-search="true" :min-chars="1"
                                                :classes="{
                                                    placeholder: 'flex items-center text-center h-full absolute left-0 top-0 pointer-events-none bg-transparent leading-snug pl-3.5 text-gray-400 rtl:left-auto rtl:right-0 rtl:pl-0 rtl:pr-3.5',
                                                    search: 'w-full absolute text-center inset-0 outline-none focus:ring-0 appearance-none box-border border-0 text-base font-sans bg-white rounded pl-3.5 rtl:pl-0 rtl:pr-3.5',
                                                    container: 'relative mx-auto w-full h-7  flex items-center justify-end box-border cursor-pointer border border-gray-300 rounded bg-white text-base leading-snug outline-none',
                                                    singleLabel: 'pr-6 text-[8pt] flex items-center h-full max-w-full absolute left-0 top-0 pointer-events-none bg-transparent leading-snug pl-3.5  box-border rtl:left-auto rtl:right-0 rtl:pl-0 rtl:pr-3.5',
                                                    option: 'flex items-center justify-start box-border text-left cursor-pointer text-[7.5pt] leading-snug py-2 px-3',
                                                    optionSelected: 'text-white bg-[#001c48]',
                                                    optionPointed: 'text-gray-800 bg-gray-100',
                                                }" noOptionsText="<p class='text-xs'>Lista vacia<p>"
                                                noResultsText="<p class='text-xs'>Sin resultados de personas <p>"
                                                :options="arrayCentroAtencion" />
                                            <span class="text-center">
                                                {{
                                                    // Comprueba si arrayUnidadMedida no
                                                    // está vacío y si tiene la propiedad dataCentro
                                                    arrayCentroAtencion.length > 0 &&
                                                    arrayCentroAtencion.find(index => index.value ==
                                                        detalle.idCentroAtencion)?.dataCentro
                                                    ?
                                                    // Si la condición es verdadera, muestra el nombre de la unidad de medida
                                                    arrayCentroAtencion.find(index => index.value ==
                                                        detalle.idCentroAtencion).dataCentro.nombre_centro_atencion :
                                                    // Si no, muestra un guion
                                                    '-'
                                                }}
                                            </span>
                                        </div>
                                    </td>

                                    <td class=" w-12 relative "
                                        :class="{ 'bg-red-500': errorsValidation[`productAdq.${i}.detalleDoc.${j}.cantProdAdquisicion`] }">
                                        <input type="number" v-model="detalle.cantProdAdquisicion"
                                            @change="calculateTotal(i, j)"
                                            class="absolute top-0 left-0 w-full bg-transparent border-none pl-2 text-center text-[8pt] p-0 outline-none focus:outline-none focus:ring focus:ring-transparent"
                                            placeholder="0" min="0" max="1000" />
                                    </td>
                                    <td class=" relative text-center"
                                        :class="{ 'bg-red-500': errorsValidation[`productAdq.${i}.detalleDoc.${j}.costoProdAdquisicion`] }">
                                        <div class="flex justify-center space-x-1 absolute top-0 left-0 ">
                                            <input type="text" v-model="detalle.costoProdAdquisicion"
                                                @input="calculateTotal(i, j)"
                                                class="w-full bg-transparent border-none  text-center text-[8pt] p-0 outline-none focus:outline-none focus:ring focus:ring-transparent"
                                                placeholder="$00.00" min="0" max="1000" />
                                        </div>
                                    </td>
                                    <td class="relative text-center w-20">
                                        <span class="absolute top-1 left-0 w-full flex flex-col items-center">
                                            ${{ detalle.valorTotalProduct !== undefined ?
                                                detalle.valorTotalProduct.toLocaleString('en-US', {
                                                    minimumFractionDigits: 2,
                                                    maximumFractionDigits: 2
                                                }) : '00.00' }}
                                        </span>
                                    </td>

                                </tr>
                            </template>
                            <template v-else>
                                <tr>
                                    <td colspan="11" class="text-center border border-black cursor-pointer"
                                        @click="docAdq.vShowLt = true">
                                        <div class="flex items-center justify-center space-x-1">
                                            <span>oculto </span>
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                                            </svg>
                                        </div>
                                    </td>
                                </tr>

                            </template>
                            <tr>
                                <td colspan="11" class="">
                                    <button style="outline: none;" @click="addingRows(i); docAdq.vShowLt = true;"
                                        class="w-full  border-4 border-dashed border-slate-300 hover:border-slate-400  my-1 py-1">
                                        <div class="flex justify-center">
                                            <span class="text-xs pt-0.5 text-slate-600">
                                                NUEVO PRODUCTO ADQUISICION EN:
                                                <br>
                                                {{ arrayLineaTrabajo.find(index => index.value == docAdq.idLt)?.label || ''
                                                }}
                                            </span>
                                        </div>
                                    </button>
                                </td>
                            </tr>

                        </tbody>
                    </table>

                    <div>
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
                    <div v-else>
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
import { ref, toRefs, watch } from 'vue';
import { useBienesServicios } from '@/Composables/UCP/BienesServicios/useBienesServicios.js';
import Swal from 'sweetalert2';
import Tooltip from '@/Components-ISRI/Tooltip.vue';
// Define el componente Vue.js
export default {
    // Indica que este componente utiliza el componente ProcessModal
    components: { ProcessModal, Tooltip },

    // Define un array de eventos personalizados que puede emitir este componente
    emit: ["cerrar-modal"],

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
    },
    // Configuración del componente utilizando el nuevo sistema de configuración de Vue 3
    setup(props) {

        const { propProdAdquisicion, showModal } = toRefs(props)
        const {
            idLt,
            arrayMarca,
            addingRows,
            calculateTotal,
            arrayLineaTrabajo,
            objectGetFromProp,
            arrayUnidadMedida,
            idDetDocAdquisicion,
            arrayWhenIsEditingDocAdq,
            arrayDocAdquisicion,
            addinDocAdquisicion,
            arrayCentroAtencion,
            updateProductAdquisicion,
            productDataSearched,
            setInformacionProduct,
            saveProductAdquisicion,
            arrayProductsWhenIsEditable,
            arrayProductoAdquisicion,
            errorsValidation,
            disableLt,
            handleProductoSearchByCodigo,
        } = useBienesServicios()

        watch(showModal, (newValue, oldValue) => {
            // Verifica si showModal se ha establecido en falso (se cerró el modal)
            if (!newValue) {
                // Restablecer los valores a nulos o vacíos
                objectGetFromProp.value = []
                arrayProductoAdquisicion.value = []
                arrayWhenIsEditingDocAdq.value = []
                arrayLineaTrabajo.value.forEach((item) => {
                    item.disabled = false;
                });
            }
        });
        watch(propProdAdquisicion, (newValue, oldValue) => {
            // Verifica si showModal se ha establecido en falso (se cerró el modal)
            if (newValue !== null && newValue !== undefined && (Array.isArray(newValue) ? newValue.length > 0 : newValue !== '')) {
                // Utiliza el patrón de objeto "guard" para simplificar las verificaciones
                objectGetFromProp.value = newValue;
                let productosAdquisiciones = objectGetFromProp.value.productos_adquisiciones;

                // Utilizando un conjunto para rastrear los id_lt únicos
                let idLtSet = new Set();
                let productArray = new Set();

                console.log(objectGetFromProp.value);

                arrayWhenIsEditingDocAdq.value.push({
                    value: objectGetFromProp.value.id_det_doc_adquisicion,
                    label: objectGetFromProp.value.nombre_det_doc_adquisicion,
                    dataDoc: objectGetFromProp.value
                })

                // Utiliza map en lugar de reduce para simplificar la lógica
                arrayProductsWhenIsEditable.value = productosAdquisiciones.map(producto => {
                    let idProduct = producto.id_producto;

                    if (!productArray.has(idProduct)) {
                        productArray.add(idProduct);
                        const product = producto.producto;
                        return {
                            value: product.id_producto,
                            label: product.codigo_producto
                        };
                    }
                }).filter(Boolean)  // Filtra los elementos nulos o indefinidos


                // Utiliza map y filter para mejorar la legibilidad y reducir código duplicado
                arrayProductoAdquisicion.value = productosAdquisiciones
                    .map(producto => {
                        let idLt = producto.id_lt;
                        if (!idLtSet.has(idLt)) {
                            idLtSet.add(idLt);
                            return {
                                idProdAdquisicion: producto.id_prod_adquisicion,
                                idLt: idLt,
                                vShowLt: true,
                                hoverToDelete: false,
                                estadoLt: 2, // [Comment: Estado manejado en 0 => deleted,1 => created,2 =>edited]
                                detalleDoc: []
                            };
                        }
                    })
                    .filter(Boolean);

                // Utiliza forEach en lugar de map cuando no necesitas un nuevo arreglo resultante
                productosAdquisiciones.forEach(index => {
                    let indice = arrayProductoAdquisicion.value.findIndex(i => i.idLt == index.id_lt);
                    const { producto } = index;
                    idDetDocAdquisicion.value = index.id_det_doc_adquisicion
                    arrayProductoAdquisicion.value[indice]["detalleDoc"].push({
                        idProdAdquisicion: index.id_prod_adquisicion,
                        especifico: producto.id_ccta_presupuestal,
                        idProducto: producto.id_producto,
                        detalleProducto: producto.nombre_producto,
                        pesoProducto: producto.unidad_medida.id_unidad_medida,
                        idCentroAtencion: index.id_centro_atencion,
                        detalleCentro: index.centro_atencion.nombre_centro_atencion,
                        idMarca: index.id_marca,
                        cantProdAdquisicion: index.cant_prod_adquisicion,
                        costoProdAdquisicion: index.costo_prod_adquisicion,
                        descripcionProdAdquisicion: index.descripcion_prod_adquisicion,
                        estadoProdAdquisicion: 2, // [Comment: Estado manejado en 0 => deleted,1 => created,2 =>edited]
                        valorTotalProduct: index.cant_prod_adquisicion * index.costo_prod_adquisicion,
                    });
                });
                disableLt()
            } else {
                objectGetFromProp.value = [];
                arrayProductoAdquisicion.value = []
                addinDocAdquisicion()
                // addingRows(0)
            }
        });


        const eliminar = async (indexDdLT, indexProdAdq) => {

            const confirmedEliminarProd = await Swal.fire({
                title: '<p class="text-[15pt]">¿Esta seguro de eliminar el producto de adquisicion?. </p>',
                text: "Al confirmar esta acción, el producto de adquisición se eliminará temporalmente. Recuerde que los cambios serán permanentes una vez que guarde.",

                icon: "question",
                iconHtml: `<lord-icon src="https://cdn.lordicon.com/enzmygww.json" trigger="loop" delay="500" colors="primary:#121331" style="width:100px;height:100px"></lord-icon>`,
                confirmButtonText: `<p class="text-[10pt] text-center">Si, Eliminar</p>`,
                confirmButtonColor: "#D2691E",
                cancelButtonText: `<p class="text-[10pt] text-center">Cancelar</p>`,
                showCancelButton: true,
                showCloseButton: true,
            });
            if (confirmedEliminarProd.isConfirmed) {
                arrayProductoAdquisicion.value.find(index => index.idLt == indexDdLT).detalleDoc[indexProdAdq].estadoProdAdquisicion = 0
                //alert("comming soon for delete")
            }
        }
        const eliminarLinea = async (indexDdLT) => {

            const confirmedEliminarLinea = await Swal.fire({
                title: '<p class="text-[18pt] text-center">¿Esta seguro de eliminar la linea de trabajo completa?.</p>',
                text: 'Al confirmar esta acción, se eliminará de manera permanente la linea y cada producto de adquisición asociado a esta línea será eliminado de forma irreversible.',
                icon: "question",
                iconHtml: `<lord-icon src="https://cdn.lordicon.com/enzmygww.json" trigger="loop" delay="500" colors="primary:#121331" style="width:100px;height:100px"></lord-icon>`,
                confirmButtonText: `<p class="text-[10pt] text-center">Si, Eliminar</p>`,
                confirmButtonColor: "#D2691E",
                cancelButtonText: `<p class="text-[10pt] text-center">Cancelar</p>`,
                showCancelButton: true,
                showCloseButton: true,
            });
            if (confirmedEliminarLinea.isConfirmed) {
                arrayProductoAdquisicion.value[indexDdLT].estadoLt = 0;
                //alert("comming soon for delete")
            }
        }

        return {
            eliminarLinea,
            eliminar,
            idLt,
            arrayWhenIsEditingDocAdq,
            errorsValidation,
            arrayMarca,
            addingRows,
            disableLt,
            objectGetFromProp,
            calculateTotal,
            arrayLineaTrabajo,
            arrayUnidadMedida,
            addinDocAdquisicion,
            updateProductAdquisicion,
            arrayCentroAtencion,
            arrayDocAdquisicion,
            idDetDocAdquisicion,
            setInformacionProduct,
            saveProductAdquisicion,
            arrayProductoAdquisicion,
            arrayProductsWhenIsEditable,
            handleProductoSearchByCodigo,
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
</style>
