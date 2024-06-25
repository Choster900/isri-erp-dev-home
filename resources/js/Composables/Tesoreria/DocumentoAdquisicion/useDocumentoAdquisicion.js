import { ref, inject, computed } from "vue";
import axios from "axios";
import { useHandleError } from "@/Composables/General/useHandleError.js";
import { useShowToast } from "@/Composables/General/useShowToast.js";
import { toast } from "vue3-toastify";
import _ from "lodash";
export const useDocumentoAdquisicion = (context) => {
    const swal = inject("$swal");
    const isLoadingRequest = ref(false);
    const new_item = ref(true);
    const showItemInfo = ref(false)
    const currentPage = ref(1)
    const errors = ref([]);
    const index_errors = ref([]);
    const item_errors = ref([]);
    const backend_errors = ref([]);
    const doc_types = ref([])
    const management_types = ref([])
    const financing_sources = ref([])
    const suppliers = ref([])
    const employees = ref([])

    const procesoCompra = ref([]) //Array para multiselect
    const tipoProcesoCompraValue = ref(null)
    const onSelectMultiSelectProcesoCompra = (e) => {
        tipoProcesoCompraValue.value = procesoCompra.value.find(proc => proc.value === e).idTipoProcesoCompra
    };

    const acq_doc = ref(
        {
            id: '',
            type_id: '',
            management_type_id: '',
            supplier_id: '',
            procesoCompraId: '',
            tipoProceso: '',
            number: '',
            management_number: '',
            award_number: '',
            award_date: '',
            total: '',
            items: [],
            employees: []
        }
    )
    const array_item = ref(
        {
            id: '',
            index: '',
            financing_source_id: '',
            name_financing_source: '',
            commitment_number: '',
            amount: '',
            contract_manager: '',
            name: '',
            has_quedan: '',
            selected: false,
            deleted: false,
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
        console.log(data);
        //Set the multiselects options
        management_types.value = data.management_types
        financing_sources.value = data.financing_sources
        suppliers.value = data.suppliers
        procesoCompra.value = data.procesoCompra
        doc_types.value = data.doc_types
        employees.value = data.employees
        //Set the doc attributes
        acq_doc.value.id = data.acqDoc.id_doc_adquisicion ?? ""
        acq_doc.value.type_id = data.acqDoc.id_tipo_doc_adquisicion ?? ""
        acq_doc.value.management_type_id = data.acqDoc.id_tipo_gestion_compra ?? ""
        acq_doc.value.supplier_id = data.acqDoc.id_proveedor ?? ""
        acq_doc.value.number = data.acqDoc.numero_doc_adquisicion ?? ""
        acq_doc.value.management_number = data.acqDoc.numero_gestion_doc_adquisicion ?? ""
        acq_doc.value.award_number = data.acqDoc.numero_adjudicacion_doc_adquisicion ?? ""
        acq_doc.value.award_date = data.acqDoc.fecha_adjudicacion_doc_adquisicion ?? ""
        acq_doc.value.total = data.acqDoc.monto_doc_adquisicion ?? ""
        acq_doc.value.procesoCompraId = data.acqDoc.id_proceso_compra ?? ""
        onSelectMultiSelectProcesoCompra(acq_doc.value.procesoCompraId)
        //Set the doc managers
        acq_doc.value.employees = data.acqDoc.administradores.map(element => element.id_empleado);
        //Set each document item
        if (data.acqDoc.detalles) {
            acq_doc.value.items = data.acqDoc.detalles.map((value, index) => ({
                id: value.id_det_doc_adquisicion,
                index: index,
                financing_source_id: value.id_proy_financiado,
                name_financing_source: value.fuente_financiamiento.codigo_proy_financiado,
                commitment_number: value.compromiso_ppto_det_doc_adquisicion,
                amount: value.monto_det_doc_adquisicion,
                contract_manager: value.admon_det_doc_adquisicion,
                name: value.nombre_det_doc_adquisicion,
                has_quedan: value.quedan.length > 0,
                selected: false,
                deleted: false,
            }));
        } else {
            acq_doc.value.items = [];
        }

    };

    const addItem = () => {
        const fieldMappingsItem = {
            financing_source_id: 'Fuente financiamiento',
            commitment_number: 'Numero compromiso',
            amount: 'Monto Compromiso',
            name: 'Nombre item',
        };

        // Ajustando en requiredFields para hacer que 'amount' sea opcional si tipoProcesoCompraValue es igual a 2
        const requiredFields = [
            'financing_source_id',
            'commitment_number',
            // (tipoProcesoCompraValue.value === 1 && 'amount') || '', // Hacer 'amount' opcional si tipoProcesoCompraValue es igual a 2
            'name',
        ];

        if (tipoProcesoCompraValue.value === 2) {
            requiredFields.push('amount');
        } else {
            const index = requiredFields.indexOf('amount');
            if (index !== -1) {
                requiredFields.splice(index, 1);
            }
        }

        requiredFields.forEach(field => {
            console.log(field);
            if (field === 'amount' && tipoProcesoCompraValue.value === 2) {
                // Si tipoProcesoCompraValue es igual a 2, no se requiere el monto
                item_errors.value[field] = '';
            } else if (array_item.value[field]) {
                item_errors.value[field] = '';
            } else {
                item_errors.value[field] = `El campo ${fieldMappingsItem[field]} es obligatorio.`; // Asegúrate de que fieldMappingsItem[field] esté definido
            }
        });
        console.log(array_item.value);

        const err = Object.values(item_errors.value);
        console.log(err);

        if (err.every(error => error === '')) {
            const parsedAmount = parseFloat(array_item.value.amount);
            const formattedAmount = parsedAmount.toFixed(2);

            if (formattedAmount <= 0 && tipoProcesoCompraValue.value == 1) {
                useShowToast(toast.warning, "El monto del item debe ser mayor a cero.");
            } else {
                let name_source = financing_sources.value.filter((e) => e.value === array_item.value.financing_source_id);

                const arrayInsert = {
                    id: array_item.value.id != '' ? array_item.value.id : '',
                    index: array_item.value.index != '' ? array_item.value.index : '',
                    financing_source_id: array_item.value.financing_source_id != '' ? array_item.value.financing_source_id : '',
                    commitment_number: array_item.value.commitment_number != '' ? array_item.value.commitment_number : '',
                    amount: formattedAmount ? formattedAmount : 0,
                    contract_manager: array_item.value.contract_manager != '' ? array_item.value.contract_manager : '',
                    name_financing_source: name_source[0].codigo_proy_financiado,
                    name: array_item.value.name != '' ? array_item.value.name : '',
                    has_quedan: array_item.value.has_quedan != '' ? array_item.value.has_quedan : false,
                    selected: false,
                    deleted: false
                };

                if (acq_doc.value.items.some(item => item.commitment_number === array_item.value.commitment_number && !item.selected && !item.deleted)) {
                    useShowToast(toast.warning, "El numero de compromiso " + array_item.value.commitment_number + " ya ha sido agregado.");
                } else {
                    if (new_item.value) {
                        acq_doc.value.items.push(arrayInsert)
                    } else {
                        Object.assign(acq_doc.value.items[array_item.value.index], arrayInsert);
                    }
                    cleanAndUpdateTotal()
                    showItemInfo.value = false
                }
            }
        } else {
            useShowToast(toast.warning, "Tienes algunos errores, por favor verifica los datos enviados.");
        }
    };


    const cleanAndUpdateTotal = () => {
        updateTotal()
        new_item.value = true
        // Empty the array
        Object.keys(array_item.value).forEach(key => {
            array_item.value[key] = '';
        });
    }

    const updateTotal = () => {
        var sum = 0;
        for (var i = 0; i < acq_doc.value.items.length; i++) {
            if (acq_doc.value.items[i].deleted == false) {
                var amount = parseFloat(acq_doc.value.items[i].amount);
                if (!isNaN(amount)) {
                    sum += amount;
                }
            }
        }
        acq_doc.value.total = sum.toFixed(2);
    }

    const cleanArrayItem = () => {
        item_errors.value = []
        if (array_item.value.index != -1) {
            if (acq_doc.value.items[array_item.value.index]) {
                acq_doc.value.items[array_item.value.index].selected = false
            }
        }
        // Empty the array
        Object.keys(array_item.value).forEach(key => {
            array_item.value[key] = '';
        });
        new_item.value = true
        showItemInfo.value = false
    }

    const storeDocumentoAdquisicion = async (doc) => {
        console.log(doc);
        const all_deleted = acq_doc.value.items.every(item => item.deleted === true);
        if (all_deleted) {
            useShowToast(toast.warning, "Debes agregar al menos un item al documento.");
        } else {
            swal({
                title: '¿Está seguro de guardar el nuevo documento de adquisicion?',
                icon: 'question',
                iconHtml: '❓',
                confirmButtonText: 'Si, Guardar',
                confirmButtonColor: '#141368',
                cancelButtonText: 'Cancelar',
                showCancelButton: true,
                showCloseButton: true
            }).then(async (result) => {
                if (result.isConfirmed) {
                    saveDocAdquisicion(doc, '/save-acq-doc');
                }
            });
        }
    };

    const updateDocumentoAdquisicion = async (doc) => {
        console.log(doc);
        const all_deleted = acq_doc.value.items.every(item => item.deleted === true);
        if (all_deleted) {
            useShowToast(toast.warning, "Debes agregar al menos un item al documento.");
        } else {
            swal({
                title: '¿Está seguro de actualizar el documento de adquisicion?',
                icon: 'question',
                iconHtml: '❓',
                confirmButtonText: 'Si, Actualizar',
                confirmButtonColor: '#141368',
                cancelButtonText: 'Cancelar',
                showCancelButton: true,
                showCloseButton: true
            }).then(async (result) => {
                if (result.isConfirmed) {
                    saveDocAdquisicion(doc, '/update-acq-doc');
                }
            });
        }
    };

    const saveDocAdquisicion = async (doc, url) => {
        isLoadingRequest.value = true;

        doc.comesFrom = window.location.href.split('/')[3];
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
                context.emit("cerrar-modal")
                context.emit("get-table")
            } else {
                console.log(err.response);
                useShowToast(toast.warning, "Tienes algunos errores por favor verifica tus datos.");
                backend_errors.value = err.response.data.errors;
                // Itera sobre las propiedades del objeto de errores
                for (const key in backend_errors.value) {
                    var parts = key.split(".");
                    if (parts.length > 1) {
                        var index = parseInt(parts[1]);
                        index_errors.value.push(index)
                    } else {
                        currentPage.value = 1
                    }
                }
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

    const goToNextPage = () => {
        const fieldMappings = {
            type_id: 'Tipo documento',
            management_type_id: 'Tipo gestion',
            supplier_id: 'Proveedor',
            number: 'Numero documento',
            management_number: 'Numero gestion',
            award_date: 'Fecha adjudicacion',
            procesoCompraId: 'Proceso de compra'
        };

        if (currentPage.value === 1) {
            const requiredFields = [
                'type_id',
                'management_type_id',
                'supplier_id',
                'number',
                'management_number',
                'award_date',
                'procesoCompraId'
            ];

            let page_with_errors = '';

            requiredFields.forEach(field => {
                if (acq_doc.value[field]) {
                    errors.value[field] = '';
                } else {
                    errors.value[field] = `El campo ${fieldMappings[field]} es obligatorio.`;
                }
            });
            if (Object.values(errors.value).some(error => error !== '')) {
                page_with_errors = 1;
            }
        }

        const err = Object.values(errors.value);
        if (err.every(error => error === '')) {
            item_errors.value = []
            currentPage.value++;
        } else {
            if (page_with_errors !== currentPage.value) {
                currentPage.value++;
            } else {
                useShowToast(toast.warning, "Tienes algunos errores, por favor verifica los datos enviados.");
            }
        }
    }

    const editItem = (itemEdit, index) => {
        new_item.value = false
        item_errors.value = []
        const itemToClean = acq_doc.value.items.find(item => item.selected);
        if (itemToClean) {
            itemToClean.selected = false;
        }
        array_item.value.id = itemEdit.id
        array_item.value.name = itemEdit.name
        array_item.value.commitment_number = itemEdit.commitment_number
        array_item.value.amount = itemEdit.amount
        array_item.value.contract_manager = itemEdit.contract_manager
        array_item.value.index = index
        array_item.value.financing_source_id = itemEdit.financing_source_id
        array_item.value.has_quedan = itemEdit.has_quedan
        array_item.value.deleted = false
        array_item.value.selected = true
        acq_doc.value.items[index].selected = true
        //Flag to show item-info
        showItemInfo.value = true
    }

    const deleteItem = (index) => {
        swal({
            title: 'Eliminar item.',
            text: "¿Estas seguro de eliminar temporalmente? Los cambios surtiran efecto hasta que actualices el documento.",
            icon: 'warning',
            showCancelButton: true,
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#DC2626',
            cancelButtonColor: '#4B5563',
            confirmButtonText: 'Si, Eliminar.'
        }).then((result) => {
            if (result.isConfirmed) {
                if (acq_doc.value.items[index].has_quedan) {
                    useShowToast(toast.error, "No puedes eliminar este item porque tiene quedan asignados, elimina primero los quedan asignados.");
                } else {
                    if (acq_doc.value.items[index].selected) {
                        // Empty the array
                        Object.keys(array_item.value).forEach(key => {
                            array_item.value[key] = '';
                        });
                    }
                    if (acq_doc.value.items[index].id === '') {
                        acq_doc.value.items.splice(index, 1)
                    } else {
                        acq_doc.value.items[index].deleted = true
                        acq_doc.value.items[index].selected = false
                    }
                    updateTotal()
                }
            }
        })
    }

    const item_available = computed(() => {
        let exist = false
        if (acq_doc.value.items) {
            for (var i = 0; i < acq_doc.value.items.length; i++) {
                if (acq_doc.value.items[i].deleted == false) {
                    exist = true
                }
            }
            if (exist) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    });

    const itemSelected = computed(() => {
        const itemToClean = acq_doc.value.items.find(item => item.selected);
        if (itemToClean) {
            return true
        } else {
            return false
        }
    });

    const selectEmployees = (emp) => {
        if (emp.length > 10) {
            useShowToast(toast.warning, "No puedes agregar más de 10 empleados.");
            emp.pop(); // Elimina el último elemento del array
        }
    }

    return {
        isLoadingRequest, errors, acq_doc, config, array_item, index_errors, procesoCompra,
        item_errors, backend_errors, new_item, currentPage, doc_types, management_types,
        financing_sources, suppliers, item_available, itemSelected, showItemInfo, employees,
        onSelectMultiSelectProcesoCompra, tipoProcesoCompraValue,
        getInfoForModalDocumentoAdquisicion, storeDocumentoAdquisicion, updateDocumentoAdquisicion,
        goToNextPage, addItem, cleanArrayItem, editItem, deleteItem, selectEmployees
    }
}





