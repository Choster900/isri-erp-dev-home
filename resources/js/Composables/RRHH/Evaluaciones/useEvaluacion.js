import axios from "axios";
import { ref } from "vue";

export const useEvaluacion = () => {
    const idEmpleado = ref('');
    const idDependencia = ref('');
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
                    id_empleado: idEmpleado.id_evaluacion_rendimiento,
                    periodo_evaluacion_personal: periodo,
                    id_evaluacion_rendimiento: this.id_evaluacion_rendimiento,
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
        alert("fsadsa")
        if (idEmpleado.value != '' && idDependencia.value != '') {
            try {
                const resp = await axios.post("/getAllPlazasByEmployeeIdAndDependenciaId", {
                    employeeId: idEmpleado.value,
                    dependenciaId: idDependencia.value,
                });
                console.log(resp);
                dataArrayPersona.value = resp.data;
            } catch (error) {
                console.error(error);
            }
        }
    };

    return {
        handleEmployeeSearch,
        idDependencia,
        getPlazasByEmployeeIdAndDependenciaId,
        createPersonalEvaluationRequest,
        idEmpleado,
    };
};
