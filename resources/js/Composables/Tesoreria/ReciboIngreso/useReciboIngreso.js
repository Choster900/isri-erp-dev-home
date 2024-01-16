import { ref, inject, computed } from "vue";
import axios from "axios";
import { useHandleError } from "@/Composables/General/useHandleError.js";
import { useShowToast } from "@/Composables/General/useShowToast.js";
import { toast } from "vue3-toastify";
import _ from "lodash";

export const useReciboIngreso = (context) => {
    const swal = inject("$swal");
    const isLoadingRequest = ref(false);
    const isLoadingFinancingSource = ref(false)
    const isLoadingIncomeConcept = ref(false)
    const currentPage = ref(1)
    const errors = ref([]);
    const backend_errors = ref([]);
    const budget_accounts = ref([])
    const income_concept_select = ref([])
    const filteredOptions = ref([])
    const financing_sources = ref([])
    const treasury_clerk = ref([])

    const income_receipt = ref(
        {
            income_receipt_id: '',
            financing_source_id: '',
            number: '',
            treasury_clerk_id: '',
            total: '',
            budget_account_id: '',
            direction: '',
            document: '',
            client: '',
            description: '',
            income_detail: [],
        }
    )

    const getInfoForModalReciboIngreso = async (id) => {
        try {
            isLoadingRequest.value = true;
            const response = await axios.get(
                `/get-info-modal-recibo-ingreso/${id}`
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
        budget_accounts.value = data.budget_accounts
        financing_sources.value = data.financing_sources
        income_concept_select.value = data.income_concept_select
        treasury_clerk.value = data.treasury_clerk
        filteredOptions.value = income_concept_select.value

        income_receipt.value.income_receipt_id = data.receipt.id_recibo_ingreso ?? ""
        income_receipt.value.financing_source_id = data.receipt.id_proy_financiado ?? ""
        income_receipt.value.number = data.receipt.numero_recibo_ingreso ?? ""
        income_receipt.value.treasury_clerk_id = data.receipt.id_empleado_tesoreria ?? ""
        income_receipt.value.total = data.receipt.monto_recibo_ingreso ?? ""
        income_receipt.value.budget_account_id = data.receipt.id_ccta_presupuestal ?? ""
        income_receipt.value.direction = data.receipt.direccion_cliente_recibo_ingreso ?? ""
        income_receipt.value.document = data.receipt.doc_identidad_recibo_ingreso ?? ""
        income_receipt.value.client = data.receipt.cliente_recibo_ingreso ?? ""
        income_receipt.value.description = data.receipt.descripcion_recibo_ingreso ?? ""
        if (data.receipt.detalles) {
            data.receipt.detalles.forEach((value, index) => {
                var arrayInsert = {
                    nombre_concepto: value.concepto_ingreso.nombre_concepto_ingreso,
                    detail_id: value.id_det_recibo_ingreso,
                    income_concept_id: value.id_concepto_ingreso,
                    amount: value.monto_det_recibo_ingreso,
                    deleted: value.estado_det_recibo_ingreso == 1 ? false : true,
                };
                income_receipt.value.income_detail.push(arrayInsert)
            })
        } else {
            income_receipt.value.income_detail.push({ detail_id: '', income_concept_id: '', amount: '', deleted: false });
        }
    };

    const addRow = () => {
        income_receipt.value.income_detail.push({ detail_id: '', income_concept_id: '', amount: '', deleted: false })
    }

    const deleteRow = (index, detail_id) => {
        if (active_details.value.length <= 1) {
            useShowToast(toast.warning, "No puedes eliminar todos los detalles.");
        } else {
            swal({
                title: 'Eliminar concepto de ingreso.',
                text: "¿Estas seguro?",
                icon: 'warning',
                showCancelButton: true,
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#DC2626',
                cancelButtonColor: '#4B5563',
                confirmButtonText: 'Si, Eliminar.'
            }).then((result) => {
                if (result.isConfirmed) {
                    if (detail_id === "") {
                        income_receipt.value.income_detail.splice(index, 1);
                    } else {
                        income_receipt.value.income_detail[index].deleted = true
                    }
                }
            });
        }
    }

    const openOption = (option) => {
        const newOptions = []
        income_concept_select.value.map((element) => {
            const isSelected = income_receipt.value.income_detail.some((e) => e.income_concept_id === element.value && e.deleted === false);
            const isClicked = element.value === option
            if (!isSelected || isClicked) {
                newOptions.push(element)
            }
        });
        filteredOptions.value = newOptions
    }

    const active_details = computed(() => {
        return income_receipt.value.income_detail.filter((detail) => detail.deleted == false)
    });

    const calculateTotal = computed(() => {
        let sum = 0
        for (let i = 0; i < income_receipt.value.income_detail.length; i++) {
            if (income_receipt.value.income_detail[i].deleted == false) {
                let amount = parseFloat(income_receipt.value.income_detail[i].amount);
                if (!isNaN(amount)) {
                    sum += amount;
                }
            }
        }
        income_receipt.value.total = sum.toFixed(2);
        return sum.toFixed(2);
    })

    const getFinanceSource = async (id) => {
        financing_sources.value = []
        income_concept_select.value = []
        income_receipt.value.budget_account_id = ""
        income_receipt.value.financing_source_id = ""
        income_receipt.value.income_detail.forEach((element) => {
            if (element.deleted === false) {
                element.income_concept_id = ''
            }
        })

        try {
            isLoadingFinancingSource.value = true;
            const response = await axios.get("/get-select-financing-source", { params: { budget_account_id: id } })
            financing_sources.value = response.data.financing_sources
        } catch (err) {
            showErrorMessage(err);
            financing_sources.value = []
        } finally {
            isLoadingFinancingSource.value = false;
        }

    }

    const getIncomeConcept = async (sourceId, budgetId) => {
        if (sourceId && budgetId) {
            try {
                isLoadingIncomeConcept.value = true;
                const response = await axios.get("/get-select-income-concept",
                    { params: { financing_source_id: sourceId, budget_account_id: budgetId } })
                income_concept_select.value = response.data.income_concept_select
            } catch (err) {
                showErrorMessage(err);
                income_concept_select.value = []
            } finally {
                isLoadingIncomeConcept.value = false;
            }
        } else {
            income_concept_select.value = []
            income_receipt.value.financing_source_id = ""
            income_receipt.value.income_detail.forEach((element) => {
                if (element.deleted === false) {
                    element.income_concept_id = ''
                }
            })
        }
    }

    const storeReciboIngreso = async (obj) => {
        swal({
            title: '¿Está seguro de guardar el nuevo recibo de ingreso?',
            icon: 'question',
            iconHtml: '❓',
            confirmButtonText: 'Si, Guardar',
            confirmButtonColor: '#141368',
            cancelButtonText: 'Cancelar',
            showCancelButton: true,
            showCloseButton: true
        }).then(async (result) => {
            if (result.isConfirmed) {
                saveObject(obj, '/save-income-receipt');
            }
        });
    };

    const updateReciboIngreso = async (obj) => {
        swal({
            title: '¿Está seguro de actualizar el recibo de ingreso?',
            icon: 'question',
            iconHtml: '❓',
            confirmButtonText: 'Si, Actualizar',
            confirmButtonColor: '#141368',
            cancelButtonText: 'Cancelar',
            showCancelButton: true,
            showCloseButton: true
        }).then(async (result) => {
            if (result.isConfirmed) {
                saveObject(obj, '/update-income-receipt');
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
        isLoadingRequest, isLoadingIncomeConcept, isLoadingFinancingSource,
        errors, backend_errors,
        income_receipt,
        currentPage,
        budget_accounts, income_concept_select, financing_sources, treasury_clerk,
        active_details, calculateTotal, filteredOptions,//computed
        getInfoForModalReciboIngreso, storeReciboIngreso, updateReciboIngreso, addRow, deleteRow, openOption,
        getFinanceSource, getIncomeConcept,
    }
}





