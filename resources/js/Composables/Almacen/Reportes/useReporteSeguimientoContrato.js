import { ref, onMounted, inject, computed } from "vue";
import axios from "axios";
import { useHandleError } from "@/Composables/General/useHandleError.js";
import { useShowToast } from "@/Composables/General/useShowToast.js";
import moment from 'moment';
import EmpleadosPDF from '@/pdf/RRHH/EmpleadosPDF.vue';
import { createApp, h } from 'vue'
import html2pdf from 'html2pdf.js'

export const useReporteSeguimientoContrato = (context) => {
    const swal = inject("$swal");
    const isLoadingReport = ref(false)
    const errors = ref([])
    const contracts = ref([])
    const itemContracts = ref([])
    const filterItems = ref([])
    const products = ref([])
    const contractName = ref()
    const purchaseProcess = ref()
    const load = ref(0)

    const reportInfo = ref({
        startDate: '',
        endDate: '',
        contractId: '',
        itemContractId: ''
    })

    const getOption = (e) => {
        const dateFormat = 'ddd MMM DD YYYY HH:mm:ss [GMT]ZZ';
        moment.locale('en');

        const now = moment();
        let startDate, endDate;

        switch (e) {
            case 0:
                startDate = now.clone().startOf('month').format(dateFormat);
                endDate = now.format(dateFormat);
                break;
            case 1:
                startDate = now.clone().subtract(1, 'month').startOf('month').format(dateFormat);
                endDate = now.clone().subtract(1, 'month').endOf('month').format(dateFormat);
                break;
            case 2:
                startDate = now.clone().startOf('year').format(dateFormat);
                endDate = now.format(dateFormat);
                break;
            case 3:
                startDate = now.clone().subtract(6, 'months').startOf('month').format(dateFormat);
                endDate = now.format(dateFormat);
                break;
            default:
                startDate = '';
                endDate = '';
                break;
        }

        reportInfo.value.startDate = startDate;
        reportInfo.value.endDate = endDate;

        moment.locale('es');
    }

    const changeContract = (id) => {
        if (!id) {
            reportInfo.value.contractId = ''
            reportInfo.value.itemContractId = ''
            filterItems.value = []
        } else {
            reportInfo.value.itemContractId = ''
            const selectedCntr = contracts.value.find((e) => e.value === id)
            filterItems.value = itemContracts.value.filter((e) => e.id_doc === selectedCntr.value)
        }
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

    const getContractTrackingReport = async () => {
        try {
            isLoadingReport.value = true;
            const response = await axios.post(`/get-contract-tracking-report`, reportInfo.value);
            contractName.value = response.data.contractName
            purchaseProcess.value = response.data.purchaseProcess

            const mesesAbreviados = ['ene', 'feb', 'mar', 'abr', 'may', 'jun', 'jul', 'ago', 'sep', 'oct', 'nov', 'dic'];
            const mesesCompletos = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

            products.value = response.data.products.map(producto => {
                const nuevoProducto = { id_prod_adquisicion: producto.id_prod_adquisicion, producto: producto.producto, meses: [] };

                mesesAbreviados.forEach((mes, index) => {
                    const cantPa = parseFloat(producto[`cant_pa_${mes}`]);
                    const cantRec = parseFloat(producto[`cant_rec_${mes}`]);
                    const saldo = parseFloat(producto[`saldo_${mes}`]);

                    nuevoProducto.meses.push({
                        mes: mesesCompletos[index],
                        res: {
                            cant_pa: purchaseProcess.value === 5 ? cantPa.toFixed(2) : floatToInt(cantPa),
                            cant_rec: purchaseProcess.value === 5 ? cantRec.toFixed(2) : floatToInt(cantRec),
                            saldo: purchaseProcess.value === 5 ? saldo.toFixed(2) : floatToInt(saldo)
                        }
                    });
                });

                return nuevoProducto;
            });
            

        } catch (err) {
            if (err.response && err.response.data.logical_error) {
                useShowToast(toast.error, err.response.data.logical_error);
            } else {
                showErrorMessage(err);
            }
        } finally {
            isLoadingReport.value = false;
            load.value++
        }
    }

    // Método para convertir un valor flotante con dos decimales a entero
    const floatToInt = (value) => {
        // Primero, convertimos el valor a un número flotante
        const floatValue = parseFloat(value);
        // Luego, lo redondeamos al entero más cercano
        const roundedValue = Math.round(floatValue);
        // Devolvemos el resultado como un número entero
        return roundedValue;
    }

    onMounted(async () => {
        await getContractsInfo()
    })

    return {
        isLoadingReport, reportInfo, errors, contracts, filterItems, products,
        contractName, purchaseProcess, load,
        getOption, changeContract, getContractTrackingReport
    }
}
