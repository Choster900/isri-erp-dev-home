import { ref, inject, computed, onMounted } from "vue";
import ReporteConsumoPdf from "@/pdf/Almacen/ReporteConsumoPdf.vue";
import { createApp, h } from 'vue'
import { jsPDF } from "jspdf";
import html2pdf from 'html2pdf.js'
import moment from "moment";
export const useReporteConsumo = () => {


    const errors = ref([])
    const isLoadingExport = ref([])
    const isLoadinRequest = ref(false)
    const dataReporteConsumo = ref([])

    //Variables a enviar para consulta
    const idProyectoFinanciamiento = ref(null)
    const idCentroAtencion = ref(null)
    const idTipoTransaccion = ref(null)
    const idCuenta = ref(null)
    const fechaDesde = ref(null)
    const fechaHasta = ref(null)
    const tipoReporte = ref(null)
    const tipoReporteForValidate = ref(null)

    //Data para multiselect
    const fuenteFinanciamientoArray = ref([])
    const centroAtencionArray = ref([])
    const numeroCuentaArray = ref([])

    const getInformacionReport = async () => {
        try {
            isLoadinRequest.value = true;

            tipoReporteForValidate.value = tipoReporte.value == 'D' ? 'D' : 'C';
            const resp = await axios.post(
                "/get-reporte-consumo",
                {
                    idProyectoFinanciamiento: idProyectoFinanciamiento.value,
                    idCentroAtencion: idCentroAtencion.value,
                    idTipoTransaccion: idTipoTransaccion.value,
                    idCuenta: idCuenta.value,
                    fechaDesde: fechaDesde.value,
                    fechaHasta: fechaHasta.value,
                    tipoReporte: tipoReporte.value,

                }
            );
            const { data } = resp;
            console.log(data);


            dataReporteConsumo.value = data
        } catch (error) {
            console.error("Ocurrió un error al obtener la información del reporte:", error);
            isLoadinRequest.value = false;

        } finally {
            isLoadinRequest.value = false;
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

    const printPdf = () => {
        let fecha = moment().format("DD-MM-YYYY");
        let name = "NOMBRE DOCUMENTO - FECHA - CODIGO";
        const opt = {
            //margin: [0.5, 0.1, 2, 0.5], //top, left, bottom, right,
            margin: 0.5,
            filename: "rotacion",
            //pagebreak: { mode: ['avoid-all', 'css', 'legacy'] },
            image: { type: "jpeg", quality: 0.98 },
            html2canvas: { scale: 3, useCORS: true },
            jsPDF: { unit: "cm", format: "letter", orientation: "landscape" },
        };
        // Crear una instancia de la aplicación Vue para generar el componente quedanPDFVue
        const app = createApp(ReporteConsumoPdf, {
            dataConsumo: dataReporteConsumo.value,
            fecha: {
                fechaDesde: fechaDesde.value,
                fechaHasta: fechaHasta.value,
            },
        });
        // Crear un elemento div y montar la instancia de la aplicación en él
        const div = document.createElement("div");
        const pdfPrint = app.mount(div);
        const html = div.outerHTML;
        const currentDateTime = moment().format("DD/MM/YYYY, HH:mm:ss");

        // Generar y guardar el PDF utilizando html2pdf
        html2pdf()
            .set(opt)
            .from(html)
            .toPdf()
            .get("pdf")
            .then(function (pdf) {
                var totalPages = pdf.internal.getNumberOfPages();
                for (var i = 1; i <= totalPages; i++) {
                    pdf.setPage(i);
                    pdf.setFontSize(10);
                    //Text for the page number
                    let text = "Página " + i + " de " + totalPages;
                    const centerX = pdf.internal.pageSize.getWidth() / 2;
                    //Get the text width
                    const textWidth1 =
                        (pdf.getStringUnitWidth(text) *
                            pdf.internal.getFontSize()) /
                        pdf.internal.scaleFactor;
                    //Get the middle position including the text width
                    const textX = centerX - textWidth1 / 2;
                    //Write the text in the desired coordinates.
                    pdf.text(
                        textX,
                        pdf.internal.pageSize.getHeight() - 0.6,
                        text
                    );
                    //Text for the date and time.
                    let date_text = "Generado: " + currentDateTime;
                    //Get the text width
                    const textWidth =
                        (pdf.getStringUnitWidth(date_text) *
                            pdf.internal.getFontSize()) /
                        pdf.internal.scaleFactor;
                    //Write the text in the desired coordinates.
                    pdf.text(
                        pdf.internal.pageSize.getWidth() - textWidth - 0.6,
                        pdf.internal.pageSize.getHeight() - 0.6,
                        date_text
                    );
                }
            })
            .save()
            .catch((err) => console.log(err));
    };

    const exportExcel = async () => {
        try {
            try {
                const response = await axios.post(
                    "/get-excel-document-reporte-consumo",
                    {
                        idProyectoFinanciamiento: idProyectoFinanciamiento.value,
                        idCentroAtencion: idCentroAtencion.value,
                        idTipoTransaccion: idTipoTransaccion.value,
                        idCuenta: idCuenta.value,
                        fechaDesde: fechaDesde.value,
                        fechaHasta: fechaHasta.value,
                        tipoReporte: tipoReporte.value,
                    },
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
        exportExcel, getInformacionReport, isLoadingExport, dataReporteConsumo,
        handleCuentaPresupuestalChange, isLoadinRequest, printPdf,

        idProyectoFinanciamiento, tipoReporteForValidate,
        idCentroAtencion, idTipoTransaccion, idCuenta, fechaDesde, fechaHasta, tipoReporte,

    }
}
