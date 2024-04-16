import { ref, inject, computed, onMounted } from "vue";
import ReporteConsumoPdf from "@/pdf/Almacen/ReporteConsumoPdf.vue";
import { createApp, h } from 'vue'
import { jsPDF } from "jspdf";
import html2pdf from 'html2pdf.js'
import moment from "moment";

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


    return {
        getInformacionReport, varFilteredInForm, isLoadinRequest, dataGetRotation

    }

}
