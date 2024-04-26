import axios from 'axios';
import moment from 'moment';
import { onMounted, ref } from 'vue';
import { parse } from 'vue/compiler-sfc';

export const useDatatableReqAlm = () => {

    const sortOrders = ref({});
    const columns = [
        { width: "10%", label: "Numero requerimiento", name: "num_requerimiento", type: "text" },
        {
            width: "22%", label: "Centro de atencion", name: "id_centro_atencion", type: "select", options: [
                { value: "1", label: "ADMINISTRACION SUPERIOR" },
                { value: "2", label: 'CENTRO DE ATENCION A ANCIANOS "SARA ZALDIVAR"' },
                { value: "3", label: "CENTRO DEL APARATO LOCOMOTOR" },
                { value: "4", label: "CENTRO DE AUDICION Y LENGUAJE" },
                { value: "5", label: "CENTRO DE AUDICION Y LENGUAJE CENTRO DE REHABILITACION PARA CIEGOS EUGENIA DE DUEÑAS" },
                { value: "6", label: "UNIDAD DE CONSULTA EXTERNA" },
                { value: "7", label: "CENTRO DE REHABILITACION INTEGRAL PARA LA NIÑEZ Y LA ADOLESCENCIA" },
                { value: "8", label: "CENTRO DE REHABILITACION INTEGRAL DE OCCIDENTE" },
                { value: "9", label: "CENTRO DE REHABILITACION INTEGRAL DE ORIENTE" },
                { value: "10", label: "CENTRO DE REHABILITACION PROFESIONAL" },

            ]
        },
        {
            width: "22%", label: "Linea de trabajo", name: "id_lt", type: "select", options: [
                { value: "1", label: "DIRECCION SUPERIOR Y ADMINISTRACION" },
                { value: "2", label: "REHABILITACION INTEGRAL" },
                { value: "3", label: "PROGRAMA DE ELABORACION DE PROTESIS Y ORTESIS" },
                { value: "4", label: "PROGRAMA NACIONAL DE ULCERAS, HERIDAS Y PIE DIABETICO" },
                { value: "5", label: "PROGRAMA DE DOTACION DE PROTESIS PARA USUARIOS AMPUTADOS" },
                { value: "6", label: "ATENCIONES DE REHABILTACION INTEGRAL A NIÑOS (AS) CON ALGUNA CONDICION DE DISCAPACIDAD" },
                { value: "7", label: "SALAS CUNA PARA HIJOS (AS), HASTA 3 AÑOS DE EDAD DE EMPLEADOS (AS)" },

            ]
        },
        {
            width: "10%", label: "Proyecto financiado", name: "id_proy_financiado", type: "select",
            options: [
                { value: "1", label: "FONDO GENERAL" },
                { value: "2", label: "FONDO CIRCULANTE DE MONTO FIJO" },
                { value: "3", label: "RECURSOS PROPIOS" },
                { value: "4", label: "DONACION" },
            ]
        },
        { width: "10%", label: "Fecha", name: "fecha_requerimiento", type: "date" },
        {
            width: "10%", label: "Estado", name: "id_estado_req", type: "select", options: [
                { value: "1", label: "CREADO" },
                { value: "2", label: "APROBADO" },
                { value: "3", label: "DESPACHADO" },
                { value: "4", label: "ELIMINADO" },

            ]
        },
        { width: "1%", label: "", name: "Acciones" },

    ];
    const isLoadinRequest = ref(false);
    const emptyObject = ref(null);
    const permits = ref([]);
    const objectRequerimientos = ref(null);
    const showModalRequerimientoAlmacen = ref(false);
    const links = ref([]);
    const lastUrl = ref("/evaluaciones");
    columns.forEach((column) => {
        if (column.name === "id_requerimiento") sortOrders.value[column.name] = 1;
        else sortOrders.value[column.name] = -1;
    });
    const sortKey = ref("id_requerimiento");
    const perPage = ref(["10", "20", "30"]);
    const tableData = ref({
        draw: 0,
        length: 6,
        column: 0,
        dir: "desc",
        search: {},
    });
    const pagination = ref({
        lastPage: "",
        currentPage: "",
        total: "",
        lastPageUrl: "",
        nextPageUrl: "",
        prevPageUrl: "",
        from: "",
        to: "",
    });
    const objectRequerimientoToSendModal = ref(null)
    const canSaveReq = ref(false)
    const numeroRequerimientoSiguiente = ref(null)
    const getRequerimientosAlmacen = async (url = "/get-requerimiento-almacen") => {
        try {
            isLoadinRequest.value = true;
            lastUrl.value = url;
            tableData.value.draw++;

            const response = await axios.post(url, tableData.value);
            const data = response.data;


            if (tableData.value.draw === data.draw) {
                let resultado = data.data.data;

                canSaveReq.value = data.canSaveReq
                objectRequerimientos.value = data.data.data;
                pagination.value.total = data.data.total;
                links.value = data.data.links;
                links.value[0].label = "Anterior";
                links.value[links.value.length - 1].label = "Siguiente";

                emptyObject.value = objectRequerimientos.value.length === 0;

            }
            generateNumberRequerimiento()


        } catch (error) {
            // Manejo de errores aquí
        } finally {
            isLoadinRequest.value = false;
        }
    };

    const getIndex = (array, key, value) => {
        return array.findIndex((i) => i[key] == value);
    };

    const sortBy = (key) => {
        if (key != "Acciones") {
            sortKey.value = key;
            sortOrders.value[key] = sortOrders.value[key] * -1;
            tableData.value.column = getIndex(columns, "name", key);
            tableData.value.dir = sortOrders.value[key] === 1 ? "desc" : "asc";
            getRequerimientosAlmacen();
        }
    };


    const validarCamposVacios = (objeto) => {
        for (const propiedad in objeto) {
            if (objeto[propiedad] !== "") {
                return false;
            }
        }
        return true;
    };
    const handleData = (myEventData) => {
        if (validarCamposVacios(myEventData)) {
            tableData.value.search = {};
            getRequerimientosAlmacen();
        } else {
            tableData.value.search = myEventData;
        }
    };


    /**
  * Genera un nuevo número de requerimiento.
  *
  * @returns {Promise<string>} - Promesa que se resuelve con el nuevo número de requerimiento.
  * @throws {Error} - Error si la solicitud HTTP falla o no se puede procesar la respuesta.
  */
    const generateNumberRequerimiento = async () => {
        try {
            const response = await axios.post('/get-number-requerimiento');

            // Obtener el año actual
            const currentYear = moment().year();

            let nextNumber;

            // Verificar si se recibió el número actual en la respuesta
            if (response.data.num_requerimiento) {
                const currentNumber = parseInt(response.data.num_requerimiento.split('-')[2]);

                // Verificar si el número recibido corresponde al año actual
                if (parseInt(response.data.num_requerimiento.split('-')[1]) !== currentYear) {
                    nextNumber = 1; // Si no corresponde, comenzar desde 1
                } else {
                    nextNumber = currentNumber + 1; // Si corresponde, incrementar el número actual
                }
            } else {
                nextNumber = 1; // Si no se recibe el número actual, asumir que es el primero del año
            }

            // Construir el nuevo número de requerimiento
            const newNumberString = `REQ-${currentYear}-${nextNumber}`;
            console.log('Nuevo número de requerimiento:', newNumberString);

            numeroRequerimientoSiguiente.value = newNumberString;
        } catch (error) {
            console.error('Error al generar el número de requerimiento:', error);
            throw new Error('No se pudo generar el número de requerimiento.');
        }
    };


    onMounted(async () => {
        getRequerimientosAlmacen();
        // numeroRequerimientoSiguiente.value = await generateNumberRequerimiento()
    });

    return {
        columns,
        numeroRequerimientoSiguiente,
        sortKey,
        sortOrders,
        tableData,
        pagination,
        objectRequerimientos,
        emptyObject,
        permits,
        objectRequerimientoToSendModal,
        showModalRequerimientoAlmacen,
        links,
        lastUrl,
        perPage,
        isLoadinRequest,
        canSaveReq,
        sortBy,
        getRequerimientosAlmacen,
        handleData,
    };
}

