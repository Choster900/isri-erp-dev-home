import { ref, inject, computed, onMounted } from "vue";
import axios from "axios";
import { useHandleError } from "@/Composables/General/useHandleError.js";
import moment from 'moment';
import EmpleadosPDF from '@/pdf/RRHH/EmpleadosPDF.vue';
import { createApp, h } from 'vue'
//import { jsPDF } from "jspdf";
import html2pdf from 'html2pdf.js'


//import FileSaver from 'file-saver';

export const useReporteFinanciero = (context) => {
    const isLoadingExport = ref(false)
    const errors = ref([])
    const financingArray = ref([])
    const dataReporteInfo = ref([])

    const reportInfo = ref({
        startDate: '',
        endDate: '',
        financingSourceId: ''
    })

    const getProyectoFinanciado = async () => {

        const resp = await axios.post("/get-proyecto-financiado",);

        const { data } = resp
        console.log(data);
        financingArray.value = data.map(index => {
            return { value: index.id_proy_financiado, label: `${index.codigo_proy_financiado} - ${index.nombre_proy_financiado}`, disabled: false };
        })

    }

    onMounted(() => {
        getProyectoFinanciado()
    })
    return {
        isLoadingExport, reportInfo, errors, financingArray,dataReporteInfo
    }
}
