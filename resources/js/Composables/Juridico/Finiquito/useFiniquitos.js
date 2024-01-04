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
    const centros = ref([]);
    const personId = ref("")
    const amount = ref(0)
    const persons = ref([]);
    const isLoadingPerson = ref(false);

    const getInfoForModalFiniquitos = async (settlementId) => {
        try {
            isLoadingRequest.value = true;
            const response = await axios.get(
                `/get-info-modal-finiquitos`
            );
            empleados.value = response.data.empleados
            console.log(empleados.value);
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
        empleados.forEach((empleado) => {
            const idCentro = empleado.plazas_asignadas[0].centro_atencion.id_centro_atencion;
            // Buscar el índice del centro correspondiente en 'centros'
            const indiceCentro = centros.value.findIndex((centro) => centro.id === idCentro);

            if (indiceCentro === -1) {
                const emp = {
                    ...empleado, // Copiar las propiedades del empleado
                };
                // Si el centro no existe en 'centros', se agrega con un nuevo arreglo de empleados
                centros.value.push({
                    id: idCentro,
                    center: empleado.plazas_asignadas[0].centro_atencion.codigo_centro_atencion,
                    date: '',
                    startTime: '',
                    endTime: '',
                    interval : '',
                    empleados: [emp],
                });
            } else {
                // Si el centro ya existe, se agrega el empleado al arreglo existente de empleados para ese centro
                centros.value[indiceCentro].empleados.push(empleado);
            }
        });
        //console.log(centros.value);
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

    const showErrorMessage = (err) => {
        const { title, text, icon } = useHandleError(err);
        swal({ title: title, text: text, icon: icon, timer: 5000 });
    };

    return {
        isLoadingRequest, centros, empleados, personId, amount,
        isLoadingPerson, persons,
        getInfoForModalFiniquitos, asyncFindPerson,
    };
};
