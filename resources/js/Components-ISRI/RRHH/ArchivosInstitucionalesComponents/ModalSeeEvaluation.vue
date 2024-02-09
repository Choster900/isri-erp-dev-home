
<template>
    <div class="m-1.5">
        <!-- Componente del modal ProcessModal -->
        <ProcessModal maxWidth="3xl" :show="showModal" @close="$emit('cerrar-modal')">

            <div class="flex flex-col md:flex-row  ">
                <div class="justify-center w-full ">
                    <!-- Header evaluacion  -->
                    <div class=" flex  justify-center pt-2 content-between">
                        <svg class="h-7 w-7 absolute top-0 right-0 mt-2 cursor-pointer" viewBox="0 0 25 25"
                            @click="$emit('cerrar-modal')">
                            <path fill="currentColor"
                                d="M7.95 6.536l4.242-4.243a1 1 0 111.415 1.414L9.364 7.95l4.243 4.242a1 1 0 11-1.415 1.415L7.95 9.364l-4.243 4.243a1 1 0 01-1.414-1.415L6.536 7.95 2.293 3.707a1 1 0 011.414-1.414L7.95 6.536z" />
                        </svg>

                    </div>
                    <!--/ Header evaluacion  -->

                    <!-- With Icons -->
                    <div class="mx-6 mt-2">
                        <h2 class="flex gap-2 text-xl text-slate-800 font-medium mb-6">Evaluación de Desempeño para Personal
                            Administrativo</h2>
                        <div class="mb-1 border-b border-slate-200">
                            <ul class="text-sm font-medium flex flex-nowrap -mx-4 sm:-mx-6 lg:-mx-8 ">
                                <li
                                    class="pb-3 mr-6 last:mr-0 first:pl-4 sm:first:pl-6 lg:first:pl-8 last:pr-4 sm:last:pr-6 lg:last:pr-8">
                                    <a :class="headerOptions === 'DocumentoEvalacionVue' ? 'text-indigo-500' : 'text-slate-500 hover:text-slate-600'"
                                        class=" whitespace-nowrap flex items-center cursor-pointer">
                                        <svg class="w-4 h-4 shrink-0 fill-current mr-2" viewBox=" 0 0 16 16">
                                            <path
                                                d="M12.311 9.527c-1.161-.393-1.85-.825-2.143-1.175A3.991 3.991 0 0012 5V4c0-2.206-1.794-4-4-4S4 1.794 4 4v1c0 1.406.732 2.639 1.832 3.352-.292.35-.981.782-2.142 1.175A3.942 3.942 0 001 13.26V16h14v-2.74c0-1.69-1.081-3.19-2.689-3.733zM6 4c0-1.103.897-2 2-2s2 .897 2 2v1c0 1.103-.897 2-2 2s-2-.897-2-2V4zm7 10H3v-.74c0-.831.534-1.569 1.33-1.838 1.845-.624 3-1.436 3.452-2.422h.436c.452.986 1.607 1.798 3.453 2.422A1.943 1.943 0 0113 13.26V14z" />
                                        </svg>
                                        <span @click="headerOptions = 'DocumentoEvalacionVue'">Medición de
                                            Competencias</span>
                                    </a>
                                </li>
                                <li
                                    class="pb-3 mr-6 last:mr-0 first:pl-4 sm:first:pl-6 lg:first:pl-8 last:pr-4 sm:last:pr-6 lg:last:pr-8">
                                    <a :class="headerOptions === 'EvaluacionTable' ? 'text-indigo-500' : 'text-slate-500 hover:text-slate-600'"
                                        class="  whitespace-nowrap flex items-center cursor-pointer">
                                        <svg class="w-4 h-4 shrink-0 fill-current  mr-2"
                                            :class="headerOptions === 'EvaluacionTable' ? 'text-indigo-500' : 'text-slate-500 hover:text-slate-600'"
                                            viewBox=" 0 0 16 16">
                                            <path
                                                d="M14.3.3c.4-.4 1-.4 1.4 0 .4.4.4 1 0 1.4l-8 8c-.2.2-.4.3-.7.3-.3 0-.5-.1-.7-.3-.4-.4-.4-1 0-1.4l8-8zM15 7c.6 0 1 .4 1 1 0 4.4-3.6 8-8 8s-8-3.6-8-8 3.6-8 8-8c.6 0 1 .4 1 1s-.4 1-1 1C4.7 2 2 4.7 2 8s2.7 6 6 6 6-2.7 6-6c0-.6.4-1 1-1z" />
                                        </svg>
                                        <span @click="headerOptions = 'EvaluacionTable'">Análisis de
                                            Desempeño</span>
                                    </a>
                                </li>
                             <!--    <li
                                    class="pb-3 mr-6 last:mr-0 first:pl-4 sm:first:pl-6 lg:first:pl-8 last:pr-4 sm:last:pr-6 lg:last:pr-8">
                                    <a :class="headerOptions === 'RadarChartStackedEvaluation' ? 'text-indigo-500' : 'text-slate-500 hover:text-slate-600'"
                                        class="  whitespace-nowrap flex items-center cursor-pointer">
                                        <svg class="w-4 h-4 shrink-0 fill-current  mr-2"
                                            :class="headerOptions === 'RadarChartStackedEvaluation' ? 'text-indigo-500' : 'text-slate-500 hover:text-slate-600'"
                                            viewBox=" 0 0 16 16">
                                            <path
                                                d="M14.3.3c.4-.4 1-.4 1.4 0 .4.4.4 1 0 1.4l-8 8c-.2.2-.4.3-.7.3-.3 0-.5-.1-.7-.3-.4-.4-.4-1 0-1.4l8-8zM15 7c.6 0 1 .4 1 1 0 4.4-3.6 8-8 8s-8-3.6-8-8 3.6-8 8-8c.6 0 1 .4 1 1s-.4 1-1 1C4.7 2 2 4.7 2 8s2.7 6 6 6 6-2.7 6-6c0-.6.4-1 1-1z" />
                                        </svg>
                                        <span @click="headerOptions = 'RadarChartStackedEvaluation'">Analisis total</span>
                                    </a>
                                </li> -->
                            </ul>
                        </div>

                    </div>
                    <DocumentEvaluationForPreView v-if="evaluacionPersonalProp.data"
                        :class="headerOptions === 'DocumentoEvalacionVue' ? '' : 'hidden'"
                        :isLoadingObtenerCategoriaYRubrica="isLoadingObtenerCategoriaYRubrica"
                        :evaluacionPersonalProp="evaluacionPersonalProp"
                        :rubricaAndCategoriaByEvaluacion="rubricaAndCategoriaByEvaluacion"
                        @actualizar-datatable="$emit('actualizar-datatable')" />

                    <EvaluacionTable :evaluacionPersonalProp="evaluacionPersonalProp" v-if="evaluacionPersonalProp.data"
                        :rubricaAndCategoriaByEvaluacion="rubricaAndCategoriaByEvaluacion"
                        :isLoadingObtenerCategoriaYRubrica="isLoadingObtenerCategoriaYRubrica"
                        :class="headerOptions === 'EvaluacionTable' ? '' : 'hidden'" />

                </div>
            </div>
        </ProcessModal>
    </div>
</template>

<script>
import moment from 'moment';
import Swal from "sweetalert2";
import { toast } from 'vue3-toastify'
import 'vue3-toastify/dist/index.css';
import { Spanish } from "flatpickr/dist/l10n/es.js"
import Tooltip from '@/Components-ISRI/Tooltip.vue';
import Modal from "@/Components-ISRI/AllModal/Modal.vue";
import { executeRequest } from "@/plugins/requestHelpers.js";
import AccordionBasic from '@/Components-ISRI/AccordionBasic.vue';
import ModalAlert from '@/Components-ISRI/AllModal/ModalAlert.vue';
import { computed, onMounted, ref, toRef, toRefs, watch } from 'vue';
import ProcessModal from '@/Components-ISRI/AllModal/ProcessModal.vue';
import { useEvaluacion } from '@/Composables/RRHH/Evaluaciones/useEvaluacion';
import { useDocumentoEvaluacion } from '@/Composables/RRHH/Evaluaciones/useDocumentoEvaluacion';
import DropDownOptions from '@/Components-ISRI/DropDownOptions.vue';
import DocumentEvaluationForPreView from './DocumentEvaluationForPreView.vue';
import EvaluacionTable from './EvaluacionTable.vue';
export default {
    components: {
        Modal,
        moment,
        Tooltip,
        ModalAlert,
        ProcessModal,
        AccordionBasic,
        DropDownOptions,
        DocumentEvaluationForPreView,
        EvaluacionTable,
    },
    emit: ["cerrar-modal", "actualizar-datatable"],
    props: {
        showModal: {
            type: Boolean,
            default: false,
        },
        rubricaAndCategoriaByEvaluacion: {
            type: Object,
            default: () => { },

        },
        evaluacionPersonalProp: {
            type: Object,
            default: () => { },
        },
        isLoadingObtenerCategoriaYRubrica: {
            type: Boolean,
            default: false,
        }
    },
    setup(props, { emit }) {
        const { showModal, isLoadingObtenerCategoriaYRubrica } = toRefs(props);
        const {
            idEmpleado, errorsData,
            messageAlert, handleAccept,
            handleCancel, plazaOptions, headerOptions,
            handleTagToSelect, evaluationsOptions,
            objectEvaluaciones, fechaInicioFechafin,
            showPlazasModal, existMoreThanOne, clearLock,
            showMessageAlert, idTipoEvaluacion, selectedEmpleadoValue,
            handleEmployeeSearch, idEvaluacionRendimiento, opcionEmpleado,
            idCentroAtencion, doesntExistResult, evaluacionesAgrupadasPorAño,
            activeIndex, objectPlazas, evaluacionPersonal, evaluacionToPassDocumento,
            loadingEvaluacionRendimiento, createPersonalEvaluationRequest, getPlazasByEmployeeIdAndCentroAtencionId, configSecondInput,
        } = useEvaluacion();

        const {

            obtenerCategoriaYRubricaRendimiento,
        } = useDocumentoEvaluacion()

        watch(showModal, (newValue, oldValue) => {
            if (!newValue) {
                idEmpleado.value = null;
                objectPlazas.value = null;
                idCentroAtencion.value = null;
                evaluacionToPassDocumento.value = null;
                plazaOptions.value = [];
                showPlazasModal.value = false;
                evaluationsOptions.value = []
                idEvaluacionRendimiento.value = null;
                fechaInicioFechafin.value = null;
            }
        })



        return {
            moment,
            errorsData,
            objectPlazas,
            plazaOptions,
            opcionEmpleado,
            showMessageAlert,
            configSecondInput,
            evaluacionPersonal,
            handleEmployeeSearch,
            activeIndex, activeIndex,
            handleAccept, handleCancel,
            isLoadingObtenerCategoriaYRubrica,
            isLoadingObtenerCategoriaYRubrica,
            existMoreThanOne, idTipoEvaluacion,
            messageAlert, selectedEmpleadoValue,
            evaluacionToPassDocumento, clearLock,
            handleTagToSelect, doesntExistResult,
            evaluacionPersonal, objectEvaluaciones,
            evaluationsOptions, fechaInicioFechafin,
            getPlazasByEmployeeIdAndCentroAtencionId,
            evaluacionesAgrupadasPorAño, headerOptions,
            obtenerCategoriaYRubricaRendimiento, showPlazasModal,
            evaluacionesAgrupadasPorAño, idEmpleado, idCentroAtencion,
            loadingEvaluacionRendimiento, createPersonalEvaluationRequest,
        }
    }

}
</script>
<style >
.accordion-content {
    overflow: hidden;
    transition: max-height 0.3s ease-in-out, opacity 0.3s ease-in-out;
    max-height: 0;
    opacity: 0;
}

.accordion-content.open {
    max-height: 999px;
    /* Un valor suficientemente grande */
    /* Ajusta este valor según sea necesario */
    opacity: 1;
}

.flatpickr-current-month .flatpickr-monthDropdown-months {
    background: #001b47;
    border: none;
    border-radius: 20px;
    box-sizing: border-box;
    color: inherit;
    cursor: pointer;
    appearance: none;
    font-size: 12pt;
    padding: 10px;
    font-family: inherit;
    font-weight: 300;
    height: auto;
    line-height: inherit;
    margin: -1px 0 0 0;
    outline: none;
    padding: 0 0 0 0.5ch;
    position: relative;
    vertical-align: initial;
    -webkit-box-sizing: border-box;

    width: auto;
}


.flatpickr-current-month input.cur-year {
    background: transparent;
    box-sizing: border-box;
    color: inherit;
    cursor: text;
    padding: 0 0 0 0.1ch;
    margin: 0;
    display: inline-block;
    font-size: 12pt;
    font-family: inherit;
    font-weight: 300;
    line-height: inherit;
    height: auto;
    border: 0;
    border-radius: 0;
    vertical-align: initial;
    -webkit-appearance: textfield;
    -moz-appearance: textfield;
    appearance: textfield;
}

:root {
    --ms-tag-bg: #0d2141;
    --ms-tag-font-weight: 300;
    --ms-tag-color: #ffffff;
    --ms-tag-font-size: 0.875rem;

}
</style>
