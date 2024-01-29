import { ref, inject, computed } from "vue";
import axios from "axios";
import { useHandleError } from "@/Composables/General/useHandleError.js";
import { useShowToast } from "@/Composables/General/useShowToast.js";
import { toast } from "vue3-toastify";
import { useFormatDateTime } from "@/Composables/General/useFormatDateTime.js";
import { localeData } from 'moment_spanish_locale';
import moment from 'moment';
moment.locale('es', localeData)


export const useShowFiniquito = (context) => {
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
            console.log(response.data);
        } catch (err) {
            //console.log(err);
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

    const dayToWords = (day) => {
        let numerosEnPalabras = {
            '01': 'uno',
            '02': 'dos',
            '03': 'tres',
            '04': 'cuatro',
            '05': 'cinco',
            '06': 'seis',
            '07': 'siete',
            '08': 'ocho',
            '09': 'nueve',
            '10': 'diez',
            '11': 'once',
            '12': 'doce',
            '13': 'trece',
            '14': 'catorce',
            '15': 'quince',
            '16': 'dieciseis',
            '17': 'diecisiete',
            '18': 'dieciocho',
            '19': 'diecinueve',
            '20': 'veinte',
            '10': 'veitiun',
            '11': 'once',
            '12': 'doce',
            '13': 'trece',
            '14': 'catorce',
            '15': 'quince',
            '16': 'dieciseis',
            '17': 'diecisiete',
            '18': 'dieciocho',
            '19': 'diecinueve',
            '20': 'veinte',
            '20': 'veinte',
        }
    }

    const formatearFecha = (date) => {

        const fechaMoment = moment(date, 'YYYY-MM-DD');
        let fechaFormateada = ""

        fechaFormateada = fechaMoment.format('DD');

        return 'nada'
        // if (fechaMoment.isValid()) {
        //     // Formato personalizado de fecha
        //     const formatoFecha = fechaMoment.format('DD [de] MMMM [de] YYYY');

        //     // Convertir números a palabras en el formato
        //     fechaFormateada = formatDayName(formatoFecha);
        // } else {
        //     fechaFormateada = "Formato de fecha inválido";
        // }
        // return fechaFormateada
    }

    const formatDayName = (fecha) => {
        // Función auxiliar para convertir números a palabras en una cadena de texto
        return fecha.replace(/\b\d+\b/g, function (match) {
            // Utilizamos moment para convertir números a palabras
            return moment(match, 'D').format('MMMM') === 'Invalid date'
                ? match  // No es un número del día, mantener sin cambios
                : moment(match, 'D').format('MMMM').toUpperCase(); // Convertir número del día a palabras
        });
    }

    return {
        finiquito, isLoadingRequest, hireDate, signatureDateA, period, signatureTime, 
        signatureDate, amount, year,
        getInfoForModalFiniquito, DUIToWords, formatearFecha
    }
}