import axios from "axios";
import { ref } from "vue";

export const useEvaluacion = () => {
    const idEmpleado = ref(486);
    const idDependencia = ref("");
    const idEvaluacionRendimiento = ref("");
    const loadingEvaluacionRendimiento = ref("");
    const idTipoEvaluacion = ref("");
    const fechaInicioFechafin = ref("");

    const doesntExistResult = ref(false);
    const existMoreThanOne = ref(false);
    const showPlazasModal = ref(false);
    const objectPlazas = ref([]);
    const objectEvaluaciones = ref("");


    const plazaOptions = ref([]);
    const evaluationsOptions = ref([]);

    // Función para obtener personas por nombre
    const handleEmployeeSearch = async (nombreToSearch) => {
        try {
            // endpoint getPersonaByName se encuentra en PersonaController => nombre del metodo getPersonByCompleteName
            const response = await axios.post(
                "/search-employees-for-evaluations",
                {
                    nombre: nombreToSearch,
                }
            );
            console.log(response);
            // Devuelve los datos de la respuesta
            return response.data;
        } catch (error) {
            // Manejo de errores
            console.error("Error al obtener personas por nombre:", error);
            throw error; // Lanza el error para que pueda ser manejado por el componente que utiliza este composable
        }
    };

    const createPersonalEvaluationRequest = () => {
        return new Promise(async (resolve, reject) => {
            try {
                const resp = await axios.post("/create-new-evaluacion", {
                    idEvaluacionRendimiento: idEvaluacionRendimiento.value,
                    idEmpleado: idEmpleado.value,
                    idDependencia: idDependencia.value,
                    idTipoEvaluacion: idTipoEvaluacion.value,
                    fechaInicioFechafin: fechaInicioFechafin.value,
                });

                console.log(resp.data);

                resolve(resp); // Resolvemos la promesa con la respuesta exitosa
            } catch (error) {
                console.log(error);

                reject(error); // Rechazamos la promesa en caso de excepción
            }
        });
    };

    const getPlazasByEmployeeIdAndDependenciaId = async () => {
        try {
            // Inicialización y limpieza de variables
            resetVariables();

            // Validar si falta información necesaria
            if (!idEmpleado.value || !idDependencia.value) {
                return;
            }

            // Obtener información del servidor
            const resp = await obtenerPlazasDesdeServidor();

            // Procesar la respuesta del servidor
            procesarRespuestaServidor(resp);
        } catch (error) {
            // Manejo de errores
            console.error(error);
        } finally {
            // Finalización y limpieza
            loadingEvaluacionRendimiento.value = false;
        }
    };

    // Función para inicializar y limpiar variables
    const resetVariables = () => {
        objectEvaluaciones.value = null;
        plazaOptions.value = null;
        showPlazasModal.value = false;
        loadingEvaluacionRendimiento.value = true;
        doesntExistResult.value = false;
        objectPlazas.value = null;
        evaluationsOptions.value = [];
        idEvaluacionRendimiento.value = null;
        existMoreThanOne.value = false;
    };

    // Función para obtener información del servidor
    const obtenerPlazasDesdeServidor = async () => {
        return await axios.post("/getAllPlazasByEmployeeIdAndDependenciaId", {
            employeeId: idEmpleado.value,
            dependenciaId: idDependencia.value,
        });
    };

    // Función para procesar la respuesta del servidor
    const procesarRespuestaServidor = (resp) => {
        if (resp.data) {
            const cantidadEvaluaciones = resp.data.cantidadEvaluacionesRendimiento;

            if (cantidadEvaluaciones === 1) {
                // Caso de una sola evaluación
                procesarUnaEvaluacion(resp.data);
            } else if (cantidadEvaluaciones >= 2 && cantidadEvaluaciones <= 3) {
                // Caso de dos o tres evaluaciones
                procesarDosOtresEvaluaciones(resp.data);
            } else {
                // Caso sin evaluaciones ni plazas asignadas
                alert("No se encontraron evaluaciones ni plazas asignadas.");
                doesntExistResult.value = true;
            }
        } else {
            // Caso de respuesta vacía
            doesntExistResult.value = true;
            idEvaluacionRendimiento.value = null; // Limpiar el valor
        }
    };

    // Función para procesar una sola evaluación
    const procesarUnaEvaluacion = (data) => {
        evaluationsOptions.value = Object.values(data.evaluacionRendimiento).map((index) => ({
            value: index.id_evaluacion_rendimiento,
            label: index.codigo_evaluacion_rendimiento,
        }));
        objectPlazas.value = data.plazasAsignadas.map((index) => ({
            value: index.detalle_plaza.plaza.id_plaza,
            label: index.detalle_plaza.plaza.nombre_plaza,
        }));
        idEvaluacionRendimiento.value = data.evaluacionRendimiento[0].id_evaluacion_rendimiento;
    };

    // Función para procesar dos o tres evaluaciones
    const procesarDosOtresEvaluaciones = (data) => {
        evaluationsOptions.value = Object.values(data.evaluacionRendimiento).map((index) => ({
            value: index.id_evaluacion_rendimiento,
            label: index.codigo_evaluacion_rendimiento,
            id_tipo_plaza: index.id_tipo_plaza,
        }));
        plazaOptions.value = data.plazasAsignadas.map((index) => ({
            value: index.detalle_plaza.plaza.id_plaza,
            label: index.detalle_plaza.plaza.nombre_plaza,
            id_tipo_plaza: index.detalle_plaza.plaza.tipo_plaza.id_tipo_plaza,
        }));
        showPlazasModal.value = true;
        existMoreThanOne.value = true;
        doesntExistResult.value = false;
    };

    const handleTagToSelect = async (option, select$) => {
        console.log(option);
        console.log(evaluationsOptions.value);
        console.log(idEvaluacionRendimiento.value);

        let optionEvaluation = evaluationsOptions.value.find(
            (i) => i.value == idEvaluacionRendimiento.value
        );
        console.log(optionEvaluation);

        if (option.id_tipo_plaza != optionEvaluation.id_tipo_plaza) {
            console.log(objectPlazas.value);
            var resultado = window.confirm("La plaza no pertenece al tipo de evaluacion que se hara. ¿Desea agregarla de todas formas?");
            if (resultado === true) {
                console.log("LO AGREGAMOS (no hacemos nada)");
            } else {
                console.log("LO BORRAMOS");
                // Buscamos el índice del elemento a borrar
                const indexToDelete = objectPlazas.value.findIndex(
                    (i) => i.value == option.value
                );

                // Verificamos si se encontró el elemento
                if (indexToDelete !== -1) {
                    // Borramos el elemento del array
                    objectPlazas.value.splice(indexToDelete, 1);
                    console.log("Elemento borrado:", option);
                } else {
                    console.log("Elemento no encontrado en el array");
                }
            }
        }
    };




    return {
        handleEmployeeSearch,
        handleTagToSelect,
        idDependencia,
        plazaOptions,
        idEvaluacionRendimiento,
        existMoreThanOne,
        loadingEvaluacionRendimiento,
        getPlazasByEmployeeIdAndDependenciaId,
        createPersonalEvaluationRequest,
        idTipoEvaluacion,
        doesntExistResult,
        evaluationsOptions,
        fechaInicioFechafin,
        showPlazasModal,
        objectPlazas,
        objectEvaluaciones,
        idEmpleado,
    };
};
