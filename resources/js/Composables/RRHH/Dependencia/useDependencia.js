import { ref, inject } from "vue";
import axios from "axios";
import { useHandleError } from "@/Composables/General/useHandleError.js";
import _ from 'lodash';

export const useDependencia = () => {
    const swal = inject("$swal");
    const isLoadingRequest = ref(false);
    const isLoadingEmployee = ref(false)
    const employees = ref([])
    const mainCenters = ref([])
    const baseOptions = ref([]);
    const depInfo = ref({
        id: "",
        personId: "",
        parentId: "",
        depName: "",
        email: "",
        code: "",
        phoneNumber: "",
        address: "",
    });


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

    
    const asyncFindEmployee = _.debounce(async (query) => {
        try {
            isLoadingEmployee.value = true;
            const response = await axios.post("/search-employee", { busqueda: query });
            employees.value = response.data.employees;
        } catch (errors) {
            employees.value = []
            console.log(errors);
        } finally {
            isLoadingEmployee.value = false;
        }
    }, 350)

    const fetchData = async (depId) => {
        const { data, isError } = await getInfoForModalDependencias(
            depId
        );
        if (!isError) {
            mainCenters.value = data.dependencies;

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
                    personId: data.dependency.id_persona,
                    parentId: data.dependency.dep_id_dependencia ?? 1,
                    depName: data.dependency.nombre_dependencia,
                    email: data.dependency.email_dependencia,
                    code: data.dependency.codigo_dependencia,
                    phoneNumber: data.dependency.telefono_dependencia,
                    address: data.dependency.direccion_dependencia,
                });
            }
        }
    };

    const showErrorMessage = (error) => {
        const { title, text, icon } = useHandleError(error);
        swal({ title: title, text: text, icon: icon, timer: 5000 });
    };

    return {
        getCentrosAtencion,
        getInfoForModalDependencias,
        fetchData,
        asyncFindEmployee,
        isLoadingRequest,
        isLoadingEmployee,
        employees,
        baseOptions,
        depInfo,
        mainCenters,
    };
};
