import { ref, inject, computed } from "vue";
import axios from "axios";
import { useHandleError } from "@/Composables/General/useHandleError.js";
import { useShowToast } from "@/Composables/General/useShowToast.js";
import { toast } from "vue3-toastify";
import { useValidateInput } from '@/Composables/General/useValidateInput';
import { useFormatDateTime } from "@/Composables/General/useFormatDateTime.js";

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
    const filteredProds = ref([])
    const documents = ref([])
    const items = ref([])
    const startRec = ref(false)
    const infoToShow = ref({
        docId: '',
        detDocId: '',
        docName: '',
    })

    const recDocument = ref({
        id: '',
        acta: '',
        invoice:'',
        prods: [],
    })

    const {
        formatDateVue3DP, formatTimeVue3DP
    } = useFormatDateTime()

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
        if (infoToShow.value.detDocId === '' && id <= 0) {
            useShowToast(toast.error, "Error, no se puede procesar la petición por información incompleta.");
        } else {
            try {
                isLoadingRequest.value = true;
                const response = await axios.post(
                    `/get-info-modal-recep`, {
                    id: id,
                    detId: infoToShow.value.detDocId
                });
                setModalValues(response.data, id)
            } catch (err) {
                console.log(err);
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
        const recepData = data.recep
        infoToShow.value.docName = data.itemInfo.documento_adquisicion.numero_doc_adquisicion
        // Check if id > 0
        if (id > 0) {
            recDocument.value.id = recepData.id_recepcion_pedido
            recDocument.value.acta = recepData.acta_recepcion_pedido
            // Filter products based on conditions
            const newOptions = data.products.filter(element => {
                const rightOpt = recepData.detalle_recepcion.some(e => e.id_prod_adquisicion === element.value && e.estado_prod_adquisicion === 1);
                return rightOpt || element.total_menos_acumulado != 0;
            });

            // Set products and filteredProds to newOptions
            products.value = filteredProds.value = newOptions;

            // Iterate over detalle_recepcion
            recepData.detalle_recepcion.forEach(element => {
                // Check estado_det_recepcion_pedido
                if (element.estado_det_recepcion_pedido === 1) {
                    const paId = element.producto_adquisicion.id_prod_adquisicion;
                    const cantRecep = element.cant_det_recepcion_pedido;
                    const detRecepId = element.id_det_recepcion_pedido;

                    // Construct array
                    const array = {
                        detRecId: detRecepId,
                        prodId: paId,
                        desc: "",
                        expiryDate: formatDateVue3DP(element.fecha_vencimiento_det_recepcion_pedido),
                        perishable: "",
                        avails: "",
                        qty: cantRecep,
                        cost: "",
                        total: "",
                        deleted: false
                    };

                    // Push array to prods
                    recDocument.value.prods.push(array);

                    // Get the index of the last item in the array
                    const lastIndex = recDocument.value.prods.length - 1;

                    // Call setProdItem and updateItemTotal
                    setProdItem(lastIndex, paId, detRecepId);
                    updateItemTotal(lastIndex, cantRecep, paId);
                }
            });
        } else {
            // Filter products based on condition
            const newOptions = data.products.filter(element => element.total_menos_acumulado != 0);

            // Set products and filteredProds to newOptions
            products.value = filteredProds.value = newOptions;

            // Call addNewRow
            addNewRow();
        }

        startRec.value = true
    }

    const setProdItem = (index, paId, recepId) => {
        if (paId) {
            const selectedProd = products.value.find((element) => {
                return element.value === paId; // Adding a return statement here
            });
            //console.log(selectedProd.descripcion_producto);
            recDocument.value.prods[index].desc = 
            selectedProd.nombre_producto + ' -- '+
            selectedProd.abreviatura_unidad_medida+ ' -- '+
            selectedProd.descripcion_prod_adquisicion

            recDocument.value.prods[index].perishable = recepId ? recDocument.value.prods[index].perishable : ''
            recDocument.value.prods[index].expiryDate = recepId ? recDocument.value.prods[index].expiryDate : ''
            recDocument.value.prods[index].avails = selectedProd.total_menos_acumulado
            recDocument.value.prods[index].perishable = selectedProd.perecedero_producto
            recDocument.value.prods[index].cost = selectedProd.costo_prod_adquisicion
            recDocument.value.prods[index].qty = recepId ? recDocument.value.prods[index].qty : ''
            recDocument.value.prods[index].total = '0.00'
        } else {
            recDocument.value.prods[index].desc = ""
            recDocument.value.prods[index].perishable = ""
            recDocument.value.prods[index].expiryDate = ""
            recDocument.value.prods[index].avails = ""
            recDocument.value.prods[index].qty = ""
            recDocument.value.prods[index].cost = ""
            recDocument.value.prods[index].total = '0.00'
        }
    }

    const {
        validateInput
    } = useValidateInput()

    const handleValidation = (input, validation, element) => {
        if (element) {
            recDocument.value.prods[element.index][input] = validateInput(recDocument.value.prods[element.index][input], validation)
            updateItemTotal(element.index, recDocument.value.prods[element.index][input], recDocument.value.prods[element.index].prodId)
        } else {
            recDocument.value.prods[input] = validateInput(recDocument.value.prods[input], validation)
        }
    }

    const updateItemTotal = (index, qty, paId) => {
        if (paId != '') {
            const selectedProd = products.value.find((element) => {
                return element.value === paId; // Adding a return statement here
            });
            recDocument.value.prods[index].avails = qty === '' ?
                selectedProd.total_menos_acumulado : selectedProd.total_menos_acumulado - qty
            recDocument.value.prods[index].total = (selectedProd.costo_prod_adquisicion * qty).toFixed(2)
        } else {
            recDocument.value.prods[index].total = ''
        }
    }

    const openOption = (option) => {
        const newOptions = []
        products.value.map((element) => {
            const isSelected = recDocument.value.prods.some((e) => e.prodId === element.value && e.deleted === false);
            const isClicked = element.value === option
            if ((!isSelected || isClicked) && element.total_menos_acumulado != 0) {
                newOptions.push(element)
            }
        });
        filteredProds.value = newOptions
    }

    const totalRec = computed(() => {
        let sum = 0
        for (let i = 0; i < recDocument.value.prods.length; i++) {
            if (recDocument.value.prods[i].deleted == false) {
                let amount = parseFloat(recDocument.value.prods[i].total);
                if (!isNaN(amount)) {
                    sum += amount;
                }
            }
        }
        //recDocument.value.total = sum.toFixed(2);
        return sum.toFixed(2);
    });

    const addNewRow = () => {
        if (activeDetails.value.length < products.value.length) {
            let array = {
                detRecId: "",
                prodId: "",
                desc: "",
                expiryDate: '',
                perishable: "",
                avails: "",
                qty: "",
                cost: "",
                total: '0.00',
                deleted: false
            }
            recDocument.value.prods.push(array);
        } else {
            useShowToast(toast.warning, "Has alcanzado el maximo de filas disponibles.");
        }
    }

    const deleteRow = (index, detRecId) => {
        if (activeDetails.value.length <= 1) {
            useShowToast(toast.warning, "No puedes eliminar todos las filas.");
        } else {
            swal({
                title: 'Eliminar producto.',
                text: "¿Estas seguro de eliminar temporalmente el producto? Los cambios surtiran efecto al actualizar la recepcion.",
                icon: 'warning',
                showCancelButton: true,
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#DC2626',
                cancelButtonColor: '#4B5563',
                confirmButtonText: 'Si, Eliminar.'
            }).then((result) => {
                if (result.isConfirmed) {
                    useShowToast(toast.success, "Producto eliminado temporalmente.");
                    if (detRecId === "") {
                        recDocument.value.prods.splice(index, 1);
                    } else {
                        recDocument.value.prods[index].deleted = true
                    }
                }
            });
        }
    }

    const activeDetails = computed(() => {
        return recDocument.value.prods.filter((detail) => detail.deleted == false)
    });

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
            return element.id_doc_adquisicion == infoToShow.value.docId
        });
        return result ?? [];
    });

    const storeReception = async (obj) => {
        swal({
            title: '¿Está seguro de guardar nueva recepción de productos?',
            icon: 'question',
            iconHtml: '❓',
            confirmButtonText: 'Si, Guardar',
            confirmButtonColor: '#141368',
            cancelButtonText: 'Cancelar',
            showCancelButton: true,
            showCloseButton: true
        }).then(async (result) => {
            if (result.isConfirmed) {
                saveObject(obj, '/save-goods-reception');
            }
        });
    };

    const updateReception = async (obj) => {
        swal({
            title: '¿Está seguro de actualizar la recepción de productos?',
            icon: 'question',
            iconHtml: '❓',
            confirmButtonText: 'Si, Actualizar',
            confirmButtonColor: '#141368',
            cancelButtonText: 'Cancelar',
            showCancelButton: true,
            showCloseButton: true
        }).then(async (result) => {
            if (result.isConfirmed) {
                saveObject(obj, '/update-goods-reception');
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
                useShowToast(toast.warning, "Información incompleta, por favor llena todos los campos requeridos.");
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
        budgetAccounts, unitsMeasmt, documents, ordenC, contrato, docSelected,
        filteredDoc, filteredItems, recDocument, startRec, filteredProds, totalRec,
        infoToShow,
        getInfoForModalRecep, startReception, setProdItem, updateItemTotal, addNewRow,
        openOption, deleteRow, handleValidation, storeReception, updateReception
    }
}