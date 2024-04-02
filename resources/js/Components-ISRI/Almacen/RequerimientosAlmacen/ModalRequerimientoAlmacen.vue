<script lang="ts">
import { defineComponent, ref, toRefs, watch, onMounted } from 'vue';

import ButtonCloseModal from "@/Components/ButtonCloseModal.vue";
import ProcessModal from "@/Components-ISRI/AllModal/ProcessModal.vue";
import TitleModalReq from "./TitleModalReq.vue";
import OnlyLabelInput from '@/Components-ISRI/ComponentsToForms/OnlyLabelInput.vue';
import { useRequerimientoAlmacen } from '../../../Composables/Almacen/RequerimientoAlmacen/useRequerimientoAlmacen.js'
import Swal from 'sweetalert2';
import { executeRequest } from "@/plugins/requestHelpers";
import axios from 'axios';
import InputError from '@/Components/InputError.vue';

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
        }
    },
    setup(props, { emit }) {
        const { objectRequerimientoToSendModal, showModal } = toRefs(props)
        const optionsCentroAtencion = ref(null)
        const { dataDetalleRequerimiento, appendProduct, appendDetalleRequerimiento, proyectoFinanciados,
            productosArray, setDescripcionProducto,
            centroAtenionArray, centroProduccion, idRequerimiento,
            idLt,
            idCentroAtencion,
            idProyFinanciado, handleProductSearch,
            idEstadoReq, errorsValidation,
            numRequerimiento,
            cantPersonalRequerimiento,
            fechaRequerimiento, updateRequerimientoAlmacenRequest,
            observacionRequerimiento, saveRequerimientoAlmacenRequest,
            lineaTrabajoArray, } = useRequerimientoAlmacen()

        watch(objectRequerimientoToSendModal, (newValue, oldValue) => {

            if (newValue !== null && newValue !== undefined && (Array.isArray(newValue) ? newValue.length > 0 : newValue !== '')) {
                const { detalles_requerimiento, id_lt, id_centro_atencion, id_proy_financiado, id_estado_req, num_requerimiento, id_requerimiento, cant_personal_requerimiento, observacion_requerimiento } = newValue
                idRequerimiento.value = id_requerimiento
                idLt.value = id_lt
                idCentroAtencion.value = id_centro_atencion
                idProyFinanciado.value = id_proy_financiado
                idEstadoReq.value = id_estado_req
                numRequerimiento.value = num_requerimiento
                cantPersonalRequerimiento.value = cant_personal_requerimiento
                observacionRequerimiento.value = observacion_requerimiento
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
                            stateCentroProduccion: 1,
                            isHidden: false,
                        };
                    }
                })
                    .filter(Boolean);
                detalles_requerimiento.forEach(index => {
                    let indice = dataDetalleRequerimiento.value.findIndex(i => i.idCentroProduccion == index.id_centro_produccion);
                    const { producto } = index
                    dataDetalleRequerimiento.value[indice]["productos"].push({
                        idDetRequerimiento: index.id_det_requerimiento,
                        idProducto: producto.id_producto,
                        descripcionProductos: producto.descripcion_producto,
                        cantDetRequerimiento: index.cant_det_requerimiento,
                        costoDetRequerimiento: index.costo_det_requerimiento,
                        stateProducto: 1,

                    });
                });
                getProductoByDependencia()
            } else {
                dataDetalleRequerimiento.value = [];
                idRequerimiento.value = null
                idLt.value = null
                /* idCentroAtencion.value = null */
                idProyFinanciado.value = null
                idEstadoReq.value = null
                numRequerimiento.value = null
                cantPersonalRequerimiento.value = null
                observacionRequerimiento.value = null
                appendDetalleRequerimiento()

            }

        })

        watch(showModal, (newValue, oldValue) => {
            if (!newValue) {
                productosArray.value = []
            }
        })
        /**
         * Guarda productos adquisicion
         *
         * @param {string} productCode - codigo del producto a buscar.
         * @returns {Promise<object>} - Objeto con los datos de la respuesta.
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
          * Guarda productos adquisicion
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

        /**
      * Obtener la dependencia por usuario.
      *
      * @returns {Promise<object>} - Promesa que se resuelve con la respuesta exitosa o se rechaza con el error.
      */
        const getDependenciaByUser = () => {
            return new Promise(async (resolve, reject) => {
                try {
                    // Hace una solicitud POST a la ruta "/save-prod-adquicicion" con los datos necesarios
                    const resp = await axios.post("/get-centro-by-user");
                    console.log(resp.data.length);

                    if (resp.data.length == 1) {
                        idCentroAtencion.value = resp.data[0].id_centro_atencion
                        console.log(idCentroAtencion.value);

                    }

                    optionsCentroAtencion.value = resp.data.map(index => {
                        return { value: index.id_centro_atencion, label: `${index.codigo_centro_atencion} - ${index.nombre_centro_atencion}`, disabled: false };
                    })
                    // Se resuelve la promesa con la respuesta exitosa de la solicitud
                    resolve(resp);
                } catch (error) {
                    console.log(error);

                    reject(error);
                }
            });
        }

        /**
    * Obtener la dependencia por usuario.
    *
    * @returns {Promise<object>} - Promesa que se resuelve con la respuesta exitosa o se rechaza con el error.
    */
        const getProductoByDependencia = () => {
            return new Promise(async (resolve, reject) => {
                try {
                    console.log(idCentroAtencion.value);

                    // Hace una solicitud POST a la ruta "/save-prod-adquicicion" con los datos necesarios
                    const resp = await axios.post("/get-product-by-proy-financiado", {
                        idCentroAtencion: idCentroAtencion.value,
                        idProyFinanciado: idProyFinanciado.value,
                        idLt: idLt.value,
                    });
                    console.log(resp);
                    productosArray.value = resp.data.map(index => {
                        return { value: index.productos.id_producto, label: `${index.productos.codigo_producto} - ${index.productos.nombre_producto}`, completeData: index.productos };
                    })
                    // Se resuelve la promesa con la respuesta exitosa de la solicitud
                    resolve(resp);
                } catch (error) {
                    console.log(error);

                    reject(error);
                }
            });
        }

        onMounted(() => {
            getDependenciaByUser()
            //getProductoByDependencia()
        })
        return {
            dataDetalleRequerimiento, appendProduct, appendDetalleRequerimiento, proyectoFinanciados,
            productosArray, setDescripcionProducto,
            centroAtenionArray, centroProduccion, idRequerimiento,
            idLt,
            idCentroAtencion,
            idProyFinanciado, errorsValidation,
            optionsCentroAtencion, getProductoByDependencia,
            idEstadoReq, handleProductSearch,
            numRequerimiento,
            cantPersonalRequerimiento,
            fechaRequerimiento, updateRequerimientoAlmacen,
            observacionRequerimiento, saveRequerimientoAlmacen,
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
                <div id="formulario-principal">
                    <pre>
                    {{ errorsValidation }}
                    </pre>
                    <div class="pt-4 flex justify-between space-x-2">
                        <!-- {{ dataDetalleRequerimiento }} -->
                        <div class="w-full">
                            <OnlyLabelInput textLabel="Linea de trabajo" />
                            <Multiselect v-model="idLt" @select="getProductoByDependencia()"
                                :classes='{ containerDisabled: "bg-gray-200 text-text-slate-400", container: "relative mx-auto w-full h-7 flex items-center justify-end box-border   border border-gray-300 rounded bg-white text-base leading-snug outline-none", optionSelectedDisabled: "text-white bg-[#001c48] bg-opacity-50 cursor-not-allowed", optionPointed: "text-gray-800 bg-gray-100", }'
                                :filter-results="false" :searchable="true" :clear-on-search="true" :min-chars="1"
                                :options="lineaTrabajoArray"
                                noResultsText="<p class='text-xs'>Sin resultados de personas</p>" placeholder="-"
                                noOptionsText="<p class='text-xs'>vacio</p>" />
                            <InputError class="mt-2" v-if="errorsValidation && errorsValidation['idLt'] !== ''"
                                :message="errorsValidation['idLt']" />
                        </div>

                        <div class="w-full">
                            <OnlyLabelInput textLabel="Proyecto financiamiento" />

                            <Multiselect v-model="idProyFinanciado" @select="getProductoByDependencia()"
                                :classes='{ containerDisabled: "bg-gray-200 text-text-slate-400", container: "relative mx-auto w-full h-7 flex items-center justify-end box-border   border border-gray-300 rounded bg-white text-base leading-snug outline-none", optionSelectedDisabled: "text-white bg-[#001c48] bg-opacity-50 cursor-not-allowed", optionPointed: "text-gray-800 bg-gray-100", }'
                                :filter-results="false" :searchable="true" :clear-on-search="true" :min-chars="1"
                                :options="proyectoFinanciados"
                                noResultsText="<p class='text-xs'>Sin resultados de personas</p>" placeholder="-"
                                noOptionsText="<p class='text-xs'>vacio</p>" />
                            <InputError class="mt-2"
                                v-if="errorsValidation && errorsValidation['idProyFinanciado'] !== ''"
                                :message="errorsValidation['idProyFinanciado']" />

                        </div>
                        <div class="w-full">
                            <OnlyLabelInput textLabel="Centro de atencion" />

                            <template v-if="optionsCentroAtencion && optionsCentroAtencion.length > 1">
                                <Multiselect v-model="idCentroAtencion" @select="getProductoByDependencia"
                                    :classes='{ containerDisabled: "bg-gray-200 text-text-slate-400", container: "relative mx-auto w-full h-7 flex items-center justify-end box-border border border-gray-300 rounded bg-white text-base leading-snug outline-none", optionSelectedDisabled: "text-white bg-[#001c48] bg-opacity-50 cursor-not-allowed", optionPointed: "text-gray-800 bg-gray-100", }'
                                    :filter-results="false" :searchable="true" :clear-on-search="true" :min-chars="1"
                                    :options="optionsCentroAtencion"
                                    noResultsText="<p class='text-xs'>Sin resultados de personas</p>" placeholder="-"
                                    noOptionsText="<p class='text-xs'>vacio</p>" />
                            </template>

                            <template v-else>
                                <div class="text-xs items-center">
                                    <span v-if="optionsCentroAtencion && optionsCentroAtencion[0]">
                                        {{ optionsCentroAtencion[0].label }}</span>
                                    <span v-else>-</span>
                                </div>
                            </template>

                            <InputError class="mt-2"
                                v-if="errorsValidation && errorsValidation['idCentroAtencion'] !== ''"
                                :message="errorsValidation['idCentroAtencion']" />
                        </div>

                    </div>
                    <div class="pt-4 flex justify-start space-x-2 items-end">
                        <div class="flex flex-col gap-1">
                            <OnlyLabelInput textLabel="Cantidad de personal" />
                            <input type="text" class="h-7 border-slate-300 text-xs" placeholder="-"
                                v-model="cantPersonalRequerimiento">
                            <InputError class="mt-2"
                                v-if="errorsValidation && errorsValidation['cantPersonalRequerimiento'] !== ''"
                                :message="errorsValidation['cantPersonalRequerimiento']" />
                        </div>
                        <div class="flex flex-col gap-1">
                            <OnlyLabelInput textLabel="Numero requerimiento" />
                            <input type="text" class="h-7 border-slate-300 text-xs" placeholder="-"
                                v-model="numRequerimiento">
                            <InputError class="mt-2"
                                v-if="errorsValidation && errorsValidation['numRequerimiento'] !== ''"
                                :message="errorsValidation['numRequerimiento']" />
                        </div>
                    </div>
                    <div class="pt-4 flex justify-start space-x-2 items-end">

                        <textarea placeholder='Observacion del requerimiento' rows="2" name=''
                            v-model="observacionRequerimiento"
                            class="w-full rounded-md px-4 text-xs border-slate-300 border pt-2.5 outline-[#007bff]"></textarea>

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
                    <div class="pt-4 flex justify-start space-x-2 items-end" v-else>
                        <button
                            class=" bg-indigo-700 rounded px-2 text-xs h-6 text-slate-200 hover:text-white hover:bg-indigo-600 ">
                            <div class="flex items-center space-x-1">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5" />
                                </svg>
                                <span @click="updateRequerimientoAlmacen">Actualizar y cerrar</span>
                            </div>
                        </button>
                    </div>
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
                                        <DropDownOptions>
                                            <div class="flex hover:bg-gray-100 py-1 px-2 rounded cursor-pointer">
                                                <div class="w-8 text-blue-900">
                                                    <span class="text-xs">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                            class="w-6 h-6">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                                        </svg>

                                                    </span>
                                                </div>
                                                <div class="font-semibold">Ver</div>
                                            </div>

                                        </DropDownOptions>
                                    </div>
                                </button>
                            </div>
                        </div>

                        <div v-show="!detalleRequerimiento.isHidden"
                            class="border-b-4 px-2 text-xs flex justify-end  space-x-4 items-center bg-slate-100 hover:bg-slate-200"
                            v-for="(producto, j ) in detalleRequerimiento.productos" :key="j">
                            <template v-if="producto.stateProducto == 1">
                                <div class="text-xs w-20 ">
                                    <input type="text" v-model="producto.cantDetRequerimiento"
                                        class="bg-white text-center border-0 h-6 text-xs w-20 border-x-transparentborder-t-transparent bg-transparent focus:border-x-transparentfocus:border-t-transparent"
                                        placeholder="CANT">
                                </div>
                                <div class="text-xs w-64 py-1 flex items-center">
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
                                    <DropDownOptions>
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
                            </template>

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

        </ProcessModal>
    </div>
</template>
<style scoped></style>
