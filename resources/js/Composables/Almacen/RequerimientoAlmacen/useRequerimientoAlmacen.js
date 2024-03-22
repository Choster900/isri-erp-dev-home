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
                    idDetRequerimiento: '',
                    idProducto: '',
                    descripcionProducto: '',
                    cantDetRequerimiento: '',
                    costoDetRequerimiento: '',
                    stateProducto: 1,
                }
            ],
            stateCentroProduccion: 1,
            isHidden: false,
        })
    }

    const appendProduct = (i) => {
        /*  alert("dasda") */
        dataDetalleRequerimiento.value[i].productos.push({
            idDetRequerimiento: '',
            idProducto: '',
            descripcionProducto: '',
            cantDetRequerimiento: '',
            costoDetRequerimiento: '',
            stateProducto: 1,
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
                return { value: index.id_producto, label: `${index.codigo_producto} - ${index.nombre_producto}`, completeData: index };
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
     * Guarda productos adquisición en el servidor.
     *
     * @returns {Promise<object>} - Promesa que se resuelve con la respuesta exitosa o se rechaza con el error.
     */
    const saveRequerimientoAlmacenRequest = () => {
        return new Promise(async (resolve, reject) => {
            try {
                // Hace una solicitud POST a la ruta "/save-prod-adquicicion" con los datos necesarios
                const resp = await axios.post("/insert-requerimiento-almacen", {
                    idLt: idLt.value,
                    idCentroAtencion: idCentroAtencion.value,
                    idProyFinanciado: idProyFinanciado.value,
                    idEstadoReq: idEstadoReq.value,
                    numRequerimiento: numRequerimiento.value,
                    cantPersonalRequerimiento: cantPersonalRequerimiento.value,
                    fechaRequerimiento: fechaRequerimiento.value,
                    observacionRequerimiento: observacionRequerimiento.value,
                    dataDetalleRequerimiento: dataDetalleRequerimiento.value,
                });
                console.log(resp);
                // Se resuelve la promesa con la respuesta exitosa de la solicitud
                resolve(resp);
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
     * Guarda productos adquisición en el servidor.
     *
     * @returns {Promise<object>} - Promesa que se resuelve con la respuesta exitosa o se rechaza con el error.
     */
const updateRequerimientoAlmacenRequest = () => {
    return new Promise(async (resolve, reject) => {
        try {
            // Hace una solicitud POST a la ruta "/save-prod-adquicicion" con los datos necesarios
            const resp = await axios.post("/update-requerimiento-almacen", {
                idRequerimiento: idRequerimiento.value,
                idLt: idLt.value,
                idCentroAtencion: idCentroAtencion.value,
                idProyFinanciado: idProyFinanciado.value,
                idEstadoReq: idEstadoReq.value,
                numRequerimiento: numRequerimiento.value,
                cantPersonalRequerimiento: cantPersonalRequerimiento.value,
                fechaRequerimiento: fechaRequerimiento.value,
                observacionRequerimiento: observacionRequerimiento.value,
                dataDetalleRequerimiento: dataDetalleRequerimiento.value,
            });
            console.log(resp);

            // Se resuelve la promesa con la respuesta exitosa de la solicitud
            resolve(resp);
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
    * Obtener Arrays de objetos para multiselect
    *
    * @returns {Promise<object>} - Promesa que se resuelve con la respuesta del servidor.
    */
   /*  const insertRequerimiento = async () => {
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
                dataDetalleRequerimiento: dataDetalleRequerimiento.value,
            });
            console.log(resp);

        } catch (error) {
            reject(error);
            console.error("Error en la creación de la evaluación personal:", error);
            throw new Error("Error en la creación de la evaluación personal");
        }

    }; */

    /**
    * Obtener Arrays de objetos para multiselect
    *
    * @returns {Promise<object>} - Promesa que se resuelve con la respuesta del servidor.
    */
   /*  const updateRequerimiento = async () => {
        alert("alerta")
        try {
            const resp = await axios.post("/update-requerimiento-almacen", {
                idRequerimiento: idRequerimiento.value,
                idLt: idLt.value,
                idCentroAtencion: idCentroAtencion.value,
                idProyFinanciado: idProyFinanciado.value,
                idEstadoReq: idEstadoReq.value,
                numRequerimiento: numRequerimiento.value,
                cantPersonalRequerimiento: cantPersonalRequerimiento.value,
                fechaRequerimiento: fechaRequerimiento.value,
                observacionRequerimiento: observacionRequerimiento.value,
                dataDetalleRequerimiento: dataDetalleRequerimiento.value,
            });
            console.log(resp);

        } catch (error) {
            reject(error);
            console.error("Error en la creación de la evaluación personal:", error);
            throw new Error("Error en la creación de la evaluación personal");
        }

    }; */
    /**
       * Busca empleados por nombre para evaluaciones.
       *
       * @param {string} nombreProductToSearch - Nombre a buscar.
       * @returns {Promise<object>} - Objeto con los datos de la respuesta.
       * @throws {Error} - Error al obtener empleados por nombre.
       */

    const handleProductSearch = async (nombreProductToSearch) => {

        try {
            // Realiza la búsqueda de empleados
            const response = await axios.post(
                "/get-product-searched-almacen",
                {
                    nombre: nombreProductToSearch,
                }
            );

            // Devuelve los datos de la respuesta
            console.log(response);
            return response.data;
        } catch (error) {
            // Manejo de errores específicos
            console.error("Error al obtener empleados por nombre:", error);
            // Lanza el error para que pueda ser manejado por el componente que utiliza este composable
            throw new Error("Error en la búsqueda de empleados");
        }

    };

    onMounted(() => {
        getArrayObject()
    })
    return {
        appendProduct,
        saveRequerimientoAlmacenRequest,
        appendDetalleRequerimiento,
        updateRequerimientoAlmacenRequest,
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
        handleProductSearch,
        proyectoFinanciados,
        productosArray,
        centroAtenionArray,
        lineaTrabajoArray,
        centroProduccion,

        setDescripcionProducto,
    }
}
