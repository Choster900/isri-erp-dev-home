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
                <div class="flex items-center justify-start pt-2 gap-2 pb-4">
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

                </div>
            </div>

            <button @click="addDataIndiceEvaluacion"
                style="float: right;margin-right:-4px;margin-top:0.1px; font-size: 30px; padding: 0 10px; border: 0; background-color: transparent;">
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
                <tbody v-for="(incidente, i) in dataIndiceEvaluacion" :key="i">
                    <tr @contextmenu.prevent="deleteRow(incidente.idIncidenteEvaluacion)" v-if="!incidente.isDeleted"
                        class="*:border *:text-xs *:text-center *:align-middle *:border-slate-400 *:h-24 *:text-slate-600 ">
                        <td class="w-24">{{ i + 1 }}</td>
                        <td class="w-32">{{ incidente.fechaRegIncidenteEvaluacion }}</td>
                        <td class="w-40">
                            <div class="relative flex h-8 w-full ">

                                <Multiselect :filter-results="true" v-model="incidente.idCategoriaRendimiento"
                                    :searchable="true" :clear-on-search="true" placeholder="Categorias" :classes="{
                                        wrapper: 'relative text-xs cursor-not-allowed mx-auto w-full flex items-center justify-end box-border cursor-pointer outline-none',
                                        containerDisabled: 'cursor-not-allowed bg-gray-200 text-text-slate-400',
                                        placeholder: 'flex items-center text-center h-full absolute left-0 top-0 pointer-events-none bg-transparent leading-snug pl-3.5 text-gray-400 rtl:left-auto rtl:right-0 rtl:pl-0 rtl:pr-3.5',
                                    }" noOptionsText="<p class='text-xs'>Sin resultados<p>"
                                    noResultsText="<p class='text-xs'>Sin resultados  <p>"
                                    :options="opcionesCategoriaRendimiento" />
                            </div>
                        </td>
                        <td class="w-28">
                            <div class="relative flex h-8 w-full ">
                                <Multiselect :filter-results="true" v-model="incidente.resultadoIncidenteEvaluacion"
                                    :searchable="true" :clear-on-search="true" placeholder="Tipos" :classes="{
                                        wrapper: 'relative text-xs cursor-not-allowed mx-auto w-full flex items-center justify-end box-border cursor-pointer outline-none',
                                        containerDisabled: 'cursor-not-allowed bg-gray-200 text-slate-400',
                                        placeholder: 'flex items-center text-center h-full absolute left-0 top-0 pointer-events-none bg-transparent leading-snug pl-3.5 text-gray-400 rtl:left-auto rtl:right-0 rtl:pl-0 rtl:pr-3.5',
                                    }" noOptionsText="<p class='text-xs'>Sin resultados<p>"
                                    noResultsText="<p class='text-xs'>Sin resultados  <p>"
                                    :options="[{ value: 0, label: 'F - favorable' }, { value: 1, label: 'D - desfavorable' }]" />
                            </div>

                        </td>
                        <td class="w-52" contenteditable="true"
                            @input="incidente.comentarioIncidenteEvaluacion = $event.target.innerText"> {{
                                incidente.comentarioIncidenteEvaluacion }}</td>
                    </tr>
                </tbody>
            </table>
            <button @click="guardarYEnviarIncidentes"
                class="bg-indigo-900 rounded-sm shadow text-center text-white text-sm font-light w-full py-1 mt-5">
                TERMINAR ANALISIS DE DESEMPEÑO (Los datos se guardaran entonces)</button>
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
import { useAnalisisDes } from '@/Composables/RRHH/Evaluaciones/useAnalisisDes'

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
        const { separarTexto } = useDocumentoEvaluacion();
        const { opcionesCategoriaRendimiento, objectCat, dataIndiceEvaluacion, addDataIndiceEvaluacion, deleteRow, saveIncidentesEvaluacionesRequest } = useAnalisisDes(evaluacionPersonalProp)

        watch(rubricaAndCategoriaByEvaluacion, (newValue, oldValue) => {
            if (newValue != '') {
                objectCat.value = newValue.categorias_rendimiento
            } else {
                objectCat.value = null;
            }
        })

        watch(evaluacionPersonalProp, (newValue, oldValue) => {
            if (newValue !== null && typeof newValue === 'object' && 'data' in newValue) {
                const { incidentes_evaluacion } = newValue.data;
                dataIndiceEvaluacion.value = []

                incidentes_evaluacion.forEach(element => {
                    dataIndiceEvaluacion.value.push({
                        idEvaluacionPersonal: evaluacionPersonalProp.value.data.id_evaluacion_personal,
                        idCategoriaRendimiento: element.id_cat_rendimiento,
                        resultadoIncidenteEvaluacion: element.resultado_incidente_evaluacion,
                        comentarioIncidenteEvaluacion: element.comentario_incidente_evaluacion,
                        fechaRegIncidenteEvaluacion: element.fecha_reg_incidente_evaluacion,
                        idIncidenteEvaluacion: element.id_incidente_evaluacion,
                        isDeleted: false,
                    })

                });
            }
        });


        const guardarYEnviarIncidentes = async () => {
            const confirmed = await Swal.fire({
                title: '<p class="text-[16pt] text-center">¿Esta seguro de enviar los incidentes?</p>',
                icon: "question",
                iconHtml: `<lord-icon src="https://cdn.lordicon.com/enzmygww.json" trigger="loop" delay="500" colors="primary:#121331" style="width:100px;height:100px"></lord-icon>`,
                confirmButtonText: "Si, enviar",
                confirmButtonColor: "#001b47",
                cancelButtonText: "Cancelar",
                showCancelButton: true,
                showCloseButton: true,
            });
            if (confirmed.isConfirmed) {

                executeRequest(
                    saveIncidentesEvaluacionesRequest(),
                    "La evaluacion se ha enviado"
                );

            }
        };

        return {
            deleteRow,
            separarTexto,
            opcionesCategoriaRendimiento,
            objectCat,
            guardarYEnviarIncidentes,
            addDataIndiceEvaluacion,
            dataIndiceEvaluacion,
            saveIncidentesEvaluacionesRequest,

            moment,
            Tooltip,
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
