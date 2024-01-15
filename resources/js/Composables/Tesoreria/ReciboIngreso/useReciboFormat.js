import { ref, inject, computed } from "vue";
import axios from "axios";
import ReciboIngresoMatricialVue from '@/pdf/Tesoreria/ReciboIngresoMatricial.vue';
import { createApp, h } from 'vue'
import { jsPDF } from "jspdf";
import html2pdf from 'html2pdf.js'
import moment from 'moment';
import { useHandleError } from "@/Composables/General/useHandleError.js";
import { useShowToast } from "@/Composables/General/useShowToast.js";
import { toast } from "vue3-toastify";
import _ from "lodash";

export const useReciboFormat = (context) => {
    const swal = inject("$swal");
    const isLoadingRequest = ref(false);
    const receipt_to_print = ref({})
    const letras1 = ref("")
    const letras2 = ref("")

    const getInfoForModalReciboFormat = async (id) => {
        try {
            isLoadingRequest.value = true;
            const response = await axios.get(
                `/get-info-modal-recibo-format/${id}`
            );
            receipt_to_print.value = response.data.receipt
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

    const printPdf = () => {
        let fecha = moment().format('DD-MM-YYYY');
        let name = 'RECIBO ' + receipt_to_print.value.numero_recibo_ingreso + ' - ' + fecha;
        const opt = {
            margin: 0,
            filename: name,
            //pagebreak: {mode:'css',before:'#pagebreak'},
            image: { type: 'jpeg', quality: 0.98 },
            html2canvas: { scale: 3, useCORS: true },
            //jsPDF: { unit: 'cm', format: [13.95,21.5], orientation: 'landscape' }
            jsPDF: { unit: 'cm', format: 'letter', orientation: 'portrait' },
        };

        const limiteCaracteres = 70;
        if (receipt_to_print.value.monto_letras.length <= limiteCaracteres) {
            letras1.value = receipt_to_print.value.monto_letras;
            letras2.value = ''
        } else {
            let textoTruncado = receipt_to_print.value.monto_letras.slice(0, limiteCaracteres);
            let ultimoEspacio = textoTruncado.lastIndexOf(' ');
            letras1.value = textoTruncado.slice(0, ultimoEspacio);
            letras2.value = receipt_to_print.value.monto_letras.slice(ultimoEspacio + 1);
        }

        const app = createApp(ReciboIngresoMatricialVue, {
            receipt_to_print: receipt_to_print.value,
            formatedAmount: receipt_to_print.value.monto_recibo_ingreso,
            empleado: empleado.value,
            nombre_cuenta: nombre_cuenta.value,
            fecha_recibo: fecha_recibo.value,
            letras1: letras1.value,
            letras2: letras2.value
        });
        const div = document.createElement('div');
        const pdfPrint = app.mount(div);
        const html = div.outerHTML;

        html2pdf().set(opt).from(html).save();
    }

    const formatedAmount = computed(() => {
        return '$' + receipt_to_print.value.monto_recibo_ingreso
    })

    const empleado = computed(() => {
        return receipt_to_print.value.empleado_tesoreria ? receipt_to_print.value.empleado_tesoreria.nombre_empleado_tesoreria : ''
    })

    const nombre_cuenta = computed(() => {
        return receipt_to_print.value.cuenta_presupuestal ? receipt_to_print.value.cuenta_presupuestal.nombre_ccta_presupuestal : ''
    })

    const fecha_recibo = computed(() => {
        return receipt_to_print.cuenta_presupuestal ? moment(receipt_to_print.fecha_recibo_ingreso, 'Y-M-D').format('DD/MM/Y') : ''
    })

    const showErrorMessage = (err) => {
        const { title, text, icon } = useHandleError(err);
        swal({ title: title, text: text, icon: icon, timer: 5000 });
    };

    return {
        receipt_to_print, isLoadingRequest,
        formatedAmount, empleado, nombre_cuenta, fecha_recibo,
        getInfoForModalReciboFormat, printPdf
    }

}