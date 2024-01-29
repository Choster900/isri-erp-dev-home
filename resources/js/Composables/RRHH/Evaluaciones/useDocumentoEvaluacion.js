import axios from "axios";
import moment from "moment";
import { computed, ref, watch } from "vue";

/**
 * Hook para manejar la obtención de datos de evaluación y rubricas.
 * @returns {Object} Un objeto con funciones y variables relacionadas.
 */
export const useDocumentoEvaluacion = () => {
    // Estado para indicar si la carga está en progreso.
    const isLoadingObtenerCategoriaYRubrica = ref(false);

    // Referencia para almacenar datos de rubrica y categoría por evaluación.
    const rubricaAndCategoriaByEvaluacion = ref(null);

    const optionsSelected = ref([])

    const evaluacionPersonal = ref(null);

    // Maneja la visibilidad del boton de enviar evaluacion
    const arrayShowButtons = ref([]);

    const ranges = ref([
        { label: 'Excelente', min: 73, max: 84 },
        { label: 'Muy Bueno', min: 56, max: 72 },
        { label: 'Bueno', min: 28, max: 55 },
        { label: 'Insatisfactorio', min: 0, max: 27 },
    ])
    /**
     * Obtiene la información de categoría y rubrica de rendimiento por evaluación.
     * @param {number} idEvaluacionRendimiento - Identificador de la evaluación de rendimiento.
     */
    const obtenerCategoriaYRubricaRendimiento = async (idEvaluacionRendimiento) => {
        try {
            // Inicia el indicador de carga.
            isLoadingObtenerCategoriaYRubrica.value = true;

            // Realiza la solicitud al servidor.
            const response = await axios.post('/get-evaluacion', { idEvaluacionRendimiento: idEvaluacionRendimiento });

            // Almacena la respuesta en la referencia.
            rubricaAndCategoriaByEvaluacion.value = response.data;
        } catch (error) {
            // Maneja los errores imprimiéndolos en la consola.
            console.error('Error en la búsqueda:', error);
        } finally {
            // Detiene el indicador de carga, independientemente de si hubo éxito o error.
            isLoadingObtenerCategoriaYRubrica.value = false;
        }
    };

    const separarTexto = (texto) => {
        const regex = /\bPARA\b/g;
        // Separar la cadena usando "PARA" como delimitador
        const palabrasSeparadas = texto.split(regex);
        // Unir las palabras separadas con un espacio
        return palabrasSeparadas[1];
    };


    /**
     * Maneja el evento de hacer clic en una casilla de verificación y actualiza el array optionsSelected.
     * @param {Integer} idCategoria - El identificador de la categoría.
     * @param {Integer} idRubrica - El identificador de la rubrica.
     */
    const saveResponseWhenIsClickedCheckbox = (idCategoria, idRubrica, puntaje) => {
        // Buscar la posición del objeto en el array según el id de la categoría
        const objectResponseIndex = optionsSelected.value.findIndex(item => item.id_cat_rendimiento === idCategoria);

        // Si se encuentra el objeto en el array, eliminarlo
        if (objectResponseIndex !== -1) {
            optionsSelected.value.splice(objectResponseIndex, 1);
        }

        // Agregar un nuevo objeto al array con la información de la categoría y rubrica seleccionadas
        optionsSelected.value.push({
            id_cat_rendimiento: idCategoria,
            id_rubrica_rendimiento: idRubrica,
            puntaje_rubrica_rendimiento: puntaje,
        });

        // Imprimir el array actualizado en la consola para propósitos de depuración
        console.log("Array optionsSelected actualizado:", optionsSelected.value);
    };


    const sendResponsesEvaluation = async () => {
        try {

            const response = await axios.post('/save-evaluation-responses', {
                responseRendimiento: optionsSelected.value,
                idEvaluacionPersonal: evaluacionPersonal.value.id_evaluacion_personal
            });

            console.log(response);
        } catch (error) {
            console.error('Error en la búsqueda:', error);
        } finally {
        }
    };

    const isScoreInRange = (min, max) => {
        const totalScore = optionsSelected.value.reduce(
            (score, object) => score + parseFloat(object.puntaje_rubrica_rendimiento),
            0
        );
        return totalScore >= min && totalScore <= max;
    }


    // Retorna las funciones y variables necesarias para el componente que utiliza este hook.
    return {
        obtenerCategoriaYRubricaRendimiento,
        isScoreInRange,
        arrayShowButtons,
        isLoadingObtenerCategoriaYRubrica,
        ranges,
        rubricaAndCategoriaByEvaluacion,
        sendResponsesEvaluation,
        optionsSelected,
        separarTexto,
        evaluacionPersonal,
        saveResponseWhenIsClickedCheckbox,
    };
};
