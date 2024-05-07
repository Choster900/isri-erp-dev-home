import { ref, inject, computed, nextTick, watch } from "vue";
import axios from "axios";
import { useHandleError } from "@/Composables/General/useHandleError.js";
import { useShowToast } from "@/Composables/General/useShowToast.js";
import { toast } from "vue3-toastify";
import { useFormatDateTime } from "@/Composables/General/useFormatDateTime.js";
import { useValidateInput } from '@/Composables/General/useValidateInput';
//import { localeData } from 'moment_spanish_locale';
import moment from 'moment';
import _ from "lodash";
//moment.locale('es', localeData)

export const useDonacion = (context) => {
    const swal = inject("$swal");
    const isLoadingRequest = ref(false);
    const errors = ref([]);

    const isLoadingProduct = ref(false);
    const products = ref([])
    const centers = ref([])
    const suppliers = ref([])
    const brands = ref([])

    const donInfo = ref({
        id: '', //reception id
        acta: '', //Acta number
        dateTime: '', //Donation date and time
        prodId: '', //product identifier for global search
        supplierId: '', //supplier
        nit: '', // supplier nit
        dui: '',
        centerId: '', //Healthcare center
        observation: '', //Reception observation
        status: '', //We use this to manage some functionalities in the view, it represent the reception status
        total: '', //This is the sum of all products
        prods: [], //Array of products
    })

    const {
        formatDateVue3DP
    } = useFormatDateTime()

    const {
        validateInput
    } = useValidateInput()

    const handleValidation = (input, validation, element) => {
        if (element) {
            donInfo.value.prods[element.index][input] = validateInput(donInfo.value.prods[element.index][input], validation)
        } else {
            donInfo.value[input] = validateInput(donInfo.value[input], validation)
        }
    }

    const getInfoForModalDonation = async (id) => {
        try {
            isLoadingRequest.value = true;
            const response = await axios.post(
                `/get-info-modal-donation`, {
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
        const recepData = data.recep
        //Set the general information to show in the view

        suppliers.value = data.suppliers
        centers.value = data.centers
        brands.value = data.brands

        donInfo.value.status = id > 0 ? recepData.id_estado_recepcion_pedido : 1
        donInfo.value.dateTime = recepData ? moment(recepData.fecha_reg_recepcion_pedido).format('DD/MM/YYYY, HH:mm:ss') : ''

        // Check if id > 0
        if (id > 0) {
            donInfo.value.id = recepData.id_recepcion_pedido //Set reception id
            donInfo.value.acta = recepData.acta_recepcion_pedido //Set acta number
            donInfo.value.invoice = recepData.factura_recepcion_pedido //Set invoice number
            donInfo.value.observation = recepData.observacion_recepcion_pedido //Set observation
            donInfo.value.supplierId = recepData.id_proveedor // Set supplier
            donInfo.value.nit = recepData.proveedor.nit_proveedor //Set supplier nit
            donInfo.value.dui = recepData.proveedor.dui_proveedor //Set supplier nit
            donInfo.value.centerId = recepData.detalle_recepcion[0].id_centro_atencion //healthcare center


            // Iterate over detalle_recepcion
            recepData.detalle_recepcion.forEach(element => {
                // Check estado_det_recepcion_pedido
                if (element.estado_det_recepcion_pedido === 1) {
                    // Construct array
                    const array = {
                        detRecId: element.id_det_recepcion_pedido, //id_det_recepcion_pedido
                        prodId: element.id_producto, //id_product
                        brandId: element.id_marca,
                        brandLabel: element.marca.nombre_marca,
                        perishable: element.producto.perecedero_producto,
                        expDate: formatDateVue3DP(element.fecha_vcto_det_recepcion_pedido), //Expiry date
                        fractionated: element.producto.fraccionado_producto,
                        desc: element.producto.codigo_producto + ' — ' + element.producto.nombre_completo_producto + ' — ' + element.producto.unidad_medida.nombre_unidad_medida,
                        qty: element.producto.fraccionado_producto == 0 ? floatToInt(element.cant_det_recepcion_pedido) : element.cant_det_recepcion_pedido, //Represents the the number of products the user wants to register
                        cost: parseFloat(element.costo_det_recepcion_pedido).toFixed(2), //Represents the the cost of the product
                        total: "", //Represents the result of qty x cost for every row
                        deleted: false, //This is the state of the row, it represents the logical deletion.
                    };
                    // Push array to prods
                    donInfo.value.prods.push(array);
                }
            });
            // Desplazar la pantalla hasta la última fila agregada
            nextTick(() => {
                const newRowElement = document.getElementById(`total`);
                if (newRowElement) {
                    newRowElement.scrollIntoView({ behavior: 'smooth', block: 'end' });
                }
            });
        }
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

    const deleteRow = (index, detRecId) => {
        if (activeDetails.value.length <= 1) {
            useShowToast(toast.warning, "No puedes eliminar todas las filas.");
        } else {
            swal({
                title: 'Eliminar producto.',
                text: "¿Estas seguro de eliminar temporalmente el producto? Los cambios surtiran efecto al actualizar la donacion.",
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
                        donInfo.value.prods.splice(index, 1);
                    } else {
                        donInfo.value.prods[index].deleted = true
                    }
                }
            });
        }
    }

    const storeReception = async (obj) => {
        swal({
            title: '¿Está seguro de guardar nueva donación de productos?',
            icon: 'question',
            iconHtml: '❓',
            confirmButtonText: 'Si, Guardar',
            confirmButtonColor: '#141368',
            cancelButtonText: 'Cancelar',
            showCancelButton: true,
            showCloseButton: true
        }).then(async (result) => {
            if (result.isConfirmed) {
                saveObject(obj, '/save-donation-info');
            }
        });
    };

    const updateReception = async (obj) => {
        swal({
            title: '¿Está seguro de actualizar la donación de productos?',
            icon: 'question',
            iconHtml: '❓',
            confirmButtonText: 'Si, Actualizar',
            confirmButtonColor: '#141368',
            cancelButtonText: 'Cancelar',
            showCancelButton: true,
            showCloseButton: true
        }).then(async (result) => {
            if (result.isConfirmed) {
                saveObject(obj, '/update-donation-info');
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

    const activeDetails = computed(() => {
        return donInfo.value.prods.filter((detail) => detail.deleted == false)
    });

    const asyncFindProduct = _.debounce(async (query) => {
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

    const selectProv = (id) => {
        const selectedProv = suppliers.value.find((e) => e.value == id);
        donInfo.value.nit = selectedProv.nit_proveedor
        donInfo.value.dui = selectedProv.dui_proveedor
    }

    const selectProd = (prodId) => {
        if (!prodId) {
            useShowToast(toast.info, "Debes elegir un producto de la lista.");
        } else {
            const selectedProd = products.value.find((e) => e.value === prodId)
            // Construct array
            const array = {
                detRecId: '', //id_det_recepcion_pedido
                prodId: prodId, //id_prod_adquisicion
                desc: selectedProd.allInfo.codigo_producto + ' — ' + selectedProd.allInfo.nombre_completo_producto + ' — ' + selectedProd.allInfo.unidad_medida.nombre_unidad_medida,
                brandId: '',
                brandLabel: '',
                prodLabel: '',
                expDate: '',
                fractionated: selectedProd.allInfo.fraccionado_producto,
                perishable: selectedProd.allInfo.perecedero_producto, //If the product is perishable, set to true, otherwise set to false.
                qty: '', //Represents the the number of products the user wants to register
                cost: '', //Represents the the cost of the product
                total: '', //Represents the result of qty x cost for every row
                deleted: false, //This is the state of the row, it represents the logical deletion.
            };
            donInfo.value.prods.push(array);

            // Desplazar la pantalla hasta la última fila agregada
            nextTick(() => {
                const newRowId = `row-${donInfo.value.prods.length-1}`;
                const newRowElement = document.getElementById(newRowId);
                if (newRowElement) {
                    newRowElement.scrollIntoView({ behavior: 'smooth', block: 'end' });
                    // Aplicar la clase de animación temporalmente
                    newRowElement.classList.add('blinking');
                    // Eliminar la clase después de un período de tiempo
                    setTimeout(() => {
                        newRowElement.classList.remove('blinking');
                    }, 3000); // 3000 milisegundos (3 segundos) en este ejemplo
                }
            });

            //Clean the select
            donInfo.value.prodId = ''
        }
    }

    const totalRec = computed(() => {
        let sum = 0
        for (let i = 0; i < donInfo.value.prods.length; i++) {
            if (donInfo.value.prods[i].deleted == false) {
                let amount = parseFloat(donInfo.value.prods[i].total);
                if (!isNaN(amount)) {
                    sum += amount;
                }
            }
        }
        donInfo.value.total = sum.toFixed(2);
        return sum.toFixed(2);
    });

    // Observa cambios en las propiedades qty y cost de cada producto
    watch(donInfo, (newValue) => {
        newValue.prods.forEach((prod) => {
            prod.total = (prod.qty * prod.cost).toFixed(2);
        });
    }, { deep: true });

    const showErrorMessage = (err) => {
        const { title, text, icon } = useHandleError(err);
        swal({ title: title, text: text, icon: icon, timer: 5000 });
    };

    return {
        isLoadingRequest, errors, donInfo, suppliers, products, centers,
        asyncFindProduct, isLoadingProduct, totalRec, brands, activeDetails, 
        getInfoForModalDonation, selectProv, selectProd, returnToTop,
        deleteRow, storeReception, updateReception, handleValidation
    }
}