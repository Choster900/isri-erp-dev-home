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
    const items = ref([])
    const startRec = ref(false)

    const recDocument = ref({
        id: '',
        docId: '',
        detDocId: '',
        prods: []
    })

    const getInfoForModalRecep = async (id) => {
        if (id > 0) {
            await startReception(id)
        } else {
            try {
                isLoadingRequest.value = true;
                const response = await axios.get(
                    `/get-initial-doc-info`
                );
                documents.value = response.data.documents
                items.value = response.data.items
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
        }
    };

    const startReception = async (id) => {
        if (recDocument.value.detDocId === '' && id <= 0) {
            useShowToast(toast.error, "Error, no se puede procesar la petición por información incompleta.");
        } else {
            try {
                isLoadingRequest.value = true;
                const response = await axios.post(
                    `/get-info-modal-recep`, {
                    id: id,
                    detId: recDocument.value.detDocId
                });
                setModalValues(response.data, id)
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
        }
    }

    const setModalValues = (data, id) => {
        //reception.value = response.data.recep
        products.value = data.products


        if (id > 0) {
            let array = {
                value: product.catalogo_unspsc.id_catalogo_unspsc,
                label: product.catalogo_unspsc.nombre_catalogo_unspsc,
            };
            recDocument.value.prods.push(array);
        } else {
            let array = {
                prodId: "",
                desc: "",
                unit: "",
                avails: "",
                qty: "",
                cost: "",
                total: ""
            }
            recDocument.value.prods.push(array);
        }

        startRec.value = true
    }

    const setProdItem = (index, paId) => {
        if(paId){
            const selectedProd = products.value.find((element) => {
                return element.value === paId; // Adding a return statement here
            });
            //console.log(selectedProd.descripcion_producto);
            recDocument.value.prods[index].desc = selectedProd.descripcion_prod_adquisicion
            recDocument.value.prods[index].unit = selectedProd.abreviatura_unidad_medida
            recDocument.value.prods[index].avails = selectedProd.diferencia
            recDocument.value.prods[index].cost = selectedProd.costo_prod_adquisicion
        }else{
            recDocument.value.prods[index].desc = ""
            recDocument.value.prods[index].unit = ""
            recDocument.value.prods[index].avails = ""
            recDocument.value.prods[index].cost = ""
            recDocument.value.prods[index].total = ""
        }   
    }

    const updateItemTotal = (index,qty,paId) => {
        if(paId != ''){
            const selectedProd = products.value.find((element) => {
                return element.value === paId; // Adding a return statement here
            });
            recDocument.value.prods[index].total = (selectedProd.costo_prod_adquisicion * qty).toFixed(2)
        }else{
            recDocument.value.prods[index].total = ''
        }
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

    const filteredItems = computed(() => {
        const result = items.value.filter((element) => {
            return element.id_doc_adquisicion == recDocument.value.docId
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
        filteredDoc, filteredItems, recDocument, startRec,
        getInfoForModalRecep, startReception, setProdItem, updateItemTotal
    }
}