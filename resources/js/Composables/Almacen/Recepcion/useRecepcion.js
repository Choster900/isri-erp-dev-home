import { ref, inject, computed, nextTick, watch } from "vue";
import axios from "axios";
import { useHandleError } from "@/Composables/General/useHandleError.js";
import { useToCalculate } from '@/Composables/General/useToCalculate.js';
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
    const documents = ref([])
    const items = ref([])
    const brands = ref([])
    const isLoadingItem = ref(false);
    const months = ref([])

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
        dui: '', //dui_proveedor
        dateTime: '', //fecha_reg_recepcion_pedido
        acqDocDate: '', //acquisition document reference date
        monthName: ''
    })

    const recDocument = ref({
        id: '', //reception id
        acta: '', //Acta number
        monthId: '',
        detStockId: '',
        isGas: '',
        financingSourceId: '',
        is6Decimals:'',
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

    const { round2Decimals } = useToCalculate();

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
        try {
            isLoadingRequest.value = true;
            const response = await axios.post(
                `/get-info-modal-recep`, {
                id: id,
                detId: infoToShow.value.detDocId,
                monthId: recDocument.value.monthId
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

    const setModalValues = (data, id) => {
        setGeneralInfo(data, id);
        setRecDocumentData(data, id);
        if (id > 0) {
            setExistingReceptionData(data.products, data.recep);
        } else {
            filterProducts(data.products);
        }
        startRec.value = true;
    };

    const setGeneralInfo = (data, id) => {
        const itemInfo = data.itemInfo;
        const recepData = data.recep;

        infoToShow.value.docName = itemInfo.documento_adquisicion.tipo_documento_adquisicion.nombre_tipo_doc_adquisicion + ' "' + itemInfo.documento_adquisicion.numero_doc_adquisicion + '"'
        infoToShow.value.itemName = upperCase(itemInfo.nombre_det_doc_adquisicion)
        infoToShow.value.financingSource = itemInfo.fuente_financiamiento.nombre_proy_financiado
        infoToShow.value.commitment = itemInfo.compromiso_ppto_det_doc_adquisicion
        infoToShow.value.supplier = itemInfo.documento_adquisicion.proveedor.razon_social_proveedor
        infoToShow.value.nit = itemInfo.documento_adquisicion.proveedor.nit_proveedor
        infoToShow.value.dui = itemInfo.documento_adquisicion.proveedor.dui_proveedor
        infoToShow.value.dateTime = recepData ? moment(recepData.fecha_reg_recepcion_pedido).format('DD/MM/YYYY, HH:mm:ss') : ''
        infoToShow.value.status = id > 0 ? recepData.id_estado_recepcion_pedido : 1
        infoToShow.value.acqDocDate = moment(itemInfo.documento_adquisicion.fecha_adjudicacion_doc_adquisicion).format('DD/MM/YYYY')
    };

    const setRecDocumentData = (data, id) => {
        const itemInfo = data.itemInfo;
        const recepData = data.recep;

        recDocument.value.is6Decimals = itemInfo.tipo_costo_det_doc_adquisicion === 1 ? false : true
        recDocument.value.financingSourceId = itemInfo.id_proy_financiado
        recDocument.value.detDocId = itemInfo.id_det_doc_adquisicion
        recDocument.value.isGas = itemInfo.documento_adquisicion.proceso_compra.nombre_proceso_compra === 'GAS LICUADO DE PETROLEO' ? true : false
        recDocument.value.procedure = data.products
        brands.value = data.brands

        if (id > 0) {
            recDocument.value.id = recepData.id_recepcion_pedido //Set reception id
            recDocument.value.acta = recepData.acta_recepcion_pedido //Set acta number
            recDocument.value.observation = recepData.observacion_recepcion_pedido ?? '' //Set observation
            docSelected.value = itemInfo.documento_adquisicion.id_tipo_doc_adquisicion;
            if (docSelected.value === 1) {
                infoToShow.value.monthName = data.recep.mes_recepcion.nombre_mes_recepcion;
                recDocument.value.monthId = data.recep.id_mes_recepcion;
            }
        }
    };

    const setExistingReceptionData = (prodcs, recepData) => {
        products.value = filterExistingProducts(prodcs, recepData);
        recDocument.value.prods = groupProducts(recepData.detalle_recepcion);
        scrollToElement('observ');
    };

    const filterExistingProducts = (prods, recepData) => {
        return prods.filter(element => {
            const isRightOpt = recepData.detalle_recepcion.some(e => e.id_prod_adquisicion === element.value && e.estado_prod_adquisicion === 1);
            return isRightOpt || element.total_menos_acumulado > 0 || element.total_menos_acumulado_monto > 0;
        });
    };

    const groupProducts = (detalleRecepcion) => {
        const groupedProducts = [];
        detalleRecepcion.forEach(element => {
            if (element.estado_det_recepcion_pedido === 1) {
                const codigo_up_lt = element.producto_adquisicion.linea_trabajo.codigo_up_lt;
                const id_lt = element.producto_adquisicion.id_lt;
                let groupIndex = groupedProducts.findIndex(group => group.codigo_up_lt === codigo_up_lt);
                if (groupIndex === -1) {
                    groupedProducts.push({ id_lt, codigo_up_lt, hover: false, isOpen: false, productos: [] });
                    groupedProducts.sort((a, b) => a.id_lt - b.id_lt);
                    groupIndex = groupedProducts.findIndex(group => group.codigo_up_lt === codigo_up_lt);
                }
                groupedProducts[groupIndex].productos.push(createProductObject(element));
            }
        });
        return groupedProducts;
    };

    const createProductObject = (element) => {
        return {
            detRecId: element.id_det_recepcion_pedido,
            prodId: element.producto_adquisicion.id_prod_adquisicion,
            desc: `${element.producto_adquisicion.linea_trabajo.codigo_up_lt} — ${element.producto_adquisicion.centro_atencion.codigo_centro_atencion} — ${element.producto.codigo_producto} — ${element.producto.nombre_completo_producto} — ${element.producto_adquisicion.descripcion_prod_adquisicion} — ${element.producto.unidad_medida.nombre_unidad_medida}`,
            brandId: element.id_marca,
            brandLabel: element.marca ? element.marca.nombre_marca : 'N/A',
            expiryDate: formatDateVue3DP(element.fecha_vcto_det_recepcion_pedido),
            perishable: element.producto.perecedero_producto,
            fractionated: element.producto.fraccionado_producto,
            avails: "",
            qty: element.producto.fraccionado_producto === 0 ? floatToInt(element.cant_det_recepcion_pedido) : element.cant_det_recepcion_pedido,
            cost: recDocument.value.is6Decimals ? parseFloat(element.producto_adquisicion.costo_prod_adquisicion).toFixed(6) : parseFloat(element.producto_adquisicion.costo_prod_adquisicion).toFixed(2),
            total: recDocument.value.isGas ? round2Decimals(element.cant_det_recepcion_pedido * element.costo_det_recepcion_pedido) : 
            recDocument.is6Decimals ? parseFloat(element.cant_det_recepcion_pedido * element.costo_det_recepcion_pedido).toFixed(6) : parseFloat(element.cant_det_recepcion_pedido * element.costo_det_recepcion_pedido).toFixed(2),
            deleted: false,
            initial: "",
            initialAmount: "",
        };
    };

    const filterProducts = (prodcs) => {
        const newOptions = prodcs.filter(element => {
            return recDocument.value.isGas ? element.total_menos_acumulado_monto > 0 : element.total_menos_acumulado > 0;
        });
        products.value = newOptions;
    };

    const selectItem = async (detDocId) => {
        if (detDocId && docSelected.value === 1) {
            try {
                isLoadingItem.value = true;
                const response = await axios.post(
                    `/check-available-months`, {
                    detDocId: detDocId
                }
                );
                months.value = response.data.monthsAvail
            } catch (err) {
                if (err.response.data.logical_error) {
                    useShowToast(toast.error, err.response.data.logical_error);
                    context.emit("get-table");
                } else {
                    showErrorMessage(err);
                }
            } finally {
                isLoadingItem.value = false;
            }
        } else {
            months.value = []
            recDocument.value.monthId = ''
        }
    }

    const changeMonth = (id) => {
        if (id) {
            const selectedMonth = months.value.find((element) => {
                return element.value === id;
            });
            infoToShow.value.monthName = selectedMonth.label
        } else {
            infoToShow.value.monthName = ''
        }
    }

    const createProdArray = (selectedProd, paId) => ({
        detRecId: '', //id_det_recepcion_pedido
        prodId: paId, //id_prod_adquisicion
        desc: `${selectedProd.codigo_up_lt} — ${selectedProd.codigo_centro_atencion} — ${selectedProd.codigo_producto} — ${selectedProd.nombre_completo_producto} — ${selectedProd.nombre_unidad_medida} — ${selectedProd.descripcion_prod_adquisicion}`, //Acquisition product description
        brandId: selectedProd.id_marca,
        brandLabel: '',
        expiryDate: '',
        perishable: selectedProd.perecedero_producto, //If the product is perishable, set to true, otherwise set to false.
        fractionated: selectedProd.fraccionado_producto,
        avails: '', //Represents the maximum number of products that the user can write.
        qty: '', //Represents the the number of products the user wants to register
        cost: recDocument.value.is6Decimals ? parseFloat(selectedProd.costo_prod_adquisicion).toFixed(6) : parseFloat(selectedProd.costo_prod_adquisicion).toFixed(2), //Represents the the cost of the product
        total: '', //Represents the result of qty x cost for every row
        deleted: false, //This is the state of the row, it represents the logical deletion.
        initial: selectedProd.total_menos_acumulado, //Represents the initial availability of a product
        initialAmount: selectedProd.total_menos_acumulado_monto, //Represents available money
    });

    const addProdToLineOfWork = (lineOfWork, array) => {
        lineOfWork.isOpen = true;
        lineOfWork.productos.push(array);
        return {
            indexLt: recDocument.value.prods.findIndex(group => group.id_lt === lineOfWork.id_lt),
            index: lineOfWork.productos.length - 1,
        };
    };

    const createNewLineOfWork = (selectedProd, array) => {
        recDocument.value.prods.push({
            id_lt: selectedProd.id_lt,
            codigo_up_lt: selectedProd.codigo_up_lt, // Si necesitas esta propiedad
            hover: false,
            isOpen: true,
            productos: [array],
        });

        recDocument.value.prods.sort((a, b) => a.id_lt - b.id_lt);

        return {
            indexLt: recDocument.value.prods.findIndex(e => e.id_lt === selectedProd.id_lt),
            index: 0,
        };
    };

    const setProdItem = (paId) => {
        if (!paId) {
            useShowToast(toast.info, "Debes elegir un producto de la lista.");
            return;
        }

        const selectedProd = products.value.find(element => element.value === paId);

        if (!selectedProd) {
            useShowToast(toast.error, "Producto no encontrado.");
            return;
        }

        const array = createProdArray(selectedProd, paId);

        const lineOfWork = recDocument.value.prods.find(group => group.id_lt === selectedProd.id_lt);

        const { indexLt, index } = lineOfWork
            ? addProdToLineOfWork(lineOfWork, array)
            : createNewLineOfWork(selectedProd, array);

        scrollToElement(`lt-${indexLt}prod-${index}`, 'smooth', 'end', true);

        recDocument.value.detStockId = '';
    };

    const checkBlinkingClass = (indexLt, index) => {
        const newRowId = `lt-${indexLt}prod-${index}`;
        const newRowElement = document.getElementById(newRowId);
        return newRowElement && newRowElement.classList.contains('blinking');
    }

    const scrollToElement = (elementId, behavior = 'smooth', block = 'end', highlight = false) => {
        nextTick(() => {
            const element = document.getElementById(elementId);
            if (element) {
                element.scrollIntoView({ behavior, block });
                if (highlight) {
                    element.classList.add('blinking');
                    setTimeout(() => {
                        element.classList.remove('blinking');
                    }, 3000);
                }
            }
        });
    };

    const returnToTop = () => {
        scrollToElement('headerFormat');
    }

    // Método para convertir un valor flotante con dos decimales a entero
    const floatToInt = (value) => {
        // Primero, convertimos el valor a un número flotante
        const floatValue = parseFloat(value);
        // Luego, lo redondeamos al entero más cercano
        const roundedValue = Math.round(floatValue);
        // Devolvemos el resultado como un número entero
        return roundedValue;
    }

    const {
        validateInput
    } = useValidateInput()

    const handleValidation = (input, validation, element) => {
        if (element) {
            recDocument.value.prods[element.indexLt].productos[element.index][input] = validateInput(recDocument.value.prods[element.indexLt].productos[element.index][input], validation)
        } else {
            recDocument.value[input] = validateInput(recDocument.value[input], validation)
        }
    }

    const calculateLtTotal = (indexLt) => {
        const matchProds = recDocument.value.prods[indexLt];
        let acumulado = 0
        matchProds.productos.forEach((e) => {
            if (!e.deleted) {
                let amount = parseFloat(e.total);
                if (!isNaN(amount)) {
                    acumulado += amount;
                }
            }
        })
        return round2Decimals(acumulado)
    }

    const hasActiveProds = (indexLt) => {
        return recDocument.value.prods[indexLt].productos.some((element) => !element.deleted);
    };

    const deleteRow = (indexLt, index, detRecId) => {
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
                        recDocument.value.prods[indexLt].productos.splice(index, 1);
                    } else {
                        recDocument.value.prods[indexLt].productos[index].deleted = true
                    }
                }
            });
        }
    }

    const handleReceptionStart = async (id) => {
        if (docSelected.value === 1) {
            if ((infoToShow.value.docId && infoToShow.value.docId != '')
                && (infoToShow.value.detDocId && infoToShow.value.detDocId != '')
                && (recDocument.value.monthId && recDocument.value.monthId != '')) {
                await startReception(id)
            } else {
                useShowToast(toast.warning, "No es posible iniciar la recepción, debes completar toda la información requerida.");
            }
        } else {
            if ((infoToShow.value.docId && infoToShow.value.docId != '')
                && (infoToShow.value.detDocId && infoToShow.value.detDocId != '')) {
                await startReception(id)
            } else {
                useShowToast(toast.warning, "No es posible iniciar la recepción, debes completar toda la información requerida.");
            }
        }
    }

    const showAvails = (prodId, indexLt, index, isGas) => {
        if (!prodId) {
            recDocument.value.prods[indexLt].productos[index].avails = -1;
            return "";
        }

        const { productos } = recDocument.value.prods[indexLt];
        const prodProcedure = products.value.find(e => e.value === prodId);

        if (!prodProcedure) return "";

        const initialAmount = parseFloat(isGas ? prodProcedure.acumulado_monto : prodProcedure.acumulado) || 0;

        const acumulado = productos
            .filter(e => e.prodId === prodId && !e.deleted)
            .reduce((sum, e) => {
                const amount = parseFloat(isGas ? e.total : e.qty);
                return sum + (isNaN(amount) ? 0 : amount);
            }, initialAmount);

        const qtyTotal = isGas
            ? (prodProcedure.cant_prod_adquisicion * prodProcedure.costo_prod_adquisicion) - acumulado
            : prodProcedure.cant_prod_adquisicion - acumulado;
        //                                       GAS(MONEY)                                                                           STOCK(2 decimals)     STOCK(0 decimals)                        
        const formattedQtyTotal = isGas ? round2Decimals(qtyTotal) : recDocument.value.prods[indexLt].productos[index].fractionated ? qtyTotal.toFixed(2) : qtyTotal;

        recDocument.value.prods[indexLt].productos[index].avails = formattedQtyTotal;

        // Agregar o actualizar la propiedad 'existencia' en 'products'
        const productIndex = products.value.findIndex(product => product.value === prodId);
        if (productIndex !== -1) {
            const existencia = qtyTotal > 0 ? qtyTotal : 0;
            products.value[productIndex].existencia = existencia;
        }

        return formattedQtyTotal;
    };

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
        if (err.response && err.response.status === 422) {
            if (err.response.data.logical_error) {
                useShowToast(toast.error, err.response.data.logical_error);
                if (err.response.data.refresh) {
                    products.value = err.response.data.prods
                }
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

    const activeDetails = computed(() => {
        // Filtrar los productos no eliminados dentro de todos los grupos
        const activeProducts = recDocument.value.prods.flatMap(group => group.productos.filter(producto => !producto.deleted));

        return activeProducts;
    });

    const totalRec = computed(() => {
        let sum = 0;
        for (let i = 0; i < recDocument.value.prods.length; i++) {
            for (let j = 0; j < recDocument.value.prods[i].productos.length; j++) { // <- Corregido aquí
                if (recDocument.value.prods[i].productos[j].deleted == false) {
                    let amount = parseFloat(recDocument.value.prods[i].productos[j].total);
                    if (!isNaN(amount)) {
                        sum += amount;
                    }
                }
            }
        }
        recDocument.value.total = round2Decimals(sum)
        return round2Decimals(sum)
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

    const filteredProds = computed(() => {
        return products.value.filter(product => {
            // Si la propiedad 'existencia' no está definida o es mayor que 0, incluir el producto
            return !('existencia' in product) || product.existencia > 0;
        });
    });

    // Observa cambios en las propiedades qty y cost de cada producto
    watch(recDocument, (newValue) => {
        newValue.prods.forEach((lts) => {
            lts.productos.forEach((prod) => {
                if (recDocument.value.isGas) {
                    if (prod.total && prod.qty) {
                        if (prod.total > 0 && prod.qty > 0) {
                            prod.cost = (prod.total / prod.qty).toFixed(6) //6 decimals when is GAS
                        } else {
                            prod.cost = '0.00'
                        }
                    } else {
                        prod.cost = '0.00'
                    }
                } else {
                    let prevRes = prod.qty * prod.cost
                    prod.total = recDocument.value.is6Decimals ? (prod.qty * prod.cost).toFixed(6) : round2Decimals(prevRes)
                }
            })
        });
    }, { deep: true });

    return {
        errors, isLoadingRequest, reception, infoToShow, activeDetails,
        documents, ordenC, contrato, docSelected, totalRec, products, isLoadingItem,
        filteredDoc, filteredItems, recDocument, startRec, filteredProds, brands, months,
        getInfoForModalRecep, handleReceptionStart, setProdItem, calculateLtTotal, checkBlinkingClass,
        deleteRow, handleValidation, storeReception, updateReception, showAvails, returnToTop,
        hasActiveProds, selectItem, changeMonth
    }
}