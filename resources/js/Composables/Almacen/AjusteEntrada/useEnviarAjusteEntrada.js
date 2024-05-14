import { ref, inject } from "vue";
import axios from "axios";
import { useHandleError } from "@/Composables/General/useHandleError.js";
import { useShowToast } from "@/Composables/General/useShowToast.js";
import { toast } from "vue3-toastify";

export const useEnviarAjusteEntrada = (context, getDataToShow, tableData) => {
    const swal = inject("$swal");
    const isLoadingTop = ref(false)

    const changeStatusElement = async (elementId, status) => {
        swal({
            title: "¿Está seguro de Eliminar este ajuste?",
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
                    const response = await axios.post('/change-status-shortage-adjustment', {
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

    const sendShortageAdjustment = async (id) => {
        swal({
            title: "¿Está seguro de enviar este ajuste al kardex? No podrás revertir esta acción.",
            icon: "question",
            iconHtml: "❓",
            confirmButtonText: "Si, Enviar",
            confirmButtonColor: "#001b47",
            cancelButtonText: "Cancelar",
            showCancelButton: true,
            showCloseButton: true
        }).then(async (result) => {
            if (result.isConfirmed) {
                isLoadingTop.value = true;
                try {
                    const response = await axios.post('/send-shortage-adjustment', {
                        id: id,
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

    const showErrorMessage = (err) => {
        const { title, text, icon } = useHandleError(err);
        swal({ title: title, text: text, icon: icon, timer: 5000 });
    };

    return {
        isLoadingTop,
        changeStatusElement, sendShortageAdjustment
    }
}