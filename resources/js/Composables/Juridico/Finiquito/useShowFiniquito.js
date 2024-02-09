import { ref } from "vue";
import axios from "axios";
import { createApp, inject } from 'vue'
import FiniquitoEmpPDFVue from '@/pdf/Juridico/FiniquitoEmpPDF.vue';
import { useHandleError } from "@/Composables/General/useHandleError.js";
import { useShowToast } from "@/Composables/General/useShowToast.js";
import { toast } from "vue3-toastify";
import html2pdf from 'html2pdf.js'
import { localeData } from 'moment_spanish_locale';
import moment from 'moment';
moment.locale('es', localeData)


export const useShowFiniquito = (context) => {
    const swal = inject("$swal");
    const finiquito = ref([])
    const isLoadingRequest = ref(false)
    const hireDate = ref("")
    const signatureDate = ref("")
    const signatureDateA = ref("")
    const signatureTime = ref("")
    const period = ref("")
    const amount = ref("")
    const year = ref("")

    const getInfoForModalFiniquito = async (id) => {
        try {
            isLoadingRequest.value = true;
            const response = await axios.get(
                `/get-info-modal-show-finiquito/${id}`
            );
            finiquito.value = response.data.finiquitoEmp
            hireDate.value = response.data.hireDate
            signatureDate.value = response.data.signatureDate
            signatureDateA.value = response.data.signatureDateA
            period.value = response.data.period
            signatureTime.value = response.data.signatureTime
            amount.value = response.data.amountText
            year.value = response.data.year
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

    const DUIToWords = (dui) => {
        let numerosEnPalabras = {
            '0': 'cero',
            '1': 'uno',
            '2': 'dos',
            '3': 'tres',
            '4': 'cuatro',
            '5': 'cinco',
            '6': 'seis',
            '7': 'siete',
            '8': 'ocho',
            '9': 'nueve',
            '-': 'guion',
        }

        const words = dui.split("").map((digito) => numerosEnPalabras[digito] || "").join(" ");

        return words
    }

    const printPdf = () => {
        if (finiquito.value.firmado_finiquito_laboral == 1) {
            generatePDF();
        } else {
            swal({
                title: '¿Está seguro de generar PDF? Una vez generado el sistema lo registrará como firmado.',
                icon: 'question',
                iconHtml: '❓',
                confirmButtonText: 'Si, generar',
                confirmButtonColor: '#141368',
                cancelButtonText: 'Cancelar',
                showCancelButton: true,
                showCloseButton: true
            }).then(async (result) => {
                if (result.isConfirmed) {
                    await generatePDF();
                }
            });
        }
    }

    const generatePDF = async () => {
        try {
            isLoadingRequest.value = true;
            const response = await axios.get(
                `/print-settlement/${finiquito.value.id_finiquito_laboral}`
            );
            let fecha = moment().format('DD-MM-YYYY');
            let name = 'FINIQUITO ' + finiquito.value.empleado.persona.nombre_apellido + ' - ' + fecha;
            const opt = {
                //margin: [0, 2.5, 0, 2.5], //top, left, buttom, right,
                margin: [2.5, 2.5, 2.5, 2.5],
                filename: name,
                //pagebreak: {mode:'avoid-all'},
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 3, useCORS: false },
                jsPDF: { unit: 'cm', format: 'letter', orientation: 'portrait' }
                //jsPDF: { unit: 'cm', format: 'letter', orientation: 'portrait' },
            };
            const app = createApp(FiniquitoEmpPDFVue, {
                finiquito: finiquito.value,
                DUIWords: DUIToWords(finiquito.value.empleado.persona.dui_persona),
                hireDate: hireDate.value,
                signatureDate: signatureDate.value,
                signatureDateA: signatureDateA.value,
                signatureTime: signatureTime.value,
                period: period.value,
                amount: amount.value,
                year: year.value
            });
            const div = document.createElement('div');
            const pdfPrint = app.mount(div);
            const html = div.outerHTML;

            html2pdf().set(opt).from(html).save();

        } catch (error) {
            showErrorMessage(error);
            context.emit("cerrar-modal");
            context.emit("get-table");
        } finally {
            isLoadingRequest.value = false;
            context.emit("cerrar-modal");
            context.emit("get-table");
        }
    }

    const showErrorMessage = (err) => {
        const { title, text, icon } = useHandleError(err);
        swal({ title: title, text: text, icon: icon, timer: 5000 });
    };

    return {
        finiquito, isLoadingRequest, hireDate, signatureDateA, period, signatureTime,
        signatureDate, amount, year,
        getInfoForModalFiniquito, DUIToWords, printPdf,
    }
}