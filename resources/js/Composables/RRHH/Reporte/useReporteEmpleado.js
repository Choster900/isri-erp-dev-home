import { ref, inject, computed } from "vue";
import axios from "axios";
import { useHandleError } from "@/Composables/General/useHandleError.js";
import moment from 'moment';
import { useShowToast } from "@/Composables/General/useShowToast.js";
import { toast } from "vue3-toastify";
//import FileSaver from 'file-saver';

export const useReporteEmpleado = (context) => {

    const swal = inject("$swal");
    const isLoadingExport = ref(false);


    const exportExcel = async (queryResult, depInfo, title, date) => {
        swal({
            title: "¿Está seguro de exportar este reporte a excel",
            icon: "question",
            iconHtml: "❓",
            confirmButtonText: "Si, exportar.",
            confirmButtonColor: "#047857",
            cancelButtonText: "Cancelar",
            showCancelButton: true,
            showCloseButton: true,
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
                    console.log(err);
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
        isLoadingExport
    }
}