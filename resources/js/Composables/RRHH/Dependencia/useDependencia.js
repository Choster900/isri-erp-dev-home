import { ref, inject, computed } from "vue";
import axios from "axios";
import { useHandleError } from "@/Composables/General/useHandleError.js";
import { useShowToast } from "@/Composables/General/useShowToast.js";
import { toast } from "vue3-toastify";
import _ from "lodash";

export const useDependencia = (context) => {
    const swal = inject("$swal");
    const isLoadingRequest = ref(false);
    const isLoadingEmployee = ref(false);
    const employees = ref([]);
    const mainCenters = ref([]);
    const dependencies = ref([]);
    const baseOptions = ref([]);
    const errors = ref([]);
    const depInfo = ref({
        id: "",
        type: "",
        jerarquia: "",
        centerId: "",
        personId: "",
        parentId: "",
        depName: "",
        email: "",
        code: "",
        phoneNumber: "",
        address: "",
    });
    const depToShow = ref({})

    const desactiveDependency = (depId, status) => {
        const msg = status === 0 ? 'activar' : 'desactivar'
        swal({
            title: "¿Está seguro de " + msg + " la dependencia?",
            icon: "question",
            iconHtml: "❓",
            confirmButtonText: "Si, " + msg,
            confirmButtonColor: "#141368",
            cancelButtonText: "Cancelar",
            showCancelButton: true,
            showCloseButton: true,
        }).then(async (result) => {
            if (result.isConfirmed) {
                try {
                    isLoadingRequest.value = true;
                    const response = await axios.post("/change-status-dependency", {
                        id: depId,
                        status: status
                    });
                    useShowToast(
                        toast.success,
                        response.data.message
                    );
                    context.emit("get-table")
                } catch (err) {
                    if (err.response.status === 422) {
                        if (err.response.data.logical_error) {
                            useShowToast(
                                toast.error,
                                err.response.data.logical_error
                            );
                        }
                    } else {
                        showErrorMessage(err);
                        getDataToShow()
                    }
                } finally {
                    isLoadingRequest.value = false;
                }
            }
        });
    }

    const getCentrosAtencion = async () => {
        try {
            isLoadingRequest.value = true;
            const response = await axios.get("/get-centros-atencion");
            return {
                data: response.data,
                isError: false,
            };
        } catch (error) {
            showErrorMessage(error);
            return {
                data: [],
                isError: true,
            };
        } finally {
            isLoadingRequest.value = false;
        }
    };

    const getInfoForModalDependencias = async (depId) => {
        try {
            isLoadingRequest.value = true;
            const response = await axios.get(
                `/get-info-modal-dependencias/${depId}`
            );
            return {
                data: response.data,
                isError: false,
            };
        } catch (error) {
            showErrorMessage(error);
            return {
                data: [],
                isError: true,
            };
        } finally {
            isLoadingRequest.value = false;
        }
    };

    const depFilter = computed(() => {
        const result = dependencies.value.filter((element) => {
            return element.id_centro_atencion === depInfo.value.centerId
            && element.value != depInfo.value.id; // Agregar el 'return'
        });
        return result ?? [];
    });


    const asyncFindEmployee = _.debounce(async (query) => {
        try {
            isLoadingEmployee.value = true;
            const response = await axios.post("/search-employee", {
                busqueda: query,
            });
            employees.value = response.data.employees;
        } catch (errors) {
            employees.value = [];
        } finally {
            isLoadingEmployee.value = false;
        }
    }, 350);

    const fetchData = async (depId) => {
        const { data, isError } = await getInfoForModalDependencias(depId);
        if (!isError) {
            mainCenters.value = data.mainCenters;
            depToShow.value = data.dependency
            dependencies.value = data.dependencies

            //Set the employee name
            if (data.dependency != "") {
                if (data.dependency.jefatura) {
                    const pnombre = data.dependency.jefatura.pnombre_persona;
                    const snombre = data.dependency.jefatura.snombre_persona;
                    const tnombre = data.dependency.jefatura.tnombre_persona;
                    const papellido =
                        data.dependency.jefatura.papellido_persona;
                    const sapellido =
                        data.dependency.jefatura.sapellido_persona;
                    const tapellido =
                        data.dependency.jefatura.tapellido_persona;
                    let employeeName = pnombre;
                    if (snombre) employeeName += " " + snombre;
                    if (tnombre) employeeName += " " + tnombre;
                    if (papellido) employeeName += " " + papellido;
                    if (sapellido) employeeName += " " + sapellido;
                    if (tapellido) employeeName += " " + tapellido;

                    let array = {
                        value: data.dependency.id_persona,
                        label: employeeName,
                    };
                    baseOptions.value.push(array);
                }

                Object.assign(depInfo.value, {
                    id: data.dependency.id_dependencia,
                    type: data.dependency.id_tipo_dependencia,
                    jerarquia:
                        data.dependency.jerarquia_organizacion_dependencia ?? 0,
                    personId: data.dependency.id_persona,
                    parentId:
                        data.dependency.jerarquia_organizacion_dependencia ?? 1,
                    centerId: data.dependency.id_centro_atencion,
                    depName: data.dependency.nombre_dependencia,
                    email: data.dependency.email_dependencia,
                    code: data.dependency.codigo_dependencia,
                    phoneNumber: data.dependency.telefono_dependencia,
                    address: data.dependency.direccion_dependencia,
                });
            }
        }
    };

    const storeDependency = async (dependency, url) => {
        swal({
            title: "¿Está seguro de guardar la nueva dependencia?",
            icon: "question",
            iconHtml: "❓",
            confirmButtonText: "Si, Guardar",
            confirmButtonColor: "#141368",
            cancelButtonText: "Cancelar",
            showCancelButton: true,
            showCloseButton: true,
        }).then(async (result) => {
            if (result.isConfirmed) {
                saveDependency(dependency, url);
            }
        });
    };
    const updateDependency = async (dependency, url) => {
        swal({
            title: "¿Está seguro de actualizar la dependencia?",
            icon: "question",
            iconHtml: "❓",
            confirmButtonText: "Si, Actualizar",
            confirmButtonColor: "#141368",
            cancelButtonText: "Cancelar",
            showCancelButton: true,
            showCloseButton: true,
        }).then(async (result) => {
            if (result.isConfirmed) {
                saveDependency(dependency, url);
            }
        });
    };

    const changeBoss = async (dependency, url) => {
        swal({
            title: "¿Está seguro de actualizar el empleado asignado?",
            icon: "question",
            iconHtml: "❓",
            confirmButtonText: "Si, Actualizar",
            confirmButtonColor: "#141368",
            cancelButtonText: "Cancelar",
            showCancelButton: true,
            showCloseButton: true,
        }).then(async (result) => {
            if (result.isConfirmed) {
                saveDependency(dependency, url);
            }
        });
    };

    const saveDependency = async (dependency, url) => {
        isLoadingRequest.value = true;
        await axios
            .post(url, dependency)
            .then((response) => {
                handleSuccessResponse(response)
            })
            .catch((error) => {
                handleErrorResponse(error)
            })
            .finally(() => {
                isLoadingRequest.value = false;
            });
    };

    const showErrorMessage = (error) => {
        const { title, text, icon } = useHandleError(error);
        swal({ title: title, text: text, icon: icon, timer: 5000 });
    };

    const handleErrorResponse = (err) => {
        if (err.response.status === 422) {
            useShowToast(
                toast.warning,
                "Tienes algunos errores, por favor verifica los datos enviados."
            );
            errors.value = err.response.data.errors;
        } else {
            showErrorMessage(err);
            context.emit("cerrar-modal")
        }
    };

    const handleSuccessResponse = (response) => {
        useShowToast(
            toast.success,
            response.data.message
        );
        context.emit("cerrar-modal")
        context.emit("get-table")
    }

    return {
        getCentrosAtencion,
        getInfoForModalDependencias,
        fetchData,
        storeDependency,
        updateDependency,
        desactiveDependency,
        changeBoss,
        asyncFindEmployee,
        isLoadingRequest,
        isLoadingEmployee,
        employees,
        baseOptions,
        depInfo,
        mainCenters,
        errors,
        depToShow,
        depFilter
    };
};