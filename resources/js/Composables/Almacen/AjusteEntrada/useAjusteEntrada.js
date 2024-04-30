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
    const errors = ref([]);
    const products = ref([])
    const asyncProds = ref([])
    const selectedProducts = ref([])

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
        const req = data.req
        //Set the general information to show in the view

        reasons.value = data.reasons
        centers.value = data.centers
        financingSources.value = data.financingSources
        lts.value = data.lts
        brands.value = data.brands

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


            // Iterate over detalle_requerimiento
            req.detalles_requerimiento.forEach(element => {
                // Check estado_det_recepcion_pedido
                if (element.estado_det_requerimiento === 1) {
                    //Construct the options
                    let arrayOpt = {
                        value: element.producto.id_producto,
                        label: element.producto.codigo_producto + " - " + element.producto.nombre_producto,
                    };
                    selectedProducts.value.push(arrayOpt);
                    products.value.push(arrayOpt);

                    // Construct array
                    const array = {
                        detId: element.id_det_requerimiento, //id_det_recepcion_pedido
                        prodId: element.id_producto, //id_product
                        brandId: element.id_marca,
                        brandLabel: element.marca.nombre_marca,
                        perishable: element.producto.perecedero_producto,
                        expDate: formatDateVue3DP(element.fecha_vcto_det_requerimiento), //Expiry date
                        isLoadingProd: false, //Flag to manage loader for every multiselect
                        desc: element.producto.codigo_producto + ' — ' + element.producto.nombre_completo_producto + ' — ' + element.producto.unidad_medida.nombre_unidad_medida,
                        qty: element.cant_det_requerimiento, //Represents the the number of products the user wants to register
                        cost: element.costo_det_requerimiento, //Represents the the cost of the product
                        total: "", //Represents the result of qty x cost for every row
                        deleted: false, //This is the state of the row, it represents the logical deletion.
                    };
                    // Push array to prods
                    adjustment.value.prods.push(array);
                }
            });
            // Desplazar la pantalla hasta la última fila agregada
            nextTick(() => {
                const newRowElement = document.getElementById(`total`);
                if (newRowElement) {
                    newRowElement.scrollIntoView({ behavior: 'smooth', block: 'end' });
                }
            });
        } else {
            // Call addNewRow
            addNewRow();
        }
    }

    const addNewRow = () => {
        let array = {
            detId: "",
            prodId: "",
            perishable: "",
            expDate: "",
            isLoadingProd: false,
            desc: "",
            brandId: "",
            brandLabel: "",
            qty: "",
            cost: "",
            total: '0.00',
            deleted: false,
        }
        adjustment.value.prods.push(array);

        // Desplazar la pantalla hasta la última fila agregada
        nextTick(() => {
            const newRowElement = document.getElementById(`total`);
            if (newRowElement) {
                newRowElement.scrollIntoView({ behavior: 'smooth', block: 'end' });
            }
        });
    }

    const selectProd = (prodId, index) => {
        if (prodId) {
            const selectedProd = products.value.find((e) => e.value === prodId)
            adjustment.value.prods[index].desc = selectedProd.allInfo.codigo_producto + ' — ' + selectedProd.allInfo.nombre_completo_producto + ' — ' + selectedProd.allInfo.unidad_medida.nombre_unidad_medida
            adjustment.value.prods[index].perishable = selectedProd.allInfo.perecedero_producto
            let arrayOpt = {
                value: selectedProd.allInfo.id_producto,
                label: selectedProd.allInfo.codigo_producto + " — " + selectedProd.allInfo.nombre_completo_producto,
            };
            selectedProducts.value.push(arrayOpt);
        } else {
            adjustment.value.prods[index].prodId = ''
            adjustment.value.prods[index].desc = ''
            adjustment.value.prods[index].perishable = ''
        }
        adjustment.value.prods[index].brandId = ''
        adjustment.value.prods[index].qty = ''
        adjustment.value.prods[index].cost = ''
        adjustment.value.prods[index].expDate = ''
    }

    const asyncFindProduct = _.debounce(async (query, index, prodId) => {
        try {
            adjustment.value.prods[index].isLoadingProd = true
            if (query.length >= 3) {
                const response = await axios.post("/search-donation-product", {
                    busqueda: query,
                });
                products.value = response.data.products;
            } else {
                products.value = [];
            }
        } catch (errors) {
            products.value = [];
        } finally {
            adjustment.value.prods[index].isLoadingProd = false
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
                products.value = selectedProducts.value
            });
    };

    const handleErrorResponse = (err) => {
        console.log(err);
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
        products, brands, asyncFindProduct, totalRec, asyncProds, selectedProducts,
        getInfoForModalAdjustment, selectProd, deleteRow, addNewRow, storeAdjustment, updateAdjustment, handleValidation,
        changeFinancingSource
    }
}