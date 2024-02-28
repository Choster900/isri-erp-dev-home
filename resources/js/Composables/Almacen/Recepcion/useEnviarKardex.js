import { ref, inject, computed, nextTick } from "vue";
import axios from "axios";
import { useHandleError } from "@/Composables/General/useHandleError.js";
import { useShowToast } from "@/Composables/General/useShowToast.js";
import { toast } from "vue3-toastify";
import { useValidateInput } from '@/Composables/General/useValidateInput';
import { useFormatDateTime } from "@/Composables/General/useFormatDateTime.js";
import { localeData } from 'moment_spanish_locale';
import moment from 'moment';
import { upperCase } from "lodash";
moment.locale('es', localeData)

export const useEnviarKardex = (context) => {
    const swal = inject("$swal");
    const isLoadingRequest = ref(false);
    const errors = ref([]);

    const recInfo = ref({})
    const empOptions = ref([])

    const getInfoForModalSendKardex = async (id) => {
        try {
            isLoadingRequest.value = true;
            const response = await axios.get(
                `/get-info-modal-send-kardex/${id}`
            );
            recInfo.value = response.data.recInfo
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

    const showErrorMessage = (err) => {
        const { title, text, icon } = useHandleError(err);
        swal({ title: title, text: text, icon: icon, timer: 5000 });
    };

    return {
        isLoadingRequest, recInfo, errors, empOptions,
        getInfoForModalSendKardex
    }
}