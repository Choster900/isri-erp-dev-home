import { ref, inject, computed, nextTick, watch } from "vue";
import axios from "axios";
import { useHandleError } from "@/Composables/General/useHandleError.js";
import { useShowToast } from "@/Composables/General/useShowToast.js";
import { toast } from "vue3-toastify";
import moment from 'moment';
import _ from "lodash";

export const useAjusteEntrada = (context) => {
    const swal = inject("$swal");
    const isLoadingRequest = ref(false);
    const errors = ref([]);
    const products = ref([])

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
        date: '',
        observation: '',
        status: '',
        prods: []
    })

    const getInfoForModalAdjustment = async (id) => {
        try {
            isLoadingRequest.value = true;
            const response = await axios.post(
                `/get-info-modal-shortage-adjustment`, {
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

            console.log(req);

            // Iterate over detalle_requerimiento
            req.detalles_requerimiento.forEach(element => {
                // Check estado_det_recepcion_pedido
                if (element.estado_det_requerimiento === 1) {
                    //Construct the options
                    let arrayOpt = {
                        value: element.producto.id_producto,
                        label: element.producto.codigo_producto + " - " + element.producto.nombre_producto,
                    };
                    products.value.push(arrayOpt);

                    // Construct array
                    const array = {
                        detId: element.id_det_requerimiento, //id_det_recepcion_pedido
                        prodId: element.id_producto, //id_product
                        centerId: element.id_centro_atencion, //Care center
                        brandId: element.id_marca,
                        isLoadingProd: false, //Flag to manage loader for every multiselect
                        //Product description
                        desc: element.producto.codigo_producto + ' — ' + element.producto.nombre_producto + ' — ' + element.producto.descripcion_producto + ' — ' + element.producto.unidad_medida.nombre_unidad_medida ?? 'Sin marca',
                        qty: element.cant_det_requerimiento, //Represents the the number of products the user wants to register
                        cost: element.costo_det_requerimiento, //Represents the the cost of the product
                        total: "", //Represents the result of qty x cost for every row
                        deleted: false, //This is the state of the row, it represents the logical deletion.
                    };
                    // Push array to prods
                    adjustment.value.prods.push(array);
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
            centerId: "",
            isLoadingProd: false,
            desc: "",
            qty: "",
            cost: "",
            total: '0.00',
            deleted: false,
        }
        adjustment.value.prods.push(array);

        // Desplazar la pantalla hasta la última fila agregada
        nextTick(() => {
            //const newRowIndex = donInfo.value.prods.length - 1;
            //const newRowElement = document.getElementById(`row-${newRowIndex}`);
            const newRowElement = document.getElementById(`total`);
            if (newRowElement) {
                newRowElement.scrollIntoView({ behavior: 'smooth', block: 'end' });
            }
        });
    }

    const selectProd = (prodId, index) => {
        if (prodId) {
            const selectedProd = products.value.find((e) => e.value === prodId)
            adjustment.value.prods[index].desc = selectedProd.allInfo.codigo_producto + ' — ' + selectedProd.allInfo.nombre_producto + ' — ' + selectedProd.allInfo.descripcion_producto + ' — ' + selectedProd.allInfo.unidad_medida.nombre_unidad_medida
        } else {
            donInfo.value.prods[index].desc = ''
        }
        donInfo.value.prods[index].centerId = ''
        donInfo.value.prods[index].qty = ''
        donInfo.value.prods[index].cost = ''
    }

    const asyncFindProduct = _.debounce(async (query, index, prodId) => {
        try {
            adjustment.value.prods[index].isLoadingProd = true
            if (query.length >= 3) {
                // Filtrar los elementos de defaultProds que tengan un valor diferente a prodId
                const filteredProds = adjustment.value.prods.filter((e) => e.prodId !== prodId && e.deleted == false);
                // Crear un array no asociativo con solo las propiedades 'value' de los elementos filtrados
                const prodIdToIgnore = filteredProds.map((e) => e.prodId); //Productos que debe ignorar al momento de realizar la busqueda asincrona
                const response = await axios.post("/search-donation-product", {
                    busqueda: query,
                    prodIdToIgnore: prodIdToIgnore
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
        products, brands, asyncFindProduct, totalRec,
        getInfoForModalAdjustment, selectProd, deleteRow, addNewRow
    }
}