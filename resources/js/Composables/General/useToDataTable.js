import axios from "axios";
import { onMounted, ref, inject } from "vue";
import { useHandleError } from "@/Composables/General/useHandleError.js";
import { useShowToast } from "@/Composables/General/useShowToast.js";
import { toast } from "vue3-toastify";

export const useToDataTable = (columns, requestUrl, columnToSort, dir, col = 0) => {
    const swal = inject("$swal");
    const sortOrders = ref([]);
    const isLoadinRequest = ref(false);
    const isLoadingTop = ref(false);
    const emptyObject = ref(null);
    const dataToShow = ref([]);
    const links = ref([]);

    columns.forEach((column) => {
        if (column.name === columnToSort) sortOrders.value[column.name] = 1;
        else sortOrders.value[column.name] = -1;
    });
    const sortKey = ref(columnToSort);
    const perPage = ref(["10", "20", "30"]);
    const tableData = ref({
        draw: 0,
        length: 5,
        column: col,
        dir: dir,
        total: "",
        currentPage: "",
        search: {},
    });

    
    const changeStatusElement = async (elementId, status, url) => {
        const msg = status === 0 ? 'activar' : 'desactivar'
        swal({
            title: "¿Está seguro de " + msg + " este registro?",
            icon: "question",
            iconHtml: "❓",
            confirmButtonText: "Si, " + msg,
            confirmButtonColor: "#141368",
            cancelButtonText: "Cancelar",
            showCancelButton: true,
            showCloseButton: true,
        }).then(async (result) => {
            if (result.isConfirmed) {
                isLoadingTop.value = true;
                try {
                    const response = await axios.post(url, {
                        id: elementId,
                        status: status
                    });
                    useShowToast(
                        toast.success,
                        response.data.message
                    );
                    getDataToShow(tableData.value.currentPage)
                } catch (err) {
                    if (err.response.status === 422) {
                        if (err.response.data.logical_error) {
                            useShowToast(
                                toast.error,
                                err.response.data.logical_error
                            );
                        }
                        getDataToShow(tableData.value.currentPage)
                    } else {
                        showErrorMessage(err);
                    }
                } finally {
                    isLoadingTop.value = false;
                }
            }
        });
    }

    const getDataToShow = async (url = requestUrl) => {
        try {
            isLoadinRequest.value = true;
            tableData.value.draw++;
            tableData.value.currentPage = url

            const response = await axios.post(url, tableData.value);
            const data = response.data;

            if (tableData.value.draw === data.draw) {
                dataToShow.value = data.data.data;
                tableData.value.total = data.data.total;
                links.value = data.data.links;
                links.value[0].label = "Anterior";
                links.value[links.value.length - 1].label = "Siguiente";
            }
            emptyObject.value = dataToShow.value.length === 0;
        } catch (error) {
            const { title, text, icon } = useHandleError(error);
            swal({ title: title, text: text, icon: icon, timer: 5000 });
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
            getDataToShow();
        }
    };

    const showErrorMessage = (err) => {
        const { title, text, icon } = useHandleError(err);
        swal({ title: title, text: text, icon: icon, timer: 5000 });
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
        perPage,
        isLoadinRequest,
        isLoadingTop,
        changeStatusElement,
        sortBy,
        getDataToShow,
        handleData,
    };
};
