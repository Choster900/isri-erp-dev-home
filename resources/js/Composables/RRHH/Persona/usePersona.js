import axios from "axios";

export const usePersona = () => {
    // Función para obtener personas por nombre
    const getPeopleByName = async (nombreToSearch) => {
        try {
            // endpoint getPersonaByName se encuentra en PersonaController => nombre del metodo getPersonByCompleteName
            const response = await axios.post("/getPersonaByName", {
                nombre: nombreToSearch,
            });

            // Devuelve los datos de la respuesta
            return response.data;
        } catch (error) {
            // Manejo de errores
            console.error("Error al obtener personas por nombre:", error);
            throw error; // Lanza el error para que pueda ser manejado por el componente que utiliza este composable
        }
    };
    // Devuelve la función para ser utilizada por el componente
    return {
        getPeopleByName,
    };
};
