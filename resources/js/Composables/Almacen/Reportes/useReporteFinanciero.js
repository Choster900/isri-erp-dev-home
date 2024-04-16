import { ref, inject, computed, onMounted } from "vue";
import axios from "axios";
import { useHandleError } from "@/Composables/General/useHandleError.js";
import moment from 'moment';
import EmpleadosPDF from '@/pdf/RRHH/EmpleadosPDF.vue';
import { createApp, h } from 'vue'
import { jsPDF } from "jspdf";
import html2pdf from 'html2pdf.js'
import ReporteFinancieroPdf from '@/pdf/Almacen/ReporteFinancieroPdf.vue'

export const useReporteFinanciero = (context) => {
    const isLoadingExport = ref(false)
    const errors = ref([])
    const financingArray = ref([])
    const dataReporteInfo = ref([])

    const reportInfo = ref({
        startDate: '',
        endDate: '',
        numeroCuenta: '611',
        financingSourceId: '',
    })
    /* const dataFinanciamiento = ref({}) */

    const getProyectoFinanciado = async () => {

        const resp = await axios.post("/get-proyecto-financiado",);

        const { data } = resp
        console.log(data);
        financingArray.value = data.map(index => {
            return { value: index.id_proy_financiado, label: `${index.codigo_proy_financiado} - ${index.nombre_proy_financiado}`, disabled: false };
        })

    }


    const printPdf = () => {
        let fecha = moment().format("DD-MM-YYYY");
        let name = "NOMBRE DOCUMENTO - FECHA - CODIGO";
        const opt = {
            margin: [0.5, 0.1, 2, 0.5], //top, left, bottom, right,
            filename: "evaluacion",
            //pagebreak: { mode: ['avoid-all', 'css', 'legacy'] },
            image: { type: "jpeg", quality: 0.98 },
            html2canvas: { scale: 3, useCORS: true },
            jsPDF: { unit: "cm", format: "letter", orientation: "landscape" },
        };
        // Crear una instancia de la aplicación Vue para generar el componente quedanPDFVue
        const app = createApp(ReporteFinancieroPdf, {
            dataFinanciamiento: dataReporteInfo.value
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

    onMounted(() => {
        getProyectoFinanciado()
    })
    return {
        printPdf,
        isLoadingExport, reportInfo, errors, financingArray, dataReporteInfo
    }
}
