<template>
    <div class="mx-4 overflow-y-auto h-[550px] py-3 px-2 mb-4 ">
        <div v-if="rubricaAndCategoriaByEvaluacion && !isLoadingObtenerCategoriaYRubrica && evaluacionPersonalProp">
            <table class="w-full">
                <tbody>
                    <tr>
                        <td class="border border-black w-40" rowspan="4">
                            <div class="w-40 px-1.5">
                                <img src="../../../../img/isri-gob.png" alt="logotipo-isri" draggable="false">
                            </div>
                        </td>
                        <td class="border text-sm border-black text-center font-medium uppercase" rowspan="2">
                            FORMULARIO DE ANALISIS DEL DESEMPEÑO
                        </td>
                        <td class="border border-black text-[7pt] font-semibold pr-3 px-1.5">FECHA : {{
                            evaluacionPersonalProp.data.fecha_evaluacion_personal }}</td>
                    </tr>
                    <tr>
                        <td class="border border-black text-[7pt] font-semibold   px-1.5">EMPLEADO: {{
                            evaluacionPersonalProp.allData.codigo_empleado }}</td>
                    </tr>
                    <tr>
                        <td class="border text-sm border-black text-center font-medium px-8" rowspan="2">
                            <!--  {{ evaluacionPersonalProp }} -->
                            PARA {{
                                separarTexto(evaluacionPersonalProp.data.evaluacion_rendimiento.nombre_evaluacion_rendimiento)
                            }}
                        </td>
                        <td class="border border-black text-[7pt] font-semibold py-0.5 px-1.5">
                            CENTRO:ADMON
                        </td>
                    </tr>
                    <tr>
                        <td class="border border-black text-[7pt] font-semibold  py-0.5 px-1.5">
                            CODIGO: {{ evaluacionPersonalProp.data.evaluacion_rendimiento.codigo_evaluacion_rendimiento }}
                        </td>
                    </tr>
                    <tr>
                        <td class="border border-black bg-black h-5 text-white text-center text-[9pt] " colspan="3">
                            ESPECIFICACIONES
                        </td>
                    </tr>
                </tbody>
            </table>

            <div id="especificaciones">
                <div class="flex items-center justify-between pt-4 gap-2">
                    <div class="flex items-center">
                        <label for="" class="text-[7pt] font-semibold mr-2">EMPLEADO:</label>
                        <input type="text"
                            :value="`${evaluacionPersonalProp.allData.persona.pnombre_persona || ''} ${evaluacionPersonalProp.allData.persona.snombre_persona || ''} ${evaluacionPersonalProp.allData.persona.tnombre_persona || ''} ${evaluacionPersonalProp.allData.persona.papellido_persona || ''} ${evaluacionPersonalProp.allData.persona.sapellido_persona || ''} ${evaluacionPersonalProp.allData.persona.tapellido_persona || ''}`"
                            class="w-44 text-[7pt]  font-medium capitalize h-5 border-x-0 border-t-0">
                    </div>
                    <div class="flex items-center">
                        <label for="" class="text-[7pt] font-semibold mr-2">PUESTO:</label>
                        <input type="text" :value="evaluacionPersonalProp.data.plaza_evaluada && evaluacionPersonalProp.data.plaza_evaluada.filter(plaza => plaza.plaza_asignada.estado_plaza_asignada === 1)
                            .map((plaza, index) => {
                                return plaza.plaza_asignada.detalle_plaza.plaza.nombre_plaza;
                            }).join('-') || ''"
                            class="w-72 text-left text-[7pt]  font-medium capitalize h-5 border-x-0 border-t-0"
                            v-if="evaluacionPersonalProp.data.plaza_evaluada.length === 1">

                        <Tooltip bg="dark" position="left" :key="weekIndex" v-else>
                            <template v-slot:contenido>
                                <input type="text" :value="evaluacionPersonalProp.data.plaza_evaluada && evaluacionPersonalProp.data.plaza_evaluada.filter(plaza => plaza.plaza_asignada.estado_plaza_asignada === 1)
                                    .map((plaza, index) => {
                                        return plaza.plaza_asignada.detalle_plaza.plaza.nombre_plaza;
                                    }).join('-') || ''"
                                    class="w-72 text-left text-[7pt]  font-medium capitalize h-5 border-x-0 border-t-0">
                            </template>
                            <template v-slot:message>
                                <div class="w-full  mt-6 md:mt-0 overflow-x-auto">
                                    <table class="w-full">
                                        <tr class="text-start">
                                            <th class="py-2" colspan="2">
                                                <h1 class=" font-medium text-white text-sm">Información de Plazas Asignadas
                                                </h1>
                                            </th>
                                        </tr>
                                        <tr class="text-start text-[8pt]">
                                            <td class="border border-white p-2">
                                                <div class="text-xs text-slate-200 w-[20.5em] text-">
                                                    <ul class="ml-10 list-circle py-2 ">
                                                        <li v-for="(data, i ) in evaluacionPersonalProp.data.plaza_evaluada"
                                                            :key="i"
                                                            class="relative text-xs text-slate-500 before:w-[10px] before:h-[10px] before:border-[3px] before:border-indigo-500 before:rounded-full before:absolute before:-left-4 before:top-1">
                                                            <p v-if="data.plaza_asignada.estado_plaza_asignada === 1"
                                                                class="text-white">
                                                                {{ data.plaza_asignada.detalle_plaza.plaza.nombre_plaza }}
                                                            </p>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </template>
                        </Tooltip>
                    </div>
                    <div class="flex items-center">
                        <label for="" class="text-[7pt] font-semibold mr-2">SUELDO:</label>

                        <input type="text" :value="evaluacionPersonalProp.data.plaza_evaluada && evaluacionPersonalProp.data.plaza_evaluada.filter(plaza => plaza.plaza_asignada.estado_plaza_asignada === 1)
                            .map((plaza, index) => {
                                return plaza.plaza_asignada.detalle_plaza.plaza.salario_base_plaza;
                            }).join('-') || ''"
                            class="w-16 text-left text-[7pt]  font-medium capitalize h-5 border-x-0 border-t-0"
                            v-if="evaluacionPersonalProp.data.plaza_evaluada.length === 1">

                        <Tooltip bg="dark" position="left" :key="weekIndex" v-else>
                            <template v-slot:contenido>
                                <input type="text" :value="evaluacionPersonalProp.data.plaza_evaluada && evaluacionPersonalProp.data.plaza_evaluada.filter(plaza => plaza.plaza_asignada.estado_plaza_asignada === 1)
                                    .map((plaza, index) => {
                                        return plaza.plaza_asignada.detalle_plaza.plaza.salario_base_plaza;
                                    }).join('-') || ''"
                                    class="w-16 text-left text-[7pt]  font-medium capitalize h-5 border-x-0 border-t-0">
                            </template>
                            <template v-slot:message>
                                <div class="w-full  mt-6 md:mt-0 overflow-x-auto">
                                    <table class="w-full">
                                        <tr class="text-start">
                                            <th class="py-2" colspan="2">
                                                <h1 class=" font-medium text-white text-sm">Información de salario
                                                </h1>
                                            </th>
                                        </tr>
                                        <tr class="text-start text-[8pt]">
                                            <td class="border border-white p-2">
                                                <div class="text-xs text-slate-200 w-[20.5em] text-">
                                                    <ul class="ml-10 list-circle py-2 ">
                                                        <li v-for="(data, i ) in evaluacionPersonalProp.data.plaza_evaluada"
                                                            :key="i"
                                                            class="relative text-xs text-slate-500 before:w-[10px] before:h-[10px] before:border-[3px] before:border-indigo-500 before:rounded-full before:absolute before:-left-4 before:top-1">
                                                            <p v-if="data.plaza_asignada.estado_plaza_asignada === 1"
                                                                class="text-white">
                                                                $ {{
                                                                    data.plaza_asignada.detalle_plaza.plaza.salario_base_plaza
                                                                }}
                                                            </p>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </template>
                        </Tooltip>
                    </div>
                </div>
                <div class="flex items-center justify-between pt-2 gap-1">
                    <div class="flex items-center">
                        <label for="" class="text-[7pt] font-semibold mr-2">UNIDAD ADM:
                            (Direccion,Division,Dpto.,Unidad,Seccion,Area)</label>

                        <input type="text" :value="`${evaluacionPersonalProp.data.plaza_evaluada &&
                            evaluacionPersonalProp.data.plaza_evaluada
                                .filter(plaza => plaza.plaza_asignada.estado_plaza_asignada === 1)
                                .map((plaza, index) => {
                                    return plaza.plaza_asignada.dependencia.nombre_dependencia;
                                }).join('-') || ''} - ${evaluacionPersonalProp.data.plaza_evaluada &&
                                evaluacionPersonalProp.data.plaza_evaluada
                                    .filter(plaza => plaza.plaza_asignada.estado_plaza_asignada === 1)
                                    .map((plaza, index) => {
                                        return plaza.plaza_asignada.centro_atencion.nombre_centro_atencion;
                                    }).join('-') || ''}`"
                            class="w-32 text-left text-[7pt]  font-medium capitalize h-5 border-x-0 border-t-0"
                            v-if="evaluacionPersonalProp.data.plaza_evaluada.length === 1">

                        <Tooltip bg="dark" position="left" :key="weekIndex" v-else>
                            <template v-slot:contenido>
                                <input type="text" :value="`${evaluacionPersonalProp.data.plaza_evaluada &&
                                    evaluacionPersonalProp.data.plaza_evaluada
                                        .filter(plaza => plaza.plaza_asignada.estado_plaza_asignada === 1)
                                        .map((plaza, index) => {
                                            return plaza.plaza_asignada.dependencia.nombre_dependencia;
                                        }).join('-') || ''} - ${evaluacionPersonalProp.data.plaza_evaluada &&
                                        evaluacionPersonalProp.data.plaza_evaluada
                                            .filter(plaza => plaza.plaza_asignada.estado_plaza_asignada === 1)
                                            .map((plaza, index) => {
                                                return plaza.plaza_asignada.centro_atencion.nombre_centro_atencion;
                                            }).join('-') || ''}`"
                                    class="w-32 text-left text-[7pt]  font-medium capitalize h-5 border-x-0 border-t-0">
                            </template>
                            <template v-slot:message>
                                <div class="w-full  mt-6 md:mt-0 overflow-x-auto">
                                    <table class="w-full">
                                        <tr class="text-start">
                                            <th class="py-2" colspan="2">
                                                <h1 class=" font-medium text-white text-sm">Información de UNIDAD
                                                </h1>
                                            </th>
                                        </tr>
                                        <tr class="text-start text-[8pt]">
                                            <td class="border border-white p-2">
                                                <div class="text-xs text-slate-200 w-[20.5em] text-">
                                                    <ul class="ml-10 list-circle py-2 ">
                                                        <li v-for="(data, i ) in evaluacionPersonalProp.data.plaza_evaluada"
                                                            :key="i"
                                                            class="relative text-xs text-slate-500 before:w-[10px] before:h-[10px] before:border-[3px] before:border-indigo-500 before:rounded-full before:absolute before:-left-4 before:top-1">
                                                            <p v-if="data.plaza_asignada.estado_plaza_asignada === 1"
                                                                class="text-white">
                                                                {{
                                                                    data.plaza_asignada.centro_atencion.nombre_centro_atencion
                                                                }}
                                                                -
                                                                {{ data.plaza_asignada.dependencia.nombre_dependencia }}
                                                            </p>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </template>
                        </Tooltip>
                    </div>
                    <div class="flex items-center">
                        <label for="" class="text-[7pt] font-semibold mr-2">PERIODO COMPRENDIDO:</label>
                        <input type="text"
                            :value="evaluacionPersonalProp.data.periodo_evaluacion.id_periodo_evaluacion == 1 ? `ENERO A JUNIO ${moment(evaluacionPersonalProp.data.fecha_evaluacion_personal).format('YYYY')}` : `JULIO A DICIEMBRE ${moment(evaluacionPersonalProp.data.fecha_evaluacion_personal).format('YYYY')}`"
                            class="w-36 text-left text-[7pt]  font-medium capitalize h-5 border-x-0 border-t-0">
                    </div>
                </div>
                <div class="flex items-center justify-between pt-2 gap-2 pb-4">
                    <div class="flex items-center">
                        <label for="" class="text-[7pt] font-semibold mr-2">JEFE INME:</label>

                        <input type="text" :value="evaluacionPersonalProp.data.plaza_evaluada &&
                            evaluacionPersonalProp.data.plaza_evaluada
                                .filter(plaza => plaza.plaza_asignada.estado_plaza_asignada === 1)
                                .map((plaza, index) => {
                                    return `${plaza.plaza_asignada.dependencia.jefatura?.pnombre_persona || ''} ${plaza.plaza_asignada.dependencia.jefatura?.snombre_persona || ''} ${plaza.plaza_asignada.dependencia.jefatura?.tnombre_persona || ''} ${plaza.plaza_asignada.dependencia.jefatura?.papellido_persona || ''} ${plaza.plaza_asignada.dependencia.jefatura?.sapellido_persona || ''} ${plaza.plaza_asignada.dependencia.jefatura?.tapellido_persona || ''}`;
                                }).join('-') || ''"
                            class="w-40 text-left text-[7pt]  font-medium capitalize h-5 border-x-0 border-t-0"
                            v-if="evaluacionPersonalProp.data.plaza_evaluada.length === 1">

                        <Tooltip bg="dark" position="right" :key="weekIndex" v-else>
                            <template v-slot:contenido>


                                <input type="text" :value="evaluacionPersonalProp.data.plaza_evaluada &&
                                    evaluacionPersonalProp.data.plaza_evaluada
                                        .filter(plaza => plaza.plaza_asignada.estado_plaza_asignada === 1)
                                        .map((plaza, index) => {
                                            return `${plaza.plaza_asignada.dependencia.jefatura?.pnombre_persona || ''} ${plaza.plaza_asignada.dependencia.jefatura?.snombre_persona || ''} ${plaza.plaza_asignada.dependencia.jefatura?.tnombre_persona || ''} ${plaza.plaza_asignada.dependencia.jefatura?.papellido_persona || ''} ${plaza.plaza_asignada.dependencia.jefatura?.sapellido_persona || ''} ${plaza.plaza_asignada.dependencia.jefatura?.tapellido_persona || ''}`;
                                        }).join('-') || ''"
                                    class="w-40 text-left text-[7pt]  font-medium capitalize h-5 border-x-0 border-t-0">
                            </template>
                            <template v-slot:message>
                                <div class="w-full  mt-6 md:mt-0 overflow-x-auto">
                                    <table class="w-full">
                                        <tr class="text-start">
                                            <th class="py-2" colspan="2">
                                                <h1 class=" font-medium text-white text-sm">Información de JEFATURA
                                                </h1>
                                            </th>
                                        </tr>
                                        <tr class="text-start text-[8pt]">
                                            <td class="border border-white p-2">
                                                <div class="text-xs text-slate-200 w-[20.5em] text-">
                                                    <ul class="ml-10 list-circle py-2 ">
                                                        <li v-for="(data, i ) in evaluacionPersonalProp.data.plaza_evaluada"
                                                            :key="i"
                                                            class="relative text-xs text-slate-500 before:w-[10px] before:h-[10px] before:border-[3px] before:border-indigo-500 before:rounded-full before:absolute before:-left-4 before:top-1">
                                                            <p v-if="data.plaza_asignada.estado_plaza_asignada === 1"
                                                                class="text-white">
                                                                {{
                                                                    `
                                                                ${data.plaza_asignada.dependencia.jefatura?.pnombre_persona
                                                                        || ''}
                                                                                                                                ${data.plaza_asignada.dependencia.jefatura?.snombre_persona
                                                                        || ''}
                                                                                                                                ${data.plaza_asignada.dependencia.jefatura?.tnombre_persona
                                                                        || ''}
                                                                                                                                ${data.plaza_asignada.dependencia.jefatura?.papellido_persona
                                                                        || ''}
                                                                                                                                ${data.plaza_asignada.dependencia.jefatura?.sapellido_persona
                                                                        || ''}
                                                                                                                                ${data.plaza_asignada.dependencia.jefatura?.tapellido_persona
                                                                        || ''}
                                                                `
                                                                }}
                                                            </p>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </template>
                        </Tooltip>


                    </div>
                    <div class="flex items-center">
                        <label for="" class="text-[7pt] font-semibold mr-2">PUESTO:</label>
                        <input type="text" :value="evaluacionPersonalProp.data.plaza_evaluada &&
                            evaluacionPersonalProp.data.plaza_evaluada
                                .filter(plaza => plaza.plaza_asignada.estado_plaza_asignada === 1)
                                .map((plaza, index) => {
                                    return plaza.plaza_asignada.dependencia.jefatura?.empleado.plazas_asignadas.map((plaza, index) => {
                                        return plaza.detalle_plaza.plaza.nombre_plaza
                                    })
                                }).join('-') || ''"
                            class="w-72 text-left text-[7pt]  font-medium capitalize h-5 border-x-0 border-t-0"
                            v-if="evaluacionPersonalProp.data.plaza_evaluada.length === 1">

                        <Tooltip bg="dark" position="left" :key="weekIndex" v-else>
                            <template v-slot:contenido>
                                <input type="text" :value="evaluacionPersonalProp.data.plaza_evaluada &&
                                    evaluacionPersonalProp.data.plaza_evaluada
                                        .filter(plaza => plaza.plaza_asignada.estado_plaza_asignada === 1)
                                        .map((plaza, index) => {
                                            return plaza.plaza_asignada.dependencia.jefatura?.empleado.plazas_asignadas.map((plaza, index) => {
                                                return plaza.detalle_plaza.plaza.nombre_plaza
                                            })
                                        }).join('-') || ''"
                                    class="w-72 text-left text-[7pt]  font-medium capitalize h-5 border-x-0 border-t-0">
                            </template>
                            <template v-slot:message>
                                <div class="w-full  mt-6 md:mt-0 overflow-x-auto">
                                    <table class="w-full">
                                        <tr class="text-start">
                                            <th class="py-2" colspan="2">
                                                <h1 class=" font-medium text-white text-sm">Información de PUESTO DE
                                                    JEFATURA
                                                </h1>
                                            </th>
                                        </tr>
                                        <tr class="text-start text-[8pt]">
                                            <td class="border border-white p-2">
                                                <div v-for="(data, i) in evaluacionPersonalProp.data.plaza_evaluada"
                                                    :key="i">
                                                    <div class="text-xs text-slate-200 w-[20.5em] text-">
                                                        <ul class="ml-10 list-circle py-2">
                                                            <li v-for="(plaza, key) in data.plaza_asignada.dependencia.jefatura?.empleado.plazas_asignadas"
                                                                :key="key"
                                                                class="relative text-xs text-slate-500 before:w-[10px] before:h-[10px] before:border-[3px] before:border-indigo-500 before:rounded-full before:absolute before:-left-4 before:top-1">
                                                                <p v-if="plaza.estado_plaza_asignada === 1"
                                                                    class="text-white">
                                                                    {{ plaza.detalle_plaza.plaza.nombre_plaza }}
                                                                </p>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>

                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </template>
                        </Tooltip>
                    </div>

                    <div class="flex items-center">
                        <label for="" class="text-[7pt] font-semibold mr-2">PUNTAJE TOTAL:</label>
                        <input type="text"
                            :value="optionsSelected.reduce((score, object) => score + parseFloat(object.puntaje_rubrica_rendimiento), 0)"
                            class="w-12 text-left text-[9pt]  font-medium capitalize h-5 border-x-0 border-t-0">
                    </div>
                </div>
            </div>

            <button type="button"
                style="float: right;margin-right:-4px;margin-top:3px; font-size: 30px; padding: 0 10px; border: 0; background-color: transparent;">
                <span type="button" data-toggle="tooltip" data-placement="right">
                    +
                </span>
            </button>
            <table class="">
                <thead>
                    <tr class="*:border *:text-xs *:text-center *:align-middle *:border-slate-400 *:h-7 *:text-slate-600 ">
                        <th class="w-24">#</th>
                        <th class="w-32">FECHA</th>
                        <th class="w-40">CATEGORIA</th>
                        <th class="w-28">TIPO INCIDENTE</th>
                        <th class="w-52">COMENTARIO</th>
                    </tr>
                </thead>
                <tbody v-for="i in 3" :key="i">
                    <tr class="*:border *:text-xs *:text-center *:align-middle *:border-slate-400 *:h-24 *:text-slate-600 ">
                        <td class="w-24">1</td>
                        <td class="w-32">28/2/2024</td>
                        <td class="w-40">
                            <div class="relative flex h-8 w-full ">

                                <Multiselect :filter-results="false" :resolve-on-load="false" :delay="1000"
                                    :searchable="true" :clear-on-search="true" :min-chars="1" placeholder="Categorias"
                                    :classes="{
                                        wrapper: 'relative text-xs cursor-not-allowed mx-auto w-full flex items-center justify-end box-border cursor-pointer outline-none',
                                        containerDisabled: 'cursor-not-allowed bg-gray-200 text-text-slate-400',
                                        placeholder: 'flex items-center text-center h-full absolute left-0 top-0 pointer-events-none bg-transparent leading-snug pl-3.5 text-gray-400 rtl:left-auto rtl:right-0 rtl:pl-0 rtl:pr-3.5',
                                    }" noOptionsText="<p class='text-xs'>Sin Personas<p>"
                                    noResultsText="<p class='text-xs'>Sin resultados de personas <p>" :options="[]" />
                            </div>
                        </td>
                        <td class="w-28">
                            <div class="relative flex h-8 w-full ">

                                <Multiselect :filter-results="false" :resolve-on-load="false" :delay="1000"
                                    :searchable="true" :clear-on-search="true" :min-chars="1" placeholder="Tipos" :classes="{
                                        wrapper: 'relative text-xs cursor-not-allowed mx-auto w-full flex items-center justify-end box-border cursor-pointer outline-none',
                                        containerDisabled: 'cursor-not-allowed bg-gray-200 text-text-slate-400',
                                        placeholder: 'flex items-center text-center h-full absolute left-0 top-0 pointer-events-none bg-transparent leading-snug pl-3.5 text-gray-400 rtl:left-auto rtl:right-0 rtl:pl-0 rtl:pr-3.5',
                                    }" noOptionsText="<p class='text-xs'>Sin Personas<p>"
                                    noResultsText="<p class='text-xs'>Sin resultados de personas <p>" :options="[]" />
                            </div>
                        </td>
                        <td class="w-52" contenteditable="true">

                        </td>
                    </tr>
                </tbody>
            </table>
            <button class="bg-indigo-900 rounded-sm shadow text-center text-white text-sm font-light w-full py-1 mt-5">
                TERMINAR ANALISIS DE DESEMPEÑO (Los datos se guardaran entonces)</button>
            <!-- <pre>
                {{ rubricaAndCategoriaByEvaluacion }}
            </pre> -->
        </div>
    </div>
</template>

<script>
import { useDocumentoEvaluacion } from '@/Composables/RRHH/Evaluaciones/useDocumentoEvaluacion';
import { ref, toRefs, watch } from 'vue';
import Tooltip from '@/Components-ISRI/Tooltip.vue';
import moment from 'moment';
import Swal from "sweetalert2";
import { toast } from 'vue3-toastify'
import { executeRequest } from "@/plugins/requestHelpers.js";
import 'vue3-toastify/dist/index.css';
export default {
    components: { Tooltip },
    props: {
        evaluacionPersonalProp: { // Objeto la tabla evaluacion personal
            type: Object,
            default: () => { },
        },
        rubricaAndCategoriaByEvaluacion: { // Objeto que trae las categorias y rubricas de la evaluacion seleccionada
            type: Object,
            default: () => { },
        },
        isLoadingObtenerCategoriaYRubrica: { // Prop que controla el evento de carga al request de las cateogrias y rubricass de la evaluacion
            type: Object,
            default: false,
        },
    },
    setup(props) {
        const { evaluacionPersonalProp, rubricaAndCategoriaByEvaluacion } = toRefs(props)
        const { separarTexto, evaluacionPersonal, saveResponseWhenIsClickedCheckbox, optionsSelected, sendResponsesEvaluation, ranges, isScoreInRange } = useDocumentoEvaluacion();

        return {
            separarTexto,
            optionsSelected,
            moment,
            optionsSelected,
            Tooltip,
            isScoreInRange,
            ranges,
            saveResponseWhenIsClickedCheckbox,
            evaluacionPersonal,
            sendResponsesEvaluation,
        }
    }
}
</script>

<style>
td {
    outline: none;
    /* Desactiva el marco de enfoque */
}

/* Estilos para la barra de desplazamiento en Webkit (Chrome, Safari) */
::-webkit-scrollbar {
    width: 10px;
    /* Ancho de la barra de desplazamiento */
}

::-webkit-scrollbar-thumb {
    background-color: #c1c2c5;
    /* Color de la manija de desplazamiento */
    border-radius: 5px;
    /* Radio de la manija de desplazamiento */
}

::-webkit-scrollbar-track {
    background-color: #f3f4f6;
    /* Color del fondo de la barra de desplazamiento */
}
</style>
