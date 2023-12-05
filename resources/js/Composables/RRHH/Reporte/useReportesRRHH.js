import { ref, inject } from "vue";
import axios from "axios";
import { useHandleError } from "@/Composables/General/useHandleError.js";
import { useShowToast } from "@/Composables/General/useShowToast.js";
import { toast } from "vue3-toastify";
import _ from "lodash";

export const useReportesRRHH = (context) => {
    const swal = inject("$swal");
    const isLoadingRequest = ref(false);
    const errors = ref([])
    const showModal = ref(false)

    const queryResult = ref([])
    const dependencies = ref([])
    const states = ref([])
    const typesOfContract = ref([])

    const getDataForReport = async (parameters) => {
        swal({
            title: "¿Está seguro de generar este reporte?",
            icon: "question",
            iconHtml: "❓",
            confirmButtonText: "Si, generar.",
            confirmButtonColor: "#141368",
            cancelButtonText: "Cancelar",
            showCancelButton: true,
            showCloseButton: true,
        }).then(async (result) => {
            if (result.isConfirmed) {
                cleanObject(errors.value)
                try {
                    isLoadingRequest.value = true;
                    const response = await axios.post('/get-report-employees-rrhh', parameters);
                    queryResult.value = response.data.query
                    console.log(response);
                    showModal.value = false
                } catch (error) {
                    if (err.response.status === 422) {
                        useShowToast(
                            toast.warning,
                            "Tienes algunos errores, por favor verifica los datos enviados."
                        );
                        errors.value = err.response.data.errors;
                    } else {
                        showErrorMessage(err);
                        showModal.value = false
                    }
                } finally {
                    isLoadingRequest.value = false;
                }
            }
        });
    };

    const getInfoForModal = async () => {
        isLoadingRequest.value = true
        await axios.get("/get-info-for-reports")
            .then((response) => {
                dependencies.value = response.data.dependencies
                states.value = response.data.states
                typesOfContract.value = response.data.typesOfContract
            })
            .catch((err) => {
                if (err.response.status === 422) {
                    useShowToast(
                        toast.warning,
                        "Tienes algunos errores, por favor verifica los datos enviados."
                    );
                    errors.value = err.response.data.errors;
                } else {
                    showErrorMessage(err);
                    showModal.value = false
                }
            })
            .finally(() => {
                isLoadingRequest.value = false;
            });
    }

    const cleanObject = (obj) => {
        for (const key in obj) {
            if (Object.prototype.hasOwnProperty.call(obj, key)) {
                obj[key] = '';
            }
        }
    }

    const showErrorMessage = (error) => {
        const { title, text, icon } = useHandleError(error);
        swal({ title: title, text: text, icon: icon, timer: 5000 });
    };

    return {
        errors,
        queryResult,
        dependencies,
        showModal,
        states,
        typesOfContract,
        isLoadingRequest,
        getDataForReport,
        getInfoForModal,
        cleanObject
    };
};
