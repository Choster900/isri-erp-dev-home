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
    const unitsMeasmt = ref([])
    const catPerc = ref([])
    const catNicsp = ref([])
    const baseOption = ref([])

    const prod = ref({
        id: '',
        name: '',
        description: '',
        mUnitId: '',
        price: '',
        status: '',
        budgetAccountId: '',
        purchaseProcedureId: '',
        unspscId: '',
        catPercId: '',
        catNicspId: '',
        perishable: -1,
        gAndS: -1
    })

    const getInfoForModalProd = async (id) => {
        try {
            isLoadingRequest.value = true;
            const response = await axios.get(
                `/get-info-modal-prod/${id}`
            );
            purchaseProcedures.value = response.data.purchaseProcedures
            budgetAccounts.value = response.data.budgetAccounts
            unitsMeasmt.value = response.data.unitsMeasmt
            catNicsp.value = response.data.catNicsp
            catPerc.value = response.data.catPerc
            id > 0 ? setModalValues(response.data.prod) : ''
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
            if (query.length >= 3) {
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
                label: product.catalogo_unspsc.codigo_catalogo_unspsc + " - " + product.catalogo_unspsc.nombre_catalogo_unspsc,
            };
            catUnspsc.value.push(array);
            baseOption.value = catUnspsc.value
        }

        prod.value.id = product.id_producto
        prod.value.name = product.nombre_producto
        prod.value.description = product.descripcion_producto
        prod.value.status = product.estado_producto
        prod.value.mUnitId = product.unidad_medida.id_unidad_medida
        prod.value.price = '$' + product.precio_producto
        prod.value.budgetAccountId = product.id_ccta_presupuestal
        prod.value.purchaseProcedureId = product.id_proceso_compra
        prod.value.unspscId = product.id_catalogo_unspsc
        prod.value.catPercId = product.id_catalogo_perc
        prod.value.catNicspId = product.id_ccta_nicsp
        prod.value.perishable = product.perecedero_producto
        prod.value.gAndS = product.basico_producto
    }

    const storeProduct = async (obj) => {
        swal({
            title: '¿Está seguro de guardar el nuevo producto?',
            icon: 'question',
            iconHtml: '❓',
            confirmButtonText: 'Si, Guardar',
            confirmButtonColor: '#141368',
            cancelButtonText: 'Cancelar',
            showCancelButton: true,
            showCloseButton: true
        }).then(async (result) => {
            if (result.isConfirmed) {
                saveObject(obj, '/save-product');
            }
        });
    };

    const updateProduct = async (obj) => {
        swal({
            title: '¿Está seguro de actualizar el producto?',
            icon: 'question',
            iconHtml: '❓',
            confirmButtonText: 'Si, Actualizar',
            confirmButtonColor: '#141368',
            cancelButtonText: 'Cancelar',
            showCancelButton: true,
            showCloseButton: true
        }).then(async (result) => {
            if (result.isConfirmed) {
                saveObject(obj, '/update-product');
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
                catUnspsc.value = baseOption.value //refresh the unspsc options
                useShowToast(toast.warning, "Tienes algunos errores, por favor verifica los datos enviados.");
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

    const openUnspsc = () => {
        catUnspsc.value = baseOption.value
    }

    const selectUnspsc = (id) => {
        const selectedUnspsc = catUnspsc.value.filter((e) => e.value == id)
        baseOption.value = selectedUnspsc
    }

    const showErrorMessage = (err) => {
        const { title, text, icon } = useHandleError(err);
        swal({ title: title, text: text, icon: icon, timer: 5000 });
    };

    return {
        errors, isLoadingRequest, prod, purchaseProcedures, catUnspsc, isLoadingUnspsc,
        budgetAccounts, unitsMeasmt, catPerc, catNicsp, baseOption,
        asyncFindUnspsc, getInfoForModalProd, storeProduct, updateProduct, openUnspsc, selectUnspsc
    }
}