import { ref, inject, computed } from "vue";
import axios from "axios";
import { useHandleError } from "@/Composables/General/useHandleError.js";
import { useShowToast } from "@/Composables/General/useShowToast.js";
import { toast } from "vue3-toastify";
import _ from "lodash";
import moment from 'moment';

export const useFiniquitos = (context) => {
    const swal = inject("$swal");
    const isLoadingRequest = ref(false);
    const errors = ref([]);
    const empleados = ref([])
    const finiquito = ref([])
    const persons = ref([]);
    const isLoadingPerson = ref(false);

    const getInfoForModalFiniquitos = async () => {
        try {
            isLoadingRequest.value = true;
            const response = await axios.get(
                `/get-info-modal-finiquitos`
            );
            empleados.value = response.data.empleados
            //console.log(empleados.value);
            setModalValues(empleados.value)
        } catch (err) {
            console.log(err);
            if (err.response.data.logical_error) {
                useShowToast(toast.error, err.response.data.logical_error);
                context.emit("get-table");
            } else {
                showErrorMessage(err);
            }
            context.emit("cerrar-modal");
        } finally {
            isLoadingRequest.value = false;
        }
    };

    const setModalValues = (empleados) => {
        finiquito.value.personId = ""
        finiquito.value.amount = 0
        finiquito.value.centros = []

        empleados.forEach((empleado) => {
            const idCentro = empleado.plazas_asignadas[0].centro_atencion.id_centro_atencion;
            // Buscar el índice del centro correspondiente en 'centros'
            const indiceCentro = finiquito.value.centros.findIndex((centro) => centro.id === idCentro);

            if (indiceCentro === -1) {
                const emp = {
                    ...empleado, // Copiar las propiedades del empleado
                };
                // Si el centro no existe en 'centros', se agrega con un nuevo arreglo de empleados
                finiquito.value.centros.push({
                    id: idCentro,
                    center: empleado.plazas_asignadas[0].centro_atencion.codigo_centro_atencion,
                    date: '',
                    startTime: '',
                    endTime: '',
                    interval: '',
                    empleados: [emp],
                });
            } else {
                // Si el centro ya existe, se agrega el empleado al arreglo existente de empleados para ese centro
                finiquito.value.centros[indiceCentro].empleados.push(empleado);
            }
        });
        //console.log(finiquito.value);
    };

    const asyncFindPerson = _.debounce(async (query) => {
        try {
            isLoadingPerson.value = true;
            const response = await axios.post("/search-person-jrd", {
                busqueda: query,
            });
            persons.value = response.data.persons;
        } catch (errors) {
            persons.value = [];
        } finally {
            isLoadingPerson.value = false;
        }
    }, 350);

    const storeFiniquitos = async (finiq) => {
        //console.log(finiq);
        swal({
            title: "¿Está seguro de guardar los finiquitos para todos los empleados?",
            icon: "question",
            iconHtml: "❓",
            confirmButtonText: "Si, Guardar",
            confirmButtonColor: "#141368",
            cancelButtonText: "Cancelar",
            showCancelButton: true,
            showCloseButton: true,
        }).then(async (result) => {
            if (result.isConfirmed) {
                saveFiniquito(finiq, '/store-finiquitos');
            }
        });
    };

    const saveFiniquito = async (finiq, url) => {
        //console.log(finiquitos);
        isLoadingRequest.value = true;
        await axios
            .post(url, {
                personId: finiq.personId,
                amount: finiq.amount,
                centers: finiq.centros
            })
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

    const handleErrorResponse = (err) => {
        if (err.response.status === 422) {
            if (err.response.data.logical_error) {
                console.log(err.response);
                useShowToast(toast.error, err.response.data.logical_error);

            } else {
                useShowToast(
                    toast.warning,
                    "Tienes algunos errores, por favor verifica los datos enviados."
                );
                errors.value = err.response.data.errors;
            }
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

    const showErrorMessage = (err) => {
        const { title, text, icon } = useHandleError(err);
        swal({ title: title, text: text, icon: icon, timer: 5000 });
    };

    return {
        isLoadingRequest, finiquito, empleados, isLoadingPerson, persons,
        getInfoForModalFiniquitos, asyncFindPerson, storeFiniquitos,
    };
};
