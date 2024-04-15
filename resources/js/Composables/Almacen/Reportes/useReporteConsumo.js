import { ref, inject, computed, onMounted } from "vue";

export const useReporteConsumo = () => {


    const errors = ref([])
    const isLoadingExport = ref([])
    const dataReporteConsumo = ref([])

    //Variables a enviar para consulta
    const idProyectoFinanciamiento = ref(null)
    const idCentroAtencion = ref(null)
    const idTipoTransaccion = ref(null)
    const idCuenta = ref(null)
    const fechaDesde = ref(null)
    const fechaHasta = ref(null)
    const tipoReporte = ref(null)

    //Data para multiselect
    const fuenteFinanciamientoArray = ref([])
    const centroAtencionArray = ref([])
    const numeroCuentaArray = ref([])

    const getInformacionReport = async () => {
        try {
            isLoadingExport.value = true;
            const resp = await axios.post(
                "/get-reporte-consumo",
                {
                    idProyectoFinanciamiento: idProyectoFinanciamiento.value,
                    idCentroAtencion: idCentroAtencion.value,
                    idTipoTransaccion: idTipoTransaccion.value,
                    idCuenta: idCuenta.value,
                    fechaDesde: fechaDesde.value,
                    fechaHasta:fechaHasta.value,
                    tipoReporte: tipoReporte.value,

                 }
            );
            const { data } = resp;
            console.log(data);


            dataReporteConsumo.value = data
        } catch (error) {
            console.error("Ocurrió un error al obtener la información del reporte:", error);

        }
    };


    const getDataArrayForSelect = () => {


    }

     /**
     * Busca cuenta presupuestal por id
     *
     * @param {string} idCuenta - cuenta a buscar por id.
     * @returns {Promise<object>} - Objeto con los datos de la respuesta.
     * @throws {Error} - Error al obtener empleados por nombre.
     */
     const handleCuentaPresupuestalChange = async (idCuenta) => {
        try {
            // Realiza la búsqueda de empleados
            const response = await axios.post(
                "/get-cuenta-by-number",
                {
                    numeroCuenta: idCuenta,
                }
            );
            // Mapeando los centros a los que las personas que coincidieron en la busqueda

            return response.data;
        } catch (error) {
            // Manejo de errores específicos
            console.error("Error al obtener empleados por nombre:", error);
            // Lanza el error para que pueda ser manejado por el componente que utiliza este composable
            throw new Error("Error en la búsqueda de empleados");
        }
    };


    onMounted(() => {
        getDataArrayForSelect();
    })


    const exportExcel = async () => {
        try {
            try {
                const response = await axios.post(
                    "/get-excel-document-reporte-consumo",
                    null,
                    { responseType: "blob" }
                );

                // Crear una URL para el blob
                const url = window.URL.createObjectURL(new Blob([response.data]));

                // Crear un enlace temporal y simular un clic para descargar el archivo
                const link = document.createElement("a");
                link.href = url;
                link.setAttribute("download", "nombre_del_archivo.xlsx"); // Nombre del archivo deseado
                document.body.appendChild(link);
                link.click();

                // Liberar la URL del blob después de la descarga
                window.URL.revokeObjectURL(url);
                document.body.style.cursor = 'default';

            } catch (error) {
                console.error('Error al generar el PDF:', error);
            }
            finally {
                //TODO: revisar que el cursos no cambia a default
                // Restaurar el cursor del cuerpo del documento a "default" después de la generación del PDF
                document.body.style.cursor = 'default';
            }

        } catch (error) {
            console.error("Error al descargar el archivo:", error);
        }
    };

    return {
        exportExcel,getInformacionReport,isLoadingExport,dataReporteConsumo,
        handleCuentaPresupuestalChange,

        idProyectoFinanciamiento,
        idCentroAtencion,idTipoTransaccion,idCuenta,fechaDesde,fechaHasta,tipoReporte,

    }
}
