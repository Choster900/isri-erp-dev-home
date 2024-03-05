import { ref, inject } from "vue";
import axios from "axios";
import { useHandleError } from "@/Composables/General/useHandleError.js";
import { useShowToast } from "@/Composables/General/useShowToast.js";
import { toast } from "vue3-toastify";
import { localeData } from 'moment_spanish_locale';
import moment from 'moment';
moment.locale('es', localeData)

export const useEnviarKardex = (context) => {
    const swal = inject("$swal");
    const isLoadingRequest = ref(false);
    const errors = ref([]);

    const recInfo = ref({})
    const empOptions = ref([])
    const infoToSend = ref({
        id: '',
        conctManagerId: '',
        nonCompliant: -1,
        observation: '',
        suppRep: ''
    })

    const getInfoForModalSendKardex = async (id) => {
        try {
            isLoadingRequest.value = true;
            const response = await axios.get(
                `/get-info-modal-send-kardex/${id}`
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

    const sendReception = async (obj) => {
        swal({
            title: '¿Está seguro de registrar la recepcion en el kardex?, ten en cuenta que no podrás revertir los cambios.',
            icon: 'question',
            iconHtml: '❓',
            confirmButtonText: 'Si, Registrar',
            confirmButtonColor: '#141368',
            cancelButtonText: 'Cancelar',
            showCancelButton: true,
            showCloseButton: true
        }).then(async (result) => {
            if (result.isConfirmed) {
                saveObject(obj, '/send-goods-reception');
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
        if (err.response.status === 422) {
            if (err.response.data.logical_error) {
                useShowToast(toast.error, err.response.data.logical_error);
            } else {
                useShowToast(toast.warning, "Tienes algunos errores, por favor verifica los datos enviados.");
                errors.value = err.response.data.errors;
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
        isLoadingRequest, recInfo, errors, empOptions, infoToSend,
        getInfoForModalSendKardex, sendReception
    }
}