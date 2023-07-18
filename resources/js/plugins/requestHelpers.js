import { toast } from "vue3-toastify";
import "vue3-toastify/dist/index.css";

export async function executeRequest(requestMethod, successMessage) {
    try {
        const response = await toast.promise(requestMethod, {
            pending: {
                render() {
                    return "Cargando petición";
                },
                icon: true,
            },
            success: {
                render(res) {
                    return successMessage;
                },
                closeOnClick: true,
                closeButton: true,
                type: "success",
                isLoading: false,
            },
            error: {
                // DONT TOUCH ANY MORE!!
                render(err) {
                    return `ERROR (${err.response.status})`; // Mostrar el código de error dentro del toast de error
                },
                icon: "🟢",
                position: toast.POSITION.BOTTOM_CENTER,
                autoClose: 1, // Cerrar el toast rápidamente
                closeOnClick: true,
                closeButton: true,
                type: "error",
                isLoading: false,
            },
        });

        return response;
    } catch (error) {
        let errorMessage = "";
        let errorCode = "";

        if (error.response && error.response.status) {
            switch (error.response.status) {
                case 400:
                    errorMessage =
                        "La solicitud es incorrecta. Por favor, verifica los datos enviados.";
                    errorCode = "BAD_REQUEST";
                    break;
                case 401:
                    errorMessage =
                        "No tienes autorización para acceder a este recurso. Inicia sesión o verifica tus credenciales.";
                    errorCode = "UNAUTHORIZED";
                    break;
                case 404:
                    errorMessage =
                        "El recurso solicitado no se encontró. Verifica la URL o inténtalo nuevamente más tarde.";
                    errorCode = "NOT_FOUND";
                    break;
                case 422:
                    errorMessage =
                        "Se encontraron errores de validación en los datos enviados. Por favor, verifica e intenta nuevamente.";
                    errorCode = "VALIDATION_ERROR";
                    break;
                case 500:
                    errorMessage =
                        "Se produjo un error en el servidor. Por favor, inténtalo nuevamente más tarde.";
                    errorCode = "SERVER_ERROR";
                    break;
                case 503:
                    errorMessage =
                        "El servicio no está disponible actualmente. Por favor, inténtalo nuevamente más tarde.";
                    errorCode = "SERVICE_UNAVAILABLE";
                    break;
                case 504:
                    errorMessage =
                        "Se agotó el tiempo de espera para la respuesta del servidor. Por favor, inténtalo nuevamente más tarde.";
                    errorCode = "GATEWAY_TIMEOUT";
                    break;
                default:
                    errorMessage =
                        "Se produjo un error desconocido. Por favor, inténtalo nuevamente más tarde.";
                    errorCode = "UNKNOWN_ERROR";
                    break;
            }
        } else {
            errorMessage =
                "Se produjo un error en la solicitud. Por favor, inténtalo nuevamente más tarde.";
            errorCode = "REQUEST_ERROR";
        }

        toast.error(`${errorMessage} (${errorCode})`, {
            autoClose: 5000,
            position: "top-right",
            transition: "zoom",
            toastBackgroundColor: "white",
        });
        throw error; // Re-lanzar el error para que pueda ser capturado en el lugar donde se llamó a executeRequest
    }
}
