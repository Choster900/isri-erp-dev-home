import { ref, inject, computed } from "vue";
import axios from "axios";
import { useHandleError } from "@/Composables/General/useHandleError.js";
import { useShowToast } from "@/Composables/General/useShowToast.js";
import { toast } from "vue3-toastify";
import _ from "lodash";
import moment from 'moment';
export const useConceptoIngreso = (context) => {
    const swal = inject("$swal");
    const isLoadingRequest = ref(false);
    const errors = ref([]);
    const budget_accounts = ref([])
    const dependencies = ref([])
    const financing_sources = ref([])
    const income_concept = ref(
        {
            income_concept_id: '',
            dependency_id: '',
            budget_account_id: '',
            financing_source_id: '',
            name: '',
            detail: ''
        }
    )

    const getInfoForModalConceptosIngreso = async (id) => {
        try {
            isLoadingRequest.value = true;
            const response = await axios.get(
                `/get-info-modal-concepto-ingreso/${id}`
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
        dependencies.value = data.dependencies
        financing_sources.value = data.financing_sources
        budget_accounts.value = data.budget_accounts

        income_concept.value.income_concept_id = data.concept.id_concepto_ingreso ?? ""
        income_concept.value.dependency_id = data.concept.id_centro_atencion ?? ""
        income_concept.value.budget_account_id = data.concept.id_ccta_presupuestal ?? ""
        income_concept.value.financing_source_id = data.concept.id_proy_financiado ?? ""
        income_concept.value.name = data.concept.nombre_concepto_ingreso ?? ""
        income_concept.value.detail = data.concept.detalle_concepto_ingreso ?? ""
    };

    const storeConceptoIngreso = async (concept) => {
        swal({
            title: '¿Está seguro de guardar el nuevo concepto de ingreso?',
            icon: 'question',
            iconHtml: '❓',
            confirmButtonText: 'Si, Guardar',
            confirmButtonColor: '#141368',
            cancelButtonText: 'Cancelar',
            showCancelButton: true,
            showCloseButton: true
        }).then(async (result) => {
            if (result.isConfirmed) {
                saveConceptoIngreso(concept, '/save-income-concept');
            }
        });
    };

    const updateConceptoIngreso = async (concept) => {
        swal({
            title: '¿Está seguro de actualizar el concepto de ingreso?',
            icon: 'question',
            iconHtml: '❓',
            confirmButtonText: 'Si, Actualizar',
            confirmButtonColor: '#141368',
            cancelButtonText: 'Cancelar',
            showCancelButton: true,
            showCloseButton: true
        }).then(async (result) => {
            if (result.isConfirmed) {
                saveConceptoIngreso(concept, '/update-income-concept');
            }
        });
    };

    const saveConceptoIngreso = async (concept, url) => {
        isLoadingRequest.value = true;
        await axios
            .post(url, concept)
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
                useShowToast(
                    toast.warning,
                    "Tienes algunos errores, por favor verifica los datos enviados."
                );
                errors.value = err.response.data.errors;
            }
        } else {
            showErrorMessage(err);
            context.emit("cerrar-modal")
        }
    };

    const handleSuccessResponse = (response) => {
        useShowToast(
            toast.success,
            response.data.message
        );
        context.emit("cerrar-modal")
        context.emit("get-table")
    }

    const showErrorMessage = (err) => {
        const { title, text, icon } = useHandleError(err);
        swal({ title: title, text: text, icon: icon, timer: 5000 });
    };

    return {
        isLoadingRequest, errors, income_concept, financing_sources,
        dependencies, budget_accounts,
        getInfoForModalConceptosIngreso, storeConceptoIngreso, updateConceptoIngreso
    }
}





