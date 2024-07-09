<template>
    <Modal modal-title="Gestión de Sub Almacen." maxWidth="2xl" :show="ModalIsOpen" @close="$emit('close-modal')">
        <div class="py-5">
            <div class="flex-row justify-center items-center mb-7 mx-4">
                <div class="mb-4 md:flex flex-row justify-items-start mx-8">
                    <div class="md:mr-2 md:mb-0 w-full">
                        <TextInput :label-input="true" v-model="nombreSubAlmacen" id="numero-requerimiento" type="text"
                            placeholder="Nombre de sub almacen">
                        </TextInput>
                    </div>
                </div>
                <div class="mb-4 md:flex flex-row justify-items-start mx-8">
                    <div class="  mb-7 w-full">
                        <label class="block text-gray-700 text-xs font-medium mb-1" for="name">Nombre del
                            empleado <span class="text-red-600 font-extrabold">*</span></label>
                        <div class="relative flex h-8 w-full flex-row-reverse ">
                            <Multiselect v-model="idEmpleado" :disabled="loadingEvaluacionRendimiento"
                                @open="handleStage(true)" @close="handleStage(false)"
                                :filter-results="false" :resolve-on-load="false" :delay="1000" :searchable="true"
                                :clear-on-search="true" :min-chars="1" placeholder="Busqueda de empleado..." :classes="{
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
                    <GeneralButton v-if="dataSubAlmacen"@click="updateSubAlmacen()" color="bg-orange-700  hover:bg-orange-800"
                        text="Actualizar" icon="update" />
                    <GeneralButton v-else color="bg-green-700  hover:bg-green-800" text="Agregar" icon="add"
                        @click="saveSubAlmacen()" />
                    <div class="mb-4 md:mr-2 md:mb-0 px-1">
                        <GeneralButton text="Cancelar" icon="defaultBruh" @click="ModalIsOpen = false" />
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

export default {
    components: { Modal, TextInput },
    props: {
        ModalIsOpen: { type: Boolean, default: false, },
        dataSubAlmacen: { type: Object, default: () => ({}) }
    },
    setup(props,{ emit }) {

        const { ModalIsOpen, dataSubAlmacen } = toRefs(props);

        const idEmpleado = ref(null)
        const nombreSubAlmacen = ref(null)
        const idSubAlmacen = ref(null)

        watch(dataSubAlmacen, (newValue, oldValue) => {
            
            if (newValue != null) {
                idEmpleado.value = newValue.empleado.persona.id_persona
                idSubAlmacen.value = newValue.id_sub_almacen
                nombreSubAlmacen.value = newValue.nombre_sub_almacen
            } else {
                idEmpleado.value = null
                idSubAlmacen.value = null
                nombreSubAlmacen.value = null

            }
        })

        const objectEmployee = computed(() => {
            console.log("...");
            return dataSubAlmacen.value ? [
                { value: dataSubAlmacen.value.empleado.persona.id_persona, label: `${dataSubAlmacen.value.empleado.persona.nombre_apellido || ''}` }] : [];


        })

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
                    "/find-employee-sub-almacen",
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


        const saveSubAlmacenRequest = async (nombreToSearch) => {
            try {
                // Realiza la búsqueda de empleados
                const response = await axios.post(
                    "/save-sub-almacen",
                    {
                        nombreSubAlmacen: nombreSubAlmacen.value,
                        idEmpleado: idEmpleado.value,
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


        const updateSubAlmacenRequest = async (nombreToSearch) => {
            try {
                // Realiza la búsqueda de empleados
                const response = await axios.post(
                    "/update-sub-almacen",
                    {
                        nombreSubAlmacen: nombreSubAlmacen.value,
                        idEmpleado: idEmpleado.value,
                        idSubAlmacen: idSubAlmacen.value
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
         const updateSubAlmacen = async () => {
            const confirmed = await Swal.fire({
                title: '<p class="text-[15pt]">¿Está seguro de editar Sub Almacen?</p>',
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
                    updateSubAlmacenRequest(),
                    "El Sub Almacen se ha actualizado correctamente!"
                );
                emit("actualizar-datatable");
            }
        }

           /**
         * guardando sub almacen
         *
         * @param {string} productCode - codigo del producto a buscar.
         * @returns {Promise<object>} - Objeto con los datos de la respuesta.
         * @throws {Error} - Error al obtener empleados por nombre.
         */
         const saveSubAlmacen = async () => {
            const confirmed = await Swal.fire({
                title: '<p class="text-[15pt]">¿Está seguro de agregar un nuevo Sub Almacen?</p>',
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
                    saveSubAlmacenRequest(),
                    "El Sub Almacen se ha guardado correctamente!"
                );
                emit("actualizar-datatable");
            }
        }


        const buscarpornombre = ref(false)
        const handleStage = (log) => {
            buscarpornombre.value = log
        }

        return { objectEmployee, updateSubAlmacenRequest,updateSubAlmacen, idSubAlmacen, handleStage, buscarpornombre, ModalIsOpen, handleEmployeeSearch, idEmpleado, nombreSubAlmacen, saveSubAlmacen, dataSubAlmacen }
    }
}
</script>

<style lang="scss" scoped></style>
