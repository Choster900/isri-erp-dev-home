import { ref, inject } from "vue";
import axios from "axios";
import { useHandleError } from "@/Composables/General/useHandleError.js";
import moment from 'moment';
import EmpleadosPDF from '@/pdf/RRHH/EmpleadosPDF.vue';
import { createApp, h } from 'vue'
import html2pdf from 'html2pdf.js'

export const useReporteEmpleado = (context) => {

    const swal = inject("$swal");
    const isLoadingExport = ref(false);


    const exportExcel = async (queryResult, depInfo, title, date) => {
        swal({
            title: "¿Está seguro de exportar este reporte a Excel?",
            icon: "question",
            confirmButtonText: "Si, exportar.",
            confirmButtonColor: "#047857",
            cancelButtonText: "Cancelar",
            showCancelButton: true,
            showCloseButton: true,
            iconColor: "green"
        }).then(async (result) => {
            if (result.isConfirmed) {
                try {
                    isLoadingExport.value = true;
                    const response = await axios.post('/create-excel-employees',
                        {
                            queryResult: queryResult,
                            location: depInfo,
                            title: title,
                            date: date,
                        }, { responseType: 'blob' }
                    );
                    //Set the file name
                    const disposition = response.headers['content-disposition'];
                    const filenameRegex = /filename[^;=\n]*=((['"])[^\2]*\2|[^;\n]*)/;
                    const matches = filenameRegex.exec(disposition);
                    const filename = matches && matches.length > 1 ? matches[1].replace(/['"]/g, '') : 'default_filename.xlsx';

                    //Code to download excel
                    const url = window.URL.createObjectURL(new Blob([response.data]));
                    const link = document.createElement('a');
                    link.href = url;
                    link.setAttribute('download', filename); // Name assigned from the server
                    document.body.appendChild(link);
                    link.click();
                } catch (err) {
                    const { title, text, icon } = useHandleError(err);
                    swal({ title: title, text: text, icon: icon, timer: 5000 });
                } finally {
                    isLoadingExport.value = false;
                }
            }
        });
    };

    const generatePDF = async (queryResult, depInfo, title, date) => {
        swal({
            title: "¿Está seguro de exportar a PDF?",
            icon: "question",
            confirmButtonText: "Si, exportar.",
            confirmButtonColor: "#047857",
            cancelButtonText: "Cancelar",
            showCancelButton: true,
            showCloseButton: true,
            iconColor: "green"
        }).then(async (result) => {
            if (result.isConfirmed) {
                try {
                    isLoadingExport.value = true;
                    const data = {
                        queryResult: queryResult,
                        depInfo: depInfo,
                        title: title,
                        date: date
                    };
                    // const app = createApp(EmpleadosPDF, data);

                    const response = await axios.post('/create-pdf-employees', data, {
                        responseType: 'blob' // Espera una respuesta de tipo blob (archivo)
                    });

                    console.log(response);
            
                    const blob = new Blob([response.data], { type: 'application/pdf' });
                    const link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);
                    link.download = 'document.pdf';
                    link.click();

                    // const div = document.createElement('div');
                    // const pdfPrint = app.mount(div);
                    // const html = div.outerHTML;

                    // //const currentDateTime = new Date().toLocaleString();
                    // const currentDateTime = moment().format('DD/MM/YYYY, HH:mm:ss');
                    // let fecha = moment().format('DD-MM-YYYY');

                    // await html2pdf()
                    //     .set({
                    //         margin: [1,1.5,1.3,1], //top, left, buttom, right,
                    //         //pagebreak: { mode: ['avoid-all', 'css', 'legacy'] },
                    //         filename: 'RPT-EMPLEADOS-' + fecha,
                    //         image: {
                    //             type: 'jpeg',
                    //             quality: 0.8
                    //         },
                    //         html2canvas: {
                    //             scale: 2, // A mayor escala, mejores gráficos, pero más peso
                    //             //letterRendering: true,
                    //             useCORS: true
                    //         },
                    //         jsPDF: {
                    //             unit: "cm",
                    //             format: "letter",
                    //             orientation: 'landscape' // landscape o portrait
                    //         },
                    //     })
                    //     //codigo para paginar
                    //     .from(html).toPdf().get('pdf').then(function (pdf) {
                    //         var totalPages = pdf.internal.getNumberOfPages();
                    //         for (var i = 1; i <= totalPages; i++) {
                    //             pdf.setPage(i);
                    //             pdf.setFontSize(10);
                    //             //Text for the page number
                    //             let text = 'Página ' + i + ' de ' + totalPages;
                    //             const centerX = pdf.internal.pageSize.getWidth() / 2;
                    //             //Get the text width
                    //             const textWidth1 = pdf.getStringUnitWidth(text) * pdf.internal.getFontSize() / pdf.internal.scaleFactor;
                    //             //Get the middle position including the text width
                    //             const textX = centerX - (textWidth1 / 2);
                    //             //Write the text in the desired coordinates.
                    //             pdf.text(textX, (pdf.internal.pageSize.getHeight() - 0.6), text);
                    //             //Text for the date and time.
                    //             let date_text = 'Generado: ' + currentDateTime
                    //             //Get the text width
                    //             const textWidth = pdf.getStringUnitWidth(date_text) * pdf.internal.getFontSize() / pdf.internal.scaleFactor;
                    //             //Write the text in the desired coordinates.
                    //             pdf.text(pdf.internal.pageSize.getWidth() - textWidth - 1, pdf.internal.pageSize.getHeight() - 0.6, date_text);
                    //         }

                    //     })
                    //     .save()
                    //     .catch(err => console.log(err));
                } catch (err) {
                    console.log(err);
                } finally {
                    isLoadingExport.value = false;
                }
            }
        });
    }

    // const generatePDF = async (queryResult, depInfo, title, date) => {
    //     try {
    //         isLoadingExport.value = true;
    
    //         const data = {
    //             queryResult: queryResult,
    //             depInfo: depInfo,
    //             title: title,
    //             date: date
    //         };
    
    //         const response = await axios.post('/generate-pdf', data, {
    //             responseType: 'blob' // Espera una respuesta de tipo blob (archivo)
    //         });
    
    //         const blob = new Blob([response.data], { type: 'application/pdf' });
    //         const link = document.createElement('a');
    //         link.href = window.URL.createObjectURL(blob);
    //         link.download = 'document.pdf';
    //         link.click();
    //     } catch (error) {
    //         console.error('Error al generar el PDF:', error);
    //     } finally {
    //         isLoadingExport.value = false;
    //     }
    // };
    

    return {
        exportExcel,
        generatePDF,
        isLoadingExport
    }
}