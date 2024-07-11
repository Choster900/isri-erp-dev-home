<template>
    <Modal modal-title="Gestión de Procesos de Compras." maxWidth="2xl" :show="ModalIsOpen"
        @close="$emit('close-modal')">
        <div class="py-5">
            <div class="flex flex-col justify-center items-center mb-7 mx-4">
                <div class="mb-4 md:flex md:flex-row justify-items-start mx-8 w-full">
                    <div class="md:mr-2 md:mb-0 w-full">
                        <TextInput :label-input="true" id="numero-requerimiento" type="text"
                            v-model="nombreProcesoCompra" placeholder="Nombre proceso de compra">
                        </TextInput>
                    </div>
                </div>
                <div class="mb-4 md:flex md:flex-row justify-items-start mx-8 space-x-2 w-full">
                    <div
                        class="w-full md:w-1/2 h-9 flex items-center ps-4 border-2 border-gray-500 rounded mb-2 md:mb-0">
                        <input id="bordered-radio-1" type="radio" value="1" v-model="tipoProcesoCompra"
                            name="bordered-radio"
                            class="w-4 h-4 text-[#001c48] bg-gray-100 border-gray-300 focus:ring-[#001c48]">
                        <label for="bordered-radio-1"
                            class="w-full py-4 ms-2 text-sm font-medium text-gray-900">Producto</label>
                    </div>
                    <div class="w-full md:w-1/2 h-9 flex items-center ps-4 border-2 border-gray-500 rounded">
                        <input checked id="bordered-radio-2" type="radio" value="2" v-model="tipoProcesoCompra"
                            name="bordered-radio"
                            class="w-4 h-4 text-[#001c48] bg-gray-100 border-gray-300 focus:ring-[#001c48]">
                        <label for="bordered-radio-2"
                            class="w-full py-4 ms-2 text-sm font-medium text-gray-900">Servicio</label>
                    </div>
                </div>
                {{ dataProcesoCompra }}
                <!-- Buttons -->
                <div class="mt-4 mb-4 md:flex flex-row justify-center">
                    <GeneralButton v-if="dataProcesoCompra != ''" @click="updateProcesoCompra()"
                        color="bg-orange-700  hover:bg-orange-800" text="Actualizar" icon="update" />
                    <GeneralButton v-else @click="saveSubAlmacen()" color="bg-green-700  hover:bg-green-800"
                        text="Agregar" icon="add" />
                    <div class="mb-4 md:mr-2 md:mb-0 px-1" @click="$emit('close-modal')">
                        <GeneralButton text="Cancelar" icon="defaultBruh" />
                    </div>
                </div>
            </div>
        </div>
    </Modal>
</template>

<script>
import Modal from "@/Components-ISRI/AllModal/Modal.vue";
import TextInput from '@/Components-ISRI/ComponentsToForms/TextInput.vue';
import Swal from 'sweetalert2';
import { toRefs, reactive, ref, watch, computed } from 'vue';
import { executeRequest } from '@/plugins/requestHelpers';
import RadioButton from "../../../Components-ISRI/ComponentsToForms/RadioButton.vue"
export default {
    name: 'ModalProcesoCompra',
    components: { Modal, TextInput, RadioButton },
    props: {
        ModalIsOpen: { type: Boolean, default: false, },
        dataProcesoCompra: { type: Object, default: () => [] }
    },
    setup(props, { emit }) {

        const { ModalIsOpen, dataProcesoCompra } = toRefs(props);

        const nombreProcesoCompra = ref(null)
        const tipoProcesoCompra = ref(null)
        const idProcesoCompra = ref(null)



        watch(dataProcesoCompra, (newValue, oldValue) => {

            if (newValue != null) {
                idProcesoCompra.value = newValue.id_proceso_compra
                nombreProcesoCompra.value = newValue.nombre_proceso_compra
                tipoProcesoCompra.value = newValue.id_tipo_proceso_compra
            } else {
                idProcesoCompra.value = null
                nombreProcesoCompra.value = null
                tipoProcesoCompra.value = null

            }
        })



        /**
      * guardando sub almacen
      *
      * @param {string} productCode - codigo del producto a buscar.
      * @returns {Promise<object>} - Objeto con los datos de la respuesta.
      * @throws {Error} - Error al obtener empleados por nombre.
      */
        const saveSubAlmacen = async () => {
            const confirmed = await Swal.fire({
                title: '<p class="text-[15pt]">¿Está seguro de agregar un nuevo proceso de compra?</p>',
                text: "Al confirmar esta acción, los cambios se guardarán de manera instantánea. Tenga en cuenta que serán permanentes una vez confirmada la operación.",
                icon: "question",
                iconHtml: `<lord-icon src="https://cdn.lordicon.com/enzmygww.json" trigger="loop" delay="500" colors="primary:#121331" style="width:100px;height:100px"></lord-icon>`,
                confirmButtonText: "Si, Actualizar",
                confirmButtonColor: "#001b47",
                cancelButtonText: "Cancelar",
                showCancelButton: true,
                showCloseButton: true,
            });
            if (confirmed.isConfirmed) {
                await executeRequest(
                    saveProcesoCompra(),
                    "El proceso de compra se ha guardado correctamente!"
                );
                emit("actualizar-datatable");
            }
        }



        const saveProcesoCompra = async (nombreToSearch) => {
            try {
                // Realiza la búsqueda de empleados
                const response = await axios.post(
                    "/save-proceso-compra",
                    {
                        nombreProcesoCompra: nombreProcesoCompra.value,
                        tipoProcesoCompra: tipoProcesoCompra.value,
                    }
                );

                // Devuelve los datos de la respuesta
                return response.data;
            } catch (error) {
                // Manejo de errores específicos
                console.error("Error al obtener empleados por nombre:", error);
                // Lanza el error para que pueda ser manejado por el componente que utiliza este composable
                throw new Error("Error en la búsqueda de empleados");
            }
        };




        const updateProcesoCompraRequest = async (nombreToSearch) => {
            try {
                // Realiza la búsqueda de empleados
                const response = await axios.post(
                    "/update-proceso-compra",
                    {
                        nombreProcesoCompra: nombreProcesoCompra.value,
                        tipoProcesoCompra: tipoProcesoCompra.value,
                        idProcesoCompra: idProcesoCompra.value
                    }
                );

                // Devuelve los datos de la respuesta
                return response.data;
            } catch (error) {
                // Manejo de errores específicos
                console.error("Error al obtener empleados por nombre:", error);
                // Lanza el error para que pueda ser manejado por el componente que utiliza este composable
                throw new Error("Error en la búsqueda de empleados");
            }
        };



        /**
      * editando sub almacen
      *
      * @param {string} productCode - codigo del producto a buscar.
      * @returns {Promise<object>} - Objeto con los datos de la respuesta.
      * @throws {Error} - Error al obtener empleados por nombre.
      */
        const updateProcesoCompra = async () => {
            const confirmed = await Swal.fire({
                title: '<p class="text-[15pt]">¿Está seguro de editar el proceso de compra?</p>',
                text: "Al confirmar esta acción, los cambios se guardarán de manera instantánea. Tenga en cuenta que serán permanentes una vez confirmada la operación.",
                icon: "question",
                iconHtml: `<lord-icon src="https://cdn.lordicon.com/enzmygww.json" trigger="loop" delay="500" colors="primary:#121331" style="width:100px;height:100px"></lord-icon>`,
                confirmButtonText: "Si, Actualizar",
                confirmButtonColor: "#001b47",
                cancelButtonText: "Cancelar",
                showCancelButton: true,
                showCloseButton: true,
            });
            if (confirmed.isConfirmed) {
                await executeRequest(
                    updateProcesoCompraRequest(),
                    "El proceso de compra se ha actualizado correctamente!"
                );
                emit("actualizar-datatable");
            }
        }


        return {
            ModalIsOpen,
            nombreProcesoCompra,
            tipoProcesoCompra,
            dataProcesoCompra,

            updateProcesoCompra,
            saveSubAlmacen,
        }
    }
}
</script>

<style lang="scss" scoped></style>
