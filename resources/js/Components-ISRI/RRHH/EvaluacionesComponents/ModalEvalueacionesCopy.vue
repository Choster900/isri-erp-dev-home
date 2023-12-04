
<template>
    <div class="m-1.5">
        <!-- Componente del modal ProcessModal -->
        <ProcessModal maxWidth="6xl" :show="showModal" @close="$emit('cerrar-modal')">

            <div class="flex flex-col md:flex-row  ">
                <div class="w-full md:w-2/6 bg-slate-200/40 p-3 border">
                    <div class="col-span-full xl:col-span-6 bg-white shadow-lg  border border-slate-300 ">
                        <header class="px-5 py-4 border-b-4 border-indigo-500">
                            <h2 class="font-semibold text-slate-800">Crear una nueva evaluación +</h2>
                        </header>
                        <div class="p-3">

                            <div class="-mx-3 flex gap-1">
                                <div class="mb-1 px-3  w-full">
                                    <label class="block text-gray-700 text-xs font-medium mb-1" for="name">Nombre del
                                        empleado</label>
                                    <div class="relative flex h-8 w-full flex-row-reverse ">
                                        <Multiselect v-model="idEmpleado" @select="getPlazasByEmployeeIdAndDependenciaId()"
                                            :filter-results="false" :resolve-on-load="false" :delay="1000"
                                            :searchable="true" :clear-on-search="true" :min-chars="5"
                                            placeholder="Busqueda de empleado..." :classes="{
                                                placeholder: 'flex items-center text-center h-full absolute left-0 top-0 pointer-events-none bg-transparent leading-snug pl-3.5 text-gray-400 rtl:left-auto rtl:right-0 rtl:pl-0 rtl:pr-3.5',
                                            }" noOptionsText="<p class='text-xs'>Sin Personas<p>"
                                            noResultsText="<p class='text-xs'>Sin resultados de personas <p>" :options="async function (query) {
                                                return await handleEmployeeSearch(query)
                                            }" />
                                    </div>
                                </div>
                            </div>
                            <div class=" flex gap-1">
                                <div class="mb-1 -ml-3 pl-3 w-7/12">
                                    <label class="block text-gray-700 text-xs font-medium mb-1"
                                        for="name">Dependencia</label>
                                    <div class="relative flex h-8 w-full flex-row-reverse ">
                                        <Multiselect :searchable="true" v-model="idDependencia"
                                            @select="getPlazasByEmployeeIdAndDependenciaId()" :classes="{
                                                placeholder: 'flex items-center text-center h-full absolute left-0 top-0 pointer-events-none bg-transparent leading-snug pl-3.5 text-gray-400 rtl:left-auto rtl:right-0 rtl:pl-0 rtl:pr-3.5',
                                            }" noOptionsText="<p class='text-xs'>Sin Personas<p>"
                                            noResultsText="<p class='text-xs'>Sin resultados de personas <p>"
                                            :options="listDependencias" />
                                    </div>
                                </div>
                                <div class="mb-1 -mr-3 pr-3 w-1/2">
                                    <label class="block text-gray-700 text-xs font-medium mb-1"
                                        for="name">Evaluacion</label>
                                    <div class="relative flex h-8 w-full flex-row-reverse ">
                                        <Multiselect :filter-results="false" :resolve-on-load="false" :delay="1000"
                                            :searchable="true" :clear-on-search="true" :min-chars="5" placeholder=""
                                            :classes="{
                                                placeholder: 'flex items-center text-center h-full absolute left-0 top-0 pointer-events-none bg-transparent leading-snug pl-3.5 text-gray-400 rtl:left-auto rtl:right-0 rtl:pl-0 rtl:pr-3.5',
                                            }" noOptionsText="<p class='text-xs'>Sin Personas<p>"
                                            noResultsText="<p class='text-xs'>Sin resultados de personas <p>"
                                            :options="[{ value: '1', label: 'F22 V1.0' }, { value: '2', label: 'F21 V1.0' }, { value: '3', label: 'F23 V1.0' }]" />
                                    </div>
                                </div>
                            </div>

                            <div class="-mx-3 flex py-1">
                                <div class="mb-1 px-3 w-full">
                                    <div class="mb-4 md:mr-0 md:mb-0 basis-1/2">
                                        <label class="block text-gray-700 text-xs font-medium mb-1" for="name">Fecha [Desde
                                            - Hasta]</label>
                                        <div class="relative flex">
                                            <flat-pickr
                                                class="text-xs cursor-pointer rounded-md border h-8 border-slate-400 px-2 text-slate-900 placeholder-slate-400 transition-colors duration-300 focus:border-[#001b47] focus:outline-none w-full"
                                                :config="configSecondInput" :placeholder="'Seleccione Fecha Inicial'" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mx-0.5 mt-2">
                                <label class="block text-gray-700 text-xs font-medium mb-1" for="name">
                                    Tipo evaluacion
                                </label>
                                <div class="flex flex-wrap items-center -m-3">
                                    <div class="m-3">
                                        <label class="flex items-center">
                                            <input type="radio" name="radio-buttons" class="form-radio" checked="">
                                            <span class="text-sm ml-2 text-selection-disable">Desempeño</span></label>
                                    </div>
                                    <div class="m-3">
                                        <label class="flex items-center">
                                            <input type="radio" name="radio-buttons" class="form-radio">
                                            <span class="text-sm ml-2 text-selection-disable">Periodo de
                                                prueba</span></label>
                                    </div>
                                </div>
                            </div>
                            <button
                                class="bg-indigo-900 rounded-sm shadow text-center text-white text-sm font-light w-full py-1 mt-5">Crear
                                una nueva evaluación</button>

                        </div>
                    </div>

                    <div
                        class="h-[270px] overflow-y-auto col-span-full xl:col-span-6 bg-white shadow-lg  border border-slate-300">
                        <div class="p-3">
                            <div class="max-h-[250px] ">
                                <article class="pt-4 border-b border-slate-200" v-for="i in 3" :key="i">
                                    <header class="flex items-start mb-2 cursor-pointer"
                                        @click='activeIndex = activeIndex === i ? null : i'>
                                        <div class="mr-3">
                                            <svg class="w-4 h-4 shrink-0 fill-current" viewBox="0 0 16 16">
                                                <path class="text-indigo-300"
                                                    d="M4 8H0v4.9c0 1 .7 1.9 1.7 2.1 1.2.2 2.3-.8 2.3-2V8z">
                                                </path>
                                                <path class="text-indigo-500"
                                                    d="M15 1H7c-.6 0-1 .4-1 1v11c0 .7-.2 1.4-.6 2H13c1.7 0 3-1.3 3-3V2c0-.6-.4-1-1-1z">
                                                </path>
                                            </svg>
                                        </div>
                                        <h3 class="text-sm text-selection-disable"
                                            :class="activeIndex === i ? 'text-slate-800 font-medium' : 'text-slate-400 font-medium'">
                                            Evaluaciones
                                            del año 2023</h3>
                                    </header>
                                    <div class="accordion-content" :class="i === activeIndex ? 'open' : ''">
                                        <div>
                                            <div v-for="j in 6" :key="j"
                                                class="bg-slate-300/40 border-l-[4px] border-y-0 border-r-0 border-l-indigo-500 hover:bg-slate-300 cursor-pointer"
                                                :class="j == 1 ? ' border-b border-b-slate-400' : ''">
                                                <ul class="ml-10 list-circle py-2 ">
                                                    <li
                                                        class="relative text-xs  before:w-[10px] before:h-[10px] before:border-[3px] before:border-indigo-500 before:rounded-full before:absolute before:-left-4 before:top-1">
                                                        Puntuacion</li>
                                                    <p class="font-medium text-xs"> 10 00</p>
                                                    <li
                                                        class="relative text-xs before:w-[10px] before:h-[10px] before:border-[3px] before:border-indigo-500 before:rounded-full before:absolute before:-left-4 before:top-1">
                                                        Fecha Evaluado</li>
                                                    <p class="font-medium text-xs">ahora</p>

                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </article>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="justify-center w-full md:w-4/6">
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
                        <h2 class="flex gap-2 text-xl text-slate-800 font-bold mb-6">Evaluación del desempeño para
                            personal
                            administrativo
                        </h2>

                        <div class="mb-1 border-b border-slate-200">
                            <ul class="text-sm font-medium flex flex-nowrap -mx-4 sm:-mx-6 lg:-mx-8 ">
                                <li
                                    class="pb-3 mr-6 last:mr-0 first:pl-4 sm:first:pl-6 lg:first:pl-8 last:pr-4 sm:last:pr-6 lg:last:pr-8">
                                    <a :class="toShow === 'DocumentoEvalacionVue' ? 'text-indigo-500' : 'text-slate-500 hover:text-slate-600'"
                                        class=" whitespace-nowrap flex items-center cursor-pointer">
                                        <svg class="w-4 h-4 shrink-0 fill-current mr-2" viewBox=" 0 0 16 16">
                                            <path
                                                d="M12.311 9.527c-1.161-.393-1.85-.825-2.143-1.175A3.991 3.991 0 0012 5V4c0-2.206-1.794-4-4-4S4 1.794 4 4v1c0 1.406.732 2.639 1.832 3.352-.292.35-.981.782-2.142 1.175A3.942 3.942 0 001 13.26V16h14v-2.74c0-1.69-1.081-3.19-2.689-3.733zM6 4c0-1.103.897-2 2-2s2 .897 2 2v1c0 1.103-.897 2-2 2s-2-.897-2-2V4zm7 10H3v-.74c0-.831.534-1.569 1.33-1.838 1.845-.624 3-1.436 3.452-2.422h.436c.452.986 1.607 1.798 3.453 2.422A1.943 1.943 0 0113 13.26V14z" />
                                        </svg>
                                        <span @click="toShow = 'DocumentoEvalacionVue'">Medición de Competencias</span>
                                    </a>
                                </li>
                                <li
                                    class="pb-3 mr-6 last:mr-0 first:pl-4 sm:first:pl-6 lg:first:pl-8 last:pr-4 sm:last:pr-6 lg:last:pr-8">
                                    <a :class="toShow === 'DocumentoAnalisisDesempeñoVue' ? 'text-indigo-500' : 'text-slate-500 hover:text-slate-600'"
                                        class="  whitespace-nowrap flex items-center cursor-pointer">
                                        <svg class="w-4 h-4 shrink-0 fill-current  mr-2"
                                            :class="toShow === 'DocumentoAnalisisDesempeñoVue' ? 'text-indigo-500' : 'text-slate-500 hover:text-slate-600'"
                                            viewBox=" 0 0 16 16">
                                            <path
                                                d="M14.3.3c.4-.4 1-.4 1.4 0 .4.4.4 1 0 1.4l-8 8c-.2.2-.4.3-.7.3-.3 0-.5-.1-.7-.3-.4-.4-.4-1 0-1.4l8-8zM15 7c.6 0 1 .4 1 1 0 4.4-3.6 8-8 8s-8-3.6-8-8 3.6-8 8-8c.6 0 1 .4 1 1s-.4 1-1 1C4.7 2 2 4.7 2 8s2.7 6 6 6 6-2.7 6-6c0-.6.4-1 1-1z" />
                                        </svg>
                                        <span @click="toShow = 'DocumentoAnalisisDesempeñoVue'">Análisis de
                                            Desempeño</span>
                                    </a>
                                </li>
                                <li
                                    class="pb-3 mr-6 last:mr-0 first:pl-4 sm:first:pl-6 lg:first:pl-8 last:pr-4 sm:last:pr-6 lg:last:pr-8">
                                    <a :class="toShow === 'ImpresionDeDocumentos' ? 'text-indigo-500' : 'text-slate-500 hover:text-slate-600'"
                                        class="  whitespace-nowrap flex items-center cursor-pointer">

                                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none">
                                            <path d="M7 8H21M7 12H21M7 16H21M3 8H3.01M3 12H3.01M3 16H3.01"
                                                :stroke="toShow === 'ImpresionDeDocumentos' ? '#6366f1' : '#94a3b8'"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>

                                        </svg>

                                        <span class="ml-2" @click="toShow = 'ImpresionDeDocumentos'">Acciones</span>
                                    </a>
                                </li>
                            </ul>
                        </div>

                    </div>
                    <!-- 
                    <DocumentoEvaluacionVue :contenidoEvaluacionRendimiento="contenidoEvaluacionRendimiento"
                        :registroEvaluacionRendimientoPersonal="registroEvaluacionRendimientoPersonal"
                        :info-employee="registrosEvaluacionesRentimientoPersonal"
                        @actualizar-table-data="$emit('cerrar-modal')" :showMe="toShow" /> -->
                </div>
            </div>
        </ProcessModal>
    </div>
</template>

<script>
import Tooltip from '@/Components-ISRI/Tooltip.vue';
import { toast } from 'vue3-toastify'
import 'vue3-toastify/dist/index.css';
import moment from 'moment';
import Modal from "@/Components-ISRI/AllModal/Modal.vue";
import ProcessModal from '@/Components-ISRI/AllModal/ProcessModal.vue';
import DocumentoEvaluacion from '../DocumentoEvaluacion.vue';
import { useEvaluacion } from '@/Composables/RRHH/Evaluaciones/useEvaluacion';
import { ref, toRefs } from 'vue';
import AccordionBasic from '@/Components-ISRI/AccordionBasic.vue';
export default {
    components: { Tooltip, moment, ProcessModal, Modal, DocumentoEvaluacion, AccordionBasic },
    emit: ["cerrar-modal"],
    props: {
        showModal: { type: Boolean, default: false, },
        listDependencias: { type: Object, default: () => { }, },
    },
    setup(props) {
        const configSecondInput = ref({
            mode: 'range',
            wrap: true, // set wrap to true only when using 'input-group'
            altInput: true,
            monthSelectorType: 'static',
            altFormat: 'M j, Y',
            dateFormat: "Y-m-d",
            disableMobile: "true",
            locale: {
                rangeSeparator: " a ",
                firstDayOfWeek: 1,
                weekdays: {
                    shorthand: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
                    longhand: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
                },
                months: {
                    shorthand: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                    longhand: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                },
            },
        })
        const { listDependencias } = toRefs(props)
        const { handleEmployeeSearch, idEmpleado, getPlazasByEmployeeIdAndDependenciaId, idDependencia } = useEvaluacion();
        const activeIndex = ref(0);
        const activeContent = ref(0);


        return {
            listDependencias,
            configSecondInput,
            activeIndex, activeContent, getPlazasByEmployeeIdAndDependenciaId,
            handleEmployeeSearch, idEmpleado, idDependencia
        }
    }

}
</script>
<style scoped>
.accordion-content {
    overflow: hidden;
    transition: max-height 0.3s ease-in-out, opacity 0.3s ease-in-out;
    max-height: 0;
    opacity: 0;
}

.accordion-content.open {
    max-height: 500px;
    /* Un valor suficientemente grande */
    /* Ajusta este valor según sea necesario */
    opacity: 1;
}
</style>