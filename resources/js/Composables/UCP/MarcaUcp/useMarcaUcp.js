import { ref, inject, computed } from "vue";
import axios from "axios";
import { useHandleError } from "@/Composables/General/useHandleError.js";
import { useShowToast } from "@/Composables/General/useShowToast.js";
import { toast } from "vue3-toastify";
import _ from "lodash";

export const useMarcaUcp = (context) => {
    //General parameters
    const swal = inject("$swal");
    const isLoadingRequest = ref(false);
    const showTooltipCAT =  ref(false)
    const showTooltipUAT =  ref(false)
    const errors = ref([]);

    //Product info
    const objDB = ref({
        id_marca: '',
        nombre_marca: '',
        fecha_reg_marca: '',
        fecha_mod_marca: '',
        estado_marca: ''
    })

    const getInfoForModalBrandUcp = async (id) => {
        try {
            isLoadingRequest.value = true;
            const response = await axios.get(
                `/get-info-modal-brand-ucp/${id}`
            );
            id > 0 ? setModalValues(response.data.brand) : ''
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

    const setModalValues = (product) => {
        objDB.value = product
    }

    const storeObject = async (obj) => {
        swal({
            title: '¿Está seguro de guardar la nueva marca?',
            icon: 'question',
            iconHtml: '❓',
            confirmButtonText: 'Si, Guardar',
            confirmButtonColor: '#141368',
            cancelButtonText: 'Cancelar',
            showCancelButton: true,
            showCloseButton: true
        }).then(async (result) => {
            if (result.isConfirmed) {
                saveObject(obj, '/save-brand-ucp');
            }
        });
    };

    const updateObject = async (obj) => {
        swal({
            title: '¿Está seguro de actualizar marca?',
            icon: 'question',
            iconHtml: '❓',
            confirmButtonText: 'Si, Actualizar',
            confirmButtonColor: '#141368',
            cancelButtonText: 'Cancelar',
            showCancelButton: true,
            showCloseButton: true
        }).then(async (result) => {
            if (result.isConfirmed) {
                saveObject(obj, '/update-brand-ucp');
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
        errors, isLoadingRequest, objDB, showTooltipCAT, showTooltipUAT,
        getInfoForModalBrandUcp, storeObject, updateObject
    }
}