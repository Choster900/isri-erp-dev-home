import { ref, onMounted, inject, computed } from "vue";
import axios from "axios";
import { useHandleError } from "@/Composables/General/useHandleError.js";
import { useShowToast } from "@/Composables/General/useShowToast.js";
import { toast } from "vue3-toastify";
import moment from 'moment';
import ReporteSeguimientoContratoPDF from '@/pdf/Almacen/ReporteSeguimientoContratoPDF.vue';
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
                            cant_pa: (purchaseProcess.value === 5 || producto.fraccionado_producto === 1) ? cantPa.toFixed(2) : floatToInt(cantPa),
                            cant_rec: (purchaseProcess.value === 5 || producto.fraccionado_producto === 1) ? cantRec.toFixed(2) : floatToInt(cantRec),
                            saldo: (purchaseProcess.value === 5 || producto.fraccionado_producto === 1) ? saldo.toFixed(2) : floatToInt(saldo)
                        }
                    });
                });

                return nuevoProducto;
            });

        } catch (err) {
            if (err.response && err.response.status === 422) {
                if (err.response.data.logical_error) {
                    useShowToast(toast.error, err.response.data.logical_error);
                } else {
                    useShowToast(toast.warning, "Tienes errores en tus datos, por favor verifica e intenta nuevamente.");
                    errors.value = err.response.data.errors;
                    // Limpiar los errores después de 5 segundos
                    setTimeout(() => {
                        errors.value = [];
                    }, 5000);
                }
            } else {
                showErrorMessage(err);
                context.emit("cerrar-modal")
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

    const generatePDF = async () => {
        swal({
            title: "¿Está seguro de exportar a PDF?",
            icon: "question",
            confirmButtonText: "Si, exportar.",
            confirmButtonColor: "#001c48",
            cancelButtonText: "Cancelar",
            showCancelButton: true,
            showCloseButton: true,
            iconColor: "#001c48"
        }).then(async (result) => {
            if (result.isConfirmed) {
                try {
                    isLoadingReport.value = true;
                    const data = {
                        products: products.value,
                        contractName: contractName.value,
                        purchaseProcess: purchaseProcess.value,
                        startDate: moment(reportInfo.value.startDate).format('DD,MMMM, YYYY'),
                        endDate: moment(reportInfo.value.endDate).format('DD,MMMM, YYYY')
                    };
                    const app = createApp(ReporteSeguimientoContratoPDF, data);
                    const div = document.createElement('div');
                    const pdfPrint = app.mount(div);
                    const html = div.outerHTML;

                    //const currentDateTime = new Date().toLocaleString();
                    const currentDateTime = moment().format('DD/MM/YYYY, HH:mm:ss');
                    let fecha = moment().format('DD-MM-YYYY');

                    await html2pdf()
                        .set({
                            margin: [0.3, 0.2, 0.5, 0.2],//top, left, buttom, right,
                            filename: 'RPT-SEGUIMIENTO-' + fecha,
                            image: {
                                type: 'jpeg',
                                quality: 0.98
                            },
                            html2canvas: {
                                scale: 3, // A mayor escala, mejores gráficos, pero más peso
                                letterRendering: true,
                                useCORS: true
                            },
                            jsPDF: {
                                unit: "in",
                                format: "letter",
                                orientation: 'landscape' // landscape o portrait
                            },
                        })
                        //codigo para paginar
                        .from(html).toPdf().get('pdf').then(function (pdf) {
                            var totalPages = pdf.internal.getNumberOfPages();
                            for (var i = 1; i <= totalPages; i++) {
                                pdf.setPage(i);
                                pdf.setFontSize(10);
                                //Text for the page number
                                let text = 'Página ' + i + ' de ' + totalPages;
                                const centerX = pdf.internal.pageSize.getWidth() / 2;
                                //Get the text width
                                const textWidth1 = pdf.getStringUnitWidth(text) * pdf.internal.getFontSize() / pdf.internal.scaleFactor;
                                //Get the middle position including the text width
                                const textX = centerX - (textWidth1 / 2);
                                //Write the text in the desired coordinates.
                                pdf.text(textX, (pdf.internal.pageSize.getHeight() - 0.3), text);
                                //Text for the date and time.
                                let date_text = 'Generado: ' + currentDateTime
                                //Get the text width
                                const textWidth = pdf.getStringUnitWidth(date_text) * pdf.internal.getFontSize() / pdf.internal.scaleFactor;
                                //Write the text in the desired coordinates.
                                pdf.text(pdf.internal.pageSize.getWidth() - textWidth - 0.4, pdf.internal.pageSize.getHeight() - 0.3, date_text);
                            }

                        })
                        .save()
                        .catch(err => console.log(err));

                } catch (err) {
                    const { title, text, icon } = useHandleError(err);
                    swal({ title: title, text: text, icon: icon, timer: 5000 });
                } finally {
                    isLoadingReport.value = false;
                }
            }
        });
    };

    const exportExcel = async () => {
        
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
                    isLoadingReport.value = true;
                    const data = {
                        products: products.value,
                        contractName: contractName.value,
                        purchaseProcess: purchaseProcess.value,
                        startDate: moment(reportInfo.value.startDate).format('DD,MMMM, YYYY'),
                        endDate: moment(reportInfo.value.endDate).format('DD,MMMM, YYYY')
                    };

                    const response = await axios.post('/create-excel-tracking-contract', data, { responseType: 'blob' });

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
                    isLoadingReport.value = false;
                }
            }
        });
    };

    onMounted(async () => {
        await getContractsInfo()
    })

    return {
        isLoadingReport, reportInfo, errors, contracts, filterItems, products,
        contractName, purchaseProcess, load,
        getOption, changeContract, getContractTrackingReport, generatePDF, exportExcel
    }
}
