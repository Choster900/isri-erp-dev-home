import { ref, inject, computed } from "vue";
import axios from "axios";
import { useHandleError } from "@/Composables/General/useHandleError.js";
import { useShowToast } from "@/Composables/General/useShowToast.js";
import { toast } from "vue3-toastify";
import _, { set } from "lodash";

export const useFiniquitos = (context) => {
    const swal = inject("$swal");
    const isLoadingRequest = ref(false);
    const errors = ref([]);

    const getInfoForModalFiniquitos = async (settlementId) => {
        try {
            isLoadingRequest.value = true;
            const response = await axios.get(
                `/get-info-modal-finiquitos/${settlementId}`
            );
            //console.log(response);
        } catch (err) {
            if (err.response.data.logical_error) {
                useShowToast(
                    toast.error,
                    err.response.data.logical_error
                );
                context.emit("get-table");
            }else{
                showErrorMessage(err);
            }
            context.emit("cerrar-modal")
        } finally {
            isLoadingRequest.value = false;
        }
    };

    const showErrorMessage = (err) => {
        const { title, text, icon } = useHandleError(err);
        swal({ title: title, text: text, icon: icon, timer: 5000 });
    };

    return {
        isLoadingRequest,
        getInfoForModalFiniquitos
    }

}