import { ref, inject, computed } from "vue";
import axios from "axios";
import { useHandleError } from "@/Composables/General/useHandleError.js";
import { useShowToast } from "@/Composables/General/useShowToast.js";
import { toast } from "vue3-toastify";

export const useRecepcion = (context) => {
    const swal = inject("$swal");
    const isLoadingRequest = ref(false);
    const errors = ref([]);
    const purchaseProcedures = ref([])
    const catUnspsc = ref([])
    const budgetAccounts = ref([])
    const unitsMeasmt = ref([])

    const docSelected = ref('')
    const reception = ref([])
    const products = ref([])
    const documents = ref([])

    // const reception = ref({
    //     id: '',
    //     name: '',
    //     description: '',
    //     mUnitId: '',
    //     price: '',
    //     budgetAccountId: '',
    //     purchaseProcedureId: '',
    //     unspscId: '',
    //     perishable: -1,
    //     gAndS:-1
    // })

    const getInfoForModalRecep = async (id) => {
        try {
            isLoadingRequest.value = true;
            if (id > 0) {
                const response = await axios.get(
                    `/get-info-modal-recep/${id}`
                );
                reception.value = response.data.recep
                products.value = response.data.products
            } else {
                const response = await axios.get(
                    `/get-initial-doc-info`
                );
                documents.value = response.data.documents
            }
            //id > 0 ? setModalValues(response.data.prod) : ''
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
        prod.value.price = '$' + product.precio_producto
        prod.value.budgetAccountId = product.id_ccta_presupuestal
        prod.value.purchaseProcedureId = product.id_proceso_compra
        prod.value.unspscId = product.id_catalogo_unspsc
        prod.value.perishable = product.perecedero_producto
        prod.value.gAndS = product.basico_producto
    }

    const ordenC = computed(() => {
        const result = documents.value.filter((element) => {
            return element.id_tipo_doc_adquisicion == 2
        });
        return result ?? [];
    });

    const contrato = computed(() => {
        const result = documents.value.filter((element) => {
            return element.id_tipo_doc_adquisicion == 1
        });
        return result ?? [];
    });

    const filteredDoc = computed(() => {
        const result = documents.value.filter((element) => {
            return element.id_tipo_doc_adquisicion == docSelected.value
        });
        return result ?? [];
    });

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
        errors, isLoadingRequest, reception, purchaseProcedures, catUnspsc,
        budgetAccounts, unitsMeasmt, products, documents, ordenC, contrato, docSelected,
        filteredDoc,
        getInfoForModalRecep
    }
}