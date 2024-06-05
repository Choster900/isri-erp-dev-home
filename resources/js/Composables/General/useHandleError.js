
export const useHandleError = (errors) => {
    let title = "";
    let text = "";
    let icon = "";

    const getErrorParameters = () => {
        if (!errors.response) {
            // Aquí asumimos que podría ser un error de red o de sesión expirada sin respuesta
            if (errors.request && errors.request.status === 0) {
                console.log(errors);
                // Este caso maneja específicamente un error sin respuesta
                title = "Error de conexión";
                text = "No se pudo establecer conexión con el servidor. Por favor, inténtalo de nuevo más tarde.";
                icon = "error";
            } else {
                console.log(errors);
                // Maneja otros posibles casos sin respuesta de una manera genérica
                title = "Error desconocido";
                text = "Ocurrió un error desconocido. Por favor, inténtalo de nuevo.";
                icon = "error";
            }
        } else {
            const code_error = errors.response.status;

            switch (code_error) {
                case 401:
                    console.log(errors.response.data.message || "Unauthorized");
                    title = "Autenticación requerida.";
                    text = "Necesita estar autenticado para acceder al recurso solicitado, por favor recargue la página e inicie sesión.";
                    icon = "warning";
                    break;
                case 403:
                    console.log(errors.response.data.message || "Forbidden.");
                    title = "Acceso no autorizado";
                    text = "No tiene los permisos para acceder al recurso solicitado, consulte con el administrador.";
                    icon = "warning";
                    break;
                case 404:
                    console.log(errors.response.data.message || "Not Found");
                    title = "Recurso no disponible";
                    text = "El recurso al que intentas acceder no esta disponible, consulte con el administrador.";
                    icon = "warning";
                    break;
                case 419:
                    console.log(errors.response.data.message || "Página expirada.");
                    title = "Sesión expirada";
                    text = "Página expirada, por favor recargue la página.";
                    icon = "warning";
                    break;
                case 429:
                    console.log(errors.response.data.message || "Demasiadas solicitudes.");
                    title = "Demasiadas solicitudes";
                    text = "Has realizado demasiadas solicitudes en un corto período de tiempo. Por favor, inténtalo de nuevo más tarde.";
                    icon = "warning";
                    break;
                case 500:
                    console.log(errors.response.data.message || "Error del servidor.");
                    title = "Operación cancelada";
                    text = "Error al conectarse con el servidor.";
                    icon = "error";
                    break;
                case 503:
                    console.log(errors.response.data.message || "Servicio no disponible.");
                    title = "Servicio no disponible";
                    text = "El servidor no está disponible temporalmente debido a mantenimiento o sobrecarga. Por favor, inténtalo de nuevo más tarde.";
                    icon = "error";
                    break;
                default:
                    console.log(errors.response.data.message || "Error desconocido.");
                    title = "Operación cancelada";
                    text = "Lo sentimos, hubo un error al procesar la petición.";
                    icon = "error";
                    break;
            }
        }

        return {
            title,
            text,
            icon,
        };
    };

    return getErrorParameters();
};
