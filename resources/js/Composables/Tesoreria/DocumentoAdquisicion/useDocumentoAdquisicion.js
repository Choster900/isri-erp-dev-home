import { ref, inject, computed } from "vue";
import axios from "axios";
import { useHandleError } from "@/Composables/General/useHandleError.js";
import { useShowToast } from "@/Composables/General/useShowToast.js";
import { toast } from "vue3-toastify";
import _ from "lodash";
import moment from 'moment';
export const useDocumentoAdquisicion = (context) => {
    const swal = inject("$swal");
    const isLoadingRequest = ref(false);
    const new_item = ref(true);
    const currentPage = ref(1)
    const errors = ref([]);
    const index_errors = ref([]);
    const item_errors = ref([]);
    const backend_errors = ref([]);
    const doc_types = ref([])
    const management_types = ref([])
    const financing_sources = ref([])
    const suppliers = ref([])

    const acq_doc = ref(
        {
            id: '',
            type_id: '',
            management_type_id: '',
            supplier_id: '',
            number: '',
            management_number: '',
            award_number: '',
            award_date: '',
            total: '',
            items: []
        }
    )
    const array_item = ref(
        {
            id: '',
            index: '',
            financing_source_id: '',
            name_financing_source:'',
            commitment_number: '',
            amount: '',
            contract_manager: '',
            name: '',
            has_quedan: '',
            selected: false,
            deleted: false
        }
    )
    const config = {
        altInput: true,
        //static: true,
        monthSelectorType: 'static',
        altFormat: "d/m/Y",
        dateFormat: "Y-m-d",
        locale: {
            firstDayOfWeek: 1,
            weekdays: {
                shorthand: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
                longhand: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
            },
            months: {
                shorthand: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
                longhand: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
            },
        },
    }

    const getInfoForModalDocumentoAdquisicion = async (id) => {
        try {
            isLoadingRequest.value = true;
            const response = await axios.get(
                `/get-info-modal-doc-adquisicion/${id}`
            );
            setModalValues(response.data)
        } catch (err) {
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

    const setModalValues = (data) => {
        management_types.value = data.management_types
        financing_sources.value = data.financing_sources
        suppliers.value = data.suppliers
        doc_types.value = data.doc_types

        acq_doc.value.id = data.acqDoc.id_doc_adquisicion ?? ""
        acq_doc.value.type_id = data.acqDoc.id_tipo_doc_adquisicion ?? ""
        acq_doc.value.management_type_id = data.acqDoc.id_tipo_gestion_compra ?? ""
        acq_doc.value.supplier_id = data.acqDoc.id_proveedor ?? ""
        acq_doc.value.number = data.acqDoc.numero_doc_adquisicion ?? ""
        acq_doc.value.management_number = data.acqDoc.numero_gestion_doc_adquisicion ?? ""
        acq_doc.value.award_number = data.acqDoc.numero_adjudicacion_doc_adquisicion ?? ""
        acq_doc.value.award_date = data.acqDoc.fecha_adjudicacion_doc_adquisicion ?? ""
        acq_doc.value.total = data.acqDoc.monto_doc_adquisicion ?? ""
        if(data.acqDoc.detalles.length > 0){
            data.acqDoc.detalles.forEach((value, index) => {
                var arrayInsert = {
                    id: value.id_det_doc_adquisicion,
                    index: index,
                    financing_source_id: value.id_proy_financiado,
                    name_financing_source: value.fuente_financiamiento.nombre_proy_financiado,
                    commitment_number: value.compromiso_ppto_det_doc_adquisicion,
                    amount: value.monto_det_doc_adquisicion,
                    contract_manager: value.admon_det_doc_adquisicion,
                    name: value.nombre_det_doc_adquisicion,
                    has_quedan: value.quedan.length > 0 ? true : false,
                    selected: false,
                    deleted: false,
                };
                acq_doc.value.items.push(arrayInsert)
            })
        }else{
            acq_doc.items = []
        }
    };

    const storeDocumentoAdquisicion = async (doc) => {
        swal({
            title: '¿Está seguro de guardar el nuevo concepto de ingreso?',
            icon: 'question',
            iconHtml: '❓',
            confirmButtonText: 'Si, Guardar',
            confirmButtonColor: '#141368',
            cancelButtonText: 'Cancelar',
            showCancelButton: true,
            showCloseButton: true
        }).then(async (result) => {
            if (result.isConfirmed) {
                saveConceptoIngreso(doc, '/save-acq-doc');
            }
        });
    };

    const updateDocumentoAdquisicion = async (doc) => {
        swal({
            title: '¿Está seguro de actualizar el concepto de ingreso?',
            icon: 'question',
            iconHtml: '❓',
            confirmButtonText: 'Si, Actualizar',
            confirmButtonColor: '#141368',
            cancelButtonText: 'Cancelar',
            showCancelButton: true,
            showCloseButton: true
        }).then(async (result) => {
            if (result.isConfirmed) {
                saveConceptoIngreso(doc, '/update-acq-doc');
            }
        });
    };

    const saveConceptoIngreso = async (doc, url) => {
        isLoadingRequest.value = true;
        await axios
            .post(url, doc)
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
        isLoadingRequest, errors, acq_doc, config, array_item, index_errors, 
        item_errors, backend_errors, new_item, currentPage, doc_types, management_types,
        financing_sources, suppliers,
        getInfoForModalDocumentoAdquisicion, storeDocumentoAdquisicion, updateDocumentoAdquisicion
    }
}





