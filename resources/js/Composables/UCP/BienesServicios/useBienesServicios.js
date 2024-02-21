import { ref, onMounted } from "vue";
import axios from "axios";
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
                    idProducto: '',
                    detalleProducto: '',
                    pesoProducto: '',
                    idCentroAtencion: '',
                    detalleCentro: '',
                    idMarca: '',
                    cantProdAdquisicion: '',
                    costoProdAdquisicion: '',
                    descripcionProdAdquisicion: '',
                    estadoProdAdquisicion: '',
                    valorTotalProduct: '',
                });

                // Muestra la matriz actualizada en la consola
                console.log("Matriz de productos de adquisición actualizada:", arrayProductoAdquisicion.value);
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
                idLt: '',
                detalleDoc: [],
            });

            // Muestra la matriz actualizada en la consola
            console.log("Matriz de productos de adquisición actualizada:", arrayProductoAdquisicion.value);
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
            console.log(resp);
            arrayLineaTrabajo.value = resp.data.lineaTrabajo.map(index => {
                return { value: index.id_lt, label: index.nombre_lt };
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
            console.log(arrayLineaTrabajo.value);
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
            console.log(`Cantidad: ${cantProdAdquisicion}, Costo: ${costoProdAdquisicion}`);

            // Realiza el cálculo del valor total y asigna al producto
            const valorTotal = cantProdAdquisicion * costoProdAdquisicion;
            arrayProductoAdquisicion.value[docAdq].detalleDoc[detalleDocAdq].valorTotalProduct = valorTotal;

            // Log del valor total calculado y del producto actualizado
            console.log(`Valor total calculado: ${valorTotal}`);
            console.log("Producto actualizado con el valor total:", arrayProductoAdquisicion.value[docAdq].detalleDoc[detalleDocAdq]);
        } catch (error) {
            // Maneja los errores imprimiéndolos en la consola.
            console.error("Error al calcular el valor total del producto:", error);
        }
    };


    /**
   * Busca producto por codigo.
   *
   * @param {string} productCode - codigo del producto a buscar.
   * @returns {Promise<object>} - Objeto con los datos de la respuesta.
   * @throws {Error} - Error al obtener empleados por nombre.
   */
    const saveProductAdquisicion = async () => {
        try {
            // Realiza la búsqueda de empleados
            const response = await axios.post(
                "/save-prod-adquicicion", {
                productAdq: arrayProductoAdquisicion.value,
                idDetDocAdquisicion: idDetDocAdquisicion.value
            });
            console.log(response);
        } catch (error) {
            // Manejo de errores específicos
            console.error("Error en guardar los datos:", error);
            // Lanza el error para que pueda ser manejado por el componente que utiliza este composable
            throw new Error("Error ");
        }
    };

    const updateDescripcion = (event) => {
        this.detalle.descripcionProdAdquisicion = event.target.textContent;
    }

    onMounted(() => {
        getArrayObject()
    })
    return {
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
        handleProductoSearchByCodigo,
        setInformacionProduct,
    }
}
