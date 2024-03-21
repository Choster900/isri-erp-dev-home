import axios from 'axios';
import moment from 'moment';
import { onMounted, ref } from 'vue';

export const useDatatableReqAlm = () => {

    const sortOrders = ref({});
    const columns = [
        { width: "22%", label: "ID", name: "id_requerimiento", type: "text" },

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
    const getRequerimientosAlmacen = async (url = "/get-requerimiento-almacen") => {
        try {
            isLoadinRequest.value = true;
            lastUrl.value = url;
            tableData.value.draw++;

            const response = await axios.post(url, tableData.value);
            const data = response.data;

            if (tableData.value.draw === data.draw) {
                let resultado = data.data.data;

                objectRequerimientos.value = data.data.data;
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


    onMounted(async () => {
        getRequerimientosAlmacen();
    });

    return {
        columns,
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
        sortBy,
        getRequerimientosAlmacen,
        handleData,
    };
}

