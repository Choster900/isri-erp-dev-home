import { ref, inject, computed } from "vue";
import axios from "axios";
import { useHandleError } from "@/Composables/General/useHandleError.js";
import { useShowToast } from "@/Composables/General/useShowToast.js";
import moment from 'moment';
import EmpleadosPDF from '@/pdf/RRHH/EmpleadosPDF.vue';
import { createApp, h } from 'vue'
import html2pdf from 'html2pdf.js'

export const useReporteSeguimientoContrato = (context) => {
    const isLoadingExport = ref(false)
    const errors = ref([])
    const contracts = ref([])
    const itemContracts = ref([])

    const reportInfo = ref({
        startDate: '',
        endDate: '',
        contractId: '',
        itemContractId: ''
    })

    const getOption = (e) => {
        moment.locale('en');
        switch (e) {
            case 0:
                reportInfo.value.startDate = moment().startOf('month').format('ddd MMM DD YYYY HH:mm:ss [GMT]ZZ');
                reportInfo.value.endDate = moment().format('ddd MMM DD YYYY HH:mm:ss [GMT]ZZ');
                break;
            case 1:
                reportInfo.value.startDate = moment().subtract(1, 'month').startOf('month').format('ddd MMM DD YYYY HH:mm:ss [GMT]ZZ');
                reportInfo.value.endDate = moment().subtract(1, 'month').endOf('month').format('ddd MMM DD YYYY HH:mm:ss [GMT]ZZ');
                break;
            case 2:
                reportInfo.value.startDate = moment().startOf('year').format('ddd MMM DD YYYY HH:mm:ss [GMT]ZZ');
                reportInfo.value.endDate = moment().format('ddd MMM DD YYYY HH:mm:ss [GMT]ZZ');
                break;
            case 3:
                reportInfo.value.startDate = moment().subtract(6, 'months').startOf('month').format('ddd MMM DD YYYY HH:mm:ss [GMT]ZZ');
                reportInfo.value.endDate = moment().format('ddd MMM DD YYYY HH:mm:ss [GMT]ZZ');
                break;
            case 4:
                reportInfo.value.startDate = ''; // Asigna el primer día de tu rango de datos
                reportInfo.value.endDate = ''; // Asigna el último día de tu rango de datos
                break;
            default:
                break;
        }
        moment.locale('es');
    }

    const showErrorMessage = (err) => {
        const { title, text, icon } = useHandleError(err);
        swal({ title: title, text: text, icon: icon, timer: 5000 });
    };

    const getContractsInfo = async () => {
        try {
            //isLoadingRequest.value = true;
            const response = await axios.get(`/get-contracts-info`);
            contracts.value = response.data.contracts
            itemContracts.value = response.data.itemContracts
        } catch (err) {
            if (err.response && err.response.data.logical_error) {
                useShowToast(toast.error, err.response.data.logical_error);
            } else {
                showErrorMessage(err);
            }
        } finally {
            //isLoadingRequest.value = false;
        }
    }

    onMounted(async () => {
        getContractsInfo()
    })

    return {
        isLoadingExport, reportInfo, errors, contracts, itemContracts, getOption
    }
}
