import { ref, inject, computed, nextTick, watch } from "vue";
import axios from "axios";
import { useHandleError } from "@/Composables/General/useHandleError.js";
import { useShowToast } from "@/Composables/General/useShowToast.js";
import { useFormatDateTime } from "@/Composables/General/useFormatDateTime.js";
import { useValidateInput } from '@/Composables/General/useValidateInput';
import { toast } from "vue3-toastify";
import _ from "lodash";

export const useAjusteEntrada = (context) => {
    const swal = inject("$swal");
    const isLoadingRequest = ref(false);
    const isLoadingProduct = ref(false);
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
        prodId: '', //product identifier for global search
        idLt: '',
        number: '',
        expDate: '',
        perishable: '',
        observation: '',
        status: '',
        prods: []
    })

    /**
    * Utility method to format date for DateTimePickerM display.
    *
    * @function formatDateVue3DP
    * @returns {string} Formatted date string suitable for DateTimePickerM display.
    */
    const {
        formatDateVue3DP
    } = useFormatDateTime()

    /**
    * Utility method to validate input using specified validation rules.
    *
    * @function validateInput
    * @param {String} input - The input to be validated.
    * @param {Object} validationRule - The validation rule object specifying the validation criteria.
    * @returns {String} The validated input value.
    */
    const {
        validateInput
    } = useValidateInput()


    /**
    * Handles input validation, optionally targeting a specific element within an array of inputs.
    *
    * @function handleValidation
    * @param {string} input - The name of the input field to validate.
    * @param {Object} validation - The validation rule object specifying the validation criteria.
    * @param {Object} element - Optional. The element object containing index information for array inputs.
    */
    const handleValidation = (input, validation, element) => {
        if (element) {
            // If element is provided, validate the specific input within the array.
            adjustment.value.prods[element.index][input] = validateInput(adjustment.value.prods[element.index][input], validation)
        } else {
            // Otherwise, validate the input directly.
            adjustment.value[input] = validateInput(adjustment.value[input], validation)
        }
    }


    /**
    * Retrieves initial information to populate the adjustment modal based on the given ID.
    * If the ID is 0, it indicates a new element creation; otherwise, it's an edit operation.
    *
    * @async
    * @function getInfoForModalAdjustment
    * @param {number} id - The ID of the adjustment element.
    */
    const getInfoForModalAdjustment = async (id) => {
        try {
            // Set loading state while fetching data.
            isLoadingRequest.value = true;
            // Fetch data from the server based on the ID.
            const response = await axios.post(`/get-info-modal-shortage-adjustment`, {
                id: id,
            });
            // Set modal values based on the response data.
            setModalValues(response.data, id);
        } catch (err) {
            // Handle errors
            if (err.response && err.response.data.logical_error) {
                // Show logical error message and emit event to refresh the table.
                useShowToast(toast.error, err.response.data.logical_error);
                context.emit("get-table");
            } else {
                // Show generic error message and close the modal.
                showErrorMessage(err);
                context.emit("cerrar-modal");
            }
        } finally {
            // Reset loading state after request completes.
            isLoadingRequest.value = false;
        }
    };

    /**
    * Sets modal values based on the provided data.
    *
    * @function setModalValues
    * @param {Object} data - The data object containing modal information.
    * @param {number} id - The ID of the adjustment element.
    */
    const setModalValues = (data, id) => {
        const req = data.req;

        // Set general values.
        setGeneralValues(data, id);

        // If editing an existing element, set adjustment-specific values and scroll to the last row.
        if (id > 0) {
            setAdjustmentValues(req);
            scrollToLastRow();
        }
    }

    /**
    * Sets general values used in the adjustment modal.
    *
    * @function setGeneralValues
    * @param {Object} data - The data object containing general information.
    * @param {number} id - The ID of the adjustment element.
    */
    const setGeneralValues = (data, id) => {
        adjustment.value.status = id > 0 ? data.req.id_estado_req : 1;
        reasons.value = data.reasons;
        centers.value = data.centers;
        financingSources.value = data.financingSources;
        lts.value = data.lts;
        brands.value = data.brands;
    }

    /**
    * Sets adjustment-specific values based on the provided request data.
    *
    * @function setAdjustmentValues
    * @param {Object} req - The request data object containing adjustment details.
    */
    const setAdjustmentValues = (req) => {
        adjustment.value.id = req.id_requerimiento;
        adjustment.value.centerId = req.id_centro_atencion;
        adjustment.value.financingSourceId = req.id_proy_financiado;
        adjustment.value.reasonId = req.id_motivo_ajuste;
        adjustment.value.idLt = req.id_lt;
        adjustment.value.number = req.num_requerimiento;
        adjustment.value.observation = req.observacion_requerimiento ?? '';

        // Iterate through request details to construct product arrays for adjustment.
        req.detalles_requerimiento.forEach(element => {
            if (element.estado_det_requerimiento === 1) {
                const array = constructArrayFromElement(element);
                adjustment.value.prods.push(array);
            }
        });
    }

    /**
    * Constructs an array from the given element object.
    *
    * @function constructArrayFromElement
    * @param {Object} element - The element object containing product details.
    * @returns {Object} The constructed array containing product information.
    */
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


    /**
    * Scrolls the page to a specific element identified by its ID.
    *
    * @function scrollToElement
    * @param {string} elementId - The ID of the element to scroll to.
    */
    const scrollToElement = (elementId) => {
        // Asynchronously execute the scrolling operation on the next tick.
        nextTick(() => {
            // Find the DOM element with the specified ID.
            const element = document.getElementById(elementId);
            // If the element is found, scroll to it smoothly.
            if (element) {
                element.scrollIntoView({ behavior: 'smooth', block: 'end' });
            }
        });
    }

    /**
    * Scrolls the page to the last row element.
    *
    * @function scrollToLastRow
    */
    const scrollToLastRow = () => {
        // Scroll to the element with ID 'total', typically the last row.
        scrollToElement('total');
    }

    /**
    * Scrolls the page to the top.
    *
    * @function returnToTop
    */
    const returnToTop = () => {
        // Scroll to the element with ID 'headerFormat', usually the top of the page.
        scrollToElement('headerFormat');
    }

    /**
     * Scrolls the page to the last row element when inserting a new row.
     *
     * @function scrollToLastRowWhenInsert
     */
    const scrollToLastRowWhenInsert = () => {
        // Asynchronously execute the scrolling operation on the next tick.
        nextTick(() => {
            // Generate the ID of the new row element based on the current number of rows.
            const newRowId = `row-${adjustment.value.prods.length - 1}`;
            // Find the DOM element with the generated ID.
            const newRowElement = document.getElementById(newRowId);
            // If the element is found, scroll to it smoothly and apply a blinking animation.
            if (newRowElement) {
                newRowElement.scrollIntoView({ behavior: 'smooth', block: 'end' });
                applyBlinkingAnimation(newRowElement);
            }
        });
    }

    /**
    * Method to convert a floating-point value with two decimals to an integer
    *
    * @function floatToInt
    * @param {string} value - The value we want to convert
    * @returns {number} The converted value.
    */
    const floatToInt = (value) => {
        // First, we convert the value to a floating-point number
        const floatValue = parseFloat(value);
        // Then, we round it to the nearest integer
        const roundedValue = Math.round(floatValue);
        // Return the result as an integer
        return roundedValue;
    }

    /**
    * Handles the selection of a product. Adds the selected product to the adjustment array.
    *
    * @function selectProd
    * @param {string} prodId - The ID of the selected product.
    */
    const selectProd = (prodId) => {
        // Check if a product has been selected
        if (!prodId) {
            // Show a toast message if no product is selected
            useShowToast(toast.info, "Debes elegir un producto de la lista.");
            return;
        }

        // Find the selected product
        const selectedProd = products.value.find((e) => e.value === prodId);
        // Construct an array from the selected product information
        const array = constructArrayFromProduct(selectedProd.allInfo, prodId);
        // Add the array to the adjustment array
        adjustment.value.prods.push(array);

        // Scroll to the last row after insertion
        scrollToLastRowWhenInsert();

        // Clear the product selection
        adjustment.value.prodId = '';
    }

    /**
    * Constructs an array with product information for insertion into the adjustment array.
    *
    * @function constructArrayFromProduct
    * @param {Object} productInfo - Information about the selected product.
    * @param {string} prodId - The ID of the selected product.
    * @returns {Object} An array containing product details.
    */
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

    /**
    * Applies a blinking animation to the specified HTML element for 3 seconds.
    *
    * @function applyBlinkingAnimation
    * @param {HTMLElement} element - The HTML element to which the animation will be applied.
    */
    const applyBlinkingAnimation = (element) => {
        // Add the 'blinking' class to the element to trigger the animation
        element.classList.add('blinking');
        // Remove the 'blinking' class after 3 seconds to stop the animation
        setTimeout(() => {
            element.classList.remove('blinking');
        }, 3000); // 3000 milliseconds (3 seconds)
    }

    /**
    * Performs an asynchronous search for products with debounce functionality.
    * Debouncing ensures that the search function is only called after a certain delay
    * to prevent rapid consecutive calls, enhancing performance.
    *
    * @function asyncFindProduct
    * @param {string} query - The search query.
    */
    const asyncFindProduct = _.debounce(async (query) => {
        try {
            // Set loading state while fetching products.
            isLoadingProduct.value = true;
            // Check if the query length meets the minimum requirement for search.
            if (query.length >= 3) {
                // Fetch products matching the query from the server.
                const response = await axios.post("/search-donation-product", {
                    busqueda: query,
                });
                // Update the products list with the response data.
                products.value = response.data.products;
            }
        } catch (errors) {
            // Reset products list in case of errors.
            products.value = [];
        } finally {
            // Reset loading state after the search operation completes.
            isLoadingProduct.value = false;
        }
    }, 350); // Debounce delay of 350 milliseconds


    /**
    * Handles actions when the financing source selection changes.
    * Depending on the selected financing source, it may perform specific actions.
    *
    * @function changeFinancingSource
    * @param {number} id - The ID of the selected financing source.
    */
    const changeFinancingSource = (id) => {
        if (id) {
            // Check if a financing source is selected
            if (id === 4) { // If the selected source is 'Donation'
                // Reset the ID of the linked transaction
                adjustment.value.idLt = '';
            }
        } else {
            // Reset the financing source ID if none is selected
            adjustment.value.financingSourceId = '';
        }
    }

    /**
    * Deletes a row from the array of products.
    * If the row has no detail record ID (detRecId), it means the product is not yet saved in the database,
    * so it's removed from the array. Otherwise, the 'deleted' property of the product is set to true,
    * indicating that it's temporarily marked for deletion.
    *
    * @function deleteRow
    * @param {number} index - The index of the row to be deleted.
    * @param {string} detRecId - The detail record ID of the row.
    */
    const deleteRow = (index, detRecId) => {
        // Check if there is at least one active detail row
        if (activeDetails.value.length <= 1) {
            // Show a warning message if trying to delete all rows
            useShowToast(toast.warning, "No puedes eliminar todas las filas.");
        } else {
            // Display a confirmation dialog for deleting the product
            swal({
                title: 'Eliminar producto.',
                text: "¿Estas seguro de eliminar temporalmente el producto? Los cambios surtiran efecto al actualizar el ajuste.",
                icon: 'warning',
                showCancelButton: true,
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#DC2626',
                cancelButtonColor: '#4B5563',
                confirmButtonText: 'Si, Eliminar.'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show a success message upon successful deletion
                    useShowToast(toast.success, "Producto eliminado temporalmente.");
                    // Check if the product is not saved in the database
                    if (detRecId === "") {
                        // Remove the product from the array
                        adjustment.value.prods.splice(index, 1);
                    } else {
                        // Mark the product as deleted by setting the 'deleted' property to true
                        adjustment.value.prods[index].deleted = true;
                    }
                }
            });
        }
    }

    /**
    * Displays a confirmation dialog before saving a new adjustment.
    * If confirmed, it calls the saveObject method to store the adjustment.
    *
    * @function storeAdjustment
    * @param {Object} obj - The adjustment object to be saved.
    */
    const storeAdjustment = async (obj) => {
        // Display a confirmation dialog for saving a new adjustment
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
                // If confirmed, call saveObject to store the adjustment
                saveObject(obj, '/save-shortage-adjustment-info');
            }
        });
    };

    /**
    * Displays a confirmation dialog before updating an existing adjustment.
    * If confirmed, it calls the saveObject method to update the adjustment.
    *
    * @function updateAdjustment
    * @param {Object} obj - The adjustment object to be updated.
    */
    const updateAdjustment = async (obj) => {
        // Display a confirmation dialog for updating the adjustment
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
                // If confirmed, call saveObject to update the adjustment
                saveObject(obj, '/update-shortage-adjustment-info');
            }
        });
    };

    /**
    * Saves the object by making a POST request to the specified URL.
    * Handles success and error responses accordingly.
    *
    * @function saveObject
    * @param {Object} obj - The object to be saved.
    * @param {string} url - The URL to which the object will be sent for saving.
    */
    const saveObject = async (obj, url) => {
        // Set loading state while saving the object
        isLoadingRequest.value = true;
        // Make a POST request to save the object
        await axios
            .post(url, obj)
            .then((response) => {
                // Handle success response
                handleSuccessResponse(response);
            })
            .catch((error) => {
                // Handle error response
                handleErrorResponse(error);
            })
            .finally(() => {
                // Reset loading state after request completes
                isLoadingRequest.value = false;
            });
    };

    /**
    * Handles error responses from the server.
    * Shows appropriate error messages based on the response status.
    *
    * @function handleErrorResponse
    * @param {Error} err - The error object containing the response status and data.
    */
    const handleErrorResponse = (err) => {
        if (err.response.status === 422) {
            // If validation error, show error messages
            if (err.response && err.response.data.logical_error) {
                useShowToast(toast.error, err.response.data.logical_error);
            } else {
                useShowToast(toast.warning, "You have errors in your data, please verify and try again.");
                errors.value = err.response.data.errors;
                // Clear errors after 5 seconds
                setTimeout(() => {
                    errors.value = [];
                }, 5000);
            }
        } else {
            // For other errors, show a generic error message
            showErrorMessage(err);
            context.emit("cerrar-modal");
        }
    };

    /**
    * Handles success responses from the server.
    * Shows a success message and emits events to close the modal and refresh the table.
    *
    * @function handleSuccessResponse
    * @param {Object} response - The response object containing the success message.
    */
    const handleSuccessResponse = (response) => {
        // Show a success message
        useShowToast(toast.success, response.data.message);
        // Close the modal and refresh the table
        context.emit("cerrar-modal");
        context.emit("get-table");
    };

    /**
    * Shows an error message using swal based on the provided error object.
    *
    * @function showErrorMessage
    * @param {Error} err - The error object containing title, text, and icon properties.
    */
    const showErrorMessage = (err) => {
        const { title, text, icon } = useHandleError(err);
        swal({ title: title, text: text, icon: icon, timer: 5000 });
    };

    /**
     * Computes the total amount of the adjustment based on the products in the adjustment array.
     * It filters out deleted products, calculates their total amounts, and sums them up.
     *
     */
    const totalRec = computed(() => {
        let sum = 0;
        // Iterate through the products in the adjustment array
        for (let i = 0; i < adjustment.value.prods.length; i++) {
            // Check if the product is not marked as deleted
            if (adjustment.value.prods[i].deleted == false) {
                let amount = parseFloat(adjustment.value.prods[i].total);
                // Check if the total amount is a valid number
                if (!isNaN(amount)) {
                    // Add the amount to the sum
                    sum += amount;
                }
            }
        }
        // Set the total amount in the adjustment object
        adjustment.value.total = sum.toFixed(2);
        // Return the computed total amount
        return sum.toFixed(2);
    });

    /**
     * Computes the array of active details by filtering out deleted products.
     *
     */
    const activeDetails = computed(() => {
        // Filter out deleted products from the adjustment array
        return adjustment.value.prods.filter((detail) => detail.deleted == false);
    });

    /**
    * Watches for changes in the 'adjustment' object and recalculates the 'total' property of each product.
    * It multiplies the quantity and cost of each product to update its total amount.
    *
    * @param {Ref<Object>} adjustment - The reactive reference to the 'adjustment' object.
    * @param {Function} callback - The callback function to execute when changes occur.
    * @param {Object} options - Additional options for the watcher (deep: true to watch nested properties).
    */
    watch(adjustment, (newValue) => {
        // Iterate through each product in the 'prods' array of the 'adjustment' object
        newValue.prods.forEach((prod) => {
            // Calculate the total amount for the product (quantity * cost)
            prod.total = (prod.qty * prod.cost).toFixed(2);
        });
    }, { deep: true });


    return {
        isLoadingRequest, errors, reasons, centers, financingSources, lts, adjustment,
        products, brands, asyncFindProduct, totalRec, activeDetails, isLoadingProduct,
        getInfoForModalAdjustment, selectProd, deleteRow, storeAdjustment, updateAdjustment, handleValidation,
        changeFinancingSource, returnToTop
    }
}