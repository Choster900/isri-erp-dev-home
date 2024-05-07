import { ref, inject, computed, onMounted } from "vue";
import { createApp, h } from 'vue'
import { jsPDF } from "jspdf";
import html2pdf from 'html2pdf.js'
import moment from "moment";
import ReporteExistenciaPdf from "@/pdf/Almacen/ReporteExistenciaPdf.vue";

export const useReporteExistencia = () => {

    const dataReporteExistencia = ref([]);

    const idFuenteFinanciamiento = ref(null);
    const idCentroAtencion = ref(null);
    const idSubAlmacen = ref(null);

    /**
     * Función para realizar una búsqueda de reporte de existencia en el almacén.
     */
    const searchReportExistencia = async () => {
        try {
            // Realiza una solicitud POST para obtener el reporte de existencia en el almacén
            const resp = await axios.post("/get-reporte-existencia-almacen", {
                idSubAlmacen: idSubAlmacen.value,
                idCentroAtencion: idCentroAtencion.value,
                idFuenteFinanciamiento: idFuenteFinanciamiento.value,
            });

            // Extrae los datos de la respuesta
            const { data } = resp;

            // Imprime los datos en la consola (puedes modificar este comportamiento según sea necesario)
            console.log(data);

            dataReporteExistencia.value = data;

        } catch (error) {
            // Maneja cualquier error que ocurra
            console.error('Ocurrió un error al obtener la información del reporte:', error);
        } finally {
            // Código a ejecutar después de la solicitud, independientemente de si fue exitosa o no
            // Aquí puedes agregar cualquier limpieza de estado o finalización de tareas que necesites realizar
        }
    };

    const exportExcel = async () => {
        try {
            try {
                document.body.style.cursor = 'wait';

                const response = await axios.post(
                    "/get-reporte-existencia-almacen-excel",
                    {
                        idSubAlmacen: idSubAlmacen.value,
                        idCentroAtencion: idCentroAtencion.value,
                        idFuenteFinanciamiento: idFuenteFinanciamiento.value,
                    },
                    { responseType: "blob" }
                );

                // Crear una URL para el blob
                const url = window.URL.createObjectURL(new Blob([response.data]));

                // Crear un enlace temporal y simular un clic para descargar el archivo
                const link = document.createElement("a");
                link.href = url;
                link.setAttribute("download", "REPORTE_EXISTENCIA_" + moment().format('L') + ".xlsx"); // Nombre del archivo deseado
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


    const printPdf = () => {
        document.body.style.cursor = 'wait';

        const opt = {
            //margin: [0.5, 0.1, 2, 0.5], //top, left, bottom, right,
            margin: 0.5,
            filename: "REPORTE_EXISTENCIA_" + moment().format("L"),
            //pagebreak: { mode: ['avoid-all', 'css', 'legacy'] },
            image: { type: "jpeg", quality: 0.98 },
            html2canvas: { scale: 3, useCORS: true },
            jsPDF: { unit: "cm", format: "letter", orientation: "landscape" },
        };
        // Crear una instancia de la aplicación Vue para generar el componente quedanPDFVue
        const app = createApp(ReporteExistenciaPdf, { dataReporteExistencia: dataReporteExistencia.value });
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
                document.body.style.cursor = 'default';

            })
            .save()
            .catch((err) => console.log(err));
    };

    return {
        searchReportExistencia,
        dataReporteExistencia,
        exportExcel,
        printPdf,
        idSubAlmacen,
        idCentroAtencion,
        idFuenteFinanciamiento,
    }

}
