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
    const productDataSearched = ref(null)
    const addingRows = () => {
        arrayProductoAdquisicion.value.push(
            {
                //id_det_doc_adquisicion: '',
                //id_lt: '',
                especifico: arrayDocAdquisicion.value.find(index => index.value == idDetDocAdquisicion.value).dataDoc.compromiso_ppto_det_doc_adquisicion,
                idProducto: '',
                detalleProducto: '',
                pesoProducto: '',

                idCentroAtencion: '',
                detalleCentro: '',

                cantProdAdquisicion: '',
                costoProdAdquisicion: '',
                descripcionProdAdquisicion: '',
                estadoProdAdquisicion: '',
                valorTotalProduct: '',
            }
        );
    }


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
     * Configura la información del producto seleccionado.
     * @param {number} e - El valor del producto seleccionado.
     * @param {number} row - Índice de la fila en la que se encuentra el producto.
     */
    const setInformacionProduct = (e, row) => {
        try {
            // Encuentra el objeto en productDataSearched que coincida con el valor 'e'
            const selectedProduct = productDataSearched.value.find(produc => produc.value == e);
            if (selectedProduct) {
                // Desestructura la información del producto
                const { unidad_medida, precio_producto, nombre_producto } = selectedProduct.allDataProducto;
                // Actualiza el peso del producto en arrayProductoAdquisicion
                arrayProductoAdquisicion.value[row].pesoProducto = unidad_medida.id_unidad_medida;
                arrayProductoAdquisicion.value[row].detalleProducto = nombre_producto;
               /*  arrayProductoAdquisicion.value[row].costoProdAdquisicion = precio_producto; */
                arrayProductoAdquisicion.value[row].valorTotalProduct = (arrayProductoAdquisicion.value[row].costoProdAdquisicion * arrayProductoAdquisicion.value[row].cantProdAdquisicion).toFixed(2);
            } else {
                console.log("Producto no encontrado en la lista de productos buscados.");
            }
        } catch (error) {
            console.error("Error al configurar la información del producto:", error);
        }
    };


    /**
     * Calcula el valor total del producto en la fila especificada.
     * @param {number} row - Índice de la fila en la que se encuentra el producto.
     */
    const calculateTotal = (row) => {
        try {
            // Obtiene el producto en la fila especificada
            const producto = arrayProductoAdquisicion.value[row];

            // Realiza el cálculo del valor total
            const valorTotal = (producto.costoProdAdquisicion * producto.cantProdAdquisicion).toFixed(2);

            // Asigna el valor total al producto en la fila
            producto.valorTotalProduct = valorTotal;

            // Log del producto actualizado con el valor total
            console.log("Producto actualizado con el valor total:", producto);
        } catch (error) {
            // Maneja los errores imprimiéndolos en la consola.
            console.error("Error al calcular el valor total del producto:", error);
        }
    };

    onMounted(() => {
        getArrayObject()
    })
    return {
        arrayProductoAdquisicion,
        calculateTotal,
        idDetDocAdquisicion,
        idLt,
        arrayLineaTrabajo,
        arrayDocAdquisicion,
        arrayUnidadMedida,
        arrayCentroAtencion,
        productDataSearched,
        addingRows,
        handleProductoSearchByCodigo,
        setInformacionProduct,
    }
}
