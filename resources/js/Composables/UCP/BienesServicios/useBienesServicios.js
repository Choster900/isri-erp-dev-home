import { ref, onMounted } from "vue";
import axios from "axios";
import { executeRequest } from "@/plugins/requestHelpers";
import Swal from "sweetalert2";
export const useBienesServicios = () => {
    const arrayProductoAdquisicion = ref([])
    const idDetDocAdquisicion = ref(null)
    const idLt = ref(null)
    const arrayLineaTrabajo = ref([])
    const arrayDocAdquisicion = ref([])
    const arrayUnidadMedida = ref([])
    const arrayCentroAtencion = ref([])
    const arrayMarca = ref([])
    const productDataSearched = ref(null)
    const arrayWhenIsEditingDocAdq = ref([])

    const objectGetFromProp = ref(null)
    const arrayProductsWhenIsEditable = ref(null)
    /**
     * Agrega una nueva fila de detalle de adquisición a la matriz de productos de adquisición.
     * @param {number} i - Índice de la fila en la que se agregará el detalle de adquisición.
     */
    const addingRows = (i) => {
        try {
            // Verifica si hay datos en la posición i de arrayProductoAdquisicion y si hay documentos de adquisición disponibles
            if (arrayProductoAdquisicion.value[i] && arrayDocAdquisicion.value.length > 0) {
                // Obtiene el compromiso presupuestario del documento de adquisición actual
                //const compromisoPpto = arrayDocAdquisicion.value.find(index => index.value == idDetDocAdquisicion.value)?.dataDoc?.compromiso_ppto_det_doc_adquisicion || '';

                // Agrega un nuevo objeto de detalle de adquisición a la matriz de productos
                arrayProductoAdquisicion.value[i].detalleDoc.push({
                    especifico: '',
                    idProdAdquisicion: '', // [Comment: 'Identificador producto adquisicion por producto]
                    idProducto: '',
                    detalleProducto: '',
                    pesoProducto: '',
                    idCentroAtencion: '',
                    detalleCentro: '',
                    idMarca: '',
                    cantProdAdquisicion: '',
                    costoProdAdquisicion: '',
                    descripcionProdAdquisicion: '',
                    estadoProdAdquisicion: 1,
                    valorTotalProduct: '',
                });

                // Muestra la matriz actualizada en la consola
<<<<<<< HEAD
                console.log("Matriz de productos de adquisición actualizada:", arrayProductoAdquisicion.value);
=======
                //console.log("Matriz de productos de adquisición actualizada:", arrayProductoAdquisicion.value);
>>>>>>> 63fa7ae2fa0221515133457cf9daeb508dfddf8b
            } else {
                // Muestra un mensaje de error si faltan datos para agregar filas
                console.error("No se pueden agregar filas debido a datos faltantes.");
            }
        } catch (error) {
            // Maneja los errores imprimiéndolos en la consola
            console.error("Error al agregar filas:", error);
        }
    };



    /**
    * Agrega un nuevo objeto de documento de adquisición a la matriz de productos de adquisición.
    */
    const addinDocAdquisicion = () => {
        try {
            // Agrega un nuevo objeto de documento de adquisición a la matriz de productos
            arrayProductoAdquisicion.value.push({
                idProdAdquisicion: '', // [Comment: 'Identificador producto adquisicion por linea de trabajo]
                idLt: '',
                vShowLt: true, //
                estadoLt: 1, // [Comment: Estado manejado en 0 => deleted,1 => created,2 =>edited]
                hoverToDelete: false, // [Comment: It´ll add color]
                detalleDoc: [],
            });
<<<<<<< HEAD

            // Muestra la matriz actualizada en la consola
            console.log("Matriz de productos de adquisición actualizada:", arrayProductoAdquisicion.value);
=======
            addingRows(arrayProductoAdquisicion.value.length - 1)
            // Muestra la matriz actualizada en la consola
            //console.log("Matriz de productos de adquisición actualizada:", arrayProductoAdquisicion.value);
>>>>>>> 63fa7ae2fa0221515133457cf9daeb508dfddf8b
        } catch (error) {
            // Maneja los errores imprimiéndolos en la consola
            console.error("Error al agregar documento de adquisición:", error);
        }
    };


    /**
     * Obtener Arrays de objetos para multiselect
     *
     * @returns {Promise<object>} - Promesa que se resuelve con la respuesta del servidor.
     */
    const getArrayObject = async () => {
        try {
            const resp = await axios.post("/get-array-objects-for-multiselect");
<<<<<<< HEAD
            console.log(resp);
            arrayLineaTrabajo.value = resp.data.lineaTrabajo.map(index => {
                return { value: index.id_lt, label: index.nombre_lt };
=======
            arrayLineaTrabajo.value = resp.data.lineaTrabajo.map(index => {
                return { value: index.id_lt, label: index.nombre_lt, disabled: false };
>>>>>>> 63fa7ae2fa0221515133457cf9daeb508dfddf8b
            })
            arrayDocAdquisicion.value = resp.data.detalleDocAdquisicion.map(index => {
                return { value: index.id_det_doc_adquisicion, label: index.nombre_det_doc_adquisicion, dataDoc: index };
            })

            arrayUnidadMedida.value = resp.data.unidadesMedida.map(index => {
                return { value: index.id_unidad_medida, label: index.abreviatura_unidad_medida, dataUnidad: index };
            })
            arrayCentroAtencion.value = resp.data.centrosAtencion.map(index => {
                return { value: index.id_centro_atencion, label: index.codigo_centro_atencion, dataCentro: index };
            })
            arrayMarca.value = resp.data.marca.map(index => {
                return { value: index.id_marca, label: index.nombre_marca, dataMarca: index };
            })
<<<<<<< HEAD
            console.log(arrayLineaTrabajo.value);
=======
>>>>>>> 63fa7ae2fa0221515133457cf9daeb508dfddf8b
        } catch (error) {
            reject(error);
            console.error("Error en la creación de la evaluación personal:", error);
            throw new Error("Error en la creación de la evaluación personal");
        }

    };


    /**
    * Busca producto por codigo.
    *
    * @param {string} productCode - codigo del producto a buscar.
    * @returns {Promise<object>} - Objeto con los datos de la respuesta.
    * @throws {Error} - Error al obtener empleados por nombre.
    */
    const handleProductoSearchByCodigo = async (productCode) => {
        try {
            // Realiza la búsqueda de empleados
            const response = await axios.post(
                "/get-product-by-codigo-producto", {
                codigoProducto: productCode,
            });
            productDataSearched.value = response.data
            return response.data;
        } catch (error) {
            // Manejo de errores específicos
            console.error("Error al obtener empleados por nombre:", error);
            // Lanza el error para que pueda ser manejado por el componente que utiliza este composable
            throw new Error("Error en la búsqueda de empleados");
        }
    };


    /**
     *
     * Configura la información del producto seleccionado.
     * @param {number} productoId - El valor del producto seleccionado.
     * @param {number} rowDocAdq - Índice del documento adquisicion.
     * @param {number} rowDetalleDocAdq - Índice del detalle documento adquisicion.
     */
    const setInformacionProduct = (productoId, rowDocAdq, rowDetalleDocAdq) => {
        try {

            // Encuentra el objeto en productDataSearched que coincida con el valor 'e'
            const selectedProduct = productDataSearched.value.find(produc => produc.value == productoId);

            if (selectedProduct) {
                // Desestructura la información del producto
                const { unidad_medida, precio_producto, nombre_producto, id_ccta_presupuestal } = selectedProduct.allDataProducto;

                // Actualiza el peso del producto en arrayProductoAdquisicion
                arrayProductoAdquisicion.value[rowDocAdq].detalleDoc[rowDetalleDocAdq].pesoProducto = unidad_medida.id_unidad_medida;
                arrayProductoAdquisicion.value[rowDocAdq].detalleDoc[rowDetalleDocAdq].detalleProducto = nombre_producto;

                arrayProductoAdquisicion.value[rowDocAdq].detalleDoc[rowDetalleDocAdq].especifico = id_ccta_presupuestal;

                // Obtén el producto en la fila especificada
                const producto = arrayProductoAdquisicion.value[rowDocAdq].detalleDoc[rowDetalleDocAdq];

                // Verifica que tanto costoProdAdquisicion como cantProdAdquisicion sean números válidos
                const costo = parseFloat(producto.costoProdAdquisicion);
                const cantidad = parseFloat(producto.cantProdAdquisicion);

                // Verifica si los valores son números válidos antes de realizar el cálculo
                if (!isNaN(costo) && !isNaN(cantidad)) {
                    // Realiza el cálculo del valor total y asigna al producto
                    arrayProductoAdquisicion.value[rowDocAdq].detalleDoc[rowDetalleDocAdq].valorTotalProduct = (costo * cantidad);
                } else {
                    // Si alguno de los valores no es un número válido, muestra un mensaje o toma la acción adecuada
                    console.warn("Costo o cantidad no son números válidos. No se pudo calcular el valor total.");
                }
            } else {
                console.log("Producto no encontrado en la lista de productos buscados.");
            }
        } catch (error) {
            console.error("Error al configurar la información del producto:", error);
        }
    };


    /**
     * Calcula el valor total del producto en la fila especificada.
     * @param {number} docAdq - Índice del documento de adquisición.
     * @param {number} detalleDocAdq - Índice del detalle del documento de adquisición.
     */
    const calculateTotal = (docAdq, detalleDocAdq) => {
        try {
            // Obtén el producto en la fila especificada
            const producto = arrayProductoAdquisicion.value[docAdq].detalleDoc[detalleDocAdq];

            // Log de la información del producto antes del cálculo
            console.log(`Información del producto en la fila ${docAdq}, detalle ${detalleDocAdq}:`, producto);

            // Desestructura las propiedades necesarias del producto
            const { cantProdAdquisicion, costoProdAdquisicion } = producto;

            // Log de las cantidades y costos antes del cálculo
<<<<<<< HEAD
            console.log(`Cantidad: ${cantProdAdquisicion}, Costo: ${costoProdAdquisicion}`);
=======
            //console.log(`Cantidad: ${cantProdAdquisicion}, Costo: ${costoProdAdquisicion}`);
>>>>>>> 63fa7ae2fa0221515133457cf9daeb508dfddf8b

            // Realiza el cálculo del valor total y asigna al producto
            const valorTotal = cantProdAdquisicion * costoProdAdquisicion;
            arrayProductoAdquisicion.value[docAdq].detalleDoc[detalleDocAdq].valorTotalProduct = valorTotal;

            // Log del valor total calculado y del producto actualizado
<<<<<<< HEAD
            console.log(`Valor total calculado: ${valorTotal}`);
            console.log("Producto actualizado con el valor total:", arrayProductoAdquisicion.value[docAdq].detalleDoc[detalleDocAdq]);
=======
            // console.log(`Valor total calculado: ${valorTotal}`);
            // console.log("Producto actualizado con el valor total:", arrayProductoAdquisicion.value[docAdq].detalleDoc[detalleDocAdq]);
>>>>>>> 63fa7ae2fa0221515133457cf9daeb508dfddf8b
        } catch (error) {
            // Maneja los errores imprimiéndolos en la consola.
            console.error("Error al calcular el valor total del producto:", error);
        }
    };
    const errorsValidation = ref([]);
    const saveProductAdquisicionRequest = () => {
        return new Promise(async (resolve, reject) => {
            try {
                const response = await axios.post(
                    "/save-prod-adquicicion", {
                    productAdq: arrayProductoAdquisicion.value,
                    idDetDocAdquisicion: idDetDocAdquisicion.value
                });
                this.$emit("actualizar-table-data");
                resolve(response); // Resolvemos la promesa con la respuesta exitosa
            } catch (error) {
                if (error.response.status === 422) {

                    let data = error.response.data.errors
                    var myData = new Object();
                    for (const errorBack in data) {
                        myData[errorBack] = data[errorBack][0]
                    }
                    errorsValidation.value = myData
                    setTimeout(() => {
                        errorsValidation.value = [];
                    }, 5000);
                    console.error("Error en guardar los datos:", error);
                }
                // Manejo de errores específicos
                reject(error); // Rechazamos la promesa en caso de excepción
            }
        });
    }

    /**
   * Guarda productos adquisicion
   *
   * @param {string} productCode - codigo del producto a buscar.
   * @returns {Promise<object>} - Objeto con los datos de la respuesta.
   * @throws {Error} - Error al obtener empleados por nombre.
   */
    const saveProductAdquisicion = async () => {
        const confirmed = await Swal.fire({
            title: '<p class="text-[18pt] text-center">¿Esta seguro de guardar el anexo?</p>',
            icon: "question",
            iconHtml: `<lord-icon src="https://cdn.lordicon.com/enzmygww.json" trigger="loop" delay="500" colors="primary:#121331" style="width:100px;height:100px"></lord-icon>`,
            confirmButtonText: "Si, Editar",
            confirmButtonColor: "#001b47",
            cancelButtonText: "Cancelar",
            showCancelButton: true,
            showCloseButton: true,
        });
        if (confirmed.isConfirmed) {
            executeRequest(
                saveProductAdquisicionRequest(),
                "¡El documento de adquisicion se ha guardado correctamente!"
            );
        }
    }


    const updateProductAdquisicionRequest = () => {
        return new Promise(async (resolve, reject) => {
            try {
                const response = await axios.post(
                    "/update-prod-adquicicion", {
                    productAdq: arrayProductoAdquisicion.value,
                    idDetDocAdquisicion: idDetDocAdquisicion.value
                });
                resolve(response); // Resolvemos la promesa con la respuesta exitosa
            } catch (error) {
                if (error.response.status === 422) {

                    let data = error.response.data.errors
                    var myData = new Object();
                    for (const errorBack in data) {
                        myData[errorBack] = data[errorBack][0]
                    }
                    errorsValidation.value = myData
                    setTimeout(() => {
                        errorsValidation.value = [];
                    }, 5000);
                    console.error("Error en guardar los datos:", error);
                }
                // Manejo de errores específicos
                reject(error); // Rechazamos la promesa en caso de excepción
            }
        });
    };

    /**
     * Edita productos adquisicion
     *
     * @param {string} productCode - codigo del producto a buscar.
     * @returns {Promise<object>} - Objeto con los datos de la respuesta.
     * @throws {Error} - Error al obtener empleados por nombre.
     */
    const updateProductAdquisicion = async () => {
        const confirmed = await Swal.fire({
            title: '<p class="text-[18pt] text-center">¿Esta seguro de editar?</p>',
            icon: "question",
            iconHtml: `<lord-icon src="https://cdn.lordicon.com/enzmygww.json" trigger="loop" delay="500" colors="primary:#121331" style="width:100px;height:100px"></lord-icon>`,
            confirmButtonText: "Si, Editar",
            confirmButtonColor: "#001b47",
            cancelButtonText: "Cancelar",
            showCancelButton: true,
            showCloseButton: true,
        });
        if (confirmed.isConfirmed) {
            executeRequest(
                updateProductAdquisicionRequest(),
                "¡El documento de adquisicion se ha actualizado correctamente!"
            );
        }
    }

<<<<<<< HEAD
=======
    const disableLt = (e = null) => {
        // Obtener la línea de trabajo seleccionada
        const selectedLineaTrabajo = arrayLineaTrabajo.value[e - 1];

        if (selectedLineaTrabajo) {

            selectedLineaTrabajo.disabled = true;
        }

        // Si no existe, habilitar todas las opciones y deshabilitar la seleccionada
        arrayLineaTrabajo.value.forEach((item) => {
            const existe = arrayProductoAdquisicion.value.some((index) => item.value === index.idLt);
            if (!existe) {
                item.disabled = false;
            } else {
                item.disabled = true;
            }
        });

    };

>>>>>>> 63fa7ae2fa0221515133457cf9daeb508dfddf8b
    onMounted(() => {
        getArrayObject()
    })
    return {
<<<<<<< HEAD
=======
        disableLt,
>>>>>>> 63fa7ae2fa0221515133457cf9daeb508dfddf8b
        updateProductAdquisicion,
        errorsValidation,
        addinDocAdquisicion,
        saveProductAdquisicion,
        arrayProductoAdquisicion,
        calculateTotal,
        idDetDocAdquisicion,
        idLt,
        arrayProductsWhenIsEditable,
        arrayLineaTrabajo,
        arrayDocAdquisicion,
        objectGetFromProp,
        arrayUnidadMedida,
        arrayMarca,
        arrayCentroAtencion,
        productDataSearched,
        addingRows,
        arrayWhenIsEditingDocAdq,
        handleProductoSearchByCodigo,
        setInformacionProduct,
    }
}
