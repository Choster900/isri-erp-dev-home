import { onMounted, ref } from "vue"

export const useRequerimientoAlmacen = () => {

    // Variables de tabla requerimiento
    const idRequerimiento = ref(null)
    const idLt = ref(null)
    const idCentroAtencion = ref(null)
    const idProyFinanciado = ref(null)
    const idEstadoReq = ref(null)
    const numRequerimiento = ref(null)
    const cantPersonalRequerimiento = ref(null)
    const fechaRequerimiento = ref(null)
    const observacionRequerimiento = ref(null)

    const dataDetalleRequerimiento = ref([/* {
        idDetRequerimiento: '',
        idRequerimiento: '',
        idCentroProduccion: '',
        productos: [
            {
                idProducto: '',
                descripcionProductos: '',
                cantDetRequerimiento: '',
                costoDetRequerimiento: '',
            }
        ],
        stateDetalle: 1,
        isHidden: false,
    } */])


    // Variables que almacenanan los objetos para mostrar en multiselect
    const centroAtenionArray = ref(null)
    const lineaTrabajoArray = ref(null)
    const productosArray = ref(null)
    const proyectoFinanciados = ref(null)
    const centroProduccion = ref(null)

    const appendDetalleRequerimiento = () => {
        dataDetalleRequerimiento.value.push({
            idDetRequerimiento: '',
            idRequerimiento: '',
            idCentroProduccion: '',
            productos: [
                {
                    idProducto: '',
                    descripcionProducto: '',
                    cantDetRequerimiento: '',
                    costoDetRequerimiento: '',
                }
            ],
            stateDetalle: 1,
            isHidden: false,
        })
    }

    const appendProduct = (i) => {
        /*  alert("dasda") */
        dataDetalleRequerimiento.value[i].productos.push({
            idProducto: '',
            cantDetRequerimiento: '',
            costoDetRequerimiento: '',
        })
    }

    /**
     * Actualiza la descripción del producto en los datos del requerimiento.
     * @param {string} valueProd - El valor del producto.
     * @param {number} rowCentroProd - El índice de la fila del centro de producción.
     * @param {number} rowProd - El índice de la fila del producto.
     */
    const setDescripcionProducto = (valueProd, rowCentroProd, rowProd) => {
        // Encuentra el producto en el array de productos.
        const producto = productosArray.value.find(producto => producto.value === valueProd);

        // Verifica si el producto fue encontrado.
        if (producto) {
            // Actualiza la descripción del producto en los datos del requerimiento.
            dataDetalleRequerimiento.value[rowCentroProd].productos[rowProd].descripcionProductos = producto.completeData.descripcion_producto;
        } else {
            console.error(`No se encontró el producto con el valor: ${valueProd}`);
        }
    };


    /**
    * Obtener Arrays de objetos para multiselect
    *
    * @returns {Promise<object>} - Promesa que se resuelve con la respuesta del servidor.
    */
    const getArrayObject = async () => {
        try {
            const resp = await axios.post("/get-object-for-requerimiento-almacen", {});
            const { data } = resp
            console.log(data);
            centroAtenionArray.value = data.centrosAtencion.map(index => {
                return { value: index.id_centro_atencion, label: `${index.codigo_centro_atencion} - ${index.nombre_centro_atencion}`, disabled: false };
            })
            lineaTrabajoArray.value = data.lineaTrabajo.map(index => {
                return { value: index.id_lt, label: `${index.codigo_up_lt} - ${index.nombre_lt}`, completeData: index };
            })
            productosArray.value = data.productos.map(index => {
                return { value: index.id_producto, label: `${index.codigo_producto}`, completeData: index };
            })
            proyectoFinanciados.value = data.proyectosFinanciados.map(index => {
                return { value: index.id_proy_financiado, label: `${index.nombre_proy_financiado}`, completeData: index };
            })
            centroProduccion.value = data.centroProduccion.map(index => {
                return { value: index.id_centro_produccion, label: `${index.codigo_centro_produccion} - ${index.sigla_centro_produccion} - ${index.nombre_centro_produccion}`, completeData: index };
            })

        } catch (error) {
            reject(error);
            console.error("Error en la creación de la evaluación personal:", error);
            throw new Error("Error en la creación de la evaluación personal");
        }

    };
    /**
    * Obtener Arrays de objetos para multiselect
    *
    * @returns {Promise<object>} - Promesa que se resuelve con la respuesta del servidor.
    */
    const insertRequerimiento = async () => {
        alert("alerta")
        try {
            const resp = await axios.post("/insert-requerimiento-almacen", {
                idLt: idLt.value,
                idCentroAtencion: idCentroAtencion.value,
                idProyFinanciado: idProyFinanciado.value,
                idEstadoReq: idEstadoReq.value,
                numRequerimiento: numRequerimiento.value,
                cantPersonalRequerimiento: cantPersonalRequerimiento.value,
                fechaRequerimiento: fechaRequerimiento.value,
                observacionRequerimiento: observacionRequerimiento.value,
                dataDetalleRequerimiento:dataDetalleRequerimiento.value,
            });
            console.log(resp);

        } catch (error) {
            reject(error);
            console.error("Error en la creación de la evaluación personal:", error);
            throw new Error("Error en la creación de la evaluación personal");
        }

    };

    onMounted(() => {
        getArrayObject()
    })
    return {
        appendProduct,
        insertRequerimiento,
        appendDetalleRequerimiento,
        dataDetalleRequerimiento,
        idRequerimiento,
        idLt,
        idCentroAtencion,
        idProyFinanciado,
        idEstadoReq,
        numRequerimiento,
        cantPersonalRequerimiento,
        fechaRequerimiento,
        observacionRequerimiento,

        proyectoFinanciados,
        productosArray,
        centroAtenionArray,
        lineaTrabajoArray,
        centroProduccion,

        setDescripcionProducto,
    }
}
