import { ref, inject, computed, nextTick, watch } from "vue";
import axios from "axios";
import { useHandleError } from "@/Composables/General/useHandleError.js";
import { useShowToast } from "@/Composables/General/useShowToast.js";
import { toast } from "vue3-toastify";
import { useValidateInput } from '@/Composables/General/useValidateInput';
import { useToCalculate } from '@/Composables/General/useToCalculate.js';
import _ from "lodash";

export const useTransferencia = (context) => {
    const swal = inject("$swal");
    const isLoadingRequest = ref(false);
    const isLoadingProds = ref(false);
    const errors = ref([]);
    const frontErrors = ref(
        {
            centerId: [],
            financingSourceId: [],
            idLt: []
        }
    )
    const products = ref([])
    const filteredOptions = ref([])
    const load = ref(0)

    const reasons = ref([])
    const centers = ref([])
    const financingSources = ref([])
    const lts = ref([])

    const adjustment = ref({
        id: '',
        centerId: '',
        destCenterId: '',
        financingSourceId: '',
        reasonId: '',
        idLt: '',
        number: '',
        observation: '',
        status: '',
        prods: []
    })

    const { round2Decimals } = useToCalculate();

    const getInfoForModalTransfers = async (id) => {
        try {
            isLoadingRequest.value = true;
            const response = await axios.post(
                `/get-info-modal-warehouse-transfer`, {
                id: id,
            });
            setModalValues(response.data, id)
        } catch (err) {
            console.log(err);
            if (err.response && err.response.data.logical_error) {
                useShowToast(toast.error, err.response.data.logical_error);
                context.emit("get-table");
            } else {
                showErrorMessage(err);
                context.emit("cerrar-modal");
            }
        } finally {
            isLoadingRequest.value = false;
        }
    };

    const setModalValues = (data, id) => {
        const req = data.req
        //Set the general information to show in the view
        reasons.value = data.reasons
        centers.value = data.centers
        financingSources.value = data.financingSources
        lts.value = data.lts

        adjustment.value.status = id > 0 ? req.id_estado_req : 1

        // Check if id > 0
        if (id > 0) {
            adjustment.value.id = req.id_requerimiento //Set reception id
            adjustment.value.centerId = req.id_centro_atencion //Set acta number
            adjustment.value.financingSourceId = req.id_proy_financiado //Set invoice number
            adjustment.value.reasonId = req.id_motivo_ajuste //Set observation
            adjustment.value.idLt = req.id_lt // Set supplier
            adjustment.value.number = req.num_requerimiento
            adjustment.value.observation = req.observacion_requerimiento
            adjustment.value.destCenterId = req.cen_id_centro_atencion

            load.value++
            products.value = data.products
            filteredOptions.value = data.products

            // Iterate over detalle_requerimiento
            req.detalles_requerimiento.forEach(element => {
                // Check estado_det_recepcion_pedido
                if (element.estado_det_requerimiento === 1) {

                    const selectedProd = products.value.find((e) => e.value === element.id_det_existencia_almacen)

                    // Construct array
                    const array = {
                        id: element.id_det_requerimiento, //id_det_recepcion_pedido
                        detId: element.id_det_existencia_almacen, //id_det_existencia_almacen
                        qty: element.cant_det_requerimiento, //Represents the the number of products the user wants to register
                        cost: element.detalle_existencia_almacen.existencia_almacen.costo_existencia_almacen, //Represents the the cost of the product
                        prevAvails: element.detalle_existencia_almacen.cant_det_existencia_almacen,
                        fractionated: element.producto.fraccionado_producto,
                        prodLabel: selectedProd.label,
                        avails: "",
                        total: "", //Represents the result of qty x cost for every row
                        deleted: false, //This is the state of the row, it represents the logical deletion.
                    };
                    // Push array to prods
                    adjustment.value.prods.push(array);
                }
            });

            // Desplazar la pantalla hasta la última fila agregada
            nextTick(() => {
                const newRowElement = document.getElementById(`observ`);
                if (newRowElement) {
                    newRowElement.scrollIntoView({ behavior: 'smooth', block: 'end' });
                }
            });
        }
    }

    const addNewRow = () => {
        let array = {
            id: "",
            detId: "",
            fractionated: "",
            prodLabel: "",
            qty: "",
            cost: "",
            prevAvails: "",
            avails: "",
            total: '0.00',
            deleted: false,
        }
        adjustment.value.prods.push(array);

        // Desplazar la pantalla hasta la última fila agregada
        nextTick(() => {
            const newRowElement = document.getElementById(`observ`);
            if (newRowElement) {
                newRowElement.scrollIntoView({ behavior: 'smooth', block: 'end' });
            }
        });
    }

    const searchProducts = async () => {
        //We look for errors first
        adjustment.value.centerId === '' ? frontErrors.value.centerId[0] = 'Debe seleccionar centro' : frontErrors.value.centerId[0] = ''
        adjustment.value.financingSourceId === '' ? frontErrors.value.financingSourceId[0] = 'Debe seleccionar fuente financiamiento' : frontErrors.value.financingSourceId[0] = ''
        if ([1, 2, 3].includes(adjustment.value.financingSourceId)) {
            frontErrors.value.idLt[0] = adjustment.value.idLt === '' ? 'Debe seleccionar linea de trabajo' : '';
        } else {
            frontErrors.value.idLt[0] = '';
        }

        // Evalúa si todos los errores están vacíos
        const allErrorsEmpty = Object.values(frontErrors.value).every(errors => errors[0] === '');

        if (allErrorsEmpty) {
            executeSearchQuery()
        }
    };

    const executeSearchQuery = async () => {
        try {
            isLoadingProds.value = true;
            const response = await axios.post(
                `/search-products-outgoing-adjustment`, {
                financingSourceId: adjustment.value.financingSourceId,
                centerId: adjustment.value.centerId,
                idLt: adjustment.value.idLt
            });
            products.value = response.data.products
            filteredOptions.value = response.data.products
            products.value.length > 0 ? addNewRow() : ''
        } catch (err) {
            if (err.response && err.response.data.logical_error) {
                useShowToast(toast.error, err.response.data.logical_error);
                context.emit("get-table");
            } else {
                showErrorMessage(err);
            }
        } finally {
            isLoadingProds.value = false;
            load.value++
        }
    }

    const {
        validateInput
    } = useValidateInput()

    const handleValidation = (input, validation, element) => {
        if (element) {
            adjustment.value.prods[element.index][input] = validateInput(adjustment.value.prods[element.index][input], validation)
        } else {
            adjustment.value[input] = validateInput(adjustment.value[input], validation)
        }
    }

    const selectProd = (detId, index) => {
        if (detId) {
            const selectedProd = products.value.find((e) => e.value === detId)
            adjustment.value.prods[index].cost = selectedProd.allInfo.existencia_almacen.costo_existencia_almacen
            adjustment.value.prods[index].avails = selectedProd.allInfo.cant_det_existencia_almacen
            adjustment.value.prods[index].prevAvails = selectedProd.allInfo.cant_det_existencia_almacen
            adjustment.value.prods[index].prodLabel = selectedProd.label
            adjustment.value.prods[index].fractionated = selectedProd.allInfo.existencia_almacen.producto.fraccionado_producto
        } else {
            adjustment.value.prods[index].cost = ''
            adjustment.value.prods[index].avails = ''
            adjustment.value.prods[index].prevAvails = ''
            adjustment.value.prods[index].prodLabel = ''
            adjustment.value.prods[index].fractionated = ''
        }
        adjustment.value.prods[index].qty = ''
    }

    const deleteRow = (index, detRecId) => {
        if (activeDetails.value.length <= 1) {
            useShowToast(toast.warning, "No puedes eliminar todas las filas.");
        } else {
            swal({
                title: 'Eliminar producto.',
                text: "¿Estas seguro de eliminar temporalmente el producto? Los cambios surtiran efecto al actualizar el ajuste.",
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
                        adjustment.value.prods.splice(index, 1);
                    } else {
                        adjustment.value.prods[index].deleted = true
                    }
                }
            });
        }
    }

    const resetProducts = () => {
        swal({
            title: '¿Está seguro de reiniciar productos? Esta acción borrará todos los productos agregados actualmente.',
            icon: 'question',
            iconHtml: '❓',
            confirmButtonText: 'Si, Reiniciar',
            confirmButtonColor: '#141368',
            cancelButtonText: 'Cancelar',
            showCancelButton: true,
            showCloseButton: true
        }).then(async (result) => {
            if (result.isConfirmed) {
                for (let i = adjustment.value.prods.length - 1; i >= 0; i--) {
                    const e = adjustment.value.prods[i];
                    if (e.id !== '') {
                        e.deleted = true;
                    } else {
                        adjustment.value.prods.splice(i, 1);
                    }
                }
                products.value = []
                filteredOptions.value = []
                load.value = 0
            }
        });
    }

    const changeFinancingSource = (id) => {
        if (id) {
            if (id === 4)//DONACION
            {
                adjustment.value.idLt = ''
            }
        } else {
            adjustment.value.financingSourceId = ''
        }
    }

    const changeCenter = (id) => {
        if(id != '' && id === adjustment.value.destCenterId){
            adjustment.value.destCenterId = ''
        }
    }

    const storeAdjustment = async (obj) => {
        swal({
            title: '¿Está seguro de guardar nueva transferencia?',
            icon: 'question',
            iconHtml: '❓',
            confirmButtonText: 'Si, Guardar',
            confirmButtonColor: '#141368',
            cancelButtonText: 'Cancelar',
            showCancelButton: true,
            showCloseButton: true
        }).then(async (result) => {
            if (result.isConfirmed) {
                saveObject(obj, '/save-warehouse-transfer-info');
            }
        });
    };

    const updateAdjustment = async (obj) => {
        swal({
            title: '¿Está seguro de actualizar ajuste?',
            icon: 'question',
            iconHtml: '❓',
            confirmButtonText: 'Si, Actualizar',
            confirmButtonColor: '#141368',
            cancelButtonText: 'Cancelar',
            showCancelButton: true,
            showCloseButton: true
        }).then(async (result) => {
            if (result.isConfirmed) {
                saveObject(obj, '/update-warehouse-transfer-info');
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
                console.log(error);
                
                handleErrorResponse(error)
            })
            .finally(() => {
                isLoadingRequest.value = false;
            });
    };

    const handleErrorResponse = (err) => {
        if (err.response.status === 422) {
            // Desplazar la pantalla hasta la última fila agregada
            nextTick(() => {
                const newRowElement = document.getElementById(`observ`);
                if (newRowElement) {
                    newRowElement.scrollIntoView({ behavior: 'smooth', block: 'end' });
                }
            });
            filteredOptions.value = products.value
            if (err.response && err.response.data.logical_error) {
                useShowToast(toast.error, err.response.data.logical_error);
            } else {
                useShowToast(toast.warning, "Tienes errores en tus datos, por favor verifica e intenta nuevamente.");
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

    const openOption = (option) => {
        const newOptions = []
        products.value.map((element) => {
            const isSelected = adjustment.value.prods.some((e) => e.detId === element.value && e.deleted === false);
            const isClicked = element.value === option
            if (!isSelected || isClicked) {
                newOptions.push(element)
            }
        });
        filteredOptions.value = newOptions
    }

    const totalRec = computed(() => {
        let sum = 0
        for (let i = 0; i < adjustment.value.prods.length; i++) {
            if (adjustment.value.prods[i].deleted == false) {
                let amount = parseFloat(adjustment.value.prods[i].total);
                if (!isNaN(amount)) {
                    sum += amount;
                }
            }
        }
        // Set the total amount in the adjustment object
        adjustment.value.total = round2Decimals(sum);
        // Return the computed total amount
        return round2Decimals(sum);
    });

    const activeDetails = computed(() => {
        return adjustment.value.prods.filter((detail) => detail.deleted == false)
    });

    const showSearchButton = computed(() => {
        return products.value.length > 0 ? false : true;
    });

    const filteredCenters = computed(() => {
        return centers.value.filter((center) => adjustment.value.centerId != center.value)
    });

    const nonSelectedProds = computed(() => {
        // Obtener todos los IDs de productos seleccionados
        const selectedIds = adjustment.value.prods.map(e => e.detId);

        // Filtrar productos que no estén en la lista de IDs seleccionados
        return products.value.filter(product => !selectedIds.includes(product.value));
    })

    // Observa cambios en las propiedades qty y cost de cada producto
    watch(adjustment, (newValue) => {
        newValue.prods.forEach((prod) => {
            let prevRes = prod.qty * prod.cost
            prod.total = prod.fractionated === 1 ? prevRes.toFixed(8) : prevRes.toFixed(6)

            let prev = prod.prevAvails - prod.qty
            prod.avails = prod.fractionated === 1 ? prev.toFixed(2) : prev
        });
    }, { deep: true });

    return {
        isLoadingRequest, errors, reasons, centers, financingSources, lts, adjustment, products,
        nonSelectedProds, totalRec, isLoadingProds, load, showSearchButton, filteredOptions, frontErrors,
        filteredCenters,
        getInfoForModalTransfers, selectProd, deleteRow, addNewRow, storeAdjustment, updateAdjustment,
        searchProducts, resetProducts, handleValidation, openOption, changeFinancingSource, changeCenter
    }
}
