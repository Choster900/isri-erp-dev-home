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
    const errorsValidation = ref([]);

    const objectGetFromProp = ref(null)
    const arrayProductsWhenIsEditable = ref(null)

    const estadoDocAdq = ref(1)
    const tipoProcesoCompra = ref(null)

    const ArrayProductFiltered = ref([])

    // Se creó esta variable 'brandsUsedInDoc' para almacenar información que se enviará al documento PDF.
    // Esto se hace con el propósito de evitar el procesamiento lento al enviar el documento completo de marcas.
    const brandsUsedInDoc = ref(null)
    const loader = ref(null)

    const letterNumber = ref(null)
    const totProductos = ref(null)

    /**
     * Agrega una nueva fila de detalle de adquisición a la matriz de productos de adquisición.
     * @param {number} i - Índice de la fila en la que se agregará el detalle de adquisición.
     */
    const addingRows = (i) => {
        try {
            // Verifica si hay datos en la posición i de arrayProductoAdquisicion y si hay documentos de adquisición disponibles
            if (arrayProductoAdquisicion.value[i] && arrayDocAdquisicion.value.length > 0) {

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
                    cantProdAdquisicion: 0,
                    costoProdAdquisicion: 0,
                    descripcionProdAdquisicion: '',
                    estadoProdAdquisicion: 1,
                    valorTotalProduct: 0,
                });

                // Muestra la matriz actualizada en la consola
                //console.log("Matriz de productos de adquisición actualizada:", arrayProductoAdquisicion.value);
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
            addingRows(arrayProductoAdquisicion.value.length - 1)
            // Muestra la matriz actualizada en la consola
            //console.log("Matriz de productos de adquisición actualizada:", arrayProductoAdquisicion.value);
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
            const resp = await axios.post("/get-array-objects-for-multiselect", {});
            arrayLineaTrabajo.value = resp.data.lineaTrabajo.map(index => {
                return { value: index.id_lt, label: `${index.codigo_up_lt} - ${index.nombre_lt}`, disabled: false };
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
     * Al seleccionar producto toma tuda su informacion y la setea a la fila correspoendiente
     * @param {number} productoId - El id del producto seleccionado.
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
            //console.log(`Cantidad: ${cantProdAdquisicion}, Costo: ${costoProdAdquisicion}`);

            // Realiza el cálculo del valor total y asigna al producto
            const valorTotal = cantProdAdquisicion * costoProdAdquisicion;
            arrayProductoAdquisicion.value[docAdq].detalleDoc[detalleDocAdq].valorTotalProduct = valorTotal;
            sumatorioTotalProduct()
        } catch (error) {
            // Maneja los errores imprimiéndolos en la consola.
            console.error("Error al calcular el valor total del producto:", error);
        }
    };

    const sumatorioTotalProduct = () => {
        let suma = []
        arrayProductoAdquisicion.value.forEach(element => {
            element.detalleDoc.forEach(element2 => {
                suma.push(element2)
            });
        });

        let sumaTotal = suma.reduce((acumulador, producto) => acumulador + producto.valorTotalProduct, 0);
        console.log("La suma total de los valores de los productos es: " + sumaTotal);
        totProductos.value = sumaTotal
        getTextForNumber(sumaTotal)
    }

    const loadingNumberLetter = ref(false)
      /**
    * Convertir numeros a letras
    *
    * @param {string} number - numero a convertir
    * @returns {Promise<object>} - Objeto con los datos de la respuesta.
    * @throws {Error} - Error al obtener empleados por nombre.
    */
      const getTextForNumber = async (number) => {
        try {
            loadingNumberLetter.value = true;
            // Realiza la búsqueda de empleados
            const response = await axios.post(
                "/convert-numbers-to-string", {
                    number: number,
            });
            console.log(response);
            letterNumber.value = response.data
            //return response.data;
        } catch (error) {
            // Manejo de errores específicos
            console.error("Error al obtener empleados por nombre:", error);
            // Lanza el error para que pueda ser manejado por el componente que utiliza este composable
            throw new Error("Error en la búsqueda de empleados");
        } finally {
            loadingNumberLetter.value = false;
        }
    };

    /**
     * Guarda productos adquisición en el servidor.
     *
     * @returns {Promise<object>} - Promesa que se resuelve con la respuesta exitosa o se rechaza con el error.
     */
    const saveProductAdquisicionRequest = () => {
        return new Promise(async (resolve, reject) => {
            try {
                // Hace una solicitud POST a la ruta "/save-prod-adquicicion" con los datos necesarios
                const response = await axios.post(
                    "/save-prod-adquicicion", {
                    productAdq: arrayProductoAdquisicion.value,
                    idDetDocAdquisicion: idDetDocAdquisicion.value
                });
                // Se resuelve la promesa con la respuesta exitosa de la solicitud
                resolve(response);
            } catch (error) {
                console.log(error);
                // Si hay un error y el código de respuesta es 422 (Unprocessable Entity), maneja los errores de validación
                if (error.response.status === 422) {
                    // Obtiene los errores del cuerpo de la respuesta y los transforma a un formato más manejable
                    let data = error.response.data.errors;
                    var myData = new Object();
                    for (const errorBack in data) {
                        myData[errorBack] = data[errorBack][0];
                    }
                    // Actualiza el estado "errorsValidation" con los errores y los limpia después de 5 segundos
                    errorsValidation.value = myData;
                    setTimeout(() => {
                        errorsValidation.value = [];
                    }, 5000);
                    console.error("Error en guardar los datos:", error);
                }
                // Rechaza la promesa en caso de excepción
                reject(error);
            }
        });
    }

    /**
     * Actualiza productos de adquisición en el servidor.
     *
     * @returns {Promise<object>} - Promesa que se resuelve con la respuesta exitosa o se rechaza con el error.
     */
    const updateProductAdquisicionRequest = () => {
        return new Promise(async (resolve, reject) => {
            try {
                // Hace una solicitud POST a la ruta "/update-prod-adquicicion" con los datos necesarios
                const response = await axios.post("/update-prod-adquicicion", {
                    productAdq: arrayProductoAdquisicion.value,
                    idDetDocAdquisicion: idDetDocAdquisicion.value,
                });
                // Se resuelve la promesa con la respuesta exitosa de la solicitud
                resolve(response);
            } catch (error) {
                // Si hay un error y el código de respuesta es 422 (Unprocessable Entity), maneja los errores de validación
                if (error.response.status === 422) {
                    // Obtiene los errores del cuerpo de la respuesta y los transforma a un formato más manejable
                    let data = error.response.data.errors;
                    var myData = new Object();
                    for (const errorBack in data) {
                        myData[errorBack] = data[errorBack][0];
                    }
                    // Actualiza el estado "errorsValidation" con los errores y los limpia después de 5 segundos
                    errorsValidation.value = myData;
                    setTimeout(() => {
                        errorsValidation.value = [];
                    }, 5000);
                    console.error("Error en guardar los datos:", error);
                }
                // Rechaza la promesa en caso de excepción
                reject(error);
            }
        });
    };

    /**
     * Deshabilita una línea de trabajo específica y habilita las demás en el array.
     *
     * @param {number} e - Índice de la línea de trabajo a deshabilitar (opcional).
     */
    const disableLt = (e = null) => {
        // Obtener la línea de trabajo seleccionada
        const selectedLineaTrabajo = arrayLineaTrabajo.value[e - 1];

        // Si la línea de trabajo seleccionada existe, la deshabilita
        if (selectedLineaTrabajo) {
            selectedLineaTrabajo.disabled = true;
        }

        // Recorre todas las líneas de trabajo en el array
        arrayLineaTrabajo.value.forEach((item) => {
            // Verifica si la línea de trabajo actual existe en el arrayProductoAdquisicion
            const existe = arrayProductoAdquisicion.value.some((index) => item.value === index.idLt);

            // Si no existe en el arrayProductoAdquisicion, habilita la línea de trabajo
            if (!existe) {
                item.disabled = false;
            } else {
                // Si existe, la deshabilita
                item.disabled = true;
            }
        });
    };

    /**
     * Función para eliminar temporalmente un producto de adquisición.
     *
     * @param {number} indexDdLT - Índice de la línea de trabajo a la que pertenece el producto.
     * @param {number} indexProdAdq - Índice del producto de adquisición a eliminar.
     * @returns {Promise<void>} - Promesa que indica si el producto fue eliminado o no.
     */
    const deleteProductAdq = async (indexDdLT, indexProdAdq) => {
        // Muestra un cuadro de diálogo de confirmación usando la biblioteca Swal
        const confirmedEliminarProd = await Swal.fire({
            title: '<p class="text-[15pt]">¿Eliminar producto adquisicion?. </p>',
            text: "Al confirmar esta acción, el producto de adquisición se eliminará temporalmente. Recuerde que los cambios serán permanentes una vez que guarde.",
            icon: "question",
            iconHtml: `<lord-icon src="https://cdn.lordicon.com/enzmygww.json" trigger="loop" delay="500" colors="primary:#121331" style="width:100px;height:100px"></lord-icon>`,
            confirmButtonText: `<p class="text-[10pt] text-center">Si, Eliminar</p>`,
            confirmButtonColor: "#D2691E",
            cancelButtonText: `<p class="text-[10pt] text-center">Cancelar</p>`,
            showCancelButton: true,
            showCloseButton: true,
        });

        // Verifica si el usuario confirmó la eliminación
        if (confirmedEliminarProd.isConfirmed) {
            // Accede al array de productos de adquisición y actualiza el estado del producto a "eliminado" (estadoProdAdquisicion = 0)
            arrayProductoAdquisicion.value.find(index => index.idLt == indexDdLT).detalleDoc[indexProdAdq].estadoProdAdquisicion = 0;
        }
    };

    /**
     * Función para eliminar de forma permanente una línea de trabajo y sus productos de adquisición asociados.
     *
     * @param {number} indexDdLT - Índice de la línea de trabajo a eliminar.
     * @returns {Promise<void>} - Promesa que indica si la línea de trabajo fue eliminada o no.
     */
    const deletLineaTrabajo = async (indexDdLT) => {
        // Muestra un cuadro de diálogo de confirmación usando la biblioteca Swal
        const confirmedEliminarLinea = await Swal.fire({
            title: '<p class="text-[18pt] text-center">¿Eliminar linea de trabajo?.</p>',
            text: 'Al confirmar esta acción, se eliminará de manera permanente la línea y cada producto de adquisición asociado a esta línea será eliminado de forma irreversible.',
            icon: "question",
            iconHtml: `<lord-icon src="https://cdn.lordicon.com/enzmygww.json" trigger="loop" delay="500" colors="primary:#121331" style="width:100px;height:100px"></lord-icon>`,
            confirmButtonText: `<p class="text-[10pt] text-center">Si, Eliminar</p>`,
            confirmButtonColor: "#D2691E",
            cancelButtonText: `<p class="text-[10pt] text-center">Cancelar</p>`,
            showCancelButton: true,
            showCloseButton: true,
        });

        // Verifica si el usuario confirmó la eliminación
        if (confirmedEliminarLinea.isConfirmed) {
            // Accede al array de productos de adquisición y actualiza el estado de la línea de trabajo a "eliminado" (estadoLt = 0)
            arrayProductoAdquisicion.value[indexDdLT].estadoLt = 0;
        }
    };

    /**
     *
     * @description Funcion para obtener los productos por proceso del item seleccionado.
     * @param {number} valueDocument - El valor del documento de adquisición seleccionado.
     * @returns {Promise<void>} - Una promesa que se resuelve después de completar la operación.
     */
    const onSelectDocAdquisicion = async (valueDocument) => {
        console.log({ valueDocument });
        console.log(arrayDocAdquisicion.value);

        try {
            loader.value = true;
            productDataSearched.value = []
            // Obtiene el ID del proceso de compra asociado al documento de adquisición seleccionado.
            const procesoId = arrayDocAdquisicion.value.find(d => d.value === valueDocument)?.dataDoc?.documento_adquisicion?.id_proceso_compra;
            console.log(procesoId);
            console.log(arrayDocAdquisicion.value);
            if (!procesoId) {
                throw new Error("ID del proceso de compra no encontrado");
            }

            // Realiza una solicitud para obtener los productos asociados al proceso de compra.
            const resp = await axios.post("/get-product-by-proceso", { procesoId });

            // Actualiza el valor de productDataSearched con la información obtenida.
            productDataSearched.value = resp.data;
        } catch (error) {
            // Manejo de errores: rechaza la promesa, muestra un mensaje de error en la consola y lanza una excepción.
            console.error("Error en la obtención de productos por proceso de adquisicion:", error);
            throw new Error("Error en obtener los productos por proceso de adquisicion: " + error.message);
        } finally {
            loader.value = false;
        }
    };



    // Se llama a la función getArrayObject() cuando el componente se monta para realizar alguna lógica específica.
    onMounted(() => {
        getArrayObject();
    });

    return {
        deleteProductAdq,
        totProductos,
        letterNumber,
        onSelectDocAdquisicion,
        deletLineaTrabajo,
        disableLt,
        ArrayProductFiltered,
        loader,
        errorsValidation,
        addinDocAdquisicion,
        arrayProductoAdquisicion,
        calculateTotal,
        idDetDocAdquisicion,
        idLt,
        arrayProductsWhenIsEditable,
        saveProductAdquisicionRequest,
        estadoDocAdq,
        arrayLineaTrabajo,
        arrayDocAdquisicion,
        objectGetFromProp,
        arrayUnidadMedida,
        arrayMarca,
        updateProductAdquisicionRequest,
        arrayCentroAtencion,
        productDataSearched,
        addingRows,
        arrayWhenIsEditingDocAdq,
        sumatorioTotalProduct,
        brandsUsedInDoc,
        loadingNumberLetter,
        handleProductoSearchByCodigo,
        setInformacionProduct,
    }
}
