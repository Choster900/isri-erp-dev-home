import { ref } from "vue";

export const useHandleError = (errors) => {
    let title = ref("");
    let text = ref("");
    let icon = ref("");
    const getErrorParameters = () => {
        if (!errors.response) {
            title = "Error de conexión";
            text = "No se pudo establecer conexión con el servidor. Por favor, inténtalo de nuevo más tarde.";
            icon = "error";
        } else {
            let code_error = errors.response.status;
            if (code_error >= 500) {
                console.log(errors.response.data.message);
                text = "Error al conectarse con el servidor.";
            } else if (code_error >= 400 && code_error < 500) {
                console.log(errors.response.data.message);
                text =
                    "Funcionalidad no disponible, consulte con el administrador.";
            } else {
                console.log(errors.response.data.message);
                text = "Lo sentimos, hubo un error al procesar la petición.";
            }

            title = "Operación cancelada";
            icon = "warning";
        }

        return {
            title,
            text,
            icon,
        };
    };

    return getErrorParameters();
};
