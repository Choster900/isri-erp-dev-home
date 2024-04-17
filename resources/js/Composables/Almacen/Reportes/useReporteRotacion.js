import { ref, inject, computed, onMounted } from "vue";
import { createApp, h } from 'vue'
import { jsPDF } from "jspdf";
import html2pdf from 'html2pdf.js'
import moment from "moment";
import ReporteRotacionPdf from "@/pdf/Almacen/ReporteRotacionPdf.vue";
export const useReporteRotacion = (context) => {

    const dataGetRotation = ref([])
    const isLoadinRequest = ref(false)
    const varFilteredInForm = ref({
        idProy: '',
        idCentro: '',
        porcentaje: '',
        fechaInicial: '',
        fechaFinal: '',
    })

    const getInformacionReport = async () => {
        try {
            isLoadinRequest.value = true;

            const resp = await axios.post("/get-reporte-rotacion", { varFilteredInForm: varFilteredInForm.value });
            const { data } = resp;

            console.log(data);

            dataGetRotation.value = data;
            isLoadinRequest.value = false;
        } catch (error) {
            console.error('Ocurrió un error al obtener la información del reporte:', error);
            isLoadinRequest.value = false;


        } finally {
            isLoadinRequest.value = false;
        }

    }


    const printPdf = () => {
        let fecha = moment().format("DD-MM-YYYY");
        let name = "NOMBRE DOCUMENTO - FECHA - CODIGO";
        const opt = {
            //margin: [0.5, 0.1, 2, 0.5], //top, left, bottom, right,
            margin: 0.5,
            filename: "consumo",
            //pagebreak: { mode: ['avoid-all', 'css', 'legacy'] },
            image: { type: "jpeg", quality: 0.98 },
            html2canvas: { scale: 3, useCORS: true },
            jsPDF: { unit: "cm", format: "letter", orientation: "landscape" },
        };
        // Crear una instancia de la aplicación Vue para generar el componente quedanPDFVue
        const app = createApp(ReporteRotacionPdf, { dataReporteInfo: dataGetRotation.value });
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
                    "/get-excel-reporte-rotacion",
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

    const getOption = (e) => {
        moment.locale('en');
        switch (e) {
            case 0:
                varFilteredInForm.value.fechaInicial = moment().startOf('month').format('ddd MMM DD YYYY HH:mm:ss [GMT]ZZ');
                varFilteredInForm.value.fechaFinal = moment().format('ddd MMM DD YYYY HH:mm:ss [GMT]ZZ');
                break;
            case 1:
                varFilteredInForm.value.fechaInicial = moment().subtract(1, 'month').startOf('month').format('ddd MMM DD YYYY HH:mm:ss [GMT]ZZ');
                varFilteredInForm.value.fechaFinal = moment().subtract(1, 'month').endOf('month').format('ddd MMM DD YYYY HH:mm:ss [GMT]ZZ');
                break;
            case 2:
                varFilteredInForm.value.fechaInicial = moment().startOf('year').format('ddd MMM DD YYYY HH:mm:ss [GMT]ZZ');
                varFilteredInForm.value.fechaFinal = moment().format('ddd MMM DD YYYY HH:mm:ss [GMT]ZZ');
                break;
            case 3:
                varFilteredInForm.value.fechaInicial = moment().subtract(6, 'months').startOf('month').format('ddd MMM DD YYYY HH:mm:ss [GMT]ZZ');
                varFilteredInForm.value.fechaFinal = moment().format('ddd MMM DD YYYY HH:mm:ss [GMT]ZZ');
                break;
            case 4:
                varFilteredInForm.value.fechaInicial = ''; // Asigna el primer día de tu rango de datos
                varFilteredInForm.value.fechaFinal = ''; // Asigna el último día de tu rango de datos
                break;
            default:
                break;
        }


    }
    return {
        getInformacionReport, varFilteredInForm, isLoadinRequest, dataGetRotation, exportExcel, printPdf, getOption

    }

}
