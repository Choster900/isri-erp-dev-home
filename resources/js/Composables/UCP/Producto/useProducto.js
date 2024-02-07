import { ref, inject, computed } from "vue";
import axios from "axios";
import { useHandleError } from "@/Composables/General/useHandleError.js";
import { useShowToast } from "@/Composables/General/useShowToast.js";
import { toast } from "vue3-toastify";
import _ from "lodash";

export const useProducto = (context) => {
    const swal = inject("$swal");
    const isLoadingRequest = ref(false);
    const isLoadingUnspsc = ref(false)
    const errors = ref([]);
    const purchaseProcedures = ref([])
    const catUnspsc = ref([])
    const budgetAccounts = ref([])

    const prod = ref({
        id: '',
        name: '',
        description: '',
        mUnitId: '',
        price: '',
        budgetAccountId: '',
        purchaseProcedureId: '',
        unspscId: '',
    })

    const getInfoForModalProd = async (id) => {
        try {
            isLoadingRequest.value = true;
            const response = await axios.get(
                `/get-info-modal-prod/${id}`
            );
            purchaseProcedures.value = response.data.purchaseProcedures
            budgetAccounts.value = response.data.budgetAccounts
            setModalValues(response.data.prod)
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

    const asyncFindUnspsc = _.debounce(async (query) => {
        try {
            isLoadingUnspsc.value = true;
            if (query.length > 3) {
                const response = await axios.post("/search-unspsc", {
                    busqueda: query,
                });
                catUnspsc.value = response.data.catUnspsc;
            } else {
                catUnspsc.value = [];
            }
        } catch (errors) {
            catUnspsc.value = [];
        } finally {
            isLoadingUnspsc.value = false;
        }
    }, 350);


    const setModalValues = (product) => {
        if (product.catalogo_unspsc) {
            let array = {
                value: product.catalogo_unspsc.id_catalogo_unspsc,
                label: product.catalogo_unspsc.nombre_catalogo_unspsc,
            };
            catUnspsc.value.push(array);
        }

        prod.value.id = product.id_producto
        prod.value.name = product.nombre_producto
        prod.value.description = product.descripcion_producto
        prod.value.mUnitId = product.unidad_medida.id_unidad_medida
        prod.value.price = product.precio_producto
        prod.value.budgetAccountId = product.id_ccta_presupuestal
        prod.value.purchaseProcedureId = product.id_proceso_compra
        prod.value.unspscId = product.id_catalogo_unspsc

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
        errors, isLoadingRequest, prod, purchaseProcedures, catUnspsc, isLoadingUnspsc,
        budgetAccounts,
        asyncFindUnspsc, getInfoForModalProd
    }
}