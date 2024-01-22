import { ref, inject, computed } from "vue";
import axios from "axios";
import { useHandleError } from "@/Composables/General/useHandleError.js";
import { useShowToast } from "@/Composables/General/useShowToast.js";
import { toast } from "vue3-toastify";
import moment from 'moment';

export const useFiniquitoEmp = (context) => {
    const swal = inject("$swal");
    const isLoadingRequest = ref(false);
    const errors = ref([]);

    const finiquitoEmp = ref({
        id: '',
        empleado: '',
        codigo: '',
        signatureTime: '',
        signatureDate: '',
        amount: ''
    })

    const getInfoForModalFiniquitoEmp = async (id) => {
        try {
            isLoadingRequest.value = true;
            const response = await axios.get(
                `/get-info-modal-finiquito-emp/${id}`
            );
            setModalValues(response.data)
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

    const setModalValues = (data) => {
        finiquitoEmp.value.id = data.finiquitoEmp.id_finiquito_laboral
        finiquitoEmp.value.signatureTime = data.finiquitoEmp.hora_firma_finiquito_laboral
        finiquitoEmp.value.signatureDate = data.finiquitoEmp.fecha_firma_finiquito_laboral
        finiquitoEmp.value.amount = data.finiquitoEmp.monto_finiquito_laboral
        finiquitoEmp.value.empleado = data.finiquitoEmp.empleado.persona.nombre_apellido
        finiquitoEmp.value.codigo = data.finiquitoEmp.empleado.codigo_empleado
    };

    const updateFiniquitoEmp = async (doc) => {
        swal({
            title: '¿Está seguro de actualizar la informacion del finiquito?',
            icon: 'question',
            iconHtml: '❓',
            confirmButtonText: 'Si, Actualizar',
            confirmButtonColor: '#141368',
            cancelButtonText: 'Cancelar',
            showCancelButton: true,
            showCloseButton: true
        }).then(async (result) => {
            if (result.isConfirmed) {
                saveObject(doc, '/update-finiquito');
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
        errors, isLoadingRequest, finiquitoEmp,
        updateFiniquitoEmp, getInfoForModalFiniquitoEmp
    }
}