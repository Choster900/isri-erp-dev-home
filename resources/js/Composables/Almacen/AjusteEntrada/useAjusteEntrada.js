import { ref, inject, computed, nextTick, watch } from "vue";
import axios from "axios";
import { useHandleError } from "@/Composables/General/useHandleError.js";
import { useShowToast } from "@/Composables/General/useShowToast.js";
import { useFormatDateTime } from "@/Composables/General/useFormatDateTime.js";
import { useValidateInput } from '@/Composables/General/useValidateInput';
import { toast } from "vue3-toastify";
import moment from 'moment';
import _ from "lodash";

export const useAjusteEntrada = (context) => {
    const swal = inject("$swal");
    const isLoadingRequest = ref(false);
    const isLoadingProduct = ref(false);
    const errors = ref([]);
    const products = ref([])
    const asyncProds = ref([])

    const reasons = ref([])
    const centers = ref([])
    const financingSources = ref([])
    const lts = ref([])
    const brands = ref([])

    const adjustment = ref({
        id: '',
        centerId: '',
        financingSourceId: '',
        reasonId: '',
        brandId: '',
        prodId: '', //product identifier for global search
        idLt: '',
        number: '',
        expDate: '',
        perishable: '',
        observation: '',
        status: '',
        prods: []
    })

    const {
        formatDateVue3DP
    } = useFormatDateTime()

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

    const getInfoForModalAdjustment = async (id) => {
        try {
            isLoadingRequest.value = true;
            const response = await axios.post(
                `/get-info-modal-shortage-adjustment`, {
                id: id,
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
    };

    const setModalValues = (data, id) => {
        const req = data.req;

        // Configurar valores generales
        setGeneralValues(data,id);

        if (id > 0) {
            setAdjustmentValues(req);

            // Desplazar la pantalla hasta la última fila agregada
            scrollToLastRow();
        }
    }

    const setGeneralValues = (data,id) => {
        adjustment.value.status = id > 0 ? data.req.id_estado_req : 1
        reasons.value = data.reasons;
        centers.value = data.centers;
        financingSources.value = data.financingSources;
        lts.value = data.lts;
        brands.value = data.brands;
    }

    const setAdjustmentValues = (req) => {
        adjustment.value.id = req.id_requerimiento;
        adjustment.value.centerId = req.id_centro_atencion;
        adjustment.value.financingSourceId = req.id_proy_financiado;
        adjustment.value.reasonId = req.id_motivo_ajuste;
        adjustment.value.idLt = req.id_lt;
        adjustment.value.number = req.num_requerimiento;
        adjustment.value.observation = req.observacion_requerimiento ?? '';

        req.detalles_requerimiento.forEach(element => {
            if (element.estado_det_requerimiento === 1) {
                const array = constructArrayFromElement(element);
                adjustment.value.prods.push(array);
            }
        });
    }

    const constructArrayFromElement = (element) => {
        return {
            detId: element.id_det_requerimiento,
            prodId: element.id_producto,
            brandId: element.id_marca,
            brandLabel: element.marca.nombre_marca,
            perishable: element.producto.perecedero_producto,
            fractionated: element.producto.fraccionado_producto,
            expDate: formatDateVue3DP(element.fecha_vcto_det_requerimiento),
            desc: `${element.producto.codigo_producto} — ${element.producto.nombre_completo_producto} — ${element.producto.unidad_medida.nombre_unidad_medida}`,
            qty: element.producto.fraccionado_producto === 0 ? floatToInt(element.cant_det_requerimiento) : element.cant_det_requerimiento,
            cost: parseFloat(element.costo_det_requerimiento).toFixed(2),
            total: "",
            deleted: false,
        };
    }

    const scrollToLastRow = () => {
        nextTick(() => {
            const newRowElement = document.getElementById(`total`);
            if (newRowElement) {
                newRowElement.scrollIntoView({ behavior: 'smooth', block: 'end' });
            }
        });
    }


    const returnToTop = () => {
        // Desplazar la pantalla a la parte superior
        nextTick(() => {
            const newRowElement = document.getElementById(`headerFormat`);
            if (newRowElement) {
                newRowElement.scrollIntoView({ behavior: 'smooth', block: 'end' });
            }
        });
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

    const selectProd = (prodId) => {
        if (!prodId) {
            useShowToast(toast.info, "Debes elegir un producto de la lista.");
            return;
        }
    
        const selectedProd = products.value.find((e) => e.value === prodId);
        const array = constructArrayFromProduct(selectedProd.allInfo, prodId);
        adjustment.value.prods.push(array);
    
        scrollToLastRowWhenInsert();
    
        adjustment.value.prodId = ''; // Limpiar la selección
    }
    
    const constructArrayFromProduct = (productInfo, prodId) => {
        return {
            detId: '',
            prodId: prodId,
            desc: `${productInfo.codigo_producto} — ${productInfo.nombre_completo_producto} — ${productInfo.unidad_medida.nombre_unidad_medida}`,
            brandId: '',
            brandLabel: '',
            prodLabel: '',
            expDate: '',
            fractionated: productInfo.fraccionado_producto,
            perishable: productInfo.perecedero_producto,
            qty: '',
            cost: '',
            total: '',
            deleted: false,
        };
    }
    
    const scrollToLastRowWhenInsert = () => {
        nextTick(() => {
            const newRowId = `row-${adjustment.value.prods.length - 1}`;
            const newRowElement = document.getElementById(newRowId);
            if (newRowElement) {
                newRowElement.scrollIntoView({ behavior: 'smooth', block: 'end' });
                applyBlinkingAnimation(newRowElement);
            }
        });
    }
    
    const applyBlinkingAnimation = (element) => {
        element.classList.add('blinking');
        setTimeout(() => {
            element.classList.remove('blinking');
        }, 3000); // 3000 milisegundos (3 segundos)
    }
    

    const asyncFindProduct = _.debounce(async (query, index, prodId) => {
        try {
            isLoadingProduct.value = true
            if (query.length >= 3) {
                const response = await axios.post("/search-donation-product", {
                    busqueda: query,
                });
                products.value = response.data.products;
            }
        } catch (errors) {
            products.value = [];
        } finally {
            isLoadingProduct.value = false
        }
    }, 350);

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

    const storeAdjustment = async (obj) => {
        swal({
            title: '¿Está seguro de guardar nuevo ajuste?',
            icon: 'question',
            iconHtml: '❓',
            confirmButtonText: 'Si, Guardar',
            confirmButtonColor: '#141368',
            cancelButtonText: 'Cancelar',
            showCancelButton: true,
            showCloseButton: true
        }).then(async (result) => {
            if (result.isConfirmed) {
                saveObject(obj, '/save-shortage-adjustment-info');
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
                saveObject(obj, '/update-shortage-adjustment-info');
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
        adjustment.value.total = sum.toFixed(2);
        return sum.toFixed(2);
    });

    const activeDetails = computed(() => {
        return adjustment.value.prods.filter((detail) => detail.deleted == false)
    });

    // Observa cambios en las propiedades qty y cost de cada producto
    watch(adjustment, (newValue) => {
        newValue.prods.forEach((prod) => {
            prod.total = (prod.qty * prod.cost).toFixed(2);
        });
    }, { deep: true });

    return {
        isLoadingRequest, errors, reasons, centers, financingSources, lts, adjustment,
        products, brands, asyncFindProduct, totalRec, asyncProds, activeDetails, isLoadingProduct,
        getInfoForModalAdjustment, selectProd, deleteRow, storeAdjustment, updateAdjustment, handleValidation,
        changeFinancingSource, returnToTop
    }
}