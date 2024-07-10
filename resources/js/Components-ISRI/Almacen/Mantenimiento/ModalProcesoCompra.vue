<template>
    <Modal modal-title="Gestión de Procesos de Compras." maxWidth="2xl" :show="ModalIsOpen"
        @close="$emit('close-modal')">
        <div class="py-5">
            <div class="flex-row justify-center items-center mb-7 mx-4">

                <div class="mb-4 md:flex flex-row justify-items-start mx-8">
                    <div class="  mb-7 w-full">
                        <label class="block text-gray-700 text-xs font-medium mb-1" for="name">Nombre del
                            empleado <span class="text-red-600 font-extrabold">*</span></label>

                        <div class="relative flex h-8 w-full flex-row-reverse ">
                            <Multiselect v-model="idEmpleado" :disabled="loadingEvaluacionRendimiento"
                                @open="handleStage(true)" @close="handleStage(false)" :filter-results="false"
                                :resolve-on-load="false" :delay="1000" :searchable="true" :clear-on-search="true"
                                :min-chars="1" placeholder="Busqueda de empleado..." :classes="{
        placeholder: 'flex items-center text-center h-full absolute left-0 top-0 pointer-events-none bg-transparent leading-snug pl-3.5 text-gray-400 rtl:left-auto rtl:right-0 rtl:pl-0 rtl:pr-3.5',
    }" noOptionsText="<p class='text-xs'>Lista vacia<p>"
                                noResultsText="<p class='text-xs'>Sin resultados de personas <p>" :options="buscarpornombre ?

        async function (query) { return await handleEmployeeSearch(query) } : objectEmployee

        " />
                        </div>

                    </div>
                </div>


                <!-- Buttons -->
                <div class="mt-4 mb-4 md:flex flex-row justify-center">
                    <GeneralButton v-if="dataProcesoCompra" @click="updateProcesoCompra()"
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
        dataProcesoCompra: { type: Object, default: () => ({}) }
    },
    setup(props, { emit }) {

        const { ModalIsOpen, dataProcesoCompra } = toRefs(props);

        const nombreProcesoCompra = ref(null)
        const tipoProcesoCompra = ref(null)
        const idProcesoCompra = ref(null)
        const idEmpleado = ref(null)



        watch(dataProcesoCompra, (newValue, oldValue) => {
            console.log(newValue);
            if (newValue != null) {
                idProcesoCompra.value = newValue.id_proceso_compra
                nombreProcesoCompra.value = newValue.nombre_proceso_compra
                tipoProcesoCompra.value = newValue.id_tipo_proceso_compra
                idEmpleado.value = newValue.id_empleado
            } else {
                idProcesoCompra.value = null
                nombreProcesoCompra.value = null
                tipoProcesoCompra.value = null
                idEmpleado.value = null

            }
        })

        const objectEmployee = computed(() => {
            console.log("...");
            if (dataProcesoCompra.value && dataProcesoCompra.value.empleado && dataProcesoCompra.value.empleado.persona) {
                const persona = dataProcesoCompra.value.empleado.persona;
                return [
                    { value: persona.id_persona, label: `${persona.nombre_apellido || ''}` }
                ];
            }
            return [];
        });


        /**
    * Busca empleados por nombre para evaluaciones.
    *
    * @param {string} nombreToSearch - Nombre a buscar.
    * @returns {Promise<object>} - Objeto con los datos de la respuesta.
    * @throws {Error} - Error al obtener empleados por nombre.
    */
        const handleEmployeeSearch = async (nombreToSearch) => {
            try {
                // Realiza la búsqueda de empleados
                const response = await axios.post(
                    "/find-employee-by-name-for-warehouse",
                    {
                        nombre: nombreToSearch,
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
                    "/update-employee-in-proceso-compra",
                    {
                        idProcesoCompra: idProcesoCompra.value,
                        idEmpleado: idEmpleado.value
                    }
                );

                // Devuelve los datos de la respuesta
                return response.data;
            } catch (error) {
                // Manejo de errores específicos  n
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


        const buscarpornombre = ref(false)
        const handleStage = (log) => {
            buscarpornombre.value = log
        }


        return {
            ModalIsOpen,
            nombreProcesoCompra,
            tipoProcesoCompra,
            dataProcesoCompra,
            idEmpleado,
            objectEmployee,
            buscarpornombre,

            updateProcesoCompra,
            saveSubAlmacen,
            handleEmployeeSearch,
            handleStage,
        }
    }
}
</script>

<style lang="scss" scoped></style>
