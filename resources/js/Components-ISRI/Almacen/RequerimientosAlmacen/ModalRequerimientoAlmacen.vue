<script>
import { defineComponent, ref, toRefs, watch, onMounted, h } from 'vue';

import ButtonCloseModal from "@/Components/ButtonCloseModal.vue";
import ProcessModal from "@/Components-ISRI/AllModal/ProcessModal.vue";
import TitleModalReq from "./TitleModalReq.vue";
import OnlyLabelInput from '@/Components-ISRI/ComponentsToForms/OnlyLabelInput.vue';
import { useRequerimientoAlmacen } from '../../../Composables/Almacen/RequerimientoAlmacen/useRequerimientoAlmacen.js'
import Swal from 'sweetalert2';
import { executeRequest } from "@/plugins/requestHelpers";
import axios from 'axios';
import InputError from '@/Components/InputError.vue';
import { usePage } from '@inertiajs/vue3';

export default defineComponent({
    components: { ProcessModal, ButtonCloseModal, TitleModalReq, OnlyLabelInput, InputError },
    emits: ["cerrar-modal", "actualizar-datatable"], // Cambiado a 'emits' en lugar de 'emit'
    props: {
        showModal: {
            type: Boolean,
            default: false,
        },
        objectRequerimientoToSendModal: {
            type: Object,
            default: () => { },
        },
        numeroRequerimientoSiguiente: {
            type: String,
            default: null,
        }
    },
    setup(props, { emit }) {
        const { objectRequerimientoToSendModal, showModal, numeroRequerimientoSiguiente } = toRefs(props)

        const {
            idLt,
            canEditReq,
            marcasArray,
            idEstadoReq,
            searchProductionCenterByAtentionCenter,
            appendProduct,
            productosArray,
            isLoadingCentrosProduccion,
            isLoadinProduct,
            idRequerimiento,
            centroProduccion,
            idCentroAtencion,
            idProyFinanciado,
            errorsValidation,
            numRequerimiento,
            lineaTrabajoArray,
            centroAtenionArray,
            fechaRequerimiento,
            proyectoFinanciados,
            handleProductSearch,
            optionsCentroAtencion,
            dataDetalleRequerimiento,
            getProductoByDependencia,
            observacionRequerimiento,
            appendDetalleRequerimiento,
            saveRequerimientoAlmacenRequest,
            updateRequerimientoAlmacenRequest,
            productoArrayWithOutProductNoStock,
            setIdProductoByDetalleExistenciaAlmacenId,
        } = useRequerimientoAlmacen(objectRequerimientoToSendModal, numeroRequerimientoSiguiente, showModal)

        /**
         * DialogModal para guardar requerimiento
         */
        const saveRequerimientoAlmacen = async () => {
            const confirmed = await Swal.fire({
                title: '<p class="text-[18pt] text-center">¿Esta seguro de guardar el requerimiento para almacen?</p>',
                icon: "question",
                iconHtml: `<lord-icon src="https://cdn.lordicon.com/enzmygww.json" trigger="loop" delay="500" colors="primary:#121331" style="width:100px;height:100px"></lord-icon>`,
                confirmButtonText: "Si, Guardar",
                confirmButtonColor: "#001b47",
                cancelButtonText: "Cancelar",
                showCancelButton: true,
                showCloseButton: true,
            });
            if (confirmed.isConfirmed) {
                await executeRequest(
                    saveRequerimientoAlmacenRequest(),
                    "¡El documento de adquisicion se ha guardado correctamente!"
                );
                emit("actualizar-datatable");
            }
        };
        /**
         * DialogModal para modificar producto adquisicion
         *
         * @param {string} productCode - codigo del producto a buscar.
         * @returns {Promise<object>} - Objeto con los datos de la respuesta.
         */
        const updateRequerimientoAlmacen = async () => {
            const confirmed = await Swal.fire({
                title: '<p class="text-[18pt] text-center">¿Está seguro de que desea actualizar el requerimiento para almacen?</p>',
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
                    updateRequerimientoAlmacenRequest(),
                    "¡El documento de adquisicion se ha actualizado correctamente!"
                );
                emit("actualizar-datatable");
            }
        };
        return {
            idLt,
            canEditReq,
            marcasArray,
            idEstadoReq,
            appendProduct,
            productosArray,
            idRequerimiento,
            isLoadinProduct,
            centroProduccion,
            idCentroAtencion,
            idProyFinanciado,
            errorsValidation,
            numRequerimiento,
            lineaTrabajoArray,
            centroAtenionArray,
            fechaRequerimiento,
            proyectoFinanciados,
            handleProductSearch,
            isLoadingCentrosProduccion,
            optionsCentroAtencion,
            dataDetalleRequerimiento,
            searchProductionCenterByAtentionCenter,
            getProductoByDependencia,
            getProductoByDependencia,
            observacionRequerimiento,
            saveRequerimientoAlmacen,
            appendDetalleRequerimiento,
            updateRequerimientoAlmacen,
            productoArrayWithOutProductNoStock,
            setIdProductoByDetalleExistenciaAlmacenId,
        }
    },
});
</script>

<template>
    <div class="m-1.5">
        <ProcessModal maxWidth="3xl" :show="showModal" @close="$emit('cerrar-modal')">
            <div class="py-4 px-3 border-4 m-4">
                <ButtonCloseModal @close="$emit('cerrar-modal')" />
                <TitleModalReq />
                <div id="formulario-principal">
                <pre class="text-[8pt]">
                {{optionsCentroAtencion}}
                </pre>
                    <div class="pt-4 flex justify-start space-x-2 items-center">
                        <h1 class="text-xs ">Requerimiento N°: <span class="font-medium text-sm underline">{{ numRequerimiento }}</span></h1>

                        <div class="text-xs items-center flex gap-1 "
                            v-if="optionsCentroAtencion?.length == 1 && !isLoadingCentrosProduccion">
                            <span>Centro:</span>
                            <span class="font-medium text-sm underline"
                                v-if="optionsCentroAtencion && optionsCentroAtencion[0]">
                                {{ optionsCentroAtencion[0].label }}
                            </span>
                        </div>

                        <div class="text-xs items-center w-96 flex gap-2"
                            v-else-if="optionsCentroAtencion?.length > 1 && !isLoadingCentrosProduccion">

                            <OnlyLabelInput textLabel="Centro:" />
                            <Multiselect v-model="idCentroAtencion"
                                @select="searchProductionCenterByAtentionCenter($event, '/get-centro-produccion-by-centro',false)"
                                :disabled="!canEditReq" :classes="{
                                    containerDisabled: `bg-gray-200 text-text-slate-400`,
                                    container: `relative mx-auto w-full h-7 flex items-center justify-end box-border   border border-gray-300 rounded  text-base leading-snug outline-none
                                    ${canEditReq ? 'bg-white' : ''}`,
                                    optionSelectedDisabled: 'text-white bg-[#001c48] bg-opacity-50 cursor-not-allowed',
                                    optionPointed: 'text-gray-800 bg-gray-100',
                                }" :filter-results="false" :searchable="true" :clear-on-search="true" :min-chars="1"
                                :options="optionsCentroAtencion"
                                noResultsText="<p class='text-xs'>Sin resultados de personas</p>" placeholder="-"
                                noOptionsText="<p class='text-xs'>vacio</p>" />
                            <InputError class="mt-2"
                                v-if="errorsValidation && errorsValidation['idCentroAtencion'] !== ''"
                                :message="errorsValidation['idCentroAtencion']" />
                        </div>

                        <div class="w-96 h-5 bg-gray-300 rounded mb-2 animate-pulse"
                            v-else-if="isLoadingCentrosProduccion"></div>
                    </div>
                    <div class="pt-4 flex justify-between space-x-2">


                        <div class="w-full">
                            <OnlyLabelInput textLabel="Proyecto financiamiento" />

                            <Multiselect v-model="idProyFinanciado" @select="getProductoByDependencia()"
                                :disabled="!canEditReq"
                                :classes="{ containerDisabled: `bg-gray-200 text-text-slate-400`, container: `relative mx-auto w-full h-7 flex items-center justify-end box-border   border border-gray-300 rounded  text-base leading-snug outline-none ${canEditReq ? 'bg-white' : ''}`, optionSelectedDisabled: `text-white bg-[#001c48] bg-opacity-50 cursor-not-allowed`, optionPointed: `text-gray-800 bg-gray-100` }"
                                :filter-results="false" :searchable="true" :clear-on-search="true" :min-chars="1"
                                :options="proyectoFinanciados"
                                noResultsText="<p class='text-xs'>Sin resultados de personas</p>" placeholder="-"
                                noOptionsText="<p class='text-xs'>vacio</p>" />
                            <InputError class="mt-2"
                                v-if="errorsValidation && errorsValidation['idProyFinanciado'] !== ''"
                                :message="errorsValidation['idProyFinanciado']" />

                        </div>
                        <div class="w-full" v-if="idProyFinanciado != 4">
                            <OnlyLabelInput textLabel="Linea de trabajo" />
                            <Multiselect v-model="idLt" @select="getProductoByDependencia()" :disabled="!canEditReq"
                                :classes="{
            containerDisabled: `bg-gray-200 text-text-slate-400`,
            container: `relative mx-auto w-full h-7 flex items-center justify-end box-border   border border-gray-300 rounded  text-base leading-snug outline-none
                                        ${canEditReq ? 'bg-white' : ''}`,
            optionSelectedDisabled: 'text-white bg-[#001c48] bg-opacity-50 cursor-not-allowed',
            optionPointed: 'text-gray-800 bg-gray-100',
        }" :filter-results="false" :searchable="true" :clear-on-search="true" :min-chars="1"
                                :options="lineaTrabajoArray"
                                noResultsText="<p class='text-xs'>Sin resultados de personas</p>" placeholder="-"
                                noOptionsText="<p class='text-xs'>vacio</p>" />
                            <InputError class="mt-2" v-if="errorsValidation && errorsValidation['idLt'] !== ''"
                                :message="errorsValidation['idLt']" />
                        </div>
                    </div>
                    <div class="pt-4 flex justify-start space-x-2 items-end">

                        <textarea placeholder='Observacion del requerimiento' rows="2" name=''
                            v-model="observacionRequerimiento" :disabled="!canEditReq"
                            class="w-full rounded-md px-4 text-xs border-slate-300 border pt-2.5 outline-[#007bff]"
                            :class="{ 'bg-slate-300': !canEditReq }"></textarea>

                    </div>
                    <div class="pt-4 flex justify-start space-x-2 items-end"
                        v-if="objectRequerimientoToSendModal == ''">
                        <button
                            class=" bg-green-700 rounded px-2 text-xs h-6 text-slate-200 hover:text-white hover:bg-green-600 ">
                            <div class="flex items-center space-x-1">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5" />
                                </svg>
                                <span @click="saveRequerimientoAlmacen">Guardar y cerrar</span>
                            </div>
                        </button>
                    </div>
                    <div class="pt-4 flex justify-start space-x-2 items-end" v-else v-show="canEditReq">
                        <button
                            class=" bg-indigo-700 rounded px-2 text-xs h-6 text-slate-200 hover:text-white hover:bg-indigo-600 ">
                            <div class="flex items-center space-x-1">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5" />
                                </svg>
                                <span @click="canEditReq ? updateRequerimientoAlmacen() : ''">Actualizar y cerrar</span>
                            </div>
                        </button>
                    </div>
                </div>
                <table class="mt-4 w-[740px] ">
                    <tr class=" *:text-xs *:text-slate-700">
                        <td class="w-20 text-end pl-4">CANTIDAD</td>
                        <td class="w-full text-center">PRODUCTO</td>
                    </tr>
                </table>
                <div id="secctionTableDetalleReq" class="w-[700px]">

                    <div v-for="(detalleRequerimiento, i) in dataDetalleRequerimiento" :key="i" class="">
                        <div class=" justify-start items-center  bg-slate-100  hover:bg-slate-400/50 cursor-pointer">
                            <div class="flex space-x-3 items-center px-2">
                                <div class="text-xs"
                                    @click="detalleRequerimiento.isHidden = !detalleRequerimiento.isHidden">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="2" stroke="currentColor" class="w-6 h-6 text-slate-700 ">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                    </svg>
                                </div>
                                <div class="text-xs w-[645px] py-1">
                                    <Multiselect v-model="detalleRequerimiento.idCentroProduccion"
                                        :loading="isLoadingCentrosProduccion" :disabled="!canEditReq"
                                        :classes="{ containerDisabled: 'bg-gray-200 text-text-slate-400', container: `relative mx-auto w-full h-7 flex items-center justify-end box-border   border border-gray-300 rounded  text-base leading-snug outline-none ${canEditReq ? 'bg-white' : ''}`, optionSelectedDisabled: 'text-white bg-[#001c48] bg-opacity-50 cursor-not-allowed', optionPointed: 'text-gray-800 bg-gray-100', }"
                                        :filter-results="false" :searchable="true" :clear-on-search="true"
                                        :min-chars="1" :options="centroProduccion"
                                        noResultsText="<p class='text-xs'>Sin resultados de personas</p>"
                                        placeholder="CENTRO DE PRODUCCION"
                                        noOptionsText="<p class='text-xs'>SIN CENTROS DE PRODUCCION</p>" />
                                </div>
                            </div>

                            <div class="text-xs bg-slate-100 border-b-4 border-slate-400  w-full text-center">
                                <span class="text-red-600">
                                    {{ errorsValidation && errorsValidation['dataDetalleRequerimiento'] &&
            errorsValidation['dataDetalleRequerimiento'][0]['idCentroProduccion'] }}
                                </span>
                            </div>
                        </div>

                        <div v-show="!detalleRequerimiento.isHidden"
                            v-for="(producto, j ) in detalleRequerimiento.productos" :key="j">

                            <div v-if="producto.stateProducto == 1"
                                class=" px-2 text-xs flex justify-end  space-x-4 items-center bg-slate-100 hover:bg-slate-200">

                                <div class="text-xs w-20 ">
                                    <input type="text" v-model="producto.cantDetRequerimiento" :disabled="!canEditReq"
                                        class=" text-center border-0 h-6 text-xs w-20 border-x-transparentborder-t-transparent bg-transparent focus:border-x-transparentfocus:border-t-transparent"
                                        :class="!canEditReq ? 'bg-slate-700' : 'bg-white'" placeholder="CANT">
                                </div>
                                <div class="text-xs w-full py-1 flex items-center" v-if="producto.idDetRequerimiento">
                                    <div class="h-8 border-l-4 border-slate-500 pl-3 opacity-40"></div>
                                    <div class="flex-1">
                                        <Multiselect @select="setIdProductoByDetalleExistenciaAlmacenId($event, i, j)"
                                            v-model="producto.idDetExistenciaAlmacen" :loading="isLoadinProduct"
                                            :disabled="isLoadinProduct || !canEditReq"
                                            :classes="{ containerDisabled: 'bg-gray-200 text-text-slate-400', container: `relative mx-auto w-full h-7 flex items-center justify-end box-border   border border-gray-300 rounded  text-base leading-snug outline-none ${canEditReq ? 'bg-white' : ''}`, optionSelectedDisabled: 'text-white bg-[#001c48] bg-opacity-50 cursor-not-allowed', optionPointed: 'text-gray-800 bg-gray-100', }"
                                            :filter-results="false" :searchable="true" :clear-on-search="true"
                                            :min-chars="1" :options="productosArray"
                                            noResultsText="<p class='text-xs'>Sin resultados de personas</p>"
                                            placeholder="TODOS LOS PRODUCTOS"
                                            noOptionsText="<p class='text-xs'>PRODUCTOS FILTRADOS POR LINEA DE TRABAJO Y PROYECTO FINANCIADO</p>" />
                                    </div>
                                </div>

                                <div class="text-xs w-full py-1 flex items-center" v-else>
                                    <div class="h-8 border-l-4 border-slate-500 pl-3 opacity-40"></div>
                                    <div class="flex-1">
                                        <Multiselect @select="setIdProductoByDetalleExistenciaAlmacenId($event, i, j)"
                                            v-model="producto.idDetExistenciaAlmacen" :loading="isLoadinProduct"
                                            :disabled="isLoadinProduct || !canEditReq"
                                            :classes="{ containerDisabled: 'bg-gray-200 text-text-slate-400', container: `relative mx-auto w-full h-7 flex items-center justify-end box-border   border border-gray-300 rounded  text-base leading-snug outline-none ${canEditReq ? 'bg-white' : ''}`, optionSelectedDisabled: 'text-white bg-[#001c48] bg-opacity-50 cursor-not-allowed', optionPointed: 'text-gray-800 bg-gray-100', }"
                                            :filter-results="false" :searchable="true" :clear-on-search="true"
                                            :min-chars="1" :options="productoArrayWithOutProductNoStock"
                                            noResultsText="<p class='text-xs'>Sin resultados de personas</p>"
                                            placeholder="TODOS LOS PRODUCTOS"
                                            noOptionsText="<p class='text-xs'>PRODUCTOS FILTRADOS POR LINEA DE TRABAJO Y PROYECTO FINANCIADO</p>" />
                                    </div>
                                </div>

                                <div class="text-xs py-1 ">
                                    <DropDownOptions v-if="canEditReq">
                                        <div class="flex items-center hover:bg-gray-100 py-1 px-2 rounded cursor-pointer"
                                            @click.stop="producto.stateProducto = 0">
                                            <div class="w-8 text-red-900">
                                                <span class="text-xs">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        class="w-6 h-6">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M12 9.75 14.25 12m0 0 2.25 2.25M14.25 12l2.25-2.25M14.25 12 12 14.25m-2.58 4.92-6.374-6.375a1.125 1.125 0 0 1 0-1.59L9.42 4.83c.21-.211.497-.33.795-.33H19.5a2.25 2.25 0 0 1 2.25 2.25v10.5a2.25 2.25 0 0 1-2.25 2.25h-9.284c-.298 0-.585-.119-.795-.33Z" />
                                                    </svg>

                                                </span>
                                            </div>
                                            <div class="font-semibold">Eliminar</div>
                                        </div>

                                    </DropDownOptions>

                                </div>
                            </div>
                            <div class="text-xs bg-slate-100 border-b-4 border-slate-400  w-full text-center"
                                id="seccion-error">

                                <div v-if="errorsValidation &&
            errorsValidation[`dataDetalleRequerimiento.${i}.productos.${j}.cantDetRequerimiento`] || errorsValidation &&
            errorsValidation[`dataDetalleRequerimiento.${i}.productos.${j}.idDetExistenciaAlmacen`]"
                                    class="flex justify-center items-center m-1 font-medium py-1 px-2  rounded-md text-red-700 bg-red-100 border border-red-300 ">
                                    <div slot="avatar">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" class="feather feather-alert-octagon w-4 h-4 mx-2">
                                            <polygon
                                                points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2">
                                            </polygon>
                                            <line x1="12" y1="8" x2="12" y2="12"></line>
                                            <line x1="12" y1="16" x2="12.01" y2="16"></line>
                                        </svg>
                                    </div>
                                    <div class="text-[8pt] text-black font-normal  max-w-full flex-initial"> {{
            errorsValidation &&
            errorsValidation[`dataDetalleRequerimiento.${i}.productos.${j}.cantDetRequerimiento`]
        }}
                                    </div>
                                    <div class="text-[8pt] text-black font-normal  max-w-full flex-initial"> {{
                errorsValidation &&
                errorsValidation[`dataDetalleRequerimiento.${i}.productos.${j}.idDetExistenciaAlmacen`]
            }}

                                    </div>

                                </div>
                            </div>
                            <div class="text-xs bg-slate-100 border-b-4 border-slate-400  w-full text-center"
                                id="seccion-error" v-if="productosArray?.find(index => index.value ==
                producto.idDetExistenciaAlmacen)?.stock < 0">

                                <div
                                    class="flex justify-center items-center m-1 font-medium py-1 px-2  rounded-md text-yellow-700 bg-yellow-100 border border-yellow-300 ">

                                    <div class="text-base font-normal  max-w-full flex-initial">
                                        <div class="py-2">Producto agotado:
                                            <div class="text-sm font-base">El almacen no tiene disponible este producto
                                            </div>


                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div @click="appendProduct(i)" v-if="canEditReq"
                            class="hover:bg-slate-200 hover:border-slate-500 w-full h-20 border-4 border-dashed border-slate-400 my-4 flex items-center justify-center bg-white">
                            <span class="uppercase text-left text-xs">
                                + agregar en: {{ centroProduccion?.find(index => index.value ==
                                detalleRequerimiento.idCentroProduccion)?.label || 'N/A' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </ProcessModal>
    </div>
</template>

<style scoped></style>
