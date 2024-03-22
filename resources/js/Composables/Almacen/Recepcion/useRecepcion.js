import { ref, inject, computed, nextTick } from "vue";
import axios from "axios";
import { useHandleError } from "@/Composables/General/useHandleError.js";
import { useShowToast } from "@/Composables/General/useShowToast.js";
import { toast } from "vue3-toastify";
import { useValidateInput } from '@/Composables/General/useValidateInput';
import { useFormatDateTime } from "@/Composables/General/useFormatDateTime.js";
import { localeData } from 'moment_spanish_locale';
import moment from 'moment';
import { upperCase } from "lodash";
moment.locale('es', localeData)

export const useRecepcion = (context) => {
    const swal = inject("$swal");
    const isLoadingRequest = ref(false);
    const errors = ref([]);

    const docSelected = ref('')
    const reception = ref([])
    const products = ref([])
    const filteredProds = ref([])
    const documents = ref([])
    const items = ref([])
    const startRec = ref(false)
    const infoToShow = ref({ //This is an object used to show general information related to the acquisition document
        docId: '', //id_doc_adquisicion
        detDocId: '', //id_det_doc_adquisicion
        docName: '', //nombre_tipo_doc_adquisicion
        itemName: '', //nombre_det_doc_adquisicion
        financingSource: '', //codigo_proy_financiado
        commitment: '', //compromiso_ppto_det_doc_adquisicion
        supplier: '', //razon_social_proveedor
        nit: '', //nit_proveedor
        dateTime: '', //fecha_reg_recepcion_pedido
    })

    const recDocument = ref({
        id: '', //reception id
        acta: '', //Acta number
        invoice: '', //Invoice number
        financingSourceId: '',
        observation: '', //Reception observation
        detDocId: '', //Identifier of the document detail related to the reception
        status: '', //We use this to manage some functionalities in the view, it represent the reception status
        total: '', //This is the sum of all products
        prods: [], //Array of products
        procedure: [] //This is the result of the query we are using to compare the quantity of each product, we are sending this to backend to compare with a updated query
    })

    const {
        formatDateVue3DP
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
                documents.value = response.data.docs
                items.value = response.data.test
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
        }
    }

    const setModalValues = (data, id) => {
        const recepData = data.recep
        //Set the general information to show in the view
        infoToShow.value.docName = data.itemInfo.documento_adquisicion.tipo_documento_adquisicion.nombre_tipo_doc_adquisicion + " " + data.itemInfo.documento_adquisicion.numero_doc_adquisicion
        infoToShow.value.itemName = upperCase(data.itemInfo.nombre_det_doc_adquisicion)
        infoToShow.value.financingSource = data.itemInfo.fuente_financiamiento.codigo_proy_financiado
        infoToShow.value.commitment = data.itemInfo.compromiso_ppto_det_doc_adquisicion
        infoToShow.value.supplier = data.itemInfo.documento_adquisicion.proveedor.razon_social_proveedor
        infoToShow.value.nit = data.itemInfo.documento_adquisicion.proveedor.nit_proveedor
        infoToShow.value.dateTime = recepData ? moment(recepData.fecha_reg_recepcion_pedido).format('DD/MM/YYYY, HH:mm:ss') : ''
        infoToShow.value.status = id > 0 ? recepData.id_estado_recepcion_pedido : 1

        recDocument.value.financingSourceId = data.itemInfo.id_proy_financiado
        recDocument.value.detDocId = data.itemInfo.id_det_doc_adquisicion

        // Check if id > 0
        if (id > 0) {
            recDocument.value.id = recepData.id_recepcion_pedido //Set reception id
            recDocument.value.acta = recepData.acta_recepcion_pedido //Set acta number
            recDocument.value.invoice = recepData.factura_recepcion_pedido //Set invoice number
            recDocument.value.observation = recepData.observacion_recepcion_pedido

            if (recepData.id_estado_recepcion_pedido === 1) {
                // Filter products based on conditions
                const newOptions = data.products.filter(element => {
                    const rightOpt = recepData.detalle_recepcion.some(e => e.id_prod_adquisicion === element.value && e.estado_prod_adquisicion === 1);
                    return rightOpt || element.total_menos_acumulado != 0;
                });

                // Set products and filteredProds to newOptions
                products.value = filteredProds.value = newOptions;
            }else{
                filteredProds.value = products.value = data.products
            }

            // Iterate over detalle_recepcion
            recepData.detalle_recepcion.forEach(element => {
                // Check estado_det_recepcion_pedido
                if (element.estado_det_recepcion_pedido === 1) {
                    const paId = element.producto_adquisicion.id_prod_adquisicion;
                    const cantRecep = element.cant_det_recepcion_pedido;
                    const detRecepId = element.id_det_recepcion_pedido;

                    // Construct array
                    const array = {
                        detRecId: detRecepId, //id_det_recepcion_pedido
                        prodId: paId, //id_prod_adquisicion
                        desc: "", //Acquisition product description
                        //expiryDate: formatDateVue3DP(element.fecha_vencimiento_det_recepcion_pedido),
                        perishable: "", //If the product is perishable, set to true, otherwise set to false.
                        avails: "", //Represents the maximum number of products that the user can write.
                        qty: cantRecep, //Represents the the number of products the user wants to register
                        cost: "", //Represents the the cost of the product
                        total: "", //Represents the result of qty x cost for every row
                        deleted: false, //This is the state of the row, it represents the logical deletion.
                        initial: "" //Represents the initial availability of a product
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
            recDocument.value.prods[index].desc =
                selectedProd.nombre_completo_producto + ' — ' +
                selectedProd.nombre_unidad_medida + ' — ' +
                selectedProd.descripcion_prod_adquisicion

            recDocument.value.prods[index].perishable = recepId ? recDocument.value.prods[index].perishable : ''
            //recDocument.value.prods[index].expiryDate = recepId ? recDocument.value.prods[index].expiryDate : ''
            recDocument.value.prods[index].avails = selectedProd.total_menos_acumulado
            recDocument.value.prods[index].perishable = selectedProd.perecedero_producto
            recDocument.value.prods[index].cost = selectedProd.costo_prod_adquisicion
            recDocument.value.prods[index].qty = recepId ? recDocument.value.prods[index].qty : ''
            recDocument.value.prods[index].total = '0.00'
            recDocument.value.prods[index].initial = selectedProd.total_menos_acumulado
        } else {
            recDocument.value.prods[index].desc = ""
            recDocument.value.prods[index].perishable = ""
            //recDocument.value.prods[index].expiryDate = ""
            recDocument.value.prods[index].avails = ""
            recDocument.value.prods[index].qty = ""
            recDocument.value.prods[index].cost = ""
            recDocument.value.prods[index].total = '0.00'
            recDocument.value.prods[index].initial = ""
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
            recDocument.value[input] = validateInput(recDocument.value[input], validation)
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
        recDocument.value.total = sum.toFixed(2);
        return sum.toFixed(2);
    });

    const addNewRow = () => {
        if (activeDetails.value.length < products.value.length) {
            let array = {
                detRecId: "",
                prodId: "",
                desc: "",
                //expiryDate: '',
                perishable: "",
                avails: "",
                qty: "",
                cost: "",
                total: '0.00',
                deleted: false,
                initial: ""
            }
            recDocument.value.prods.push(array);

            // Desplazar la pantalla hasta la última fila agregada
            nextTick(() => {
                //const newRowIndex = recDocument.value.prods.length - 1;
                //const newRowElement = document.getElementById(`row-${newRowIndex}`);
                const newRowElement = document.getElementById(`total`);
                if (newRowElement) {
                    newRowElement.scrollIntoView({ behavior: 'smooth', block: 'end' });
                }
            });
        } else {
            useShowToast(toast.warning, "Has alcanzado el maximo de filas disponibles.");
        }
    }

    const deleteRow = (index, detRecId) => {
        if (activeDetails.value.length <= 1) {
            useShowToast(toast.warning, "No puedes eliminar todas las filas.");
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
            if (err.response && err.response.data.logical_error) {
                useShowToast(toast.error, err.response.data.logical_error);
                if (err.response.data.refresh) {
                    products.value = filteredProds.value = err.response.data.prods
                    recDocument.value.prods.forEach((element, index) => {
                        setProdItem(index, element.prodId, element.detRecId);
                        updateItemTotal(index, element.qty, element.prodId);
                    })
                }
            } else {
                useShowToast(toast.warning, "Tienes errores en tus datos, por favor verifica e intenta nuevamente.");
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
        errors, isLoadingRequest, reception, infoToShow,
        documents, ordenC, contrato, docSelected, totalRec,
        filteredDoc, filteredItems, recDocument, startRec, filteredProds,
        getInfoForModalRecep, startReception, setProdItem, updateItemTotal, addNewRow,
        openOption, deleteRow, handleValidation, storeReception, updateReception
    }
}