import { ref } from "vue";


export const useNumbersToString = () => {

    const letterNumber = ref(null)
     /**
    * Convertir numeros a letras
    *
    * @param {string} number - numero a convertir
    * @returns {Promise<object>} - Objeto con los datos de la respuesta.
    * @throws {Error} - Error al obtener empleados por nombre.
    */
     const getText = async (number) => {
        try {
            // Realiza la búsqueda de empleados
            const response = await axios.post(
                "/convert-numbers-to-string", {
                    number: number,
            });
            console.log(response);
            letterNumber.value = response.data
            //return response.data;
        } catch (error) {
            // Manejo de errores específicos
            console.error("Error al obtener empleados por nombre:", error);
            // Lanza el error para que pueda ser manejado por el componente que utiliza este composable
            throw new Error("Error en la búsqueda de empleados");
        }
    };

    return {
        getText,letterNumber
    }
};
