import { createApp, ref, inject } from "vue";
import html2pdf from 'html2pdf.js';
import ActaDonacionPDFVue from '@/pdf/Almacen/ActaDonacionPDF.vue';

import axios from "axios";
import { useHandleError } from "@/Composables/General/useHandleError.js";
import { useShowToast } from "@/Composables/General/useShowToast.js";
import { toast } from "vue3-toastify";
import moment from 'moment';

export const useEnviarDonacion = (context, getDataToShow, tableData) => {
    const swal = inject("$swal");
    const isLoadingRequest = ref(false);
    const errors = ref([]);
    const isLoadingTop = ref(false)
    const empOptions = ref([])

    const infoToSend = ref({
        id: '',
        authorizeEmpId: '',
        receiveEmpId: '',
        observation: '',
    })

    const getInfoForModalSendDonation = async (id) => {
        try {
            isLoadingRequest.value = true;
            const response = await axios.get(
                `/get-info-modal-send-donation/${id}`
            );
            infoToSend.value.id = id
            empOptions.value = response.data.empOptions
        } catch (err) {
            if (err.response.data.logical_error) {
                useShowToast(toast.error, err.response.data.logical_error);
                context.emit("get-table");
            } else {
                showErrorMessage(err);
            }
            context.emit("cerrar-modal");
        } finally {
            isLoadingRequest.value = false;
        }
    };

    const printDonation = async (id) => {
        swal({
            title: '¿Está seguro de imprimir el acta de recepción?.',
            icon: 'question',
            iconHtml: '❓',
            confirmButtonText: 'Si, imprimir',
            confirmButtonColor: '#141368',
            cancelButtonText: 'Cancelar',
            showCancelButton: true,
            showCloseButton: true
        }).then(async (result) => {
            if (result.isConfirmed) {
                try {
                    isLoadingTop.value = true;
                    const response = await axios.get(
                        `/print-donation/${id}`
                    );
                    let fecha = moment().format('DD-MM-YYYY');
                    let name = 'ACTA ' + response.data.recToPrint.acta_recepcion_pedido + ' - ' + fecha;
                    const opt = {
                        //margin: [1, 1, 1, 1], //top, left, buttom, right,
                        margin: 1,
                        filename: name,
                        //pagebreak: { mode: ['avoid-all', 'css', 'legacy'] },
                        image: { type: 'jpeg', quality: 0.98 },
                        html2canvas: { scale: 3, useCORS: true },
                        jsPDF: { unit: 'cm', format: 'letter', orientation: 'portrait' }
                        //jsPDF: { unit: 'cm', format: 'letter', orientation: 'portrait' },
                    };
                    const app = createApp(ActaDonacionPDFVue, {
                        recToPrint: response.data.recToPrint,
                    });
                    const div = document.createElement('div');
                    const pdfPrint = app.mount(div);
                    const html = div.outerHTML;

                    const currentDateTime = moment().format('DD/MM/YYYY, HH:mm:ss');

                    html2pdf().set(opt).from(html).toPdf().get('pdf').then(function (pdf) {
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
                            pdf.text(textX, (pdf.internal.pageSize.getHeight() - 0.6), text);
                            //Text for the date and time.
                            let date_text = 'Generado: ' + currentDateTime
                            //Get the text width
                            const textWidth = pdf.getStringUnitWidth(date_text) * pdf.internal.getFontSize() / pdf.internal.scaleFactor;
                            //Write the text in the desired coordinates.
                            pdf.text(pdf.internal.pageSize.getWidth() - textWidth - 0.6, pdf.internal.pageSize.getHeight() - 0.6, date_text);
                        }

                    })
                        .save()
                        .catch(err => console.log(err));

                } catch (error) {
                    console.log(error);
                    showErrorMessage(error);
                } finally {
                    isLoadingTop.value = false;
                }
            }
        });
    }

    const changeStatusElement = async (elementId, status) => {
        swal({
            title: "¿Está seguro de Eliminar esta donacion?",
            icon: "question",
            iconHtml: "❓",
            confirmButtonText: "Si, Eliminar",
            confirmButtonColor: "#DC2626",
            cancelButtonText: "Cancelar",
            showCancelButton: true,
            showCloseButton: true,
        }).then(async (result) => {
            if (result.isConfirmed) {
                isLoadingTop.value = true;
                try {
                    const response = await axios.post('/change-status-donation', {
                        id: elementId,
                        status: status
                    });
                    useShowToast(toast.success, response.data.message);
                } catch (err) {
                    if (err.response.status === 422) {
                        if (err.response.data.logical_error) {
                            useShowToast(toast.error, err.response.data.logical_error);
                        }
                    } else {
                        showErrorMessage(err);
                    }
                } finally {
                    isLoadingTop.value = false;
                    getDataToShow(tableData.currentPage)
                }
            }
        });
    }

    const sendDonation = async (obj) => {
        swal({
            title: '¿Está seguro de registrar la donación en el kardex? Ten en cuenta que no podrás revertir los cambios.',
            icon: 'question',
            iconHtml: '❓',
            confirmButtonText: 'Si, Registrar',
            confirmButtonColor: '#141368',
            cancelButtonText: 'Cancelar',
            showCancelButton: true,
            showCloseButton: true
        }).then(async (result) => {
            if (result.isConfirmed) {
                saveObject(obj, '/send-goods-donation');
            }
        });
    };

    const saveObject = async (obj, url) => {
        isLoadingRequest.value = true;
        await axios
            .post(url, obj)
            .then((response) => {
                handleSuccessResponse(response)
            })
            .catch((error) => {
                handleErrorResponse(error)
            })
            .finally(() => {
                isLoadingRequest.value = false;
            });
    };

    const handleErrorResponse = (err) => {
        console.log(err);
        if (err.response.status === 422) {
            if (err.response.data.logical_error) {
                useShowToast(toast.error, err.response.data.logical_error);
            } else {
                useShowToast(toast.warning, "Tienes algunos errores, por favor verifica los datos enviados.");
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
    };

    const handleSuccessResponse = (response) => {
        useShowToast(toast.success, response.data.message);
        context.emit("cerrar-modal")
        context.emit("get-table")
    }

    const showErrorMessage = (err) => {
        const { title, text, icon } = useHandleError(err);
        swal({ title: title, text: text, icon: icon, timer: 5000 });
    };

    return {
        isLoadingTop, isLoadingRequest, infoToSend, errors, empOptions,
        changeStatusElement, getInfoForModalSendDonation, sendDonation, printDonation
    }
}