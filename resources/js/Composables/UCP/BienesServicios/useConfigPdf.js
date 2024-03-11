import { jsPDF } from "jspdf";
import html2pdf from 'html2pdf.js'
import moment from "moment";
import { createApp, h } from 'vue'
import OrdenCompraBienesServicios from "@/pdf/UnidadComprasPublicas/OrdenCompraBienesServicios.vue";
export const useConfigPdf = (arrayDocAdquisicion, idDetDocAdquisicion,objectGetFromProp) => {
    const printPdf = () => {
/*         console.log(idDetDocAdquisicion.value);
        console.log(arrayDocAdquisicion.value); */
        console.log(objectGetFromProp.value);
        let fecha = moment().format('DD-MM-YYYY');
        let name = 'NOMBRE DOCUMENTO - FECHA - CODIGO';
        const opt = {
            margin: [0.2, 0.2, 0.1, 0.2], //top, left, bottom, right,
            filename: 'evaluacion',
            image: { type: 'jpeg', quality: 0.98 },
            html2canvas: { scale: 2, useCORS: true },
            pagebreak: { mode: ['avoid-all', 'css', 'legacy'] },
            jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' },
        };
        // Crear una instancia de la aplicación Vue para generar el componente quedanPDFVue
        const app = createApp(OrdenCompraBienesServicios, {
            arrayDocAdquisicion: arrayDocAdquisicion.value,
            idDetDocAdquisicion: idDetDocAdquisicion.value,
            objectGetFromProp: objectGetFromProp.value,
        });
        // Crear un elemento div y montar la instancia de la aplicación en él
        const div = document.createElement('div');
        const pdfPrint = app.mount(div);
        const html = div.outerHTML;

        // Generar y guardar el PDF utilizando html2pdf
        html2pdf().set(opt).from(html).save();
    };
    return {
        printPdf
    }
}
