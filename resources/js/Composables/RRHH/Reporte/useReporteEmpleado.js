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
                    const response = await axios.post('/create-pdf-employees', data, {
                        responseType: 'blob' // Espera una respuesta de tipo blob (archivo)
                    });

                    // Obtener la fecha y hora de generación desde los encabezados
                    const generatedAt = response.headers['x-generated-at'];

                    const blob = new Blob([response.data], { type: 'application/pdf' });
                    const link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);

                    // Concatenar la fecha y hora de creación al nombre del archivo
                    link.download = `reporte-empleados_${generatedAt}.pdf`;
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

    return {
        exportExcel,
        generatePDF,
        isLoadingExport
    }
}