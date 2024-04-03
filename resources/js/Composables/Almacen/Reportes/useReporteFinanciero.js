import { ref, inject, computed } from "vue";
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
    const financingSourcers = ref([])

    const reportInfo = ref({
        startDate: '',
        endDate: '',
        financingSourceId: ''
    })

    return {
        isLoadingExport, reportInfo, errors, financingSourcers
    }
}