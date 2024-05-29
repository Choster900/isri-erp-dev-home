import axios from "axios";
import { onMounted, ref } from "vue";

export const useDatatable = () => {
    const sortOrders = ref({});
    const columns = [
        { width: "10%", label: "ID", name: "id_persona", type: "text" },
        { width: "10%", label: "Dui", name: "dui_persona", type: "text" },
        { width: "20%", label: "Profesion", name: "nombre_profesion", type: "text" },
        { width: "20%", label: "Nombres", name: "collecNombre", type: "text" },
        {width: "20%",label: "Apellidos",name: "collecApellido",type: "text",},
        {width: "10%",label: "Fecha",name: "fecha_reg_archivo_anexo",type: "date",},
        { width: "1%", label: "", name: "Acciones" },
    ];
    const isLoadinRequest = ref(false);
    const emptyObject = ref(null);
    const permits = ref([]);
    const stateModal = ref(false);
    const persona = ref(null);
    const dataBeneficiariosToSendModal = ref([]);
    const showModal = ref(false);
    const links = ref([]);
    const lastUrl = ref("/expedientes");

    columns.forEach((column) => {
        if (column.name === "id_persona") sortOrders.value[column.name] = 1;
        else sortOrders.value[column.name] = -1;
    });
    const sortKey = ref("id_persona");
    const perPage = ref(["10", "20", "30"]);
    const tableData = ref({
        draw: 0,
        length: 10,
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

    const getPeople = async (url = "/expedientes") => {
        try {
            isLoadinRequest.value = true;
            lastUrl.value = url;
            tableData.value.draw++;

            const response = await axios.post(url, tableData.value);
            const data = response.data;

            if (tableData.value.draw === data.draw) {
                persona.value = data.data.data;
                pagination.value.total = data.data.total;
                links.value = data.data.links;
                links.value[0].label = "Anterior";
                links.value[links.value.length - 1].label = "Siguiente";
            }

            emptyObject.value = persona.value.length === 0;
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
            getPeople();
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
            getPeople();
        } else {
            tableData.value.search = myEventData;
        }
    };

    onMounted(async () => {
        getPeople();
    });

    return {
        columns,
        sortKey,
        sortOrders,
        tableData,
        pagination,
        emptyObject,
        permits,
        stateModal,
        persona,
        dataBeneficiariosToSendModal,
        showModal,
        links,
        lastUrl,
        perPage,
        isLoadinRequest,
        sortBy,
        getPeople,
        handleData,
    };
};
