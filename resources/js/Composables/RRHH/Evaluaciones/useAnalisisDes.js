import axios from "axios";
import { computed, onMounted, ref } from "vue";
import moment from 'moment';
import { v4 as uuidv4 } from 'uuid';
import Swal from "sweetalert2";

export const useAnalisisDes = (evaluacionPersonalProp) => {
    const objectCat = ref(null);
    const dataIndiceEvaluacion = ref([]);

    const opcionesCategoriaRendimiento = computed(() => {
        let objectPersonaOption = [];
        if (
            objectCat.value &&
            objectCat.value.length > 0
        ) {

            objectPersonaOption = objectCat.value.map(cat => ({
                value: cat.id_cat_rendimiento,
                label: cat.nombre_cat_rendimiento
            }));
            return objectPersonaOption;
        } else {
            return null;
        }
    });

    const addDataIndiceEvaluacion = () => {
        dataIndiceEvaluacion.value.push({
            idEvaluacionPersonal: evaluacionPersonalProp.value.data.id_evaluacion_personal,
            idCategoriaRendimiento: null,
            resultadoIncidenteEvaluacion: null,
            comentarioIncidenteEvaluacion: null,
            fechaRegIncidenteEvaluacion: moment().format('l'),
            idIncidenteEvaluacion: uuidv4(),
            isDeleted: false,
        })
    }
    const deleteRow = (index) => {
        const rowIndex = dataIndiceEvaluacion.value.findIndex(indices => indices.idIncidenteEvaluacion == index)
        if (rowIndex != -1) {
            Swal.fire({
                title: '<p class="text-[16pt] text-center">¿Esta seguro de eliminar?</p>',
                icon: "question",
                iconHtml: `<lord-icon src="https://cdn.lordicon.com/enzmygww.json" trigger="loop" delay="500" colors="primary:#121331" style="width:100px;height:100px"></lord-icon>`,
                confirmButtonText: "Si, Eliminar",
                confirmButtonColor: "#D2691E",
                cancelButtonText: "Cancelar",
                showCancelButton: true,
                showCloseButton: true,
            }).then((confirmed) => {
                if (confirmed.isConfirmed) {
                    console.log(dataIndiceEvaluacion.value[rowIndex]);
                    dataIndiceEvaluacion.value[rowIndex].isDeleted = true;
                }
            });

        }
    }


    /**
     * Guardar los incidentes de evaluación personal.
     *
     * @returns {Promise<object>} - Promesa que se resuelve con la respuesta exitosa.
     * @throws {Error} - Error en la creación de incidentes personal.
     */
    const saveIncidentesEvaluacionesRequest = async () => {
        try {
            const promises = dataIndiceEvaluacion.value.map(async (element) => {
                const response = await axios.post("/save-incidentes", {
                    dataIndiceEvaluacion: element,
                });

                if (response.data.inserted_id != null) {
                    element.idIncidenteEvaluacion = response.data.inserted_id;
                }
                return response;
            });

            const responses = await Promise.all(promises);
            return responses;
        } catch (error) {
            console.error("Error en la creación de incidentes personal:", error);
            throw new Error("Error en la creación de incidentes personal");
        }
    };


    return {
        deleteRow,
        saveIncidentesEvaluacionesRequest,
        addDataIndiceEvaluacion,
        objectCat,
        dataIndiceEvaluacion,
        opcionesCategoriaRendimiento,
    }
}
