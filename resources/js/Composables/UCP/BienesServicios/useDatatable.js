import { executeRequest } from "@/plugins/requestHelpers";
import axios from "axios";
import Swal from "sweetalert2";
import { onMounted, ref } from "vue";

export const useDatatable = () => {
    const sortOrders = ref({});
    const objectProductoAdquisicion = ref([]);
    const columns = [
        { width: "22%", label: "Proveedor", name: "razon_social_proveedor", type: "text" },
        {
            width: "1%", label: "Tipo Adquisicion", name: "id_tipo_doc_adquisicion", type: "select",
            options: [
                { value: "1", label: "CONTRATO" },
                { value: "2", label: "ORDEN DE COMPRA" },
                { value: "3", label: "FACTURA" }
            ]
        },
        { width: "1%", label: "Gestion Compra", name: "id_tipo_gestion_compra", type: "select",
        options: [
            { value: "1", label: "CONTRATACION DIRECTA" },
            { value: "2", label: "LIBRE GESTION" },
            { value: "3", label: "LICITACION PUBLICA" },
            { value: "4", label: "MERCADO BURSATIL" },

        ] },
        { width: "7%", label: "Numero Adquisicion", name: "numero_doc_adquisicion", type: "text", },
        { width: "22%", label: "Nombre adquisicion", name: "nombre_det_doc_adquisicion", type: "text", },

        { width: "1%", label: "Monto", name: "monto_det_doc_adquisicion", type: "text", },
        { width: "1%", label: "Fecha", name: "fecha_reg_prod_adquisicion", type: "date", },
        {
            width: "1%", label: "Estado", name: "id_estado_doc_adquisicion", type: "select",
            options: [
                { value: "1", label: "CREADO" },
                { value: "2", label: "ENVIADO" },
                { value: "3", label: "CERRADO" }
            ]
        },
        { width: "1%", label: "", name: "Acciones" },
    ];
    const isLoadinRequest = ref(false);
    const emptyObject = ref(null);
    const permits = ref([]);
    const stateModal = ref(false);
    const prodAdquisicion = ref(null);
    const showModalEvaluacion = ref(false);
    const links = ref([]);
    const lastUrl = ref("/evaluaciones");
    const listDependencias = ref(null);
    columns.forEach((column) => {
        if (column.name === "id_proveedor") sortOrders.value[column.name] = 1;
        else sortOrders.value[column.name] = -1;
    });
    const sortKey = ref("id_proveedor");
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

    const getProductoAdquisicion = async (url = "/producto-adquisiciono") => {
        try {
            isLoadinRequest.value = true;
            lastUrl.value = url;
            tableData.value.draw++;

            const response = await axios.post(url, tableData.value);
            const data = response.data;

            if (tableData.value.draw === data.draw) {
                let resultado = data.data.data;

                prodAdquisicion.value = data.data.data;
                pagination.value.total = data.data.total;
                links.value = data.data.links;
                links.value[0].label = "Anterior";
                links.value[links.value.length - 1].label = "Siguiente";
            }

            emptyObject.value = evaluaciones.value.length === 0;
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
            getProductoAdquisicion();
        }
    };


    const updateDetDocAdquisicionRequest = (idDetDoc, idState) => {
        return new Promise(async (resolve, reject) => {
            try {
                const response = await axios.post(
                    "/change-state-detalle-doc-adquisicion", {
                    id: idDetDoc,
                    idState: idState,
                });
                resolve(response); // Resolvemos la promesa con la respuesta exitosa
            } catch (error) {
                // Manejo de errores específicos
                reject(error); // Rechazamos la promesa en caso de excepción
            }
        });
    };

    /**
   * Busca producto por codigo.
   *
   * @param {BigInteger} idDetDoc - id documento detalle adquisicion.
   * @param {BigInteger} idState - id estado det doc adq
   * @returns {Promise<object>} - Objeto con los datos de la respuesta.
   * @throws {Error} - Error al obtener empleados por nombre.
   */
    const changeState = async (idDetDoc, idState) => {
        try {
            const confirmedEliminarProd = await Swal.fire({
                title: `<p class="text-[15pt]">¿Desea ${idState == 2 ? `Enviar` : `Cerrar`} el documento?.</p>`,
                icon: "question",
                iconHtml: `<lord-icon src="https://cdn.lordicon.com/enzmygww.json" trigger="loop" delay="500" colors="primary:#121331" style="width:100px;height:100px"></lord-icon>`,
                confirmButtonText: `<p class="text-[10pt] text-center">Si, Eliminar</p>`,
                confirmButtonColor: "#D2691E",
                cancelButtonText: `<p class="text-[10pt] text-center">Cancelar</p>`,
                showCancelButton: true,
                showCloseButton: true,
            });
            if (confirmedEliminarProd.isConfirmed) {
                executeRequest(
                    updateDetDocAdquisicionRequest(idDetDoc, idState),
                    `¡El documento de adquisicion se ha ${idState == 2 ? `Enviado` : `Cerrado`} correctamente!`
                );
                getProductoAdquisicion(lastUrl.value)
            }
        } catch (error) {
            // Manejo de errores específicos
            console.error("Error en cambiar de estado:", error);
            // Lanza el error para que pueda ser manejado por el componente que utiliza este composable
            throw new Error("Error en cambiar de estado po:");
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
            getProductoAdquisicion();
        } else {
            tableData.value.search = myEventData;
        }
    };

    async function getAllDependencias() {
        try {
            const resp = await axios.get("/getAllDependencias");
            let list = resp.data.map((data) => {
                return { value: data.id_centro_atencion, label: `${data.codigo_centro_atencion} - ${data.nombre_centro_atencion}`, dependencias: data.dependencias }

            });
            listDependencias.value = list

        } catch (error) {
            console.error(error);
        }
    }

    onMounted(async () => {
        getAllDependencias();
        getProductoAdquisicion();
    });

    return {
        columns,
        changeState,
        sortKey,
        sortOrders,
        objectProductoAdquisicion,
        tableData,
        pagination,
        listDependencias,
        prodAdquisicion,
        emptyObject,
        permits,
        stateModal,
        showModalEvaluacion,
        links,
        lastUrl,
        perPage,
        isLoadinRequest,
        sortBy,
        getProductoAdquisicion,
        handleData,
    };
};
