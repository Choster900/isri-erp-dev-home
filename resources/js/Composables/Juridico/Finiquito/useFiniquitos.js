import { ref, inject, computed } from "vue";
import axios from "axios";
import { useHandleError } from "@/Composables/General/useHandleError.js";
import { useShowToast } from "@/Composables/General/useShowToast.js";
import { toast } from "vue3-toastify";
import _ from "lodash";

export const useFiniquitos = (context) => {
    const swal = inject("$swal");
    const isLoadingRequest = ref(false);
    const errors = ref([]);
    const allFiniquitos = ref([])
    const centros = ref([]);
    const personId = ref("")
    const amount = ref(0)
    const baseOption = ref([]);
    const persons = ref([]);
    const isLoadingPerson = ref(false);

    const getInfoForModalFiniquitos = async (settlementId) => {
        try {
            isLoadingRequest.value = true;
            const response = await axios.get(
                `/get-info-modal-finiquitos/${settlementId}`
            );
            allFiniquitos.value = response.data.ejercicio
            setModalValues(allFiniquitos.value.finiquitos_ejercicio_fiscal)
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

    const setModalValues = (finiquitos) => {
        const idPersonaSet = new Set();
        finiquitos.forEach((finiquito) => {
            idPersonaSet.add(finiquito.id_persona);
            const idCentro = finiquito.empleado.primer_centro_atencion.id_centro_atencion;
            // Buscar el índice del centro correspondiente en 'centros'
            const indiceCentro = centros.value.findIndex((centro) => centro.id === idCentro);

            if (indiceCentro === -1) {
                const finq = {
                    ...finiquito, // Copiar las propiedades del empleado
                };
                // Si el centro no existe en 'centros', se agrega con un nuevo arreglo de empleados
                centros.value.push({
                    id: idCentro,
                    center: finiquito.empleado.primer_centro_atencion.centro_atencion.codigo_centro_atencion,
                    date: finiquito.fecha_firma_finiquito_laboral,
                    finiquitos: [finq],
                });
            } else {
                // Si el centro ya existe, se agrega el empleado al arreglo existente de empleados para ese centro
                centros.value[indiceCentro].finiquitos.push(finiquito);
            }
        });
        //Convertimos a Array
        const arrayPersons = Array.from(idPersonaSet)
        //Verificamos si solo hay una persona a cargo del finiquito, si hay mas de una, la funcionalidad debe ser creada
        if (arrayPersons.length === 1) {
            personId.value = finiquitos[0].persona.id_persona

            const pnombre = finiquitos[0].persona.pnombre_persona;
            const snombre = finiquitos[0].persona.snombre_persona;
            const tnombre = finiquitos[0].persona.tnombre_persona;
            const papellido = finiquitos[0].persona.papellido_persona;
            const sapellido = finiquitos[0].persona.sapellido_persona;
            const tapellido = finiquitos[0].persona.tapellido_persona;
            let employeeName = pnombre;
            if (snombre) employeeName += " " + snombre;
            if (tnombre) employeeName += " " + tnombre;
            if (papellido) employeeName += " " + papellido;
            if (sapellido) employeeName += " " + sapellido;
            if (tapellido) employeeName += " " + tapellido;

            let array = {
                value: finiquitos[0].persona.id_persona,
                label: employeeName,
            };
            baseOption.value.push(array);
        } else {
            //Tiene mas de una persona como notario.
            console.log('Existe mas de una persona de notario o no hay nadie a cargo.');
            context.emit("cerrar-modal")
            useShowToast(
                toast.error,
                "No es posible procesar la petición, consulte con informatica."
            );
        }
    };

    const asyncFindPerson = _.debounce(async (query) => {
        try {
            isLoadingPerson.value = true;
            const response = await axios.post("/search-person-jrd", {
                busqueda: query,
            });
            console.log(response);
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
        isLoadingRequest, centros, allFiniquitos, personId, amount, baseOption,
        isLoadingPerson, persons,
        getInfoForModalFiniquitos, asyncFindPerson,
    };
};
