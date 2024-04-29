import { onMounted, ref, watch } from "vue"
import { usePage } from '@inertiajs/vue3';

export const useRequerimientoAlmacen = (objectRequerimientoToSendModal, numeroRequerimientoSiguiente, showModal) => {

    // Variables de tabla requerimiento
    const idRequerimiento = ref(null)
    const idLt = ref(null)
    const idCentroAtencion = ref(null)
    const idProyFinanciado = ref(null)
    const idEstadoReq = ref(null)
    const numRequerimiento = ref(null)
    const fechaRequerimiento = ref(null)
    const observacionRequerimiento = ref(null)
    const errorsValidation = ref(null)

    const dataDetalleRequerimiento = ref([])

    // Variables que almacenanan los objetos para mostrar en multiselect
    const centroAtenionArray = ref(null)
    const lineaTrabajoArray = ref(null)
    const productosArray = ref(null)
    const proyectoFinanciados = ref(null)
    const centroProduccion = ref(null)
    const marcasArray = ref(null)
    const productoArrayWithOutProductNoStock = ref([])

    const canEditReq = ref(false)
    const isLoadinProduct = ref(false)
    const optionsCentroAtencion = ref(null)
    const isLoadingCentrosProduccion = ref(false)

    const appendDetalleRequerimiento = () => {
        dataDetalleRequerimiento.value.push({
            idRequerimiento: '',
            idDetRequerimiento: '',
            idRequerimiento: '',
            idCentroProduccion: '',
            productos: [
                {
                    idMarca: '',
                    idProducto: '',
                    stateProducto: 1,
                    idDetRequerimiento: '',
                    cantDetRequerimiento: '',
                    idDetExistenciaAlmacen: '',
                }
            ],
            isHidden: false,
            stateCentroProduccion: 1,
        })
    }

    const appendProduct = (i) => {
        /*  alert("dasda") */
        dataDetalleRequerimiento.value[i].productos.push({
            idMarca: '',
            idProducto: '',
            stateProducto: 1,
            idDetRequerimiento: '',
            cantDetRequerimiento: '',
            idDetExistenciaAlmacen: '',
        })
    }

    /**
     * Setea IdProducto del detalle de existencia almacen.
     * @param {string} idDetalleExistenciaAlmacen - El valor del producto.
     * @param {number} rowCentroProd - El índice de la fila del centro de producción.
     * @param {number} rowProd - El índice de la fila del producto.
     */
    const setIdProductoByDetalleExistenciaAlmacenId = (idDetalleExistenciaAlmacen, rowCentroProd, rowProd) => {
        // Encuentra el producto en el array de productos.
        const producto = productosArray.value.find(detExistencia => detExistencia.value === idDetalleExistenciaAlmacen);

        // Verifica si el producto fue encontrado.
        if (producto) {
            // Actualiza la descripción del producto en los datos del requerimiento.
            dataDetalleRequerimiento.value[rowCentroProd].productos[rowProd].idProducto = producto.codidoProducto;
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
            /*  centroAtenionArray.value = data.centrosAtencion.map(index => {
                 return { value: index.id_centro_atencion, label: `${index.codigo_centro_atencion} - ${index.nombre_centro_atencion}`, disabled: false };
             }) */
            lineaTrabajoArray.value = data.lineaTrabajo.map(index => {
                return { value: index.id_lt, label: `${index.codigo_up_lt} - ${index.nombre_lt}`, completeData: index };
            })
            marcasArray.value = data.marcas.map(index => {
                return { value: index.id_marca, label: `${index.nombre_marca} `, completeData: index };
            })
            proyectoFinanciados.value = data.proyectosFinanciados.map(index => {
                return { value: index.id_proy_financiado, label: `${index.nombre_proy_financiado}`, completeData: index };
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
                    idLt: idProyFinanciado.value === 4 ? null : idLt.value,
                    idCentroAtencion: idCentroAtencion.value,
                    idProyFinanciado: idProyFinanciado.value,
                    idEstadoReq: idEstadoReq.value,
                    numRequerimiento: numRequerimiento.value,

                    fechaRequerimiento: fechaRequerimiento.value,
                    observacionRequerimiento: observacionRequerimiento.value,
                    dataDetalleRequerimiento: dataDetalleRequerimiento.value,
                });
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
         * modifica productos adquisición en el servidor.
         *
         * @returns {Promise<object>} - Promesa que se resuelve con la respuesta exitosa o se rechaza con el error.
         */
    const updateRequerimientoAlmacenRequest = () => {
        return new Promise(async (resolve, reject) => {
            try {
                const resp = await axios.post("/update-requerimiento-almacen", {
                    idRequerimiento: idRequerimiento.value,
                    idLt: idProyFinanciado.value === 4 ? null : idLt.value,
                    idCentroAtencion: idCentroAtencion.value,
                    idProyFinanciado: idProyFinanciado.value,
                    idEstadoReq: idEstadoReq.value,
                    numRequerimiento: numRequerimiento.value,
                    fechaRequerimiento: fechaRequerimiento.value,
                    observacionRequerimiento: observacionRequerimiento.value,
                    dataDetalleRequerimiento: dataDetalleRequerimiento.value,
                });

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
            return response.data;
        } catch (error) {
            // Manejo de errores específicos
            console.error("Error al obtener empleados por nombre:", error);
            // Lanza el error para que pueda ser manejado por el componente que utiliza este composable
            throw new Error("Error en la búsqueda de empleados");
        }

    };

    /**
             * Obtiene la dependencia por usuario.
             *
             * @returns {Promise<object>} - Promesa que se resuelve con la respuesta exitosa o se rechaza con el error.
             */
    const getDependenciaByUser = async () => {
        try {
            isLoadingCentrosProduccion.value = true;


            const resp = await axios.post("/get-centro-by-user");

            if (resp.data && resp.data.length === 1) {
                idCentroAtencion.value = resp.data[0].id_centro_atencion;
            }

            const options = resp.data.map(item => ({
                value: item.id_centro_atencion,
                label: `${item.codigo_centro_atencion} - ${item.nombre_centro_atencion}`,
                disabled: false
            }));

            optionsCentroAtencion.value = options;
            if (optionsCentroAtencion.value.length == 1) {
                console.log(optionsCentroAtencion.value[0].value);
                searchProductionCenterByAtentionCenter(optionsCentroAtencion.value[0].value, "/get-centro-produccion-by-users-centro")
            }
            isLoadingCentrosProduccion.value = false

            return resp;
        } catch (error) {
            console.error('Error al obtener la dependencia por usuario:', error);
            throw error; // Propaga el error para que pueda ser manejado por el código que llama a esta función.
        }
    };

    /**
           * Obtener los productos en existencia almacen por centro, proyecto financiado y linea de trabajo
           *
           * @returns {Promise<object>} - Promesa que se resuelve con la respuesta exitosa o se rechaza con el error.
           */
    const getProductoByDependencia = async () => {
        try {
            // Indicar que se está cargando productos
            isLoadinProduct.value = true;

            // Realizar la solicitud POST para obtener los productos por centro, proyecto financiado y línea de trabajo
            const resp = await axios.post("/get-product-by-proy-financiado", {
                idCentroAtencion: idCentroAtencion.value,
                idProyFinanciado: idProyFinanciado.value,
                idLt: idLt.value,
            });

            // Limpiar los arreglos de productos
            productosArray.value = [];
            productoArrayWithOutProductNoStock.value = [];

            // Iterar sobre los datos de respuesta para procesar cada producto
            resp.data.forEach(index => {
                // Calcular el stock restando la cantidad solicitada en los requerimientos
                const stock = index.cant_det_existencia_almacen - index.solicitado_en_req;

                // Obtener el nombre de la marca o establecer "Sin marca" si no está definido
                const marca = index.marca ? index.marca.nombre_marca : "NO BRAND";

                // Crear un objeto con los datos del producto
                const productData = {
                    value: index.id_det_existencia_almacen,
                    label: `${index.existencia_almacen.productos.codigo_producto} -
                ${index.existencia_almacen.productos.nombre_producto}  -
                ${stock < 0 ? 'NO DISPONIBLE':stock}  -
                ${marca} -
                ${index.existencia_almacen.productos.descripcion_producto}`,
                    completeData: index,
                    stock: stock,
                    codidoProducto: index.existencia_almacen.id_producto
                };

                // Agregar el producto al arreglo de todos los productos
                productosArray.value.push(productData);

                // Agregar el producto al arreglo de productos con stock mayor que 0
                if (stock > 0) {
                    productoArrayWithOutProductNoStock.value.push(productData);
                }
            });

            // Indicar que se ha completado la carga de productos
            isLoadinProduct.value = false;

            // Devolver la respuesta
            return resp;
        } catch (error) {
            // Manejar errores
            console.error('Error al obtener productos por dependencia:', error);
            throw error;
        } finally {
            // Indicar que se ha completado la carga de productos, incluso en caso de error
            isLoadinProduct.value = false;
        }
    };

    const searchProductionCenterByAtentionCenter = async (centroAtencion, URL, isAddingOne = true) => {

        if (isAddingOne == true) {

            isLoadingCentrosProduccion.value = true;
        }
        /* optionsCentroAtencion.value = [] */
      /*   idCentroAtencion.value = null; */

        const resp = await axios.post(URL, {
            idCentroAtencion: centroAtencion,
        });
        console.log(resp);



        if (URL == '/get-centro-produccion-by-centro') {


            if (isAddingOne == true) {

                const options = resp.data?.centrosAtencion?.map(item => ({
                    value: item.id_centro_atencion,
                    label: `${item.codigo_centro_atencion} - ${item.nombre_centro_atencion}`,
                    disabled: false
                }));
                optionsCentroAtencion.value = options;
            }

            centroProduccion.value = resp.data.centroProduccion.map(index => {
                return { value: index.id_centro_produccion, label: `${index.codigo_centro_produccion} - ${index.sigla_centro_produccion} - ${index.nombre_centro_produccion}`, completeData: index };
            })
        } else {
            centroProduccion.value = resp.data.map(index => {
                return { value: index.id_centro_produccion, label: `${index.codigo_centro_produccion} - ${index.sigla_centro_produccion} - ${index.nombre_centro_produccion}`, completeData: index };
            })
        }

        if (isAddingOne == true) {
            isLoadingCentrosProduccion.value = false
        }
    }

    const canThisUserEdit = (estadoReq, usuarioRq) => {
        const nickUser = usePage().props.auth.user.nick_usuario
        canEditReq.value = true
        // Verificar si el usuario actual coincide con el usuario asociado al requerimiento
        if (nickUser === usuarioRq) {
            // Si el estado del requerimiento es 2 o 3, el usuario no puede editar
            if (estadoReq === 2 || estadoReq === 3) {
                canEditReq.value = false
            } else {
                // En cualquier otro caso, el usuario puede editar
                canEditReq.value = true
            }
        } else {
            // Si el usuario actual no coincide con el usuario asociado al requerimiento, no puede editar
            canEditReq.value = false
        }
    }

    const canThisUserEdit = (estadoReq, usuarioRq) => {
        const nickUser = usePage().props.auth.user.nick_usuario
        canEditReq.value = true
        // Verificar si el usuario actual coincide con el usuario asociado al requerimiento
        if (nickUser === usuarioRq) {
            // Si el estado del requerimiento es 2 o 3, el usuario no puede editar
            if (estadoReq === 2 || estadoReq === 3) {
                canEditReq.value = false
            } else {
                // En cualquier otro caso, el usuario puede editar
                canEditReq.value = true
            }
        } else {
            // Si el usuario actual no coincide con el usuario asociado al requerimiento, no puede editar
            canEditReq.value = false
        }
    }

    watch(objectRequerimientoToSendModal, (newValue, oldValue) => {
        const nickUser = usePage().props.auth.user.nick_usuario

        if (newValue !== null && newValue !== undefined && (Array.isArray(newValue) ? newValue.length > 0 : newValue !== '')) {
            const { detalles_requerimiento, id_lt, id_centro_atencion, id_proy_financiado, id_estado_req, num_requerimiento, id_requerimiento, observacion_requerimiento, usuario_requerimiento } = newValue
            idRequerimiento.value = id_requerimiento
            idLt.value = id_lt
            idCentroAtencion.value = id_centro_atencion
            searchProductionCenterByAtentionCenter(id_centro_atencion, "/get-centro-produccion-by-centro")

            idProyFinanciado.value = id_proy_financiado
            idEstadoReq.value = id_estado_req
            numRequerimiento.value = num_requerimiento
            observacionRequerimiento.value = observacion_requerimiento


            canThisUserEdit(idEstadoReq.value, usuario_requerimiento)



            let idCentroProduccionSet = new Set();

            dataDetalleRequerimiento.value = detalles_requerimiento.map(det => {
                let idCentroProduct = det.id_centro_produccion;
                if (!idCentroProduccionSet.has(idCentroProduct)) {
                    idCentroProduccionSet.add(idCentroProduct);
                    return {
                        idDetRequerimiento: det.id_det_requerimiento,
                        idRequerimiento: det.id_requerimiento,
                        idCentroProduccion: det.id_centro_produccion,
                        productos: [],
                        stateCentroProduccion: 1,
                        isHidden: false,
                    };
                }
            })
                .filter(Boolean);
            detalles_requerimiento.forEach(index => {
                let indice = dataDetalleRequerimiento.value.findIndex(i => i.idCentroProduccion == index.id_centro_produccion);
                const { producto } = index
                dataDetalleRequerimiento.value[indice]["productos"].push({
                    stateProducto: 1,
                    idMarca: index.id_marca,
                    idProducto: producto.id_producto,
                    idDetRequerimiento: index.id_det_requerimiento,
                    cantDetRequerimiento: index.cant_det_requerimiento,
                    idDetExistenciaAlmacen: index.id_det_existencia_almacen,

                });
            });
            getProductoByDependencia()
        } else {
            dataDetalleRequerimiento.value = [];
            idRequerimiento.value = null
            idLt.value = null
            centroProduccion.value = []
            idCentroAtencion.value = null
            idProyFinanciado.value = null
            idEstadoReq.value = null
            numRequerimiento.value = numeroRequerimientoSiguiente.value
            observacionRequerimiento.value = null
            canEditReq.value = true
            getDependenciaByUser()
            appendDetalleRequerimiento()

        }
    });

    watch(showModal, (newValue, oldValue) => {
        if (!newValue) {
            productosArray.value = []
            productoArrayWithOutProductNoStock.value = []
            /*   canEditReq.value = false */
        }
    })
    onMounted(() => {
        getArrayObject()
        getDependenciaByUser()
    })
    return {
        appendProduct,
        searchProductionCenterByAtentionCenter,
        saveRequerimientoAlmacenRequest,
        appendDetalleRequerimiento,
        updateRequerimientoAlmacenRequest,
        isLoadingCentrosProduccion,
        errorsValidation,
        dataDetalleRequerimiento,
        idRequerimiento,
        getProductoByDependencia,
        idLt,
        idCentroAtencion,
        idProyFinanciado,
        idEstadoReq,
        numRequerimiento,
        fechaRequerimiento,
        observacionRequerimiento,
        handleProductSearch,
        proyectoFinanciados,
        productosArray,
        centroAtenionArray,
        marcasArray,
        lineaTrabajoArray,
        productoArrayWithOutProductNoStock,
        centroProduccion,
        isLoadinProduct,
        optionsCentroAtencion,
        canEditReq,
        setIdProductoByDetalleExistenciaAlmacenId,
    }
}
