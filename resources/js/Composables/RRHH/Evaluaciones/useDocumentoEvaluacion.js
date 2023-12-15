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
            console.log(response);
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

    // Retorna las funciones y variables necesarias para el componente que utiliza este hook.
    return {
        obtenerCategoriaYRubricaRendimiento,
        isLoadingObtenerCategoriaYRubrica,
        rubricaAndCategoriaByEvaluacion,
        separarTexto,
    };
};
