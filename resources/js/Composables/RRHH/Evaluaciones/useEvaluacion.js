import axios from "axios";
import moment from "moment";
import { computed, ref, watch } from "vue";
/**
 * 
 * @returns data
 */
export const useEvaluacion = () => {
    const idEmpleado = ref(null);
    const idDependencia = ref("");
    const idCentroAtencion = ref("");
    const idEvaluacionRendimiento = ref("");
    const loadingEvaluacionRendimiento = ref(false);
    const idTipoEvaluacion = ref(1);
    const fechaInicioFechafin = ref("");
    const activeIndex = ref(0);

    const doesntExistResult = ref(false);
    const existMoreThanOne = ref(false);
    const showPlazasModal = ref(false);
    const objectPlazas = ref([]);
    const objectEvaluaciones = ref("");

    const plazaOptions = ref([]);
    const evaluationsOptions = ref([]);

    const showMessageAlert = ref(false);
    const messageAlert = ref("");
    const opcionAlertPressed = ref("");
    const globalOptionSelectePlaza = ref("");

    const errorsData = ref([]);

    const centrosPosibleOptionsData = ref([]);
    const centrosPosibleOptionsDataCopy = ref([]);
    const flagTrueOrFalse = ref(true);

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
                "/search-employees-for-evaluations",
                {
                    nombre: nombreToSearch,
                }
            );
            // Mapeando los centros a los que las personas que coincidieron en la busqueda 
            centrosPosibleOptionsData.value = response.data.map((item) => {
                return item.allDataPersonas.empleado.plazas_asignadas
            })
            centrosPosibleOptionsDataCopy.value = response.data.map((item) => {
                return item.allDataPersonas.empleado.plazas_asignadas
            })
            flagTrueOrFalse.value = true;
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
     * Crea una nueva evaluación personal.
     *
     * @returns {Promise<object>} - Promesa que se resuelve con la respuesta exitosa.
     * @throws {Error} - Error en la creación de la evaluación personal.
     */
    const createPersonalEvaluationRequest = async () => {
        try {
            const response = await axios.post("/create-new-evaluacion", {
                idEvaluacionRendimiento: idEvaluacionRendimiento.value,
                idEmpleado: idEmpleado.value,
                idCentroAtencion: idCentroAtencion.value,
                idTipoEvaluacion: idTipoEvaluacion.value,
                fechaInicioFechafin: fechaInicioFechafin.value,
                plazasAsignadas: objectPlazas.value,
            });

            idCentroAtencion.value = null;
            objectPlazas.value = null;
            idEvaluacionRendimiento.value = null;
            fechaInicioFechafin.value = null;
            return response; // Devuelve la respuesta exitosa y limpiamos los datos
        } catch (error) {

            if (error.response.status === 422) {
                let data = error.response.data.errors;
                console.log(data);
                var myData = new Object();
                for (const errorBack in data) {
                    myData[errorBack] = data[errorBack][0];
                }
                errorsData.value = myData;
                console.table(errorsData.value);
                setTimeout(() => {
                    errorsData.value = [];
                }, 5000);
            }
            reject(error);

            console.error("Error en la creación de la evaluación personal:", error);
            throw new Error("Error en la creación de la evaluación personal");
        }
    };

    /**
     * Realiza la búsqueda de plazas según el ID del empleado y el ID de la dependencia.
     */
    const getPlazasByEmployeeIdAndCentroAtencionId = async () => {
        try {
            // Inicialización y limpieza de variables
            resetVariables();
            //FIXME: Hay errores en este codigo pero funciona mas o menos 
            const plazasAsignadas = [];
            watch(idEmpleado, () => {
                centrosPosibleOptionsData.value = centrosPosibleOptionsDataCopy.value;
                flagTrueOrFalse.value = true;
            })

            if (centrosPosibleOptionsData.value && Array.isArray(centrosPosibleOptionsData.value) && flagTrueOrFalse.value === true) {
                centrosPosibleOptionsData.value.forEach(persona => {
                    const empleado = persona;
                    if (empleado && Array.isArray(empleado)) {
                        empleado.forEach(element => {
                            if (element && element.id_empleado === idEmpleado.value) {
                                plazasAsignadas.push(element);
                            }
                        });
                    }
                });
            }
            if (plazasAsignadas.length === 1 && flagTrueOrFalse.value === true && centrosPosibleOptionsData.value.length > 0) {
                idCentroAtencion.value = plazasAsignadas[0].id_centro_atencion
                centrosPosibleOptionsData.value = null
                flagTrueOrFalse.value = false;
            }
            //FIXME: Fin de errorres

            // Validar si falta información necesaria
            if (!idEmpleado.value || !idCentroAtencion.value || !idTipoEvaluacion.value || !fechaInicioFechafin.value) {
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

    /**
     * Inicializa y limpia las variables utilizadas en la búsqueda de plazas.
     */
    const resetVariables = () => {
        objectEvaluaciones.value = null;
        plazaOptions.value = null;
        showPlazasModal.value = false;
        doesntExistResult.value = false;
        objectPlazas.value = null;
        evaluationsOptions.value = [];
        idEvaluacionRendimiento.value = null;
        existMoreThanOne.value = false;
    };

    /**
     * Realiza la llamada al servidor para obtener las plazas.
     *
     * @returns {Promise<object>} - Promesa que se resuelve con la respuesta del servidor.
     */
    const obtenerPlazasDesdeServidor = async () => {
        try {
            loadingEvaluacionRendimiento.value = true;
            errorsData.value = null;
            return await axios.post("/getAllPlazasByEmployeeIdAndDependenciaId", {
                employeeId: idEmpleado.value,
                centroAtencionId: idCentroAtencion.value,
                idTipoEvaluacion: idTipoEvaluacion.value,
                fechaInicioFechafin: fechaInicioFechafin.value,
            });
        } catch (error) {
            if (error.response.status === 422) {
                console.log(error.response.data.mensaje_periodo);

                errorsData.value = {
                    fechaInicioFechafin: error.response.data.mensaje_periodo
                };

            }
            reject(error);

            console.error("Error en la creación de la evaluación personal:", error);
            throw new Error("Error en la creación de la evaluación personal");
        }

    };

    // Función para procesar la respuesta del servidor
    const procesarRespuestaServidor = (resp) => {
        if (resp.data) {
            const cantidadEvaluaciones =
                resp.data.cantidadEvaluacionesRendimiento;

            if (cantidadEvaluaciones === 1) {
                // Caso de una sola evaluación
                procesarUnaEvaluacion(resp.data);
            } else if (cantidadEvaluaciones >= 2 && cantidadEvaluaciones <= 3) {
                // Caso de dos o tres evaluaciones
                procesarDosOtresEvaluaciones(resp.data);
            } else {
                // Caso sin evaluaciones ni plazas asignadas
                //alert("No se encontraron evaluaciones ni plazas asignadas.");
                doesntExistResult.value = true;
            }
        } else {
            // Caso de respuesta vacía
            doesntExistResult.value = true;
            idEvaluacionRendimiento.value = null; // Limpiar el valor
        }
    };

    /**
    * Procesa los datos cuando hay una sola evaluación.
    *
    * @param {object} data - Datos de la respuesta del servidor.
    */
    const procesarUnaEvaluacion = (data) => {
        evaluationsOptions.value = Object.values(
            data.evaluacionRendimiento
        ).map((index) => ({
            value: index.id_evaluacion_rendimiento,
            label: index.codigo_evaluacion_rendimiento,
        }));
        objectPlazas.value = data.plazasAsignadas.map((index) => ({
            value: index.id_plaza_asignada,
            label: index.detalle_plaza.plaza.nombre_plaza,
            dependencia:index.dependencia,
        }));
        idEvaluacionRendimiento.value =
            data.evaluacionRendimiento[0].id_evaluacion_rendimiento;
    };

    /**
     * Procesa los datos cuando hay dos o tres evaluaciones.
     *
     * @param {object} data - Datos de la respuesta del servidor.
     */
    const procesarDosOtresEvaluaciones = (data) => {
        evaluationsOptions.value = Object.values(
            data.evaluacionRendimiento
        ).map((index) => ({
            value: index.id_evaluacion_rendimiento,
            label: index.codigo_evaluacion_rendimiento,
            id_tipo_plaza: index.tipo_plaza.id_tipo_plaza,
            tipo_plaza: index.tipo_plaza,
        }));
        plazaOptions.value = data.plazasAsignadas.map((index) => ({
            value: index.id_plaza_asignada,
            label: index.detalle_plaza.plaza.nombre_plaza,
            id_tipo_plaza: index.detalle_plaza.plaza.tipo_plaza.id_tipo_plaza,
            nombre_tipo_plaza:
                index.detalle_plaza.plaza.tipo_plaza.nombre_tipo_plaza,
        }));
        showPlazasModal.value = true;
        existMoreThanOne.value = true;
        doesntExistResult.value = false;
    };

    /**
     * Maneja la aceptación de la selección en el modal.
     * Agrega la plaza seleccionada al array de plazas.
     */
    const handleAccept = () => {
        showMessageAlert.value = false;
        opcionAlertPressed.value = true;

        // Agrega la plaza seleccionada al array de plazas
        objectPlazas.value.push({
            value: globalOptionSelectePlaza.value.value,
            label: globalOptionSelectePlaza.value.label,
        });
    };

    /**
     * Maneja el rechazo o cancelación de la selección en el modal.
     */
    const handleCancel = () => {
        showMessageAlert.value = false;
        opcionAlertPressed.value = false;
    };

    /**
     * Maneja la selección de una plaza.
     *
     * @param {object} option - Opción seleccionada en el dropdown.
     * @param {object} select$ - Objeto del dropdown.
     */
    const handleTagToSelect = async (option, select$) => {
        // Almacena la opción seleccionada globalmente
        globalOptionSelectePlaza.value = option;

        // Encuentra la opción de evaluación correspondiente
        const optionEvaluation = evaluationsOptions.value.find(
            (evalOption) => evalOption.value == idEvaluacionRendimiento.value
        );

        // Verifica si la plaza seleccionada es del mismo tipo que la evaluación
        if (option.id_tipo_plaza !== optionEvaluation.id_tipo_plaza) {
            // Muestra el modal de confirmación
            showMessageAlert.value = true;
            messageAlert.value = `La plaza "${option.label
                .toLowerCase()
                .bold()}" pertenece al tipo de plaza "${option.nombre_tipo_plaza
                    .toLowerCase()
                    .bold()}". Por ende, queda bajo su criterio evaluarla como "${optionEvaluation.tipo_plaza.nombre_tipo_plaza
                        .toLowerCase()
                        .bold()}".`;

            // Encuentra el índice de la plaza en el array de plazas
            const indexToDelete = objectPlazas.value.findIndex(
                (plaza) => plaza.value == option.value
            );

            // Elimina la plaza del array si se encuentra
            if (indexToDelete !== -1) {
                objectPlazas.value.splice(indexToDelete, 1);
            } else {
                console.log("Plaza no encontrada en el array");
            }
        }
    };

    const cleaningWhenInsert = () => {

    };

    return {
        handleEmployeeSearch,
        handleTagToSelect,
        idCentroAtencion,
        plazaOptions,
        idEvaluacionRendimiento,
        existMoreThanOne,
        activeIndex,
        loadingEvaluacionRendimiento,
        getPlazasByEmployeeIdAndCentroAtencionId,
        createPersonalEvaluationRequest,
        idTipoEvaluacion,
        doesntExistResult,
        evaluationsOptions,
        fechaInicioFechafin,
        showPlazasModal,
        idDependencia,
        objectPlazas,
        objectEvaluaciones,
        idEmpleado,
        messageAlert,
        errorsData,

        showMessageAlert,
        handleAccept,
        handleCancel,
    };
};
