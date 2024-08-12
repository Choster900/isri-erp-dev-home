import { ref, onMounted, watch, computed, reactive } from "vue";
import axios from "axios";
import Swal from "sweetalert2";
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
import { useToCalculate } from "@/Composables/General/useToCalculate";
/**
 * Hook personalizado para la gestión de bienes y servicios.
 * Este hook se encarga de realizar operaciones relacionadas con la gestión de bienes y servicios,
 * como la carga de datos, envío de solicitudes y manejo de eventos.
 * @param {Object} propProdAdquisicion - Propiedad que contiene los datos de adquisición de productos.
 * @param {Boolean} showModal - Indica si se muestra el modal de bienes y servicios.
 * @param {String} typeDoc - Tipo de documento relacionado con la adquisición.
 * @returns {Object} - Retorna un objeto con los datos y funciones relacionadas con la gestión de bienes y servicios.
 */
export const useBienesServicios = (propProdAdquisicion, showModal, typeDoc) => {
    const arrayProductoAdquisicion = ref([])
    const idDetDocAdquisicion = ref(null)
    const observacionDetDocAdquisicion = ref(null)
    const recepcionDetDocAdquisicion = ref(null)
    const notificacionDetDocAdquisicion = ref(null)
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

    const ArrayProductFiltered = ref([])

    // Se creó esta variable 'brandsUsedInDoc' para almacenar información que se enviará al documento PDF.
    // Esto se hace con el propósito de evitar el procesamiento lento al enviar el documento completo de marcas.
    const brandsUsedInDoc = ref(null)
    const loader = ref(null)

    const letterNumber = ref(null)
    const totProductos = ref(null)

    const tipoCostoDetDocAdquisicion = ref(null)

    const { round2Decimals } = useToCalculate()

    /**
     * Agrega una nueva fila de detalle de adquisición a la matriz de productos de adquisición.
     * @param {number} i - Índice de la fila en la que se agregará el detalle de adquisición.
     */
    const addingRows = (i) => {
        try {
            // Verifica si hay datos en la posición i de arrayProductoAdquisicion y si hay documentos de adquisición disponibles
            if (arrayProductoAdquisicion.value[i]) {

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
                    cantProdAdquisicion: null,
                    costoProdAdquisicion: null,
                    descripcionProdAdquisicion: '',
                    estadoProdAdquisicion: 1,
                    valorTotalProduct: 0,
                    amountsPerMonthList: {}

                });
                // Actualiza la paginación después de agregar una nueva fila
                initializePagination(i, true);
                // Muestra la matriz actualizada en la consola
            } else {
                // Muestra un mensaje de error si faltan datos para agregar filas
                console.error("No se pueden agregar filas debido a datos faltantes (No hay documento de adquisicion).");
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
                paginationDetalleDoc: [], // TODO : PAGINATION
                currentPage: 1, // TODO : PAGINATION
            });
            addingRows(arrayProductoAdquisicion.value.length - 1)
            // Muestra la matriz actualizada en la consola
        } catch (error) {
            // Maneja los errores imprimiéndolos en la consola
            console.error("Error al agregar documento de adquisición:", error);
        }
    };


    // Definimos una constante global para la cantidad de elementos por página
    const ITEMS_PER_PAGE = 3;

    /**
    * Inicializa la paginación para un documento de adquisición.
    * @param {number} docIndex - Índice del documento de adquisición.
    * @param {boolean} goToLastPage - Indica si se debe ir a la última página.
    */
    const initializePagination = (docIndex, goToLastPage = false) => {
        const doc = arrayProductoAdquisicion.value[docIndex];
        const totalItems = doc.detalleDoc.length;
        const totalPages = Math.ceil(totalItems / ITEMS_PER_PAGE);

        let start, end;

        if (goToLastPage) {
            start = (totalPages - 1) * ITEMS_PER_PAGE;
            end = totalItems;
            doc.currentPage = totalPages;
        } else {
            start = 0;
            end = ITEMS_PER_PAGE;
            doc.currentPage = 1;
        }

        doc.paginationDetalleDoc = doc.detalleDoc.slice(start, end);
    };

    /**
    * Avanza a la siguiente página de detalles de adquisición.
    * @param {number} docIndex - Índice del documento de adquisición.
    */
    const nextPage = (docIndex) => {
        const doc = arrayProductoAdquisicion.value[docIndex];
        const start = doc.currentPage * ITEMS_PER_PAGE;
        const end = start + ITEMS_PER_PAGE;
        if (start < doc.detalleDoc.length) {
            doc.paginationDetalleDoc = doc.detalleDoc.slice(start, end);
            doc.currentPage++;
        }
    };

    /**
    * Retrocede a la página anterior de detalles de adquisición.
    * @param {number} docIndex - Índice del documento de adquisición.
    */
    const prevPage = (docIndex) => {
        const doc = arrayProductoAdquisicion.value[docIndex];
        if (doc.currentPage > 1) {
            const start = (doc.currentPage - 2) * ITEMS_PER_PAGE;
            const end = start + ITEMS_PER_PAGE;
            doc.paginationDetalleDoc = doc.detalleDoc.slice(start, end);
            doc.currentPage--;
        }
    };


    /**
     * Obtener Arrays de objetos para multiselect
     *
     * @returns {Promise<object>} - Promesa que se resuelve con la respuesta del servidor.
     */
    const getArrayObject = async () => {
        try {
            const resp = await axios.post("/get-array-objects-for-multiselect", { tipoDoc: typeDoc });
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
                const { unidad_medida, precio_producto, nombre_producto, id_ccta_presupuestal, nombre_completo_producto, codigo_producto } = selectedProduct.allDataProducto;

                // Actualiza el peso del producto en arrayProductoAdquisicion
                arrayProductoAdquisicion.value[rowDocAdq].detalleDoc[rowDetalleDocAdq].pesoProducto = unidad_medida.id_unidad_medida;
                arrayProductoAdquisicion.value[rowDocAdq].detalleDoc[rowDetalleDocAdq].detalleProducto = codigo_producto + '-' + nombre_completo_producto + ' - ' + unidad_medida.nombre_unidad_medida;

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

    let timer = null;
    /**
    * Calcula el valor total del producto en la fila especificada.
    * @param {number} lineaTrabajo - Índice del documento de adquisición.
    * @param {number} producto - Índice del detalle (producto) del documento de adquisición.
    */
    const handleInput = (lineaTrabajo, producto) => {
        clearTimeout(timer); // Reinicia el temporizador en cada entrada de texto
        timer = setTimeout(() => {
            calculateTotal(lineaTrabajo, producto); // Ejecuta la función después de 2 segundos
        }, 2000);
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

            // Desestructura las propiedades necesarias del producto
            const { cantProdAdquisicion, costoProdAdquisicion, valorTotalProduct } = producto;

            // Realiza el cálculo del valor total o del costo según el tipo de costo
            if (!tipoCostoDetDocAdquisicion.value) {
                // Calculo del valor total
                producto.valorTotalProduct = cantProdAdquisicion * costoProdAdquisicion;
            } else {
                // Calculo del costo unitario
                producto.costoProdAdquisicion = (valorTotalProduct / cantProdAdquisicion).toFixed(6);
            }

            // Actualiza el valor total de los productos
            calculateTotalProductValue();
        } catch (error) {
            // Maneja los errores imprimiéndolos en la consola.
            console.error("Error al calcular el valor total del producto:", error);
        }
    };



    /**
     * Calculates the total sum of all product values within the acquisition document.
     * Iterates through each product in `arrayProductoAdquisicion` and their respective details,
     * sums up the total value of all products, and updates `totProductos` with the total.
     * Additionally, it formats the total to two decimal places and calls `getTextForNumber` with the total sum.
     */
    const calculateTotalProductValue = () => {
        // Flatten the array of product details
        const productDetails = arrayProductoAdquisicion.value.flatMap(element => element.detalleDoc);

        // Calculate the total sum of all product values
        const totalSum = productDetails.reduce((accumulator, product) => parseFloat(accumulator) + parseFloat(product.valorTotalProduct), 0);

        // Round the total sum to two decimal places and update the total products value
        const roundedTotal = round2Decimals(totalSum);
        totProductos.value = roundedTotal;
        // Call the function to get the text representation of the total sum
        getTextForNumber(roundedTotal);
    };



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
                    idDetDocAdquisicion: idDetDocAdquisicion.value,
                    notificacionDetDocAdquisicion: notificacionDetDocAdquisicion.value,
                    recepcionDetDocAdquisicion: recepcionDetDocAdquisicion.value,
                    observacionDetDocAdquisicion: observacionDetDocAdquisicion.value,
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
                    tipoCostoDetDocAdquisicion: tipoCostoDetDocAdquisicion.value,
                    notificacionDetDocAdquisicion: notificacionDetDocAdquisicion.value,
                    recepcionDetDocAdquisicion: recepcionDetDocAdquisicion.value,
                    observacionDetDocAdquisicion: observacionDetDocAdquisicion.value,
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

        try {
            loader.value = true;
            productDataSearched.value = []
            // Obtiene el ID del proceso de compra asociado al documento de adquisición seleccionado.
            const procesoId = arrayDocAdquisicion.value.find(d => d.value === valueDocument)?.dataDoc?.documento_adquisicion?.id_proceso_compra;

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


    watch(showModal, (newValue, oldValue) => {
        // Verifica si showModal se ha establecido en falso (se cerró el modal)
        if (!newValue) {
            // Restablecer los valores a nulos o vacíos
            objectGetFromProp.value = []
            arrayProductoAdquisicion.value = []
            arrayWhenIsEditingDocAdq.value = []
            productDataSearched.value = []
            // Encuentra el índice del objeto que tiene el valor específico en la propiedad "value"
            const indexAEliminar = arrayDocAdquisicion.value.findIndex(item => item.value === idDetDocAdquisicion.value);
            // Si el índice es encontrado (diferente de -1), elimina ese elemento del array
            if (indexAEliminar !== -1) {
                arrayDocAdquisicion.value.splice(indexAEliminar, 1);
            }
            observacionDetDocAdquisicion.value = ''
            recepcionDetDocAdquisicion.value = ''
            notificacionDetDocAdquisicion.value = ''
            idDetDocAdquisicion.value = null
            estadoDocAdq.value = 1
            arrayLineaTrabajo.value.forEach((item) => {
                item.disabled = false;
            });
            totProductos.value = null
            letterNumber.value = null
            tipoCostoDetDocAdquisicion.value = null
        }
    });

    watch(propProdAdquisicion, (newValue, oldValue) => {
        // Verifica si showModal se ha establecido en falso (se cerró el modal)
        if (newValue !== null && newValue !== undefined && (Array.isArray(newValue) ? newValue.length > 0 : newValue !== '')) {
            // Utiliza el patrón de objeto "guard" para simplificar las verificaciones
            objectGetFromProp.value = newValue;

            observacionDetDocAdquisicion.value = objectGetFromProp.value.observacion_det_doc_adquisicion
            recepcionDetDocAdquisicion.value = objectGetFromProp.value.recepcion_det_doc_adquisicion
            notificacionDetDocAdquisicion.value = objectGetFromProp.value.notificacion_det_doc_adquisicion

            tipoCostoDetDocAdquisicion.value = objectGetFromProp.value.tipo_costo_det_doc_adquisicion == 1 ? false : true

            let productosAdquisiciones = objectGetFromProp.value.productos_adquisiciones;
            // Utilizando un conjunto para rastrear los id_lt únicos
            let idLtSet = new Set();
            let productArray = new Set();
            let brandArray = new Set();

            estadoDocAdq.value = objectGetFromProp.value.id_estado_doc_adquisicion

            arrayDocAdquisicion.value.push({
                value: objectGetFromProp.value.id_det_doc_adquisicion,
                label: objectGetFromProp.value.nombre_det_doc_adquisicion,
                dataDoc: objectGetFromProp.value
            })

            // Utiliza map en lugar de reduce para simplificar la lógica
            brandsUsedInDoc.value = productosAdquisiciones.map(producto => {
                let idMarca = producto.id_marca;

                if (!brandArray.has(idMarca)) {
                    brandArray.add(idMarca);
                    const marca = producto.marca;
                    return {
                        value: marca.id_marca,
                        label: marca.nombre_marca
                    };
                }
            }).filter(Boolean)  // Filtra los elementos nulos o indefinidos
            // Utiliza map en lugar de reduce para simplificar la lógica
            arrayProductsWhenIsEditable.value = productosAdquisiciones.map(producto => {
                let idProduct = producto.id_producto;

                if (!productArray.has(idProduct)) {
                    productArray.add(idProduct);
                    const product = producto.producto;
                    return {
                        value: product.id_producto,
                        label: product.codigo_producto
                    };
                }
            }).filter(Boolean)  // Filtra los elementos nulos o indefinidos

            // si se cuenta mas de 10 producto adquisiciones oculatar tuplas por linea de trabajo para mas ergonomia
            let show = productosAdquisiciones.length > 9 ? false : true
            // Utiliza map y filter para mejorar la legibilidad y reducir código duplicado
            arrayProductoAdquisicion.value = productosAdquisiciones
                .map(producto => {
                    let idLt = producto.id_lt;
                    if (!idLtSet.has(idLt)) {
                        idLtSet.add(idLt);
                        return {
                            idProdAdquisicion: producto.id_prod_adquisicion,
                            idLt: idLt,
                            vShowLt: show,
                            hoverToDelete: false,
                            estadoLt: 2, // [Comment: Estado manejado en 0 => deleted,1 => created,2 =>edited]
                            detalleDoc: [],
                            paginationDetalleDocAdquisicion: [], // Inicializa la paginación
                            currentPage: 1, // Inicializa la página actual
                        };
                    }
                })
                .filter(Boolean);

            // Utiliza forEach en lugar de map cuando no necesitas un nuevo arreglo resultante
            productosAdquisiciones.forEach(index => {
                let indice = arrayProductoAdquisicion.value.findIndex(i => i.idLt == index.id_lt);
                const { producto } = index;
                idDetDocAdquisicion.value = index.id_det_doc_adquisicion

                let newArray = {
                    January: parseFloat(index.cant_ene_prod_adquisicion),
                    February: parseFloat(index.cant_feb_prod_adquisicion),
                    March: parseFloat(index.cant_mar_prod_adquisicion),
                    April: parseFloat(index.cant_abr_prod_adquisicion),
                    May: parseFloat(index.cant_may_prod_adquisicion),
                    June: parseFloat(index.cant_jun_prod_adquisicion),
                    July: parseFloat(index.cant_jul_prod_adquisicion),
                    August: parseFloat(index.cant_ago_prod_adquisicion),
                    September: parseFloat(index.cant_sept_prod_adquisicion),
                    October: parseFloat(index.cant_oct_prod_adquisicion),
                    November: parseFloat(index.cant_nov_prod_adquisicion),
                    December: parseFloat(index.cant_dic_prod_adquisicion),
                };


                arrayProductoAdquisicion.value[indice]["detalleDoc"].push({
                    idProdAdquisicion: index.id_prod_adquisicion,
                    especifico: producto.id_ccta_presupuestal,
                    idProducto: producto.id_producto,
                    detalleProducto: producto.codigo_producto + '-' + producto.nombre_completo_producto + ' - ' + producto.unidad_medida.nombre_unidad_medida,
                    pesoProducto: producto.unidad_medida.id_unidad_medida,
                    idCentroAtencion: index.id_centro_atencion,
                    detalleCentro: index.centro_atencion.nombre_centro_atencion,
                    idMarca: index.id_marca,
                    cantProdAdquisicion: index.cant_prod_adquisicion,
                    costoProdAdquisicion: index.costo_prod_adquisicion,
                    descripcionProdAdquisicion: index.descripcion_prod_adquisicion,
                    estadoProdAdquisicion: 2, // [Comment: Estado manejado en 0 => deleted,1 => created,2 =>edited]
                    valorTotalProduct: round2Decimals(index.cant_prod_adquisicion * index.costo_prod_adquisicion),
                    amountsPerMonthList: newArray
                });
            });
            onSelectDocAdquisicion(idDetDocAdquisicion.value)
            calculateTotalProductValue()
            disableLt()

            // Inicializa la paginación para cada documento de adquisición
            arrayProductoAdquisicion.value.forEach((_, index) => initializePagination(index));


        } else {
            objectGetFromProp.value = [];
            arrayProductoAdquisicion.value = []
            addinDocAdquisicion()
        }
    });

    /*
     ESTO ES NUEVO. POR FAVOR MOVER A MODAL BIENES Y SERVICIOS
     (QUE ES EL MODAL DE COMPRAS)
    */

    //! PROPIEDADES COMPUTADAS

    /**
     * Computed property to determine the type of document (NIT or DUI) based on the provider's data.
     * @returns {string} - Returns "NIT", "DUI", or an empty string if not available.
     */
    const documentType = computed(() => {
        if (arrayDocAdquisicion.value !== '' && idDetDocAdquisicion.value != null) {
            // Find the document in arrayDocAdquisicion matching idDetDocAdquisicion
            const doc = arrayDocAdquisicion.value.find(index => index.value == idDetDocAdquisicion.value);
            if (doc && doc.dataDoc) {
                const proveedor = doc.dataDoc.documento_adquisicion.proveedor;
                // Return "NIT" if nit_proveedor is present, otherwise "DUI"
                if (proveedor.nit_proveedor !== null || proveedor.dui_proveedor !== null) {
                    return proveedor.nit_proveedor ? "NIT" : "DUI";
                }
            }
        }
        return '';
    });

    /**
     * Computed property to get the provider's business name.
     * @returns {string} - Returns the business name or an empty string if not available.
     */
    const providerBusinessName = computed(() => {
        if (arrayDocAdquisicion.value !== '' && idDetDocAdquisicion.value != null) {
            // Find the document in arrayDocAdquisicion matching idDetDocAdquisicion
            const doc = arrayDocAdquisicion.value.find(index => index.value == idDetDocAdquisicion.value);
            if (doc && doc.dataDoc && doc.dataDoc.documento_adquisicion.proveedor.razon_social_proveedor) {
                return doc.dataDoc.documento_adquisicion.proveedor.razon_social_proveedor;
            }
        }
        return '';
    });

    /**
     * Computed property to get the document number (NIT or DUI) of the provider.
     * @returns {string} - Returns the document number or an empty string if not available.
     */
    const documentNumber = computed(() => {
        if (arrayDocAdquisicion.value !== '' && idDetDocAdquisicion.value != null) {
            // Find the document in arrayDocAdquisicion matching idDetDocAdquisicion
            const doc = arrayDocAdquisicion.value.find(index => index.value == idDetDocAdquisicion.value);
            if (doc && doc.dataDoc) {
                const proveedor = doc.dataDoc.documento_adquisicion.proveedor;
                // Return nit_proveedor or dui_proveedor if present
                if (proveedor.nit_proveedor !== null || proveedor.dui_proveedor !== null) {
                    return proveedor.nit_proveedor || proveedor.dui_proveedor;
                }
            }
        }
        return '';
    });

    //! FUNCIONES QUE RETORNAN INFORMACIÓN SELECCIONADA EN SELECT

    /**
     * Function to get the brand name by its ID.
     * @param {number} idMarca - The ID of the brand.
     * @returns {string} - Returns the brand name or '-' if not found.
     */
    const getBrandName = (idMarca) => {
        // Find the brand in arrayMarca matching idMarca
        const marca = arrayMarca.value.find(index => index.value == idMarca);
        return marca?.dataMarca?.nombre_marca || '-';
    };

    /**
     * Function to get the center name by its ID.
     * @param {number} idCentroAtencion - The ID of the attention center.
     * @returns {string} - Returns the center name or '-' if not found.
     */
    const getCenterName = (idCentroAtencion) => {
        // Find the center in arrayCentroAtencion matching idCentroAtencion
        const centro = arrayCentroAtencion.value.find(index => index.value == idCentroAtencion);
        return centro?.dataCentro?.nombre_centro_atencion || '-';
    };


    /**
     * Maneja la actualización de los datos del calendario y calcula el total de cantidades.
     *
     * @param {Object} updatedDataCalendar - El objeto que contiene las cantidades actualizadas para cada mes.
     * @param {number} lineaTrabajoIndex - El índice de la línea de trabajo en arrayProductoAdquisicion.
     * @param {number} productoIndex - El índice del producto dentro de la línea de trabajo especificada.
     */
    const handleDataCalendarUpdate = (updatedDataCalendar, lineaTrabajoIndex, productoIndex) => {
        // Calcula la suma de todas las cantidades de los meses
        const totalCantidad = Object.values(updatedDataCalendar)
            .map(cantidad => parseFloat(cantidad)) // Convierte cada valor a un número
            .reduce((total, cantidad) => total + cantidad, 0); // Suma todos los valores

        // Obtiene el producto en la fila especificada
        const producto = arrayProductoAdquisicion.value[lineaTrabajoIndex].detalleDoc[productoIndex];

        // Actualiza la propiedad cantProdAdquisicion con la suma total
        producto.cantProdAdquisicion = totalCantidad;
        producto.amountsPerMonthList = updatedDataCalendar; // Actualiza la lista de cantidades por mes

        // Llama a la función handleInput para hacer los calculos de cantidad * costo unitario
        handleInput(lineaTrabajoIndex, productoIndex);
    };


    const showGrayBackground = ref(false);
    const showGrayBackgroundTotal = ref(false);
    watch(() => tipoCostoDetDocAdquisicion.value, (newVal) => {
        if (newVal) {
            showGrayBackground.value = true;
            setTimeout(() => {
                showGrayBackground.value = false;
            }, 1000); // 1000 milisegundos = 1 segundo
        } else {
            showGrayBackgroundTotal.value = true;
            setTimeout(() => {
                showGrayBackgroundTotal.value = false;
            }, 1000); // 1000 milisegundos = 1 segundo
        }
        recalculateCostoProdAdquisicion()
    });

    /**
    * Recalcula el costo unitario y el valor total de los productos en arrayProductoAdquisicion.
    */
    const recalculateCostoProdAdquisicion = () => {
        // Itera sobre cada elemento en arrayProductoAdquisicion
        arrayProductoAdquisicion.value.forEach(index => {
            // Itera sobre cada producto en el detalle del documento
            index.detalleDoc.forEach(producto => {

                // Desestructura las propiedades necesarias del producto
                const { cantProdAdquisicion, valorTotalProduct, costoProdAdquisicion } = producto;

                // Verifica el tipo de costo para realizar el cálculo adecuado
                if (!tipoCostoDetDocAdquisicion.value) {
                    // Calculo del valor total
                    producto.valorTotalProduct = (cantProdAdquisicion * costoProdAdquisicion).toFixed(2);
                    producto.costoProdAdquisicion = (valorTotalProduct / cantProdAdquisicion).toFixed(2);
                } else {
                    // Calculo del costo unitario
                    producto.costoProdAdquisicion = (valorTotalProduct / cantProdAdquisicion).toFixed(6);
                }
            });
        });
    }

    return {
        nextPage, // Añadido
        prevPage, // Añadido
        ITEMS_PER_PAGE,
        showGrayBackgroundTotal,
        showGrayBackground,
        handleDataCalendarUpdate,
        getCenterName,
        tipoCostoDetDocAdquisicion,
        getBrandName,
        documentType,
        documentNumber,
        providerBusinessName,
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
        notificacionDetDocAdquisicion,
        recepcionDetDocAdquisicion,
        observacionDetDocAdquisicion,
        arrayDocAdquisicion,
        objectGetFromProp,
        arrayUnidadMedida,
        handleInput,
        arrayMarca,
        updateProductAdquisicionRequest,
        arrayCentroAtencion,
        productDataSearched,
        addingRows,
        arrayWhenIsEditingDocAdq,
        calculateTotalProductValue,
        brandsUsedInDoc,
        loadingNumberLetter,
        setInformacionProduct,
    }
}
