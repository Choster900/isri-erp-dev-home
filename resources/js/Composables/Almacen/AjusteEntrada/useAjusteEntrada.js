import { ref, inject, computed, nextTick, watch } from "vue";
import axios from "axios";
import { useHandleError } from "@/Composables/General/useHandleError.js";
import { useShowToast } from "@/Composables/General/useShowToast.js";
import { toast } from "vue3-toastify";
import moment from 'moment';
import _ from "lodash";

export const useAjusteEntrada = (context) => {
    const swal = inject("$swal");
    const isLoadingRequest = ref(false);
    const errors = ref([]);

    const reasons = ref([])
    const centers = ref([])
    const financingSources = ref([])
    const lts = ref([])

    const adjustment = ref({
        id: '',
        centerId: '',
        financingSourceId: '',
        reasonId: '',
        idLt: '',
        number: '',
        date: '',
        observation: ''
    })

    const getInfoForModalAdjustment = async (id) => {
        try {
            isLoadingRequest.value = true;
            const response = await axios.post(
                `/get-info-modal-surplus-adjustment`, {
                id: id,
            });
            setModalValues(response.data, id)
        } catch (err) {
            console.log(err);
            if (err.response && err.response.data.logical_error) {
                useShowToast(toast.error, err.response.data.logical_error);
                context.emit("get-table");
            } else {
                showErrorMessage(err);
                context.emit("cerrar-modal");
            }
        } finally {
            isLoadingRequest.value = false;
        }
    };

    const setModalValues = (data, id) => {
        const req = data.req
        //Set the general information to show in the view

        reasons.value = data.reasons
        centers.value = data.centers
        financingSources.value = data.financingSources
        lts.value = data.lts


        // Check if id > 0
        if (id > 0) {
            adjustment.value.id = req.id_requerimiento //Set reception id
            adjustment.value.centerId = req.id_centro_atencion //Set acta number
            adjustment.value.financingSourceId = req.id_proy_financiado //Set invoice number
            adjustment.value.reasonId = req.id_motivo_ajuste //Set observation
            adjustment.value.idLt = req.id_lt // Set supplier

            //Missing details
        } else {
            // Call addNewRow
            //addNewRow();
        }
    }

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
        if (err.response.status === 422) {
            if (err.response && err.response.data.logical_error) {
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
        isLoadingRequest, errors, reasons, centers, financingSources, lts, adjustment,
        getInfoForModalAdjustment
    }
}