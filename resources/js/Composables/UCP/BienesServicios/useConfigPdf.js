import { jsPDF } from "jspdf";
import html2pdf from "html2pdf.js";
import moment from "moment";
import { createApp } from "vue";
import OrdenCompraBienesServicios from "@/pdf/UnidadComprasPublicas/OrdenCompraBienesServicios.vue";
//import OrdenCompraBienesServicios from "@/pdf/UnidadComprasPublicas/OrdenCompraBienesServicios.vue";
import OrdenCompraPdf from "@/pdf/UnidadComprasPublicas/OrdenCompraPdf.vue";
import ContratoPdf from "@/pdf/UnidadComprasPublicas/ContratoPdf.vue";


/**
 * Composable para configurar y generar PDFs de órdenes de compra.
 *
 * @param {Ref<Array>} arrayDocAdquisicion - Array de documentos de adquisición.
 * @param {Ref<Number>} idDetDocAdquisicion - ID del detalle del documento de adquisición.
 * @param {Ref<Object>} objectGetFromProp - Objeto con datos obtenidos de propiedades.
 * @param {Ref<Object>} notificacionDetDocAdquisicion - Notificación del detalle del documento de adquisición.
 * @param {Ref<Object>} recepcionDetDocAdquisicion - Recepción del detalle del documento de adquisición.
 * @param {Ref<Object>} observacionDetDocAdquisicion - Observación del detalle del documento de adquisición.
 * @param {Ref<Array>} arrayLineaTrabajo - Array de líneas de trabajo.
 * @param {Ref<Array>} arrayProductoAdquisicion - Array de productos de adquisición.
 * @param {Ref<Array>} brandsUsedInDoc - Array de marcas usadas en el documento.
 * @param {Ref<Array>} arrayUnidadMedida - Array de unidades de medida.
 * @param {Ref<Array>} arrayCentroAtencion - Array de centros de atención.
 * @param {Ref<String>} letterNumber - Número de carta.
 * @param {Ref<Number>} totProductos - Total de productos.
 *
 * @returns {Object} - Retorna la función `printOrdenCompraPdf` para generar el PDF.
 */
export const useConfigPdf = (
    arrayDocAdquisicion,
    idDetDocAdquisicion,
    objectGetFromProp,
    notificacionDetDocAdquisicion,
    recepcionDetDocAdquisicion,
    observacionDetDocAdquisicion,
    arrayLineaTrabajo,
    arrayProductoAdquisicion,
    brandsUsedInDoc,
    arrayUnidadMedida,
    arrayCentroAtencion,
    letterNumber,
    totProductos
) => {

    /**
     * Función para generar y descargar el PDF de la orden de compra.
     */
    const printOrdenCompraPdf = () => {
        // Cambiar el cursor a "wait" para indicar que el PDF se está generando
        document.body.style.cursor = 'wait';

        console.log(objectGetFromProp.value);

        // Configuración de opciones para html2pdf
        const opt = {
            margin: [0.5, 0.5, 2, 0.5], // Márgenes: superior, izquierdo, inferior, derecho
            filename: "ORDEN DE COMPRA BIENES Y SERVICIOS",
            image: { type: "jpeg", quality: 0.98 },
            html2canvas: { scale: 3, useCORS: true },
            jsPDF: { unit: "cm", format: "letter", orientation: "portrait" },
        };

        // Crear una instancia de la aplicación Vue para el componente OrdenCompraBienesServicios
        const app = createApp(OrdenCompraPdf, {
            arrayDocAdquisicion: arrayDocAdquisicion.value,
            idDetDocAdquisicion: idDetDocAdquisicion.value,
            objectGetFromProp: objectGetFromProp.value,
            notificacionDetDocAdquisicion: notificacionDetDocAdquisicion.value,
            recepcionDetDocAdquisicion: recepcionDetDocAdquisicion.value,
            observacionDetDocAdquisicion: observacionDetDocAdquisicion.value,
            arrayLineaTrabajo: arrayLineaTrabajo.value,
            arrayProductoAdquisicion: arrayProductoAdquisicion.value,
            arrayMarca: brandsUsedInDoc.value,
            arrayUnidadMedida: arrayUnidadMedida.value,
            arrayCentroAtencion: arrayCentroAtencion.value,
            letterNumber: letterNumber.value,
            totProductos: totProductos.value,
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

                    // Texto para el número de página
                    let text = "Página " + i + " de " + totalPages;
                    const centerX = pdf.internal.pageSize.getWidth() / 2;
                    const textWidth1 = (pdf.getStringUnitWidth(text) * pdf.internal.getFontSize()) / pdf.internal.scaleFactor;
                    const textX = centerX - textWidth1 / 2;
                    pdf.text(textX, pdf.internal.pageSize.getHeight() - 0.6, text);

                    // Texto para la fecha y hora
                    let date_text = "Generado: " + currentDateTime;
                    const textWidth = (pdf.getStringUnitWidth(date_text) * pdf.internal.getFontSize()) / pdf.internal.scaleFactor;
                    pdf.text(pdf.internal.pageSize.getWidth() - textWidth - 0.6, pdf.internal.pageSize.getHeight() - 0.6, date_text);
                }

                // Cambiar el cursor de vuelta a "default"
                document.body.style.cursor = 'default';
            })
            .save()
            .catch((err) => {
                console.error(err);
                document.body.style.cursor = 'default';
            });
    };


    /**
     * Función para generar y descargar el PDF de la orden de compra.
     */
    const printContratoPdf = () => {
        // Cambiar el cursor a "wait" para indicar que el PDF se está generando
        document.body.style.cursor = 'wait';

        console.log(objectGetFromProp.value);

        // Configuración de opciones para html2pdf
        const opt = {
            margin: [0.5, 0.5, 2, 0.5], // Márgenes: superior, izquierdo, inferior, derecho
            filename: "ORDEN DE COMPRA BIENES Y SERVICIOS",
            image: { type: "jpeg", quality: 0.98 },
            html2canvas: { scale: 3, useCORS: true },
            jsPDF: { unit: "cm", format: "letter", orientation: "landscape" },
        };

        // Crear una instancia de la aplicación Vue para el componente OrdenCompraBienesServicios
        const app = createApp(ContratoPdf, {
            arrayDocAdquisicion: arrayDocAdquisicion.value,
            idDetDocAdquisicion: idDetDocAdquisicion.value,
            objectGetFromProp: objectGetFromProp.value,
            notificacionDetDocAdquisicion: notificacionDetDocAdquisicion.value,
            recepcionDetDocAdquisicion: recepcionDetDocAdquisicion.value,
            observacionDetDocAdquisicion: observacionDetDocAdquisicion.value,
            arrayLineaTrabajo: arrayLineaTrabajo.value,
            arrayProductoAdquisicion: arrayProductoAdquisicion.value,
            arrayMarca: brandsUsedInDoc.value,
            arrayUnidadMedida: arrayUnidadMedida.value,
            arrayCentroAtencion: arrayCentroAtencion.value,
            letterNumber: letterNumber.value,
            totProductos: totProductos.value,
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

                    // Texto para el número de página
                    let text = "Página " + i + " de " + totalPages;
                    const centerX = pdf.internal.pageSize.getWidth() / 2;
                    const textWidth1 = (pdf.getStringUnitWidth(text) * pdf.internal.getFontSize()) / pdf.internal.scaleFactor;
                    const textX = centerX - textWidth1 / 2;
                    pdf.text(textX, pdf.internal.pageSize.getHeight() - 0.6, text);

                    // Texto para la fecha y hora
                    let date_text = "Generado: " + currentDateTime;
                    const textWidth = (pdf.getStringUnitWidth(date_text) * pdf.internal.getFontSize()) / pdf.internal.scaleFactor;
                    pdf.text(pdf.internal.pageSize.getWidth() - textWidth - 0.6, pdf.internal.pageSize.getHeight() - 0.6, date_text);
                }

                // Cambiar el cursor de vuelta a "default"
                document.body.style.cursor = 'default';
            })
            .save()
            .catch((err) => {
                console.error(err);
                document.body.style.cursor = 'default';
            });
    };



    const exportDocumentToExcel = async () => {
        try {
            try {
                document.body.style.cursor = 'wait';

                const response = await axios.post(
                    "/export-reporte-bienes-servicios-to-excel",
                    {
                        arrayDocAdquisicion: arrayDocAdquisicion.value,
                        idDetDocAdquisicion: idDetDocAdquisicion.value,
                        objectGetFromProp: objectGetFromProp.value,
                        notificacionDetDocAdquisicion: notificacionDetDocAdquisicion.value,
                        recepcionDetDocAdquisicion: recepcionDetDocAdquisicion.value,
                        observacionDetDocAdquisicion: observacionDetDocAdquisicion.value,
                        arrayLineaTrabajo: arrayLineaTrabajo.value,
                        arrayProductoAdquisicion: arrayProductoAdquisicion.value,
                        arrayMarca: brandsUsedInDoc.value,
                        arrayUnidadMedida: arrayUnidadMedida.value,
                        arrayCentroAtencion: arrayCentroAtencion.value,
                        letterNumber: letterNumber.value,
                        totProductos: totProductos.value,
                    },
                    { responseType: "blob" }
                );

                // Crear una URL para el blob
                const url = window.URL.createObjectURL(new Blob([response.data]));

                // Crear un enlace temporal y simular un clic para descargar el archivo
                const link = document.createElement("a");
                link.href = url;
                link.setAttribute("download", "REPORTE_CONSUMO_" + moment().format('L') + ".xlsx"); // Nombre del archivo deseado
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
        printOrdenCompraPdf,exportDocumentToExcel, printContratoPdf
    }
}
