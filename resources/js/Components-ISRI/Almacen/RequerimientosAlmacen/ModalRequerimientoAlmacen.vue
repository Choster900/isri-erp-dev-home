<script lang="ts">
import { defineComponent, ref, toRefs, watch } from "vue";

import ButtonCloseModal from "@/Components/ButtonCloseModal.vue";
import ProcessModal from "@/Components-ISRI/AllModal/ProcessModal.vue";
import TitleModalReq from "./TitleModalReq.vue";
import OnlyLabelInput from '@/Components-ISRI/ComponentsToForms/OnlyLabelInput.vue';
import { useRequerimientoAlmacen } from '../../../Composables/Almacen/RequerimientoAlmacen/useRequerimientoAlmacen.js'

export default defineComponent({
    components: { ProcessModal, ButtonCloseModal, TitleModalReq, OnlyLabelInput },
    emits: ["cerrar-modal", "actualizar-datatable"], // Cambiado a 'emits' en lugar de 'emit'
    props: {
        showModal: {
            type: Boolean,
            default: false,
        },
        objectRequerimientoToSendModal: {
            type: Object,
            default: () => { },
        }
    },
    setup(props, { emit }) {
        const { objectRequerimientoToSendModal } = toRefs(props)
        const { dataDetalleRequerimiento, appendProduct, appendDetalleRequerimiento, proyectoFinanciados,
            productosArray, setDescripcionProducto,
            centroAtenionArray, centroProduccion, idRequerimiento,
            idLt,
            idCentroAtencion,
            idProyFinanciado,
            idEstadoReq,
            numRequerimiento,
            cantPersonalRequerimiento,
            fechaRequerimiento,
            observacionRequerimiento, insertRequerimiento,
            lineaTrabajoArray, } = useRequerimientoAlmacen();


        watch(objectRequerimientoToSendModal, (newValue, oldValue) => {
            console.log(newValue);
            const { detalles_requerimiento,id_lt,id_centro_atencion,id_proy_financiado,id_estado_req,num_requerimiento,cant_personal_requerimiento } = newValue

            idLt.value = id_lt
            idCentroAtencion.value = id_centro_atencion
            idProyFinanciado.value = id_proy_financiado
            idEstadoReq.value = id_estado_req,
            numRequerimiento.value = num_requerimiento
            cantPersonalRequerimiento.value = cant_personal_requerimiento

            let idCentroProduccionSet = new Set();

            dataDetalleRequerimiento.value = detalles_requerimiento.map(det => {
                let idCentroProduct = det.id_centro_produccion;
                if (!idCentroProduccionSet.has(idCentroProduct)) {
                    idCentroProduccionSet.add(idCentroProduct);
                    return {
                        idDetRequerimiento: det.id_det_requerimiento,
                        idRequerimiento: det.id_requerimiento,
                        idCentroProduccion: det.id_centro_produccion,
                        productos: [],
                    };
                }
            })
                .filter(Boolean);
            detalles_requerimiento.forEach(index => {
                let indice = dataDetalleRequerimiento.value.findIndex(i => i.idCentroProduccion == index.id_centro_produccion);
                const { producto } = index
                dataDetalleRequerimiento.value[indice]["productos"].push({
                    idProducto: producto.id_producto,
                    descripcionProductos: producto.nombre_producto,
                    cantDetRequerimiento: index.cant_det_requerimiento,
                    costoDetRequerimiento: index.costo_det_requerimiento,
                });
            });
        })
        return {
            dataDetalleRequerimiento, appendProduct, appendDetalleRequerimiento, proyectoFinanciados,
            productosArray, setDescripcionProducto,
            centroAtenionArray, centroProduccion, idRequerimiento,
            idLt,
            idCentroAtencion,
            idProyFinanciado,
            idEstadoReq,
            numRequerimiento,
            cantPersonalRequerimiento,
            fechaRequerimiento,
            observacionRequerimiento, insertRequerimiento,
            lineaTrabajoArray,
        }
    },
});
</script>

<template>
    <div class="m-1.5">
        <ProcessModal maxWidth="3xl" :show="showModal" @close="$emit('cerrar-modal')">
            <div class="py-4 px-3 ">
                <ButtonCloseModal @close="$emit('cerrar-modal')" />
                <TitleModalReq />
                <div class="pt-4 flex justify-between space-x-2">
                    <!-- {{ dataDetalleRequerimiento }} -->
                    <div class="w-full">
                        <OnlyLabelInput textLabel="Linea de trabajo" />
                        <Multiselect v-model="idLt"
                            :classes='{ containerDisabled: "bg-gray-200 text-text-slate-400", container: "relative mx-auto w-full h-7 flex items-center justify-end box-border   border border-gray-300 rounded bg-white text-base leading-snug outline-none", optionSelectedDisabled: "text-white bg-[#001c48] bg-opacity-50 cursor-not-allowed", optionPointed: "text-gray-800 bg-gray-100", }'
                            :filter-results="false" :searchable="true" :clear-on-search="true" :min-chars="1"
                            :options="lineaTrabajoArray"
                            noResultsText="<p class='text-xs'>Sin resultados de personas</p>" placeholder="-"
                            noOptionsText="<p class='text-xs'>vacio</p>" />
                    </div>

                    <div class="w-full">
                        <OnlyLabelInput textLabel="Proyecto financiamiento" />

                        <Multiselect v-model="idProyFinanciado"
                            :classes='{ containerDisabled: "bg-gray-200 text-text-slate-400", container: "relative mx-auto w-full h-7 flex items-center justify-end box-border   border border-gray-300 rounded bg-white text-base leading-snug outline-none", optionSelectedDisabled: "text-white bg-[#001c48] bg-opacity-50 cursor-not-allowed", optionPointed: "text-gray-800 bg-gray-100", }'
                            :filter-results="false" :searchable="true" :clear-on-search="true" :min-chars="1"
                            :options="proyectoFinanciados"
                            noResultsText="<p class='text-xs'>Sin resultados de personas</p>" placeholder="-"
                            noOptionsText="<p class='text-xs'>vacio</p>" />
                    </div>
                    <div class="w-full">
                        <OnlyLabelInput textLabel="Centro de atencion" />

                        <Multiselect v-model="idCentroAtencion"
                            :classes='{ containerDisabled: "bg-gray-200 text-text-slate-400", container: "relative mx-auto w-full h-7 flex items-center justify-end box-border   border border-gray-300 rounded bg-white text-base leading-snug outline-none", optionSelectedDisabled: "text-white bg-[#001c48] bg-opacity-50 cursor-not-allowed", optionPointed: "text-gray-800 bg-gray-100", }'
                            :filter-results="false" :searchable="true" :clear-on-search="true" :min-chars="1"
                            :options="centroAtenionArray"
                            noResultsText="<p class='text-xs'>Sin resultados de personas</p>" placeholder="-"
                            noOptionsText="<p class='text-xs'>vacio</p>" />
                    </div>
                </div>
                <div class="pt-4 flex justify-start space-x-2 items-end">
                    <div class="flex flex-col gap-1">
                        <OnlyLabelInput textLabel="Cantidad de personal" />
                        <input type="text" class="h-7 border-slate-300 text-xs" placeholder="-"
                            v-model="cantPersonalRequerimiento">
                    </div>
                    <div class="flex flex-col gap-1">
                        <OnlyLabelInput textLabel="Numero requerimiento" />
                        <input type="text" class="h-7 border-slate-300 text-xs" placeholder="-"
                            v-model="numRequerimiento">
                    </div>
                </div>
                <div class="pt-4 flex justify-start space-x-2 items-end">

                    <textarea placeholder='Observacion del requerimiento' rows="2" name=''
                        v-model="observacionRequerimiento"
                        class="w-full rounded-md px-4 text-xs border-slate-300 border pt-2.5 outline-[#007bff]"></textarea>

                </div>
                <div class="pt-4 flex justify-start space-x-2 items-end">
                    <button
                        class=" bg-green-700 rounded px-2 text-xs h-6 text-slate-200 hover:text-white hover:bg-green-600 ">
                        <div class="flex items-center space-x-1">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5" />
                            </svg>
                            <span @click="insertRequerimiento">Guardar y cerrar</span>
                        </div>
                    </button>
                </div>


                <table class="mt-4 w-[740px] ">
                    <tr class=" *:text-xs *:text-slate-700">
                        <td class="w-[165px] text-end pr-7">CANTIDAD</td>
                        <td class="w-[145px] text-center">CODIGO PRODUCTO</td>
                        <td class="w- text-center">NOMBRE DEL PRODUCTO</td>
                    </tr>
                </table>
                <div id="secctionTableDetalleReq" class="w-[740px]">

                    <div v-for="(detalleRequerimiento, i) in dataDetalleRequerimiento" :key="i" class="">
                        <div
                            class=" flex space-x-3 justify-start items-center  px-2 bg-slate-100  hover:bg-slate-400/50 cursor-pointer">
                            <div class="flex space-x-3 items-center">
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
                                        :classes='{ containerDisabled: "bg-gray-200 text-text-slate-400", container: "relative mx-auto w-full h-7 flex items-center justify-end box-border   border border-gray-300 rounded bg-white text-base leading-snug outline-none", optionSelectedDisabled: "text-white bg-[#001c48] bg-opacity-50 cursor-not-allowed", optionPointed: "text-gray-800 bg-gray-100", }'
                                        :filter-results="false" :searchable="true" :clear-on-search="true"
                                        :min-chars="1" :options="centroProduccion"
                                        noResultsText="<p class='text-xs'>Sin resultados de personas</p>"
                                        placeholder="CENTRO DE PRODUCCION"
                                        noOptionsText="<p class='text-xs'>vacio</p>" />
                                </div>
                            </div>
                            <div class="flex space-x-3">
                                <button
                                    class="  rounded px-2 text-xs h-6 text-slate-700 hover:text-black hover:bg-white-600 ">
                                    <div class="flex items-center space-x-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M12 6.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 12.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 18.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5Z" />
                                        </svg>
                                        <!-- <span>modificar</span> -->
                                    </div>
                                </button>
                            </div>
                        </div>

                        <div v-show="!detalleRequerimiento.isHidden"
                            class="border-b-4 px-2 text-xs flex justify-end  space-x-4 items-center bg-slate-100 hover:bg-slate-200"
                            v-for="(producto, j ) in detalleRequerimiento.productos" :key="j">
                            <div class="text-xs w-20 ">
                                <input type="text" v-model="producto.cantDetRequerimiento"
                                    class="bg-white text-center border-0 h-6 text-xs w-20 border-x-transparentborder-t-transparent bg-transparent focus:border-x-transparentfocus:border-t-transparent"
                                    placeholder="CANT">
                            </div>
                            <div class="text-xs w-32 py-1 flex items-center">
                                <div class="h-8 border-l-4 border-slate-500 pl-3 opacity-40"></div>
                                <div class="flex-1">
                                    <Multiselect @select="setDescripcionProducto($event, i, j)"
                                        v-model="producto.idProducto"
                                        :classes="{ containerDisabled: ' bg-gray-200 text-text-slate-400', optionSelectedDisabled: 'text-white bg-[#001c48] bg-opacity-50 cursor-not-allowed', optionPointed: 'text-gray-800 bg-gray-100', container: `relative mx-auto w-full h-6 flex items-center justify-end box-border   border border-gray-300 rounded bg-white text-base leading-snug outline-none` }"
                                        :filter-results="false" :searchable="true" :clear-on-search="true"
                                        :min-chars="1" :options="productosArray"
                                        noResultsText="<p class='text-xs'>Sin resultados de personas</p>"
                                        placeholder="00-0" noOptionsText="<p class='text-xs'>vacio</p>" />
                                </div>
                            </div>
                            <div class="text-xs w-96 py-1 flex uppercase">
                                <div class="flex">
                                    <div class="h-full border-l-4 border-slate-500 pl-3 opacity-40"></div>
                                </div>
                                <div class="flex-1">
                                    {{ producto.descripcionProductos }}
                                </div>
                            </div>
                            <div class="text-xs py-1 ">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 6.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 12.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 18.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5Z" />
                                </svg>

                            </div>
                        </div>
                        <div @click="appendProduct(i)"
                            class="hover:bg-slate-200 hover:border-slate-500 w-full h-10 border-4 border-dashed border-slate-400 my-4 flex items-center justify-center bg-white">
                            <span class="uppercase text-left text-xs"> <!-- Alinea el texto a la izquierda -->
                                + agregar en: INSTRUCTORIA VOCACIONAL DE COSTURA INDUSTRIAL
                            </span>
                        </div>
                    </div>
                    <div @click="appendDetalleRequerimiento"
                        class="hover:bg-slate-200 hover:border-slate-500 w-full h-24 border-4 border-dashed border-slate-400 my-4 flex items-center justify-center bg-white">
                        <span class="uppercase text-left text-xs"> <!-- Alinea el texto a la izquierda -->
                            + Agregar Centro de produccion
                        </span>
                    </div>

                </div>
            </div>

            <!-- <pre>
{{ dataDetalleRequerimiento }}
</pre> -->

        </ProcessModal>
    </div>
</template>
<style scoped></style>
