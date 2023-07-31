export const manageError = (errors, context) => {
  if (!errors.response) {
    // No se pudo obtener respuesta del servidor
    context.$swal.fire({
      title: 'Error de conexión',
      text: 'No se pudo establecer conexión con el servidor. Por favor, inténtalo de nuevo más tarde.',
      icon: 'error',
      timer: 5000,
    });
  } else {
    let code_error = errors.response.status;
    let msg;
    if (code_error >= 500) {
      console.log(errors.response.data.message);
      msg = "Error al conectarse con el servidor.";
    } else if (code_error >= 400 && code_error < 500) {
      console.log(errors.response.data.message);
      msg = "Funcionalidad no disponible, consulte con el administrador.";
    } else {
      console.log(errors.response.data.message);
      msg = "Lo sentimos, hubo un error al procesar la petición.";
    }

    // Mostrar SweetAlert con el mensaje de error utilizando this.$swal del contexto proporcionado
    context.$swal.fire({
      title: 'Operación cancelada',
      text: msg,
      icon: 'warning',
      timer: 5000,
    });
  }
};