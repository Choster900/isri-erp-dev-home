import { ref, inject, computed, nextTick, watch } from "vue";
import axios from "axios";
import { useHandleError } from "@/Composables/General/useHandleError.js";
import { useShowToast } from "@/Composables/General/useShowToast.js";
import { toast } from "vue3-toastify";
import { useFormatDateTime } from "@/Composables/General/useFormatDateTime.js";
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
    const selectedProducts = ref([])

    const donInfo = ref({
        id: '', //reception id
        acta: '', //Acta number
        dateTime: '', //Donation date and time
        supplierId: '', //supplier
        nit: '', // supplier nit
        centerId: '', //Healthcare center
        observation: '', //Reception observation
        status: '', //We use this to manage some functionalities in the view, it represent the reception status
        total: '', //This is the sum of all products
        prods: [], //Array of products
    })

    const {
        formatDateVue3DP
    } = useFormatDateTime()

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
            donInfo.value.centerId = recepData.detalle_recepcion[0].id_centro_atencion //healthcare center


            // Iterate over detalle_recepcion
            recepData.detalle_recepcion.forEach(element => {
                // Check estado_det_recepcion_pedido
                if (element.estado_det_recepcion_pedido === 1) {
                    //Construct the options
                    let arrayOpt = {
                        value: element.producto.id_producto,
                        label: element.producto.codigo_producto + " - " + element.producto.nombre_producto,
                    };
                    selectedProducts.value.push(arrayOpt);
                    products.value.push(arrayOpt);

                    // Construct array
                    const array = {
                        detRecId: element.id_det_recepcion_pedido, //id_det_recepcion_pedido
                        prodId: element.id_producto, //id_product
                        isLoadingProd: false, //Flag to manage loader for every multiselect
                        brandId: element.id_marca,
                        perishable: element.producto.perecedero_producto,
                        expDate: formatDateVue3DP(element.fecha_vcto_det_recepcion_pedido), //Expiry date
                        //Product description
                        desc: element.producto.codigo_producto + ' — ' + element.producto.nombre_producto + ' — ' + element.producto.descripcion_producto + ' — ' + element.producto.unidad_medida.nombre_unidad_medida,
                        qty: element.cant_det_recepcion_pedido, //Represents the the number of products the user wants to register
                        cost: element.costo_det_recepcion_pedido, //Represents the the cost of the product
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
        } else {
            // Call addNewRow
            addNewRow();
        }
    }

    const addNewRow = () => {
        let array = {
            detRecId: "",
            prodId: "",
            isLoadingProd: false,
            perishable: "",
            expDate: "",
            brandId: "",
            desc: "",
            qty: "",
            cost: "",
            total: '0.00',
            deleted: false,
        }
        donInfo.value.prods.push(array);

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
                products.value = selectedProducts.value
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

    const asyncFindProduct = _.debounce(async (query, index, prodId) => {
        try {
            donInfo.value.prods[index].isLoadingProd = true
            if (query.length >= 3) {
                // Filtrar los elementos de defaultProds que tengan un valor diferente a prodId
                //const filteredProds = donInfo.value.prods.filter((e) => e.prodId !== prodId && e.deleted == false);
                // Crear un array no asociativo con solo las propiedades 'value' de los elementos filtrados
                //const prodIdToIgnore = filteredProds.map((e) => e.prodId); //Productos que debe ignorar al momento de realizar la busqueda asincrona
                const response = await axios.post("/search-donation-product", {
                    busqueda: query,
                    //prodIdToIgnore: prodIdToIgnore
                });
                products.value = response.data.products;
            } else {
                products.value = [];
            }
        } catch (errors) {
            products.value = [];
        } finally {
            donInfo.value.prods[index].isLoadingProd = false
        }
    }, 350);

    const openAnySelect = (prodId) => {
        // const selectedProd = defaultProds.value.filter((e) => e.value === prodId)
        // products.value = selectedProd
        products.value = []
    }

    const selectProv = (id) => {
        const selectedProv = suppliers.value.find((e) => e.value == id);
        donInfo.value.nit = selectedProv.nit_proveedor
    }

    const selectProd = (prodId, index) => {
        if (prodId) {
            const selectedProd = products.value.find((e) => e.value === prodId)
            donInfo.value.prods[index].desc = selectedProd.allInfo.codigo_producto + ' — ' + selectedProd.allInfo.nombre_producto + ' — ' + selectedProd.allInfo.descripcion_producto + ' — ' + selectedProd.allInfo.unidad_medida.nombre_unidad_medida
            donInfo.value.prods[index].perishable = selectedProd.allInfo.perecedero_producto
            let arrayOpt = {
                value: selectedProd.allInfo.id_producto,
                label: selectedProd.allInfo.codigo_producto + " - " + selectedProd.allInfo.nombre_producto,
            };
            selectedProducts.value.push(arrayOpt);
        } else {
            donInfo.value.prods[index].prodId = ''
            donInfo.value.prods[index].desc = ''
            donInfo.value.prods[index].perishable = ''
        }
        donInfo.value.prods[index].brandId = ''
        donInfo.value.prods[index].qty = ''
        donInfo.value.prods[index].cost = ''
        donInfo.value.prods[index].expDate = ''
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
        asyncFindProduct, isLoadingProduct, totalRec, brands, selectedProducts,
        getInfoForModalDonation, selectProv, openAnySelect, selectProd, addNewRow,
        deleteRow, storeReception, updateReception
    }
}