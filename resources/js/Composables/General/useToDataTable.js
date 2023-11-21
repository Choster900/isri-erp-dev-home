import axios from "axios";
import { onMounted, ref } from "vue";

export const useToDataTable = (columns, requestUrl, columnToSort, dir) => {
    const sortOrders = ref([]);
    const isLoadinRequest = ref(false);
    const emptyObject = ref(null);
    const dataToShow = ref([]);
    const links = ref([]);
    const lastUrl = ref(requestUrl);

    columns.forEach((column) => {
        if (column.name === columnToSort) sortOrders.value[column.name] = 1;
        else sortOrders.value[column.name] = -1;
    });
    const sortKey = columnToSort;
    const perPage = ref(["10", "20", "30"]);
    const tableData = ref({
        draw: 0,
        length: 5,
        column: 0,
        dir: dir,
        total: "",
        currentPage: "",
        search: {},
    });

    const getDataToShow = async (url = requestUrl) => {
        try {
            isLoadinRequest.value = true;
            lastUrl.value = url;
            tableData.value.draw++;

            const response = await axios.post(url, tableData.value);
            const data = response.data;

            if (tableData.value.draw === data.draw) {
                dataToShow.value = data.data.data;
                tableData.total = data.data.total;
                links.value = data.data.links;
                links.value[0].label = "Anterior";
                links.value[links.value.length - 1].label = "Siguiente";
            }

            emptyObject.value = persona.value.length === 0;
        } catch (error) { //pending
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
            getDataToShow();
        }
    };

    const handleData = (myEventData) => {
        tableData.value.search = myEventData;
        const data = Object.values(myEventData);
        if (data.every((error) => error === "")) {
            this.getJobPositions();
        }
    };

    onMounted(async () => {
        getDataToShow();
    });

    return {
        sortKey,
        sortOrders,
        tableData,
        emptyObject,
        dataToShow,
        links,
        lastUrl,
        perPage,
        isLoadinRequest,
        sortBy,
        getDataToShow,
        handleData,
    };
};
