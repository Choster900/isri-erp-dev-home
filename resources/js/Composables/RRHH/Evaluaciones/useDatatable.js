import axios from "axios";
import { onMounted, ref } from "vue";

export const useEvaluacion = () => {
    const sortOrders = ref({});
    const columns = [
        { width: "10%", label: "ID", name: "id_empleado", type: "text" },
        {
            width: "10%",
            label: "Codigo",
            name: "codigo_empleado",
            type: "text",
        },
        { width: "20%", label: "Nombres", name: "collecNombre", type: "text" },
        {
            width: "20%",
            label: "Apellidos",
            name: "collecApellido",
            type: "text",
        },
        {
            width: "20%",
            label: "Correo institucional",
            name: "email_institucional_empleado",
            type: "text",
        },
        { width: "1%", label: "", name: "Acciones" },
    ];
    const isLoadinRequest = ref(false);
    const emptyObject = ref(null);
    const permits = ref([]);
    const stateModal = ref(false);
    const evaluaciones = ref(null);
    const dataBeneficiariosToSendModal = ref([]);
    const showModalEvaluacion = ref(false);
    const links = ref([]);
    const lastUrl = ref("/evaluaciones");
    const listDependencias = ref(null);
    columns.forEach((column) => {
        if (column.name === "id_empleado") sortOrders.value[column.name] = 1;
        else sortOrders.value[column.name] = -1;
    });
    const sortKey = ref("id_empleado");
    const perPage = ref(["10", "20", "30"]);
    const tableData = ref({
        draw: 0,
        length: 5,
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

    const getEvaluaciones = async (url = "/evaluaciones") => {
        try {
            isLoadinRequest.value = true;
            lastUrl.value = url;
            tableData.value.draw++;

            const response = await axios.post(url, tableData.value);
            const data = response.data;

            if (tableData.value.draw === data.draw) {
                evaluaciones.value = data.data.data;
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
            getEvaluaciones();
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
            getEvaluaciones();
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
            //console.log(listDependencias.value);
        } catch (error) {
            console.error(error);
        }
    }

    onMounted(async () => {
        getAllDependencias();
        getEvaluaciones();
    });

    return {
        columns,
        sortKey,
        sortOrders,
        tableData,
        pagination,
        listDependencias,
        emptyObject,
        permits,
        stateModal,
        evaluaciones,
        dataBeneficiariosToSendModal,
        showModalEvaluacion,
        links,
        lastUrl,
        perPage,
        isLoadinRequest,
        sortBy,
        getEvaluaciones,
        handleData,
    };
};
