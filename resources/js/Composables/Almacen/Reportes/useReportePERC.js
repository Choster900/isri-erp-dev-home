import { ref, inject, computed, onMounted } from "vue";
import ReporteConsumoPdf from "@/pdf/Almacen/ReporteConsumoPdf.vue";
import { createApp, h } from 'vue'
import { jsPDF } from "jspdf";
import html2pdf from 'html2pdf.js'
import moment from "moment";
export const useReportePERC = () => {


    const errors = ref([])
    const isLoadingExport = ref([])
    const isLoadinRequest = ref(false)
    const dataReportePerc = ref([])

    //Variables a enviar para consulta
    const idTipoPerc = ref(null)
    const idCentro = ref(null)
    const fechaInicial = ref(null)
    const fechaFinal = ref(null)



    const getInformacionReport = async () => {
        try {
            isLoadinRequest.value = true;

            const resp = await axios.post(
                "/get-reporte-perc-report", {
                    idTipoPerc: idTipoPerc.value,
                    idCentro: idCentro.value,
                    fechaInicial: fechaInicial.value,
                    fechaFinal: fechaFinal.value,
                }
            );
            const { data } = resp;
            console.log(data);


            dataReportePerc.value = data
        } catch (error) {
            console.error("Ocurrió un error al obtener la información del reporte:", error);

            if (error.response.status === 422) {
                // Obtiene los errores del cuerpo de la respuesta y los transforma a un formato más manejable
                let data = error.response.data.errors;
                var myData = new Object();
                for (const errorBack in data) {
                    myData[errorBack] = data[errorBack][0];
                }
                // Actualiza el estado "errors" con los errores y los limpia después de 5 segundos
                errors.value = myData;
                setTimeout(() => {
                    errors.value = [];
                }, 5000);
                console.error("Error en guardar los datos:", errors.value);
            }

            isLoadinRequest.value = false;

        } finally {
            isLoadinRequest.value = false;
        }
    };

    const printPdf = () => {
        document.body.style.cursor = 'wait';

        let fecha = moment().format("DD-MM-YYYY");
        let name = "NOMBRE DOCUMENTO - FECHA - CODIGO";
        const opt = {
            //margin: [0.5, 0.1, 2, 0.5], //top, left, bottom, right,
            margin: 0.5,
            filename: "REPORTE_CONSUMO_" + moment().format("L"),
            //pagebreak: { mode: ['avoid-all', 'css', 'legacy'] },
            image: { type: "jpeg", quality: 0.98 },
            html2canvas: { scale: 3, useCORS: true },
            jsPDF: { unit: "cm", format: "letter", orientation: "landscape" },
        };
        // Crear una instancia de la aplicación Vue para generar el componente quedanPDFVue
        const app = createApp(ReporteConsumoPdf, {
            dataConsumo: dataReportePerc.value,
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
                document.body.style.cursor = 'default';

            })
            .save()
            .catch((err) => console.log(err));
    };

    const exportExcel = async () => {
        try {
            try {
                document.body.style.cursor = 'wait';

                const response = await axios.post(
                    "/get-reporte-excel-kardex",
                    {

                    },
                    { responseType: "blob" }
                );

                // Crear una URL para el blob
                const url = window.URL.createObjectURL(new Blob([response.data]));

                // Crear un enlace temporal y simular un clic para descargar el archivo
                const link = document.createElement("a");
                link.href = url;
                link.setAttribute("download", "TARJETA_KARDEX" + moment().format('L') + ".xlsx"); // Nombre del archivo deseado
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
        exportExcel,
        getInformacionReport,
        isLoadingExport,
        dataReportePerc,
        isLoadinRequest,
        printPdf,
        errors,

        fechaFinal,
        fechaInicial,
        idCentro,
        idTipoPerc,


    }
}
