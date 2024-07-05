import { ref, inject, computed } from "vue";
import axios from "axios";
import { useHandleError } from "@/Composables/General/useHandleError.js";
import { useShowToast } from "@/Composables/General/useShowToast.js";
import { toast } from "vue3-toastify";
import _ from "lodash";

export const useProductoAlmacen = (context) => {
    //General parameters
    const swal = inject("$swal");
    const isLoadingRequest = ref(false);
    const showTooltipCAT =  ref(false)
    const showTooltipUAT =  ref(false)
    const errors = ref([]);

    //Selects data
    const catPerc = ref([])
    const subWarehouses = ref([])

    //Product info
    const prod = ref({
        id: '',
        fullName: '',
        code: '',
        unitM : '',
        purchaseProcedure: '',
        mUnitId: '',
        createdAt: '',
        updatedAt: '',
        price: '',
        status: '',
        catPercId: '',
        subWarehouseId: '',
        perishable: -1,
    })

    const getInfoForModalProd = async (id) => {
        try {
            isLoadingRequest.value = true;
            const response = await axios.get(
                `/get-info-modal-prod-almacen/${id}`
            );
            catPerc.value = response.data.catPerc
            subWarehouses.value = response.data.subWarehouses
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

    const setModalValues = (product) => {
        prod.value.id = product.id_producto
        prod.value.code = product.codigo_producto
        prod.value.fullName = product.nombre_completo_producto
        prod.value.unitM = product.unidad_medida.nombre_unidad_medida
        prod.value.createdAt = product.fecha_reg_producto
        prod.value.updatedAt = product.fecha_mod_producto
        prod.value.purchaseProcedure = product.proceso_compra.id_proceso_compra + ' - ' + product.proceso_compra.nombre_proceso_compra
        prod.value.mUnitId = product.unidad_medida.id_unidad_medida
        prod.value.price = '$' + product.precio_producto
        prod.value.catPercId = product.id_catalogo_perc
        prod.value.subWarehouseId = product.id_sub_almacen
        prod.value.status = product.estado_producto
        prod.value.perishable = product.perecedero_producto
    }

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
                saveObject(obj, '/update-product-almacen');
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

    const showErrorMessage = (err) => {
        const { title, text, icon } = useHandleError(err);
        swal({ title: title, text: text, icon: icon, timer: 5000 });
    };

    return {
        errors, isLoadingRequest, prod,
        catPerc, subWarehouses, showTooltipCAT, showTooltipUAT,
        getInfoForModalProd, updateProduct
    }
}